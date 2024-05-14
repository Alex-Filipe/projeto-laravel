<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password'
    ];


    public static function validationRulesLogin()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public static function validationMessagesLogin()
    {
        return [
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.email' => 'O e-mail fornecido não é válido.',
            'password.required' => 'O campo de senha é obrigatório.',
        ];
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function verifyCredentials($credentials)
    {
        try {
            if (!$token = FacadesJWTAuth::attempt($credentials)) 
            {
                throw new Exception('Credenciais inválidas', 401);
            }
            return $token;

        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    
    }

}
