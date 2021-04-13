<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GlobalNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification) {

        $this -> notification = $notification;
        $this -> notify_by = ['database'];

        $this -> show_link = 'yes';

        if($notification['notify_by_email'] == 'yes') {
            $this -> notify_by[] = 'mail';
        }
        if($notification['notify_by_text'] == 'yes') {
            $this -> notify_by[] = 'nexmo';
        }

        if($notification['type'] == 'commission') {

            $this -> link_url = '/agents/doc_management/transactions/transaction_details/'.$notification['transaction_id'].'/'.$notification['transaction_type'].'?tab=commission';
            $this -> link_text = 'View Commission';

        } else if($notification['type'] == 'release') {

            $this -> link_url = '/doc_management/document_review/'.$notification['transaction_id'];
            $this -> link_text = 'View Release';

        } else if($notification['type'] == 'earnest' || $notification['type'] == 'using_heritage_title') {

            $this -> link_url = '/agents/doc_management/transactions/transaction_details/'.$notification['transaction_id'].'/'.$notification['transaction_type'];
            $this -> link_text = 'View Contract';

        }
        // agent notifications
        else if($notification['type'] == 'commission_ready') {

            $this -> link_url = '/agents/doc_management/transactions/transaction_details/'.$notification['transaction_id'].'/'.$notification['transaction_type'].'?tab=commission';
            $this -> link_text = 'View Commission';

        } else if($notification['type'] == 'bounced_earnest') {

            $this -> link_url = '/agents/doc_management/transactions/transaction_details/'.$notification['transaction_id'].'/'.$notification['transaction_type'];
            $this -> link_text = 'View Contract';
            if($notification['show_link'] == 'no') {
                $this -> show_link = 'no';
            }

        }

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this -> notify_by;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if($this -> show_link == 'yes') {
            return (new MailMessage)
                -> subject($this -> notification['subject'])
                -> line(new HtmlString($this -> notification['message_email']))
                -> action($this -> link_text, url($this -> link_url));
        }

        return (new MailMessage)
            -> subject($this -> notification['subject'])
            -> line(new HtmlString($this -> notification['message_email']));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => $this -> notification['type'],
            'transaction_type' => $this -> notification['transaction_type'],
            'transaction_id' => $this -> notification['transaction_id'],
            'message' => $this -> notification['message'],
            'link_url' => $this -> link_url,
            'link_text' => $this -> link_text
        ];
    }
}
