<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppModels\Setting;

class HomeController extends Controller
{
  public function index(Request $request)
  {
    return view('client.template.template');
  }
  public function getSettings(Request $request)
  {
    $setting = Setting::find(1);
    return response()->json(
      $setting,
      201
    );
  }
}
