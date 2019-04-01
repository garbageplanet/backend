const Joi = require('joi');
const validate = require('koa-joi-validate');

const featureValidator = validate({
      headers : {
      }
    , query: {
        type: Joi.string().required()
      }
    , body: {
          lat: Joi.string().required()
        , lng: Joi.string().required()
        //, TODO add feature dependant validators 
        // latlng 'regex:/^([-+]?\d{1,2}[.]\d+)\s*,\s*([-+]?\d{1,3}[.]\d+)$/u'
        // polyline 'regex:/^(\[([-+]?\d{1,2}[.]\d+)\s*,\s*([-+]?\d{1,3}[.]\d+)\]\s*,?)+$/u'
    }
});

module.exports = {
    feature : featureValidator
}
