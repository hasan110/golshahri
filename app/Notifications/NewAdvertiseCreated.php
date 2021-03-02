<?php

namespace App\Notifications;

use App\Models\User\User;
use App\Models\Advertise\Advertise;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAdvertiseCreated extends Notification
{
    use Queueable;
    private $advertise;

    public function __construct(Advertise $advertise)
    {
        $this->advertise = $advertise;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'title'=>"آگهی جدید" ,
            'content'=>"آگهی جدید با عنوان (" . $this->advertise->title . ") ثبت شد .",
            'time'=>now()->format('Y-m-d H:i:s')
        ];
    }
}
