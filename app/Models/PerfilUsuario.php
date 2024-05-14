<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerfilUsuario extends Model
{
    protected $table = 'perfil_usuario';
    
    protected $fillable = [
        'id',
        'id_usuario',
        'id_perfil',
        'status',
        'dt_hr_gravacao',
        'created_at',
        'updated_at'
    ];
}
