<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    use HasFactory;

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function product()
    {
        return $this->belongsToMany(Product::class,'product_tag_relations','product_tag_id','product_id');
    }
}
