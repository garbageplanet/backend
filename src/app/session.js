                   require('dotenv').config();
const redisStore = require('koa-redis');

const options = { 
    auth_pass : process.env.REDIS_PASSWORD,
    //password  : process.env.REDIS_PASSWORD,
    host      : process.env.REDIS_HOST,
    port      : process.env.REDIS_PORT,
    db        : process.env.REDIS_DB,
    retry_strategy: function (options) {
        if (options.error && options.error.code === 'ECONNREFUSED') {
            // End reconnecting on a specific error and flush all commands with
            // a individual error
            return new Error('The server refused the connection');
        }
        if (options.total_retry_time > 1000 * 60 * 60) {
            // End reconnecting after a specific timeout and flush all commands
            // with a individual error
            return new Error('Retry time exhausted');
        }
        if (options.attempt > 10) {
            // End reconnecting with built in error
            return undefined;
        }
        // reconnect after
        return Math.min(options.attempt * 100, 3000);
    }
};

module.exports = new redisStore(options);
