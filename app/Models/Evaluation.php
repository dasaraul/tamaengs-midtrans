<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'user_id',
        'product_id',
        'score',
        'feedback',
        'status'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function judge()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function competition()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }
    
    // Hitung total nilai dengan bobot
    public function calculateTotalScore()
    {
        $total = 0;
        $totalWeight = 0;
        
        foreach ($this->scores as $score) {
            $criteria = $score->criteria;
            $total += ($score->score * $criteria->weight);
            $totalWeight += $criteria->weight;
        }
        
        return $totalWeight > 0 ? $total / $totalWeight : 0;
    }
}