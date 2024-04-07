<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllInstitutions extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'ownership',
        'year_established',
        'rector_name',
        'rector_email',
        'rector_phone',
        'proprietor_name',
        'proprietor_email',
        'proprietor_phone',
        'registrar_name',
        'registrar_email',
        'registrar_phone', 
        // Add other attributes as needed
    ];
}
