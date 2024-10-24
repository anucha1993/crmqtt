<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Auth;
use Carbon\Carbon;
use Validator;

use App\Models\Quotation;
use App\Models\Products;
use App\Models\Orders;
use App\Models\MasterCustomer;
use App\Models\CustomerOther;
use App\Models\OrderItmes;
use App\Models\OrderDelivery;
use App\Models\PaymentHistory;
use App\Models\CustomerPocketHistory;
use App\Models\DeliveryLocation;
use App\Models\Notifications;
use App\Events\SendNoti;

class OrdersController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }
	
	public function barcode_ajax(Request $request)
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
                'orders.status_payment as status_payment',
        );
		$query->where(function($q) use ($request) {
				$q->where('orders.order_number','=',$request->id);
		});
		$datas = $query->get();
		$total_price = 0;
		foreach ($datas as $data) {
			$total_price = $data->price_all;
		}
        #return $request->id;
		return $total_price;
    }
	
	public function barcode_sub_ajax(Request $request)
    {
		$datas_pera = OrderDelivery::select(
            'order_delivery.created_at',
            'order_delivery.order_delivery_number',
            DB::raw("case when orders.customer_type = 'customer' then customer_pocket_history.pocket_present else '0.00' end AS pocket_present"),
            'order_delivery.total',
            'order_delivery.money_deposit',
            DB::raw("case when orders.customer_type = 'customer' then customer_pocket_history.pocket_money else '0.00' end AS pocket_money"),
            'payment_history.status as status_payment',
            'payment_history.date_playment as date_playment',
            'order_delivery.file as file',
            'order_delivery.date_send',
            'order_delivery.status as status_delivery',
            'order_delivery.order_delivery_id as order_delivery_id',
            'orders.customer_type'
            )
        ->leftjoin('orders','orders.id','=','order_delivery.order_id')
        ->leftjoin('customer_pocket_history as customer_pocket_history','customer_pocket_history.order_delivery_id','=','order_delivery.order_delivery_id')
        ->leftjoin('payment_history','payment_history.order_delivery_id','=','order_delivery.order_delivery_id')
        ->where('order_delivery.order_delivery_number',"S001")
		->where('order_delivery.order_id',60)
		->orderby('order_delivery.created_at','asc')->get();
		
		$query = Orders::select(
                'orders.id',
                'orders.order_number as order_number',
                'orders.price_all as price_all',
                'orders.total as total',
                'orders.file as file',
                'orders.status as status',
                'orders.status_send as status_send',
                'orders.on_vat as on_vat',
                'orders.status_payment as status_payment',
        );
		$query->where(function($q) use ($request) {
				$q->where('orders.order_number','=',$request->id);
		});
		$datas = $query->get();
		$total_price = 0;
		$total_sub_price = 0;
		foreach ($datas as $data) {
			$total_price = $data->price_all;
		}
		foreach ($datas_pera as $data) {
			$total_sub_price = $data->total;
		}
        #return $request->id;
		return $total_sub_price;
    }

    public function list(Request $request)
    {
        // if($request->alax())
        // {
            $query = Orders::select(
                'orders.id',
                'orders.order_number as order_number',
                'orders.price_all as price_all',
                'orders.total as total',
                'orders.file as file',
                'orders.status as status',
                'orders.status_send as status_send',
                'orders.on_vat as on_vat',
                'orders.status_payment as status_payment',
                'customer_other.customer_address',
                DB::raw("case when orders.customer_type = 'customer' then master_customer.store_name else customer_other.store_name end AS name_store"),
                DB::raw("case when orders.customer_type = 'customer' then master_customer.customer_name else customer_other.customer_name end AS name_customer"),
                DB::raw("case when orders.customer_type = 'customer' then master_customer.customer_phone else customer_other.customer_phone end AS phone"),
                // DB::raw("IF(sum(payment_history.total) = orders.total,1,0) AS check_payment") 
                // DB::raw("SUM(payment_history.total)  AS check_payment")
                )
            ->leftJoin('master_customer', 'master_customer.id', '=', 'orders.customer_id')
            ->leftJoin('customer_other', 'customer_other.id', '=', 'orders.customer_id')
            ->leftJoin('payment_history','orders.id','=','payment_history.order_id');

            if(Auth::user()->role_id == '2')
            {
                $query->where('orders.on_vat', '1');
            }

            if($request->s)
            {
                $query->where('orders.status',$request->s);
            }
            if($request->ds)
            {
                $query->where('orders.status_send',$request->s);

            }
            if($request->search)
            {
                $query->where(function($q) use ($request) {
                    $q->where('orders.order_number','like','%'.$request->search.'%');
                    $q->orWhere('orders.price_all','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.store_name','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.customer_name','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.customer_phone','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.store_name','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.customer_name','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.customer_phone','like','%'.$request->search.'%');
                });
            }
            // ->where('master_customer.store_name','like','%เทสสอบ%')
            $datas = $query->orderby('orders.created_at','desc')->orderby('orders.status','asc')->groupBy('orders.id')->get();

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $limt_page = 10;
            $per_page = $limt_page;
            $collection = Collect($datas, $per_page);
            $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->all();
            $data_gen = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
            $gen = $this->genhtml($data_gen, $per_page);
            $arrayData['datas'] = $gen['html'];
            $arrayData['total'] = $gen['total'];
            $arrayData['pagination'] = $gen['pagination'];
            return $arrayData;
        // }
    }

    private function genhtml($datas, $per_page)
    {
        $html = '';

        $i = $datas->currentPage();
        $i = ($i * $per_page) - $per_page;
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                $html .= '<tr>';
                $html .= '<td>' . ++$i . '</td>';
                $html .= '<td>' . $data->order_number . '</td>';
                $html .= '<td>' . $data->name_store . '</td>';
                $html .= '<td>' . $data->name_customer . '</td>';
                $html .= '<td>' . $data->phone . '</td>';
                $html .= '<td>' . $data->customer_address . '</td>';
                $html .= '<td>' . number_format($data->total,2) . '</td>';
                $html .= '<td class="text-center">' . ($data->file ? '<a href="'.url('storage/delivery/'.$data->file).'"><i class="fa fa-paperclip" aria-hidden="true"></i></a>' : '') . '</td>';
                if ($data->status == '0') {
                    $html .= '<td><span class="dot wait"></span>รอยืนยัน</td>';
                } else if ($data->status == '1') {
                    $html .= '<td><span class="dot approve"></span>ยืนยัน</td>';
                } else if ($data->status == '2') {
                    $html .= '<td><span class="dot close"></span>ยกเลิก</td>';
                }
                if ($data->status_send == '0') {
                    $html .= '<td><span class="dot wait"></span>กำลังดำเนินการ</td>';
                } else if ($data->status_send == '1') {
                    $html .= '<td><span class="dot approve"></span>จัดส่งสำเร็จ</td>';
                } else if ($data->status_send == '2') {
                    $html .= '<td><span class="dot close"></span>ยกเลิก</td>';
                }
                $html .= '<td>'.statusPaymentStr($data->status_payment).'</td>';


                // $html .= '<td>' . ((($data->on_vat == '1' && $data->status == '1') && $data->status_payment != '0') ? '<a href="#"><i class="mdi mdi-printer"></i> Print ใบกำกับภาษี</a>' : '') . '</td>';
                $html .= '<td style="text-align: center;">
                            <div class="dropdown">
                                <button type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-outline-secondary dropdown-toggle">
                                     จัดการ
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a class="dropdown-item '.' " href="'.url('orders/view/'.$data->id).'">หน้าบิลหลัก</a>';
                                if($data->status == 1)
                                {
                                    $html .= '<a class="dropdown-item" href="'.url('orders/delivery/list/'.$data->id).'">รายการบิลย่อย</a>
                                            <a class="dropdown-item" href="'.url('payments/order/'.$data->id).'">ประวัติการชำระ</a>';
                                }
                        $html .= '</div>
                            </div>
                        </td>';
                $html .= '</tr>';
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

    public function add($id)
    {
        $quotation =  Quotation::find($id);
        $last_order = Orders::select('id')->orderBy('id', 'desc')->first();

        $datas = OrderItmes::select('order_itmes.*','product_type.product_type_name as type_name','product_size_name as size_name')
        ->join('product_type','product_type.id','=','order_itmes.product_type_id')
        ->join('product_size','product_size.id','=','order_itmes.product_size_id')
        ->where('order_itmes.quotation_id',$id)->get();

        //check customer
        if($quotation->customer_type == 'customer')
        {
            $customer = MasterCustomer::select('master_customer.*','province.province_name','amphoe.amphoe_name','district.district_name')
            ->leftjoin('master_province as province','province.province_code','=','master_customer.province_code')
            ->leftjoin('master_amphoe as amphoe','amphoe.amphoe_code','=','master_customer.amphoe_code')
            ->leftjoin('master_district as district','district.district_code','=','master_customer.district_code')
            ->where('master_customer.id',$quotation->customer_id)->with('PocketMoney')->first();
            $pocketmoney = !empty($customer->PocketMoney) ? $customer->PocketMoney->pocket_money : '0.00';
        }else{
            $customer = CustomerOther::where('id',$quotation->customer_id)->first();
            $pocketmoney = '0.00';
        }
        $breadcrumb = [['url'=>'quotation','title'=>'ใบเสนอราคา'],['url'=>'quotation/view/'.$quotation->id,'title'=>'ใบเสนอราคา '.$quotation->quotation_number],['url'=>'','title'=>'สร้างบิลหลัก']];
        $i = 1;

        return view('orders.add',compact('breadcrumb','quotation','customer','datas','i','pocketmoney'));
    }

    public function save(Request $request)
    {
        // dd($request->all());
        $quotation =  Quotation::find($request->quotationid);

        $last_order = Orders::select('id')->orderBy('id', 'desc')->first();
        $order_number = (!empty($last_order)) ? $last_order->id + 1 : 1;
        $order_number = str_pad($order_number, 6, '0', STR_PAD_LEFT);
        $arrayAddOrder['quotation_id'] = $request->quotationid;
        $arrayAddOrder['customer_type'] = $quotation->customer_type;
        $arrayAddOrder['customer_id'] = $quotation->customer_id;
        $arrayAddOrder['order_number'] = 'P'.$order_number;
        $arrayAddOrder['status'] = (isset($request->status) ? $request->status : 0);
        $arrayAddOrder['status_send'] = 0;
        $arrayAddOrder['on_vat'] = ($request->on_vat ? $request->on_vat :(Auth::user()->role_id == 2 ? 1 :0));
        $arrayAddOrder['vat'] = $request->vat;
        $arrayAddOrder['price_all'] = $request->total_items_all;
        $arrayAddOrder['total'] = $request->total_all;
        $arrayAddOrder['note'] = $request->note;
        $arrayAddOrder['payment_type'] = $request->payment_type;

        if($request->hasFile('file'))
        {
            $attach = array('file' => $request->file('file'));
            $rules = array('file' => 'mimes:pdf,png,jpg'); //mimes:jpeg,bmp,
            $validateAttach = Validator::make($attach, $rules);
            if ($validateAttach->fails()) {
                return response()->json(['mgs' => 'ไม่รองรับไฟล์นี้', 'status' => 'error'], 200);
            }
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $filename = time() .$arrayAddOrder['order_number']. '.' . $ext;
            $file->storeAs('public/delivery/', $filename);
            $arrayAddOrder['file'] = $filename;;
        }

        $arrayAddOrder['created_at'] = Carbon::now();
        $arrayAddOrder['created_by'] = Auth::user()->id;
		/**
		$arrayAddOrder['remark_1'] = $request->remark_1;
		$arrayAddOrder['remark_2'] = $request->remark_2;
		$arrayAddOrder['remark_3'] = $request->remark_3;
		$arrayAddOrder['remark_4'] = $request->remark_4;
		$arrayAddOrder['remark_5'] = $request->remark_5;
		**/
        $newOrder =  Orders::create($arrayAddOrder);

        OrderItmes::where('quotation_id',$request->quotationid)->update(['order_id'=>$newOrder->id]);

        if($request->payment_type == 1)
        {
            $arrayAddPayment['order_id'] = $newOrder->id;
            $arrayAddPayment['payment_type'] = $request->payment_type;
            $arrayAddPayment['status'] = ($newOrder->status == 1 ? 1 : 0);
            $arrayAddPayment['total'] = str_replace(",","",$request->payment);
            $arrayAddPayment['date_playment'] = null;
            $arrayAddPayment['file'] = $newOrder->file;
            $arrayAddPayment['created_at'] = Carbon::now();
            $arrayAddPayment['created_by'] = Auth::user()->id;
            PaymentHistory::create($arrayAddPayment);
        }

        //noti order created
        $noti = new Notifications;
        $noti->item_id = $newOrder->id;
        $noti->noti_type = 2;
        $noti->items_status = 0;
        $noti->read = 0;
        $noti->noti_status = 0;
        $noti->on_vat = $newOrder->on_vat;
        $noti->created_by = Auth::user()->id;
        $noti->created_at = Carbon::now();
        $noti->save();
        event(new SendNoti());
        $linemessage = 'สร้างบิลหลักเลขที่ '.$newOrder->order_number.' ยอดรวมสั่งซื้อ '.number_format($newOrder->total,2).' วันที่สร้าง '.date('d/m/Y H:i:s',strtotime($newOrder->created_at));
        SendNotiline($linemessage);

        //noti order created สถานะอนุมัติ
        if($request->status == 1)
        {
            $noti = new Notifications;
            $noti->item_id = $newOrder->id;
            $noti->noti_type = 2;
            $noti->items_status = 1;
            $noti->read = 0;
            $noti->noti_status = 0;
            $noti->on_vat = $newOrder->on_vat;
            $noti->created_by = Auth::user()->id;
            $noti->created_at = Carbon::now();
            $noti->save();
            event(new SendNoti());
            $linemessage = 'อนุมัติบิลหลักเลขที่ '.$newOrder->order_number.' ยอดรวมสั่งซื้อ '.number_format($newOrder->total,2).' วันที่อนุมัติ '.date('d/m/Y H:i:s',strtotime($newOrder->created_at));
            SendNotiline($linemessage);
        }


        return redirect()->to('orders');
    }
	
	public function assign_pocket_money() 
	{
		return view('orders.assign_pocket_money');
	}
	
    public function view($id)
    {
		$check_if_already_added_for_payment_history_of_OTP =  PaymentHistory::select('*')
				->join('orders','payment_history.order_id','=','orders.id')
				->where('orders.id',$id)
				->first();
				
		$check_if_already_updated_for_payment_method_of_order =  Orders::select('*')
				->where('orders.payment_method_type_code',0)
				->where('orders.id',$id)
				->first();
				
		$check_if_not_a_payment_method_of_OTP =  Orders::select('*')
				->where('orders.payment_method_type_code','<>',1)
				->where('orders.id',$id)
				->first();
				
		$check_if_already_added_for_payment_history_of_OTP_flag = "No";	
		
		if (
				($check_if_already_added_for_payment_history_of_OTP != NULL) 
				or ($check_if_already_updated_for_payment_method_of_order != NULL)
				or ($check_if_not_a_payment_method_of_OTP != NULL)
		   ) {
			$check_if_already_added_for_payment_history_of_OTP_flag = "Yes";
		}
		
        $quotation =  Quotation::select('*')
				->join('orders','orders.quotation_id','=','quotation.id')
				->where('orders.id',$id)
				->first();
		
		$orders = Orders::find($id);
				
		$datas = OrderItmes::select('order_itmes.*','product_type.product_type_name as type_name','product_size_name as size_name')
			->join('product_type','product_type.id','=','order_itmes.product_type_id')
			->join('product_size','product_size.id','=','order_itmes.product_size_id')
			->where('order_itmes.order_id',$id)
			->get();
		
		#return $datas;
		#/**
		$datas_sub_total = OrderItmes::select('order_itmes.*','products.weight as products_weight')
        ->join('products','products.id','=','order_itmes.product_id')
        ->where('order_itmes.order_id',$id)
		->sum(\DB::raw('products.weight * order_itmes.count_unit'));
		#**/
		
		#$datas_sub_total = 10;
		$orders_pera = "";
		$orders_pera_sql = "";
		
		if ($orders->delivery_location_id == 0) {
			$location_name = "ยังไม่มีที่จัดส่ง";
			$location_contact_person_name = "ยังไม่มีข้อมูล";
			$location_contact_person_phone_no = "ยังไม่มีข้อมูล";
		} else {
			$orders_pera = Orders::select('*','master_delivery_location.location as pera_delivery_location_name')
				->join('master_delivery_location','master_delivery_location.id','=','orders.delivery_location_id')
				->where('orders.id',$id)->first();
			$sql_99 = Orders::select('*','master_delivery_location.location as pera_delivery_location_name')
				->join('master_delivery_location','master_delivery_location.id','=','orders.delivery_location_id')
				->where('orders.id',$id)->toSql();
			#die($sql_99);
			$location_name = $orders_pera->pera_delivery_location_name;
			$location_contact_person_name = $orders_pera->onsite_contact_name;
			$location_contact_person_phone_no = $orders_pera->onsite_contact_phone_no;
		}
        $delivery_location = DeliveryLocation::select('*')
			->where('master_delivery_location.order_id',$orders->id)
			->where('master_delivery_location.customer_id',$orders->customer_id)->get();
        
        foreach($datas as $data)
        {
            $data->item_send = collect(OrderDelivery::select('order_delivery_items.qty')->join('order_delivery_items','order_delivery_items.order_delivery_id','order_delivery.order_delivery_id')->where('order_delivery.status',1)->where('order_delivery_items.order_items_id',$data->id)->get())->sum('qty');
            // $query = OrderDelivery::where('order_id',$data->order_id)->
        }
        //check customer
        if($orders->customer_type == 'customer')
        {
            $customer = MasterCustomer::select('master_customer.*','province.province_name','amphoe.amphoe_name','district.district_name')
            ->leftjoin('master_province as province','province.province_code','=','master_customer.province_code')
            ->leftjoin('master_amphoe as amphoe','amphoe.amphoe_code','=','master_customer.amphoe_code')
            ->leftjoin('master_district as district','district.district_code','=','master_customer.district_code')
            ->where('master_customer.id',$orders->customer_id)->with('PocketMoney')->first();
            $pocketmoney = !empty($customer->PocketMoney) ? $customer->PocketMoney->pocket_money : '0.00';
        }else{
            $customer = CustomerOther::where('id',$orders->customer_id)->first();
            $pocketmoney =  '0.00';
        }
        $breadcrumb = [['url'=>'orders','title'=>'รายการบิลหลัก'],['url'=>'','title'=>'รายการบิลหลัก '.$orders->order_number]];
        $i = 1;
        $historypayment = PaymentHistory::where('order_id',$id)->first();
        $sumpayment = PaymentHistory::where('order_id',$id)->get()->sum('total');

        $paymentType = DB::table('payment_type')->get();

        return view('orders.view',compact('paymentType','quotation','check_if_already_added_for_payment_history_of_OTP_flag','breadcrumb','location_contact_person_name','location_contact_person_phone_no','orders','customer','datas','datas_sub_total','delivery_location','i','pocketmoney','historypayment','sumpayment','orders_pera','orders_pera_sql','location_name'));
    }

	public function update_payment_method_type_code(Request $request)
    { 	
		// if existing order payment method type code is 3, delete before update new code that is diff from code => 3//
		$sql = "SELECT payment_method_type_code FROM `orders` WHERE id = $request->id";
		$result = DB::select($sql);
		
		if ($result[0]->payment_method_type_code == '3') {
			$sql = "delete FROM payment_method_pocket_money WHERE order_id = $request->id";
			$result = DB::statement($sql);
		}
		
		$orders = Orders::find($request->id);
		$orders->payment_method_type_code = $request->payment_method_type_code;
		$orders->save();
        return redirect()->to('orders');;
    }

    public function update(Request $request)
    { 
		$orders = Orders::find($request->orderid);
		
		$orders->remark_1 = $request->remark_1;
		$orders->remark_2 = $request->remark_2;
		$orders->remark_3 = $request->remark_3;
		$orders->remark_4 = $request->remark_4;
		$orders->remark_5 = $request->remark_5;
		if(!empty($request->render_price) and $request->render_price = "No"){
			$orders->render_price = "No";
			$orders->save();
			#die($request->orderid."::".$request->render_price);
		} 
		if(!empty($request->render_price) and $request->render_price = "Yes") {
			$orders->render_price = "Yes";
			$orders->save();
		}
		if ($request->change_delivery_location_check_box == "yes") {
			if ($request->new_delivery_location_check_box == "yes") {
				$arrayAddDeliveryLocation['customer_id'] = $orders->customer_id;
				$arrayAddDeliveryLocation['order_id'] = $request->orderid;
				$arrayAddDeliveryLocation['order_delivery_id'] = 0;
				$arrayAddDeliveryLocation['location'] = $request->delivery_location;
				$arrayAddDeliveryLocation['onsite_contact_name'] = $request->new_delivery_location_contact_person_name;
				$arrayAddDeliveryLocation['onsite_contact_phone_no'] = $request->new_delivery_location_contact_person_phone_no;

				$id_pera = DeliveryLocation::create($arrayAddDeliveryLocation)->id;
				
				$orders_pera = Orders::find($request->orderid);
				$orders_pera->delivery_location_id = $id_pera;
				$orders_pera->save();
			} else {
				$orders_pera = Orders::find($request->orderid);
				$orders_pera->delivery_location_id = $request->existing_delivery_location_id;
				$orders_pera->save();
			}
        }
        if($request->hasFile('file'))
        {
            $attach = array('file' => $request->file('file'));
            $rules = array('file' => 'mimes:pdf,png,jpg'); //mimes:jpeg,bmp,
            $validateAttach = Validator::make($attach, $rules);
            if ($validateAttach->fails()) {
                return response()->json(['mgs' => 'ไม่รองรับไฟล์นี้', 'status' => 'error'], 200);
            }
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $filename = time() .$orders->order_number. '.' . $ext;
            $file->storeAs('public/delivery/', $filename);
            $orders->file = $filename;
            $orders->save();
        }
        if($orders->payment_type == null)
        {
            $orders->payment_type = $request->payment_type;
            if($request->payment_type == 1)
            {
                $arrayAddPayment['order_id'] = $orders->id;
                $arrayAddPayment['payment_type'] = $request->payment_type;
                $arrayAddPayment['status'] = ($orders->status == 1 ? 1 : 0);
                $arrayAddPayment['total'] = str_replace(",","",$request->payment);
                $arrayAddPayment['date_playment'] = null;
                $arrayAddPayment['file'] = $orders->file;
                $arrayAddPayment['created_at'] = Carbon::now();
                $arrayAddPayment['created_by'] = Auth::user()->id;
                PaymentHistory::create($arrayAddPayment);
            }
            $orders->save();
        }
        if($orders->status != 1)
        {
            $orders->status = $request->status;
            $orders->updated_by = Auth::user()->id;
            $orders->updated_at = Carbon::now();
            $orders->save();

            if($orders->payment_type == 1 && $orders->status == 1)
            {
                $payment = PaymentHistory::where('order_id',$orders->id)->whereNull('order_delivery_id')->first();
                $payment->status = 1;
                $payment->updated_by = Auth::user()->id;
                $payment->updated_at = Carbon::now();
                $payment->save();
            }
            //noti order created สถานะอนุมัติ

            if($orders->status == 1)
            {
                $noti = new Notifications;
                $noti->item_id = $orders->id;
                $noti->noti_type = 2;
                $noti->items_status = 1;
                $noti->read = 0;
                $noti->noti_status = 0;
                $noti->on_vat = $orders->on_vat;
                $noti->created_by = Auth::user()->id;
                $noti->created_at = Carbon::now();
                $noti->save();
                event(new SendNoti());
                $linemessage = 'อนุมัติบิลหลักเลขที่ '.$orders->order_number.' ยอดรวมสั่งซื้อ '.number_format($orders->total,2).' วันที่อนุมัติ '.date('d/m/Y H:i:s',strtotime($orders->created_at));
                SendNotiline($linemessage);
            }
        }

        return redirect()->to('orders');
    }

    public function payment($id)
    {
        $orders = Orders::find($id);
        $datas = PaymentHistory::select(
            'orders.order_number',
            'order_delivery.order_delivery_number',
            'payment_history.order_delivery_id',
            'payment_history.order_id',
            'payment_history.date_playment',
            'payment_history.total',
            'payment_history.status',
            'payment_history.file',
            'payment_history.created_at',
            DB::raw("case when payment_history.order_delivery_id IS NULL then 1 else 0 end AS `check`")
        )
        ->leftJoin('orders', 'orders.id', '=', 'payment_history.order_id')
        ->leftJoin('order_delivery', 'order_delivery.order_delivery_id', '=', 'payment_history.order_delivery_id')
        ->where('payment_history.order_id', $id)
        ->orderBy('created_at', 'desc')
        ->get();
    
        $breadcrumb = [
            ['url' => 'orders', 'title' => 'รายการบิลหลัก'],
            ['url' => 'orders/view/' . $orders->id, 'title' => 'รายการบิลหลัก ' . $orders->order_number],
            ['url' => '', 'title' => 'ประวัติการชำระ']
        ];
    
        $i = 1;
        return view('orders.payment', compact('breadcrumb', 'orders', 'datas'));
    }

    public function updatequotation(Request $request)
    {
        $orders = Orders::where('quotation_id',$request->quotationid)->first();
        $quotation =  Quotation::find($request->quotationid);
        if($request->status == 2)
        {
            if(!empty($orders))
            {
                $orders->status = $request->status;
                $orders->save();
            }
        }
        $quotation->status = $request->status;
        $quotation->note = $request->note;
        $quotation->save();
        return redirect()->to('orders/add/'.$request->quotationid);

    }

}
