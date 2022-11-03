<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->comment('アイテムID');
            $table->string('image_name',256)->comment('画像ファイル名');
            $table->string('image_extension')->comment('画像ファイル拡張子');
            $table->timestamps();
            $table->softDeletes()->comment('ソフトデリート日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
};
