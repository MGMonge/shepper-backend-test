<?php

namespace App\Exceptions;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class ForeignCoordinatesException extends \RuntimeException implements Responsable
{
    /**
     * {@inheritDoc}
     */
    public function toResponse($request)
    {
        return new JsonResponse([
            'errors' => [
                'general' => [sprintf("The coordinates does not belong to user's country: %s", $request->user()->country_code)],
            ]
        ], 422);
    }
}