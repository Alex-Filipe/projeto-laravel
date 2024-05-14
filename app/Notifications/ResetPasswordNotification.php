<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    protected $token;

    /**
     * Cria uma nova instância da notificação.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Obtém as informações de notificação para o canal de e-mail.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Gere a URL para redefinir a senha com base no token fornecido.
        $url = url("/password/reset?token={$this->token}");

        // Crie uma mensagem de e-mail com instruções para redefinir a senha.
        return (new MailMessage)
            ->subject('Redefinição de Senha') // Assunto do e-mail
            ->greeting('Olá!') // Saudação
            ->line('Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.') // Mensagem de texto
            ->line('Clique no botão abaixo para redefinir sua senha:') // Nova linha de mensagem
            ->action('Redefinir Senha', $url) // Botão para redefinir a senha
            ->line('Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.') // Mensagem de texto
            ->salutation('Atenciosamente, Sua Aplicação'); // Saudação final
    }

    /**
     * Obtém os canais para os quais a notificação deve ser enviada.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
}
