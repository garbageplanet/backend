const knex = require('../connection');

function getAllFeatures(type) {
  return knex.db.select().from(type);
}

function makeGeometry(id, type, latlngs, userid) {
  let query = type === 'garbages' || 'cleanings' ? 
    `UPDATE ${type} SET geom = ST_SetSRID(ST_MakePoint(${latlngs}), 4326) WHERE id = ${id} AND created_by = ${userid};` : type === 'litters' ? 
    `UPDATE ${type} SET geom = ST_SetSRID(ST_GeomFromText('LINESTRING(${latlngs})'), 4326) WHERE id = ${id} AND created_by = ${userid};` :
    `UPDATE ${type} SET geom = ST_SetSRID(ST_MakePolygon(ST_GeomFromText('LINESTRING(${latlngs})')), 4326) WHERE id = ${id} AND created_by = ${userid};`;
  return knex.db.raw( query, {latlngs, id, type});
}

function getAllFeaturesWithinBounds(type, minlon, minlat, maxlon, maxlat) {
  return knex.db.select('*', knex.st.makeEnvelope(minlon, minlat, maxlon, maxlat)).from(type)
}

function getOneFeature(id, type) {
  return knex.db(type)
  .where({ id: parseInt(id) })
  .first();
}

function addFeature(feature, type, userid) {
  let f = feature;
  f.created_by = parseInt(userid)
  return knex.db(type)
  .insert(f)
  .returning('*');
}

function updateFeature(id, feature, type, userid) {
  let f = feature;
  f.updated_by = parseInt(userid);
  return knex.db(type)
    .where({ id: parseInt(id) })
    .update(f)
    .returning('*');
}

function confirmFeature(id, type, userid) {
  return knex.db('confirms')
    .insert({ feature_id: parseInt(id), feature_type: type, user_id: parseInt(userid) })
   // .where({ id: parseInt(id) })
   // .increment('confirms',1)
    .returning('*');
}

function attendCleaning(id, type, userid) {
  return knex.db('attends')
    .insert({ feature_id: parseInt(id), user_id: parseInt(userid)})
    //.where({ d: parseInt(id) })
    // .and({u: type})
    //.increment('attends', 1)
    .returning('*');
}

function cleanFeature(id, type, userid) {

  const query = `UPDATE ONLY ${type} SET cleaned = NOT cleaned WHERE id = ${id}`

  return knex.db(type)
    .raw(query)
    .update({ cleaned_by: parseInt(userid) })
    .returning('*');
}

function deleteFeature(id, type, userid) {
  return knex.db(type)
  .del()
  .where({ id: parseInt(id), created_by: parseInt(userid) })
  .returning('id');
}

module.exports = {
    add          : addFeature
  , attend       : attendCleaning
  , clean        : cleanFeature
  , confirm      : confirmFeature
  , delete       : deleteFeature
  , getAll       : getAllFeatures
  , withinBounds : getAllFeaturesWithinBounds
  , getOne       : getOneFeature
  , addGeom      : makeGeometry
  , update       : updateFeature
};
