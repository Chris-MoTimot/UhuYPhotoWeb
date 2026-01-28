<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_private',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function pins() {
        return $this->hasMany(Pin::class);
    }
}
