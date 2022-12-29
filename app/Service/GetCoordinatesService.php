<?php

namespace App\Service;

use App\Service\Geocoding\GeocoderFactory;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GetCoordinatesService
{
    public function __construct(
        private GetResolvedGeocoderService $resolvedGeocoderService,
        private GeocoderFactory $geocoderFactory,
        private StoreCoordinatesService $storeCoordinatesService
    ) {
    }

    public function execute(
        string $country,
        string $city,
        string $street,
        int $postcode,
        ?string $source = null,
        bool $forceRefresh = false
    ): ?Coordinates {
        $coordinates = null;
        $address = new Address($country, $city, $street, $postcode);

        if ($forceRefresh === false) {
            $coordinates = $this->resolvedGeocoderService->execute($address, $source);
        }

        if ($coordinates === null) {
            $sources = $source ? [$source] : explode(',', $_ENV['GEOCODER_ORDER']);
            foreach ($sources as $sourceName) {
                $coordinates = $this->geocoderFactory->make($sourceName)->getCoordinates($address);
                $this->storeCoordinatesService->execute($sourceName, $address, $coordinates);
                if ($coordinates !== null) {
                    break;
                }
            }
        }

        return $coordinates;
    }
}
