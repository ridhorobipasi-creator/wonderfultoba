<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_packages_endpoint_returns_json()
    {
        $response = $this->getJson('/api/packages');

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_cars_endpoint_returns_json()
    {
        $response = $this->getJson('/api/cars');

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_blogs_endpoint_returns_json()
    {
        $response = $this->getJson('/api/blogs');

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_cities_endpoint_returns_json()
    {
        $response = $this->getJson('/api/cities');

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_gallery_endpoint_returns_json()
    {
        $response = $this->getJson('/api/gallery');

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_outbound_services_endpoint_returns_json()
    {
        $response = $this->getJson('/api/outbound/services');

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function test_stats_endpoint_returns_json()
    {
        $response = $this->getJson('/api/stats');

        $response->assertStatus(200)
            ->assertJson([
                'packages' => 0,
                'happyClients' => 1540,
            ]);
    }
}
