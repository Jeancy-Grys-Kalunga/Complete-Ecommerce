<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Auth;
class StatusNotification extends Notification
{
    use Queueable;
    private $details;
    private $url;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details=$details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->subject('Status Notification')
    //                 ->from(env('MAIL_USERNAME','test@gmail.com'),'E-shop')
    //                 ->line($this->details['title'])
    //                 ->action('View Order', $this->details['actionURL'])
    //                 ->line('Thank you!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    // public function toArray($notifiable)
    // {
    //     return [
    //         'title'=>$this->details['title'],
    //         'actionURL'=>$this->details['actionURL'],
    //         'fas'=>$this->details['fas']
    //     ];
    // }
    public function toArray($notifiable)
    {
        return [
            'title'=>$this->details['title'],
            'actionURL'=>$this->details['actionURL'],
            'fas'=>$this->details['fas']
        ];
    }


    
    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {

        if(Auth::user()->role == 'fournisseur') {
            $this->url = route('fournisseur.notification', $this->id);

        } elseif(Auth::user()->role == 'admin') {
            $this->url = route('admin.notification', $this->id);
        }
        return new BroadcastMessage([
            'title' => $this->details['title'],
            'actionURL' => $this->details['actionURL'],
            'url' => $this->url,
            'fas' => $this->details['fas'],
            'time' => date('F d, Y h:i A')
        ]);
    }


    
}
