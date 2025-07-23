<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rom extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_name',
        'property_address',
        'property_type',
        'floors',
        'buildings',
        'parking_area',
        'connection_between_buildings',
        'coverage_area',
        'average_density',
        'construction_status',
        'system_type',
        'pptx_file_path',
        'pdf_file_path'
    ];
}