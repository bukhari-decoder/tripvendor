<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BasicService;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PwaController extends Controller
{
    use Upload;

    public function create(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('admin.control_panel.pwa');
        } elseif ($request->isMethod('POST')) {
            $request->validate([
                'app_name' => 'string|max:255',
                'short_name' => 'string|max:50',
                'background_color' => 'regex:/^#[0-9a-fA-F]{6}$/',
                'theme_color' => 'regex:/^#[0-9a-fA-F]{6}$/',
                'display' => 'in:standalone,fullscreen,minimal-ui',
                'status_bar' => 'in:default,black,white',
            ]);

            $data = array_merge($request->all(), [
                'app_name' => $request->app_name ?? 'Touriety',
                'short_name' => $request->short_name ?? 'Touriety',
                'background_color' => $request->background_color ?? '#fff',
                'theme_color' => $request->theme_color ?? '#000000',
                'display' => $request->display ?? 'standalone',
                'status_bar' => $request->status_bar ?? 'default',
            ]);
            $env = [
                'APP_NAME' => '"' . $data['app_name'] . '"',
                'SHORT_NAME' => '"' . $data['short_name'] . '"',
                'BACKGROUND_COLOR' => '"' . $data['background_color'] . '"',
                'THEME_COLOR' => '"' . $data['theme_color'] . '"',
                'DISPLAY' => '"' . $data['display'] . '"',
                'STATUS_BAR' => '"' . $data['status_bar'] . '"',
            ];

            $basicService = new BasicService();
            $basicService->setEnv($env);


            if ($request->hasFile('icon')) {
                $file = $request->icon;
                foreach (config('pwacontrol.icons') as $key => $icon) {
                    $upload = $this->fileUpload($file, config('filelocation.pwa.path'), 'icon-' . $key, $key, 'png', 90, $icon, config('pwacontrol.driver'));
                    $this->saveConfig($upload, $key, 'icons');
                }

            }
        }

        if ($request->hasFile('splash')) {
            $file = $request->splash;
            foreach (config('pwacontrol.splash') as $key => $splash) {
                $upload = $this->fileUpload($file, config('filelocation.pwa.path'), 'splash-' . $key, $key, 'png', 90, $icon, config('pwacontrol.driver'));
                $this->saveConfig($upload, $key, 'splash');
            }
        }
        return back()->with('success', 'Successfully update pwa configuration');
    }

    public function saveConfig($upload, $key, $type): void
    {
        config(['pwacontrol.driver' => $upload['driver']]);
        config(['pwacontrol.' . $type . '.' . $key => $upload['path']]);
        $fp = fopen(base_path() . '/config/pwacontrol.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('pwacontrol'), true) . ';');
        fclose($fp);

        Artisan::call('optimize:clear');

        if ($type == 'icons') {
            config(['laravelpwa.manifest.' . $type . '.' . $key . '.path' => getFile($upload['driver'], $upload['path'])]);
        } else {
            config(['laravelpwa.manifest.' . $type . '.' . $key => getFile($upload['driver'], $upload['path'])]);
        }
        $fp = fopen(base_path() . '/config/laravelpwa.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('laravelpwa'), true) . ';');
        fclose($fp);

    }
}
