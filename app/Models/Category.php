<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    public function menuTab()
    {
        return $this->belongsTo(MenuTab::class);
    }
    public function products()
    {
        return $this->hasManyThrough(Product::class, SubSubCategory::class, 'sub_category_id', 'sub_sub_category_id');
    }
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
