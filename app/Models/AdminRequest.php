<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRequest extends Model
{
    use HasFactory;

    protected $table = 'admin_requests';

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'accepted_by',
        'accepted_at',
    ];
}


