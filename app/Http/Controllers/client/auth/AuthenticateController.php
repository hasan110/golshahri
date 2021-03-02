<?php

namespace App\Http\Controllers\client\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\User;
use App\Models\Admin\Admin;
use App\Models\AppModels\LoginSmsCode;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserRegistered;
use Carbon\Carbon;

class AuthenticateController extends Controller
{
    public function sendCode(Request $request)
    {
        $request->validate([
            'number' => ['required','regex:/(09)[0-9]{9}/'],
        ]);

        $last_sent_code=LoginSmsCode::where('number',$request->number)->latest()->first();

        if(!is_null($last_sent_code)){
            if($last_sent_code->created_at > Carbon::now()->subMinutes(2)){
                return response()->json(
                    ['status' => 'failed' , 'message'=>'کد تایید قبلا ارسال شده است .'],
                    400
                );
            }
        }

        $token = rand(1000 , 9999);
        
        // $curl = curl_init('https://api.ghasedak.io/v2/verification/send/simple');
        // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        // curl_setopt($curl, CURLOPT_POSTFIELDS, 'receptor='.$request->number.'&template=verify&type=1&param1='.$token);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        // curl_setopt($curl, CURLOPT_ENCODING, '');
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        //     "apikey: 8df596628385b6516b021da31169260ee782d991392fa6b1923c8090e62c80b6",
        //     "cache-control: no-cache",
        //     "content-type: application/x-www-form-urlencoded",
        // ));
        // $result = curl_exec($curl);
        // $error = curl_error($curl);
        // $result = json_decode($result, true);
        // curl_close($curl);

        // if ($error) {
        //     return response()->json(
        //         ['status' => 'failed' , 'message'=>'مشکل در ارسال پیام پیش آمده است .'],
        //         400
        //     );
        // }

        $code=new LoginSmsCode;
        $code->number=$request->number;
        $code->code=$token;
        $code->save();

        return response()->json(
            ['status' => 'success' , 'message'=>'کد تایید با موفقیت ارسال شد .'],
            200
        );  
    }
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => ['required'],
            'number' => ['required','regex:/(09)[0-9]{9}/'],
        ]);

        $code = LoginSmsCode::whereNumber($request->number)->latest()->first();

        if ($code->code == $request->code) {
            if ($code->created_at < Carbon::now()->subMinutes(2)) {
                return response()->json([
                    'error' => 'codeExpired',
                    'message' => 'متاسفانه کد ارسالی اعتبار ندارد',
                ], 400);
            }
        } else {
            return response()->json([
                'error' => 'codeIncorrect',
                'message' => 'کد وارد شده صحیح نمی باشد',
            ], 400);
        }

        $user = User::whereNumber($request->number)->first();
        
        if ($user !== null) {
            return response()->json([
                'status' => 'old',
                'data' => $user,
                'message' => 'کاربر قدیمی است',
            ], 200);
        } else {
            return response()->json([
                'status' => 'new',
                'data' => '',
                'message' => 'کاربر جدید است',
            ], 200);
        }
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'number' => ['required','unique:users','regex:/(09)[0-9]{9}/'],
        ]);

        $auth_token = md5(rand(1000,9999).time());

        $user = User::create([
            'name'=>$request->name,
            'number'=>$request->number,
            'is_block'=>0,
            'image'=>null,
            'auth_token'=>$auth_token
        ]);

        $notifiable_admins = Admin::role('super_admin')->get(); 

        Notification::send($notifiable_admins , new NewUserRegistered($user));

        return response()->json(
            ['data' => $user , 'message'=>'ثبت نام با موفقیت انجام شد'],
            201
        );
    }
    public function getUserData(Request $request)
    {
        $request->validate([
            'auth_token' => ['required']
        ]);

        $user = User::where('auth_token' , $request->auth_token)->first();

        return response()->json(
            $user,
            200
        );
    }
}
