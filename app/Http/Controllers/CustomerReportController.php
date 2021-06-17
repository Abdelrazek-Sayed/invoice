<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class CustomerReportController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:تقرير العملاء', ['only' => ['index', 'Search_customers']]);
    }


    public function index()
    {
        $sections = Section::all();
        return view('reports.customers_report', compact('sections'));
    }


    public function Search_customers(Request $request)
    {
        // في حالة البحث بدون التاريخ
        if ($request->section && $request->product && $request->start_at == '' && $request->end_at == '') {


            $invoices = Invoice::select('*')->where('section_id', '=', $request->section)->where('product', '=', $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_report', compact('sections'))->withDetails($invoices);
        } elseif ($request->section === 'all' && $request->start_at == '' && $request->end_at == '') {

            $invoices = Invoice::all();
            $sections = Section::all();
            return view('reports.customers_report', compact('sections'))->withDetails($invoices);
        } else { // في حالة البحث بتاريخ

            $start_at = date($request->start_at);
            $end_at = date($request->end_at);

            if ($request->section === 'all') {
                $invoices = Invoice::whereBetween('invoice_date', [$start_at, $end_at])->get();
                $sections = Section::all();
                return view('reports.customers_report',  compact('sections', 'start_at', 'end_at'))->withDetails($invoices);
            }

            $invoices = Invoice::whereBetween('invoice_date', [$start_at, $end_at])->where('section_id', '=', $request->section)->where('product', '=', $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_report', compact('sections', 'start_at', 'end_at'))->withDetails($invoices);
        }
    }
}
