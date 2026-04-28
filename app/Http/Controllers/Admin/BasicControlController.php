<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Facades\App\Services\BasicService;
use Facades\App\Services\CurrencyLayerService;
use Exception;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class BasicControlController extends Controller
{
    public function index($settings = null)
    {
        $settings = $settings ?? 'settings';
        abort_if(!in_array($settings, array_keys(config('generalsettings'))), 404);
        $settingsDetails = config("generalsettings.{$settings}");
        return view('admin.control_panel.settings', compact('settings', 'settingsDetails'));
    }

    public function basicControl()
    {
        $data['basicControl'] = basicControl();
        $data['timeZones'] = timezone_identifiers_list();
        $data['dateFormat'] = config('dateformat');
        return view('admin.control_panel.basic_control', $data);
    }

    public function basicControlUpdate(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|min:1|max:100',
            'time_zone' => 'required|string',
            'base_currency' => 'required|string|min:1|max:100',
            'currency_symbol' => 'required|string|min:1|max:100',
            'fraction_number' => 'required|integer|not_in:0',
            'paginate' => 'required|integer|not_in:0',
            'user_paginate' => 'required|integer|not_in:0',
            'free_listing' => 'required|integer',
            'date_format' => 'required|string',
            'admin_prefix' => 'required|string|min:3|max:100',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
        ]);

        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'site_title' => $request->site_title,
                'time_zone' => $request->time_zone,
                'base_currency' => $request->base_currency,
                'currency_symbol' => $request->currency_symbol,
                'fraction_number' => $request->fraction_number,
                'date_time_format' => $request->date_format,
                'paginate' => $request->paginate,
                'user_paginate' => $request->user_paginate,
                'free_listing' => $request->free_listing,
                'admin_prefix' => $request->admin_prefix,
                'primary_color' => $request->primary_color,
                'secondary_color' => $request->secondary_color,
            ]);

            if (!$response)
                throw new Exception('Something went wrong, when updating data');

            $env = [
                'APP_TIMEZONE' => $response->time_zone,
                'APP_DEBUG' => $response->error_log == 0 ? 'true' : 'false'
            ];

            BasicService::setEnv($env);
            session()->flash('success', 'Basic control has been successfully configured');
            Artisan::call('optimize:clear');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function basicControlActivityUpdate(Request $request)
    {
        $request->validate([
            'strong_password' => 'nullable|numeric|in:0,1',
            'ai_feature' => 'nullable|numeric|in:0,1',
            'registration' => 'nullable|numeric|in:0,1',
            'error_log' => 'nullable|numeric|in:0,1',
            'is_active_cron_notification' => 'nullable|numeric|in:0,1',
            'has_space_between_currency_and_amount' => 'nullable|numeric|in:0,1',
            'is_force_ssl' => 'nullable|numeric|in:0,1',
            'is_currency_position' => 'nullable|string|in:left,right',
            'automatic_currency_update_permission' => 'nullable|numeric|in:0,1'
        ]);

        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'error_log' => $request->error_log,
                'strong_password' => $request->strong_password,
                'ai_feature' => $request->ai_feature,
                'registration' => $request->registration,
                'is_active_cron_notification' => $request->is_active_cron_notification,
                'has_space_between_currency_and_amount' => $request->has_space_between_currency_and_amount,
                'is_currency_position' => $request->is_currency_position,
                'is_force_ssl' => $request->is_force_ssl,
                'automatic_currency_update_permission' => $request->automatic_currency_update_permission
            ]);

            if (!$response)
                throw new Exception('Something went wrong, when updating the data.');

            session()->flash('success', 'Basic control has been successfully configured.');
            Artisan::call('optimize:clear');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function currencyExchangeApiConfig()
    {
        $data['scheduleList'] = config('schedulelist.schedule_list');
        $data['basicControl'] = basicControl();
        return view('admin.control_panel.exchange_api_setting', $data);
    }

    public function currencyExchangeApiConfigUpdate(Request $request)
    {
        $request->validate([
            'currency_layer_access_key' => 'required|string',
        ]);

        try {
            $basicControl = basicControl();
            $basicControl->update([
                'currency_layer_access_key' => $request->currency_layer_access_key,
                'currency_layer_auto_update' => $request->currency_layer_auto_update,
                'currency_layer_auto_update_at' => $request->currency_layer_auto_update_at,
            ]);
            return back()->with('success', 'Configuration changes successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function openAiUpdate(Request $request)
    {
        $basicControl = basicControl();
        if ($request->isMethod('get')) {
            return view('admin.control_panel.openAi', compact('basicControl'));
        } elseif ($request->isMethod('post')) {

            $purifiedData = Purify::clean($request->all());
            $purifiedData['image'] = $request->image;
            $validator = Validator::make($purifiedData, [
                'open_ai_key' => 'required|string|min:5',
                'open_ai_model' => 'required|string|min:3',
                'open_ai_max_token' => 'required|min:1',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $purifiedData = (object)$purifiedData;
            $basicControl->open_ai_key = $purifiedData->open_ai_key;
            $basicControl->open_ai_model = $purifiedData->open_ai_model;
            $basicControl->open_ai_max_token = $purifiedData->open_ai_max_token;
            $basicControl->open_ai_status = $purifiedData->open_ai_status;
            if ($basicControl->open_ai_status == 1) {
                $basicControl->gemini_status = 0;
            }
            $basicControl->save();

            return back()->with('success', 'OpenAi has been updated.');
        }
    }

    public function geminiUpdate(Request $request)
    {
        $basicControl = basicControl();
        if ($request->isMethod('get')) {
            return view('admin.control_panel.gemini', compact('basicControl'));
        } elseif ($request->isMethod('post')) {

            $purifiedData = Purify::clean($request->all());
            $purifiedData['image'] = $request->image;
            $validator = Validator::make($purifiedData, [
                'gemini_key' => 'required|string|min:5',
                'gemini_model' => 'required|string|min:3',
                'gemini_max_token' => 'required|min:1',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $purifiedData = (object)$purifiedData;
            $basicControl->gemini_key = $purifiedData->gemini_key;
            $basicControl->gemini_model = $purifiedData->gemini_model;
            $basicControl->gemini_max_token = $purifiedData->gemini_max_token;
            $basicControl->gemini_status = $purifiedData->gemini_status;
            if ($basicControl->gemini_status == 1) {
                $basicControl->open_ai_status = 0;
            }
            $basicControl->save();


            return back()->with('success', 'Google Gemini has been updated.');
        }
    }
}
