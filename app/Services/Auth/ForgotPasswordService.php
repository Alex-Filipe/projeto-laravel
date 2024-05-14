<?php

namespace App\Services\Auth;

use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use App\Notifications\ResetPasswordNotification;
use Exception;
use Illuminate\Support\Str;

class ForgotPasswordService
{
    protected $userRepository;
    protected $authRepository;

    public function __construct(UserRepository $userRepository, AuthRepository $authRepository)
    {
        $this->userRepository = $userRepository;
        $this->authRepository = $authRepository;
    }

    /**
     * Solicita uma redefinição de senha para o usuário com o email fornecido.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function forgotPassword($request)
    {
        try 
        {
            // Valide o email fornecido
            $email = $request->input('email');
            $user = $this->userRepository->findUserByEmail($email);

            // Cria o registro do token de redefinição de senha no banco de dados
            $token = Str::random(60);
            $this->authRepository->createResetToken($user, $token);

            // Envio de email
            $user->notify(new ResetPasswordNotification($token));
            return ['status' => 200, 'message' => 'Email para redefinição de senha enviado com sucesso. Verifique sua caixa postal.'];

        } catch (\Throwable $e) {
            $statusCode = $e->getCode() ? $e->getCode() : 500; 
            Log::error('Erro no envio de email para redefinir a senha: ' . $e->getMessage() . $statusCode, ['trace' => $e->getTraceAsString()]);
            
            throw new Exception($e->getMessage(), $statusCode);  
        }
    }
}
