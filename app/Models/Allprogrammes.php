<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allprogrammes extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'isTechnologyBased',
        'faculty',
        
        // Add other attributes as needed
    ];
}
