<?php

namespace App\Notifications;

use App\Models\Ad;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageRefuse extends Notification
{
    use Queueable;

  
    protected $message;
    protected $messageRefus;
    protected $ad;
    public function __construct(Ad $ad, Message $message, $messageRefus)
    {
        $this->ad = $ad;
        $this->message = $message;
        $this->messageRefus = $messageRefus;
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
        return (new MailMessage)
        ->line('Nous avons refusé ce message que vous avez déposé :')
        ->line('--------------------------------------')
        ->line($this->message->texte)
        ->line('--------------------------------------')
        ->line('Pour la raison suivante :')
        ->line('--------------------------------------')
        ->line($this->messageRefus)
        ->line('--------------------------------------')
        ->action('Voir cette annonce', route('annonces.show', $this->ad->id))
        ->line("Merci d'utiliser notre site pour vos annonces !");

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
