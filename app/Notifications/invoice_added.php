<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;


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
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->invoice->id,
            'title' => 'تم اضافة فاتورة جديد بواسطة ',
            'user' => Auth::user()->name,
        ];
    }
}
