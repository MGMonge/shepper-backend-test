<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\TooManyLocationsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class TooManyLocationsExceptionTest extends TestCase
{
    /**
     * @var TooManyLocationsException
     */
    protected $SUT;

    public function _before(): void
    {
        $this->SUT = new TooManyLocationsException();
    }

    /** @test */
    function it_can_be_transformed_into_a_validation_error_response_with_a_friendly_message()
    {
        config()->set('shepper.max-locations', 10);
        $request = Request::create('/', 'GET');

        $actual = $this->SUT->toResponse($request);

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertSame(422, $actual->getStatusCode());
        $this->assertSame([
            'errors' => [
                'general' => ['Cannot store more than 10 locations'],
            ]
        ], $actual->getData(true));
    }
}