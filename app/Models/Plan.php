<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'features' => 'object',
        'gateway_plan_id' => 'array'
    ];

    public function planPurchase()
    {
        return $this->hasMany(PlanPurchase::class, 'plan_id');
    }
    public function vendorInfo()
    {
        return $this->hasMany(VendorInfo::class, 'active_plan');
    }
}
