<?php

namespace App\Service\Geocoding;

use App\Models\ResolvedAddress;

class GeocoderFactory
{
    public function __construct(
        private HereService $hereService,
        private GoogleService $googleService,
    ) {
    }

    public function make(string $source): GeocoderInterface
    {
        return match ($source) {
            ResolvedAddress::SOURCE_GOOGLE => $this->googleService,
            ResolvedAddress::SOURCE_HERE => $this->hereService,
            default => throw new \Exception('Unknown geocoding source'),
        };
    }
}
