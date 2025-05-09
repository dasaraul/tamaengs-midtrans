<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
        'snap_token',
        'total_price',
        'team_name',
        'institution',
        'leader_name',
        'leader_npm',
        'leader_semester',
        'leader_faculty',
        'leader_phone',
        'leader_email'
    ];
    
    protected $casts = [
        'total_price' => 'decimal:2',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }
    
    // Mendapatkan nama kompetisi yang didaftarkan
    public function getCompetitionNames()
    {
        if (!$this->items || $this->items->isEmpty()) {
            return 'No competitions';
        }
        
        return $this->items->map(function ($item) {
            if (!$item->product) {
                return 'Competition #' . $item->product_id;
            }
            return $item->product->name;
        })->implode(', ');
    }
    
    /**
     * Get total amount accessor for compatibility
     */
    public function getTotalAmountAttribute()
    {
        return $this->total_price;
    }
    
    /**
     * Get order number accessor for compatibility
     */
    public function getOrderNumberAttribute()
    {
        return $this->transaction_id ?? ('ORD-' . $this->id);
    }
}