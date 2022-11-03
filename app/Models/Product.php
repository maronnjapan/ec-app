<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'explain',
    ];

    public function productImage()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productTag()
    {
        return $this->belongsToMany(ProductTag::class,'product_tag_relations','product_id', 'product_tag_id');
    }

    public function getFirstImageModel($id)
    {
        return $this->productImage()->where('product_id', $id)->first();
    }
}
