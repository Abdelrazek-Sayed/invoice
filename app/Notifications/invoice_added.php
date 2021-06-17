<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class invoice_added extends Notification
{
    use Queueable;
    private $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }


    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        // $url = 'http://127.0.0.1:8000/invoice/' . $this->invoice_id;
        return (new MailMessage)
            ->subject(' فاتورة جديدة')
            ->line('تم اضافة فاتورة جديدة ')
            ->action('عرض الفاتورة', route("invoice.show", $this->invoice->id))
            ->line('شكرا لاستخدامك شركتنا لادارة الفواتير');
    }


    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->invoice->id,
            'title' => 'تم اضافة فاتورة جديدة بواسطة ',
            'user' => Auth::user()->name,
        ];
    }
}
