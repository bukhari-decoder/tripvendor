<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGateway extends Model
{
    protected $table = 'user_gateways';
    protected $guarded = ['id'];

    protected $casts = [
        'currency' => 'object',
        'supported_currency' => 'object',
        'receivable_currencies' => 'object',
        'parameters' => 'object',
        'currencies' => 'object',
        'extra_parameters' => 'object',
    ];
    public function scopeAutomatic()
    {
        return $this->where('id', '<', 1000);
    }

    public function scopeManual()
    {
        return $this->where('id', '>=', 1000);
    }

    public function countGatewayCurrency()
    {
        $currencyLists = $this->currencies->{0} ?? $this->currencies->{1};
        $count = 0;
        foreach ($currencyLists as $currency) {
            $count++;
        }
        return $count;
    }


}
