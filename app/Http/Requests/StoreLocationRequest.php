<?php

namespace App\Http\Requests;

class StoreLocationRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'     => 'required|between:3,30',
            'latitude'  => 'required',
            'longitude' => 'required',
            'radius'    => 'required|numeric|between:0.5,50',
        ];
    }
}
