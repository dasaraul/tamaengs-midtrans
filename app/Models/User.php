<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'institution',
        'faculty',
        'npm',
        'semester'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    
    /**
     * Check if user is Kiw (Super Admin)
     */
    public function isKiw()
    {
        return $this->hasRole('kiw');
    }
    
    /**
     * Check if user is Admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->isKiw();
    }
    
    /**
     * Check if user is Juri
     */
    public function isJuri()
    {
        return $this->hasRole('juri');
    }
    
    /**
     * Check if user is Peserta
     */
    public function isPeserta()
    {
        return $this->hasRole('peserta');
    }
    
    /**
     * Get orders related to this user
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    /**
     * Get evaluations given by this user (as juri)
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
    
    /**
     * Get competitions assigned to this juri
     */
    public function assignedCompetitions()
    {
        if (!$this->isJuri()) {
            return collect();
        }
        
        return Product::whereHas('evaluations', function ($query) {
            $query->where('user_id', $this->id);
        })->get();
    }
}