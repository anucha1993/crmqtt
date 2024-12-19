<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Orders;
use Mpdf\QrCode\QrCode;
use App\Models\OrderItmes;
use Mpdf\QrCode\Output\Png;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;

class ReceiptController extends Controller
{

    
   

    public function generateReceipt($id)
{
    $order = Orders::findOrFail($id);
    $datas = OrderItmes::select(
        'order_itmes.*',
        'product_type.product_type_name as type_name',
        'product_size_name as size_name'
    )
        ->join('product_type', 'product_type.id', '=', 'order_itmes.product_type_id')
        ->join('product_size', 'product_size.id', '=', 'order_itmes.product_size_id')
        ->where('order_itmes.order_id', $id)
        ->get();
    $paymentHistory = PaymentHistory::where('order_id', $id)->where('status', 1)->get();
    $datas_sub_total = OrderItmes::select('order_itmes.*', 'products.weight as products_weight')
        ->join('products', 'products.id', '=', 'order_itmes.product_id')
        ->where('order_itmes.order_id', $id)
        ->sum(\DB::raw('products.weight * order_itmes.count_unit'));

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
        $htmlPage1 = view('MPDF.receipt-page-1', compact('order', 'datas', 'qrCodeImage', 'paymentHistory', 'datas_sub_total'))->render();
        $mpdf->WriteHTML($htmlPage1);

        // เพิ่มหน้าใหม่
        $mpdf->AddPage();

        // สร้าง HTML สำหรับหน้าที่สอง
        $htmlPage2 = view('MPDF.receipt-page-2', compact('order', 'datas', 'qrCodeImage', 'paymentHistory', 'datas_sub_total'))->render();
        $mpdf->WriteHTML($htmlPage2);

         // เพิ่มหน้าใหม่
         $mpdf->AddPage();

         // สร้าง HTML สำหรับหน้าที่สอง
         $htmlPage3 = view('MPDF.receipt-page-3', compact('order', 'datas', 'qrCodeImage', 'paymentHistory', 'datas_sub_total'))->render();
         $mpdf->WriteHTML($htmlPage3);

          // เพิ่มหน้าใหม่
        $mpdf->AddPage();

        // สร้าง HTML สำหรับหน้าที่สอง
        $htmlPage4 = view('MPDF.receipt-page-4', compact('order', 'datas', 'qrCodeImage', 'paymentHistory', 'datas_sub_total'))->render();
        $mpdf->WriteHTML($htmlPage4);

        // แสดง PDF
        return $mpdf->Output('document.pdf', 'I');
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


}
