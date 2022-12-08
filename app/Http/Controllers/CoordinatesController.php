<?php

namespace App\Http\Controllers;

use App\Service\GeocoderInterface;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoordinatesController extends Controller
{
    private GeocoderInterface $geocoder;

    public function __construct(GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    /**
     * @Route(path="/coordinates", name="geocode")
     * @param Request $request
     * @return Response
     */
    public function geocodeAction(Request $request): Response
    {
        $country = $request->get('countryCode', 'rt');
        $city = $request->get('city', 'Cairo');
        $street = $request->get('street', 'street');
        $postcode = $request->get('postcode', '31111');

       abort(404);
    }

    /**
     * @Route(path="/gmaps", name="gmaps")
     * @param Request $request
     * @return Response
     */
    public function gmapsAction(Request $request): Response
    {
        $country = $request->get('country', 'Egypt');
        $city = $request->get('city', 'cairo');
        $street = $request->get('street', 'street');
        $postcode = $request->get('postcode', '31111');

        $apiKey = $_ENV["GOOGLE_GEOCODING_API_KEY"];

        $params = [
            'query' => [
                'address' => $street,
                'components' => implode('|', ["country:{$country}", "locality:{$city}", "postal_code:{$postcode}"]),
                'key' => $apiKey
            ]
        ];

        $client = new Client();

        $response = $client->get('https://maps.googleapis.com/maps/api/geocode/json', $params);

        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (count($data['results']) === 0) {
            return new JsonResponse([]);
        }

        $firstResult = $data['results'][0];

        if ($firstResult['geometry']['location_type'] !== 'ROOFTOP') {
            return new JsonResponse([]);
        }

        return new JsonResponse($firstResult['geometry']['location']);
    }

    /**
     * @Route(path="/hmaps", name="hmaps")
     * @param Request $request
     * @return Response
     */
    public function hmapsAction(Request $request): Response
    {
        $country = $request->get('country', 'egypt');
        $city = $request->get('city', 'cairo');
        $street = $request->get('street', 'street');
        $postcode = $request->get('postcode', '311111');

        $apiKey = $_ENV["HEREMAPS_GEOCODING_API_KEY"];

        $params = [
            'query' => [
                'qq' => implode(';', ["country={$country}", "city={$city}", "street={$street}", "postalCode={$postcode}"]),
                'apiKey' => $apiKey
            ]
        ];

        $client = new Client();

        $response = $client->get('https://geocode.search.hereapi.com/v1/geocode', $params);

        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (count($data['items']) === 0) {
            return new JsonResponse([]);
        }

        $firstItem = $data['items'][0];

        if ($firstItem['resultType'] !== 'houseNumber') {
            return new JsonResponse([]);
        }

        return new JsonResponse($firstItem['position']);
    }
}
