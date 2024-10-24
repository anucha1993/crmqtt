
<?php
include app_path()."\Providers\php-barcode-generator\src\BarcodeGenerator.php";
include app_path()."\Providers\php-barcode-generator\src\BarcodeGeneratorPNG.php";
$code = $orders->order_number;//รหัส Barcode ที่ต้องการสร้าง

$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
$border = 4;//กำหนดความหน้าของเส้น Barcode
$height = 70;//กำหนดความสูงของ Barcode

$image = $generator->getBarcode($code , $generator::TYPE_CODE_128,$border,$height);
#echo '<img src="data:image/jpeg;base64,'.base64_encode($image).'">';
?>
<div id="printBilling" style="display: none;">
  @php

    $datas_chuck = $datas->chunk(30);
  @endphp
   @for ($y = 0; $y < 3; $y++)
<div style="padding: 3rem">
 
      

  @foreach ($datas_chuck as $datas_chuck_item)
  <div class="text-left" style="font-weight:bold; display:inline-block;"><img src="data:image/jpeg;base64,<?=base64_encode($image)?>"></div>
  <div class="text-right" style="font-size: 18px;font-weight: bold;"><span class="nameBilling"></span>{{($y == 0) ? '':'(ฉบับสำเนา '.$y.')'}}</div>
  <div style="font-size: 16px;">
    <div style="display: inline-block;">xบริษัท เจริญมั่น คอนกรีต จำกัด (สำนักงานใหญ่)</div>
    <div style="display: inline-block;float: right;">ปเลขที่ {{$orders->order_number}}</div>
  </div>
  <div style="font-size: 16px;">
	<div style="display: inline-block;float: right;">น้ำหนักรวม {{$datas_sub_total}} Kgs.</div>
  </div>
  <div style="font-size: 16px;">ที่อยู่ : 95/5 หมู่ 6 ตำบลบางบัวทอง อำเภอบางบัวทอง จังหวัดนนทบุรี 11110</div>
  <div style="font-size: 16px;">โทร : 082-478-9197</div>
  <div style="font-size: 16px;">
    <div style="display: inline-block;">ได้รับเงินจาก <span style="padding-left: 1rem;">{{$customer->store_name}}</span></div>
    <div style="display: inline-block;float: right;"></div>
  </div>
  <div style="font-size: 16px;">
    <p style="padding-bottom: 0px;margin-bottom: 0.5px;">ที่อยู่ :</p>
    <p style="width: 350px;height: 42px;padding-bottom: 0px;margin-bottom: 0px;display: inline-block;">
      @if($orders->customer_type =='customer')
          {{$customer->customer_address .' ตำบล '.$customer->district_name.' อำเภอ '.$customer->amphoe_name.' จังหวัด '.$customer->province_name.' '.$customer->zip_code}}
      @else
          {{$customer->customer_address}}
      @endif
    </p>
  </div>
  <div style="font-size: 16px;padding-bottom: 1px;">
    <div style="display: inline-block;">เลขประจำตัวผู้เสียภาษี <span style="padding-left: 1rem;">{{$customer->text_number_vat}}</span></div>
  </div>

  <table style="font-size: 16px;width: 100%;" id="tableBilling">
    <thead>
      <tr>
        <td style="width: 5%;">ลำดับ</td>
        <td style="width: 10%;">จำนวน</td>
        <td style="width: 10%;">หน่วยนับ</td>
        <td >รายการสินค้า</td>
        <td style="width: 16%;">ราคาต่อหน่วย</td>
        <td style="width: 20%;">จำนวนเงินไม่รวมภาษี</td>
      </tr>
    </thead>
    <tbody>
      @php
          $j=0;
          $diffTotal = ((count($datas_chuck_item) < 30) ? (30-count($datas_chuck_item)) : 0);
      @endphp
      @foreach ($datas_chuck_item as $data)
      <tr>
          <td class="text-center">{{++$j}}</td>
          <td class="text-right">{{$data->number_order}}</td>
          <td class="text-center">{{countunitstr($data->count_unit)}}</td>
          <td><span style="padding-left:1rem">{{$data->product_name}}</span> <span style="float: right;padding-right:1rem">{{$data->size_unit.' '.$data->size_name}}</span></td>
          <td class="text-right">{{($orders->render_price == "No") ? 'disabled' : number_format($data->price,2)}}</td>
		  <!--td class="text-right">{{number_format($data->price,2)}}</td -->
          <td class="text-right">{{number_format($data->total_item,2)}}</td>
        </tr>
      @endforeach

      @for ($i = 0; $i < $diffTotal; $i++)
      <tr>
        <td class="text-center"><br></td>
        <td class="text-right"><br></td>
        <td class="text-center"><br></td>
        <td><span style="padding-left:1rem"><br></span> <span style="float: right;padding-right:1rem"></span></td>
        <td class="text-right"><br></td>
        <td class="text-right"><br></td>
      </tr>
      @endfor
    </tbody>
    <tfoot>
      <tr style="border-top: 1px solid;">
        <td colspan="4"></td>
        <td class="text-right" style="border: 1px solid;">รวมเงิน</td>
        <td class="text-right" style="border: 1px solid;">{{number_format($orders->price_all,2)}}</td>
      </tr>
      <tr>
        <td colspan="4"></td>
        <td class="text-right" style="border: 1px solid;">ส่วนลด</td>
        <td class="text-right" style="border: 1px solid;"></td>
      </tr>
      <tr>
        <td colspan="4"></td>
        <td class="text-right" style="border: 1px solid;">เงินหลังหักส่วนลด</td>
        <td class="text-right" style="border: 1px solid;"></td>
      </tr>
      <tr>
        <td colspan="4"></td>
        <td class="text-right" style="border: 1px solid;">ภาษีมูลค่าเพิ่ม 7%</td>
        <td class="text-right" style="border: 1px solid;">{{ $orders->on_vat == 1 ? number_format($orders->vat,2) : '0.00'}}</td>
      </tr>
      <tr>
        <td colspan="4"></td>
        <td class="text-right" style="border: 1px solid;">ยอดเงินสุทธิ</td>
        <td class="text-right" style="border: 1px solid;">{{number_format($orders->total,2)}}</td>
      </tr>
    </tfoot>

  </table>
  <div style="font-size: 16px;padding-top: 1.5rem;">จำนวนเงินทั้งสิ้น (ตัวอักษร) (<span class="convertText"></span>)</div>

  <div style="font-size: 16px;padding-top: 1.5rem;">
    <div style="display: inline-block;">ลงชื่อ .......................................................... ผู้รับสินค้า</div>
    <div style="display: inline-block;float: right;">ลงชื่อ ........................................................... ผู้รับเงิน</div>
  </div>

</div>
<div style="page-break-after: always"></div>
  @endforeach

  @endfor



  
</div>