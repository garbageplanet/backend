# README #

### What is this repository for? ###
Repository for the garbagepla.net api (made with Laravel 5.1). You can visit the front end [here](http://www.garbagepla.net).

### TODO

#### Models & controllers
- [ ] make route for remove user account
- [ ] make `Join` controller and model for joining cleaning events
- [ ] make `Confirm` controller and model for confirming presence of garbage
- [ ] make `Game` controller and model to restrict user action in an area
- [ ] make `Access` controller and model for user to join games
- [ ] add new public functions to the `User.php` model and edit the current ones to match the new routes/models
- [ ] make db migrations for new routes/models
- [ ] only creator can edit / delete own data
- [ ] GET / POST lat lngs as single field (lat.lat,lng.lng) or in brackets
- [ ] add regex checks for fields in all controllers

#### Network
- [ ] set CORS so that the api is accessible only from garbagepla.net for now

### Licence
This code is available under the MIT licence, see [the license file](https://github.com/garbageplanet/api/blob/dev/license.md) for more details.

### Current functionalities
api access is at http://dev.garbagepla.net/api

- create trash POST: /api/trashes
- get trash GET: /api/trashes/{id}
- delete trash DELETE: /api/trashes/{id}
- get trashes within bounds (coordinates: ne, sw) GET: /api/trashes/withinbounds?bounds=({ne_lat, ne_lng, sw_lat, sw_lng})
- authenticate user POST: /api/authenticate
- register user POST: /api/register
- get user details of authenticated user GET: /api/authenticate/user