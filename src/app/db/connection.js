                    require('dotenv').config();
const config      = require('../../../knexfile.js')[process.env.NODE_ENV];
const knex        = require('knex');
const knexpostgis = require('knex-postgis');

const db = knex(config);
const st = knexpostgis(db);

module.exports = {
    db : db,
    st: st
}