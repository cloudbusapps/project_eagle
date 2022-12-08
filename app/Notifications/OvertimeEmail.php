<?php

namespace App\Notifications;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OvertimeEmail extends Notification implements ShouldQueue
{
    use Queueable;
    protected $data;

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
        $hoursRendered = $this->getHours($this->data['timeIn'], $this->data['timeOut']);


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
            ->line('Hours Rendered: ' . $hoursRendered)
            ->line('Reason: ' . $this->data['reason'])
            ->line($this->data['thanks'])

            ->salutation("\r\n\r\n Regards,  \r\n " . $this->data['userName'] . ".");
    }
    public function getHours($TimeIn, $TimeOut)
    {
        $from_time = strtotime($TimeIn);
        $to_time = strtotime($TimeOut);
        if ($from_time > $to_time) {
            $time_perday = ($to_time + 86400 - $from_time) / 3600;
            return $hoursRendered = (int) $time_perday . ' hours, ' . ($time_perday - (int) $time_perday) * 60 . ' minutes.';
        } else {
            $time_perday = ($from_time - $to_time) / 3600;
            $date1 = new DateTime($TimeIn);
            $date2 = new DateTime($TimeOut);
            $diff = $date1->diff($date2);
            return $hoursRendered = $diff->format('%h hours, %i minutes.');
        }
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
