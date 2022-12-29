<?php

namespace Tests\Unit;

use App\Service\Geocoding\GeocoderFactory;
use App\Service\Geocoding\GoogleService;
use App\Service\GetCoordinatesService;
use App\Service\GetResolvedGeocoderService;
use App\Service\StoreCoordinatesService;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use PHPUnit\Framework\TestCase;

class GetCoordinatesServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_return_coordinates_by_api()
    {
        $source = 'google';
        $coordinates = new Coordinates(1, 2);
        $address = new Address('country', 'city', 'street', 12345);
        $resolvedGeocoderServiceMock = $this->createMock(GetResolvedGeocoderService::class);
        $geocoderFactoryMock = $this->createMock(GeocoderFactory::class);
        $storeCoordinatesServiceMock = $this->createMock(StoreCoordinatesService::class);
        $googleServiceMock = $this->createMock(GoogleService::class);
        $getCoordinatesService = new GetCoordinatesService(
            $resolvedGeocoderServiceMock,
            $geocoderFactoryMock,
            $storeCoordinatesServiceMock
        );
        $resolvedGeocoderServiceMock->expects($this->once())
            ->method('execute')
            ->willReturn(null);
        $geocoderFactoryMock->expects($this->once())
            ->method('make')
            ->willReturn($googleServiceMock);
        $googleServiceMock->expects($this->once())
            ->method('getCoordinates')
            ->willReturn($coordinates);
        $storeCoordinatesServiceMock->expects($this->once())
            ->method('execute')
            ->with('google', $address, $coordinates);

        $return = $getCoordinatesService->execute(
            'country',
            'city',
            'street',
            12345,
            $source,
            false
        );
        $this->assertEquals($coordinates, $return);
    }

    public function test_return_coordinates_by_db()
    {
        $source = 'google';
        $coordinates = new Coordinates(1, 2);
        $resolvedGeocoderServiceMock = $this->createMock(GetResolvedGeocoderService::class);
        $geocoderFactoryMock = $this->createMock(GeocoderFactory::class);
        $storeCoordinatesServiceMock = $this->createMock(StoreCoordinatesService::class);
        $googleServiceMock = $this->createMock(GoogleService::class);
        $getCoordinatesService = new GetCoordinatesService(
            $resolvedGeocoderServiceMock,
            $geocoderFactoryMock,
            $storeCoordinatesServiceMock
        );
        $resolvedGeocoderServiceMock->expects($this->once())
            ->method('execute')
            ->willReturn($coordinates);
        $geocoderFactoryMock->expects($this->never())
            ->method('make');
        $googleServiceMock->expects($this->never())
            ->method('getCoordinates');
        $storeCoordinatesServiceMock->expects($this->never())
            ->method('execute');

        $return = $getCoordinatesService->execute(
            'country',
            'city',
            'street',
            12345,
            $source,
            false
        );
        $this->assertEquals($coordinates, $return);
    }
}
