<?php

namespace App\Exceptions;

use Illuminate\Contracts\Support\Responsable;

class TooManyLocationsException extends \RuntimeException implements Responsable
{
    /**
     * {@inheritDoc}
     */
    public function toResponse($request)
    {
        return response()->json([
            'errors' => [
                'general' => [sprintf('Cannot store more than %d locations', config('shepper.max-locations'))],
            ]
        ], 422);
    }
}