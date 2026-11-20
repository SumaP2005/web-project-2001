<?php
require_once __DIR__ . '/ExchangeRateService.php';

function fake_http($response)
{
    return function ($url) use ($response) { return $response; };
}
$ok = ['status' => 200, 'errno' => 0, 'error' => '',
       'body' => json_encode(['result' => 'success', 'conversion_rates' => ['BAM' => 1, 'EUR' => 0.5, 'USD' => 0.55]])];

function expect_result($label, $actual, $expected)
{
    echo ($actual === $expected ? 'PASS ' : 'FAIL ') . "$label\n";
}
function expect_exception($label, $class, $fn)
{
    try { $fn(); echo "FAIL $label (no exception)\n"; }
    catch (Exception $e) {
        echo ($e instanceof $class ? 'PASS ' : 'FAIL ') . "$label -> " . get_class($e) . ': ' . $e->getMessage() . "\n";
    }
}

$service = new ExchangeRateService(fake_http($ok), 'test-key');
expect_result('25 BAM -> EUR = 12.5', $service->convert('25', 'eur')['converted'], 12.5);
expect_exception('missing amount',  'ValidationException', fn() => $service->convert(null, 'EUR'));
expect_exception('negative amount', 'ValidationException', fn() => $service->convert('-5', 'EUR'));
expect_exception('amount = abc',    'ValidationException', fn() => $service->convert('abc', 'EUR'));
expect_exception('bad code "E1"',   'ValidationException', fn() => $service->convert('10', 'E1'));
expect_exception('unknown XYZ',     'NotFoundException',   fn() => $service->convert('10', 'XYZ'));

$timeout = new ExchangeRateService(fake_http(['status' => 0, 'body' => '', 'errno' => 28, 'error' => 'timed out']), 'test-key');
expect_exception('timeout',         'ExternalServiceException', fn() => $timeout->convert('10', 'EUR'));

$bad = new ExchangeRateService(fake_http(['status' => 403, 'errno' => 0, 'error' => '',
    'body' => '{"result":"error","error-type":"invalid-key"}']), 'test-key');
expect_exception('provider error',  'ExternalServiceException', fn() => $bad->convert('10', 'EUR'));

$nokey = new ExchangeRateService(fake_http($ok), '');
expect_exception('no API key',      'ExternalServiceException', fn() => $nokey->convert('10', 'EUR'));