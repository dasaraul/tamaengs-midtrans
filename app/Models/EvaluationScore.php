<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationScore extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'evaluation_id',
        'evaluation_criteria_id',
        'score',
        'notes'
    ];
    
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
    
    public function criteria()
    {
        return $this->belongsTo(EvaluationCriteria::class, 'evaluation_criteria_id');
    }
}