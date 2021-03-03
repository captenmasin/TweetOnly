<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserSettingsController extends Controller
{
    public function set(Request $request)
    {
        return response()->json($request->user()->settings()->set($request->setting, $request->value));
    }
}
