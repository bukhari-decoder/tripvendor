<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use App\Models\Page;
use Illuminate\Http\Request;

class PackageStylesController extends Controller
{
    public function style()
    {
        $data['basicControl'] = BasicControl::firstOrCreate();
        return view('admin.style.package', $data);
    }

    public function selectStyle(Request $request)
    {

        $selectedStyle = $request->input('val');

        $configure = BasicControl::firstOrCreate();

        if (!in_array($selectedStyle, collect(config('packages')[$configure->theme]['listStyle'])->keys()->toArray())) {
            return response()->json(['error' => "Invalid Request"], 422);
        }
        $configure->package_list_style = $selectedStyle;
        $configure->save();

        $message = ' "' . $request->title . '" Package style selected.';
        return response()->json(['message' => $message], 200);
    }
}
