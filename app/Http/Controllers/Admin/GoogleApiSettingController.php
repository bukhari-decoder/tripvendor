<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoogleMapApi;
use App\Models\GoogleSheetApi;
use Illuminate\Http\Request;
use App\Traits\Upload;

class GoogleApiSettingController extends Controller
{
    use Upload;
    public function googleMapAPISetting()
    {
        $data['apis'] = GoogleMapApi::all();
        return view('admin.control_panel.google_api_setting',$data);
    }

    public function googleMapAPIAdd(Request $request){
        $request->validate([
            'key' => 'required',
        ]);
        if ($request->key){
            $api = new GoogleMapApi();
            $api->api_key = $request->key;
            $api->save();

            $this->updateEnv('GOOGLE_MAPS_API_KEY', $request->key);

            return back()->with('success','Api Added Successfully.');
        }else{
            return back()->with('error','Something is Wrong Here.');
        }
    }
    public function googleMapAPIEdit(Request $request,$id){
        $request->validate([
            'key' => 'required',
        ]);
        $key = GoogleMapApi::where('id',$id)->first();

        if ($key){
            $key->api_key = $request->key;
            $key->save();

            $this->updateEnv('GOOGLE_MAPS_API_KEY', $request->key);
            return back()->with('success','Api Key Changed Successfully.');
        }else{
            return back()->with('error','Key Not Found.');
        }
    }

    protected function updateEnv($key, $value)
    {
        $envPath = base_path('.env');
        $envFile = file_get_contents($envPath);

        $value = '"' . $value . '"';

        if (strpos($envFile, $key) !== false) {
            $envFile = preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $envFile);
        } else {
            $envFile .= "\n{$key}={$value}";
        }

        file_put_contents($envPath, $envFile);
    }

    public function googleMapAPIEditStatus(Request $request,$id){

        $key = GoogleMapApi::where('id',$id)->first();

        if ($key){
            if ($key->status == 1){
                $key->status = 0;
                $key->save();
            }elseif ($key->status == 0){
                $key->status = 1;
                $key->save();
            }else{
                return back()->with('error','Something is Wrong');
            }
            return back()->with('success','Api Status Changed Successfully.');
        }else{
            return back()->with('error','Api Key Not Found.');
        }
    }

    public function googleAPICredentialUpload(Request $request)
    {

        $googleSheetCredential = GoogleSheetApi::firstOrFail();

        $request->validate([
              'credential' => 'nullable',
        ]);

        if ($request->hasFile('credential')) {
            try {
                $file = $this->fileUpload($request->credential, config('filelocation.googleTranslateCredential.path'), config('filesystems.default'));
                if ($file) {
                    $file_credential = $file['path'];
                    $file_driver = 'local';
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'File could not be uploaded.');
            }
        }

        $response = $googleSheetCredential->update([
            'api_credential_file' => $file_credential,
            'file_driver' => $file_driver,
        ]);

        if (!$response){
            throw  new \Exception('Something went wrong');
        }


        return back()->with('success', 'File uploaded successfully.');

    }
}
