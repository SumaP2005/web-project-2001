<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/exceptions.php';
require_once __DIR__ . '/../../utils/Validator.php';

/**
 * BUSINESS LOGIC – converts BAM prices with live rates from exchangerate-api.com.
 * No DAO is used: the data comes from an external API.
 */
class ExchangeRateService
{
    const BASE_CURRENCY   = 'BAM';
    const CONNECT_TIMEOUT = 3;   // seconds to open the connection
    const TIMEOUT         = 5;   // seconds for the whole request
    const MAX_AMOUNT      = 1000000;

    private $http;
    private $api_key;

    /** $http and $api_key can be replaced in tests, so no internet is needed. */
    public function __construct($http = null, $api_key = null)
    {
        $this->http = $http ?: [$this, 'curl_get'];
        $this->api_key = $api_key ?? Config::EXCHANGE_API_KEY();
    }

    public function convert($amount, $to)
    {
        $amount = $this->validate_amount($amount);
        $to = $this->validate_currency($to);

        $rates = $this->fetch_rates();
        if (!isset($rates[$to])) {
            throw new NotFoundException("Currency '$to' is not supported.");
        }

        $rate = (float) $rates[$to];
        return [
            'from'      => self::BASE_CURRENCY,
            'to'        => $to,
            'amount'    => $amount,
            'rate'      => $rate,
            'converted' => round($amount * $rate, 2),
            'source'    => 'exchangerate-api.com'
        ];
    }

    private function validate_amount($amount)
    {
        Validator::required(['amount' => $amount], ['amount']);
        if (!is_numeric($amount)) {
            throw new ValidationException("Field 'amount' must be a number.");
        }
        $amount = (float) $amount;
        if ($amount <= 0 || $amount > self::MAX_AMOUNT) {
            throw new ValidationException("Field 'amount' must be greater than 0 and at most " . self::MAX_AMOUNT . ".");
        }
        return $amount;
    }

    private function validate_currency($to)
    {
        Validator::required(['to' => $to], ['to']);
        $to = strtoupper(trim((string) $to));
        if (!preg_match('/^[A-Z]{3}$/', $to)) {
            throw new ValidationException("Field 'to' must be a 3-letter currency code, for example EUR.");
        }
        return $to;
    }

    /** Returns the map of rates {"EUR": 0.51, "USD": 0.55, ...} for 1 BAM. */
    private function fetch_rates()
    {
        if (trim((string) $this->api_key) === '') {
            error_log('ExchangeRateService: EXCHANGE_API_KEY is not set.');
            throw new ExternalServiceException('The exchange rate service is not configured.');
        }

        // never log this URL – it contains the API key
        $url = 'https://v6.exchangerate-api.com/v6/' . rawurlencode($this->api_key) . '/latest/' . self::BASE_CURRENCY;
        $response = call_user_func($this->http, $url);

        if ($response['errno'] !== 0) {
            error_log('ExchangeRateService: cURL error ' . $response['errno'] . ': ' . $response['error']);
            if ($response['errno'] === CURLE_OPERATION_TIMEDOUT) {
                throw new ExternalServiceException('The exchange rate service took too long to answer. Please try again later.');
            }
            throw new ExternalServiceException('The exchange rate service is not available at the moment. Please try again later.');
        }

        $data = json_decode((string) $response['body'], true);
        if (!is_array($data) || ($data['result'] ?? '') !== 'success' || !is_array($data['conversion_rates'] ?? null)) {
            error_log('ExchangeRateService: provider answered HTTP ' . $response['status']
                . ', error-type: ' . ($data['error-type'] ?? 'unknown'));
            throw new ExternalServiceException('The exchange rate service returned an invalid answer.');
        }

        return $data['conversion_rates'];
    }

    /** The real HTTP call with timeouts. Returns status, body and cURL error info. */
    public function curl_get($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => self::CONNECT_TIMEOUT,
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTPHEADER     => ['Accept: application/json']
        ]);
        $body = curl_exec($ch);

        return [
            'status' => (int) curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'body'   => $body === false ? '' : $body,
            'errno'  => curl_errno($ch),
            'error'  => curl_error($ch)
        ];
    }
}