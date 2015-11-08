***Roska project demo API***

Made with Laravel 5.1

Aim is to provide simple api for the Roska UI app.

***Functionalities done:***
- create trash POST: /api/trashes
- get trash GET: /api/trashes/{id}
- delete trash DELETE: /api/trashes/{id}
- get trashes in bounds (coordinates: ne, sw) GET: /api/trashes/withinbounds?bounds=({ne_lat, ne_lng, sw_lat, sw_lng})
- authenticate user POST: /api/authenticate
- register user POST: /api/register
- get user details of authenticated user GET: /api/authenticate/user

Authentication is made with JWT authentication tokens

***Integrations:***

- send garbage details to City of Helsinki Issue Reporting Service API (Service code: 246)
- receive garbage data from CoHIRS API


