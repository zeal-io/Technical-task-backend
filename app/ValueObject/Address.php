<?php

namespace App\ValueObject;

class Address
{
    private string $country;
    private string $city;
    private string $street;
    private int $postcode;

    public function __construct(string $country, string $city, string $street, int $postcode)
    {
        $this->country = $country;
        $this->city = $city;
        $this->street = $street;
        $this->postcode = $postcode;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getPostcode(): int
    {
        return $this->postcode;
    }
}
