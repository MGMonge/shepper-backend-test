<?php

namespace App\Http\Controllers;

use App\Exceptions\ForeignCoordinatesException;
use App\Exceptions\TooManyLocationsException;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Resources\LocationResource;
use App\Services\Geolocation\GeolocationService;

class LocationController extends Controller
{
    /**
     * @var GeolocationService
     */
    private $geolocation;

    public function __construct(GeolocationService $geolocation)
    {
        $this->geolocation = $geolocation;
    }

    public function store(StoreLocationRequest $request)
    {
        if (config('shepper.max-locations') <= $request->user()->locations()->count()) {
            throw new TooManyLocationsException;
        }

        if ( ! $this->geolocation->areCoordinatesInCountry($request->input('latitude'), $request->input('longitude'), $request->user()->country_code)) {
            throw new ForeignCoordinatesException;
        }

        $label = $this->geolocation->getLabelForCoordinates($request->input('latitude'), $request->input('longitude'));

        $location = $request->user()->locations()->create([
            'title'     => $request->input('title'),
            'label'     => $label,
            'latitude'  => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'radius'    => $request->input('radius'),
        ]);

        return LocationResource::make($location);
    }
}