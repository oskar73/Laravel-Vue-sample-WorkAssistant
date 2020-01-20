<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        return view('user.setting.index');
    }
}
