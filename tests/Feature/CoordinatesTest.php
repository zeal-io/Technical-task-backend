<?php

namespace Tests\Feature;

use App\Models\ResolvedAddress;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoordinatesTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_return_coordinates_by_google_api(): void
    {
        $parameters = "country=US&city=NY&street=295 Washington St&"
            . "postcode=10013&force_refresh=1&source=google";
        $response = $this->getJson("/api/geocode?{$parameters}");
        $data = $response->json();

        $response->assertStatus(200);
        $this->assertArrayHasKey('coordinates', $data);
    }

    public function test_return_coordinates_by_here_api(): void
    {
        $parameters = "country=US&city=NY&street=295 Washington St&"
            . "postcode=10013&force_refresh=1&source=here";
        $response = $this->getJson("/api/geocode?{$parameters}");
        $data = $response->json();

        $response->assertStatus(200);
        $this->assertArrayHasKey('coordinates', $data);
    }

    public function test_return_coordinates_from_db(): void
    {
        $parameters = "country=US&city=NY&street=295 Washington St&"
            . "postcode=10013";
        $this->getJson("/api/geocode?{$parameters}");
        $response = $this->getJson("/api/geocode?{$parameters}");
        $data = $response->json();

        $this->assertEquals(1, ResolvedAddress::count());
        $this->assertArrayHasKey('coordinates', $data);
    }

    public function test_return_coordinates_by_google_from_db(): void
    {
        $parameters = "country=US&city=NY&street=295 Washington St&"
            . "postcode=10013&source=google";
        $this->getJson("/api/geocode?{$parameters}");
        $response = $this->getJson("/api/geocode?{$parameters}");
        $data = $response->json();

        $this->assertEquals(1, ResolvedAddress::count());
        $this->assertArrayHasKey('coordinates', $data);
    }

    public function test_return_error_when_no_result(): void
    {
        $parameters = "country=UdS&city=NY&street=295 Washington St&"
            . "postcode=10013";
        $response = $this->getJson("/api/geocode?{$parameters}");
        $data = $response->json();

        $response->assertStatus(404);
        $this->assertArrayHasKey('error', $data);
    }
}
