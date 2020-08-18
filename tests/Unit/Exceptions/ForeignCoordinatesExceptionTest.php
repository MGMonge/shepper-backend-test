<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\ForeignCoordinatesException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class ForeignCoordinatesExceptionTest extends TestCase
{
    /**
     * @var ForeignCoordinatesException
     */
    protected $SUT;

    public function _before(): void
    {
        $this->SUT = new ForeignCoordinatesException;
    }

    /** @test */
    function it_can_be_transformed_into_a_validation_error_response_with_a_friendly_message()
    {
        $user    = factory(User::class)->make(['country_code' => 'GB']);
        $request = Request::create('/', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $actual = $this->SUT->toResponse($request);

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertSame(422, $actual->getStatusCode());
        $this->assertSame([
            'errors' => [
                'general' => ["The coordinates does not belong to user's country: GB"],
            ]
        ], $actual->getData(true));
    }
}