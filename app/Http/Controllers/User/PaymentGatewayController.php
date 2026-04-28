<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserGateway;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentGatewayController extends Controller
{
    use Upload;

    public function index(Request $request)
    {
        $search = $request->all();
        $data['gateways'] = UserGateway::where('user_id', auth()->id())
            ->when(isset($search['name']), function ($query) use ($search) {
                $query->where('user_id', auth()->id())
                    ->where('name', 'LIKE', '%' . $search['name'] . '%');
            })->orderBy('sort_by', 'asc')->where('status', 1)->get();


        return view(template() . 'user.gateway.index', $data);
    }

    public function edit($id, Request $request)
    {

        $method = UserGateway::where('user_id', auth()->id())->findOrFail($id);

        if ($request->method() == 'GET') {
            return view(template() . 'user.gateway.edit', compact('method'));
        }
        if ($request->method() == 'PUT') {
            $rules = [
                'name' => 'required|string',
                'receivable_currencies' => 'required|array',
                'receivable_currencies.*.name' => 'required|string',
                'receivable_currencies.*.currency_symbol' => 'required|string|max:255',
                'receivable_currencies.*.conversion_rate' => 'required|numeric',
                'receivable_currencies.*.min_limit' => 'required|numeric',
                'receivable_currencies.*.max_limit' => 'required|numeric',
                'receivable_currencies.*.percentage_charge' => 'required|numeric',
                'receivable_currencies.*.fixed_charge' => 'required|numeric',
                'is_active' => 'nullable|integer|in:0,1',
                'test_environment' => 'sometimes|required|string|in:test,live',
                'image' => 'nullable|mimes:png,jpeg,gif|max:4096',
            ];
            $customMessages = [
                'receivable_currencies.*.currency_symbol.required' => 'The receivable currency currency symbol field is required.',
                'receivable_currencies.*.conversion_rate.required' => 'The receivable currency convention rate field is required.',
                'receivable_currencies.*.conversion_rate.numeric' => 'The convention rate for receivable currency must be a number.',
                'receivable_currencies.*.min_limit.required' => 'The receivable currency min limit field is required.',
                'receivable_currencies.*.min_limit.numeric' => 'The min limit for receivable currency must be a number.',
                'receivable_currencies.*.max_limit.required' => 'The receivable currency max limit field is required.',
                'receivable_currencies.*.max_limit.numeric' => 'The max limit for receivable currency must be a number.',
                'receivable_currencies.*.percentage_charge.required' => 'The receivable currency percentage charge field is required.',
                'receivable_currencies.*.percentage_charge.numeric' => 'The percentage charge for receivable currency must be a number.',
                'receivable_currencies.*.fixed_charge.required' => 'The receivable currency fixed charge name is required.',
                'receivable_currencies.*.fixed_charge.numeric' => 'The fixed charge for receivable currency must be a number.',
            ];

            $parameters = [];

            foreach ($request->except('_token', '_method', 'image') as $k => $v) {
                foreach ($method->parameters as $key => $cus) {
                    if ($k != $key) {
                        continue;
                    } else {
                        $rules[$key] = 'required|max:191';
                        $parameters[$key] = $v;
                    }
                }
            }

            $validator = Validator::make($request->all(), $rules, $customMessages);

            if ($validator->fails()) {

                $names = collect(request()->receivable_currencies)
                    ->filter(function ($item) {
                        return isset($item['name']) && $item['name'] !== null;
                    })
                    ->pluck('name')
                    ->toArray();
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput($request->input())
                    ->with('selectedCurrencyList', $names);
            }
            if ($request->hasFile('image')) {
                try {
                    $image = $this->fileUpload($request->image, config('filelocation.user_gateway.path'), null, config('filelocation.user_gateway.size'), 'webp', 99, $method->image, $method->driver);
                    if ($image) {
                        $gatewayImage = $image['path'];
                        $driver = $image['driver'];
                    }
                } catch (\Exception $exp) {
                    return back()->with('error', 'Image could not be uploaded.');
                }
            }

            try {
                $collection = collect($request->receivable_currencies);
                $supportedCurrency = $collection->pluck('name')->all();

                $response = $method->update([
                    'supported_currency' => $supportedCurrency,
                    'receivable_currencies' => $request->receivable_currencies,
                    'parameters' => $parameters,
                    'image' => $gatewayImage ?? $method->image,
                    'driver' => $driver ?? $method->driver,
                    'environment' => $request->test_environment ?? null,
                    'status' => $request->is_active
                ]);
                if (!$response) {
                    throw new \Exception('Unexpected error! Please try again.');
                }
                return back()->with('success', 'Gateway data has been updated.');
            } catch (\Exception $exp) {
                return back()->with('error', $exp->getMessage());
            }
        }
    }

    public function manage()
    {
        $gateways = UserGateway::where('user_id', auth()->id())->get();

        return view(template().'user.gateway.manage', compact('gateways'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:user_gateways,id',
            'status' => 'required|in:0,1',
        ]);

        $gateway = UserGateway::where('user_id', auth()->id())
            ->where('id', $request->id)
            ->first();

        if (!$gateway) {
            return response()->json(['error' => 'Gateway not found'], 404);
        }

        $gateway->status = $request->status;
        $gateway->save();


        $msg = $gateway->status == 1 ? 'Payment gateway activated successfully.' : 'Payment gateway desctivated successfully.';
        return response()->json(['msg' => $msg, 'status' => $gateway->status], 200);
    }

    public function delete($id)
    {
        $gateway = UserGateway::where('user_id', auth()->id())->findOrFail($id);
        $this->fileDelete($gateway->driver, $gateway->image);
        $gateway->delete();
        return back()->with('success', 'Gateway Deleted Successfully');
    }
}
