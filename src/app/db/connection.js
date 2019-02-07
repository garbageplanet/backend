const environment = process.env.NODE_ENV || 'development';
const config      = require('../../../knexfile.js')[environment];
const knex        = require('knex');
const knexpostgis = require('knex-postgis');

const db = knex(config);
const st = knexpostgis(db);

module.exports = {
    db : db,
    st: st
}