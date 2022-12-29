<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetGeocodeRequest;
use App\Service\GetCoordinatesService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CoordinatesController extends Controller
{

    public function geocodeAction(GetGeocodeRequest $request, GetCoordinatesService $geocoderService): Response
    {
        /**
         * @todo use JSON-API package to handel the request DTO and the response also
         */
        $country = $request->get('country');
        $city = $request->get('city');
        $street = $request->get('street');
        $postcode = $request->get('postcode');
        $source = $request->get('source');
        $forceRefresh = (bool) $request->get('force_refresh');

        $coordinates = $geocoderService->execute($country, $city, $street, $postcode, $source, $forceRefresh);

        if ($coordinates === null) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }
        return new JsonResponse(
            ['coordinates' => ['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]],
            200
        );
    }
}
