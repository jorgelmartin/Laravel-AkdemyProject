<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'program_id',
        'description'
    ];
    
    public function user() {
        return $this->belongsToMany(User::class, 'user_convocation', 'convocation_id', 'user_id');
    }
    public function program() {
        return $this->belongsTo(Program::class);
    }
}