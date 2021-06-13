<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(InvoiceAttachment::class, 'invoice_id', 'id');
    }
}
