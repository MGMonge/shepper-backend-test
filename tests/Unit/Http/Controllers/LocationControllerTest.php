<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guests_cannot_store_a_location()
    {
        $response = $this->json('POST', route('locations.store'));

        $response->assertUnauthorized();
    }

    /** @test */
    function required_fields_when_storing_a_location()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('locations.store'));

        $response->assertJsonValidationErrors([
            'title',
            'latitude',
            'longitude',
            'radius',
        ]);
    }

    /** @test */
    function the_title_has_a_minimum_of_characters_when_storing_a_location()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('locations.store'), [
            'title' => 'Ho',
        ]);

        $response->assertJsonValidationErrors(['title']);
    }

    /** @test */
    function the_title_has_a_maximum_of_characters_when_storing_a_location()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('locations.store'), [
            'title' => str_repeat('A', 31),
        ]);

        $response->assertJsonValidationErrors(['title']);
    }

    /** @test */
    function the_radius_must_be_numeric_when_storing_a_location()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('locations.store'), [
            'radius' => 'foobar',
        ]);

        $response->assertJsonValidationErrors(['radius']);
    }

    /** @test */
    function the_radius_has_a_minimum_of_kilometers_when_storing_a_location()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('locations.store'), [
            'radius' => 0.4,
        ]);

        $response->assertJsonValidationErrors(['radius']);
    }

    /** @test */
    function the_radius_has_a_maximum_of_kilometers_when_storing_a_location()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('locations.store'), [
            'radius' => 51,
        ]);

        $response->assertJsonValidationErrors(['radius']);
    }

    /** @test */
    function the_coordinates_must_be_valid()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('locations.store'), [
            'title'     => 'Home',
            'latitude'  => '1',
            'longitude' => '-1',
            'radius'    => 25.0,
        ]);

        $response->assertJsonValidationErrors(['general']);
    }

    /** @test */
    function users_can_store_a_new_location()
    {
        $user = factory(User::class)->create(['country_code' => 'DE']);
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('locations.store'), [
            'title'     => 'Home',
            'latitude'  => '50.109852',
            'longitude' => '8.681891',
            'radius'    => 25.0,
        ]);

        $response->assertCreated();
        $newLocation = Location::where([
            'title'     => 'Home',
            'label'     => 'Frankfurt, DE',
            'latitude'  => '50.109852',
            'longitude' => '8.681891',
            'radius'    => 25.0,
            'user_id'   => $user->id,
        ])->first();
        $response->assertExactJson([
            'data' => LocationResource::make($newLocation)->toArray(request())
        ]);
    }

    /** @test */
    function users_cannot_exceeds_the_limit_of_locations()
    {
        config()->set('shepper.max-locations', 1);
        $user = factory(User::class)->create();
        factory(Location::class)->create(['user_id' => $user->id]);
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('locations.store'), [
            'title'     => 'Home',
            'latitude'  => '50.109852',
            'longitude' => '8.681891',
            'radius'    => 25.0,
        ]);

        $response->assertJsonValidationErrors(['general']);
    }

    /** @test */
    function the_coordinates_must_belong_to_user_country()
    {
        config()->set('shepper.max-locations', 1);
        $user = factory(User::class)->create();
        factory(Location::class)->create(['user_id' => $user->id]);
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('locations.store'), [
            'title'     => 'Home',
            'latitude'  => '50.109852',
            'longitude' => '8.681891',
            'radius'    => 25.0,
        ]);

        $response->assertJsonValidationErrors(['general']);
    }
}