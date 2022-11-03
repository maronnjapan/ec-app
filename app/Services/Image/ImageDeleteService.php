<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Storage;

class ImageDeleteService
{
    /**
     * 指定ファイル名を削除
     * @param array　$file_name_list 削除するファイル名リスト
     * @param string $folder_name 検索するフォルダ名
     * @return void
     */
    public function deleteFiles($file_name_list, $folder_name)
    {
        array_map(function ($file_name) use ($folder_name) {
            $filePath = "/public/${folder_name}/${file_name}";
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }, $file_name_list);
    }
    /**
     * 指定ファイル名以外削除
     * @param array　$file_name_list 削除しないファイル名リスト
     * @param string $folder_name 検索するフォルダ名
     * @return void
     */
    public function deleteExceptList($file_name_list, $folder_name)
    {
        $images = Storage::files("/public/${folder_name}");

        array_map(function ($image) use ($file_name_list) {
            $fileName = basename($image);
            if (array_search($fileName,$file_name_list,true) === false) {
                Storage::delete($image);
            }
        }, $images);
    }
}
