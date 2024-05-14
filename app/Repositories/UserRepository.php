<?php

namespace App\Repositories;

use App\Models\User;
use Exception;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findUserByEmail($email)
    {
        try {
            $user = $this->model->where('email', $email)->first();
    
            if (!$user) {
                throw new Exception("Usuario " . $email . " não encontrado.", 404);  
            }

            return $user;
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        
    }
}
