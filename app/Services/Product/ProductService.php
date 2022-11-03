<?php

namespace App\Services\Product;

use Illuminate\Support\Facades\Storage;
use App\Models\Product as ProductModel;
use App\Models\ProductImage as ProductImageModel;
use App\Models\ProductTag as ProductTag;
use App\Models\ProductTagRelation as ProductTagRelation;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function __construct(ProductModel $product, ProductImageModel $product_image, ProductTag $productTag, ProductTagRelation $productRelation)
    {
        $this->imgUploadService = app('ImageUpload');
        $this->imgDelService = app('ImageDel');
        $this->product = $product;
        $this->productImg = $product_image;
        $this->productTag = $productTag;
        $this->productRelation = $productRelation;
    }

    /**
     * データをデータベースに保存
     * @param array $datas 保存するデータリスト
     * @return integer 挿入ID番号
     */
    public function createData($datas)
    {
        $productParams = $this->createParams($datas, ['name', 'explain']);
        var_dump(Auth::id());
        var_dump('ownerId:'.Auth::guard('admin')->id());
        exit;
        $insert = $this->product::create(array_merge($productParams,['created_by' => Auth::id(),'master_id'=>Auth::guard('owner')->id()]));

        $tagList = explode('|', $datas['tag_name']);
        $productTag = $this->productTag;
        $productRelation = $this->productRelation;
        array_map(function ($tag) use ($insert, $productTag, $productRelation) {
            $tagInsert = $productTag::firstOrCreate(
                [
                    'name' => $tag
                ],
                [
                    'name' => $tag
                ]
            );


            return $productRelation::create([
                'product_id' => $insert->id,
                'product_tag_id' => $tagInsert->id
            ]);
        }, $tagList);

        foreach ($datas['images'] as $key => $image) {
            $this->productImg::create([
                'product_id' => $insert->id,
                'image_name' => $datas['image_name'][$key],
                'image_extension' => $image->extension()
            ]);
        }

        return $insert->id;
    }

    /**
     * データを更新
     * @param array $datas 保存するデータリスト
     * @param integer $id データID
     * @return void
     */
    public function updateData($datas, $id)
    {
        $productParams = $this->createParams($datas, ['name', 'explain']);
        $productModel = $this->product::find($id);
        $productModel->update($productParams);


        $tagList = explode('|', $datas['tag_name']);
        $deleteTags = $productModel->productTag->filter(function ($tag) use ($tagList) {
            return (array_search($tag->name, $tagList, true)) === false;
        });
        $deleteTags->map(function ($tag) use ($productModel) {
            $productModel->productTag()->detach($tag->id);
        });



        $productTag = $this->productTag;
        $productRelation = $this->productRelation;
        array_map(function ($tag) use ($id, $productTag, $productRelation) {
            $tagInsert = $productTag::firstOrCreate(
                [
                    'name' => $tag
                ],
                [
                    'name' => $tag
                ]
            );


            return $productRelation::firstOrCreate(
                [
                    'product_id' => $id,
                    'product_tag_id' => $tagInsert->id
                ],
                [
                    'product_id' => $id,
                    'product_tag_id' => $tagInsert->id
                ]
            );
        }, $tagList);

        $images = $datas['images'] ?? array();
        foreach ($images as $key => $image) {
            $this->productImg::updateOrCreate(
                [
                    'image_name' => $datas['pre_image_name'][$key]
                ],
                [
                    'product_id' => $id,
                    'image_name' => $datas['image_name'][$key],
                    'image_extension' => $image->extension()
                ]
            );
        }
        $this->productImg::where('product_id', $id)->whereNotIn('image_name', $datas['image_name'])->delete();
    }

    /**
     * 商品を全て取得
     * @param integer $tag タグID
     * @return array 商品リスト
     */
    public function getItems($tag)
    {
        if (!is_null($tag)) {
            return $this->getItemsInTag($tag);
        }
        return $this->product->get();
    }

    /**
     * 指定タグの商品を全て取得
     * @param integer $tag タグID
     * @return array 商品リスト
     */
    private function getItemsInTag($tag)
    {
        $model = $this->productTag::find($tag);

        return $model->product;
    }

    /**
     * 指定キーワードの商品を全て取得
     * @param integer $word string
     * @return array 商品リスト
     */
    public function getItemsInWord($word)
    {
        if (!is_null($word)) {
            return $this->product::whereRaw('MATCH(name) AGAINST(? IN BOOLEAN MODE)', [$word])->get();
        }

        return $this->product->get();
    }

    /**
     * 指定したIDと一致するデータ取得
     * @param integer $id id番号
     * @return obj データオブジェクト
     */
    public function getDataById($id)
    {
        return $this->product::findOrFail($id);
    }

    /**
     * 指定データが所有するタグ名取得
     * @param obj $item 指定のproductモデル
     * @return obj(Collection) タグ名のリスト
     */
    public function getTags($item)
    {
        return $item->productTag->map(function ($tag) {
            return $tag->name;
        });
    }

    /**
     * productテーブルに保存するデータ配列の作成
     * @param array $datas ポストデータ群
     * @param array $columns 抽出するデータ
     * @return array productテーブルに関わるデータのみを抽出した配列
     */
    private function createParams($datas, $columns)
    {
        return array_filter($datas, function ($key) use ($columns) {
            $bool = (array_search($key, $columns, true) !== false) ? true : false;
            return $bool;
        }, ARRAY_FILTER_USE_KEY);
    }
}
