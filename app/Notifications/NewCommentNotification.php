<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected Comment $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hello!')
            ->line("A new comment was posted by {$this->comment->creator->name} on your post titled '{$this->comment->post->title}'.")
            ->line("Comment: \"{$this->comment->comment}\"")
            ->action('View Comment', url("/posts/{$this->comment->post->id}#comment-{$this->comment->id}"))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification (for database or broadcast).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'comment_body' => $this->comment->comment,
            'comment_author' => $this->comment->creator->name,
            'post_id' => $this->comment->post->id,
            'post_title' => $this->comment->post->title,
        ];
    }
}
