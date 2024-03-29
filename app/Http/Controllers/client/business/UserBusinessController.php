<?php

namespace App\Http\Controllers\client\business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business\Business;
use App\Models\Business\BusinessPicture;
use App\Models\AppModels\Picture;
use App\Models\AppModels\Vote;
use App\Models\AppModels\Category;
use App\Models\User\User;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Notification;
use Auth;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
use App\Notifications\NewAdvertiseCreated;
use Illuminate\Support\Facades\File;
use App\Models\AppModels\Setting;
use App\Models\AppModels\View;

class UserBusinessController extends Controller
{
    public function businessList(Request $request)
    {
        $businesses = Business::latest()->whereConfirmed(1)->with('category')->paginate(30);
        $now = Carbon::now();
        foreach($businesses as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%m/%d');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%m/%d');
            if($item->pictures()->count() > 0){
                $item['image'] = $item->pictures[0]->link;
            }else{
                $item['image'] = Setting::find(1)->advertise_default_image;
            }
            $item['view_count'] = $item->views->count();
            $item['days_ago'] = $now->diffInDays($item->created_at);
        }

        return response()->json($businesses,200);
    }
    public function businessCreate(Request $request)
    {
        $validation = $this->getValidationFactory()->make($request->all(), [
            'auth_token' => ['required'],
            'title' => ['required'],
            'description' => ['required']
        ]);
    
        if ($validation->fails()) {
            return response()->json(
                ['status' => 'failed' , 'message'=>'لطفا همه فیلد های الزامی را پر کنید .'],
                400
            );
        }

        $user = User::where('auth_token' , $request->auth_token)->first();

        $business = Business::create([
            'user_id'=>$user->id,
            'category_id'=>$request->category_id,
            'title'=>$request->title,
            'description'=>$request->description,
            'contact_number'=>$request->contact_number,
            'instagram_id'=>$request->instagram_id,
            'telegram_id'=>$request->telegram_id,
            'confirmed'=>1,
        ]);

        if($request->hasFile('images')){
            foreach($request->images as $key=>$item){
                $image = $request->images[$key];
                $file_name = 'ID'.$business->id.'-IMG'. ($key+1) .'-'.time().'.'.$image->getClientOriginalExtension();
                $image->move('uploads/businesses',$file_name);
                $link = 'businesses/'.$file_name;

                $business->pictures()->create([
                    'link'=>$link,
                    'status'=>1
                ]);
            }
        }

        // $notifiable_admins = Admin::role('super_admin')->get();

        // Notification::send($notifiable_admins , new NewAdvertiseCreated($advertise));

        return response()->json(
            [$business , 'message'=>'ایجاد آگهی کسب و کار با موفقیت انجام شد'],
            201
        );
    }
    public function businessData(Request $request)
    {
        $business = Business::find($request->id);
        if(!$business){
            return response()->json(['status'=>404],404);
        }
        $business['new_images'] = [];
        $business['image_delete'] = [];
        $business['images_count'] = $business->pictures()->count();
        $business['default_image'] = Setting::find(1)->advertise_default_image;
        $business['images'] = $business->pictures;
        
        $like_count = $business->votes()->where('type' , 'like')->count();
        $dislike_count = $business->votes()->where('type' , 'dislike')->count();
        $sum_like = $like_count + $dislike_count;
        if($sum_like){
            $number = 100 / $sum_like;
        }else{
            $number = 0;
        }
        $percent = $like_count * $number;
        $business['percent'] = floor($percent);
        $business['all_votes'] = $business->votes()->count();
        
        $this->recordView($request , $business);

        return response()->json($business,200);
    }
    public function businessEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'description' => ['required']
        ]);

        $business = Business::find($request->id);

        $business->update([
            'title'=>$request->title,
            'contact_number'=>$request->contact_number,
            'instagram_id'=>$request->instagram_id,
            'telegram_id'=>$request->telegram_id,
            'description'=>$request->description,
            'confirmed'=>1
        ]);

        if($request->hasFile('new_images')){
            foreach($request->new_images as $key=>$item){
                $image = $request->new_images[$key];
                $file_name = 'ID'.$business->id.'-IMG'. ($key+1) .'-'.time().'.'.$image->getClientOriginalExtension();
                $image->move('uploads/businesses',$file_name);
                $link = 'businesses/'.$file_name;

                $business->pictures()->create([
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
            [$business , 'message'=>'ویرایش آگهی با موفقیت انجام شد'],
            201
        );
    }
    public function getUserBusinesses(Request $request)
    {
        $user = User::where('auth_token' , $request->auth_token)->first();
        $businesses = Business::latest()->where('user_id' , $user->id)->paginate(100);
        foreach($businesses as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%m/%d');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%m/%d');
            if($item->images->count() > 0){
                $item['image'] = $item->pictures[0]->link;
            }else{
                $item['image'] = Setting::find(1)->advertise_default_image;
            }
        }
        $businesses_count = Business::where('user_id' , $user->id)->count();
        return response()->json(
            [
                'businesses' => $businesses,
                'businesses_count' => $businesses_count,
            ]
        ,200);
    }
    public function businessSearch(Request $request)
    {
        $key = $request->key;
        $businesses = Business::latest()->whereConfirmed(1)->with('category')
        ->where(function ($query) use ($key) {
            $query->where('title','LIKE', '%'.$key.'%')
            ->orWhere('description' , 'LIKE', '%'.$key.'%');
        })
        ->get();
        $now = Carbon::now();
        foreach($businesses as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%m/%d');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%m/%d');
            if($item->images->count() > 0){
                $item['image'] = $item->pictures[0]->link;
            }else{
                $item['image'] = Setting::find(1)->advertise_default_image;
            }
            $item['view_count'] = $item->views->count();
            $item['days_ago'] = $now->diffInDays($item->created_at);
        }
        return response()->json($businesses,200);
    }
    public function businessVote(Request $request)
    {
        $request->validate([
            'business_id'=>['required'],
            'type'=>['required'],
        ]);

        $business = Business::find($request->business_id);

        $ipAddress = $request->ip();
        
        if($request->has('auth_token')){
            $user = User::where('auth_token',$request->auth_token)->first();
            if($user){
                $user_id = $user->id;
            }else{
                $user_id = null;
            }
        }else{
            $user_id = null;
        }

        if($user_id){
            $vote_count = $business->votes()->where('user_id' , $user_id)->count();
            if($vote_count == 0 ){
                $vote = $business->votes()->create([
                    'user_id' => $user_id,
                    'user_ip' => $ipAddress,
                    'type' => $request->type,
                ]);
            }else{
                return response()->json([
                    'status' => 'failed',
                    'message' => 'رای شما قبلا ثبت شده!',
                ],400);
            }
        }else{
            $vote_count = $business->votes()->where('user_ip' , $ipAddress)->count();
            if($vote_count == 0 ){
                $vote = $business->votes()->create([
                    'user_id' => $user_id,
                    'user_ip' => $ipAddress,
                    'type' => $request->type,
                ]);
            }else{
                return response()->json([
                    'status' => 'failed',
                    'message' => 'رای شما قبلا ثبت شده!',
                ],400);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'رای شما ثبت شد!',
            'vote' => $vote,
        ],200);
    }
    // public function businessComment(Request $request)
    // {
    //     $request->validate([
    //         'business_id'=>['required'],
    //         'comment'=>['required'],
    //     ]);

    //     $ipAddress = $request->ip();

    //     if($request->has('auth_token')){
    //         $user = User::where('auth_token',$request->auth_token)->first();
    //         if($user){
    //             $user_id = $user->id;
    //         }else{
    //             $user_id = null;
    //         }
    //     }else{
    //         $user_id = null;
    //     }

    //     $businessComment = businessComment::create([
    //         'user_id' => $user_id,
    //         'business_id' =>  $request->business_id,
    //         'user_ip' => $ipAddress,
    //         'status' => 1,
    //         'comment' => $request->comment
    //     ]);


    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'نظر شما ثبت شد!',
    //         'businessComment' => $businessComment,
    //     ],200);
    // }
    public function businessCategories(Request $request)
    {
        $categories = Category::latest()->where('type' , 'business')->get();
        return response()->json($categories,200);
    }
}