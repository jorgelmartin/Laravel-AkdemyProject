<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'beginning', 
        'price'
    ];
    public function convocation() {
        return $this->belongsTo(Convocation::class);
    }
}
