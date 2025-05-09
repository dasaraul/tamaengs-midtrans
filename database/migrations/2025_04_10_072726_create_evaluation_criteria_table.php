<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    use HasFactory;
    
    // Tambahkan definisi tabel yang benar
    protected $table = 'evaluation_criteria';
    
    protected $fillable = [
        'product_id',
        'name',
        'description',
        'weight',
        'max_score'
    ];
    
    public function competition()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }
}