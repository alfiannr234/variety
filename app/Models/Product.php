<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
        'specifications' => 'array',
        'colors' => 'array',
        'sizes' => 'array',
        'variant_stocks' => 'array',
        'tags' => 'array',
        'is_flash_sale' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_featured' => 'boolean',
    ];
    protected $appends = ['formatted_variants'];

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function subCategory()
    {
        return $this->hasOneThrough(
            SubCategory::class,
            SubSubCategory::class,
            'id',
            'id',
            'sub_sub_category_id',
            'sub_category_id'
        );
    }
    public function subSubCategory()
    {
        return $this->belongsTo(SubSubCategory::class, 'sub_sub_category_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function getFormattedVariantsAttribute(): array
    {
        $list = [];

        // 1. Jika variant_stocks berupa list terindeks [ ['name' => ..., 'price' => ..., 'stock' => ...], ... ]
        if (!empty($this->variant_stocks) && is_array($this->variant_stocks) && array_is_list($this->variant_stocks)) {
            foreach ($this->variant_stocks as $index => $var) {
                if (is_array($var)) {
                    $list[] = [
                        'id' => $var['id'] ?? ('var_' . $index),
                        'name' => $var['name'] ?? '',
                        'price' => (int) ($var['price'] ?? $this->price),
                        'stock' => (int) ($var['stock'] ?? 0),
                        'image' => $var['image'] ?? null,
                    ];
                }
            }
            if (!empty($list)) {
                return $list;
            }
        }

        // 2. Jika variant_stocks berupa associative map [ "Olive Green-S" => 12, ... ]
        if (!empty($this->variant_stocks) && is_array($this->variant_stocks)) {
            $index = 0;
            foreach ($this->variant_stocks as $key => $val) {
                $colorPart = explode('-', (string) $key)[0] ?? (string) $key;
                $matchingColor = collect($this->colors)->firstWhere('name', trim($colorPart));
                $image = is_array($val) && !empty($val['image'])
                    ? $val['image']
                    : (is_array($matchingColor) ? ($matchingColor['image'] ?? null) : null);
                $price = is_array($val) ? ($val['price'] ?? $this->price) : $this->price;
                $stock = is_array($val) ? ($val['stock'] ?? 0) : (int) $val;

                $formattedName = is_array($val) && !empty($val['name'])
                    ? $val['name']
                    : (str_contains((string) $key, '-') && !str_contains((string) $key, ' - ')
                        ? preg_replace('/-(?=[^-]+$)/', ' - ', (string) $key)
                        : (string) $key);

                $list[] = [
                    'id' => 'var_' . \Illuminate\Support\Str::slug((string) $key) . '_' . ($index++),
                    'name' => $formattedName,
                    'price' => (int) $price,
                    'stock' => (int) $stock,
                    'image' => $image,
                ];
            }
            if (!empty($list)) {
                return $list;
            }
        }

        // 3. Fallback jika variant_stocks kosong tapi ada colors atau sizes
        if (!empty($this->colors) && is_array($this->colors)) {
            $index = 0;
            foreach ($this->colors as $col) {
                $colName = is_array($col) ? ($col['name'] ?? 'Warna') : (string) $col;
                $colImg = is_array($col) ? ($col['image'] ?? null) : null;

                if (!empty($this->sizes) && is_array($this->sizes)) {
                    foreach ($this->sizes as $sz) {
                        $list[] = [
                            'id' => 'var_' . \Illuminate\Support\Str::slug("{$colName}-{$sz}") . '_' . ($index++),
                            'name' => "{$colName} - {$sz}",
                            'price' => (int) $this->price,
                            'stock' => (int) floor($this->stock / max(1, count($this->colors) * count($this->sizes))),
                            'image' => $colImg,
                        ];
                    }
                } else {
                    $list[] = [
                        'id' => 'var_' . \Illuminate\Support\Str::slug($colName) . '_' . ($index++),
                        'name' => $colName,
                        'price' => (int) $this->price,
                        'stock' => (int) floor($this->stock / max(1, count($this->colors))),
                        'image' => $colImg,
                    ];
                }
            }
            if (!empty($list)) {
                return $list;
            }
        }

        if (!empty($this->sizes) && is_array($this->sizes)) {
            $index = 0;
            foreach ($this->sizes as $sz) {
                $list[] = [
                    'id' => 'var_size_' . \Illuminate\Support\Str::slug($sz) . '_' . ($index++),
                    'name' => "Ukuran {$sz}",
                    'price' => (int) $this->price,
                    'stock' => (int) floor($this->stock / max(1, count($this->sizes))),
                    'image' => null,
                ];
            }
        }

        return $list;
    }
}
