<?php

namespace App\Http\Controllers\server\advertise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertise\Advertise;
use App\Models\Advertise\AdvertisePicture;
use App\Models\User\User;
use App\Models\Admin\Admin;
use App\Models\Region\Region;
use Auth;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\File;

class AdvertiseController extends Controller
{
    public function advertiseList(Request $request)
    {
        if(auth()->user()->hasRole('super_admin')){
            $advertises = Advertise::latest()->with('user','admin','region','images')->paginate(50);
        }else{
            $advertises = Advertise::latest()->where('admin_id' , auth()->user()->id)->with('user','admin','region','images')->paginate(50);
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
        $street = $request->street;

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
                $street = null;
            break;
            case 5:
                $skeleton_state = null;
                $price = null;
                $length_house = null;
                $lane_width = null;
                $street = null;
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

        $advertise = Advertise::create([
            'admin_id'=>Auth::user()->id,
            'title'=>$request->title,
            'type'=>$type,
            'status'=>$status,
            'region_id'=>$request->region_id,
            'street'=>$street,
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
            'confirmed'=>1,
        ]);

        return response()->json(
            ['advertise'=>$advertise , 'message'=>'ایجاد آگهی با موفقیت انجام شد'],
            201
        );
    }
    public function advertiseData(Request $request)
    {
        $advertise = Advertise::find($request->id)->load('images')->load('region');
        $advertise['new_images'] = [];
        $advertise['image_delete'] = [];
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
            'area' => ['required'],
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
        $street = $request->street;

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
                $street = null;
            break;
            case 5:
                $skeleton_state = null;
                $price = null;
                $length_house = null;
                $lane_width = null;
                $street = null;
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
            'street'=>$street,
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

        if(count($request->image_delete)){
            foreach($request->image_delete as $key=>$image_id){
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
                foreach($advertise->images as $image){
                    File::delete(public_path('uploads/'.$image->link));
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
    public function advertiseUploadFiles(Request $request)
    {
        $advertise_id = $request->id;
        if($request->hasFile('images')){
            foreach($request->images as $key=>$item){
                $image = $request->images[$key];
                $file_name = 'ID'.$advertise_id.'-IMG'. ($key+1) .'-'.time().'.'.$image->getClientOriginalExtension();
                $image->move('uploads/advertises',$file_name);
                $link = 'advertises/'.$file_name;

                AdvertisePicture::create([
                    'advertise_id'=>$advertise_id,
                    'link'=>$link,
                    'status'=>1
                ]);
            }
        }
        
        return response()->json(
            ['message'=>'آپلود تصاویر با موفقیت انجام شد'],
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
            $advertises = Advertise::latest()->with('user','admin','region','images')
            ->where('title' , 'LIKE', '%'.$key.'%')
            ->orWhere('street' , 'LIKE', '%'.$key.'%')
            ->orWhere('area' , 'LIKE', '%'.$key.'%')
            ->orWhere('price' , 'LIKE', '%'.$key.'%')
            ->orWhere('description' , 'LIKE', '%'.$key.'%')
            ->paginate(50);
        }else{
            $advertises = Advertise::latest()->where('admin_id' , auth()->user()->id)->with('user','admin','region','images')
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
    public function advertiseFilter(Request $request)
    {
        if(auth()->user()->hasRole('super_admin')){
            $advertises = Advertise::with('user','admin','region','images');
            if($request->type !== 0){
                $advertises = $advertises->where('type' , $request->type);
            }
            if($request->status){
                $advertises = $advertises->where('status' , $request->status);
            }
            $advertises = $advertises->paginate(500);
        }else{
            $advertises = Advertise::latest()->where('admin_id' , auth()->user()->id)->with('user','admin','region','images');
            if($request->type !== 0){
                $advertises = $advertises->where('type' , $request->type);
            }
            if($request->status){
                $advertises = $advertises->where('status' , $request->status);
            }
            $advertises = $advertises->paginate(500);
        }
        foreach($advertises as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d- H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d- H:i');
        }
        return response()->json($advertises,200);
    }
    public function advertiseRegions(Request $request)
    {
        $regions = Region::whereStatus(1)->orderBy('priority' , 'ASC')->get();
        return response()->json($regions,200);
    }
}