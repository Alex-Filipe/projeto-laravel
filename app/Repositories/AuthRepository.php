<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function createResetToken($user, $token)
    {
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token
        ]);
    }

    // public function validateResetToken($user, $token)
    // {
    //     $reset = DB::table('password_resets')
    //         ->where('email', $user->email)
    //         ->where('token', $token)
    //         ->first();

    //     return $reset;
    // }

    // public function updatePassword($user, $newPassword)
    // {
    //     $user->password = Hash::make($newPassword);
    //     $user->save();
    // }
}
