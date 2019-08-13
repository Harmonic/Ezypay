<?php

namespace harmonic\Ezypay;

use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use harmonic\Ezypay\Traits\Plan;
use harmonic\Ezypay\Traits\Event;
use harmonic\Ezypay\Traits\Vault;
use harmonic\Ezypay\Traits\Invoice;
use harmonic\Ezypay\Traits\WebHook;
use harmonic\Ezypay\Traits\Customer;
use harmonic\Ezypay\Traits\Merchant;
use harmonic\Ezypay\Traits\CreditNote;
use harmonic\Ezypay\Traits\Settlement;
use harmonic\Ezypay\Traits\Transaction;
use Illuminate\Support\Facades\Storage;
use harmonic\Ezypay\Traits\Subscription;
use harmonic\Ezypay\Traits\FutureInvoice;
use harmonic\Ezypay\Traits\PaymentMethod;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class Ezypay
{
    use Merchant, Plan, Customer, Vault, PaymentMethod, Subscription, Invoice, WebHook, Transaction, CreditNote, FutureInvoice, Settlement, Event;

    private $token = null;
    private $tokenFile = 'ezypayToken.txt';
    private $currency = 'AUD';

    public function __construct(string $defaultCurrency = 'AUD')
    {
        $this->currency = $defaultCurrency;
        $this->token = $this->getAccessToken();
    }

    public function instance()
    {
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * Send a request to the Ezypay API.
     *
     * @param string $method The method to call
     * @param string $uri The uri of the request eg. clients
     * @param array $params An array of parameters to send with the request
     * @param bool $forceQuery Include query param in PATCH request
     * @param int $retries The number of times this request has failed (defaults to 0)
     * @return object The object returned by the API
     */
    private function request(string $method, string $uri, array $params = [], bool $forceQuery = false, int $retries = 0)
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = config('ezypay.url').$uri;
        if (starts_with($uri, 'vault/')) {
            $url = config('ezypay.vault_url').$uri;
        }

        $method = strtoupper($method);
        $data = ['headers' => [
            'Authorization' => 'Bearer '.$this->token,
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'application/json',
            'Merchant' => config('ezypay.merchant_id'),
        ]];

        switch ($method) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
            case 'PATCH':
                if ($forceQuery) {
                    $data['query'] = $params;
                } else {
                    $data['json'] = $params;
                }
                break;
            case 'GET':
                $data['query'] = $params;
                break;
            default:
                throw new \Exception('Invalid method provided: '.$method);
        }
        $response = $client->request($method, $url, $data);

        //TODO: If ($response->getStatusCode() != "200")
        if ($response->getStatusCode() == 401 || $response->getStatusCode() == 400) {
            Storage::disk('local')->delete($this->tokenFile);
            $this->getAccessToken();
            if ($retries < 1) { // Try the request again now we have a new access token
                $retries++;
                $this->request($method, $uri, $params, $forceQuery, $retries);
            }
        }

        return $this->processResponse($response);
    }

    /**
     * Automatically paginates a result set.
     *
     * @param string $uri
     * @param array $data
     * @param bool $fetchAll
     * @return array Of items
     */
    private function paginate(string $uri, array $data, bool $fetchAll = false)
    {
        $response = $this->request('GET', $uri, $data);

        $items = $response['data'];
        $totalNumItems = $response['paging']['totalCount'];
        $currentNumItems = count($items);

        if ($fetchAll) {
            if (! array_key_exists('limit', $data) || ! array_key_exists('cursor', $data)) {
                throw new InvalidParameterException('Pagination requires data to include a cursor and a limit.');
            }
            $limit = $data['limit'];
            while ($currentNumItems <= $totalNumItems) {
                $data['cursor'] = $response['paging']['nextCursor'];
                $data['limit'] = $response['paging']['limit'];
                $response = $this->request('GET', $uri, $data);
                $items = array_merge($items, $response['data']);
                $currentNumItems = $currentNumItems + $limit;
            }
        }

        return $items;
    }

    /**
     * Take a Guzzle repsonse and return object.
     *
     * @param Guzzle\Response $response
     * @return object Response contents object
     */
    private function processResponse($response)
    {
        $stringResponse = (string) $response->getBody();

        return json_decode($stringResponse, true);
    }

    /**
     * Retrieve an oAuth token from EZYPAY.
     *
     * @param string $refreshToken The refresh token (if requesting)
     * @return void
     */
    private function requestToken(string $refreshToken = null)
    {
        $client = new Client(); //GuzzleHttp\Client

        $url = config('ezypay.token_url');

        $data = [
            'client_id' => config('ezypay.client_id'),
            'client_secret' => config('ezypay.client_secret'),
        ];

        if (! empty($refreshToken)) {
            $data['grant_type'] = 'refresh_token';
            $data['refresh_token'] = $refreshToken;
        } else { // assume getting first token
            $data['grant_type'] = 'password';
            $data['username'] = config('ezypay.user');
            $data['password'] = config('ezypay.password');
            $data['scope'] = 'integrator billing_profile create_payment_method offline_access';
        }

        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'application/x-www-form-urlencoded',
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => $data,
        ]);

        $token = (string) $response->getBody();

        $tokenObj = json_decode($token, true);
        $tokenObj['expiration'] = Carbon::now()->addSeconds(3590); // Just under hour

        Storage::disk('local')->put($this->tokenFile, json_encode($tokenObj));

        return $tokenObj;
    }

    /**
     * Retrieve the current token or get a new one.
     *
     * @return void
     */
    private function getAccessToken()
    {
        try {
            $tokenDataFile = Storage::disk('local')->get($this->tokenFile);
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            $tokenData = $this->requestToken();

            return $tokenData['access_token'];
        }

        $tokenData = json_decode($tokenDataFile, true);

        $now = Carbon::now();
        $tokenIsExpired = ($now->gte(new Carbon($tokenData['expiration'])));

        if ($tokenIsExpired) {
            $tokenData = $this->requestToken($tokenData['refresh_token']);
        }

        return $tokenData['access_token'];
    }
}
