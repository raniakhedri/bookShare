<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JournalSharedNotification extends Notification
{
    use Queueable;

    public $journal;
    public $sharedBy;

    public function __construct($journal, $sharedBy)
    {
        $this->journal = $journal;
        $this->sharedBy = $sharedBy;
    }

    public function via($notifiable)
    {
        return ['mail']; // Envoie par email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📚 You’ve been invited to a reading journal!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('**' . $this->sharedBy->name . '** has shared the journal **"' . $this->journal->name . '"** with you.')
            ->line('You can now view and collaborate on this journal in your Bookly account.')
            ->action('View Journal', url('/journals/' . $this->journal->id))
            ->line('Thank you for using Bookly!');
    }

    public function toArray($notifiable)
    {
        return [
            'journal_id' => $this->journal->id,
            'journal_name' => $this->journal->name,
            'shared_by' => $this->sharedBy->name,
        ];
    }
}