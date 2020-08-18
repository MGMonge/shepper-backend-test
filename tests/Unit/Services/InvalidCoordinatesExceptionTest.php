<?php

namespace Tests\Unit\Services;

use App\Services\Geolocation\InvalidCoordinatesException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class InvalidCoordinatesExceptionTest extends TestCase
{
    /**
     * @var InvalidCoordinatesException
     */
    protected $SUT;

    public function _before(): void
    {
        $this->SUT = new InvalidCoordinatesException;
    }

    /** @test */
    function it_can_be_transformed_into_a_validation_error_response_with_a_friendly_message()
    {
        $request = Request::create('/', 'GET', ['latitude' => 1, 'longitude' => -1]);

        $actual = $this->SUT->toResponse($request);

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertSame(422, $actual->getStatusCode());
        $this->assertSame([
            'errors' => [
                'general' => ['The coordinates [1,-1] are invalid.'],
            ]
        ], $actual->getData(true));
    }
}