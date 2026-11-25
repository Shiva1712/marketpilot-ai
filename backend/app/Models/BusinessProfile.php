<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    protected $fillable = [
        'business_id',
        'description',
        'target_audience',
        'brand_voice',
        'marketing_goals',
    ];

    protected function casts(): array
    {
        return [
            'brand_voice' => 'array',
            'target_audience' => 'array',
            'marketing_goals' => 'array',
        ];
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
