exports.up = function (knex, Promise) {
    return Promise.all([
        knex.schema.createTable('garbages', function (table) {

            table.increments('id').primary();
            table.integer('created_by').references('users.id').nullable();
            table.integer('cleaned_by').references('users.id').nullable();
            table.integer('updated_by').references('users.id').nullable();
            table.decimal('lat', 9, 6).notNullable(); // should be specificType('double precision', ...)
            table.decimal('lng', 9, 6).notNullable();
            table.specificType('geom', 'geometry(point, 4326)');
            table.string('imageurl').nullable();
            table.integer('todo').notNullable();
            table.integer('amount').notNullable().defaultTo(3);
            table.text('note').nullable();
            table.text('types').nullable();
            table.boolean('cleaned').notNullable().defaultTo(false);
            table.timestamps();
        }),
        knex.schema.createTable('litters', function (table) {
            table.increments('id').primary();
            table.integer('created_by').references('users.id');
            table.integer('updated_by').references('users.id');
            table.integer('cleaned_by').references('users.id');
            table.string('latlngs').notNullable();
            table.specificType('geom', 'geometry(linestring, 4326)');
            table.decimal('lphys_len', 2).nullable();
            table.string('imageurl').nullable();
            table.integer('todo').notNullable();
            table.integer('amount').notNullable().defaultTo(3);
            table.text('note').nullable();
            table.integer('confirms').nullable();
            table.boolean('cleaned').nullable();
            table.timestamps();
        }),
        knex.schema.createTable('cleanings', function (table) {
            table.increments('id').primary();
            table.integer('created_by').references('users.id');
            table.integer('updated_by').references('users.id');
            table.decimal('lat', 9, 6).notNullable();
            table.decimal('lng', 9, 6).notNullable();
            table.specificType('geom', 'geometry(point, 4326)');
            table.datetime('datetime').notNullable();
            table.integer('recurrence').notNullable();
            table.text('note').nullable();
            table.integer('attends').nullable();
            table.timestamps();
        }),
        knex.schema.createTable('areas', function (table) {
            table.increments('id').primary();
            table.integer('created_by').references('users.id');
            table.integer('updated_by').references('users.id');
            table.integer('cleaned_by').references('users.id');
            table.string('latlngs').notNullable();
            table.specificType('geom', 'geometry(polygon, 4326)');
            table.decimal('area', 10, 8).nullable();
            table.integer('title', 12).unique().notNullable();
            table.text('note').nullable();
            table.timestamps();
        }),
        knex.schema.createTable('types', function (table) {
            table.increments('id');
            table.integer('garbage_id').references('garbages.id').nullable();
            table.string('name').nullable();
            table.timestamps();
        }),

        knex.schema.createTable('attends', function (table) {
            table.increments('id').primary();
            table.integer('feature_id').references('id').inTable('cleanings');
            table.integer('user_id').references('users.id');
            table.timestamps();
        }),
        knex.schema.createTable('cleans', function (table) {
            table.increments('id').primary();
            table.integer('feature_id').references('id').inTable('garbages');
            table.integer('user_id').references('users.id');
            table.timestamps();
        }),
        knex.schema.createTable('confirms', function (table) {
            table.increments('id').primary();
            table.integer('feature_id').references('id').inTable('garbages');
            table.integer('user_id').references('users.id');
            table.timestamps();
        }),
        knex.schema.createTable('trashtypes', function(table) {
            table.increments('id').primary();
            table.string('short').nullable();
            table.string('long').nullable();
            table.timestamps();
        })
    ]);
};

exports.down = function (knex, Promise) {
    return Promise.all([
        knex.schema.dropTable('garbages'),
        knex.schema.dropTable('litters'),
        knex.schema.dropTable('cleanings'),
        knex.schema.dropTable('cleans'),
        knex.schema.dropTable('confirms'),
        knex.schema.dropTable('attends'),
    ]);
};