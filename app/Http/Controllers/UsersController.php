<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Auth;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Roles;

class UsersController extends Controller
{

    public function index()
    {
        if(Auth::user()->role_id == 3)
        {
            return redirect()->to('/');
        }
        $roles = Roles::get();
        return view('users.index',compact('roles'));
    }

    public function list(Request $request)
    {
        if($request->ajax())
        {

            $query = User::select('name','email','role_id','status','id');

            if($request->search)
            {
                $query->where(
                    function($q) use($request){
                        $q->where('name','like','%'.$request->search.'%');
                        $q->orWhere('email','like','%'.$request->search.'%');
                    }
                );
            }

            if($request->role != '')
            {
                $query->where('role_id',$request->role);
            }
            if($request->status != '')
            {
                $query->where('status',$request->status);
            }

            $datas = $query->orderby('created_at','asc')->with(['GetRole'])->get();
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
                $html .= '<td>' . $data->name . '</td>';
                $html .= '<td>' . $data->email . '</td>';
                $html .= '<td>' . $data->GetRole->role_name . '</td>';
                $html .= '<td>' . statusUserstStr($data->status) . '</td>';
                $html .= '<td style="text-align: center;">
                            <a href="'.url('/users/edit/'.$data->id).'" class="btn btn-link">รายละเอียด</a>
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
        $roles = Roles::get();
        $breadcrumb = [['url'=>'users','title'=>'ผู้ดูแลระบบ'],['url'=>'','title'=>'สร้างผู้ดูแลระบบ']];
        return view('users.form',compact('roles','breadcrumb'));
    }

    public function save(Request $request)
    {

        $addUser = new User;
        $addUser->role_id = $request->role_id;
        $addUser->name = $request->fname.' '.$request->lname;
        $addUser->status = $request->status;
        $addUser->email = $request->email;
        $addUser->email_verified_at = Carbon::now();
        $addUser->password = Hash::make($request->password);
        $addUser->created_by = Auth::user()->id;
        $addUser->created_at = Carbon::now();
        $addUser->save();

        return redirect()->to('users');
    }

    public function edit($id)
    {
        $roles = Roles::get();
        $breadcrumb = [['url'=>'users','title'=>'ผู้ดูแลระบบ'],['url'=>'','title'=>'แก้ไขผู้ดูแลระบบ']];
        $edit = User::where('id',$id)->first();
        return view('users.form',compact('roles','breadcrumb','edit'));
    }

    public function update(Request $request)
    {
        $updateUser = User::find($request->userid);
        $updateUser->role_id = $request->role_id;
        $updateUser->name = $request->fname.' '.$request->lname;
        $updateUser->status = $request->status;
        $updateUser->email = $request->email;
        $updateUser->email_verified_at = Carbon::now();
        $updateUser->created_by = Auth::user()->id;
        $updateUser->created_at = Carbon::now();
        $updateUser->save();
        return redirect()->to('users');
    }

    public function cehckemail(Request $request)
    {
        if($request->ajax())
        {
            // return $request->all();
            $check = User::select('email');
            $check->whereRaw('LOWER(email) like ?',['%'.trim(strtolower($request->email)).'%']);

            if($request->userid)
            {
                $check->where('id','!=',$request->userid);
            }
            $data = $check->first();
            if($data)
            {
                return response()->json(['status' => false,'msg'=>'อีเมลนี้มีผู้ใช้งานแล้ว'],200);
            }else{
                return response()->json(['status' => true,'msg'=>''],200);
            }
        }
    }

    public function cehckname(Request $request)
    {
        if($request->ajax())
        {
            $name = trim($request->fname).' '.trim($request->lname);

            $check = User::select('name');
            $check->where('name',$name);

            if($request->userid)
            {
                $check->where('id','!=',$request->userid);
            }
            $data = $check->first();
            if($data)
            {
                return response()->json(['status' => false,'msg'=>'ชื่อมีผู้ใช้งานแล้ว'],200);
            }else{
                return response()->json(['status' => true,'msg'=>''],200);
            }
        }
    }

    public function changpass(Request $request)
    {
        $updatePass = User::find($request->userid);
        $updatePass->password = Hash::make($request->password);
        $updatePass->updated_at = Carbon::now();
        $updatePass->updated_at = Auth::user()->id;
        $updatePass->save();
        return redirect()->to('login');
    }
}
