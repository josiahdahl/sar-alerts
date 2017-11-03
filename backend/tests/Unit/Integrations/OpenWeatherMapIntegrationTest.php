<?php

namespace Tests\Feature\Unit\Integrations;

use App\Integrations\OpenWeatherMapIntegration;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OpenWeatherMapIntegrationTest extends TestCase
{
    /** @test */
    public function can_create_client()
    {
        $integration = new OpenWeatherMapIntegration();
        $this->assertEquals(get_class($integration), OpenWeatherMapIntegration::class);
    }

    /** @test */
    public function can_get_weather_data()
    {
        // TODO: Figure out how to mock Guzzle inside the integration
    }

}
