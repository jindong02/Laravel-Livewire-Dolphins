<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlotItem;
use App\Models\PlotModule;

use Illuminate\Support\Facades\DB;

class PlotModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $year = date("Y");
        $month = date("M");
        $week = date("N", strtotime(date("Y-M-D")));
        $plots = PlotModule::orderBy('dates', 'asc')->get();
        
        $data = DB::table('plot_modules')
                        ->select(DB::raw('YEAR(dates) AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'))
                        ->groupBy(DB::raw('YEAR(dates)'))
                        ->get();
        $years = DB::table('plot_modules')
                    ->select(DB::raw('YEAR(dates) AS resultyear'))
                    ->groupBy(DB::raw('YEAR(dates)'))
                    ->get();
        return view('pages.module-group.plot-modules.index', [
            'plot_modules' => $plots,       //data for table
            'data' => $data,                //data for chart   
            'type' => 'year',               //change year or month or week or day. 
            'items' => PlotItem::all(),     //plot items(all, ...)
            'category' => 'all',            //plot items(all, ...)
            'years' => $years,              //year lists
            'curyear' => $year,             //changed year
            'curmonth' => $month,           //changed month
            'curweek' => $week,             //changed week
            'chart' => 'pie',              //changed chart type(line,pie,bar)
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}