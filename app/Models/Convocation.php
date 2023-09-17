<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocation extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'user_id',
        'program_id',
        'beginning',
        'schedule'
    ];
    
    public function user() {
        return $this->belongsToMany(User::class, 'inscription', 'convocation_id', 'user_id');
        // return $this->belongsTo(User::class, 'user_id' );
    }
    public function program() {
        return $this->belongsTo(Program::class);
    }
}