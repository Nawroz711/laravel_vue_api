<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class todo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id' , 'title' , 'description' , 'priority' , 'attachments'];
}
