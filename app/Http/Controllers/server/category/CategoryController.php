<?php

namespace App\Http\Controllers\server\category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category\Category;
use Morilog\Jalali\Jalalian;

class CategoryController extends Controller
{
    public function categoryList(Request $request)
    {
        $categories = Category::latest()->paginate(50);
        
        foreach($categories as $key=>$item){
            $item['shamsi_created_at'] = Jalalian::forge($item->created_at)->format('%Y/%m/%d- H:i');
            $item['shamsi_updated_at'] = Jalalian::forge($item->updated_at)->format('%Y/%m/%d- H:i');
        }
        return response()->json($categories,200);
    }
    public function categoryCreate(Request $request)
    {
        $validation = $this->getValidationFactory()->make($request->all(), [
            'title' => ['required'],
            'type' => ['required'],
            'slug' => ['required']
        ]);
    
        if ($validation->fails()) {
            return response()->json(
                ['status' => 'failed' , 'message'=>'لطفا همه فیلد های الزامی را پر کنید .'],
                400
            );
        }

        $category = category::create([
            'title'=>$request->title,
            'slug'=>$request->slug,
            'type'=>$request->type,
        ]);

        return response()->json(
            [$category , 'message'=>'ایجاد دسته بندی با موفقیت انجام شد'],
            201
        );
    }
    public function categoryData(Request $request)
    {
        $category = Category::find($request->id);
        return response()->json($category,200);
    }
    public function categoryEdit(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'type' => ['required'],
            'slug' => ['required']
        ]);

        $category = Category::find($request->id);
        
        $category->update([
            'title'=>$request->title,
            'type'=>$request->type,
            'slug'=>$request->slug,
        ]);

        return response()->json(
            [$category , 'message'=>'ویرایش دسته بندی با موفقیت انجام شد'],
            201
        );
    }
    public function categoryDelete(Request $request)
    {
        foreach($request->array as $id){
            Category::find($id)->delete();
        }
        return response()->json(
            ['message'=>'حذف با موفقیت انجام شد'],
            200
        );
    }
}