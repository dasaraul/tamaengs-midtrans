<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'code',
        'registration_start',
        'registration_end',
        'requirements',
        'prizes',
        'active'
    ];
    
    protected $casts = [
        'registration_start' => 'date',
        'registration_end' => 'date',
        'active' => 'boolean',
    ];
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    // Asli sudah benar, tapi sekarang ditambah alias untuk kompatibilitas
    public function criteria()
    {
        return $this->hasMany(EvaluationCriteria::class, 'product_id');
    }
    
    // Alias dari criteria() untuk kompatibilitas dengan kode yang menggunakan evaluationCriterias()
    public function evaluationCriterias()
    {
        return $this->hasMany(EvaluationCriteria::class, 'product_id');
    }
    
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'product_id');
    }
    
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'product_id');
    }
    
    public function judges()
    {
        return User::whereHas('evaluations', function ($query) {
            $query->where('product_id', $this->id);
        })->where('role', 'juri')->get();
    }
    
    // Check if registration is open
    public function isRegistrationOpen()
    {
        $today = now()->startOfDay();
        return $this->active && 
               $today->greaterThanOrEqualTo($this->registration_start) && 
               $today->lessThanOrEqualTo($this->registration_end);
    }
    
    // Get submissions count
    public function getSubmissionsCount()
    {
        return $this->submissions()->count();
    }
    
    // Get registered teams count - metode ini sudah ada dan benar
    public function getRegistrationsCount()
    {
        return OrderItem::where('product_id', $this->id)
                ->whereHas('order', function($query) {
                    $query->where('payment_status', 'paid');
                })
                ->count();
    }
}