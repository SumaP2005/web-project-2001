<?php
/**
 * PRESENTATION LAYER - item images
 * OpenAPI documentation: public/v1/docs/paths/ItemImagePaths.php
 */

Flight::route('GET /item-images/by-item/@id', function ($id) {
    Flight::json(Flight::item_image_service()->get_by_item_id(route_entity_id($id)));
});

Flight::route('GET /item-images', function () {
    Flight::json(Flight::item_image_service()->get_all());
});
Flight::route('GET /item-images/@id', function ($id) {
    Flight::json(Flight::item_image_service()->get_by_id(route_entity_id($id)));
});
Flight::route('POST /item-images', function () {
    Flight::json(Flight::item_image_service()->add(route_read_json()), 201);
});
Flight::route('PUT /item-images/@id', function ($id) {
    Flight::json(Flight::item_image_service()->update(route_read_json(), route_entity_id($id)));
});
Flight::route('PATCH /item-images/@id', function ($id) {
    Flight::json(Flight::item_image_service()->update(route_read_json(), route_entity_id($id)));
});
Flight::route('DELETE /item-images/@id', function ($id) {
    Flight::item_image_service()->delete(route_entity_id($id));
    Flight::json(['message' => 'item image deleted.']);
});
