<?php

namespace App\Service;

use App\Models\ResolvedAddress;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class StoreCoordinatesService
{
    public function execute(
        string $source,
        Address $address,
        ?Coordinates $coordinates
    ): void {
        $resolvedAddress = ResolvedAddress::where('country_code', $address->getCountry())
            ->where('city', $address->getCity())
            ->where('street', $address->getStreet())
            ->where('postcode', $address->getPostcode())
            ->where('source', $source)
            ->first();
        if ($resolvedAddress === null) {
            $resolvedAddress = new ResolvedAddress();
            $resolvedAddress->country_code = $address->getCountry();
            $resolvedAddress->city = $address->getCity();
            $resolvedAddress->street = $address->getStreet();
            $resolvedAddress->postcode = $address->getPostcode();
            $resolvedAddress->source = $source;
        }
        if ($coordinates !== null) {
            $resolvedAddress->lat = $coordinates->getLat();
            $resolvedAddress->lng = $coordinates->getLng();
        }

        $resolvedAddress->save();
    }
}
