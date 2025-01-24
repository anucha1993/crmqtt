<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Orders;
use Mpdf\QrCode\QrCode;
use App\Models\Quotation;
use App\Models\OrderItmes;
use Mpdf\QrCode\Output\Png;
use Illuminate\Http\Request;
use App\Models\CustomerOther;

use App\Models\OrderDelivery;
use App\Models\MasterCustomer;
use App\Models\PaymentHistory;
use App\Models\DeliveryLocation;
use App\Models\OrderDeliveryItems;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethodPocketMoney;
use App\Models\printLogModel;
use Illuminate\Support\Facades\Auth;

class MPDF_DeliveryController extends Controller
{
    //

    public function generateMPDF(Request $request, $id )
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

        $deliverys = OrderDelivery::select('order_delivery.*','payment_history.status as status_payment')
        ->join('payment_history','payment_history.order_delivery_id','=','order_delivery.order_delivery_id')
        ->where('order_delivery.order_delivery_id',$id)->first();
		$checkStock = $data->item_number_order - $data->item_send;
        $statusDeliver = $checkStock <= 0 ? '- ส่งครบ' : '';

       
        if($request->method_print === 'print'){
            
            // ทำการพิมพ์และบันทึกข้อมูล
            $status = $datas_for_order_id->item_number_order == $data->item_send ? 'ส่งครบ' : '-';
           
            $checkCountPrint = Form::where('name', 'delivery')->first();
            $countNew = $checkCountPrint->print_count + 1;
            $checkCountPrint->update(['print_count' => $countNew]);
    
            printLogModel::create([
                'print_log_type' => 'delivery',
                'print_log_delivery_id' => $deliverys->order_delivery_id,
                'print_log_order_id' => $deliverys->order_id,
                'order_delivery_status' => $status,
                'print_log_count' => $countNew,
                'created_by' => Auth::user()->id,
            ]);
    
            // ตั้งค่า session ว่าพิมพ์ไปแล้ว
            session(['printed' => true]);
            $request->merge(['method_print' => NULL]); 
    
         
        }



        $datas_chunk = $datas->chunk(10);
        $pirntCount = Form::first();
        $paymentType = DB::table('payment_type')->get();
        //dd($pirntCount->print_count);
        $orders = Orders::find($deliverys->order_id);
        $breadcrumb = [['url'=>'orders','title'=>'รายการบิลหลัก'],['url'=>'orders/view/'.$deliverys->order_id,'title'=>'บิลหลัก '.$orders->order_number],['url'=>'orders/delivery/list/'.$deliverys->order_id,'title'=>'บิลย่อย'],['url'=>'','title'=>'รายละเอียดบิลย่อย']];
        $i = 1;
		$quotation =  Quotation::select('*')
				->join('orders','orders.quotation_id','=','quotation.id')
				->where('orders.id',$deliverys->order_id)
				->first();
				
    
        $qrCode = new QrCode('http://crmqtt.test:5555/orders/view/' . $id);
        $output = new Png();
        $qrCodeData = base64_encode($output->output($qrCode));
        $qrCodeImage = 'data:image/png;base64,' . $qrCodeData;

        
    
        try {
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];
    
            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
    
            // สร้างเอกสาร mPDF
            $mpdf = new \Mpdf\Mpdf([
                'fontDir' => array_merge($fontDirs, [
                    storage_path('fonts'),
                ]),
                'fontdata' => $fontData + [
                    'sarabun_new' => [
                        'R' => 'THSarabunNew.ttf',
                        'B' => 'THSarabunNew Bold.ttf',
                        'I' => 'THSarabunNew Italic.ttf',
                        'BI' => 'THSarabunNew BoldItalic.ttf',
                    ],
                ],
                'default_font' => 'sarabun_new',
            ]);
    
            // กำหนด Margin
            $mpdf->SetMargins(10, 10, 10, 10);

            // สร้าง HTML สำหรับหน้าแรก
        
            $htmlPage1 = view('MPDF_Delivery.page-1',compact('data','request','statusDeliver','order','qrCodeImage','paymentType','quotation','pirntCount','datas_chunk','check_if_already_updated_for_payment_method_of_order','check_if_already_added_for_payment_history_flag_or_not_update_payment_method_yet','breadcrumb','data_for_remarks','location_contact_person_name','location_contact_person_phone_no','i','datas','datas_sub_total_delivery','orders','deliverys','customer','location_name','delivery_location'))->render();
            $htmlPage2 = view('MPDF_Delivery.page-2',compact('request','statusDeliver','order','qrCodeImage','paymentType','quotation','pirntCount','datas_chunk','check_if_already_updated_for_payment_method_of_order','check_if_already_added_for_payment_history_flag_or_not_update_payment_method_yet','breadcrumb','data_for_remarks','location_contact_person_name','location_contact_person_phone_no','i','datas','datas_sub_total_delivery','orders','deliverys','customer','location_name','delivery_location'))->render();
            $htmlPage3 = view('MPDF_Delivery.page-3',compact('request','statusDeliver','order','qrCodeImage','paymentType','quotation','pirntCount','datas_chunk','check_if_already_updated_for_payment_method_of_order','check_if_already_added_for_payment_history_flag_or_not_update_payment_method_yet','breadcrumb','data_for_remarks','location_contact_person_name','location_contact_person_phone_no','i','datas','datas_sub_total_delivery','orders','deliverys','customer','location_name','delivery_location'))->render();
            $htmlPage4 = view('MPDF_Delivery.page-4',compact('request','statusDeliver','order','qrCodeImage','paymentType','quotation','pirntCount','datas_chunk','check_if_already_updated_for_payment_method_of_order','check_if_already_added_for_payment_history_flag_or_not_update_payment_method_yet','breadcrumb','data_for_remarks','location_contact_person_name','location_contact_person_phone_no','i','datas','datas_sub_total_delivery','orders','deliverys','customer','location_name','delivery_location'))->render();
            $mpdf->WriteHTML($htmlPage1);
            $mpdf->AddPage();
            $mpdf->WriteHTML($htmlPage2);
            $mpdf->AddPage();
            $mpdf->WriteHTML($htmlPage3);
            $mpdf->AddPage();
            $mpdf->WriteHTML($htmlPage4);
            $mpdf->SetHTMLHeader('<script type="text/javascript">window.onload = function() { window.print(); }; </script>');
            return $mpdf->Output('document.pdf', 'I');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
