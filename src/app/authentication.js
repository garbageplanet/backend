                      require('dotenv');
const passport      = require('koa-passport');
const LocalStrategy = require('passport-local').Strategy;
const JWTstrategy   = require('passport-jwt').Strategy;
const ExtractJWT    = require('passport-jwt').ExtractJwt;
const bcrypt        = require('bcryptjs');
const knex          = require('./db/connection');

function comparePass(userPassword, databasePassword) {
  return bcrypt.compareSync(userPassword, databasePassword);
}

passport.serializeUser((user, done) => { 
  done(null, user.id); 
});

passport.deserializeUser((id, done) => {
  return knex.db('users').where({id: parseInt(id)}).first()
  .then((user) => { 
    done(null, user); })
  .catch((err) => { 
    done(err,null); });
});

passport.use(new LocalStrategy({}, (username, password, done) => {

  knex.db('users').where({ username }).first()
  .then((user) => {
    if ( !user ) { 
      return done(null, false);
    }

    if ( !comparePass(password, user.password) ) {
      return done(null, false);
    } else {
      return done(null, user);
    }
  })
  .catch((err) => {
    return done(err);
  });
}));

passport.use(new JWTstrategy({
  secretOrKey: process.env.JWT_KEY,
  jwtFromRequest: ExtractJWT.fromAuthHeaderAsBearerToken()
}, async (token, done) => {
  try {
    return done(null, token.user);
  } catch (error) {
    done(error);
  }
}));
