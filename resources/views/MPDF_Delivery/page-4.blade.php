<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ใบส่งของ</title>
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            height: 80px;
        }

        .header .details {
            text-align: right;
            font-size: 12pt;
        }

        .company-info {
            font-size: 12pt;
            margin-bottom: 20px;
        }

        .customer-info {
            font-size: 12pt;
            margin-bottom: 20px;
        }

        .invoice-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 50px;
            font-size: 20px;
            line-height: 1.5;
            text-align: center;
        }

        .footer p {
            margin: 15px 0;
        }
        
    </style>
</head>


<body>

    @if ($request->method_print === 'preview')
    <div style="width: 120%; float: left; padding: 10px;  position: absolute;  top: 100px; right: -275px">
        <img src="{{ asset('logo/example.png') }}" alt="">
    </div>
    @endif

    
    <div class="header">
        <div style="width: 65%; float: left;">
            <img src="{{ asset('logo/logo.png') }}" alt="HomePro Logo" style="height: 50px; display: block;">
            <p style="margin: 0; font-size: 14pt;"><b>Call ติดต่อ สอบถาม 082-4789197</b></p>
            <p style="margin: 0; font-size: 14pt;">บริษัท เจริญมั่น คอนกรีต จำกัด (สำนักงานใหญ่)</p>
            <p style="margin: 0; font-size: 14pt;">ที่อยู่ 99/35 หมู่ที่ 9 ตำบลละหาร อำเภอบางบัวทอง จังหวัดนนทบุรี 11110
            </p>
            <p style="margin: 0; font-size: 14pt;">เลขประจำตัวผู้เสียภาษีอากร: 0125560015546</p>
        </div>

       
        <div style="text-align: right; padding: 0; margin: 0; width: 35%;">
            <!-- ข้อความแสดงจำนวนหน้า -->
            <p style="margin: 0; padding-right: -60px; font-size: 12pt;">
                <strong>หน้า/ที่:</strong> {PAGENO}/{nbpg}
            </p>
        
            <!-- ข้อความแสดงวันที่ -->
            {{-- <p style="margin: 0; padding: 0; font-size: 12pt;">
                <strong>วันที่:</strong> 
            </p> --}}
        
            <!-- ข้อความแสดงน้ำหนักรวม -->
            <p style="margin: 0; padding: 0; font-size: 12pt;">
                <strong>น้ำหนักรวม:</strong> {{$datas_sub_total_delivery}} Kgs.
            </p>
        
            <!-- ส่วนของ QR Code -->
            
        </div>
        

        <div style="clear: both;"></div>
    </div>

    {{-- <div class="invoice-title">
        ใบเสร็จรับเงิน (ฉบับสำเนา 1)
    </div> --}}
    <table class="table" style="width: 100%; border-collapse: collapse; font-size: 14pt;">
        <thead>
            <tr>
                <td  style="border: 1px solid rgba(0, 0, 0, 0.048); padding: 2px; text-align: left; width: 400px">
                    <span><b>ชื่อลูกค้า :</b> {{$customer->store_name}}</span><br>
                    <span><b>ที่อยู่จัดส่ง :</b> {{$location_name}}</span><br>
                    <span><b>ชื่อผู้ติดต่อ:</b> {{$location_contact_person_name}}</span><br>
                    <span><b>เบอร์ติดต่อ:</b> {{$location_contact_person_phone_no}}</span><br>
                </td>
                <th></th>
                <td   style="border: 1px solid black; padding: 1px; text-align: left; width: 200px" >
                    <span><b>วันที่จัดส่ง : </b>{{date('d/m/Y',strtotime($deliverys->date_send))}}</span><br>
                    <span><b>เลขที่บิลหลัก :</b> {{$orders->order_number}}</span><br>
                    <span><b>เลขที่บิลย่อย :</b> {{$datas_chunk[0][0]->order_delivery_number}} {{$statusDeliver}}</span><br>
                    <span><b>Billno : </b>{{$pirntCount->print_count}}</span><br>
                </td>
                <td></td>
                <td style="border: 1px solid black; padding: 1px; text-align: center;  width: 20px">
                    <img src="{{ $qrCodeImage }}" alt="QR Code" style="position: absolute; width: 100px;">
                </td>
               
            </tr>
        </thead>
    </table>

    @foreach ($datas_chunk as $datas_chuck_item)
      <table style="border:1px solid black;border-collapse:collapse; width: 100%; font-size: 16px;">
        <thead>
            <tr style="background:#b9b9b90a; border:1px solid">
                <th style="width: 10%; border:1px solid white;">ลำดับ</th>
                <th style="width: 10%; border:1px solid white;">จำนวน</th>
                <th style="width: 10%; border:1px solid white;">หน่วยนับ</th>
                <th style="width: 30%; border:1px solid white;">รายการสินค้า</th>
                <th style="width: 10%; border:1px solid white;">ราคาต่อหน่วย</th>
                <th style="width: 10%; border:1px solid white;">จำนวน</th>
            </tr>
        </thead>

        <tbody>
            @php
            $j=0;
            $total = 0;
            $diffTotal = ((count($datas_chuck_item) < 30) ? (30-count($datas_chuck_item)-6) : 0);
        @endphp
        @foreach ($datas_chuck_item as $data)
         
        <?php
            $test_pera = ($data->product_type_id == 1) ? number_format(($data->size_unit*$data->price_item*$data->item_send_qty),2) : number_format(($data->size_unit*0.35*$data->price_item*$data->item_send_qty),2);
            if ($test_pera == 0) { continue;}

            $total += $data->total_item_all;
        ?>
            
            <tr>
                <td align="center">{{++$j}}</td>
                <td align="center">{{$data->item_send_qty}}</td>
                <td align="center">{{countunitstr($data->count_unit)}}</td>
                <td align="left">{{$data->product_name}} {{$data->size_unit.' '.$data->size_name.' '.$data->pera}}</td>
                <td align="center">{{number_format($data->price_item,2)}}</td>
                <td align="right">{{number_format($data->total_item_all,2)}}</td>
            </tr>
            @endforeach

            @for ($i = $j; $i <= 9; $i++)
            <tr>
                <td align="center">{{ $i }}</td>
            </tr>
        @endfor
           

            <tr>
                <td colspan="4"></td>
                <td style="border:1px solid black; text-align: right;"><strong>ราคาก่อนภาษี: </strong></td>
                <td align="right" style="border:1px solid black;">{{ $order->render_price == 'No' ? 'n/a' : number_format($order->price_all, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="border:1px solid black; text-align: right;"><strong>ส่วนลด:</strong></td>
                <td align="right" style="border:1px solid black;">{{ number_format($order->discount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="border:1px solid black; text-align: right;"><strong>จำนวนหลังหักส่วนลด:</strong></td>
                <td align="right" style="border:1px solid black;">{{ number_format($order->price_all - $order->discount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="border:1px solid black; text-align: right;"><strong>ภาษีมูลค่าเพิ่ม:</strong></td>
                <td align="right" style="border:1px solid black;">{{ $order->render_price == 'No' ? 'n/a' : ($order->on_vat == 1 ? number_format($order->vat, 2) : '0.00') }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="border:1px solid black; text-align: right;"><strong>จำนวนเงินทั้งสิน:</strong></td>
                <td align="right" style="border:1px solid black;">{{ $order->render_price == 'No' ? 'n/a' : number_format($order->total, 2) }}</td>
            </tr>

        </tbody>
       
        </table>

        @endforeach
        





    {{-- <table style="width: 100%; margin-top: 20px; font-size: 14pt; border-collapse: collapse;">

        <tr>
            <!-- คอลัมน์ซ้าย -->

            <td style="width: 60%; vertical-align: top; padding-right: 20px;">
                <p><strong>ประเภทการชำระเงิน : </strong> {!! PatmentType($order->payment_type) !!}</p>
                @if ($order->payment_type == '1')
                    {{-- <p><strong>จำนวนเงินทั้งสิ้นจากบิลหลัก : </strong> 15,000 บาท</p> 
                    <p><strong>ชำระแล้ว : </strong> {{ number_format($paymentHistory->sum('total'), 2) }} บาท
                        @if ($paymentHistory->sum('total') >= $order->total)
                            (ชำระเงินครบแล้ว)
                        @else
                        @endif
                    </p>
                    @forelse ($paymentHistory as $key => $item)
                        <p><strong>งวดที่ {{ $key + 1 }} : </strong> แจ้งชำระวันที่
                            {{ date('d/m/Y', strtotime($item->created_at)) }} จำนวนเงิน
                            {{ number_format($item->total, 2) }} บาท</p>
                    @empty
                    @endforelse
                @else
                @endif


            </td>

            <!-- คอลัมน์ขวา -->
            <td style="width: 25%; vertical-align: top; text-align: right;">
                <p><strong>มูลค่าสินค้าก่อนภาษีมูลค่าเพิ่ม: </strong> </p>
                <p><strong>ส่วนลด:</strong></p>
                <p><strong>จำนวนหลังหักส่วนลด:</strong></p>
                <p><strong>ภาษีมูลค่าเพิ่ม:</strong></p>
                <p><strong>มูลค่ารวม:</strong></p>
            </td>

            <td style="width: 15%; vertical-align: top; text-align: right;">
                <p>{{ $order->render_price == 'No' ? 'n/a' : number_format($order->price_all, 2) }} บาท</p>
                <p>{{ number_format($order->discount, 2) }} บาท</p>
                <p>{{ number_format($order->price_all - $order->discount, 2) }} บาท</p>
                <p>{{ $order->render_price == 'No' ? 'n/a' : ($order->on_vat == 1 ? number_format($order->vat, 2) : '0.00') }}
                    บาท</p>
                <p>{{ $order->render_price == 'No' ? 'n/a' : number_format($order->total, 2) }} บาท</p>

            </td>
        </tr>
        <tr>

            <td colspan="5" style="width: 50%; padding-right: 20px; text-align: right;">
                <p><strong>จำนวนเงินทั้งสิ้น (ตัวอักษร)</strong> (@bathText($order->price_all))</p>
            </td>

        </tr>
        <tr>

        </tr>
    </table> --}}

    <div style="font-size: 18px">
        <br>
        <span><b>หมายเหตุ :</b></span>
        <span>กรุณาตรวจสอบความถูกต้องของสินค้าและเซ็นรับสินค้าในวันที่ได้รับ หากไม่มีการตรวจสอบหรือเซ็นรับสินค้า
            ทางบริษัทขอสงวนสิทธิ์ในการรับผิดชอบต่อความผิดพลาดทุกกรณี</span>
    </div>

    <table style="width: 100%; margin-top: 20px; font-size: 14pt; border-collapse: collapse;">
        <tr>
            <!-- คอลัมน์ซ้าย -->

            <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                <p><strong>ลงชื่อผู้รับสินค้า...................................................ผู้รับสินค้า</strong>
                </p>

            </td>

            <!-- คอลัมน์ขวา -->
            <td style="width: 50%; vertical-align: top; text-align: right;">
                <p><strong>ลงชื่อผู้รับเงิน...................................................ผู้รับเงิน</strong></p>

            </td>
        </tr>

    </table>



</body>


</html>
