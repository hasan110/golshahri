<?php

namespace App\Http\Controllers\server\app;

use App\Http\Controllers\Controller;
use App\Models\AppModels\SlideShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Morilog\Jalali\Jalalian;
use App\Models\Posts\PostsCategory;

class SlideShowController extends Controller
{
    public function slideshowList(Request $request)
    {
        $slideshows = SlideShow::latest()->paginate(50);
        foreach($slideshows as $key=>$slideshow){
            $slideshow['shamsi_created_at'] = Jalalian::forge($slideshow->created_at)->format('%Y/%m/%d');
            $slideshow['shamsi_updated_at'] = Jalalian::forge($slideshow->updated_at)->format('%Y/%m/%d');
            $slideshow['shamsi_release'] = Jalalian::forge($slideshow->release)->format('%Y/%m/%d');
            $slideshow['shamsi_expiry'] = Jalalian::forge($slideshow->expiry)->format('%Y/%m/%d');

            if($slideshow->place == 0){
                $slideshow['place'] = 'خانه';
            }else{
                $slideshow['place'] = PostsCategory::find($slideshow->place)->title;
            }
        }
        return response()->json($slideshows,200);
    }
    public function slideshowCreate(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'image' => ['required','image'],
            'place' => ['required'],
            'release' => ['required'],
            'expiry' => ['required']
        ]);

        $releasedate = $this->convertDate($request->release);
        $expirydate = $this->convertDate($request->expiry);
        if ($releasedate > $expirydate) {
            return response()->json(['error'=>'تاریخ انقضا زودتر از تاریخ انتشار است'],400);
        }

        if($request->has('status')){
            if($request->status){
                $status = 1;
            }else{
                $status = 0;
            }
        }else{
            $status = 0;
        }
        if($request->has('default')){
            if($request->default){
                $default = 1;
            }else{
                $default = 0;
            }
        }else{
            $default = 0;
        }

        $image = $request->file('image');
        $file_name = 'IMG-'.time().'.'.$image->getClientOriginalExtension();
        $image->move('uploads/slideshows',$file_name);

        $slideshow = SlideShow::create([
            'title'=>$request->title,
            'place'=>$request->place,
            'image'=>'slideshows/'.$file_name,
            'release'=>$releasedate,
            'expiry'=>$expirydate,
            'status'=>$status,
            'default'=>$default,
        ]);

        return response()->json(
            [$slideshow , 'message'=>'اسلایدشو با موفقیت ایجاد شد'],
            201
        );
    }
    public function slideshowData(Request $request)
    {
        $slideshow = SlideShow::find($request->id);
        $slideshow['shamsi_release'] = Jalalian::forge($slideshow->release)->format('%Y/%m/%d');
        $slideshow['shamsi_expiry'] = Jalalian::forge($slideshow->expiry)->format('%Y/%m/%d');
        return response()->json($slideshow,200);
    }
    public function slideshowEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'place' => ['required'],
            'shamsi_release' => ['required'],
            'shamsi_expiry' => ['required']
        ]);
        
        $slideshow = SlideShow::find($request->id);

        $releasedate = $this->convertDate($request->shamsi_release);
        $expirydate = $this->convertDate($request->shamsi_expiry);
        if ($releasedate > $expirydate) {
            return response()->json(['error'=>'تاریخ انقضا زودتر از تاریخ انتشار است'],400);
        }

        if($request->has('status')){
            if($request->status == true){
                $status = 1;
            }else{
                $status = 0;
            }
        }else{
            $status = 0;
        }
        if($request->has('default')){
            if($request->default == true){
                $default = 1;
            }else{
                $default = 0;
            }
        }else{
            $default = 0;
        }

        if($request->hasFile('image')){
            $image = $request->file('image');
            $file_name = 'IMG-'.time().'.'.$image->getClientOriginalExtension();
            $image->move('uploads/slideshows',$file_name);
            
            File::delete(public_path().'/uploads/'.$slideshow->image);

            $slideshow_image = 'slideshows/'.$file_name;
        }else{
            $slideshow_image = $slideshow->image;
        }

        $slideshow->update([
            'title'=>$request->title,
            'place'=>$request->place,
            'image'=>$slideshow_image,
            'release'=>$releasedate,
            'expiry'=>$expirydate,
            'status'=>$status,
            'default'=>$default,
        ]);

        return response()->json(
            [$slideshow,'message'=>'اسلایدشو با موفقیت ویرایش شد'],
            201
        );
    }
    public function slideshowDelete(Request $request)
    {
        foreach($request->array as $key=>$id){
            $slideshow = SlideShow::find($id);
            if ($slideshow->image) {
                File::delete(public_path().'/uploads/'.$slideshow->image);
            }
            $slideshow->delete();
        }
        return response()->json(
            ['message'=>'اسلایدشو با موفقیت حذف شد'],
            200
        );
    }
    public function slideshowChangeStatus(Request $request)
    {
        $slideshow = SlideShow::find($request->slideshow_id);
        if($slideshow->status == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $slideshow->update(['status'=>$status]);

        return response()->json(
            ['message'=>'اسلایدشو با موفقیت تغییر وضعیت داده شد'],
            200
        );
    }
}
