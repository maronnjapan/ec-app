<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VueSampleController extends Controller
{
    public function index()
    {
        return view('vue-test');
    }
}
