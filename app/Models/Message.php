<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'convocation_id',
        'message',
        'date'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function convocation()
    {
        return $this->belongsTo(Convocation::class);
    }
}
