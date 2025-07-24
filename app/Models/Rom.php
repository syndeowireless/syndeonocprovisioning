<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rom extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_name',
        'oem',
        'property_address',
        'master_unit_quantity',
        'bda_quantity',
        'latitude',
        'longitude',
        'remote_unit_quantity',
        'property_type',
        'average_density',
        'system_type'
    ];
}