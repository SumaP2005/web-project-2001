<?php
class Flight
{
    private static $last_json;
    private static $last_status;

    public static function route($pattern, $callback)
    {
        global $registered_routes;
        $registered_routes[$pattern] = $callback;
    }

    public static function json($data, $status = 200)
    {
        self::$last_json = $data;
        self::$last_status = $status;
    }

    public static function __callStatic($name, $arguments)
    {
        return new RouteTestService(str_replace('_service', '', $name));
    }

    public static function get_last_json()
    {
        return self::$last_json;
    }

    public static function get_last_status()
    {
        return self::$last_status;
    }
}

class RouteTestService
{
    private $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function __call($method, $arguments)
    {
        return [
            'service' => $this->service,
            'method' => $method,
            'arguments' => $arguments
        ];
    }
}

require_once __DIR__ . '/../../utils/exceptions.php';
require_once __DIR__ . '/../../utils/Validator.php';

function route_read_json()
{
    global $route_test_request_body;
    return $route_test_request_body;
}

function route_entity_id($id)
{
    Validator::integer($id, 'id', 1);
    return (int) $id;
}

$registered_routes = [];
$route_test_request_body = ['test' => 'sample'];

require_once __DIR__ . '/UserRoutes.php';
require_once __DIR__ . '/CategoryRoutes.php';
require_once __DIR__ . '/ItemRoutes.php';
require_once __DIR__ . '/ItemImageRoutes.php';
require_once __DIR__ . '/OrderRoutes.php';
require_once __DIR__ . '/ReviewRoutes.php';

$tests_run = 0;
function assert_route_test($condition, $message)
{
    global $tests_run;
    $tests_run++;
    if (!$condition) {
        throw new RuntimeException("Test failed: $message");
    }
}

assert_route_test(count($registered_routes) === 42, 'All resource routes are registered.');
assert_route_test(!isset($registered_routes['GET /api/users']), 'Routes do not use the /api prefix.');
assert_route_test(
    route_entity_id('7') === 7,
    'Route ID validation converts valid IDs to integers.'
);

try {
    route_entity_id('not-an-id');
    assert_route_test(false, 'Route ID validation rejects non-integer IDs.');
} catch (ValidationException $exception) {
    assert_route_test(true, 'Route ID validation rejects non-integer IDs.');
}

foreach ($registered_routes as $pattern => $callback) {
    $arguments = [];
    if (preg_match('/@email/', $pattern)) {
        $arguments[] = 'test@example.com';
    } elseif (preg_match('/@currency/', $pattern)) {
        $arguments[] = 'EUR';
    } elseif (preg_match('/@id/', $pattern)) {
        $arguments[] = '7';
    }

    call_user_func_array($callback, $arguments);
    $output = [
        'status' => Flight::get_last_status(),
        'response' => Flight::get_last_json()
    ];
    echo $pattern . ' => ' . json_encode($output, JSON_UNESCAPED_SLASHES) . PHP_EOL;
}

echo "Passed $tests_run route assertions; printed " . count($registered_routes) . " route responses." . PHP_EOL;
