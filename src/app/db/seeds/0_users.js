const bcrypt = require('bcryptjs');

exports.seed = (knex, Promise) => {
  return knex('users').del()
  .then(() => {
    const salt = bcrypt.genSaltSync();
    const hash = bcrypt.hashSync('123456', salt);
    return Promise.join(
      knex('users').insert({
        username: 'vadim',
        email: 'vadim@sap.com',
        password: hash
      })
    );
  })
  .then(() => {
    const salt = bcrypt.genSaltSync();
    const hash = bcrypt.hashSync('123456', salt);
    return Promise.join(
      knex('users').insert({
        username: 'rob',
        email : 'rob@test.com',
        password: hash
      })
    );
  });
};
