<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonotechnicProgramme extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'accreditationStatus',
        'approvedStream',
        'yearApproved',
        'yearGrantedInterimOrAccreditation',
       
        // Add other attributes as needed
    ];

    public static function booted()
    {
        static::retrieved(function ($programme) {
            if ($programme->expirationDate && now()->greaterThan($programme->expirationDate)) {
                $programme->accreditationStatus = Status::EXPIRED;
            }
        });
    }
    public function institution()
{
    return $this->belongsTo(MonotechnicInstitution::class);
}

}