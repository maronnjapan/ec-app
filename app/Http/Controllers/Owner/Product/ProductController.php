<?php

namespace App\Http\Controllers\Owner\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\Product\CreateRequest as CreateRequest;
use App\Http\Requests\Owner\Product\EditRequest as EditRequest;
use App\Services\Product\ProductService as ProductService;
use App\Services\Product\ProductImageService as ProductImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ProductService $service)
    {
        $tag = $request->query('tag',NULL);
        $items = $service->getItems($tag);
        return view('owner.products.index', compact('items'));
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function post(Request $request, ProductService $service)
    {
        $word = $request->input('name');
        $items = $service->getItemsInWord($word);
        return view('owner.products.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = collect([]);
        return view('owner.products.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, ProductService $service, ProductImageService $imgService)
    {
        $datas = $request->validated();

        $insertId = $service->createData($datas);
        $imgService->uploadImages($datas['images'], $datas['image_name'], $insertId);

        return redirect()->route('owner.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminatreturn redirect()->route('owner.products.index');e\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductService $service, $id)
    {
        $item = $service->getDataById($id);
        $tags = $service->getTags($item);
        return view('owner.products.edit', compact('item', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditRequest $request, ProductService $service, ProductImageService $imgService, $id)
    {
        $datas = $request->validated();
        $service->updateData($datas, $id);
        $saveImgList = $imgService->uploadImages($datas['images'] ?? array(), $datas['image_name'], $id);
        $fileNameList = $imgService->getFileNames($datas['image_name'], "images/owner/product/${id}");
        $imgService->deleteExceptImages($fileNameList, "images/owner/product/${id}");
        return redirect()->route('owner.products.edit', ['product' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
