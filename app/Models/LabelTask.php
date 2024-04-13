<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelTask extends Model
{
    use HasFactory;
    protected $table = 'labeltask';
    protected $fillable = [
        'task_id',
        'label_id'
    ];
}
