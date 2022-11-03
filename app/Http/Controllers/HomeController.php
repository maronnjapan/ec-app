<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request, ProductService $service)
    {
        $tag = $request->query('tag',NULL);
        $items = $service->getItems($tag);
        return view('home', compact('items'));
    }
}
