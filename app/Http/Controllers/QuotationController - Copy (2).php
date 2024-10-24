<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Auth;
use Carbon\Carbon;

use App\Models\Quotation;
use App\Models\OrderItmes;
use App\Models\MasterCustomer;
use App\Models\CustomerOther;
use App\Models\ProductType;
use App\Models\ProductSize;
use App\Models\Orders;
use App\Models\Notifications;
use App\Events\SendNoti;
use App\Models\DeliveryLocation;

class QuotationController extends Controller
{
    public function index()
    {
        return view('quotation.index');
    }

    public function list(Request $request)
    {
    
			$query = Quotation::select(
                'quotation.*',
                'orders.id as order_id',
                'orders.status as order_status',
				//'master_customer.customer_address as customer_address',
				//'master_customer.store_name as name_store',
				//'master_customer.customer_name as name_customer',
				//'master_customer.customer_phone as phone',
                DB::raw("case when quotation.customer_type = 'customer' then CONCAT(master_customer.customer_address,' ตำบล ',master_district.district_name,' อำเภอ ',master_amphoe.amphoe_name,' จังหวัด ',master_province.province_name,' ',master_customer.zip_code) 
				else customer_other.customer_address end AS customer_address"),
                DB::raw("case when quotation.customer_type = 'customer' then master_customer.store_name else customer_other.store_name end AS name_store"),
                DB::raw("case when quotation.customer_type = 'customer' then master_customer.customer_name else customer_other.customer_name end AS name_customer"),
                DB::raw("case when quotation.customer_type = 'customer' then master_customer.customer_phone else customer_other.customer_phone end AS phone")
                )
            ->leftJoin('master_customer', 'master_customer.id', '=', 'quotation.customer_id')
            ->leftJoin('customer_other', 'customer_other.id', '=', 'quotation.customer_id')
            ->leftJoin('orders', 'orders.quotation_id', '=', 'quotation.id')
            
            ->leftjoin('master_province','master_customer.province_code','=','master_province.province_code')
            ->leftjoin('master_amphoe','master_customer.amphoe_code','=','master_amphoe.amphoe_code')
            ->leftjoin('master_district','master_customer.district_code','=','master_district.district_code');
			#$sql_pera = $query->toSql();
            if($request->s != '')
            {
                $query->where('quotation.status',$request->s);
            }

            if($request->d)
            {
                $cutdate = explode('-', $request->d);
                $date1 = Carbon::createFromFormat('d/m/Y', trim($cutdate[0]));
                $date1 = date('Y-m-d', strtotime($date1));
                $date2 = Carbon::createFromFormat('d/m/Y', trim($cutdate[1]));
                $date2 = date('Y-m-d', strtotime($date2));
                $date = [$date1 . " 00:00:00", $date2 . " 23:59:59"];
                $query->whereBetween('quotation.created_at', $date);

            }
            if($request->search)
            {
                $query->where(function($q) use ($request) {
                    $q->where('quotation.quotation_number','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.store_name','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.store_name','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.customer_name','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.customer_name','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.customer_phone','like','%'.$request->search.'%');
                    $q->orWhere('customer_other.customer_phone','like','%'.$request->search.'%');
                });
            }
            // ->where('master_customer.store_name','like','%เทสสอบ%')
            $datas = $query->orderby('created_at','desc')->orderby('quotation.status','asc')->get();
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
			#$arrayData['sql_pera'] = $sql_pera;

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
                $html .= '<td>' . date('d/m/Y H:i:s', strtotime($data->created_at)) . '</td>';
                $html .= '<td>' . $data->quotation_number . '</td>';
                $html .= '<td>' . $data->name_store . '</td>';
                $html .= '<td>' . $data->name_customer . '</td>';
                $html .= '<td>' . $data->phone . '</td>';
                $html .= '<td>' . $data->customer_address . '</td>';
                $html .= '<td>' . number_format($data->total,2) . '</td>';
                if ($data->status == '0') {
                    $html .= '<td><span class="dot wait"></span>รอยืนยัน</td>';
                } else if ($data->status == '1') {
                    $html .= '<td><span class="dot approve"></span>ยืนยัน</td>';
                } else if ($data->status == '2') {
                    $html .= '<td><span class="dot close"></span>ยกเลิก</td>';
                }

                $html .= '<td style="text-align: center;">
                            <div class="dropdown">
                                <button type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-outline-secondary dropdown-toggle">
                                     จัดการ
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                    <a class="dropdown-item" href="'.url('quotation/view/'.$data->id).'">ใบเสนอราคา</a>';
                                    if($data->status == '1')
                                    {
                                        if($data->order_id == '')
                                        {
                                            $html .= '<a class="dropdown-item" href="'.url('orders/add/'.$data->id).'">หน้าบิลหลัก</a>';
                                        }else{

                                            if($data->order_status == '1' || $data->order_status == '2')
                                            {
                                                $html .= '<a class="dropdown-item" href="'.url('orders/view/'.$data->order_id).'">หน้าบิลหลัก</a>
                                                <a class="dropdown-item" href="'.url('orders/delivery/list/'.$data->order_id).'">รายการบิลย่อย</a>';
                                            }else{
                                                $html .= '<a class="dropdown-item" href="'.url('orders/view/'.$data->order_id).'">หน้าบิลหลัก</a>';
                                            }

                                        }
                                    }
                $html .=        '</div>
                            </div>
                         </td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td class="text-center" colspan="9">No matching records found</td></tr>';
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

    public function add()
    {
        $customer = MasterCustomer::select('master_customer.*','province.province_name','amphoe.amphoe_name','district.district_name')
            ->leftjoin('master_province as province','province.province_code','=','master_customer.province_code')
            ->leftjoin('master_amphoe as amphoe','amphoe.amphoe_code','=','master_customer.amphoe_code')
            ->leftjoin('master_district as district','district.district_code','=','master_customer.district_code')
            ->with('PocketMoney')->first();

        $producttypes = ProductType::get();
        $productsizes = ProductSize::get();
        $i = 1;
        $breadcrumb = [['url'=>'quotation','title'=>'ใบเสนอราคา'],['url'=>'','title'=>'สร้างใบเสนอราคา']];
        return view('quotation.add',compact('customer','i','producttypes','productsizes','breadcrumb'));
    }

    public function save(Request $request)
    {
		
// Pera adds begin locking table 
		//DB::statement(DB::raw('LOCK TABLES quotation WRITE'));
		//DB::statement(DB::raw('LOCK TABLES master_delivery_location WRITE'));
        $last_quotation_number = Quotation::select('id')->orderBy('id', 'desc')->first();
        $quotation_number = (!empty($last_quotation_number)) ? $last_quotation_number->id + 1 : 1;
        $quotation_number = str_pad($quotation_number, 6, '0', STR_PAD_LEFT);

        if($request->customerid)
        {
            $arrayAddQuotaion['customer_type'] = 'customer';
            $arrayAddQuotaion['customer_id'] = $request->customerid  ?? '';
        }else{
            $arrayAddQuotaion['customer_type'] = 'member';
            $arrayAddCustomer['store_name'] = $request->store_name ?? '';
            $arrayAddCustomer['text_number_vat'] = $request->text_number_vat ?? '';
            $arrayAddCustomer['customer_name'] = $request->customer_name ?? '';
            $arrayAddCustomer['customer_phone'] = $request->customer_phone ?? '';
            $arrayAddCustomer['customer_mail'] = $request->customer_mail ?? '';
            $arrayAddCustomer['customer_address'] = $request->customer_address;
			
			
			
            $arrayAddCustomer['created_at'] = Carbon::now();
            $arrayAddCustomer['created_by'] = Auth::user()->id;
            $newCustomer = CustomerOther::create($arrayAddCustomer);
            $arrayAddQuotaion['customer_id'] = $newCustomer->id;
        }
		$arrayAddQuotaion['remark_1'] = $request->remark_1;
		$arrayAddQuotaion['remark_2'] = $request->remark_2;
		$arrayAddQuotaion['remark_3'] = $request->remark_3;
		$arrayAddQuotaion['remark_4'] = $request->remark_4;
		$arrayAddQuotaion['remark_5'] = $request->remark_5;
			
        $arrayAddQuotaion['quotation_number'] = 'QN'.$quotation_number;
        $arrayAddQuotaion['total'] = array_sum(str_replace(',','',$request->total_item));
        $arrayAddQuotaion['created_by'] = Auth::user()->id;
        $arrayAddQuotaion['created_at'] = Carbon::now();
        $newObject = Quotation::create($arrayAddQuotaion);

// Pera adds
		$arrayAddDeliveryLocation['customer_id'] = $request->customerid;
		$arrayAddDeliveryLocation['quotation_id'] = $newObject->id;
		$arrayAddDeliveryLocation['order_id'] = 0;
		$arrayAddDeliveryLocation['order_delivery_id'] = 0;
		$arrayAddDeliveryLocation['location'] = $request->customer_address_onsite;
		$arrayAddDeliveryLocation['onsite_contact_name'] = $request->contact_person_onsite_name;
		$id_pera = DeliveryLocation::create($arrayAddDeliveryLocation)->id;
		
		$datas_pera = Quotation::where('id',($last_quotation_number->id + 1))->first();
		$datas_pera->delivery_location_id = $id_pera;
		$datas_pera->save();
// end Pera adds

		//DB::statement(DB::raw('UNLOCK TABLES'));
// End Pera's add, end locking table

        foreach($request->product_name as $key => $product_name)
        {
            $arrayAddOrderItem['quotation_id'] = $newObject->id;
            $arrayAddOrderItem['product_type_id'] = $request->product_type_id[$key];
            $arrayAddOrderItem['product_size_id'] = $request->product_size_id[$key];
            $arrayAddOrderItem['product_name'] = $product_name;
            $arrayAddOrderItem['size_unit'] = str_replace(',','',$request->size_unit[$key]);
            $arrayAddOrderItem['price'] = str_replace(',','',$request->price[$key]);
            $arrayAddOrderItem['total_item'] = str_replace(',','',$request->total_item[$key]);
            $arrayAddOrderItem['number_order'] = str_replace(',','',$request->number_order[$key]);
            $arrayAddOrderItem['count_unit'] = $request->countunit[$key];
            $arrayAddOrderItem['note'] = $request->note[$key];
            $arrayAddOrderItem['created_by'] = Auth::user()->id;
            $arrayAddOrderItem['created_at'] = Carbon::now();
			$arrayAddOrderItem['pera'] = $request->pera[$key]." ".$request->pera_2[$key];;
            OrderItmes::create($arrayAddOrderItem);
        }

        $noti = new Notifications;
        $noti->item_id = $newObject->id;
        $noti->noti_type = 1;
        $noti->items_status = 0;
        $noti->read = 0;
        $noti->noti_status = 0;
        $noti->created_by = Auth::user()->id;
        $noti->created_at = Carbon::now();
        $noti->save();
        event(new SendNoti());
        $linemessage = 'สร้างใบเสนอราคา '.$newObject->quotation_number.' ยอดรวมใบเสนอราคา '.number_format($newObject->total,2).' วันที่สร้าง '.date('d/m/Y H:i:s',strtotime($newObject->created_at));
        SendNotiline($linemessage);

        return redirect()->to('quotation/view/'.$newObject->id);
        // Quotation
    }

    public function view($id)
    {
        $quotation =  Quotation::find($id);
        $datas = OrderItmes::select('order_itmes.*','product_type.product_type_name as type_name','product_size_name as size_name','pera')
        ->join('product_type','product_type.id','=','order_itmes.product_type_id')
        ->join('product_size','product_size.id','=','order_itmes.product_size_id')
        ->where('quotation_id',$id)->get();
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
        $i = 1;
        $producttypes = ProductType::get();
        $productsizes = ProductSize::get();
        //end check customer
        $order = Orders::where('quotation_id',$id)->first();

        $breadcrumb = [['url'=>'quotation','title'=>'ใบเสนอราคา'],['url'=>'','title'=>'ใบเสนอราคา'.$quotation->quotation_number]];
// Pera adds
		$datas_quotation_pera = Quotation::select('*')
        ->join('master_delivery_location','delivery_location_id','=','master_delivery_location.id')
        ->where('quotation.id',$id)->first();
		#$test = $datas_quotation_pera->toSql();
// end Pera adds
        return view('quotation.view',compact('datas','customer','quotation','i','producttypes','productsizes','breadcrumb','order','datas_quotation_pera'));
    }

    public function update(Request $request)
    {

        $datas = Quotation::where('id',$request->quotationid)->first();
        $total = 0;
        if($request->status)
        {
            $datas->status = $request->status;
            $datas->note = $request->note;
            $datas->updated_by = Auth::user()->id;
            $datas->updated_at = Carbon::now();
            $datas->save();

            if($request->status == 1)
            {
                $noti = new Notifications;
                $noti->item_id = $datas->id;
                $noti->noti_type = 1;
                $noti->items_status = 1;
                $noti->read = 0;
                $noti->noti_status = 0;
                $noti->created_by = Auth::user()->id;
                $noti->created_at = Carbon::now();
                $noti->save();
                event(new SendNoti());
                $linemessage = 'อนุมัติใบเสนอราคาเลขที่ '.$datas->quotation_number.' ยอดรวมใบเสนอราคา '.number_format($datas->total,2).' วันที่อนุมัติ '.date('d/m/Y H:i:s',strtotime($datas->updated_at));
                SendNotiline($linemessage);
            }

        }



        if(!empty($request->product_name))
        {
            OrderItmes::where('quotation_id',$datas->id)->delete();
            foreach($request->product_name as $key => $product_name)
            {

                $arrayAddOrderItem['quotation_id'] = $datas->id;
                $arrayAddOrderItem['product_type_id'] = $request->product_type_id[$key];
                $arrayAddOrderItem['product_size_id'] = $request->product_size_id[$key];
                $arrayAddOrderItem['product_name'] = $product_name;
                $arrayAddOrderItem['size_unit'] = str_replace(',','',$request->size_unit[$key]);
                $arrayAddOrderItem['price'] = str_replace(',','',$request->price[$key]);
                $arrayAddOrderItem['total_item'] = str_replace(',','',$request->total_item[$key]);
                $arrayAddOrderItem['number_order'] = str_replace(',','',$request->number_order[$key]);
                $arrayAddOrderItem['count_unit'] = $request->countunit[$key];
                #$arrayAddOrderItem['note'] = $request->note[$key];
                $arrayAddOrderItem['created_by'] = Auth::user()->id;
                $arrayAddOrderItem['created_at'] = Carbon::now();
				
				$arrayAddOrderItem['pera'] = $request->pera[$key]." ".$request->pera_2[$key];

                $total = $total + str_replace(',','',$request->total_item[$key]);
                OrderItmes::create($arrayAddOrderItem);
            }
            $datas->total = $total;
            $datas->save();
        }

        if($request->status == 1)
        {
            return response()->json(['status'=>'success','mgs'=>'','url'=>'/quotation/view/'.$datas->id],200);

        }else{
            return response()->json(['status'=>'success','mgs'=>'','url'=>'/quotation/view/'.$datas->id],200);
        }

    }
    public function updatestatus(Request $request)
    {
        $datas = Quotation::where('id',$request->quotationid)->first();
        $datas->status = $request->status;
        $datas->save();
        return response()->json(['status'=>'success','mgs'=>'','url'=>'/quotation/view/'.$datas->id],200);

    }



}
