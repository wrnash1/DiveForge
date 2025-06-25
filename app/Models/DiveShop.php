<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiveShop extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'website', 
        'timezone', 'currency', 'owner_id', 'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}