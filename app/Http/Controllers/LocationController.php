<?php

namespace App\Http\Controllers;

use App\Exceptions\ForeignCoordinatesException;
use App\Exceptions\TooManyLocationsException;
use App\Http\Requests\DestroyLocationRequest;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Services\Geolocation\GeolocationService;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        return LocationResource::collection($request->user()->locations);
    }

    public function store(StoreLocationRequest $request)
    {
        if (config('shepper.max-locations') <= $request->user()->locations()->count()) {
            throw new TooManyLocationsException;
        }

        $location = $request->user()->locations()->create([
            'title'     => $request->input('title'),
            'label'     => $this->getLabel($request),
            'latitude'  => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'radius'    => $request->input('radius'),
        ]);

        return LocationResource::make($location);
    }

    public function update(UpdateLocationRequest $request, Location $location)
    {
        $location->update(array_filter([
            'title'     => $request->input('title'),
            'label'     => $this->getLabel($request),
            'latitude'  => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'radius'    => $request->input('radius'),
        ]));

        return LocationResource::make($location);
    }

    public function destroy(DestroyLocationRequest $request, Location $location)
    {
        $location->delete();

        return response()->json(null, 204);
    }

    /**
     * @param  Request  $request
     *
     * @return string|null
     */
    protected function getLabel(Request $request): ?string
    {
        if ($request->input('latitude') === null or $request->input('longitude') === null) {
            return null;
        }

        if ( ! $this->geolocation->areCoordinatesInCountry($request->input('latitude'), $request->input('longitude'), $request->user()->country_code)) {
            throw new ForeignCoordinatesException;
        }

        return $this->geolocation->getLabelForCoordinates($request->input('latitude'), $request->input('longitude'));
    }
}