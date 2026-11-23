<?php
/**
 * PRESENTATION LAYER - orders
 * OpenAPI documentation: public/v1/docs/paths/OrderPaths.php
 */

Flight::route('GET /orders/by-buyer/@id', function ($id) {
    Flight::json(Flight::order_service()->get_by_buyer_id(route_entity_id($id)));
});

Flight::route('GET /orders', function () {
    Flight::json(Flight::order_service()->get_all());
});
Flight::route('GET /orders/@id', function ($id) {
    Flight::json(Flight::order_service()->get_by_id(route_entity_id($id)));
});
Flight::route('POST /orders', function () {
    Flight::json(Flight::order_service()->add(route_read_json()), 201);
});
Flight::route('PUT /orders/@id', function ($id) {
    Flight::json(Flight::order_service()->update(route_read_json(), route_entity_id($id)));
});
Flight::route('PATCH /orders/@id', function ($id) {
    Flight::json(Flight::order_service()->update(route_read_json(), route_entity_id($id)));
});
Flight::route('DELETE /orders/@id', function ($id) {
    Flight::order_service()->delete(route_entity_id($id));
    Flight::json(['message' => 'order deleted.']);
});
