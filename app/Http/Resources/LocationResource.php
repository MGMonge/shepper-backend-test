<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->resource->id,
            'title'     => $this->resource->title,
            'label'     => $this->resource->label,
            'latitude'  => $this->resource->latitude,
            'longitude' => $this->resource->longitude,
            'radius'    => $this->resource->radius,
        ];
    }
}