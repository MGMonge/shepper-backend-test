<?php

namespace App\Exceptions;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class TooManyLocationsException extends \RuntimeException implements Responsable
{
    /**
     * {@inheritDoc}
     */
    public function toResponse($request)
    {
        return new JsonResponse([
            'errors' => [
                'general' => [sprintf('Cannot store more than %d locations', app(Repository::class)->get('shepper.max-locations'))],
            ]
        ], 422);
    }
}