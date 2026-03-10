<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
class BaseController extends Controller
{
    public function __construct()
    {
        
        if(Auth::user()->VaiTro == 'admin' || Auth::user()->VaiTro == 'letan'){
            // Cho phép truy cập
        } else {
            // Đã đăng nhập với vai trò hợp lệ
            Redirect::route('trangchu')->send();
        }
    }
}