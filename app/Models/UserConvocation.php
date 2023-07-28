<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConvocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'convocation_id',
        'status',
    ];
}