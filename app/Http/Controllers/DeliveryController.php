<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Validator;
use PDF;
use App\Models\Form;
use App\Models\Quotation;
use App\Models\Orders;
use App\Models\MasterCustomer;
use App\Models\CustomerOther;
use App\Models\OrderItmes;
use App\Models\OrderDelivery;
use App\Models\OrderDeliveryItems;
use App\Models\PaymentHistory;
use App\Models\CustomerPocketHistory;
use App\Models\DeliveryLocation;
use App\Models\PaymentMethodPocketMoney;
use App\Models\printLogModel;

class DeliveryController extends Controller
{
    public function list($id)
    {
        $datas = OrderDelivery::select(
            'order_delivery.created_at',
            'order_delivery.order_delivery_number',
            DB::raw("case when orders.customer_type = 'customer' then customer_pocket_history.pocket_present else '0.00' end AS pocket_present"),
            'order_delivery.total',
            'order_delivery.money_deposit',
            DB::raw("case when orders.customer_type = 'customer' then customer_pocket_history.pocket_money else '0.00' end AS pocket_money"),
            'order_delivery.status_payment',
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
        ->where('order_delivery.order_id',$id)->orderby('order_delivery.created_at','asc')
        ->groupBy('order_delivery.order_delivery_id')
        ->get();

        $order = Orders::where('id',$id)->with('GetDeposit')->first();
        $quotation = Quotation::where('id',$order->quotation_id)->first();
        //check customer
        if($quotation->customer_type == 'customer')
        {
            $customer = MasterCustomer::select('master_customer.*','province.province_name','amphoe.amphoe_name','district.district_name')
            ->leftjoin('master_province as province','province.province_code','=','master_customer.province_code')
            ->leftjoin('master_amphoe as amphoe','amphoe.amphoe_code','=','master_customer.amphoe_code')
            ->leftjoin('master_district as district','district.district_code','=','master_customer.district_code')
            ->where('master_customer.id',$quotation->customer_id)->with('PocketMoney')->first();
        }else{
            $customer = CustomerOther::where('id',$quotation->customer_id)->first();
        }
        
        $breadcrumb = [['url'=>'orders','title'=>'รายการบิลหลัก'],['url'=>'orders/view/'.$order->id,'title'=>'รายการบิลหลัก '.$order->order_number],['url'=>'','title'=>'บิลย่อย']];
         $deliverys = OrderDelivery::select('order_delivery.*','payment_history.status as status_payment')
        ->join('payment_history','payment_history.order_delivery_id','=','order_delivery.order_delivery_id')
        ->where('order_delivery.order_delivery_id',$id)->first();
        $paymentType = DB::table('payment_type')->get();
		
        return view('delivery.list',compact('order','quotation','customer','breadcrumb','datas','deliverys','paymentType'));
    }

    public function add($id)
    {
		$quotation =  Quotation::select('*')
				->join('orders','orders.quotation_id','=','quotation.id')
				->where('orders.id',$id)
				->first();
				
		$orders = Orders::find($id);
		
		#$orderdelivery_pera = OrderDelivery::where('order_id',$id)->count()+1;
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
				
			$location_name = $orders_pera->pera_delivery_location_name;
			$location_contact_person_name = $orders_pera->onsite_contact_name;
			$location_contact_person_phone_no = $orders_pera->onsite_contact_phone_no;
		}
		
		$delivery_location = DeliveryLocation::select('*')
			->where('master_delivery_location.order_id',$id)
			->where('master_delivery_location.customer_id',$orders->customer_id)->get();
		
        
        $datas = OrderItmes::select('order_itmes.*','product_type.product_type_name as type_name','product_size_name as size_name')
        ->join('product_type','product_type.id','=','order_itmes.product_type_id')
        ->join('product_size','product_size.id','=','order_itmes.product_size_id')
        ->where('order_itmes.order_id',$id)->get();
        foreach($datas as $data)
        {
            $data->item_send = collect(OrderDelivery::select('order_delivery_items.qty')->join('order_delivery_items','order_delivery_items.order_delivery_id','order_delivery.order_delivery_id')->where('order_delivery_items.order_items_id',$data->id)->get())->sum('qty');
            // $query = OrderDelivery::where('order_id',$data->order_id)->
        }
        // dd($datas);
        $breadcrumb = [['url'=>'orders','title'=>'รายการบิลหลัก'],['url'=>'orders/view/'.$orders->id,'title'=>'บิลหลัก '.$orders->order_number],['url'=>'orders/delivery/list/'.$orders->id,'title'=>'บิลย่อย'],['url'=>'','title'=>'เพิ่มบิลย่อย']];
        $i = 1;
        $orderdelivery = OrderDelivery::where('order_id',$id)->count()+1;
        $order_delivery_number = 'S'.str_pad($orderdelivery, 3, '0', STR_PAD_LEFT);
        $paymentcount = PaymentHistory::where('order_id',$id)->whereNotNull('order_delivery_id')->get()->sum('total');
        return view('delivery.add',compact('quotation','breadcrumb','location_contact_person_name','location_contact_person_phone_no','orders','i','datas','order_delivery_number','orderdelivery','paymentcount','orders_pera','delivery_location','location_name'));

    }

    public function save(Request $request)
    {

        $order = Orders::find($request->orderid);
		
        $addOrderDelivery['order_id'] = $request->orderid;
        $addOrderDelivery['order_delivery_number'] = $request->order_delivery_number;
        $addOrderDelivery['status'] = $request->status;
        $addOrderDelivery['status_payment'] = $request->status_payment;

        $date_send = Carbon::createFromFormat('d/m/Y', trim($request->date_send));
        $date_send = date('Y-m-d H:i:s', strtotime($date_send));
        $addOrderDelivery['date_send'] = $date_send;
        $addOrderDelivery['total'] = str_replace(",","",$request->total);
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
            $filename = time() .$order->order_number. $request->order_delivery_number . '.' . $ext;
            $file->storeAs('public/delivery/', $filename);
            $addOrderDelivery['file'] = $filename;;
        }
        $addOrderDelivery['money_deposit'] = str_replace(",","",$request->cutdeposit);
        $addOrderDelivery['created_at'] = Carbon::now();
        $addOrderDelivery['created_by'] = Auth::user()->id;

        $orderdelivery = OrderDelivery::create($addOrderDelivery);
		
		if ($request->change_delivery_location_check_box == "yes") {
			if ($request->new_delivery_location_check_box == "yes") {
				$arrayAddDeliveryLocation['customer_id'] = $order->customer_id;
				$arrayAddDeliveryLocation['order_id'] = $request->orderid;
				$arrayAddDeliveryLocation['order_delivery_id'] = $orderdelivery->order_delivery_id;
				$arrayAddDeliveryLocation['location'] = $request->delivery_location;
				$arrayAddDeliveryLocation['onsite_contact_name'] = $request->new_delivery_location_contact_person_name;
				$arrayAddDeliveryLocation['onsite_contact_phone_no'] = $request->new_delivery_location_contact_person_phone_no;
				$id_pera = DeliveryLocation::create($arrayAddDeliveryLocation)->id;
				
				$orders_pera = OrderDelivery::find($orderdelivery->order_delivery_id);
				$orders_pera->delivery_location_id = $id_pera;
				$orders_pera->save();
			} else {
				$orders_pera = OrderDelivery::find($orderdelivery->order_delivery_id);
				$orders_pera->delivery_location_id = $request->existing_delivery_location_id;
				$orders_pera->save();
			}
        }

        foreach($request->items_send as $key => $val)
        {
            $addItem['order_delivery_id'] = $orderdelivery->order_delivery_id;
            $addItem['order_items_id'] = $key;
            $addItem['qty'] = $val;
            OrderDeliveryItems::create($addItem);
        }
        if($orderdelivery->status == 1)
        {

            $orderdeliverycheck =  OrderDelivery::select(DB::raw('sum( order_delivery_items.qty ) AS sum_order_delivery_item '))
            ->join('order_delivery_items','order_delivery_items.order_delivery_id','=','order_delivery.order_delivery_id')
            ->where('order_delivery.order_id',$orderdelivery->order_id)->where('status',1)->first();
            $orderqty = Orders::select(DB::raw('sum( order_itmes.number_order ) AS sum_order_item '))
            ->join('order_itmes','order_itmes.order_id','=','orders.id')
            ->where('orders.id',$orderdelivery->order_id)->first();

            if($orderdeliverycheck->sum_order_delivery_item == $orderqty->sum_order_item)
            {
                $order = Orders::find($orderdelivery->order_id);
                $order->status_send = 1;
                $order->updated_at = Carbon::now();
                $order->updated_by = Auth::user()->id;
                $order->save();
            }
        }


        // if($orderdelivery->sum_order_delivery_item == $orderqty->sum_order_item)
        // {
        //     $order = Orders::find($orderdelivery->order_id);
        //     $order->status_send = 1;
        //     $order->updated_at = Carbon::now();
        //     $order->updated_by = Auth::user()->id;
        //     $order->save();

        // }

        // add PaymentHistory
        $addpaymenthistory['order_delivery_id']= $orderdelivery->order_delivery_id;
        $addpaymenthistory['order_id']= $orderdelivery->order_id;
        $addpaymenthistory['payment_type']= $order->payment_type;
        $addpaymenthistory['status']= $request->status_payment;
        $addpaymenthistory['total']= str_replace(",","",$request->hav_to_payment);
        if(isset($request->date_payment))
        {
            $date_payment = Carbon::createFromFormat('d/m/Y', trim($request->date_payment));
            $date_payment = date('Y-m-d', strtotime($date_payment));
            $addpaymenthistory['date_playment']= $date_payment;
        }else{
            if($request->status_payment == 1)
            {
                $addpaymenthistory['date_playment']= date('Y-m-d');
            }
        }
        $addpaymenthistory['file']= $orderdelivery->file;
        $addpaymenthistory['created_at']= Carbon::now();
        $addpaymenthistory['created_by']= Auth::user()->id;
        // PaymentHistory::create($addpaymenthistory);


        if($order->payment_type == 4)
        {
            $customerpocker = CustomerPocketHistory::where('customer_id',$order->customer_id)->orderby('created_at','desc')->first();
            $arrayAddPocker['customer_id'] = $order->customer_id;
            $arrayAddPocker['pocket_type'] = 2;
            $arrayAddPocker['order_delivery_id'] = $orderdelivery->order_delivery_id;
            $arrayAddPocker['note'] = 'จ่ายเงิน '.$order->order_number.$orderdelivery->order_delivery_number;

            $arrayAddPocker['recieve_pocket'] = $orderdelivery->total;

            $arrayAddPocker['pocket_present'] = $customerpocker->pocket_money;

            $arrayAddPocker['pocket_money'] = $customerpocker->pocket_money - $orderdelivery->total;

            $arrayAddPocker['created_at'] = Carbon::now();
            $arrayAddPocker['created_by'] = Auth::user()->id;
            CustomerPocketHistory::create($arrayAddPocker);
        }
        $this->checkstatuspayment($request->orderid);

        return redirect()->to('orders/delivery/list/'.$request->orderid);

    }

    public function view($id, $id_pdf = null)
    {
		
		$payment_method_type_code =  Orders::select('payment_method_type_code')
				->join('order_delivery','order_delivery.order_id','=','orders.id')
				->where('order_delivery.order_delivery_id',$id)
				->first();
				
		$the_amount_of_total_collected_exceeded_the_pocket_money = false;

		if ($payment_method_type_code->payment_method_type_code == '3') 
		{ 
			$amount_of_pocket_money_result_of_query = PaymentMethodPocketMoney::select('amount')
					->join('order_delivery','payment_method_pocket_money.order_id','=','order_delivery.order_id')
					->where('order_delivery.order_delivery_id',$id)
					->first();
					
			
			
			// sum previous delivery
			
			$rawSQL = "select sum(amount) as sum_amount from payment_history WHERE order_id = (select order_id from order_delivery where order_delivery.order_delivery_id = ".$id.")";
			$total_of_amount_in_payment_history = DB::select($rawSQL)[0]->sum_amount;
			
			$rawSQL = "select total from order_delivery WHERE order_delivery.order_delivery_id = ".$id;
			$amount_of_current_delivery_bill = DB::select($rawSQL)[0]->total;
			
			$total_collected_amount_of_pocket_money_method_type = $total_of_amount_in_payment_history + $amount_of_current_delivery_bill;
			
			if ((int)$total_collected_amount_of_pocket_money_method_type > (int)$amount_of_pocket_money_result_of_query->amount) {
				$the_amount_of_total_collected_exceeded_the_pocket_money = true;
			}
		}

		$check_if_already_updated_for_payment_method_of_order =  Orders::select('*')
				->join('order_delivery','order_delivery.order_id','=','orders.id')
				->where('orders.payment_method_type_code',0)
				->where('order_delivery.order_delivery_id',$id)
				->first();
				
	    $check_if_already_added_for_payment_history =  PaymentHistory::select('*')
				->join('order_delivery','payment_history.order_delivery_id','=','order_delivery.order_delivery_id')
				->join('orders','order_delivery.order_id','=','orders.id')
				->where('payment_history.order_delivery_id',$id)
				->first();
		//return $check_if_already_added_for_payment_history->toSql();
		$check_if_already_added_for_payment_history_flag_or_not_update_payment_method_yet = "No";	
		
		if (
				   ($check_if_already_added_for_payment_history != NULL) 
				or ($check_if_already_updated_for_payment_method_of_order != NULL)
				or ($the_amount_of_total_collected_exceeded_the_pocket_money == true)
		   ) 
		{
			$check_if_already_added_for_payment_history_flag_or_not_update_payment_method_yet = "Yes";
		}
		
        $datas = OrderDelivery::select(
            'order_itmes.product_name',
			'order_itmes.product_id',
			'order_itmes.pera',
            'product_type.product_type_name as type_name',
            'product_type.id AS product_type_id',
            'order_itmes.size_unit',
            'product_size_name as size_name',
            'order_itmes.price as price_item',
            'order_itmes.number_order as item_number_order',
            'order_itmes.count_unit',
            'order_itmes.total_item as total_item_all',
            'order_itmes.note as item_note',
            'order_itmes.id as order_itmes_id',
			'order_delivery.order_delivery_number',
            'order_delivery.order_delivery_id',
			'order_delivery.delivery_location_id as delivery_location_id',
            'order_delivery.order_id as order_delivery_order_id',
			'order_delivery.render_price as order_delivery_render_price',
			'order_delivery.*',
			#'order_delivery_items.qty as order_delivery_items_qty_pera'
            )
        ->join('order_itmes','order_itmes.order_id','=','order_delivery.order_id')
        ->join('product_type','product_type.id','=','order_itmes.product_type_id')
        ->join('product_size','product_size.id','=','order_itmes.product_size_id')
		#->join('order_delivery_items','order_delivery_items.order_delivery_id','=','order_delivery.order_delivery_id')
        ->where('order_delivery.order_delivery_id',$id)
        ->get();
		
		$data_for_remarks = OrderDelivery::select('*')->find($id);
		
		$datas_sub_total_delivery = OrderDeliveryItems::select('order_delivery_items.*')
		->join('order_delivery','order_delivery.order_delivery_id','=','order_delivery_items.order_delivery_id')
		->join('order_itmes','order_delivery_items.order_items_id','=','order_itmes.id')
        ->join('products','products.id','=','order_itmes.product_id')
        ->where('order_delivery.order_delivery_id',$id)
		->sum(\DB::raw('products.weight * order_delivery_items.qty'));
		
		$order_id_for_locations = "";
		foreach($datas as $data)
        {
			$order_id_for_locations = $data->order_delivery_order_id;
			if ($data->delivery_location_id == 0) {
				$location_name = 'ยังไม่มีที่จัดส่ง';
				$location_contact_person_name = "ยังไม่มีข้อมูล";
			$location_contact_person_phone_no = "ยังไม่มีข้อมูล";
			} else {
				$order_delivery_for_location = OrderDelivery::select('*','master_delivery_location.location as delivery_location_name')
				->join('master_delivery_location','master_delivery_location.id','=','order_delivery.delivery_location_id')
				->where('order_delivery.order_delivery_id',$id)
				->first();
				$location_name = $order_delivery_for_location->delivery_location_name;
				$location_contact_person_name = $order_delivery_for_location->onsite_contact_name;
				$location_contact_person_phone_no = $order_delivery_for_location->onsite_contact_phone_no;
			}
		}
		$delivery_location = DeliveryLocation::select('*')
			->where('master_delivery_location.order_id',$order_id_for_locations)->get();
		
        foreach($datas as $data)
        {
            $data->item_send = collect(OrderDelivery::select('order_delivery_items.qty')
            ->join('order_delivery_items','order_delivery_items.order_delivery_id','order_delivery.order_delivery_id')
            ->where('order_delivery_items.order_items_id',$data->order_itmes_id)
            ->where('order_delivery_items.order_delivery_id','<=',$data->order_delivery_id)
            ->get())->sum('qty');

            $OrD = OrderDeliveryItems::select(
                                         DB::raw('CAST(order_itmes.number_order - sum(order_delivery_items.qty) AS SIGNED) AS qty_padding') ,
                                         DB::raw('(SELECT order_delivery_items.qty FROM order_delivery_items WHERE order_delivery_items.order_delivery_id = '.$data->order_delivery_id.' AND order_delivery_items.order_items_id = '.$data->order_itmes_id.') AS qty')
                                        )
            ->LeftJoin('order_itmes','order_delivery_items.order_items_id','order_itmes.id')
            ->where('order_delivery_items.order_items_id',$data->order_itmes_id)
            ->where('order_delivery_items.order_delivery_id','<=',$data->order_delivery_id)
            ->groupBy('order_delivery_items.order_items_id','order_itmes.number_order')->get()->toArray();

            if (count($OrD) != 0) {
                $data->item_send_qty_padding = $OrD[0]['qty_padding'];
                $data->item_send_qty = $OrD[0]['qty'];
            }else{
                $data->item_send_qty_padding = 0;
                $data->item_send_qty = 0;
            }
        }

        #$order = Orders::where('id',$data->order_id)->with('GetDeposit')->first();
		
		$datas_for_order_id = OrderDelivery::select(
            'order_itmes.product_name',
            'product_type.product_type_name as type_name',
            'product_type.id AS product_type_id',
            'order_itmes.size_unit',
            'product_size_name as size_name',
            'order_itmes.price as price_item',
            'order_itmes.number_order as item_number_order',
            'order_itmes.count_unit',
            'order_itmes.total_item as total_item_all',
            'order_itmes.note as item_note',
            'order_itmes.id as order_itmes_id',
			'order_delivery.order_delivery_number',
            'order_delivery.order_delivery_id',
			'order_delivery.delivery_location_id as delivery_location_id',
			'order_delivery.order_id as delivery_order_id',
            'order_delivery.order_id as order_delivery_order_id'
            )
        ->join('order_itmes','order_itmes.order_id','=','order_delivery.order_id')
        ->join('product_type','product_type.id','=','order_itmes.product_type_id')
        ->join('product_size','product_size.id','=','order_itmes.product_size_id')
        ->where('order_delivery.order_delivery_id',$id)
        ->first();
		
		//die($sql_pera_2);
		
		
		$order = Orders::where('id',$datas_for_order_id->delivery_order_id)->with('GetDeposit')->first();
        $quotation = Quotation::where('id',$order->quotation_id)->first();
        //check customer
        // dd($quotation->customer_type,$order->quotation_id);
        if($quotation->customer_type == 'customer')
        {
            $customer = MasterCustomer::select('master_customer.*','province.province_name','amphoe.amphoe_name','district.district_name')
            ->leftjoin('master_province as province','province.province_code','=','master_customer.province_code')
            ->leftjoin('master_amphoe as amphoe','amphoe.amphoe_code','=','master_customer.amphoe_code')
            ->leftjoin('master_district as district','district.district_code','=','master_customer.district_code')
            ->where('master_customer.id',$quotation->customer_id)->with('PocketMoney')->first();
        }else{
            $customer = CustomerOther::where('id',$quotation->customer_id)->first();
        }

        // $deliverys = OrderDelivery::select('order_delivery.*','payment_history.status as status_payment')
        // ->join('payment_history','payment_history.order_delivery_id','=','order_delivery.order_delivery_id')
        // ->where('order_delivery.order_delivery_id',$id)->first();
		
		// $deliverys = OrderDelivery::select('order_delivery.*')
        // #->join('payment_history','payment_history.order_delivery_id','=','order_delivery.order_delivery_id')
        // ->where('order_delivery.order_delivery_id',$id)->first();
		
		#die(OrderDelivery::select('order_delivery.*','payment_history.status as status_payment')
        #->join('payment_history','payment_history.order_delivery_id','=','order_delivery.order_delivery_id')
        #->where('order_delivery.order_delivery_id',$id)->toSql());
		
		//die("$sql_pera_1");
        $datas_chunk = $datas->chunk(10);
        $pirntCount = Form::first();
        $paymentType = DB::table('payment_type')->get();
        //dd($pirntCount->print_count);

        $deliverys = OrderDelivery::where('order_delivery.order_delivery_id',$id)->first();

        $orders = Orders::find($deliverys->order_id);
        $breadcrumb = [['url'=>'orders','title'=>'รายการบิลหลัก'],['url'=>'orders/view/'.$deliverys->order_id,'title'=>'บิลหลัก '.$orders->order_number],['url'=>'orders/delivery/list/'.$deliverys->order_id,'title'=>'บิลย่อย'],['url'=>'','title'=>'รายละเอียดบิลย่อย']];
        $i = 1;
		$quotation =  Quotation::select('*')
				->join('orders','orders.quotation_id','=','quotation.id')
				->where('orders.id',$deliverys->order_id)
				->first();

        $printLogs = printLogModel::where('print_log_order_id',$deliverys->order_id)->get();
        return view('delivery.view',compact('printLogs','paymentType','quotation','pirntCount','datas_chunk','check_if_already_updated_for_payment_method_of_order','check_if_already_added_for_payment_history_flag_or_not_update_payment_method_yet','breadcrumb','data_for_remarks','location_contact_person_name','location_contact_person_phone_no','i','datas','datas_sub_total_delivery','orders','deliverys','customer','location_name','delivery_location'));
    }

    public function update(Request $request)
    {		
		$deliverys = OrderDelivery::find($request->order_delivery_id);
		$deliverys->remark_1 = $request->remark_1;
		$deliverys->remark_2 = $request->remark_2;
		$deliverys->remark_3 = $request->remark_3;
		$deliverys->remark_4 = $request->remark_4;
		$deliverys->remark_5 = $request->remark_5;
		if(!empty($request->render_price) and $request->render_price = "N"){
			$deliverys->render_price = "N";
			$deliverys->save();
		} elseif(!empty($request->render_price) and $request->render_price = "Y") {
			$deliverys->render_price = "Y";
			$deliverys->save();
		}
		
        if (isset($request->status)) {
            $deliverys->status = $request->status;
        }
        
        $deliverys->updated_at = Carbon::now();
        $deliverys->updated_by = Auth::user()->id;
        $deliverys->save();

		$deliverys_pera2 = OrderDelivery::select('*','order_delivery.order_id as order_delivery_order_id')
        ->join('orders','orders.id','=','order_delivery.order_id')
        ->where('order_delivery.order_delivery_id',$request->order_delivery_id)->first();
	
		if ($request->change_delivery_location_check_box == "yes") {
			if ($request->new_delivery_location_check_box == "yes") {
				$arrayAddDeliveryLocation['customer_id'] = $deliverys_pera2->customer_id;
				$arrayAddDeliveryLocation['order_id'] = $deliverys_pera2->order_delivery_order_id;
				
				$arrayAddDeliveryLocation['order_delivery_id'] = $request->order_delivery_id;
				$arrayAddDeliveryLocation['location'] = $request->delivery_location;
				
				$arrayAddDeliveryLocation['onsite_contact_name'] = $request->new_delivery_location_contact_person_name;
				$arrayAddDeliveryLocation['onsite_contact_phone_no'] = $request->new_delivery_location_contact_person_phone_no;
				
				$id_pera = DeliveryLocation::create($arrayAddDeliveryLocation)->id;
				
				$orders_pera = OrderDelivery::find($request->order_delivery_id);
				$orders_pera->delivery_location_id = $id_pera;
				$orders_pera->save();
			} else {
				$orders_pera = OrderDelivery::find($request->order_delivery_id);
				$orders_pera->delivery_location_id = $request->existing_delivery_location_id;
				$orders_pera->save();
			}
        }

        if($request->status)
        {
            $deliverys->status = $request->status;
            // $date_send = Carbon::createFromFormat('Y-m-d H:i:s', trim($request->date_send));
            $date_send = date('Y-m-d H:i:s', strtotime($request->date_send));
            $deliverys->date_send = $date_send;
            $deliverys->updated_at = Carbon::now();
            $deliverys->updated_by = Auth::user()->id;
            $deliverys->save();
            // check qty send
            $orderdelivery =  OrderDelivery::select(DB::raw('sum( order_delivery_items.qty ) AS sum_order_delivery_item '))
            ->join('order_delivery_items','order_delivery_items.order_delivery_id','=','order_delivery.order_delivery_id')
            ->where('order_delivery.order_id',$deliverys->order_id)->where('status',1)->first();
            $orderqty = Orders::select(DB::raw('sum( order_itmes.number_order ) AS sum_order_item '))
            ->join('order_itmes','order_itmes.order_id','=','orders.id')
            ->where('orders.id',$deliverys->order_id)->first();

            if($orderdelivery->sum_order_delivery_item == $orderqty->sum_order_item)
            {
                $order = Orders::find($deliverys->order_id);
                $order->status_send = 1;
                $order->updated_at = Carbon::now();
                $order->updated_by = Auth::user()->id;
                $order->save();

            }
        }

        if($request->hasFile('file'))
        {
            if($deliverys->file)
            {
                Storage::disk('public')->delete('delivery/'.$deliverys->file);
            }
            $attach = array('file' => $request->file('file'));
            $rules = array('file' => 'mimes:pdf,png,jpg'); //mimes:jpeg,bmp,
            $validateAttach = Validator::make($attach, $rules);
            if ($validateAttach->fails()) {
                return response()->json(['mgs' => 'ไม่รองรับไฟล์นี้', 'status' => 'error'], 200);
            }
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $filename = time() .$deliverys->getOrder->order_number. $deliverys->order_delivery_number . '.' . $ext;
            $file->storeAs('public/delivery/', $filename);
            $deliverys->file = $filename;
            $deliverys->save();
        }


        if($request->status_payment)
        {
            $updatepayment = PaymentHistory::where('order_delivery_id',$request->order_delivery_id)->first();
            if($request->status_payment == 1)
            {
                $updatepayment->date_playment = date('Y-m-d');
            }
            $updatepayment->status = $request->status_payment;
            $updatepayment->file = $deliverys->file;
            $updatepayment->updated_at = Carbon::now();
            $updatepayment->updated_by = Auth::user()->id;
            $updatepayment->save();
        }

        $this->checkstatuspayment($deliverys->order_id);

        return redirect()->to('/orders/delivery/list/'.$deliverys->order_id);
    }

    private function checkstatuspayment($id)
    {
        $ordercheckpayment =  OrderDelivery::select(
            DB::raw('sum( order_delivery_items.qty ) AS sum_order_delivery_item ')
            )
        ->join('order_delivery_items','order_delivery_items.order_delivery_id','=','order_delivery.order_delivery_id')
        ->where('order_delivery.status_payment',1)->where('order_delivery.order_id',$id)->first();

        $orderqty = Orders::select(DB::raw('sum( order_itmes.number_order ) AS sum_order_item '))
        ->join('order_itmes','order_itmes.order_id','=','orders.id')
        ->where('orders.id',$id)->first();
        if($ordercheckpayment->sum_order_delivery_item == $orderqty->sum_order_item)
        {
           $order =  Orders::find($id);
           $order->status_payment = 1;
           $order->updated_at = Carbon::now();
           $order->updated_by = Auth::user()->id;
           $order->save();

        }
    }

}
