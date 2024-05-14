<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\LoginService;
use App\Services\Auth\LogoutService;
use App\Services\Auth\ForgotPasswordService;

class AuthController extends Controller
{
    protected $loginService;
    protected $logoutService;
    protected $forgotPasswordService;

    public function __construct(LoginService $loginService, LogoutService $logoutService, ForgotPasswordService $forgotPasswordService)
    {
        $this->loginService = $loginService;
        $this->logoutService = $logoutService;
        $this->forgotPasswordService = $forgotPasswordService;
    }

    /**
     * Realiza a autenticação do usuário com as credenciais fornecidas.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try 
        {
            return response()->json($this->loginService->login($request));

        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocorreu um erro durante a autenticação: ' . $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Realiza o logout do usuário autenticado.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try 
        {
            return response()->json($this->logoutService->logout(), 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha no logout: ' . $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Envia um link para redefinição de senha para o email do usuário.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLink(Request $request)
    {
        try 
        {
            return response()->json($this->forgotPasswordService->forgotPassword($request), 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha no envio do link de redefinição: ' . $e->getMessage()], $e->getCode());
        }
    }
}
