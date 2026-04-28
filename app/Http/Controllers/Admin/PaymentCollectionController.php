<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use Illuminate\Http\Request;

class PaymentCollectionController extends Controller
{
    public function index()
    {
        return view('admin.control_panel.payment_collection');
    }

    public function update(Request $request)
    {
        $request->validate([
            'payment_collection' => 'required|in:0,1',
        ]);

        $basic = BasicControl::first();

        if (!$basic) {
            return response()->json(['success' => false, 'message' => 'Basic settings not found.'], 404);
        }

        $basic->payment_collection = $request->input('payment_collection');
        $basic->save();

        return response()->json(['success' => true, 'message' => 'Payment collection updated successfully.']);
    }
}
