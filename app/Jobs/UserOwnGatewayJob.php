<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UserOwnGatewayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $all_gateway = DB::table("gateways")->where('id', '<', 999)->get();
        $gateways = $all_gateway->map(function ($gateway) {
            $parameters = $gateway->parameters ? json_decode($gateway->parameters, true) : [];
            $gateway->parameters = json_encode(array_fill_keys(array_keys($parameters), ''));
            return $gateway;
        });

        foreach ($gateways as $key => $gateway) {
            DB::table('user_gateways')->insert([
                'user_id' => $this->user->id,
                'code' => $gateway->code,
                'name' => $gateway->name,
                'sort_by' => ++$key,
                'image' => null,
                'driver' => null,
                'status' => 0,
                'parameters' => $gateway->parameters,
                'currencies' => $gateway->currencies,
                'extra_parameters' => $gateway->extra_parameters,
                'supported_currency' => $gateway->supported_currency,
                'receivable_currencies' => $gateway->receivable_currencies,
                'description' => null,
                'currency_type' => $gateway->currency_type,
                'is_sandbox' => $gateway->is_sandbox,
                'environment' => $gateway->environment,
                'is_manual' => $gateway->is_manual,
                'note' => $gateway->note,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
