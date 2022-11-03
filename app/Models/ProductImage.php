<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'image_name',
        'image_extension'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFilePath()
    {
        return $this->image_name . '.' . $this->image_extension;
    }

}
