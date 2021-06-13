<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index($id)
    // {
    //     if (view()->exists($id)) {
    //         return view($id);
    //     } else {
    //         return view('404');
    //     }

    //     //   return view($id);
    // }

    public function index()
    {
        $all_invoices =   number_format(Invoice::sum('total'), 2);
        return view('home', compact('all_invoices'));
    }
}
