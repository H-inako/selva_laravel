<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class PasswordResetNotification extends ResetPassword
{
    use Queueable;
    //private string $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    //public function __construct()
    //{
        //
    //}

    
    
    public function __construct(string $token )
        {
          $this->token = $token;
        }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //return (new MailMessage)
          //          ->line('The introduction to the notification.')
            //        ->action('Notification Action', url('/'))
              //      ->line('Thank you for using our application!');
              if (static::$toMailCallback) {
                return call_user_func(static::$toMailCallback, $notifiable, $this->token);
            }
    
            return (new MailMessage)
                        ->subject('パスワードリセット通知')
                        ->view('emails.password_reset', [
                            'reset_url' => url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false))
                        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
