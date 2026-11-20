<?php
/**
 * PRESENTATION LAYER - users
 * OpenAPI documentation: public/v1/docs/paths/UserPaths.php
 */

Flight::route('GET /users/by-email/@email', function ($email) {
    Flight::json(Flight::user_service()->get_by_email($email));
});

Flight::route('GET /users', function () {
    Flight::json(Flight::user_service()->get_all());
});

Flight::route('GET /users/@id', function ($id) {
    Flight::json(Flight::user_service()->get_by_id(route_entity_id($id)));
});

Flight::route('POST /users', function () {
    Flight::json(Flight::user_service()->add(route_read_json()), 201);
});

Flight::route('PUT /users/@id', function ($id) {
    Flight::json(Flight::user_service()->update(route_read_json(), route_entity_id($id)));
});

Flight::route('PATCH /users/@id', function ($id) {
    Flight::json(Flight::user_service()->update(route_read_json(), route_entity_id($id)));
});

Flight::route('DELETE /users/@id', function ($id) {
    Flight::user_service()->delete(route_entity_id($id));
    Flight::json(['message' => 'user deleted.']);
});
