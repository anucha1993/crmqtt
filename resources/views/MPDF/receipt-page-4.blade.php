<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ใบกำกับภาษี/ใบเสร็จรับเงิน</title>
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
            <p style="margin: 0; padding: 0; font-size: 12pt;">
                <strong>วันที่:</strong> {{ date('d/m/Y', strtotime($order->created_at)) }}
            </p>
        
            <!-- ข้อความแสดงน้ำหนักรวม -->
            <p style="margin: 0; padding: 0; font-size: 12pt;">
                <strong>น้ำหนักรวม:</strong> {{$datas_sub_total}} Kgs.
            </p>
        
            <!-- ส่วนของ QR Code -->
            <div style="margin-top: 0px; text-align: right;">
                <img src="{{ $qrCodeImage }}" alt="QR Code" style="width: 120px; margin-right: -25px">
                <p style="margin: -10px; font-size: 14pt;"><strong>เลขที่:</strong> {{ $order->order_number }}</p>
            </div>
        </div>
        
        
        

        {{-- <div style="width: 35%; float: right; text-align: right;">
            <p style="margin: 0; font-size: 12pt; text-align: right; padding-right: 0;">
                <strong>หน้า/ที่:</strong> {PAGENO}/{nbpg}
            </p>
          
            <p style="margin: 0; font-size: 12pt;"><strong>วันที่:</strong>
                {{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                <p style="margin: 0px; font-size: 12pt;"><strong>น้ำหนักรวม:</strong> {{$datas_sub_total}} Kgs.</p>
            <div style="margin-top: 0px; text-align: right;">
                <img src="{{ $qrCodeImage }}" alt="QR Code" style="width: 120px; margin-right: -25px">
                <p style="margin: -10px; font-size: 14pt;"><strong>เลขที่:</strong> {{ $order->order_number }}</p>
            </div>

        </div> --}}




        <div style="width: 65%; float: left;">
            <p style="margin: 0; font-size: 14pt;"><b>ข้อมูลลูกค้า</b></p>
            <p style="margin: 0; font-size: 14pt;">{{ $order->customer->store_name }}</p>
            <p style="margin: 0; font-size: 14pt;">ที่อยู่ {{ $order->customer->customer_address }}</p>
            <p style="margin: 0; font-size: 14pt;">เลขประจำตัวผู้เสียภาษีอากร: {{ $order->customer->text_number_vat }}
            </p>
        </div>

        <div style="clear: both;"></div>
    </div>

    <div class="invoice-title">
        ใบเสร็จรับเงิน (ต้นฉบับ)
    </div>
    <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14pt;">
        <thead>
            <tr>
                <th style="width: 10px; border: 1px solid black; padding: 1px; text-align: center;">ลำดับ</th>
                <th style="width: 10px; border: 1px solid black; padding: 1px; text-align: center;">จำนวน</th>
                <th style="width: 10px; border: 1px solid black; padding: 1px; text-align: center;">หน่วย</th>
                <th style="width: 300px; border: 1px solid black; padding: 1px; text-align: center;">รายการสินค้า</th>
                </th>
                <th style="width: 10px; border: 1px solid black; padding: 1px; text-align: center;">ราคา/หน่วย</th>
                <th style="width: 10px; border: 1px solid black; padding: 1px; text-align: center;">จำนนวนไม่รวมภาษี
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $key => $item)
                <tr>
                    <td
                        style="border-left: 1px solid black; border-right: 1px solid black; padding: 5px; text-align: center;">
                        {{ $key + 1 }}</td>
                    <td style="border-right: 1px solid black; padding: 5px; text-align: center;">
                        {{ number_format($item->number_order) }}</td>
                    <td style="border-right: 1px solid black; padding: 5px; text-align: center;">
                        {{ countunitstr($item->count_unit) }}</td>
                    <td style="border-right: 1px solid black; padding: 5px; text-left: center;">
                        {{ $item->size_unit . ' ' . $item->size_name }} {{ $item->pera }}</td>
                    <td style="border-right: 1px solid black; padding: 5px; text-align: right;">
                        {{ $item->render_price == 'No' ? 'n/a' : number_format($item->price, 2) }}</td>
                    <td style="border-right: 1px solid black; padding: 5px; text-align: right;">
                        {{ $item->render_price == 'No' ? 'n/a' : number_format($item->total_item, 2) }}</td>
                </tr>
            @endforeach
            {{-- <tr>
                <td style="border-left: 1px solid black; border-right: 1px solid black; padding: 5px; text-align: center;">2</td>
                <td style="border-right: 1px solid black; padding: 5px; text-align: center;">7.00</td>
                <td style="border-right: 1px solid black; padding: 5px; text-align: left;">ต้น</td>
                <td style="border-right: 1px solid black; padding: 5px; text-left: center;">เสาร์เข็มหกเหลี่ยม (3.00 เมตร)</td>
                <td style="border-right: 1px solid black; padding: 5px; text-align: right;">68.00</td>
                <td style="border-right: 1px solid black; padding: 5px; text-align: right;">14,28.00</td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black; border-right: 1px solid black; padding: 5px; text-align: center;">3</td>
                <td style="border-right: 1px solid black; padding: 5px; text-align: center;">7.00</td>
                <td style="border-right: 1px solid black; padding: 5px; text-align: left;">ต้น</td>
                <td style="border-right: 1px solid black; padding: 5px; text-left: center;">เสาร์เข็มหกเหลี่ยม (3.00 เมตร)</td>
                <td style="border-right: 1px solid black; padding: 5px; text-align: right;">68.00</td>
                <td style="border-right: 1px solid black; padding: 5px; text-align: right;">14,28.00</td>
            </tr> --}}

            <tr>
                <td
                    style="border-left: 1px solid black; border-right: 1px solid black; padding: 5px; text-align: center; border-bottom: 1px solid black;">
                </td>
                <td
                    style="border-right: 1px solid black; border-bottom: 1px solid black; padding: 5px; text-align: center;">
                </td>
                <td
                    style="border-right: 1px solid black; border-bottom: 1px solid black; padding: 5px; text-align: left;">
                </td>
                <td
                    style="border-right: 1px solid black; border-bottom: 1px solid black; padding: 5px; text-align: center;">
                </td>
                <td
                    style="border-right: 1px solid black; border-bottom: 1px solid black; padding: 5px; text-align: right;">
                </td>
                <td
                    style="border-right: 1px solid black; border-bottom: 1px solid black; padding: 5px; text-align: right;">
                </td>
            </tr>

        </tbody>
    </table>


    <table style="width: 100%; margin-top: 20px; font-size: 14pt; border-collapse: collapse;">

        <tr>
            <!-- คอลัมน์ซ้าย -->

            <td style="width: 60%; vertical-align: top; padding-right: 20px;">
                <p><strong>ประเภทการชำระเงิน : </strong> {!! PatmentType($order->payment_type) !!}</p>
                @if ($order->payment_type == '1')
                    {{-- <p><strong>จำนวนเงินทั้งสิ้นจากบิลหลัก : </strong> 15,000 บาท</p> --}}
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
    </table>

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
