<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subSubCategories()
    {
        return $this->hasMany(SubSubCategory::class);
    }
    public function products()
    {
        return $this->hasManyThrough(Product::class, SubSubCategory::class);
    }
}
