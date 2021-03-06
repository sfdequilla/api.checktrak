<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ChecksReceivedNotification extends Notification
{
    protected $transmittal;
    protected $user;
    protected $receivedChecks;
    protected $unreceivedChecks;

    public function __construct($transmittal, $receivedChecks, $unreceivedChecks, $user)
    {
        $this->transmittal = $transmittal;
        $this->user = $user;
        $this->receivedChecks = $receivedChecks;
        $this->unreceivedChecks = $unreceivedChecks;
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

    public function toMail($notifiable)
    {
        $transmittal = $this->transmittal;

        return (new MailMessage)
                    ->subject('Checks Received')
                    ->greeting('Hello ' . (! $transmittal->returned ? $transmittal->user->name : $transmittal->returnedBy->name) . '!')
                    ->line($this->receivedChecks->count() . '/' . $this->unreceivedChecks->count() . ' checks from '.$transmittal->ref . ' already received by ' . $this->user->name . '.')
                    ->action('Go to App', url(config('app.ui_url') . '/check'));
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
