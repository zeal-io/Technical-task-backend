<?php

namespace App\Service;

use App\Models\ResolvedAddress;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GetResolvedGeocoderService
{

    public function execute(Address $address, ?string $source): ?Coordinates
    {
        $coordinates = null;
        $query = ResolvedAddress::where('country_code', $address->getCountry())
            ->where('city', $address->getCity())
            ->where('street', $address->getStreet())
            ->where('postcode', $address->getPostcode());
        if ($source !== null) {
            $query->where('source', $source);
        }
        $resolvedAddress = $query->first();
        if ($resolvedAddress !== null && $resolvedAddress->lat && $resolvedAddress->lng) {
            $coordinates = new Coordinates($resolvedAddress->lat, $resolvedAddress->lng);
        }
        return $coordinates;
    }
}
