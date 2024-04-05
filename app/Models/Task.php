<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
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
        return $this->belongsTo(User::class);
    }
    
    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
