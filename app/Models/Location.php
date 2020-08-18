<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'title',
        'label',
        'latitude',
        'longitude',
        'radius',
    ];
}
