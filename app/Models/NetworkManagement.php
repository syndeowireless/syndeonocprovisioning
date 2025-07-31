<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkManagement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'networkmanagement';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_name',
        'oem',
        'property_address',
        'remote_unit_quantity',
        'master_unit_quantity',
        'bda_quantity',
        'latitude',
        'longitude',
        'property_type',
        'average_density',
        'system_type'
    ];

}

