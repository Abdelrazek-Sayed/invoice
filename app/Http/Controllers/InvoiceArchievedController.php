<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\Storage;

class InvoiceArchievedController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:ارشيف الفواتير', ['only' => ['index', 'update']]);
        $this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
    }
    public function index()
    {
        $invoices = Invoice::onlyTrashed()->orderBy('id', 'DESC')->get();
        return view('invoices.Archive_Invoices', compact('invoices'));
    }

    public function update(Request $request, $id)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::withTrashed()->where('id', $invoice_id);
        if (!$invoice) {
            return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
        }
        $invoice->restore();

        return redirect()->route('invoice.index')->with(['notify_success' => 'تم استعادة الفاتورة ']);
    }

    public function destroy(Request $request, $id)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::withTrashed()->where('id', $invoice_id);
        if (!$invoice) {
            return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
        }

        $Details = InvoiceAttachment::where('invoice_id', $invoice_id)->first();
        if (!empty($Details->invoice_number)) {

            Storage::disk('uploads')->deleteDirectory($Details->invoice_number);
        }
        $invoice->forceDelete();
        return redirect()->route('invoice.index')->with(['notify_delete' => 'تم حذف الفاتورة نهائيا']);
    }
}
