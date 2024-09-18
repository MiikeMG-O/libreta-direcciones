<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'empresa',
        'notas',
        'pagina_web',
        'cumpleanos'
    ];

    public function telefonos()
    {
        return $this->hasMany(Telefono::class);
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class);
    }

    public function emails()
    {
        return $this->hasMany(Email::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
