<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Validator;
use Carbon\Carbon;

use App\Models\Province;
use App\Models\Amphoe;
use App\Models\District;
use App\Models\MasterCustomer;
use App\Models\CustomerPocketHistory;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.index');
    }
	
	public function barcode()
    {
        return view('customer.barcode');
    }
	

    public function list(Request $request)
    {
        // if($request->alax())
        // {
            $query = MasterCustomer::select(
                'master_customer.id',
                'master_customer.text_number_vat',
                'master_customer.store_name',
                'master_customer.customer_name',
                'master_customer.customer_phone',
                'master_customer.customer_mail'
            );
            if($request->search)
            {
                $query->where(function($q) use ($request) {
                    $q->where('master_customer.text_number_vat','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.store_name','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.customer_name','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.customer_phone','like','%'.$request->search.'%');
                    $q->orWhere('master_customer.customer_mail','like','%'.$request->search.'%');
                });
            }
            // ->where('master_customer.store_name','like','%เทสสอบ%')
            $datas = $query->orderby('master_customer.created_at','desc')->with('PocketMoney')->get();
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
                $html .= '<td>' . $data->store_name . '</td>';
                $html .= '<td>' . $data->text_number_vat . '</td>';
                $html .= '<td>' . $data->customer_name . '</td>';
                $html .= '<td>' . $data->customer_phone . '</td>';
                $html .= '<td>' . $data->customer_mail . '</td>';
                $html .= '<td>'.  (!empty($data->PocketMoney) ?  number_format($data->PocketMoney->pocket_money,2) : '0.00').'</td>';
                $html .= '<td style="text-align: center;">
                            <a href="'.url('/customer/edit/'.$data->id).'" class="btn btn-link">รายละเอียด</a>
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

    public function searchcustomer(Request $request)
    {
        if($request->ajax())
        {
            $customers = MasterCustomer::where(function($q) use($request){
                $q->where('store_name','like','%'.$request->search.'%')->orWhere('customer_name','like','%'.$request->search.'%');
            })->limit(5)->get();
            $results=array();

            foreach ($customers as $key => $customer) {

                $results[]=['id'=>$customer->id,'value'=>$customer->store_name.' - '.$customer->customer_name];

            }

            return response()->json($results,200);
        }

    }

    public function datacustomer(Request $request)
    {
        if($request->ajax())
        {
            $customers = MasterCustomer::select('master_customer.*','province.province_name','amphoe.amphoe_name','district.district_name')
            ->leftjoin('master_province as province','province.province_code','=','master_customer.province_code')
            ->leftjoin('master_amphoe as amphoe','amphoe.amphoe_code','=','master_customer.amphoe_code')
            ->leftjoin('master_district as district','district.district_code','=','master_customer.district_code')
            ->where('master_customer.id',$request->customerid)->with('PocketMoney')->first();
            return response()->json($customers,200);
        }
    }

    public function add()
    {
        $provinces = Province::orderby('province_code','asc')->get();
        return view('customer.add',compact('provinces'));
    }

    public function save(Request $request)
    {
        $check  = MasterCustomer::where('text_number_vat',$request->text_number_vat)->orWhere('store_name',$request->store_name)->orWhere('customer_mail',$request->customer_mail)->first();
        //if($check)
        //{
        //    return redirect()->back()->withErrors(['msg', 'ชื่อร้านค้า,เลขประจำตัวผู้เสียภาษี,อีเมล นี้มีอยู่ในระบบแล้ว']);
        //}
        $addcustomer = new MasterCustomer;
        $addcustomer->text_number_vat = $request->text_number_vat;
        $addcustomer->store_name = $request->store_name;
        $addcustomer->customer_name = trim($request->fname).' '.trim($request->lname);
        $addcustomer->customer_phone = $request->customer_phone;
        $addcustomer->customer_mail = $request->customer_mail;
        $addcustomer->customer_address = $request->customer_address;
        $addcustomer->province_code = $request->province_code;
        $addcustomer->amphoe_code = $request->amphoe_code;
        $addcustomer->district_code = $request->district_code;
        $addcustomer->zip_code = $request->zip_code;
		$addcustomer->customer_type = $request->customer_type;
        $addcustomer->created_at = Carbon::now();
		$addcustomer->updated_at = "0000-00-00 00:00:00";
        $addcustomer->created_by = Auth::user()->id;
        $addcustomer->save();
        return redirect()->to('customer');
    }

    public function edit($id)
    {
        $edit = MasterCustomer::find($id);
        $provinces = Province::orderby('province_code','asc')->get();

        return view('customer.edit',compact('edit','provinces'));
    }

    public function update(Request $request)
    {
        $updatecustomer = MasterCustomer::find($request->customerid);
        $updatecustomer->text_number_vat = $request->text_number_vat;
        $updatecustomer->store_name = $request->store_name;
        $updatecustomer->customer_name = trim($request->fname).' '.trim($request->lname);
        $updatecustomer->customer_phone = $request->customer_phone;
        $updatecustomer->customer_mail = $request->customer_mail;
        $updatecustomer->customer_address = $request->customer_address;
        $updatecustomer->province_code = $request->province_code;
        $updatecustomer->amphoe_code = $request->amphoe_code;
        $updatecustomer->district_code = $request->district_code;
        $updatecustomer->zip_code = $request->zip_code;
        $updatecustomer->updated_at = Carbon::now();
        $updatecustomer->updated_by = Auth::user()->id;
		$updatecustomer->customer_type = $request->customer_type;
        $updatecustomer->save();
        return redirect()->to('customer');
    }

    public function getamphoe(Request $request)
    {
        if($request->ajax())
        { 
            $amphoes = Amphoe::where('province_code','=',$request->proviceselect)->orderby('amphoe_code','asc')->get(); 
            $html = '<option value="">-เลือก-</option>';
            if(!empty($amphoes))
            {
                foreach($amphoes as $amphoe)
                {

                    $html .= '<option date-provincecode="'.$amphoe->province_code.' "value="'.$amphoe->amphoe_code.'">'.$amphoe->amphoe_name.'</option>';
                }
            }
			
            return response()->json(['html'=>$html],200);
        }
    }
    public function getdistrict(Request $request)
    {
        if($request->ajax())
        {
            $districts = District::where('province_code',$request->proviceselect)->where('amphoe_code',$request->amphoeselect)->orderby('amphoe_code','asc')->get();
            $html = '<option value="">-เลือก-</option>';
            if(!empty($districts))
            {
                foreach($districts as $district)
                {
                    $html .= '<option zipcode="'.$district->zipcode.'" value="'.$district->district_code.'">'.$district->district_name.'</option>';
                }
            }
            return response()->json(['html'=>$html],200);
        }
    }

    public function cehckstore(Request $request)
    {
        if($request->ajax())
        {
            if($request->customerid)
            {
                $check = MasterCustomer::where('store_name','like','%'.$request->store_name.'%')->where('id','!=',$request->customerid)->first();

            }else{

                $check = MasterCustomer::where('store_name','like','%'.$request->store_name.'%')->first();
            }
            if($check)
            {
                return response()->json(['status'=>false],200);
            }else{
                return response()->json(['status'=>true],200);
            }
        }
    }

    public function cehckTextVat(Request $request)
    {
        if($request->ajax())
        {
            if($request->customerid)
            {
                $check = MasterCustomer::where('text_number_vat','like','%'.$request->text_number_vat.'%')->where('id','!=',$request->customerid)->first();

            }else{

                $check = MasterCustomer::where('text_number_vat','like','%'.$request->text_number_vat.'%')->first();
            }
            if($check)
            {
                return response()->json(['status'=>false],200);
            }else{
                return response()->json(['status'=>true],200);
            }
        }
    }

    public function cehckCustomerMail(Request $request)
    {
        if($request->ajax())
        {
            if($request->customerid)
            {
                $check = MasterCustomer::where('customer_mail','like','%'.$request->customer_mail.'%')->where('id','!=',$request->customerid)->first();

            }else{

                $check = MasterCustomer::where('customer_mail','like','%'.$request->customer_mail.'%')->first();
            }
            if($check)
            {
                return response()->json(['status'=>false],200);
            }else{
                return response()->json(['status'=>true],200);
            }
        }
    }

    public function pocketmoney($id)
    {
        $pockethistory = CustomerPocketHistory::select('pocket_money')->where('customer_id',$id)->orderby('created_at','desc')->first();
        return view('customer.pocketmoney',compact('id','pockethistory'));
    }

    public function pocketmoneylist(Request $request)
    {
        $query = CustomerPocketHistory::select('created_at','note','note_text','file','pocket_present','pocket_type','recieve_pocket','pocket_money')->where('customer_id',$request->customerid)->orderby('created_at','desc')->get();
        $datas = $query;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $limt_page = 10;
        $per_page = $limt_page;
        $collection = Collect($datas, $per_page);
        $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->all();
        $data_gen = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
        $gen = $this->genhtmlpayment($data_gen, $per_page);
        $arrayData['datas'] = $gen['html'];
        $arrayData['total'] = $gen['total'];
        $arrayData['pagination'] = $gen['pagination'];
        return $arrayData;
    }

    private function genhtmlpayment($datas, $per_page)
    {
        $html = '';

        $i = $datas->currentPage();
        $i = ($i * $per_page) - $per_page;
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                $html .= '<tr>';
                    $html .= '<td>' . ++$i . '</td>';
                    $html .= '<td>' . date('d/m/Y H:i:s',strtotime($data->created_at)) . '</td>';
                    $html .= '<td>' . $data->note . '</td>';
                    $html .= '<td>' . $data->note_text . '</td>';
                    $html .= '<td>' . ($data->file ? '<a href="'.url('storage/pocketmoney/'.$data->file).'" class="btn btn-link" target="_blank"> <i class="fa fa-paperclip" aria-hidden="true"></i></a>' : '') . '</td>';
                    $html .= '<td>' . number_format($data->pocket_present,2) . '</td>';
                    $html .= '<td>' . ($data->pocket_type == 1 ? number_format($data->recieve_pocket,2) : '0.00') . '</td>';
                    $html .= '<td>' . ($data->pocket_type == 2 ? number_format($data->recieve_pocket,2) : '0.00') . '</td>';
                    $html .= '<td>' . number_format($data->pocket_money,2) . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td class="text-center" colspan="8">No matching records found</td></tr>';
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


    public function pocketmoneysave(Request $request)
    {
        try
        {
            $getpocket = CustomerPocketHistory::where('customer_id',$request->customerid)->orderby('created_at','desc')->first();
            if($getpocket)
            {
                $pocket_present = $getpocket->pocket_money;
            }else{
                $pocket_present = 0.00;
            }
            $addpocket = new CustomerPocketHistory;
            $addpocket->customer_id = $request->customerid;
            $addpocket->pocket_type = 1;
            $addpocket->note = 'เพิ่ม Pocket Money';
            $addpocket->note_text = $request->note_text;
            $addpocket->recieve_pocket = str_replace(",","", $request->recieve_pocket);
            $addpocket->pocket_money = $pocket_present + str_replace(",","", $request->recieve_pocket);
            $addpocket->pocket_present = $pocket_present;
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
                $filename = time() .$request->customerid.'.' .$ext;
                $file->storeAs('public/pocketmoney/', $filename);
                $addpocket->file = $filename;
            }
            $addpocket->created_at = Carbon::now();
            $addpocket->created_by = Auth::user()->id;
            $addpocket->save();
            $data = CustomerPocketHistory::select('pocket_money')->where('customer_id',$request->customerid)->orderby('created_at','desc')->first();
            $pocket_money = number_format($data->pocket_money,2);
            $time = date('d/m/Y H:i:s');
            return response()->json(['status'=>'success','pocket_money'=>$pocket_money,'time'=>$time],200);
        }catch (\Exception $e) {
            return response()->json(['status'=>'error','message'=> $e->getMessage()],200);
        }

    }


}
