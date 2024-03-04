<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'user_id',
        'description',
        'priority',
        'category',
        'due_date',
        'attachment',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function comments() {
        return $this->belongsToMany(Comment::class);
    }
}
