<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageUploadService
{
    /**
     * 画像をStorageフォルダにアップロード
     * @param array|obj $image 画像データ
     * @param string $folder_name 保存するフォルダ名
     * @param string $file_name 保存するファイル名
     * @return string 保存ファイル名
     */
    public function upload($image, $folder_name, $file_name = "")
    {
        $imageFile = (is_array($image)) ? $image['images'] : $image;
        if (is_null($imageFile)) {
            return;
        }

        $fileName = (strlen($file_name) > 0) ? $file_name : uniqid(rand() . '_');
        $fileExtension = $imageFile->extension();

        $saveFileName = "${fileName}.${fileExtension}";

        $resizeImageFile = InterventionImage::make($imageFile)->resize(960, 540)->encode();
        Storage::put("/public/${folder_name}/${saveFileName}", $resizeImageFile);
        return $saveFileName;
    }
}
