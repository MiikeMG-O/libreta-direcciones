<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'direccion'
    ];

    public function contacto()
    {
        return $this->belongsTo(Contacto::class);
    }
}
