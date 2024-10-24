<?php
namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderExport implements FromView
{
    public $view;
    public $arrayData;
    // public $dataall;
    // public $monthsArray;
    // public $groups;

    public function __construct($view, $arrayData = [])
    {
        $this->view = $view;
        $this->arrayData = $arrayData;
        // $this->dataall = $dataall;
        // $this->monthsArray = $monthsArray;
        // $this->groups = $groups;
    }
    public function view(): View
    {
        return view($this->view,[
            'datas'=>$this->arrayData,
            // 'monthsArray'=>$this->monthsArray,
            // 'dataall'=>$this->dataall,
            // 'groups'=>$this->groups,
            ]);
        // return view('exports.invoices', [
        //     'invoices' => $items,
        // ]);
    }
    //
}
