                 require('dotenv').config()
const helpers   = require('./helpers');
const queries  = require('../db/queries/features');
const Router   = require('koa-router');
const validate = require('../validation');

const router = new Router({
  prefix: '/api/v1/features'
});

const ERROR_NOT_EXIST = {
  status  : 'error',
  message : 'The map feature does not exist.'
};

/**
 * Pluralize the map feature type for easier reference
 * inside the database calls (e.g. garbage --> garbages)
 */
router.use(async function pluralizeFeatureType(ctx, next) {
  ctx.query.type = ctx.query.type ? ctx.query.type + 's' : null;
  await next();
});

/**
 * @api {get} / Request all map feature data of a given type
 * @apiName GetAllFeatures
 * @apiGroup Feature
 * @apiParam {String="garbage", "litter", "area","cleaning"} type Feature type name.
 * @apiHeader {String} access-key User unique access-key
 * @apiSuccess {Array} result A collection of feature data as JSON.
 * @apiError FeatureTypeNotFound There is no feature of that type.
 * @apiError FeatureNotFound There is no data for that feature type.
 */
router.get('/', helpers.ensureAuthenticated, helpers.ensureAuthorized, async (ctx) => {
  try {
    const features = await queries.getAll(ctx.query.type);
    ctx.body = {
      status: 'success',
      data: features
    };
  } catch (err) {
    ctx.status = 404
  }
})
/**
 * @api {get} /in Request all map feature data of a given type for a given bounding box
 * @apiName GetAllFeaturesWithinBounds
 * @apiGroup Feature
 * @apiParam {String="garbages", "litters", "areas","cleanings"} type Feature type name.
 * @apiParam {Number{-180-180}} minlon Bunding box geographical coordinate.
 * @apiParam {Number{-90-90}} minlat Bunding box geographical coordinate.
 * @apiParam {Number{-180-180}} maxlon Bunding box geographical coordinate.
 * @apiParam {Number{-90-90}} maxlat Bunding box geographical coordinate.
 * @apiHeader {String} access-key Users unique access-key
 * @apiSuccess {Array} result A collection of map feature of a given type data as JSON.
 * @apiError FeatureTypeNotFound There is no feature of that type.
 * @apiError FeatureNotFound There is no data for that feature type.
 */
router.get('/in', async (ctx) => {

  try {
    const features = await queries.withinBounds(
        ctx.query.type
      , ctx.query.minlon
      , ctx.query.minlat
      , ctx.query.maxlon
      , ctx.query.maxlat
    );
    
    if ( features.length > 0 ) {

      features.forEach(function (feature) {
        delete feature.geom;
        delete feature.st_makeenvelope;
      });

      ctx.body = {
          status   : 'success' 
        , dataType : ctx.query.type.slice(0,-1)
        , data     : features
      };

    } else {
      ctx.body = {
          status   : 'no data'
        , dataType : ctx.query.type.slice(0,-1)
      };
    }

  } catch (err) {
    console.log(err)
    ctx.status = 404
  }
})

router.get('/:id', async (ctx) => {
  try {
    const feature = await queries.getOne(ctx.params.id, ctx.query.type);
    if (feature.length) {
      ctx.body = {
        status: 'success',
        data: feature
      };
    } else {
      ctx.status = 404;
      ctx.body = ERROR_NOT_EXIST;
    }
  } catch (err) {
    console.log(err)
  }
})

/**
 * Features action endpoint
 * methods: [confirm, clean, attend, ...]
 */
router.get('/:id/:method', helpers.ensureAuthorized, async (ctx) => {
  try {
    const feature = await queries[ctx.params.method](ctx.params.id, ctx.query.type, ctx.user.id);
    if (feature.length) {
      ctx.body = {
        status: 'success',
        data: feature
      };
    } else {
      ctx.status = 404;
      ctx.body = ERROR_NOT_EXIST;
    }
  } catch (err) {
    console.log(err)
  }
})

/**
 * Feature creation endpoint
 */
router.post('/', validate.feature, helpers.ensureAuthenticated, helpers.ensureAuthorized, async (ctx) => {

  try {
    const feature = await queries.add(ctx.request.body, ctx.query.type, ctx.state.user.id);
    const latlngs = (ctx.query.type == ('garbages' || 'cleanings')) ? `${feature[0].lat},${feature[0].lng}` : feature[0].latlngs
    const geom = await queries.addGeom(feature[0].id, ctx.query.type, latlngs, ctx.state.user.id)

    if ( feature && geom.rowCount > 0 ) {

      delete feature.geom

      ctx.status = 201;
      ctx.body = {
        status: 'success',
        data: feature
      };
      
    } else {
      ctx.status = 400;
      ctx.body = {
        status: 'error',
        message: 'Something went wrong.'
      };
    }
  } catch (err) {
    ctx.status = 400;
    ctx.body = {
      status: 'error',
      message: err.message || 'Sorry, an error has occurred.'
    };
  }
})

router.put('/:id'/*, validate.feature*/, helpers.ensureAuthenticated, helpers.ensureAuthorized, async (ctx) => {
  try {
    const feature = await queries.update(ctx.params.id, ctx.request.body, ctx.query.type, ctx.state.user.id);
    const geom = await queries.addGeom(ctx.params.id, ctx.request.body.latlngs, ctx.query.type, ctx.state.user.id);
    if (feature && geom.rowCount > 0) {
      ctx.status = 200;
      ctx.body = {
        status: 'success',
        data: feature
      };
    } else {
      ctx.status = 404;
      ctx.body = ERROR_NOT_EXIST;
    }
  } catch (err) {
    ctx.status = 400;
    ctx.body = {
      status: 'error',
      message: err.message || 'Sorry, an error has occurred.'
    };
  }
})

router.delete('/:id', helpers.ensureAuthenticated, helpers.ensureAuthorized, async (ctx) => {
  try {
    const id = await queries.delete(ctx.params.id, ctx.query.type, ctx.state.user.id);
    if (id) {
      ctx.status = 200;
      ctx.body = {
        status: 'success',
        data: id
      };
    } else {
      ctx.status = 404;
      ctx.body = ERROR_NOT_EXIST;
    }
  } catch (err) {
    ctx.status = 400;
    ctx.body = {
      status: 'error',
      message: err.message || 'Internal server.'
    };
  }
})

module.exports = router;
