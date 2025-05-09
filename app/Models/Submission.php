<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'product_id',
        'title',
        'description',
        'file_path',
        'video_url',
        'additional_link',
        'status',
        'notes'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function competition()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'order_id', 'order_id')
                    ->where('product_id', $this->product_id);
    }
    
    // Hitung rata-rata nilai dari semua juri
    public function getAverageScore()
    {
        return $this->evaluations()->avg('score');
    }
}