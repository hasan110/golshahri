<?php

namespace App\Http\Controllers\client\advertise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertise\Advertise;
use App\Models\Advertise\AdvertisePicture;
use App\Models\User\User;
use App\Models\Admin\Admin;
use App\Models\AppModels\Region;
use Illuminate\Support\Facades\Notification;
use Auth;
use Morilog\Jalali\Jalalian;
use App\Notifications\NewAdvertiseCreated;
use Illuminate\Support\Facades\File;
use App\Models\AppModels\Setting;
use App\Models\AppModels\View;
use App\Models\AppModels\Picture;

class UserAdvertiseController extends Controller
{
    public function advertiseList(Request $request)
    {
        $advertises = Advertise::latest()->with('region')->whereConfirmed(1)->paginate(30);
        foreach($advertises as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%m/%d');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%m/%d');
            if($item->pictures->count() > 0){
                $item['image'] = $item->pictures[0]->link;
            }else{
                $item['image'] = Setting::find(1)->advertise_default_image;
            }
        }

        return response()->json($advertises,200);
    }
    public function advertiseCreate(Request $request)
    {
        $validation = $this->getValidationFactory()->make($request->all(), [
            'auth_token' => ['required'],
            'title' => ['required'],
            'type' => ['required'],
            'status' => ['required'],
            'region_id' => ['required'],
            'area' => ['required']
        ]);
    
        if ($validation->fails()) {
            return response()->json(
                ['status' => 'failed' , 'message'=>'لطفا همه فیلد های الزامی را پر کنید .'],
                400
            );
        }

        $status = $request->status;
        $type = $request->type;
        if($request->is_in_lane !== null && $request->is_in_lane == 1){
            $is_in_lane = 1;
        }else{
            $is_in_lane = 0;
        }
        if($request->length_house !== 'null' && $request->length_house !== null){
            $length_house = $request->length_house;
        }else{
            $length_house = null;
        }
        if($request->lane_width !== 'null' && $request->lane_width !== null){
            $lane_width = $request->lane_width;
        }else{
            $lane_width = null;
        }
        $roof_number = $request->roof_number;
        $skeleton_state = $request->skeleton_state;
        $lifetime_state = $request->lifetime_state;
        $price = $request->price;
        $rent = $request->rent;
        $meed = $request->meed;

        switch ($type) {
            case 1:
                $rent = null;
                $meed = null;
            break;
            case 2:
                $meed = null;
                $skeleton_state = null;
                $price = null;
                $length_house = null;
                $lane_width = null;
            break;
            case 3:
                $skeleton_state = null;
                $price = null;
                $length_house = null;
                $lane_width = null;
            break;
            case 4:
                $meed = null;
                $rent = null;
                $length_house = null;
                $lane_width = null;
            break;
            case 5:
                $skeleton_state = null;
                $price = null;
                $length_house = null;
                $lane_width = null;
            break;
        }

        if($status == 'مغازه'){
            $lane_width = null;
            $roof_number = null;
            $skeleton_state = null;
        }elseif($status == 'زمین'){
            $lifetime_state = null;
            $roof_number = null;
            $skeleton_state = null;
        }

        $user = User::where('auth_token' , $request->auth_token)->first();

        $advertise = Advertise::create([
            'user_id'=>$user->id,
            'title'=>$request->title,
            'type'=>$type,
            'status'=>$status,
            'region_id'=>$request->region_id,
            'address'=>$request->address,
            'street'=>'',
            'is_in_lane'=>$is_in_lane,
            'lane_width'=>$lane_width,
            'area'=>$request->area,
            'length_house'=>$length_house,
            'roof_number'=>$roof_number,
            'lifetime_state'=>$lifetime_state,
            'skeleton_state'=>$skeleton_state,
            'price'=>$price,
            'rent'=>$rent,
            'meed'=>$meed,
            'description'=>$request->description,
            'confirmed'=>0,
        ]);

        if($request->hasFile('images')){
            foreach($request->images as $key=>$item){
                $image = $request->images[$key];
                $file_name = 'ID'.$advertise->id.'-IMG'. ($key+1) .'-'.time().'.'.$image->getClientOriginalExtension();
                $image->move('uploads/advertises',$file_name);
                $link = 'advertises/'.$file_name;

                $advertise->pictures()->create([
                    'link'=>$link,
                    'status'=>1
                ]);
            }
        }

        $notifiable_admins = Admin::role('super_admin')->get();

        Notification::send($notifiable_admins , new NewAdvertiseCreated($advertise));

        return response()->json(
            [$advertise , 'message'=>'ایجاد آگهی با موفقیت انجام شد'],
            201
        );
    }
    public function advertiseData(Request $request)
    {
        $advertise = Advertise::find($request->id);
        if(!$advertise){
            return response()->json(['status'=>404],404);
        }
        $default_call_number = Setting::find(1)->default_phone_number;
        if($advertise->admin_id){
            $admin = Admin::find($advertise->admin_id);
            if($admin){
                $advertise['call_number'] = $admin->number;
            }else{
                $advertise['call_number'] = $default_call_number;
            }
        }else{
            $advertise['call_number'] = $default_call_number;
        }
        $advertise['pointer_price'] = number_format($advertise->price);
        $advertise['pointer_rent'] = number_format($advertise->rent);
        $advertise['pointer_meed'] = number_format($advertise->meed);
        $advertise['new_images'] = [];
        $advertise['image_delete'] = [];
        $advertise['images_count'] = $advertise->pictures()->count();
        $advertise['default_image'] = Setting::find(1)->advertise_default_image;
        $advertise['images'] = $advertise->pictures;
        $region = $advertise->region;

        $this->recordView($request , $advertise);

        return response()->json($advertise,200);
    }
    public function advertiseEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'type' => ['required'],
            'status' => ['required'],
            'region_id' => ['required'],
            'area' => ['required']
        ]);
        
        $status = $request->status;
        $type = $request->type;
        if($request->is_in_lane !== null && $request->is_in_lane == 1){
            $is_in_lane = 1;
        }else{
            $is_in_lane = 0;
        }
        if($request->length_house !== 'null' && $request->length_house !== null){
            $length_house = $request->length_house;
        }else{
            $length_house = null;
        }
        if($request->lane_width !== 'null' && $request->lane_width !== null){
            $lane_width = $request->lane_width;
        }else{
            $lane_width = null;
        }
        $roof_number = $request->roof_number;
        $skeleton_state = $request->skeleton_state;
        $lifetime_state = $request->lifetime_state;
        $price = $request->price;
        $rent = $request->rent;
        $meed = $request->meed;

        switch ($type) {
            case 1:
                $rent = null;
                $meed = null;
            break;
            case 2:
                $meed = null;
                $skeleton_state = null;
                $price = null;
                $length_house = null;
                $lane_width = null;
            break;
            case 3:
                $skeleton_state = null;
                $price = null;
                $length_house = null;
                $lane_width = null;
            break;
            case 4:
                $meed = null;
                $rent = null;
                $length_house = null;
                $lane_width = null;
            break;
            case 5:
                $skeleton_state = null;
                $price = null;
                $length_house = null;
                $lane_width = null;
            break;
        }

        if($status == 'مغازه'){
            $lane_width = null;
            $roof_number = null;
            $skeleton_state = null;
        }elseif($status == 'زمین'){
            $lifetime_state = null;
            $roof_number = null;
            $skeleton_state = null;
        }

        $advertise = Advertise::find($request->id);
        
        $advertise->update([
            'title'=>$request->title,
            'type'=>$type,
            'status'=>$status,
            'region_id'=>$request->region_id,
            'address'=>$request->address,
            'is_in_lane'=>$is_in_lane,
            'lane_width'=>$lane_width,
            'area'=>$request->area,
            'length_house'=>$length_house,
            'roof_number'=>$roof_number,
            'lifetime_state'=>$lifetime_state,
            'skeleton_state'=>$skeleton_state,
            'price'=>$price,
            'rent'=>$rent,
            'meed'=>$meed,
            'description'=>$request->description,
            'confirmed'=>0
        ]);

        if($request->hasFile('new_images')){
            foreach($request->new_images as $key=>$item){
                $image = $request->new_images[$key];
                $file_name = 'ID'.$advertise->id.'-IMG'. ($key+1) .'-'.time().'.'.$image->getClientOriginalExtension();
                $image->move('uploads/advertises',$file_name);
                $link = 'advertises/'.$file_name;

                $advertise->pictures()->create([
                    'link'=>$link,
                    'status'=>1
                ]);
            }
        }

        if(!empty($request->image_delete)){
            foreach(explode(',',$request->image_delete) as $key=>$image_id){
                $image = Picture::find($image_id);
                File::delete(public_path().'/uploads/'.$image->link);
                $image->delete();
            }
        }

        return response()->json(
            [$advertise , 'message'=>'ویرایش آگهی با موفقیت انجام شد'],
            201
        );
    }
    public function advertiseDelete(Request $request)
    {
        foreach($request->array as $id){
            $advertise = Advertise::find($id);
            if(!is_null($advertise->pictures())){
                foreach($advertise->pictures() as $image){
                    File::delete(public_path().'/uploads/'.$image->link);
                    $image->delete();
                }
            }
            $advertise->delete();
        }
        return response()->json(
            ['message'=>'حذف با موفقیت انجام شد'],
            200
        );
    }
    public function advertiseChangeStatus(Request $request)
    {
        $advertise = Advertise::find($request->advertise_id);
        if($advertise->confirmed == 1){
            $confirmed = 0;
        }else{
            $confirmed = 1;
        }
        $advertise->update(['confirmed'=>$confirmed]);
        return response()->json(['message'=>'آگهی با موفقیت تغییر وضعیت داده شد'],200);
    }
    public function getUserAdvertises(Request $request)
    {
        $user = User::where('auth_token' , $request->auth_token)->first();
        $advertises = Advertise::latest()->where('user_id' , $user->id)->paginate(100);
        foreach($advertises as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%m/%d');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%m/%d');
            if($item->images->count() > 0){
                $item['image'] = $item->pictures[0]->link;
            }else{
                $item['image'] = Setting::find(1)->advertise_default_image;
            }
        }
        $advertises_count = Advertise::where('user_id' , $user->id)->count();
        return response()->json(
            [
                'advertises' => $advertises,
                'advertises_count' => $advertises_count,
            ]
        ,200);
    }
    public function advertiseSearch(Request $request)
    {
        $key = $request->key;
        $advertises = Advertise::latest()->whereConfirmed(1)
        ->where(function ($query) use ($key) {
            $query->where('title','LIKE', '%'.$key.'%')
            ->orWhere('street','LIKE', '%'.$key.'%')
            ->orWhere('area','LIKE', '%'.$key.'%')
            ->orWhere('price' , 'LIKE', '%'.$key.'%')
            ->orWhere('description' , 'LIKE', '%'.$key.'%');
        })
        ->get();
        foreach($advertises as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%m/%d');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%m/%d');
            if($item->images->count() > 0){
                $item['image'] = $item->pictures[0]->link;
            }else{
                $item['image'] = Setting::find(1)->advertise_default_image;
            }
        }
        return response()->json($advertises,200);
    }
    public function advertiseRegions(Request $request)
    {
        $regions = Region::whereStatus(1)->orderBy('priority' , 'ASC')->get();
        return response()->json($regions,200);
    }
}