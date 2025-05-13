<?php

namespace App\Http\Controllers\api;

use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class api_paymentController extends Controller
{
    //

    public function create($id)
    {
        $order = Orders::where('id',$id)->with('GetDeposit')->first();
        $paymentType = DB::table('payment_type')->get();
        return view('api_payment.modal-create',compact('paymentType','order'));
    }
}
