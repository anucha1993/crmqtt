<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Models\CustomerPocketHistory;
use App\Models\PaymentMethodPocketMoney;
use Illuminate\Pagination\LengthAwarePaginator;


class PaymentMethodPocketMoneyController extends Controller
{
    //
	public function update_pocket_money_amount(Request $request)
    {
		PaymentMethodPocketMoney::where('order_id',$request->id)->update(['amount'=>$request->amount]);
		
        return NULL;
    }


    public function updatePocketMoney(Request $request, CustomerPocketHistory $CustomerPocketHistory)
    {
        $CustomerPocketHistory->update([
            'pocket_money' => $request->recieve_pocket_new,
            'updated_at' => date(now())
        ]);

        return redirect()->back();
    }
	
	public function add_payment_method_pocket_money(Request $request) {
		
		$arrayAddPocket['order_id'] = $request->id;
		$arrayAddPocket['amount'] = $request->amount;
		//$arrayAddPocket['created_at'] = Carbon::now();
		//$arrayAddPocket['created_by'] = Auth::user()->id;
		$arrayAddPocket['updated_at'] = NULL;
		$arrayAddPocket['updated_by'] = NULL;
		
		PaymentMethodPocketMoney::create($arrayAddPocket);

		return NULL;
	}
	
	public function pocket_money(Request $request) {
		return view('payment_method.pocket_money');
	}
	
	public function pocket_money_data() 
	{
		$query = PaymentMethodPocketMoney::select(
			'*',
			);
			
		$query->where('payment_method_pocket_money.amount','!=',0);
		
		$datas = $query->get();
		
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		$limt_page = 10;
		$per_page = $limt_page;
		$collection = Collect($datas, $per_page);
		$currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->all();
		$data_gen = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
		$gen = $this->genhtmlPocketMoney($data_gen, $per_page);
		$arrayData['datas'] = $gen['html'];
		$arrayData['total'] = $gen['total'];
		$arrayData['pagination'] = $gen['pagination'];	
		
		return $arrayData;
	}
	
	public function re_assign_pocket_money() 
	{
		return view('payment_method.re_assign_pocket_money');
	}
	
	private function genhtmlPocketMoney($datas, $per_page)
    {
        $html = '';

        $i = $datas->currentPage();
        $i = ($i * $per_page) - $per_page;
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                $html .= '<tr>';
                $html .= '<td>' . ++$i . '</td>';
				$html .= '<td>' . $data->amount . '</td>';
                $html .= '<td>' . date('d/m/Y H:i:s',strtotime($data->date_playment)) . '</td>';
                $html .= '<td>' . $data->order_id . '</td>';
				$html .= '<td>' . $data->order_delivery_number . '</td>';
                $html .= '<td>' . $data->store_name . '</td>';
                $html .= '<td>' . '<button onclick="javascript:open_to_select_product('.$data->order_id.','.$data->amount.');">click</button>' . '</td>';
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
}
