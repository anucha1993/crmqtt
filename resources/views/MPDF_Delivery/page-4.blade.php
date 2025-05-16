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
    <div style="width: 120%; float: left; padding: 0px;  position: absolute;  top: 100px; right: -275px">
        <img src="{{ asset('logo/example.png') }}" alt="">
    </div>
    @endif

    
    <div class="header">
        <div style="width: 65%; float: left;">
            <img src="{{ asset('logo/logo.png') }}" alt="HomePro Logo" style="height: 50px; display: block;">
            <p style="margin: 0; font-size: 16pt;"><b>Call ติดต่อ สอบถาม 082-4789197</b></p>
            <p style="margin: 0; font-size: 16pt;">บริษัท เจริญมั่น คอนกรีต จำกัด (สำนักงานใหญ่)</p>
            <p style="margin: 0; font-size: 16pt;">ที่อยู่ 99/35 หมู่ที่ 9 ตำบลละหาร อำเภอบางบัวทอง จังหวัดนนทบุรี 11110
            </p>
            <p style="margin: 0; font-size: 16pt;">เลขประจำตัวผู้เสียภาษีอากร: 0125560015546</p>
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
    <table class="table" style="width: 100%; border-collapse: collapse; font-size: 16pt; margin: -20px -20px 0px -20px">
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

   
    <table style="border:1px solid black;border-collapse:collapse; width: 100%; font-size: 20px; margin: 0px -20px 0px -20px">
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
            $j = 1; // เริ่มต้น j จาก 1 และอยู่นอก loop chunk
            $total = 0;
               $Grandtotal = 0;
            $previousChunkRows = 0; // เก็บจำนวนแถวจาก chunk ก่อนหน้า
        @endphp
        
        @foreach ($datas_chunk as $datas_chuck_item)
            @php
                $diffTotal = ((count($datas_chuck_item) < 30) ? (30 - count($datas_chuck_item) - 6) : 0);
                $currentChunkRows = 0; // นับจำนวนแถวใน chunk ปัจจุบัน
            @endphp
        
            @foreach ($datas_chuck_item as $data)
                <?php
                 $Grandtotal += $data->product_type_id == 1
    ? ($data->size_unit * $data->price_item * $data->item_send_qty)
    : ($data->size_unit * 0.35 * $data->price_item * $data->item_send_qty);
                    $test_pera = ($data->product_type_id == 1) ? number_format(($data->size_unit * $data->price_item * $data->item_send_qty), 2) : number_format(($data->size_unit * 0.35 * $data->price_item * $data->item_send_qty), 2);
                    if ($test_pera == 0) {
                        continue;
                    }
                    $total += $data->total_item_all;
                    $currentChunkRows++; // เพิ่มจำนวนแถวใน chunk ปัจจุบัน
                ?>
                <tr>
                    <td align="center">{{ $j++ }}</td>
                    <td align="center">{{ $data->item_send_qty }}</td>
                    <td align="center">{{ countunitstr($data->count_unit) }}</td>
                    <td align="left">{{ $data->product_name }} {{ $data->size_unit . ' ' . $data->size_name . ' ' . $data->pera }}</td>
                    <td align="center">{{ number_format($data->price_item, 2) }}</td>
                    <td align="right">
                        @if ($request->price1)
                             {{ $data->product_type_id == 1 ? number_format($data->size_unit * $data->price_item * $data->item_send_qty, 2) : number_format($data->size_unit * 0.35 * $data->price_item * $data->item_send_qty, 2) }}
                        @else
                            - 
                        @endif
          
                    </td>
                </tr>
            @endforeach
            @endforeach
            @php
                $rowsToAdd = 20 - $currentChunkRows; // คำนวณจำนวนแถวที่ต้องเพิ่ม
                $j += $rowsToAdd; // ปรับค่า j ให้ถูกต้อง
                $previousChunkRows = $currentChunkRows; // อัปเดตจำนวนแถวใน chunk ปัจจุบัน
            @endphp
        
            @for ($i = $currentChunkRows + 1; $i <= 6; $i++) // แก้ไขค่าเริ่มต้นของ $i และเงื่อนไขของลูป
                <tr>
                    <td align="center" style="color: rgb(255, 255, 255);">{{ $i }}</td>
                    <td colspan="5">&nbsp;</td>
                </tr>
            @endfor
           

            <tr>
                <td colspan="4"></td>
                <td style="border:1px solid black; text-align: right;"><strong>ราคาก่อนภาษี: </strong></td>
                <td align="right" style="border:1px solid black;">{{ $order->render_price == 'No' ? 'n/a' : number_format($Grandtotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="border:1px solid black; text-align: right;"><strong>ส่วนลด:</strong></td>
                <td align="right" style="border:1px solid black;">{{ number_format($order->discount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="border:1px solid black; text-align: right;"><strong>จำนวนหลังหักส่วนลด:</strong></td>
                <td align="right" style="border:1px solid black;">{{ number_format($Grandtotal - $order->discount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="border:1px solid black; text-align: right;"><strong>ภาษีมูลค่าเพิ่ม:</strong></td>
                <td align="right" style="border:1px solid black;">{{ $order->render_price == 'No' ? 'n/a' : ($order->on_vat == 1 ? number_format($Grandtotal*7/100, 2) : '0.00') }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="border:1px solid black; text-align: right;"><strong>จำนวนเงินทั้งสิน:</strong></td>
                <td align="right" style="border:1px solid black;">{{ $order->render_price == 'No' ? 'n/a' : ($order->on_vat == 1 ? number_format($Grandtotal+($Grandtotal*7/100), 2) :  number_format($Grandtotal,2)) }}</td>
            </tr>
            
         

        </tbody>
   
        </table>

     
        





    

    <div style="font-size: 20px">
        <br>
        <span><b>หมายเหตุ :</b></span>
        <span>กรุณาตรวจสอบความถูกต้องของสินค้าและเซ็นรับสินค้าในวันที่ได้รับ หากไม่มีการตรวจสอบหรือเซ็นรับสินค้า
            ทางบริษัทขอสงวนสิทธิ์ในการรับผิดชอบต่อความผิดพลาดทุกกรณี</span>
    </div>

    <table style="width: 100%; margin-top: 20px; font-size: 16pt; border-collapse: collapse;">
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
