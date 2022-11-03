<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Product\ProductService;

class ProductController extends Controller
{
    public function show(ProductService $service ,$id)
    {
        $item = $service->getDataById($id);
        $tags = $service->getTags($item);
        return view('product.show', compact('item', 'tags'));
    }
}
