                      require('dotenv').config();
const Koa           = require('koa');
const bodyParser    = require('koa-bodyparser');
const cors          = require('@koa/cors');
const session       = require('koa-session');
const passport      = require('koa-passport');
const indexRoutes   = require('./routes/index');
const featureRoutes = require('./routes/features');
const authRoutes    = require('./routes/auth');
const store         = require('./session');
                      require('./auth'); 
                      
const app           = new Koa();
const cors_options = {
    origin: ["http://127.0.0.1:5500"],
    allowMethods: 'GET,HEAD,PUT,POST',
    //allowHeaders : null,
    //maxAge: null
};

app.keys = [process.env.APP_KEY];
app.use(cors(cors_options));
app.use(session({ store }, app));
app.use(bodyParser());
app.use(passport.initialize());
app.use(passport.session());
app.use(indexRoutes.routes());
app.use(featureRoutes.routes());
app.use(authRoutes.routes());

const server = app.listen(process.env.PORT, () => {
    console.log(`Server listening on port: ${process.env.PORT}`);
});

module.exports = server;