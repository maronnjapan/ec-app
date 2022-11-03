<?php

namespace App\Services\Product;

use Illuminate\Support\Facades\Storage;
use App\Models\Product as ProductModel;
use App\Models\ProductImage as ProductImageModel;

class ProductImageService
{
    public function __construct(ProductModel $product,ProductImageModel $product_image)
    {
        $this->imgUploadService = app('ImageUpload');
        $this->imgDelService = app('ImageDel');
        $this->product = $product;
        $this->productImg = $product_image;
    }

    /**
     * 画像データをアップロード
     * @param array $images 画像ファイルリスト
     * @param array $fine_name_list 保存するファイル名のリスト
     * @param integer $insert_id データベースに挿入した時のid
     * @return array 保存したファイル名
     */
    public function uploadImages($images, $file_name_list, $insert_id)
    {
        $imageUpload = $this->imgUploadService;

        $keysImages = array_keys($images);
        $filterNameList = array_filter($file_name_list,function($key) use($keysImages){
            return array_search($key,$keysImages) !== false;
        },ARRAY_FILTER_USE_KEY);
        var_dump($filterNameList);

        $saveImgList = array_map(function ($image, $file_name) use ($imageUpload, $insert_id) {
            return $imageUpload->upload($image, "images/owner/product/${insert_id}", $file_name);
        }, $images, $filterNameList);

        return $saveImgList;
    }

    /**
     * 指定のファイル名を削除
     * @param array $file_name_list 削除したいファイル名群
     * @return void
     */
    public function deleteImages($file_name_list)
    {
        $imageDel = $this->imgDelService;
        $imageDel->deleteFiles($file_name_list, 'images/owner/product');
    }

    /**
     * 指定のファイル名以外を削除
     * @param array $file_name_list 削除したいファイル名群
     * @param integer $folder_name 指定ファイルが格納されているフォルダ名
     * @return void
     */
    public function deleteExceptImages($file_name_list,$folder_name)
    {
        $imageDel = $this->imgDelService;
        $imageDel->deleteExceptList($file_name_list, $folder_name);
    }

    public function getFileNames($file_name_list, $folder_path = 'images/owner/product')
    {
        $storageFileList = Storage::files("/public/$folder_path");
        return array_map(function ($file_name) use ($storageFileList) {
            $matchKeyNum = array_keys(preg_grep('/' . $file_name . '/', $storageFileList));
            if(!isset($matchKeyNum[0])){return;}
            $searchFileName = $storageFileList[$matchKeyNum[0]];
            $fileExtension = substr($searchFileName, strrpos($searchFileName, '.'));
            return "${file_name}${fileExtension}";
        }, $file_name_list);
    }
}
