<?php

namespace App\Http\Controllers;

use DB;



use Carbon\Carbon;
use App\Models\Orders;
use App\Events\SendNoti;

use App\Models\Products;
use App\Models\Quotation;
use App\Models\OrderItmes;
use Illuminate\Http\Request;
use App\Models\CustomerOther;
use App\Models\Notifications;
use App\Models\OrderDelivery;
use App\Models\MasterCustomer;
use App\Models\PaymentHistory;
use App\Models\DeliveryLocation;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerPocketHistory;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\FileUploadController;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentHistoryController extends Controller
{
    public function add_payment_history_record(Request $request)
    {
        $arrayAddPaymentHistory['order_delivery_id'] = $request->order_delivery_id;
        $arrayAddPaymentHistory['order_id'] = $request->order_id;
        $arrayAddPaymentHistory['amount'] = $request->amount;
        $arrayAddPaymentHistory['date_playment'] = $request->date_time;
        //$arrayAddPaymentHistory['created_by'] = Auth::user()->id;
        //$arrayAddPaymentHistory['created_at'] = Carbon::now();

        $newPaymentHistory =  PaymentHistory::create($arrayAddPaymentHistory);

        return redirect()->to('orders');
    }


    public function index(Orders $orders)
    {
        // ดึงประเภทการชำระเงิน
        $paymentType = DB::table('payment_type')->get();
        // ดึงประวัติการชำระเงิน
        $paymentHistory = PaymentHistory::where('payment_history.order_id', $orders->id) // ระบุชื่อเต็ม
            ->select('payment_history.*', 'order_delivery.order_delivery_number')
            ->leftJoin('order_delivery', 'order_delivery.order_delivery_id', '=', 'payment_history.order_delivery_id')
            ->get();

        // ส่งข้อมูลไปยัง view
        return view('payment_history.payment-history', compact('orders', 'paymentHistory', 'paymentType'));
    }



    public function store(Request $request)
    {

        //dd($request);
        // รับเลขที่ใบงานจาก request
        $jobNumber = $request->order_number;

        // สร้างอ็อบเจกต์ของ FileUploadController
        $fileUpload = new FileUploadController();

        // เรียกใช้ฟังก์ชัน uploadFile และส่งค่า request และเลขที่ใบงาน
        $path = $fileUpload->uploadFile($request, $jobNumber);
        //dd($path);
        // ตรวจสอบว่า path มีค่าหรือไม่
        $data = $request->all();
        $data['status'] = 3;
        if ($path) {
            // รวม path ของไฟล์เข้าไปใน request

            $data['file'] = $path; // ใช้ path ที่ได้จากการอัปโหลดไฟล์// ใช้ path ที่ได้จากการอัปโหลดไฟล์
            $data['created_by'] = Auth::user()->id; // ใช้ path ที่ได้จากการอัปโหลดไฟล์

            // สร้างบันทึกใหม่ใน PaymentHistory
            PaymentHistory::create($data);
        } else {
            PaymentHistory::create($data);
        }

        Orders::where('id', $request->order_id)->update(['status_payment' => 3]);

        OrderDelivery::where('order_delivery_id', $request->order_delivery_id)->update(['money_deposit' => $request->total]);


        // ทำการ redirect กลับพร้อมข้อความสำเร็จ
        return redirect('payments/order/'.$request->order_id);
    }

    public function approvePayment(PaymentHistory $PaymentHistory)
    {
     

        $order = Orders::where('id', $PaymentHistory->order_id)->first();
        
        $PaymentHistory->update(['status' => 1]);

        $paymentSum = $PaymentHistory->where('status',1)->where('order_delivery_id', $PaymentHistory->order_delivery_id)->sum('total');


        $delivery = OrderDelivery::where('order_delivery_id', $PaymentHistory->order_delivery_id)->first();

        $deliveryTotal = $delivery->money_deposit;

        if($paymentSum >= $delivery->total){
            $delivery->update(['status_payment' => 1]);
        }elseif($paymentSum > 0){
            $delivery->update(['status_payment' => 2]);
        }

        

        $statusPayment = 0;
        $statusPaymentSub = 0;

        if ($order->GetDepositSum() >= $order->total) {
            $statusPayment = 1;
        } elseif ($order->GetDepositSum() > 0) {
            $statusPayment = 2;
        }

        $delivery->update(['money_deposit' => $paymentSum]);

        $order->update(['status_payment' => $statusPayment, 'payment_type' => $PaymentHistory->payment_type]);

        return redirect()->back();
    }



    public function cancelPayment(PaymentHistory $PaymentHistory)
    {
        $PaymentHistory->update(['status' => 2]);
        $order = Orders::where('id', $PaymentHistory->order_id)->first();
        $statusPayment = 0;

        if ($order->GetDepositSum() >= $order->total) {
            $statusPayment = 1;
        } elseif ($order->GetDepositSum() > 0) {
            $statusPayment = 2;
        }

        $order->update(['status_payment' => $statusPayment]);

        return redirect()->back();
    }
}
