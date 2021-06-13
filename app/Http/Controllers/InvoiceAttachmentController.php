<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\InvoiceAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $request->file->move(public_path('attachments/' . $invoice_id), $file_name);
        return back()->with(['success' => 'تم اضافة المرفق بنجاح']);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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



            return back()->with(['success' => 'تم الحذف بنجاح']);
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
