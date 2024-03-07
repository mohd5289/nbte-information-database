<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VEI extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function programmes()
{
    return $this->hasMany(VEIProgramme::class);
}
}
