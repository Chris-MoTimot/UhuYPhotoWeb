<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pin_id',
        'user_id',
        'text',
    ];

    public function pin() {
        return $this->belongsTo(Pin::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
