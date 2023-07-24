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
        'beginning',
        'end'
    ];
    
    public function user() {
        return $this->belongsToMany(User::class, 'user_convocation', 'user_id', 'convocation_id');
    }
    public function program() {
        return $this->belongsTo(Program::class);
    }
}