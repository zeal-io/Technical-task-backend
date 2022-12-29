<?php

namespace App\Service\Geocoding;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use GuzzleHttp\Client;

class GoogleService implements GeocoderInterface
{

    public function __construct(private Client $client)
    {
    }
    public function getCoordinates(Address $address): ?Coordinates
    {
        $apiKey = $_ENV['GOOGLE_GEOCODING_API_KEY'];

        $params = [
            'query' => [
                'address'    => $address->getStreet(),
                'components' => implode(
                    '|',
                    [
                        "country:{$address->getCountry()}",
                        "locality:{$address->getCity()}",
                        "postal_code:{$address->getPostcode()}"
                    ]
                ),
                'key'        => $apiKey,
            ],
        ];
        $response = $this->client->get('https://maps.googleapis.com/maps/api/geocode/json', $params);
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (empty($data['results'][0]) || $data['results'][0]['geometry']['location_type'] !== 'ROOFTOP') {
            return null;
        }

        return new Coordinates(
            $data['results'][0]['geometry']['location']['lat'],
            $data['results'][0]['geometry']['location']['lng']
        );
    }
}
