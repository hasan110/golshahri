<?php

namespace App\Http\Controllers\server\region;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppModels\Region;
use Morilog\Jalali\Jalalian;

class RegionController extends Controller
{
    public function regionList(Request $request)
    {
        $regions = Region::latest()->paginate(50);
        
        foreach($regions as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d- H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d- H:i');
        }
        return response()->json($regions,200);
    }
    public function regionCreate(Request $request)
    {
        $validation = $this->getValidationFactory()->make($request->all(), [
            'title' => ['required'],
            'priority' => ['required'],
            'status' => ['required']
        ]);
    
        if ($validation->fails()) {
            return response()->json(
                ['status' => 'failed' , 'message'=>'لطفا همه فیلد های الزامی را پر کنید .'],
                400
            );
        }

        $region = Region::create([
            'title'=>$request->title,
            'priority'=>$request->priority,
            'status'=>$request->status
        ]);

        return response()->json(
            [$region , 'message'=>'ایجاد منطقه با موفقیت انجام شد'],
            201
        );
    }
    public function regionData(Request $request)
    {
        $region = Region::find($request->id);
        return response()->json($region,200);
    }
    public function regionEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'priority' => ['required'],
            'status' => ['required'],
        ]);

        $region = Region::find($request->id);
        
        $region->update([
            'title'=>$request->title,
            'priority'=>$request->priority,
            'status'=>$request->status,
        ]);

        return response()->json(
            [$region , 'message'=>'ویرایش منطقه با موفقیت انجام شد'],
            201
        );
    }
    public function regionDelete(Request $request)
    {
        foreach($request->array as $id){
            Region::find($id)->delete();
        }
        return response()->json(
            ['message'=>'حذف با موفقیت انجام شد'],
            200
        );
    }
}