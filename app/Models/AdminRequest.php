<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRequest extends Model
{
    use HasFactory;

    protected $table = 'admin_requests';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'accepted_by',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'date',
    ];
}


