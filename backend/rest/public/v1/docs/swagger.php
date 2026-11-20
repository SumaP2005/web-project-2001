<?php
/*
 * Generates the OpenAPI JSON from shared metadata in doc_setup.php
 * and endpoint declarations in the paths/*.php files.
 */
// Deprecation notices from libraries would break the JSON output
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '0');

require __DIR__ . '/../../../../vendor/autoload.php';

// The front controller lives in backend/index.php; API routes are relative to that directory.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$request_uri = $_SERVER['REQUEST_URI'] ?? '/backend/rest/public/v1/docs/swagger.php';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$request_path = parse_url($request_uri, PHP_URL_PATH);
$api_path = preg_replace('#rest/public/v1/docs/swagger\.php$#', '', $request_path);
define('API_BASE_URL', $scheme . '://' . $host . rtrim($api_path, '/') . '/');

$files = [__DIR__ . '/doc_setup.php'];
$pathFiles = glob(__DIR__ . '/paths/*.php') ?: [];
foreach ($files as $file) {
    require_once $file;
}
foreach ($pathFiles as $file) {
    require_once $file;
    $files[] = $file;
}

$openapi = (new \OpenApi\Generator())->generate($files);


header('Content-Type: application/json');
echo $openapi->toJson();