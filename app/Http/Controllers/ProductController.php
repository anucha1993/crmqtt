<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Models\ProductSize;

use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Auth;
use Carbon\Carbon;
use Validator;

use App\Models\Quotation;
use App\Models\Orders;
use App\Models\Products;
use App\Models\MasterCustomer;
use App\Models\CustomerOther;
use App\Models\OrderItmes;
use App\Models\OrderDelivery;
use App\Models\PaymentHistory;
use App\Models\CustomerPocketHistory;
use App\Models\Notifications;
use App\Events\SendNoti;
use App\Models\Roles;

class ProductController extends Controller
{
	
	public function index()
    {
        return view('products.index');
    }
	
	public function add()
    {
		$last_product_code = Products::select('id')->orderBy('id', 'desc')->first();
        $product_code = (!empty($last_product_code)) ? $last_product_code->id + 1 : 1;
        $product_code = str_pad($product_code, 4, '0', STR_PAD_LEFT);
		$product_code = "PDC-".$product_code;
		
        return view('products.add', compact('product_code'));
    }
	
	public function update(Request $request)
    { 
		$product = Products::find($request->id);
		$product->product_code = $request->product_code;
		$product->product_name = $request->product_name;
		$product->weight = $request->weight;
		$product->note = $request->note;
		$product->save();
		
        return redirect()->to('products');
    }
	
	public function edit($id)
    {
        $roles = Roles::get();
        $breadcrumb = [['url'=>'users','title'=>'ผู้ดูแลระบบ'],['url'=>'','title'=>'แก้ไขผู้ดูแลระบบ']];
        $edit = Products::where('id',$id)->first();
        return view('products.edit',compact('roles','breadcrumb','edit'));
    }
	
	public function deleted($id)
    {
        $product = Products::where('id',$id)->first();
		$product->deleted = "Y";
		$product->save();
        return redirect()->to('products');
    }
	
	public function save(Request $request)
    {
        $arrayAddProduct['product_code'] = $request->product_code;
        $arrayAddProduct['product_name'] = $request->product_name;
        $arrayAddProduct['weight'] = $request->weight;
        $arrayAddProduct['note'] = $request->note;
		
        $arrayAddProduct['created_at'] = Carbon::now();
        $arrayAddProduct['created_by'] = Auth::user()->id;
        $newProduct =  Products::create($arrayAddProduct);
		
		return redirect()->to('products');
    }
	
	public function list(Request $request)
    {
		$query = Products::select(
			'products.id',
			'products.product_code',
			'products.product_name',
			'products.weight',
			'products.note',
		);
		$query->where('deleted','N');
		$datas = $query->get();
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
                $html .= '<td>' . $data->product_code . '</td>';
				$html .= '<td>'. $data->product_name .'</td>';
				$html .= '<td>'. $data->weight .'</td>';
				$html .= '<td><textarea disabled cols=40 rows=4>'. $data->note .'</textarea></td>';
                $html .= '<td style="text-align: center;">
                            <div class="dropdown">
                                <button type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-outline-secondary dropdown-toggle">
                                     จัดการ
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                <a onclick="if (confirm(\'ยืนยันลบสินค้า\')) {return true; }else{ return false;}" class="dropdown-item '.' " href="'.url('products/deleted/'.$data->id).'">ลบ</a>';
                $html .= '<a class="dropdown-item" href="'.url('products/edit/'.$data->id).'">แก้ไข</a>';
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
	
    public function prodecttype(Request $request)
    {
        if($request->ajax())
        {
            $datas = ProductType::get();
            $html = '';
            $html .= '<option value="">-เลือก-</option>';
            foreach($datas as $data)
            {
                $html .= '<option value="'.$data->id.'">'.$data->product_type_name.'</option>';
            }
            return response()->json(['html'=>$html],200);
        }
    }
    public function productsize(Request $request)
    {
        if($request->ajax())
        {
            $datas = ProductSize::where('prosuct_type_id',$request->select)->get();
            $html = '';
            $html .= '<option value="">-เลือก-</option>';
            foreach($datas as $data)
            {
                $typename = ProductType::where('id',$request->select)->first();
                if($typename->product_type_name == 'แผ่นพื้น' && $data->product_size_name == 'x 0.35 ตร.ม.')
                {
                    $html .= '<option value="'.$data->id.'" selected data-size="'.$data->product_size_name.'">'.$data->product_size_name.'</option>';
                }
                elseif ($typename->product_type_name == 'ท่อน' && $data->product_size_name == 'จำนวน * ราคา') {
                  $html .= '<option value="'.$data->id.'" selected data-size="'.$data->product_size_name.'">'.$data->product_size_name.'</option>';
                }
                elseif ($typename->product_type_name == 'เที่ยว' && $data->product_size_name == 'จำนวน * ราคา') {
                  $html .= '<option value="'.$data->id.'" selected data-size="'.$data->product_size_name.'">'.$data->product_size_name.'</option>';
                }
                else{
                    $html .= '<option value="'.$data->id.'" data-size="'.$data->product_size_name.'">'.$data->product_size_name.'</option>';
                }
            }
            return response()->json(['html'=>$html],200);
        }
    }

    public function productcountunit(Request $request)
    {
        if($request->ajax())
        {
            $datas = countunit();

            $html = '';
            $html .= '<option value="">-เลือก-</option>';
            foreach($datas as $data)
            {

                $html .= '<option value="'.$data->id.'">'.$data->name.'</option>';
            }
            return response()->json(['html'=>$html],200);
        }
    }
}
