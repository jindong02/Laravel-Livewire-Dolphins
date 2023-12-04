<?php

namespace App\Livewire\ModuleGroup;

use Livewire\Component;
use App\Models\PlotItem;
use App\Models\PlotModule;
use Illuminate\Support\Facades\DB;

class ChartForm extends Component
{

    public $category = 'all';
    public $type = 'year';
    public $options = [
        'year' => 'Year',
        'month' => 'Month',
        'week' => 'Week',
        'day' => 'Day',
    ];
    public $year = 0;
    public $month = 0;
    public $week = 0;
    public $day = 0;
    public $data = [];
    public $plots = [];

    public function mount()
    {
        // Get Current Year
        $this->year = date("Y");

        // Get Current Month
        $this->month = date("M");

        // Get Current Month
        $this->day = date("D");

        // Get Current Week
        $this->week = date("N", strtotime(date("Y-M-D")));
    }

    public function render()
    {
        // Init Chart Data

        // Get Plots for Table
        $this->plots = ($this->category == 'all') ? PlotModule::orderBy('dates', 'asc')->get() : PlotItem::find($this->category)->plotModules;

        // Get Years
        $query = DB::table('plot_modules')
            ->select(DB::raw('YEAR(dates) AS resultyear'));
        if($this->category != 'all')
            $query = $query->where('item_id', '=', $this->category);
        $years = $query->groupBy(DB::raw('YEAR(dates)'))
            ->get();

        // Get Chart Data
        if($this->type == 'day'){
            $query = DB::table('plot_modules')
                    ->select(DB::raw('YEAR(dates) AS resultyear, MONTH(dates) AS resultmonth, (MOD(WEEK(dates), 5) + 1) AS resultweek,dates AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'));
            if($this->category != 'all')
                $query = $query->where('item_id', '=', $this->category);
            
            $this->data = $query
                    ->where(DB::raw('YEAR(dates)'), '=', $this->year)
                    ->where(DB::raw('MONTH(dates)'), '=', $this->month)
                    ->where(DB::raw('(MOD(WEEK(dates), 5) + 1)'), '=', $this->week)
                    ->groupBy(DB::raw('dates'))
                    ->get();
        }elseif($this->type == 'week'){
            $query = DB::table('plot_modules')
                    ->select(DB::raw('YEAR(dates) AS resultyear, MONTH(dates) AS resultmonth,(MOD(WEEK(dates), 5) + 1) AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'));
            if($this->category != 'all')
                $query = $query->where('item_id', '=', $this->category);
            
            $this->data = $query
                    ->where(DB::raw('YEAR(dates)'), '=', $this->year)
                    ->where(DB::raw('MONTH(dates)'), '=', $this->month)
                    ->groupBy(DB::raw('YEAR(dates)'))
                    ->groupBy(DB::raw('MONTH(dates)'))
                    ->groupBy(DB::raw('(MOD(WEEK(dates), 5) + 1)'))
                    ->get();
        } elseif($this->type == 'month'){
            $query = DB::table('plot_modules')
                    ->select(DB::raw('YEAR(dates) AS resultyear, MONTH(dates) AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'));
            if($this->category != 'all')
                $query = $query->where('item_id', '=', $this->category);
            
            $this->data = $query
                    ->where(DB::raw('YEAR(dates)'), '=', $this->year)
                    ->groupBy(DB::raw('YEAR(dates)'))
                    ->groupBy(DB::raw('MONTH(dates)'))
                    ->get();
        } elseif($this->type == 'year'){
            $query = DB::table('plot_modules')
                    ->select(DB::raw('YEAR(dates) AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'));
            if($this->category != 'all')
                $query = $query->where('item_id', '=', $this->category);
            
            $this->data = $query
                    ->groupBy(DB::raw('YEAR(dates)'))
                    ->get();
        } else {
            $query = DB::table('plot_modules')
                    ->select(DB::raw('YEAR(dates) AS resultyear, MONTH(dates) AS resultmonth, (MOD(WEEK(dates), 5) + 1) AS resultweek,dates AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'));
            if($this->category != 'all')
                $query = $query->where('item_id', '=', $this->category);
            
            $this->data = $query
                    ->where(DB::raw('YEAR(dates)'), '=', $this->year)
                    ->where(DB::raw('MONTH(dates)'), '=', $this->month)
                    ->where(DB::raw('MONTH(dates)'), '=', $this->week)
                    ->groupBy(DB::raw('dates'))
                    ->get();
        }
        
        return view('livewire.module-group.chart-form', [
            'items' => PlotItem::all(),
            'years' => $years,
            'data' => $this->data,
            'plots' => $this->plots
        ]);
    }

    public function loadChartData()
    {
        $this->dispatch('renderChart', [
            'data' => $this->getChartData()
        ]);
    }

    public function getChartData(){
        if($this->type == 'day'){
            
            $query = DB::table('plot_modules')
            ->select(DB::raw('YEAR(dates) AS resultyear, MONTH(dates) AS resultmonth, (MOD(WEEK(dates), 5) + 1) AS resultweek,dates AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'));
            if($this->category != 'all')
            $query = $query->where('item_id', '=', $this->category);

            $this->data = $query
                    ->where(DB::raw('YEAR(dates)'), '=', $this->year)
                    ->where(DB::raw('MONTH(dates)'), '=', $this->month)
                    ->where(DB::raw('(MOD(WEEK(dates), 5) + 1)'), '=', $this->week)
                    ->groupBy(DB::raw('dates'))
                    ->get();
            
        }elseif($this->type == 'week'){
            $query = DB::table('plot_modules')
                    ->select(DB::raw('YEAR(dates) AS resultyear, MONTH(dates) AS resultmonth,(MOD(WEEK(dates), 5) + 1) AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'));
            if($this->category != 'all')
                $query = $query->where('item_id', '=', $this->category);
            
            $this->data = $query
                    ->where(DB::raw('YEAR(dates)'), '=', $this->year)
                    ->where(DB::raw('MONTH(dates)'), '=', $this->month)
                    ->groupBy(DB::raw('YEAR(dates)'))
                    ->groupBy(DB::raw('MONTH(dates)'))
                    ->groupBy(DB::raw('(MOD(WEEK(dates), 5) + 1)'))
                    ->get();
        } elseif($this->type == 'month'){
            $query = DB::table('plot_modules')
                    ->select(DB::raw('YEAR(dates) AS resultyear, MONTH(dates) AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'));
            if($this->category != 'all')
                $query = $query->where('item_id', '=', $this->category);
            
            $this->data = $query
                    ->where(DB::raw('YEAR(dates)'), '=', $this->year)
                    ->groupBy(DB::raw('YEAR(dates)'))
                    ->groupBy(DB::raw('MONTH(dates)'))
                    ->get();
        } elseif($this->type == 'year'){
            $query = DB::table('plot_modules')
                    ->select(DB::raw('YEAR(dates) AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'));
            if($this->category != 'all')
                $query = $query->where('item_id', '=', $this->category);
            
            $this->data = $query
                    ->groupBy(DB::raw('YEAR(dates)'))
                    ->get();
        } else {
            $query = DB::table('plot_modules')
                    ->select(DB::raw('YEAR(dates) AS resultyear, MONTH(dates) AS resultmonth, (MOD(WEEK(dates), 5) + 1) AS resultweek,dates AS resultx, SUM(qty_sold) + SUM(qty_remain) AS resulty'));
            if($this->category != 'all')
                $query = $query->where('item_id', '=', $this->category);
            
            $this->data = $query
                    ->where(DB::raw('YEAR(dates)'), '=', $this->year)
                    ->where(DB::raw('MONTH(dates)'), '=', $this->month)
                    ->where(DB::raw('(MOD(WEEK(dates), 5) + 1)'), '=', $this->week)
                    ->groupBy(DB::raw('dates'))
                    ->get();
        }
        return $this->data;
    }

    public function changeCategoryEvent($value)
    {
        $this->category = $value;
        $this->loadChartData();
    }

    public function changeTypeEvent($value)
    {
        $this->type = $value;
        $this->loadChartData();
    }

    public function changeYearEvent($value)
    {
        $this->year = $value;
        $this->loadChartData();
    }

    public function changeMonthEvent($value)
    {
        $this->month = $value;
        $this->loadChartData();
    }

    public function changeWeekEvent($value)
    {
        $this->week = $value;
        $this->loadChartData();
    }

}
