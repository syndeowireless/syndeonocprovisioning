<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $fillable = [
        'first_usable_ip',
        'last_usable_ip',
        'in_use',
        'network_range',
        'description',
        
    ];
}
