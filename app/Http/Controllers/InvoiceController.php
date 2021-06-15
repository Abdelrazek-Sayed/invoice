<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use Exception;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoiceAttachment;
use App\Notifications\invoice_added;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\InvoiceCreated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::orderBy('id', 'DESC')->get();
        return view('invoices.invoice', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



        //validation
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'product' => 'required',
            'section_id' => 'required|exists:sections,id',
            'amount_collection' => 'required',
            'amount_commission' => 'required',
            'discount' => 'required',
            'value_vat' => 'required',
            'rate_vat' => 'required',
            'total' => 'required',
            'note' => 'nullable',
        ], [
            'required' => 'هذا الحقل مطلوب',
            'date' => 'هذا الحقل يجب ان يكون تاريخ',
            'unique' => 'هذا الحقل موجود مسبقا',
            'due_date.after_or_equal' => 'عذرا تاريخ الاستحقاق يجب ان يكون بعد تاريخ الفاتورة'

        ]);

        try {
            $invoice = [
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'section_id' => $request->section_id,
                'product' => $request->product,
                'amount_collection' => $request->amount_collection,
                'amount_commission' => $request->amount_commission,
                'discount' => $request->discount,
                'value_vat' => $request->value_vat,
                'rate_vat' => $request->rate_vat,
                'total' => $request->total,
                'note' => $request->note,
            ];

            $invoice_id = Invoice::insertGetId($invoice);


            InvoiceDetail::create([
                'invoice_id' => $invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section_id,
                'note' => $request->note,
                'user' => (Auth::user()->name),
            ]);

            if ($request->hasFile('file')) {

                $request->validate([
                    'file' => 'nullable|mimes:png,jpg,jpeg,pdf,txt',
                ], [
                    'file.mimes' => ' خطأ: تم حفظ الفاتورة ولم يتم حفظ المرفق لان صيغته غير مدعومة ',
                ]);

                $invoice_number = $request->invoice_number;
                $file_name = $request->file('file')->getClientOriginalName();


                $attachments = new InvoiceAttachment();
                $attachments->invoice_id = $invoice_id;
                $attachments->invoice_number = $invoice_number;
                $attachments->file_name = $file_name;
                $attachments->created_by = Auth::user()->name;
                $attachments->save();

                // move pic
                // $fileName = $request->file->getClientOriginalName();
                $request->file->move(public_path('attachments/' . $invoice_number), $file_name);
            }

            // $user = User::first();
            // Notification::send($user, new InvoiceCreated($invoice_id));


            $recievers = User::get(); // all users
            //  $recievers = User::find(auth()->user()->id); //  user who creates invoice

            $invoice = Invoice::findOrFail($invoice_id);
            if (!$invoice) {
                return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
            }

            Notification::send($recievers, new  invoice_added($invoice));



            return redirect()->route('invoice.index')->with(['notify_success' => 'تم اضافة الفاتورة بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('invoice.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        if (!$invoice) {
            return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
        }
        $details = InvoiceDetail::where('invoice_id', $id)->get();
        $attachments = InvoiceAttachment::where('invoice_id', $id)->get();
        return view('invoices.invoice_details', compact('invoice', 'attachments', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        if (!$invoice) {
            return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
        }
        $sections = Section::all();
        return view('invoices.edit', compact('invoice', 'sections'));
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
        $request->validate([
            'invoice_number' => "required|string|unique:invoices,invoice_number,$id",
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'product' => 'required',
            'section_id' => 'required|exists:sections,id',
            'amount_collection' => 'required',
            'amount_commission' => 'required',
            'discount' => 'required',
            'value_vat' => 'required',
            'rate_vat' => 'required',
            'total' => 'required',
            'note' => 'nullable',
        ], [
            'required' => 'هذا الحقل مطلوب',
            'date' => 'هذا الحقل يجب ان يكون تاريخ',
            'unique' => 'هذا الحقل موجود مسبقا',
            'exists' => 'هذا الحقل غير موجود ',
        ]);


        try {


            $invoice = Invoice::findOrFail($id);
            if (!$invoice) {
                return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
            }
            $invoice_old_number = $invoice->invoice_number;
            $invoice_new_number = $request->invoice_number;
            $invoice->update([
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'section_id' => $request->section_id,
                'product' => $request->product,
                'amount_collection' => $request->amount_collection,
                'amount_commission' => $request->amount_commission,
                'discount' => $request->discount,
                'value_vat' => $request->value_vat,
                'rate_vat' => $request->rate_vat,
                'total' => $request->total,
                'note' => $request->note,
            ]);

            $details = InvoiceDetail::where('invoice_id', $id)->get();
            $attachments = InvoiceAttachment::where('invoice_id', $id)->get();

            foreach ($details as $detail) {
                $detail->update([

                    'invoice_number' => $request->invoice_number,
                    'section' => $request->section_id,
                    'product' => $request->product,
                    'note' => $request->note,
                    'user' => (Auth::user()->name),
                ]);
            }

            foreach ($attachments as $attachment) {
                $attachment->update([

                    'invoice_number' => $request->invoice_number,
                    'created_by' => (Auth::user()->name),
                ]);
            }


            Storage::disk('uploads')->move($invoice_old_number, $invoice_new_number);

            return redirect()->route('invoice.show', $id)->with(['notify_success' => 'تم تعديل الفاتورة بنجاح']);
        } catch (Exception $e) {
            // return $e;
            // DB::rollback();
            return redirect()->route('invoice.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }



    public function archieve(Request $request)
    {
        // return $request;
        try {
            $invoice_id = $request->invoice_id;
            $invoice = Invoice::findOrFail($invoice_id);
            if (!$invoice) {
                return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
            }
            $invoice->delete();
            return back()->with(['notify_success' => 'تم نقل الفاتورة الى الارشيف']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->back()->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }

  
    public function destroy(Request $request)
    {
        // return $request;
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::where('id', $invoice_id)->first();
        if (!$invoice) {
            return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
        }
        $Details = InvoiceAttachment::where('invoice_id', $invoice_id)->first();

        if (!empty($Details->invoice_number)) {

            Storage::disk('uploads')->deleteDirectory($Details->invoice_number);
        }

        $invoice->forceDelete();
        return back()->with(['notify_delete' => 'تم حذف الفاتورة نهائيا']);
    }
    public function getProducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("name", "id");
        return json_encode($products);
    }
    public function editStatus($invoice_id)
    {

        $invoice = Invoice::findOrFail($invoice_id);
        if (!$invoice) {
            return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
        }
        return view('invoices.status_update', compact('invoice'));
    }

    public function updateStatus(Request $request)
    {
        // return $request;
        $request->validate([
            'status' => 'required|integer',
            'payment_date' => 'required|date',
            'invoice_id' => 'required|exists:invoices,id',
        ], [
            'required' => 'هذا الحقل مطلوب',
            'date' => 'هذا الحقل يجب ان يكون تاريخ',
            'exists' => 'هذا الحقل غير موجود ',
        ]);
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::findOrFail($invoice_id);
        // if (!$invoice) {
        //     return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
        // }
        $invoice->update([
            'status' => $request->status,
            'payment_date' => $request->payment_date,
        ]);

        InvoiceDetail::create([
            'invoice_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->section_id,
            'status' => $request->status,
            'payment_date' => $request->payment_date,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);
        return redirect()->route('invoice.index')->with(['notify_success' => 'تم تغير حالة الدفع للفاتورة']);
    }



    public function Invoice_unPaid()
    {
        $invoices = Invoice::where('status', 0)->get();
        return view('invoices.invoices_unpaid', compact('invoices'));
    }
    public function Invoice_Paid()
    {
        $invoices = Invoice::where('status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function Invoice_Partial()
    {
        $invoices = Invoice::where('status', 2)->get();
        return view('invoices.invoices_Partial', compact('invoices'));
    }

    public function Invoice_Print(Request $request, $id)
    {
        // $invoice_id = $request->invoice_id;
        $invoice = Invoice::where('id', $id)->first();
        if (!$invoice) {
            return back()->with(['notify_error' => 'رقم الفاتورة غير صحيح']);
        }
        return view('invoices.Print_invoice', compact('invoice'));
    }


    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    public function MarkAsRead_all(Request $request)
    {
        $userUnreadNotification = auth()->user()->unreadNotifications;

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }
    }
    public function MarkAsRead_one($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->route('invoice.show', $id);
    }
}
