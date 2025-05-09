<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id', 
        'name', 
        'npm', 
        'semester', 
        'faculty'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}