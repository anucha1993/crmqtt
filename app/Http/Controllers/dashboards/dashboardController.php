<?php

namespace App\Http\Controllers\dashboards;

use App\Models\User;
use App\Models\Orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class dashboardController extends Controller
{
    //
    


    public function quoteVsOrder()
    {
        // ดึงยอดรวม quote & order ต่อเซลส์
        $stats = User::select('id', 'name')
            ->withSum('quotations as quotation_total', 'total') // field total ในตาราง quotation
            ->withSum('orders as order_total', 'total')         // field total ในตาราง orders
            ->get()
            ->map(function ($row) {
                return [
                    'name'  => $row->name,
                    'quote' => (float) $row->quotation_total ?? 0,
                    'order' => (float) $row->order_total ?? 0,
                ];
            });

        return view('dashboard.quote-vs-order', compact('stats'));
    }

     // /api/quote-order-stats
     public function statsJson()
     {
         $stats = User::select('id', 'name')
             ->withSum('quotations as quotation_total', 'total')
             ->withSum('orders as order_total', 'total')
             ->get()
             ->map(fn ($u) => [
                 'name'  => $u->name,
                 'quote' => (float) $u->quotation_total,
                 'order' => (float) $u->order_total,
             ]);
 
         return response()->json($stats);
     }

      //  /api/quote-order-convert
    public function convertStats()
    {
        // ดึงรายชื่อเซลส์ทั้งหมด (หรือจะฟิลเตอร์ role ก็ได้)
        $users = User::select('id', 'name')->get();

        // เตรียม Order -> Quotation map เพื่อลด query
        $orderByQuote = Orders::pluck('quotation_id')->all();   // array ของ quotation_id ที่มี order แล้ว

        $stats = $users->map(function ($u) use ($orderByQuote) {
            $quoteCount     = $u->quotations()->count();        // จำนวนใบเสนอราคา
            $convertedCount = $u->quotations()
                                ->whereIn('id', $orderByQuote)  // ใบเสนอราคาที่มี order
                                ->count();

            return [
                'id'        => $u->id,
                'name'      => $u->name,
                'quotes'    => $quoteCount,
                'converted' => $convertedCount,
            ];
        });

        return response()->json($stats);
    }

    public function topCustomers()
{
    // TOP 10 ลูกค้าที่มี order เยอะสุด (จะใช้ total sales ก็ได้ เปลี่ยน selectRaw)
    $customers = Orders::selectRaw('customer_id, COUNT(*) AS order_count')
        ->groupBy('customer_id')
        ->orderByDesc('order_count')
        ->limit(10)
        ->with('Customer:id,customer_name')        // สมมติชื่อลูกค้าอยู่ในฟิลด์ customer_name
        ->get()
        ->map(fn ($row) => [
              'name'  => $row->Customer->customer_name ?? 'ไม่ทราบ',
              'count' => (int) $row->order_count,
        ]);

    return response()->json($customers);
}
   
}
