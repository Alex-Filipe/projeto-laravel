<?php

use App\Services\AuthService;
use Tests\TestCase;
use App\Models\User;
use App\Services\Auth\LoginService;
use App\Services\Auth\LogoutService;

class AuthTest extends TestCase
{
    protected $loginService;
    protected $logoutService;

    public function setUp(): void
    {
        parent::setUp();

        // Criar um usuário de exemplo no banco de dados de teste
        User::factory()->create([
            'email' => 'example@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->loginService = new LoginService(new User);
        $this->logoutService = new LogoutService(new User);
    }

    public function tearDown(): void
    {
        // Limpar os dados do banco de dados de teste após a execução dos testes
        User::where('email', 'example@example.com')->delete();
        parent::tearDown();
    }


    /**
     * Configura o ambiente de teste antes da execução dos testes.
     */
    public function testLoginWithValidCredentials()
    {
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'email' => 'example@example.com',
            'password' => 'password',
        ]);

        $tokenData = $this->loginService->login($request);

        $this->assertArrayHasKey('token_type', $tokenData);
        $this->assertArrayHasKey('expires_in', $tokenData);
        $this->assertArrayHasKey('access_token', $tokenData);
    }

    /**
     * Testa a autenticação com credenciais válidas.
     */
    public function testLoginWithInvalidCredentials()
    {
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'email' => 'example@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(401);

        $this->loginService->login($request);
    }

    /**
     * Testa o logout de um usuário autenticado.
     */
    public function testLogoutAuthenticatedUser()
    {
        // Autenticar o usuário de exemplo
        $user = User::where('email', 'example@example.com')->first();
        $token = auth()->login($user);

        // Realizar o logout do usuário autenticado
        $response = $this->logoutService->logout();

        // Verificar se o logout foi bem-sucedido
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('Logout realizado com sucesso', $response['message']);
    }
}
