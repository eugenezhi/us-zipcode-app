<?php

namespace Tests\Feature;

use App\Services\ZipcodeApiService;
use Illuminate\Support\Arr;
use Tests\TestCase;

class MainTest extends TestCase
{
    private const PARAMETERS = [
        'city' => 'Chicago',
        'state_id' => 16,
        'state' => 'IL'
    ];

    /**
     * Test zipcode api response
     */
    public function test_zipcode_api_response(): void
    {
        $service = $this->app->make(ZipcodeApiService::class);
        $apiResult = $service->getZipcodesByCityAndState(self::PARAMETERS);

        $this->assertIsArray($apiResult);
        $this->assertNotEmpty($apiResult);
        $this->assertObjectHasProperty('zipcode', $apiResult[0]);
        $this->assertCount(90, $apiResult);
    }

    /**
     * Test Main Controller searchZipcodes method
     */
    public function test_search_zipcodes_response(): void
    {
        $response = $this->call('POST', '/', self::PARAMETERS);
        $this->assertTrue($response->isOk());

        $this->assertDatabaseHas('lookup_results', Arr::only(self::PARAMETERS, ['city', 'state_id']));
    }
}
