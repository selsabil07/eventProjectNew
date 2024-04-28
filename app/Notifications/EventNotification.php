<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */


    private $eventTitle;
    private $sector;
    private $summary;
    private $startingDate;
    private $endingDate;
    
    public function __construct($eventTitle, $sector, $startingDate, $endingDate, $summary)
    {
        $this->eventTitle = $eventTitle;
        $this->sector = $sector;
        $this->summary = $summary;
        $this->startingDate = $startingDate;
        $this->endingDate = $endingDate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('We are excited to inform you that a new event has been created on our website! The event details are as follows:')
            ->line('Event Name: ' . $this->eventTitle)
            ->line('Starting Date: ' . $this->startingDate)
            ->line('Ending Date: ' . $this->endingDate)
            ->line('Summary: ' . $this->summary)
            ->line('Thank you for subscribing to our updates. We look forward to seeing you at the event!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'eventTitle' => $this->eventTitle,
            'sector' => $this->sector,
            'startingDate' => $this->startingDate,
            'endingDate' => $this->endingDate,
            'summary' => $this->summary,
        ];
    }
}
