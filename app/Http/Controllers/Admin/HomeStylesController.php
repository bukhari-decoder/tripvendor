<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use App\Models\ManageMenu;
use App\Models\Page;
use Illuminate\Http\Request;

class HomeStylesController extends Controller
{
    public function home()
    {
        $data['basicControl'] = BasicControl::firstOrCreate();
        return view('admin.style.home', $data);
    }

    public function selectHome(Request $request)
    {

        $selectedHome = $request->input('val');

        $configure = BasicControl::firstOrCreate();

        if (!in_array($selectedHome, collect(config('themes')[$configure->theme]['home_version'])->keys()->toArray())) {
            return response()->json(['error' => "Invalid Request"], 422);
        }
        $configure->home_style = $selectedHome;
        $configure->save();

       $homePage = collect(config('themes')[$configure->theme]['home_version'])->keys();

        $homePagesByTheme = Page::select('id', 'slug', 'home_name', 'template_name', 'status')->whereIn('home_name', $homePage)
            ->where('template_name', $configure->theme)
            ->get();

        $activeHomePage = $homePagesByTheme->firstWhere('home_name', $selectedHome);

        if ($activeHomePage) {
            $activeHomePage->update(['slug' => '/']);
        }

        foreach ($homePagesByTheme as $homePage) {
            if ($homePage->home_name !== $selectedHome) {
                $homePage->update([
                    'slug' => $homePage->home_name,
                ]);
            }
        }

        $message = ' "' . $request->title . '" Home style selected.';
        return response()->json(['message' => $message], 200);
    }
}
