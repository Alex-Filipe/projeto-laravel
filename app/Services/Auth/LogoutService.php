<?php

namespace App\Services\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LogoutService
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Realiza o logout do usuário.
     *
     * @return array
     * @throws \Exception
     */
    public function logout()
    {
        try {
            auth()->logout();

            return [
                'status' => 200,
                'message' => 'Logout realizado com sucesso'
            ];

        } catch (Exception $e) {
            Log::error('Erro no logout: ' . $e->getMessage() . ' (' . $e->getCode() . ')', ['trace' => $e->getTraceAsString()]);

            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
