const passport = require('koa-passport');

async function ensureAuthenticated(ctx, next) {
  if (ctx.isAuthenticated()) {
    await next();
  } else {
    ctx.throw(401);
  }
}

async function ensureAuthorized(ctx, next) {
  try {
    passport.authenticate('jwt', { session: false });
    await next();
  } catch (e) {
    ctx.throw(401);
  }
}

module.exports = {
  ensureAuthenticated: ensureAuthenticated,
  ensureAuthorized : ensureAuthorized
};