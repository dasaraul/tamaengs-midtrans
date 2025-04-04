<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'image', 'stock'
    ];

    /**
     * Get the image URL with fallback to default image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        
        return 'https://via.placeholder.com/300x200?text=No+Image';
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}