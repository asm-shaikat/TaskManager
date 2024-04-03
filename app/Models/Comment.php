<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable =[
        'task_id',
        'user_id',
        'content',
        'attachments',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function task(){
        return $this->belongsToMany(Task::class);
    }
    use HasFactory;
}
