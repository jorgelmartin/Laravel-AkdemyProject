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
        'program_id',
        'message',
        'date',
        'parent_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    // Agregamos una relación para obtener las respuestas a este mensaje
    public function responses()
    {
        return $this->hasMany(Message::class, 'parent_id', 'id');
    }

    // Cambiamos el nombre de la relación para obtener el mensaje al que se responde
    public function messageParent()
    {
        return $this->belongsTo(Message::class, 'parent_id', 'id');
    }
}
