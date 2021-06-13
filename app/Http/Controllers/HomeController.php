<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $all_invoices =   number_format(Invoice::sum('total'), 2);
        $paid_invoices =   number_format(Invoice::where('status', 1)->sum('total'), 2);
        $unpaid_invoices =   number_format(Invoice::where('status', 0)->sum('total'), 2);
        $partial_invoices =  number_format(Invoice::where('status', 2)->sum('total'), 2);
        $all_count =   Invoice::count();
        $paid_count =    Invoice::where('status', 1)->count();
        $unpaid_count =    Invoice::where('status', 0)->count();
        $partial_count =    Invoice::where('status', 2)->count();
        return view(
            'home',
            compact('all_invoices', 'paid_invoices', 'unpaid_invoices', 'partial_invoices', 'all_count', 'paid_count', 'unpaid_count', 'partial_count')
        );
    }
}
