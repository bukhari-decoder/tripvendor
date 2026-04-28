<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Fund;
use App\Models\Gateway;
use App\Models\Package;
use App\Models\UserGateway;
use Illuminate\Http\Request;
use App\Traits\PaymentValidationCheck;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{

    use PaymentValidationCheck;

    public function supportedCurrency(Request $request)
    {
        if ($request->package_id){
            $package =Package::where('id', $request->package_id)->first();
        }
        $table = $request->type == 'booking' ? getGatewayModel($package) : Gateway::class;
        $gateway = $table::where('id', $request->gateway)->first();
        $pmCurrency =  $gateway->receivable_currencies[0]->name ?? $gateway->receivable_currencies[0]->currency;
        $isCrypto = $gateway->id < 1000 && checkTo($gateway->currencies, $pmCurrency) == 1;

        return response([
            'success' => true,
            'data' => $gateway->supported_currency,
            'currencyType' => $isCrypto ? 0 : 1,
        ]);
    }

    public function checkAmount(Request $request)
    {
        if ($request->ajax()) {
            $amount = $request->amount;
            $selectedCurrency = $request->selected_currency;
            $selectGateway = $request->select_gateway;
            $selectedCryptoCurrency = $request->selectedCryptoCurrency;
            $amountType = $request->amountType ?? 'base';
            if ($request->package_id){
                $package =Package::where('id', $request->package_id)->first();
            }
            $table = ($request->gatewayType == 'booking') ? getGatewayModel($package) : Gateway::class;

            $data = $this->checkAmountValidate($amount, $selectedCurrency, $selectGateway, $selectedCryptoCurrency, $amountType,$table);
            return response()->json($data);
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }

}
