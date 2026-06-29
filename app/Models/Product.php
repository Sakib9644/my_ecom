<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'is_featured',
        'sizes',
        'colors',
        'color_images',
        'variant_stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'sizes' => 'array',
        'colors' => 'array',
        'color_images' => 'array',
        'variant_stock' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }
        
        if ($this->image && (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://'))) {
            return $this->image;
        }

        return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=60';
    }

    /**
     * Get the stock available for a specific variant combination.
     */
    public function getVariantStock(?string $size, ?string $color): int
    {
        $variantStock = $this->variant_stock;

        if (empty($variantStock)) {
            // If it's supposed to be a variant product, but no variant stock is set, return 0.
            if (!empty($this->sizes) || !empty($this->colors)) {
                return 0;
            }
            return $this->stock;
        }

        if ($size && $color && isset($variantStock[$color][$size])) {
            return (int) $variantStock[$color][$size];
        }

        if ($color && isset($variantStock[$color]) && !is_array($variantStock[$color])) {
            return (int) $variantStock[$color];
        }

        if ($size && isset($variantStock[$size]) && !is_array($variantStock[$size])) {
            return (int) $variantStock[$size];
        }

        return 0;
    }

    public function getImageForColor(?string $colorName): string
    {
        $images = $this->color_images ?? [];

        if ($colorName && isset($images[$colorName]) && $images[$colorName]) {
            $path = $images[$colorName];
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }

        return $this->image_url;
    }

    /**
     * Delete all stored color images from disk.
     */
    public function deleteColorImages(): void
    {
        $images = $this->color_images ?? [];
        foreach ($images as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * Get total stock: sum of all variant stocks if variants exist,
     * otherwise return the base stock.
     */
    public function getTotalStockAttribute(): int
    {
        $variantStock = $this->variant_stock;

        // If it's a variant product, sum the variant stock. If empty, return 0.
        if (!empty($this->sizes) || !empty($this->colors)) {
            if (empty($variantStock)) {
                return 0;
            }
            $total = 0;
            foreach ($variantStock as $value) {
                if (is_array($value)) {
                    $total += array_sum($value);
                } else {
                    $total += (int) $value;
                }
            }
            return $total;
        }

        return $this->stock;
    }
}
