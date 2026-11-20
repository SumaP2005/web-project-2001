<?php
/**
 * PRESENTATION LAYER - items
 * OpenAPI documentation: public/v1/docs/paths/ItemPaths.php
 */

Flight::route('GET /items/by-seller/@id', function ($id) {
    Flight::json(Flight::item_service()->get_by_seller_id(route_entity_id($id)));
});

Flight::route('GET /items/by-category/@id', function ($id) {
    Flight::json(Flight::item_service()->get_by_category_id(route_entity_id($id)));
});

Flight::route('GET /items', function () {
    Flight::json(Flight::item_service()->get_all());
});
Flight::route('GET /items/@id', function ($id) {
    Flight::json(Flight::item_service()->get_by_id(route_entity_id($id)));
});
Flight::route('POST /items', function () {
    Flight::json(Flight::item_service()->add(route_read_json()), 201);
});
Flight::route('PUT /items/@id', function ($id) {
    Flight::json(Flight::item_service()->update(route_read_json(), route_entity_id($id)));
});
Flight::route('PATCH /items/@id', function ($id) {
    Flight::json(Flight::item_service()->update(route_read_json(), route_entity_id($id)));
});
Flight::route('DELETE /items/@id', function ($id) {
    Flight::item_service()->delete(route_entity_id($id));
    Flight::json(['message' => 'item deleted.']);
});
