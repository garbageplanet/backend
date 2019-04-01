                 require('dotenv').config();
const Router   = require('koa-router');
const passport = require('koa-passport');
const queries  = require('../db/queries/users');
const helpers  = require('./helpers');
const jwt      = require('jsonwebtoken');
const router   = new Router();

router.post('/auth/register', async (ctx) => {

  const create_user = await queries.addUser(ctx.request.body);

  if (create_user) {
    return passport.authenticate('local', (err, user, info, status) => {

      if ( user && !err ) {
        ctx.login(user);

        const jwt_body = { _id: user.id, username: user.username };
        const token = jwt.sign({ user: jwt_body }, process.env.JWT_KEY);

        ctx.body = { 
          status : 'success',
          data   : token 
        };

      } else {
        ctx.throw(400);
      }
    })(ctx)
  } else {
    ctx.throw(500);
  }
});

router.post('/auth/login', async (ctx) => {
  return passport.authenticate('local', (err, user, info, status) => {
    if ( user && !err ) {

      ctx.login(user);

      const jwt_body = { id: user.id, username: user.username };
      const token    = jwt.sign({ user: jwt_body }, process.env.JWT_KEY);

      ctx.body = { 
        status : 'success',
        data : token
      };

    } else {
      ctx.status = 400;
      ctx.body = { status: 'error' };
    }
  })(ctx);
});

router.get('/auth/logout', helpers.ensureAuthenticated ,async (ctx) => {
    ctx.logout();
    ctx.status = 200;
});

router.get('/auth/status', helpers.ensureAuthenticated, helpers.ensureAuthorized, async (ctx) => {
    ctx.status = 200;
});

module.exports = router;