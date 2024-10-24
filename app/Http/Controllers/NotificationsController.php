<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Auth;
use Carbon\Carbon;

use App\Models\Notifications;



class NotificationsController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }

    public function updatenoti(Request $request)
    {
        $noti = Notifications::where('id',$request->notiid)->first();
        $noti->read = 1;
        $noti->updated_at = Carbon::now();
        $noti->updated_by = Auth::user()->id;
        $noti->save();
        if($noti->noti_type == 1)
        {
            $url = url('quotation/view/'.$noti->item_id);
        }else{
            $url = url('orders/view/'.$noti->item_id);
        }
        return response()->json(['url'=>$url],200);
    }




}
