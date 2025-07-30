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
        'system_type',
        'allocated_ip_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'remote_unit_quantity' => 'integer',
        'master_unit_quantity' => 'integer',
        'bda_quantity' => 'integer',
    ];

    /**
     * Get the allocated IP for this network management entry.
     */
    public function allocatedIp()
    {
        return $this->belongsTo(Ip::class, 'allocated_ip_id');
    }
}

