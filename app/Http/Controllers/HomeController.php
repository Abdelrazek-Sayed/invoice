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

        if ($paid_count == 0) {
            $paid_ratio = 0;
        } else {
            $paid_ratio = $paid_count / $all_count * 100;
        }

        if ($unpaid_count == 0) {
            $unpaid_ratio = 0;
        } else {
            $unpaid_ratio = $unpaid_count / $all_count * 100;
        }

        if ($partial_count == 0) {
            $partial_ratio = 0;
        } else {
            $partial_ratio = $partial_count / $all_count * 100;
        }

        $bar_chart = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['الفواتير المدفوعة', 'الفواتير الغير مدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => ' الفواتير المدفوعة ',
                    'backgroundColor' => ['green', 'red', 'orange'],
                    'data' => [$paid_ratio, $unpaid_ratio, $partial_ratio]
                ],
                [
                    "label" => ' الفواتير الغير مدفوعة ',
                    'backgroundColor' => ['red'],

                ],
                [
                    "label" => 'الفواتير  المدفوعة جزئيا ',
                    'backgroundColor' => ['orange'],

                ],

            ])
            ->options([]);




        $pie_chart = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['الفواتير المدفوعة', 'الفواتير الغير المدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [

                    'backgroundColor' => ['green', 'red', 'orange'],
                    'hoverBackgroundColor' => ['#FF6384', '#36A2EB', '#000'],
                    'data' => [$paid_ratio, $unpaid_ratio, $partial_ratio]
                ]
            ])
            ->options([]);

        $data = [];
        $data['all_invoices'] = $all_invoices;
        $data['paid_invoices'] = $paid_invoices;
        $data['unpaid_invoices'] = $unpaid_invoices;
        $data['partial_invoices'] = $partial_invoices;
        $data['all_count'] = $all_count;
        $data['paid_count'] = $paid_count;
        $data['unpaid_count'] = $unpaid_count;
        $data['partial_count'] = $partial_count;
        $data['bar_chart'] = $bar_chart;
        $data['pie_chart'] = $pie_chart;


        // compact('all_invoices', 'paid_invoices', 'unpaid_invoices', 'partial_invoices', 'all_count', 'paid_count', 'unpaid_count', 'partial_count', 'chartjs', 'chartjs_2')

        return view('home')->with($data);
    }
}
