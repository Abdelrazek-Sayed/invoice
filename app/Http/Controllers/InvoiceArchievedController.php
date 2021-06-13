<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\Storage;

class InvoiceArchievedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::onlyTrashed()->orderBy('id', 'DESC')->get();
        return view('invoices.Archive_Invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
