<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCreated extends Notification
{
    use Queueable;
    public $invoice_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invoice_id)
    {
        $this->$invoice_id = $invoice_id;
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
        // $url = 'http://127.0.0.1:8000/invoice/' . $this->invoice_id;
        // $url = 'http://127.0.0.1:8000/InvoicesDetails/'.$this->invoice_id;

        return (new MailMessage)
            ->subject(' فاتورة جديدة')
            ->line('تم اضافة فاتورة جديدة ')
            ->action('عرض الفاتورة', url("invoice/$this->invoice_id"))
            // ->action('عرض الفاتورة', route("invoice.show", $this->invoice_id))
            ->line('شكرا لاستخدامك شركتنا لادارة الفواتير');
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
