<?php

namespace App\Services\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class LoginService
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Realiza a autenticação do usuário e gera um token JWT.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function login($request)
    {
        // Validação do validator
        $validator = Validator::make($request->all(), $this->userModel->validationRulesLogin(), $this->userModel->validationMessagesLogin());

        if ($validator->fails()) 
        {
            throw new Exception($validator->errors()->first(), 400);
        }

        try 
        {
            // Autentica o usuário com as credenciais fornecidas
            $credentials = $request->only('email', 'password');
            $token = $this->userModel->verifyCredentials($credentials);

            // $perfil = PerfilUsuario::where('id_usuario',$user->id)->get();

            // if($perfil[0]->id_perfil != 2){
            //     return response()->json(['error' => 'O usuário não tem permissão para acessar.'], 401);
            // }

            return [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => FacadesJWTAuth::factory()->getTTL() * 60,
                'user' => auth()->user(),
                // 'user'=>$this->getPerfil()
            ];

        } catch (Exception $e) {  
            Log::error('Erro durante a autenticação: ' . $e->getMessage() . $e->getCode(), ['trace' => $e->getTraceAsString()]);

            throw new Exception($e->getMessage(), $e->getCode());
        }

        
    }
}
