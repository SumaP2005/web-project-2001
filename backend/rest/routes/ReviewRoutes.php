<?php
/**
 * PRESENTATION LAYER - reviews
 * OpenAPI documentation: public/v1/docs/paths/ReviewPaths.php
 */

Flight::route('GET /reviews/by-item/@id', function ($id) {
    Flight::json(Flight::review_service()->get_by_item_id(route_entity_id($id)));
});

Flight::route('GET /reviews', function () {
    Flight::json(Flight::review_service()->get_all());
});
Flight::route('GET /reviews/@id', function ($id) {
    Flight::json(Flight::review_service()->get_by_id(route_entity_id($id)));
});
Flight::route('POST /reviews', function () {
    Flight::json(Flight::review_service()->add(route_read_json()), 201);
});
Flight::route('PUT /reviews/@id', function ($id) {
    Flight::json(Flight::review_service()->update(route_read_json(), route_entity_id($id)));
});
Flight::route('PATCH /reviews/@id', function ($id) {
    Flight::json(Flight::review_service()->update(route_read_json(), route_entity_id($id)));
});
Flight::route('DELETE /reviews/@id', function ($id) {
    Flight::review_service()->delete(route_entity_id($id));
    Flight::json(['message' => 'review deleted.']);
});
