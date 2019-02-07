exports.seed = (knex, Promise) => {
  return knex('garbages').del()
  .then(() => {
    return knex('garbages').insert(
      {
        created_by: 1,
        cleaned_by: 1,
        updated_by: 1,
        lat: 60.11,
        lng: 24.5,
        imageurl: "www.placehold.it",
        todo: 2,
        amount: 1,
        note: "Yes this is dog",
        cleaned: false,
        types: "plastic,bags,pet"
  });
})
  .then(() => {
    return knex('garbages').insert(
      {
        created_by: 1,
        cleaned_by: 1,
        updated_by: 1,
        lat: 60.114,
        lng: 24.54,
        imageurl: "www.placehold.it",
        todo: 2,
        amount: 3,
        note: "Yes this is dog",
        cleaned: false,
        types: "plastic,bags,pet"
  });
})
  .then(() => {
    return knex('garbages').insert(
      {
        created_by: 1,
        cleaned_by: 1,
        updated_by: 1,
        lat: 60.112,
        lng: 24.52,
        imageurl: "www.placehold.it",
        todo: 2,
        amount: 4,
        note: "Yes this is dog",
        cleaned: false,
        types: "plastic,bags,pet"
      });
    })
    .then(() => {
        return knex('garbages').insert(
      {
        created_by: 1,
        cleaned_by: 1,
        updated_by: 1,
        lat: 60.115,
        lng: 24.55,
        imageurl: "www.placehold.it",
        todo: 2,
        amount: 5,
        note: "Yes this is dog",
        cleaned: false,
        types: "plastic,bags,pet"
      });
    })   
};
