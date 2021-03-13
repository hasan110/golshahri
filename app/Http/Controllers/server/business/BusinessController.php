<?php

namespace App\Http\Controllers\server\business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business\Business;
use App\Models\Business\BusinessPicture;
use App\Models\User\User;
use App\Models\Admin\Admin;
use Auth;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\File;

class BusinessController extends Controller
{
    public function businessList(Request $request)
    {
        $businesses = Business::latest()->with('user')->with('images')->paginate(50);
        
        foreach($businesses as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d- H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d- H:i');
            $item['view_count'] = $item->views->count();
        }
        return response()->json($businesses,200);
    }
    public function businessCreate(Request $request)
    {
        $validation = $this->getValidationFactory()->make($request->all(), [
            'title' => ['required'],
            'description' => ['required']
        ]);
    
        if ($validation->fails()) {
            return response()->json(
                ['status' => 'failed' , 'message'=>'لطفا همه فیلد های الزامی را پر کنید .'],
                400
            );
        }

        $business = Business::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'contact_number'=>$request->contact_number ? $request->contact_number : '',
            'instagram_id'=>$request->instagram_id ? $request->instagram_id : '',
            'telegram_id'=>$request->telegram_id ? $request->telegram_id : '',
            'confirmed'=>1,
        ]);

        if($request->hasFile('images')){
            foreach($request->images as $key=>$item){
                $image = $request->images[$key];
                $file_name = 'IMG'. ($key+1) .'-'.time().'.'.$image->getClientOriginalExtension();
                $image->move('uploads/businesses/'.$business->id,$file_name);
                $link = 'businesses/'.$business->id.'/'.$file_name;

                BusinessPicture::create([
                    'business_id'=>$business->id,
                    'link'=>$link,
                    'status'=>1,
                ]);
            }
        }

        return response()->json(
            [$business , 'message'=>'ایجاد آگهی کسب و کار با موفقیت انجام شد'],
            201
        );
    }
    public function businessData(Request $request)
    {
        $business = Business::find($request->id);
        $business['new_images'] = [];
        $business['image_delete'] = [];
        $images = $business->images;
        return response()->json($business,200);
    }
    public function businessEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'description' => ['required'],
        ]);

        $business = Business::find($request->id);

        $business->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'contact_number'=>$request->contact_number ? $request->contact_number : $business->contact_number,
            'instagram_id'=>$request->instagram_id ? $request->instagram_id : $business->instagram_id,
            'telegram_id'=>$request->telegram_id ? $request->telegram_id : $business->telegram_id,
            'confirmed'=>1,
        ]);

        if($request->hasFile('new_images')){
            foreach($request->new_images as $key=>$item){
                $image = $request->new_images[$key];
                $file_name = 'IMG'. ($key+1) .'-'.time().'.'.$image->getClientOriginalExtension();
                $image->move('uploads/businesses/'.$business->id,$file_name);
                $link = 'businesses/'.$business->id.'/'.$file_name;

                BusinessPicture::create([
                    'business_id'=>$business->id,
                    'link'=>$link,
                    'status'=>1
                ]);
            }
        }

        if(!empty($request->image_delete)){
            foreach(explode(',',$request->image_delete) as $key=>$image_id){
                $image = BusinessPicture::find($image_id);
                File::delete(public_path().'/uploads/'.$image->link);
                $image->delete();
            }
        }

        return response()->json(
            [$business , 'message'=>'ویرایش آگهی کسب و کار با موفقیت انجام شد'],
            201
        );
    }
    public function businessDelete(Request $request)
    {
        foreach($request->array as $id){
            $business = Business::find($id);
            if(!is_null($business->images)){
                File::deleteDirectory(public_path()."/uploads/businessess/".$business->id);
                foreach($business->images as $image){
                    $image->delete();
                }
            }
            $business->delete();
        }
        return response()->json(
            ['message'=>'حذف با موفقیت انجام شد'],
            200
        );
    }
    public function businessChangeStatus(Request $request)
    {
        $business = Business::find($request->business_id);
        if($business->confirmed == 1){
            $confirmed = 0;
        }else{
            $confirmed = 1;
        }
        $business->update(['confirmed'=>$confirmed]);
        return response()->json(['message'=>'آگهی کسب و کار با موفقیت تغییر وضعیت داده شد'],200);
    }
    public function businessSearch(Request $request)
    {
        $key = $request->key;
        $businesses = Business::latest()->with('user')->with('images')
        ->where('title' , 'LIKE', '%'.$key.'%')
        ->orWhere('description' , 'LIKE', '%'.$key.'%')
        ->paginate(50);
        
        foreach($businesses as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d- H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d- H:i');
        }
        return response()->json($businesses,200);
    }
}