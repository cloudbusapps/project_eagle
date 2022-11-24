<?php

namespace App\Notifications;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OvertimeEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
        $startTime = new DateTime($this->data['timeIn']);
        $timeRendered = $startTime->diff(new DateTime($this->data['timeOut']));
        // $this->data['cc'];
        // if (count($this->data['cc']) > 0) {
        //     foreach ($this->data['cc'] as $cc) {
        //     }
        // }
        return (new MailMessage)
            ->cc('testing1@example.com')
            ->subject('Overtime Notification')
            ->greeting($this->data['greeting'])
            ->line($this->data['body'])
            ->line('Agenda: ' . $this->data['agenda'])
            ->line('Date: ' . $this->data['date'])
            ->line('Time In: ' . date('g:i: a', strtotime($this->data['timeIn'])))
            ->line('Time out: ' . date('g:i: a', strtotime($this->data['timeOut'])))
            ->line('Hours Rendered: ' . $timeRendered->h . 'hrs : ' . $timeRendered->i . 'mins')
            ->line('Reason: ' . $this->data['reason'])
            ->line($this->data['thanks'])

            ->salutation("\r\n\r\n Regards,  \r\n " . $this->data['userName'] . ".");
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
