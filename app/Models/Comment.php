<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable =[
        'task_id',
        'conotent'
    ];

    public function task(){
        return $this->belongsToMany(Task::class);
    }
    use HasFactory;
}
