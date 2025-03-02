<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View as ViewView;

class MainController extends Controller{
    public function home(): ViewView{
        return view('home');
    }
}
