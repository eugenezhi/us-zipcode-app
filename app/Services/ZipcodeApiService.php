<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

/**
 * Class ZipcodeApiService
 * @package App\Services
 */
class ZipcodeApiService
{
    private string $baseUrl;

    public function __construct()
    {
        if (empty(env('ZIPCODE_API_URL')) || empty(env('ZIPCODE_API_AUTH_ID')) || empty(env('ZIPCODE_API_AUTH_TOKEN'))) {
            throw new Exception('Configuration parameter is empty', 403);
        }
        $this->baseUrl = env('ZIPCODE_API_URL');
    }

    /**
     * @param array $extraParams
     * @return PromiseInterface|Response
     */
    public function sendGetRequest(array $extraParams): PromiseInterface|Response
    {
        $params = [
            'auth-id' => env('ZIPCODE_API_AUTH_ID'),
            'auth-token' => env('ZIPCODE_API_AUTH_TOKEN'),
        ];
        return Http::get($this->baseUrl, [...$params, ...$extraParams]);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getZipcodesByCityAndState(array $params): array
    {
        $response =  $this->sendGetRequest($params);
        if (!$response->successful()) {
            return [];
        }
        $data = $response->object();
        return Arr::get($data, '0')?->zipcodes ?? [];
    }
}
