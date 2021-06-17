<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:حذف المرفق', ['only' => ['destroy']]);
        $this->middleware('permission:اضافة مرفق', ['only' => ['store']]);
    }


    public function store(Request $request)
    {
        // return $request;
        $invoice_number = $request->invoice_number;
        $invoice_id = $request->invoice_id;

        $request->validate([
            'file' => 'nullable|mimes:png,jpg,jpeg,pdf,txt',
        ], [
            'file.mimes' => ' خطأ:لم يتم حفظ المرفق لان صيغته غير مدعومة ',
        ]);


        $file_name = $request->file('file')->getClientOriginalName();


        $attachments = new InvoiceAttachment();
        $attachments->invoice_id = $invoice_id;
        $attachments->invoice_number = $invoice_number;
        $attachments->file_name = $file_name;
        $attachments->created_by = Auth::user()->name;
        $attachments->save();

        // move pic
        $request->file->move(public_path('attachments/' . $invoice_number), $file_name);
        return back()->with(['notify_success' => 'تم اضافة المرفق بنجاح']);
    }



    public function destroy(Request $request, $id)
    {
        // return $request;
        try {

            $id = $request->file_id;
            $attachment = InvoiceAttachment::findOrFail($id);
            $file_name = $attachment->file_name;
            $invoice_number = $attachment->invoice_number;
            Storage::disk('uploads')->delete($invoice_number . '/' . $file_name);
            // Storage::disk('uploads')->delete($request->invoice_number . '/' . $request->file_name);
            $attachment->delete();



            return back()->with(['notify_success' => 'تم الحذف بنجاح']);
        } catch (Exception $e) {

            return back()->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }



    public function download_file($invoice_number, $file_name)

    {
        $contents = Storage::disk('uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number . '/' . $file_name);
        return response()->download($contents);
    }



    public function open_file($invoice_number, $file_name)

    {
        $file = Storage::disk('uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number . '/' . $file_name);
        return response()->file($file);
    }
}
