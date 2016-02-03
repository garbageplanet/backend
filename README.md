# README #

### What is this repository for? ###

Repository for the garbagepla.net api (made with Laravel 5.1). You can visit the front end [here](http://www.garbagepla.net).

### TODO

### Models

- [ ] add regex checks for fields in all controllers.
- [ ] make join and confirm controllers

#### Licence
This code is available under the MIT licence, see [the license file](https://github.com/garbageplanet/api/blob/dev/license.md) for more details.

### Current unctionalities

api access is at http://dev.garbagepla.net/api

- create trash POST: /api/trashes
- get trash GET: /api/trashes/{id}
- delete trash DELETE: /api/trashes/{id}
- get trashes within bounds (coordinates: ne, sw) GET: /api/trashes/withinbounds?bounds=({ne_lat, ne_lng, sw_lat, sw_lng})
- authenticate user POST: /api/authenticate
- register user POST: /api/register
- get user details of authenticated user GET: /api/authenticate/user