<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Auth;
use Excel;
use Carbon\Carbon;
use Validator;

use App\Models\Quotation;
use App\Models\Orders;
use App\Models\MasterCustomer;
use App\Models\CustomerOther;
use App\Models\OrderItmes;
use App\Models\OrderDelivery;
use App\Models\PaymentHistory;
use App\Models\CustomerPocketHistory;
use App\Models\Notifications;
use App\Events\SendNoti;

use App\Exports\OrderExport;
use GrahamCampbell\ResultType\Result;

class ReportsController extends Controller
{
    public function orders()
    {
        return view('report.orders');
    }

    public function orderslist(Request $request)
    {
            $query = Orders::select(
                'orders.id',
                'orders.order_number as order_number',
                'orders.price_all as price_all',
                'orders.total as total',
                'orders.file as file',
                'orders.status as status',
                'orders.status_send as status_send',
                'orders.on_vat as on_vat',
                'orders.payment_type as payment_type',
                'orders.status_payment as status_payment',
                'quotation.created_at as quotation_created_at',
                DB::raw("case when orders.customer_type = 'customer' then master_customer.store_name else customer_other.store_name end AS name_store"),
                DB::raw("case when orders.customer_type = 'customer' then master_customer.customer_name else customer_other.customer_name end AS name_customer"),
                DB::raw("case when orders.customer_type = 'customer' then master_customer.customer_phone else customer_other.customer_phone end AS phone"),
                DB::raw("case when orders.customer_type = 'customer' then master_customer.text_number_vat else customer_other.text_number_vat end AS text_number_vat"),
                DB::raw("case when orders.customer_type = 'customer' then master_customer.customer_mail else customer_other.customer_mail end AS customer_mail")
                )
            ->leftJoin('master_customer', 'master_customer.id', '=', 'orders.customer_id')
            ->leftJoin('customer_other', 'customer_other.id', '=', 'orders.customer_id')
            ->leftJoin('quotation', 'quotation.id', '=', 'orders.quotation_id');
            if(Auth::user()->role_id == '2')
            {
                $query->whereBetween('orders.on_vat', '1');
            }

            if($request->date)
            {
                $cutdate = explode('-', $request->date);
                $date1 = Carbon::createFromFormat('d/m/Y', trim($cutdate[0]));
                $date1 = date('Y-m-d', strtotime($date1));
                $date2 = Carbon::createFromFormat('d/m/Y', trim($cutdate[1]));
                $date2 = date('Y-m-d', strtotime($date2));
                $date = [$date1 . " 00:00:00", $date2 . " 23:59:59"];
                $query->whereBetween('quotation.created_at', $date);
            }
            if($request->ds != '')
            {
                $query->where('orders.status_send',$request->ds);

            }
            if($request->pt != '')
            {
                $query->where('orders.payment_type',$request->pt);

            }
            if($request->ps != '')
            {
                $query->where('orders.status_payment',$request->ps);

            }
            if($request->search)
            {
                $query->where(function($q) use ($request) {
                    $q->where('orders.order_number','like','%'.$request->search.'%');
                    $q->orWhere('orders.price_all','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.store_name','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.customer_name','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.customer_phone','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.text_number_vat','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.customer_mail','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.store_name','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.customer_name','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.customer_phone','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.text_number_vat','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.customer_mail','like','%'.$request->search.'%');
                });
            }
            // ->where('master_customer.store_name','like','%เทสสอบ%')
            $datas = $query->orderby('orders.created_at','desc')->orderby('orders.status','asc')->get();
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $limt_page = 10;
            $per_page = $limt_page;
            $collection = Collect($datas, $per_page);
            $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->all();
            $data_gen = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
            $gen = $this->genhtmlOrder($data_gen, $per_page);
            $arrayData['datas'] = $gen['html'];
            $arrayData['total'] = $gen['total'];
            $arrayData['pagination'] = $gen['pagination'];
            return $arrayData;
        // }
    }
	public function payment_history() 
	{
		$query = PaymentHistory::select(
			'*',
			);
			
		$query->leftJoin('orders', 'orders.id', '=', 'payment_history.order_id');
		$query->leftJoin('master_customer', 'master_customer.id', '=', 'orders.customer_id');
		$query->where('payment_history.amount','!=',0);
		//return $query->toSql();
		return view('report.payment_history');
	}
	public function payment_history_data() 
	{
		$query = PaymentHistory::select(
			'*',
			'master_payment_method_type.name as payment_method_type_name',
			);
			
		$query->leftJoin('orders', 'orders.id', '=', 'payment_history.order_id');
		$query->leftJoin('master_payment_method_type', 'master_payment_method_type.code', '=', 'orders.payment_method_type_code');
		$query->leftJoin('order_delivery', 'orders.id', '=', 'order_delivery.order_id');
		$query->leftJoin('master_customer', 'master_customer.id', '=', 'orders.customer_id');
		$query->where('payment_history.amount','!=',0);
		$datas = $query->get();
		
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		$limt_page = 10;
		$per_page = $limt_page;
		$collection = Collect($datas, $per_page);
		$currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->all();
		$data_gen = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
		$gen = $this->genhtmlPaymentHistory($data_gen, $per_page);
		$arrayData['datas'] = $gen['html'];
		$arrayData['total'] = $gen['total'];
		$arrayData['pagination'] = $gen['pagination'];	
		
		return $arrayData;
	}
	private function genhtmlPaymentHistory($datas, $per_page)
    {
        $html = '';

        $i = $datas->currentPage();
        $i = ($i * $per_page) - $per_page;
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                $html .= '<tr>';
                $html .= '<td>' . ++$i . '</td>';
				$html .= '<td>' . $data->payment_method_type_name . '</td>';
                $html .= '<td>' . date('d/m/Y H:i:s',strtotime($data->date_playment)) . '</td>';
                $html .= '<td>' . $data->order_number . '</td>';
				$html .= '<td>' . $data->order_delivery_number . '</td>';
                $html .= '<td>' . $data->store_name . '</td>';
                $html .= '<td>' . number_format($data->amount,2) . '</td>';
                //$html .= '<td>' . PatmentType($data->payment_type) . '</td>';
                //$html .= '<td>' . statusStr($data->status_send,'delivery') . '</td>';
                //$html .= '<td>' . statusPaymentStr($data->status_payment) . '</td>';
            }
        } else {
            $html .= '<tr><td class="text-center" colspan="11">No matching records found</td></tr>';
        }

        $pagination = '';
        // if($datas->total() > 10)
        // {
        $prev = $datas->currentPage() - 1;
        $next = $datas->currentPage() + 1;
        // $pagination = '<div class="page">';
        // $pagination .= '<div class="dataTables_paginate paging_simple_numbers">';
        $pagination .= ' <ul class="pagination">';
        if ($datas->onFirstPage()) {
            $pagination .= '<li class="paginate_button page-item previous disabled"><a href="#" aria-controls="tb_position" data-dt-idx="0" tabindex="0" class="page-link previous">Previous</a></li>';
        } else {
            $pagination .= '<li  class="paginate_button page-item"><a ria-controls="tb_position" data-dt-idx="0" tabindex="0" class="page-link previous" onclick="search_page(' . $prev . ')" rel="prev">Previous</a></li>';
        }
        if ($datas->currentPage() > 2) {

            $pagination .= '<li class="paginate_button page-item"><a class="page-link" onclick="search_page(1)">1</a></li>';
        }
        // if($datas->currentPage() > 5){

        //     $pagination .= '<li class="paginate_button page-item"><a class="page-link" onclick="search_page(2)">2</a></li>';
        // }
        if ($datas->currentPage() > 3) {

            $pagination .= ' <li class="paginate_button page-item" class="page-link"><a href="vascript:void(0)" aria-controls="tb_position" data-dt-idx="1" tabindex="0" class="page-link">...</a></li>';
        }
        foreach (range(1, $datas->lastPage()) as $i) {
            if ($i >= $datas->currentPage() - 1 && $i <= $datas->currentPage() + 1) {
                if ($i == $datas->currentPage()) {
                    $pagination .= '<li class="paginate_button page-item active"><a href="vascript:void(0)" aria-controls="tb_position" data-dt-idx="1" tabindex="0" class="page-link">' . $i . '</a></li>';
                } else {

                    $pagination .= '<li class="paginate_button page-item"><a onclick="search_page(' . $i . ')" class="page-link">' . $i . '</a></li>';
                }
            }
        }
        if ($datas->currentPage() < $datas->lastPage() - 3) {

            $pagination .= '<li class="paginate_button page-item"><a href="vascript:void(0)" aria-controls="tb_position" data-dt-idx="1" tabindex="0" class="page-link">...</a></li>';
        }
        if ($datas->currentPage() < $datas->lastPage() - 2) {
            $pagination .= '<li class="paginate_button page-item"><a onclick="search_page(' . $datas->lastPage() . ')" class="page-link" >' . $datas->lastPage() . '</a></li>';
        }

        if ($datas->hasMorePages()) {

            $pagination .= '<li class="paginate_button page-item last"><a aria-controls="tb_position" data-dt-idx="2" tabindex="0" class="page-link next" onclick="search_page(' . $next . ')"  rel="next">Next</a></li>';
        } else {
            // $pagination .= '<li class="paginate_button page-item next disabled"><span class="page-link last">Next</span></li>';
            $pagination .= '<li class="paginate_button page-item next disabled"><a href="vascript:void(0)" aria-controls="tb_position" data-dt-idx="2" tabindex="0" class="page-link next">Next</a></li>';
        }
        $pagination .= '</ul>';
        // $pagination .= '</div>';
        // $pagination .= '</div>';
        // }
        $data['html'] = $html;
        $data['pagination'] = $pagination;
        $data['total'] = 'Showing ' . $datas->currentPage() . ' to ' . $datas->lastPage() . ' of ' . $datas->total() . ' entries';
        return $data;
    }
	
    private function genhtmlOrder($datas, $per_page)
    {
        $html = '';

        $i = $datas->currentPage();
        $i = ($i * $per_page) - $per_page;
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                $html .= '<tr>';
                $html .= '<td>' . ++$i . '</td>';
                $html .= '<td>' . date('d/m/Y H:i:s',strtotime($data->quotation_created_at)) . '</td>';
                $html .= '<td>' . $data->order_number . '</td>';
                $html .= '<td>' . $data->name_store . '</td>';
                $html .= '<td>' . $data->text_number_vat . '</td>';
                $html .= '<td>' . $data->name_customer . '</td>';
                $html .= '<td>' . $data->phone . '</td>';
                $html .= '<td>' . $data->customer_mail . '</td>';
                $html .= '<td>' . number_format($data->total,2) . '</td>';
                $html .= '<td>' . PatmentType($data->payment_type) . '</td>';
                $html .= '<td>' . statusStr($data->status_send,'delivery') . '</td>';
                $html .= '<td>' . statusPaymentStr($data->status_payment) . '</td>';
            }
        } else {
            $html .= '<tr><td class="text-center" colspan="11">No matching records found</td></tr>';
        }

        $pagination = '';
        // if($datas->total() > 10)
        // {
        $prev = $datas->currentPage() - 1;
        $next = $datas->currentPage() + 1;
        // $pagination = '<div class="page">';
        // $pagination .= '<div class="dataTables_paginate paging_simple_numbers">';
        $pagination .= ' <ul class="pagination">';
        if ($datas->onFirstPage()) {
            $pagination .= '<li class="paginate_button page-item previous disabled"><a href="#" aria-controls="tb_position" data-dt-idx="0" tabindex="0" class="page-link previous">Previous</a></li>';
        } else {
            $pagination .= '<li  class="paginate_button page-item"><a ria-controls="tb_position" data-dt-idx="0" tabindex="0" class="page-link previous" onclick="search_page(' . $prev . ')" rel="prev">Previous</a></li>';
        }
        if ($datas->currentPage() > 2) {

            $pagination .= '<li class="paginate_button page-item"><a class="page-link" onclick="search_page(1)">1</a></li>';
        }
        // if($datas->currentPage() > 5){

        //     $pagination .= '<li class="paginate_button page-item"><a class="page-link" onclick="search_page(2)">2</a></li>';
        // }
        if ($datas->currentPage() > 3) {

            $pagination .= ' <li class="paginate_button page-item" class="page-link"><a href="vascript:void(0)" aria-controls="tb_position" data-dt-idx="1" tabindex="0" class="page-link">...</a></li>';
        }
        foreach (range(1, $datas->lastPage()) as $i) {
            if ($i >= $datas->currentPage() - 1 && $i <= $datas->currentPage() + 1) {
                if ($i == $datas->currentPage()) {
                    $pagination .= '<li class="paginate_button page-item active"><a href="vascript:void(0)" aria-controls="tb_position" data-dt-idx="1" tabindex="0" class="page-link">' . $i . '</a></li>';
                } else {

                    $pagination .= '<li class="paginate_button page-item"><a onclick="search_page(' . $i . ')" class="page-link">' . $i . '</a></li>';
                }
            }
        }
        if ($datas->currentPage() < $datas->lastPage() - 3) {

            $pagination .= '<li class="paginate_button page-item"><a href="vascript:void(0)" aria-controls="tb_position" data-dt-idx="1" tabindex="0" class="page-link">...</a></li>';
        }
        if ($datas->currentPage() < $datas->lastPage() - 2) {
            $pagination .= '<li class="paginate_button page-item"><a onclick="search_page(' . $datas->lastPage() . ')" class="page-link" >' . $datas->lastPage() . '</a></li>';
        }

        if ($datas->hasMorePages()) {

            $pagination .= '<li class="paginate_button page-item last"><a aria-controls="tb_position" data-dt-idx="2" tabindex="0" class="page-link next" onclick="search_page(' . $next . ')"  rel="next">Next</a></li>';
        } else {
            // $pagination .= '<li class="paginate_button page-item next disabled"><span class="page-link last">Next</span></li>';
            $pagination .= '<li class="paginate_button page-item next disabled"><a href="vascript:void(0)" aria-controls="tb_position" data-dt-idx="2" tabindex="0" class="page-link next">Next</a></li>';
        }
        $pagination .= '</ul>';
        // $pagination .= '</div>';
        // $pagination .= '</div>';
        // }
        $data['html'] = $html;
        $data['pagination'] = $pagination;
        $data['total'] = 'Showing ' . $datas->currentPage() . ' to ' . $datas->lastPage() . ' of ' . $datas->total() . ' entries';
        return $data;
    }

    public function exportorder(Request $request)
    {
        $query = Orders::select(
            'orders.id',
            'orders.order_number as order_number',
            'orders.price_all as price_all',
            'orders.total as total',
            'orders.file as file',
            'orders.status as status',
            'orders.status_send as status_send',
            'orders.on_vat as on_vat',
            'orders.payment_type as payment_type',
            'orders.status_payment as status_payment',
            'quotation.created_at as quotation_created_at',
            DB::raw("case when orders.customer_type = 'customer' then master_customer.store_name else customer_other.store_name end AS name_store"),
            DB::raw("case when orders.customer_type = 'customer' then master_customer.customer_name else customer_other.customer_name end AS name_customer"),
            DB::raw("case when orders.customer_type = 'customer' then master_customer.customer_phone else customer_other.customer_phone end AS phone"),
            DB::raw("case when orders.customer_type = 'customer' then master_customer.text_number_vat else customer_other.text_number_vat end AS text_number_vat"),
            DB::raw("case when orders.customer_type = 'customer' then master_customer.customer_mail else customer_other.customer_mail end AS customer_mail")
            )
        ->leftJoin('master_customer', 'master_customer.id', '=', 'orders.customer_id')
        ->leftJoin('customer_other', 'customer_other.id', '=', 'orders.customer_id')
        ->leftJoin('quotation', 'quotation.id', '=', 'orders.quotation_id');
        if(Auth::user()->role_id == '2')
        {
            $query->whereBetween('orders.on_vat', '1');
        }

        if($request->dateexport)
        {
            $cutdate = explode('-', $request->dateexport);
            $date1 = Carbon::createFromFormat('d/m/Y', trim($cutdate[0]));
            $date1 = date('Y-m-d', strtotime($date1));
            $date2 = Carbon::createFromFormat('d/m/Y', trim($cutdate[1]));
            $date2 = date('Y-m-d', strtotime($date2));
            $date = [$date1 . " 00:00:00", $date2 . " 23:59:59"];
            $query->whereBetween('quotation.created_at', $date);
        }
        if($request->dsexport != '')
        {
            $query->where('orders.status_send',$request->dsexport);

        }
        if($request->ptexport != '')
        {
            $query->where('orders.payment_type',$request->ptexport);

        }
        if($request->psexport != '')
        {
            $query->where('orders.status_payment',$request->psexport);

        }
        if($request->searchexport)
        {
            $query->where(function($q) use ($request) {
                $q->where('orders.order_number','like','%'.$request->searchexport.'%');
                $q->orWhere('orders.price_all','like','%'.$request->searchexport.'%');
                $q->orWhere('master_customer.store_name','like','%'.$request->searchexport.'%');
                $q->orWhere('master_customer.customer_name','like','%'.$request->searchexport.'%');
                $q->orWhere('master_customer.customer_phone','like','%'.$request->searchexport.'%');
                $q->orWhere('master_customer.text_number_vat','like','%'.$request->searchexport.'%');
                $q->orWhere('master_customer.customer_mail','like','%'.$request->searchexport.'%');
                $q->orWhere('customer_other.store_name','like','%'.$request->searchexport.'%');
                $q->orWhere('customer_other.customer_name','like','%'.$request->searchexport.'%');
                $q->orWhere('customer_other.customer_phone','like','%'.$request->searchexport.'%');
                $q->orWhere('customer_other.text_number_vat','like','%'.$request->searchexport.'%');
                $q->orWhere('customer_other.customer_mail','like','%'.$request->searchexport.'%');
            });
        }
        // ->where('master_customer.store_name','like','%เทสสอบ%')
        $arrayData = $query->orderby('orders.created_at','desc')->orderby('orders.status','asc')->get();
        return Excel::download(new OrderExport("exports.orders", $arrayData), 'report_orders_' . date('d-m-Y') . '_.xlsx');
    }



}
