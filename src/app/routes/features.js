                require('dotenv').config()
const Router  = require('koa-router');
const queries = require('../db/queries/features');
const validate = require('../validation');

const router = new Router();
const BASE_URL = process.env.API_BASE_URL;

const ERROR_NOT_EXIST = {
  status: 'error',
  message: 'The map feature does not exist.'
};

/**
 * Pluralize the map feature type for easier reference
 * inside the database calls (e.g. garbage --> garbages)
 */
router.use(async function pluralizeFeatureType(context, next) {
  context.query.type ? context.query.type = context.query.type + 's' : null;
  await next();
});

/**
 * @api {get} / Request all map feature data of a given type
 * @apiName GetAllFeatures
 * @apiGroup Feature
 * @apiParam {String="garbage", "litter", "area","cleaning"} type Feature type name.
 * @apiHeader {String} access-key User unique access-key
 * @apiSuccess {Array} result A collection of feature data as JSON.
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     [{
 *       "type": "garbage",
 *       "lat": 30.0000,
 *       "lng" : 25.0000
 *     }]
 * @apiError FeatureTypeNotFound There is no feature of that type.
 * @apiError FeatureNotFound There is no data for that feature type.
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "FeatureNotFound"
 *     }
 */
router.get(`${BASE_URL}`, async (ctx) => {
  try {
    const features = await queries.getAll(ctx.query.type);
    ctx.body = {
      status: 'success',
      data: features
    };
  } catch (err) {
    console.log(err)
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
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 * @apiError FeatureTypeNotFound There is no feature of that type.
 * @apiError FeatureNotFound There is no data for that feature type.
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "FeatureNotFound"
 *     }
 */
router.get(`${BASE_URL}/in`, async (ctx) => {

  try {
    const features = await queries.withinBounds(ctx.query.type, ctx.query.minlon, ctx.query.minlat, ctx.query.maxlon, ctx.query.maxlat);
    
    console.info('Features in db', features);

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

router.get(`${BASE_URL}/:id`, async (ctx) => {
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
router.get(`${BASE_URL}/:id/:method`, async (ctx) => {
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
router.post(`${BASE_URL}`/*, validate.feature*/, async (ctx) => {
  try {
    const feature = await queries.add(ctx.request.body, ctx.query.type, ctx.user.id);
    const geom = await queries.addGeom(feature.id, ctx.query.type, ctx.request.body.latlngs, ctx.user.id)

    if (movie.length && geom.length) {
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

router.put(`${BASE_URL}/:id`/*, validate.feature*/, async (ctx) => {
  try {
    const feature = await queries.update(ctx.params.id, ctx.request.body, ctx.query.type, ctx.user.id);
    const geom = await queries.addGeom(ctx.params.id, ctx.request.body.latlngs, ctx.query.type, ctx.user.id);
    if (feature.length && geom.length) {
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

router.delete(`${BASE_URL}/:id`, async (ctx) => {
  try {
    const id = await queries.delete(ctx.params.id, ctx.query.type, ctx.user.id);
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
      message: err.message || 'Sorry, an error has occurred.'
    };
  }
})

module.exports = router;
