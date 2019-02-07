define({ "api": [
  {
    "type": "get",
    "url": "/",
    "title": "Request all map feature data of a given type",
    "name": "GetAllFeatures",
    "group": "Feature",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"garbage\"",
              "\"litter\"",
              "\"area\"",
              "\"cleaning\""
            ],
            "optional": false,
            "field": "type",
            "description": "<p>Feature type name.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "access-key",
            "description": "<p>Users unique access-key</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "result",
            "description": "<p>A collection of single feature data as JSON.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n[{\n  \"type\": \"garbage\",\n  \"lat\": 30.0000,\n  \"lng\" : 25.0000\n}]",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "FeatureTypeNotFound",
            "description": "<p>There is no feature of that type.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "FeatureNotFound",
            "description": "<p>There is no data for that feature type.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"FeatureNotFound\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/app/routes/features.js",
    "groupTitle": "Feature"
  },
  {
    "type": "get",
    "url": "/in",
    "title": "Request all map feature data of a given type for a given bounding box",
    "name": "GetAllFeaturesWithinBounds",
    "group": "Feature",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"garbage\"",
              "\"litter\"",
              "\"area\"",
              "\"cleaning\""
            ],
            "optional": false,
            "field": "type",
            "description": "<p>Feature type name.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "size": "-180-180",
            "optional": false,
            "field": "minlon",
            "description": "<p>Bunding box geographical coordinate.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "size": "-90-90",
            "optional": false,
            "field": "minlat",
            "description": "<p>Bunding box geographical coordinate.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "size": "-180-180",
            "optional": false,
            "field": "maxlon",
            "description": "<p>Bunding box geographical coordinate.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "size": "-90-90",
            "optional": false,
            "field": "maxlat",
            "description": "<p>Bunding box geographical coordinate.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "access-key",
            "description": "<p>Users unique access-key</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "result",
            "description": "<p>A collection of map feature of a given type data as JSON.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "FeatureTypeNotFound",
            "description": "<p>There is no feature of that type.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "FeatureNotFound",
            "description": "<p>There is no data for that feature type.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 404 Not Found\n{\n  \"error\": \"FeatureNotFound\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/app/routes/features.js",
    "groupTitle": "Feature"
  }
] });
