<?php
/**
 * PRESENTATION LAYER - categories
 * OpenAPI documentation: public/v1/docs/paths/CategoryPaths.php
 */

Flight::route('GET /categories', function () {
    Flight::json(Flight::category_service()->get_all());
});

Flight::route('GET /categories/@id', function ($id) {
    Flight::json(Flight::category_service()->get_by_id(route_entity_id($id)));
});

Flight::route('POST /categories', function () {
    Flight::json(Flight::category_service()->add(route_read_json()), 201);
});

Flight::route('PUT /categories/@id', function ($id) {
    Flight::json(Flight::category_service()->update(route_read_json(), route_entity_id($id)));
});

Flight::route('PATCH /categories/@id', function ($id) {
    Flight::json(Flight::category_service()->update(route_read_json(), route_entity_id($id)));
});

Flight::route('DELETE /categories/@id', function ($id) {
    Flight::category_service()->delete(route_entity_id($id));
    Flight::json(['message' => 'category deleted.']);
});
