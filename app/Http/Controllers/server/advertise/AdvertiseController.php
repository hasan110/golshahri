<?php

namespace App\Http\Controllers\server\advertise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertise\Advertise;
use App\Models\Advertise\AdvertisePicture;
use App\Models\User\User;
use App\Models\Admin\Admin;
use Auth;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\File;

class AdvertiseController extends Controller
{
    public function advertiseList(Request $request)
    {
        if(auth()->user()->hasRole('super_admin')){
            $advertises = Advertise::latest()->with('user')->with('admin')->with('images')->paginate(50);
        }else{
            $advertises = Advertise::latest()->where('admin_id' , auth()->user()->id)->with('user')->with('admin')->with('images')->paginate(50);
        }
        
        foreach($advertises as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d- H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d- H:i');
            $item['view_count'] = $item->views->count();
        }
        return response()->json($advertises,200);
    }
    public function advertiseCreate(Request $request)
    {
        $validation = $this->getValidationFactory()->make($request->all(), [
            'title' => ['required'],
            'type' => ['required'],
            'status' => ['required'],
            'neighborhood' => ['required'],
            'street' => ['required'],
            'area' => ['required']
        ]);
    
        if ($validation->fails()) {
            return response()->json(
                ['status' => 'failed' , 'message'=>'لطفا همه فیلد های الزامی را پر کنید .'],
                400
            );
        }

        if($request->is_in_lane !== null && $request->is_in_lane == 1){
            $is_in_lane = 1;
        }else{
            $is_in_lane = 0;
        }
        $type = $request->type;
        if($type == 'فروش'){
            $price = $request->price;
            $rent = null;
            $meed = null;
            $lane_width = $request->lane_width;
            $length_house = $request->length_house;
        }elseif($type == 'رهن و اجاره'){
            $rent = $request->rent;
            $meed = $request->meed;
            $price = null;
            $lane_width = null;
            $length_house = null;
        }
        
        $lifetime_state = $request->lifetime_state;
        
        $status = $request->status;
        if($status == 'مغازه'){
            $is_in_lane = 0;
            $lane_width = null;
            $roof_number = null;
            $skeleton_state = null;
        }elseif($status == 'زمین'){
            $lifetime_state = null;
            $roof_number = null;
            $skeleton_state = null;
        }else{
            $roof_number = $request->roof_number;
            if($type == 'فروش'){
                $skeleton_state = $request->skeleton_state;
            }elseif($type == 'رهن و اجاره'){
                $skeleton_state = null;
            }
        }

        $advertise = Advertise::create([
            'admin_id'=>Auth::user()->id,
            'title'=>$request->title,
            'type'=>$type,
            'status'=>$status,
            'neighborhood'=>$request->neighborhood,
            'street'=>$request->street,
            'is_in_lane'=>$is_in_lane,
            'lane_width'=>$lane_width,
            'area'=>$request->area,
            'length_house'=>$length_house,
            'roof_number'=>$roof_number,
            'lifetime_state'=>$lifetime_state,
            'skeleton_state'=>$skeleton_state,
            'price'=>$price,
            'alphabet_price'=>null,
            'rent'=>$rent,
            'meed'=>$meed,
            'description'=>$request->description,
            'address'=>$request->address,
            'note'=>$request->note,
            'confirmed'=>1,
        ]);

        if($request->hasFile('images')){
            foreach($request->images as $key=>$item){
                $image = $request->images[$key];
                $file_name = 'IMG'. ($key+1) .'-'.time().'.'.$image->getClientOriginalExtension();
                $image->move('uploads/advertises/'.$advertise->id,$file_name);
                $link = 'advertises/'.$advertise->id.'/'.$file_name;

                AdvertisePicture::create([
                    'advertise_id'=>$advertise->id,
                    'link'=>$link,
                    'status'=>1,
                ]);
            }
        }

        return response()->json(
            [$advertise , 'message'=>'ایجاد آگهی با موفقیت انجام شد'],
            201
        );
    }
    public function advertiseData(Request $request)
    {
        $advertise = Advertise::find($request->id);
        $advertise['new_images'] = [];
        $advertise['image_delete'] = [];
        $images = $advertise->images;
        return response()->json($advertise,200);
    }
    public function advertiseEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'type' => ['required'],
            'status' => ['required'],
            'neighborhood' => ['required'],
            'street' => ['required'],
            'area' => ['required'],
        ]);
        
        if($request->is_in_lane !== null && $request->is_in_lane == 1){
            $is_in_lane = 1;
        }else{
            $is_in_lane = 0;
        }
        $type = $request->type;
        if($type == 'فروش'){
            $price = $request->price;
            $rent = null;
            $meed = null;
            $lane_width = $request->lane_width;
            $length_house = $request->length_house;
        }elseif($type == 'رهن و اجاره'){
            $rent = $request->rent;
            $meed = $request->meed;
            $price = null;
            $lane_width = null;
            $length_house = null;
        }
        
        $lifetime_state = $request->lifetime_state;
        
        $status = $request->status;
        if($status == 'مغازه'){
            $is_in_lane = 0;
            $lane_width = null;
            $roof_number = null;
            $skeleton_state = null;
        }elseif($status == 'زمین'){
            $lifetime_state = null;
            $roof_number = null;
            $skeleton_state = null;
        }else{
            $roof_number = $request->roof_number;
            if($type == 'فروش'){
                $skeleton_state = $request->skeleton_state;
            }elseif($type == 'رهن و اجاره'){
                $skeleton_state = null;
            }
        }

        $advertise = Advertise::find($request->id);
        
        $advertise->update([
            'title'=>$request->title,
            'type'=>$type,
            'status'=>$status,
            'neighborhood'=>$request->neighborhood,
            'street'=>$request->street,
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
            'address'=>$request->address,
            'note'=>$request->note,
        ]);

        if($request->hasFile('new_images')){
            foreach($request->new_images as $key=>$item){
                $image = $request->new_images[$key];
                $file_name = 'IMG'. ($key+1) .'-'.time().'.'.$image->getClientOriginalExtension();
                $image->move('uploads/advertises/'.$advertise->id,$file_name);
                $link = 'advertises/'.$advertise->id.'/'.$file_name;

                AdvertisePicture::create([
                    'advertise_id'=>$advertise->id,
                    'link'=>$link,
                    'status'=>1
                ]);
            }
        }

        if(!empty($request->image_delete)){
            foreach(explode(',',$request->image_delete) as $key=>$image_id){
                $image = AdvertisePicture::find($image_id);
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
            if(!is_null($advertise->images)){
                File::deleteDirectory(public_path('uploads/advertises/'.$advertise->id));
                foreach($advertise->images as $image){
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
    public function advertiseSearch(Request $request)
    {
        $key = $request->key;
        if(auth()->user()->hasRole('super_admin')){
            $advertises = Advertise::latest()->with('user')->with('admin')->with('images')
            ->where('title' , 'LIKE', '%'.$key.'%')
            ->orWhere('street' , 'LIKE', '%'.$key.'%')
            ->orWhere('area' , 'LIKE', '%'.$key.'%')
            ->orWhere('price' , 'LIKE', '%'.$key.'%')
            ->orWhere('description' , 'LIKE', '%'.$key.'%')
            ->paginate(50);
        }else{
            $advertises = Advertise::latest()->where('admin_id' , auth()->user()->id)->with('user')->with('admin')->with('images')
            ->where(function ($query) use ($key) {
                $query->where('title','LIKE', '%'.$key.'%')
                ->orWhere('street','LIKE', '%'.$key.'%')
                ->orWhere('area','LIKE', '%'.$key.'%')
                ->orWhere('price' , 'LIKE', '%'.$key.'%')
                ->orWhere('description' , 'LIKE', '%'.$key.'%');
            })
            ->paginate(50);
        }
        foreach($advertises as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d- H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d- H:i');
        }
        return response()->json($advertises,200);
    }
}