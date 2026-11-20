<?php
/**
 * PRESENTATION LAYER – external API (exchange rates)
 * OpenAPI documentation: public/v1/docs/paths/ExchangeRatePaths.php
 */

Flight::route('GET /exchange-rates/convert', function () {
    $query = Flight::request()->query;
    Flight::json(Flight::exchange_rate_service()->convert($query['amount'], $query['to']));
});