<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'name',
        'industry',
        'description',
        'website',
        'location',
        'status',
        'timezone',
    ];

    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'business_users'
        )->withPivot('role')->withTimestamps();
    }

    public function profile()
    {
        return $this->hasOne(BusinessProfile::class);
    }
    
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }
    
}
