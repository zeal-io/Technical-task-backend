<?php

namespace App\Service\Geocoding;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

class HereService implements GeocoderInterface
{
    public function __construct(private Client $client)
    {
    }

    public function getCoordinates(Address $address): ?Coordinates
    {
        $apiKey = $_ENV['HEREMAPS_GEOCODING_API_KEY'];

        $params = [
            'query' => [
                'qq'     => implode(
                    ';',
                    [
                        "country={$address->getCountry()}",
                        "city={$address->getCity()}",
                        "street={$address->getStreet()}",
                        "postalCode={$address->getPostcode()}",
                    ]
                ),
                'apiKey' => $apiKey,
            ],
        ];
        $response = $this->client->get('https://geocode.search.hereapi.com/v1/geocode', $params);
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (empty($data['items'][0]) || $data['items'][0]['resultType'] !== 'houseNumber') {
            return null;
        }

        return new Coordinates(
            $data['items'][0]['position']['lat'],
            $data['items'][0]['position']['lng']
        );
    }
}
