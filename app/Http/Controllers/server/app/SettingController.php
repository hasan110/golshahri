<?php

namespace App\Http\Controllers\server\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppModels\Setting;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function settingData(Request $request)
    {
        $setting = Setting::find(1);
        $setting['new_advertise_default_image'] = null;
        $setting['new_logo_image'] = null;
        $setting['new_instagram_image'] = null;
        $setting['new_telegram_image'] = null;
        return response()->json(
            $setting,
            200
        );
    }

    public function settingEdit(Request $request)
    {
        $request->validate([
            'sms_service_api_key' => ['required'],
            'default_phone_number' => ['required'],
            'about_us_text' => ['required'],
        ]);

        $setting = Setting::find(1);

        if($request->hasFile('new_advertise_default_image')){
            if ($setting->advertise_default_image) {
                File::delete(public_path().'/uploads/'.$setting->advertise_default_image);
            }

            $image = $request->file('new_advertise_default_image');
            $file_name = 'IMG-'.time().'.'.$image->getClientOriginalExtension();
            $image->move('uploads/setting',$file_name);
            $file_link = 'setting/'.$file_name;
        }else{
            $file_link = $setting->advertise_default_image;
        }

        if($request->hasFile('new_logo_image')){
            if ($setting->logo_image) {
                File::delete(public_path().'/uploads/'.$setting->logo_image);
            }

            $logo_image = $request->file('new_logo_image');
            $logo_name = 'LOGOIMG-'.time().'.'.$logo_image->getClientOriginalExtension();
            $logo_image->move('uploads/setting',$logo_name);
            $logo_link = 'setting/'.$logo_name;
        }else{
            $logo_link = $setting->logo_image;
        }


        if($request->hasFile('new_instagram_image')){
            if ($setting->instagram_image) {
                File::delete(public_path().'/uploads/'.$setting->instagram_image);
            }

            $instagram_image = $request->file('new_instagram_image');
            $instagram_name = 'instagramIMG-'.time().'.'.$instagram_image->getClientOriginalExtension();
            $instagram_image->move('uploads/setting',$instagram_name);
            $instagram_link = 'setting/'.$instagram_name;
        }else{
            $instagram_link = $setting->instagram_image;
        }


        if($request->hasFile('new_telegram_image')){
            if ($setting->telegram_image) {
                File::delete(public_path().'/uploads/'.$setting->telegram_image);
            }

            $telegram_image = $request->file('new_telegram_image');
            $telegram_name = 'telegramIMG-'.time().'.'.$telegram_image->getClientOriginalExtension();
            $telegram_image->move('uploads/setting',$telegram_name);
            $telegram_link = 'setting/'.$telegram_name;
        }else{
            $telegram_link = $setting->telegram_image;
        }

        $setting->update([
            'sms_service_api_key'=>$request->sms_service_api_key,
            'default_phone_number'=>$request->default_phone_number,
            'about_us_text'=>$request->about_us_text,
            'advertise_default_image'=>$file_link,
            'logo_image'=>$logo_link,
            'telegram_image'=>$telegram_link,
            'instagram_image'=>$instagram_link,
            'telegram_address'=>$request->telegram_address,
            'instagram_address'=>$request->instagram_address,
        ]);

        return response()->json(
            [$setting , 'message'=>'تنظیمات با موفقیت بروزرسانی شد'],
            201
        );
    }
}
