<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_presents_a_location_resource()
    {
        $location = factory(Location::class)->make();

        $actual = LocationResource::make($location)->toJson();

        $this->assertEquals(json_encode([
            'id'        => $location->id,
            'title'     => $location->title,
            'label'     => $location->label,
            'latitude'  => $location->latitude,
            'longitude' => $location->longitude,
            'radius'    => $location->radius,
        ]), $actual);
    }
}