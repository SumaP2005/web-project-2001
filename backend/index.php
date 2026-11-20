<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/utils/exceptions.php';
require_once __DIR__ . '/utils/Validator.php';
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/CategoryService.php';
require_once __DIR__ . '/rest/services/ItemService.php';
require_once __DIR__ . '/rest/services/ItemImageService.php';
require_once __DIR__ . '/rest/services/OrderService.php';
require_once __DIR__ . '/rest/services/ReviewService.php';
require_once __DIR__ . '/rest/services/ExchangeRateService.php';

function route_send_error($exception)
{
    if ($exception instanceof HttpException &&
        in_array($exception->get_status(), [400, 401, 403, 404, 503], true)) {
        Flight::jsonHalt([
            'error' => true,
            'message' => $exception->getMessage()
        ], $exception->get_status());
        return;
    }

    error_log((string) $exception);
    Flight::jsonHalt([
        'error' => true,
        'message' => 'Internal server error.'
    ], 500);
}

function route_read_json()
{
    $body = file_get_contents('php://input');
    $data = json_decode($body);
    if (json_last_error() !== JSON_ERROR_NONE || !is_object($data)) {
        throw new ValidationException('Request body must be a valid JSON object.');
    }

    $entity = get_object_vars($data);
    if (!$entity) {
        throw new ValidationException('Request body must not be empty.');
    }
    return $entity;
}

function route_entity_id($id)
{
    Validator::integer($id, 'id', 1);
    return (int) $id;
}

Flight::register('user_service', 'UserService');
Flight::register('category_service', 'CategoryService');
Flight::register('item_service', 'ItemService');
Flight::register('item_image_service', 'ItemImageService');
Flight::register('order_service', 'OrderService');
Flight::register('review_service', 'ReviewService');
Flight::register('exchange_rate_service', 'ExchangeRateService');

Flight::map('error', function ($exception) {
    route_send_error($exception);
});
Flight::map('notFound', function () {
    Flight::json(['error' => true, 'message' => 'Endpoint not found.'], 404);
});
Flight::map('methodNotFound', function ($route) {
    Flight::response()->header('Allow', implode(', ', $route->methods));
    Flight::json(['error' => true, 'message' => 'Method not allowed.'], 405);
});

require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/CategoryRoutes.php';
require_once __DIR__ . '/rest/routes/ItemRoutes.php';
require_once __DIR__ . '/rest/routes/ItemImageRoutes.php';
require_once __DIR__ . '/rest/routes/OrderRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';
require_once __DIR__ . '/rest/routes/ExchangeRateRoutes.php';

Flight::start();