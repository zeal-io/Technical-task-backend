<?php

namespace App\Service\Geocoding;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

interface GeocoderInterface
{
    public function getCoordinates(Address $address): ?Coordinates;
}
