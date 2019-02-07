const path = require('path');

require('dotenv').config();

const BASE_PATH = path.join(__dirname, 'src', 'app', 'db');

module.exports = {
    development: {
        debug: true,
        client: 'pg',
        searchPath: ['knex', 'public'],
        connection: {
            host: process.env.DB_HOST,
            port: process.env.DB_PORT,
            database: process.env.DB_NAME,
            user: process.env.DB_USER,
            password: process.env.DB_PASSWORD
                },
        migrations: {
            directory: path.join(BASE_PATH, 'migrations')
        },
        seeds: {
            directory: path.join(BASE_PATH, 'seeds')
        }
    }
};