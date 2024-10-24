

<style>
     div {
       font-weight: bold;
     }
</style>
<?php
$sentences_on_quotation = "มัดจำสินค้าเพื่อคอนเฟริมการจัดส่ง ตรวจสอบสินค้าให้ถูกต้องก่อนเซ็นรับ มิฉะนั้นจะไม่รับผิดชอบทุกกรณี";
?>
<?php
//require_once  app_path()."\Providers\php-barcode-generator\src\BarcodeGenerator.php";
//require_once  app_path()."\Providers\php-barcode-generator\src\BarcodeGeneratorPNG.php";

require_once app_path() . "/Providers/php-barcode-generator/src/BarcodeGenerator.php";
require_once app_path()."/Providers/php-barcode-generator/src/BarcodeGeneratorPNG.php";



#$code = $orders->order_number."-".$deliverys->order_delivery_number;//รหัส Barcode ที่ต้องการสร้าง
$code = $deliverys->order_delivery_number;//รหัส Barcode ที่ต้องการสร้าง
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
$border = 4;//กำหนดความหน้าของเส้น Barcode
$height = 70;//กำหนดความสูงของ Barcode

$image = $generator->getBarcode($code , $generator::TYPE_CODE_128,$border,$height);
#echo '<img src="data:image/jpeg;base64,'.base64_encode($image).'">';
?>
<div id="printBillings" style="display: none;">
@php
  $datas_chuck = $datas->chunk(30);
  //dd($datas);
@endphp

 @for ($y = 0; $y < 1; $y++)
<div style="padding: 3rem">

@foreach ($datas_chuck as $datas_chuck_item)
<table width=100% border=1>
<tr  style="print-color-adjust: exact; background:#FFFFFF;">
<td style="padding:30px;">
<div id="title_header_delivery" style="font-size: 18px; font-weight: bold;padding-bottom:1rem; width: 100%">
	<div class="text-left" style="font-weight:bold; display:inline-block;"><img src="data:image/jpeg;base64,<?=base64_encode($image)?>"></div>
	<div class="text-right" style="font-weight:bold; display:inline-block;"><font color=black size=10px>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ใบส่งของ</font></div>
</div>
<div style="font-size: 16px;padding-bottom:1rem" class="text-center">
  <div style="font-weight: bold; display: inline-block;width: 30%;"><font color=black>Book No. </font></div>
  <div style="font-weight: bold;display: inline-block;width: 10%;"><font color=black>Billno: {{$pirntCount->print_count}} </font></div>
  <div style="font-weight: bold;display: inline-block;width: 50%;"><font color=black>เลขที่ {{$orders->order_number}} &nbsp; เลขที่บิลย่อย {{$datas_chunk[0][0]->order_delivery_number}}</font></div>
  {{-- <div style="font-weight: bold;display: inline-block;width: 30%;"><font color=black>เลขที่บิลย่อย {{$datas_chunk[0][0]->order_delivery_number}}</font></div> --}}
</div>
<div style="font-size: 16px;">
	<div style="font-weight: bold; display:inline-block;float: right;">น้ำหนักรวม {{$datas_sub_total_delivery}} Kgs.</div>
  </div>
</td>
</tr>
</table>
<p>
<div style="font-size: 16px;padding-bottom:1rem">
  <div style="font-weight: bold; display: inline-block;width: 7%;">ชื่อ Line </div>
  <div style="display: inline-block;width: 92%;border-bottom: 1px dotted;" > - </div>
</div>
<div style="font-size: 16px;padding-bottom:1rem">
  <div style="font-weight: bold; display: inline-block;width: 11%;">ชื่อบริษัท/ร้าน </div>
  <div style="font-weight: bold; display: inline-block;width: 88%;border-bottom: 1px dotted;" > {{$customer->store_name}} </div>
</div>
<div style="font-size: 16px;padding-bottom:1rem">
  <div style="font-weight: bold; display: inline-block;width: 12%;">ชื่อผู้ติดต่อ/ร้าน </div>
  <div style="font-weight: bold; display: inline-block;width: 70%;border-bottom: 1px dotted;" > - </div>
  <div style="font-weight: bold; display: inline-block;width:4%">วันที่ </div>
  <div style="font-weight: bold; display: inline-block;width:12%;border-bottom: 1px dotted;" > {{date('d/m/Y',strtotime($deliverys->date_send))}} </div>
</div>
<div style="font-size: 16px;padding-bottom:1rem">
  <div style="font-weight: bold; display: inline-block;width: 8%;">ชื่อผู้ติดต่อ </div>
  <div style="font-weight: bold; display: inline-block;width: 91%;border-bottom: 1px dotted;" > {{--$customer->customer_name--}} {{$location_contact_person_name}}</div>
</div>
<div style="font-size: 16px;padding-bottom:1rem">
  <div style="font-weight: bold; display: inline-block;width: 12%;">ที่อยู่หน้างาน </div>
  <div style="font-weight: bold; display: inline-block;width: 60%;border-bottom: 1px dotted;" >{{$location_name}}
	  {{--@if($orders->customer_type =='customer')
        {{$customer->customer_address .' ตำบล '.$customer->district_name.' อำเภอ '.$customer->amphoe_name.' จังหวัด '.$customer->province_name.' '.$customer->zip_code}}
    @else
        {{$customer->customer_address}}
	  @endif --}}
  </div>
  <div style="font-weight: bold; display: inline-block;width: 12%;">เบอร์โทรศัพท์ติดต่อลูกค้า </div>
  <div style="font-weight: bold; display: inline-block;width:14%;border-bottom: 1px dotted;" > {{--$customer->customer_phone--}} {{$location_contact_person_phone_no}}</div>
</div>

	<!--div style="font-size: 16px;padding-bottom: 1px;">
		<div >ผู้ติดต่อของที่อยู่จัดส่งปัจจุบัน: <span style="padding-left: 1rem;">{{$location_contact_person_name}}</span></div>
	</div>
	<div style="font-size: 16px;padding-bottom: 1px;">
		<div >เบอร์โทรศัพท์ของผู้ติดต่อหน้างาน: <span style="padding-left: 1rem;">{{$location_contact_person_phone_no}}</span></div>
	</div-->
{{-- <div style="font-size: 16px;">
  <div style="display: inline-block;">บริษัท เจริญมั่น คอนกรีต จำกัด (สำนักงานใหญ่)</div>
  <div style="display: inline-block;float: right;">เลขที่ {{$orders->order_number}}</div>
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
</div> --}}

<table style="font-size: 16px;width: 100%; border:1px solid black;" id="tableBilling">
  <thead>
    <tr style="color:white; print-color-adjust: exact; background:#011c47;">
      <td style="color:white; width: 5%; font-weight: bold;padding:5px">ลำดับ</td>
      <td style="width: 10%;font-weight: bold;">จำนวน</td>
      <td style="width: 10%;font-weight: bold;">หน่วยนับ</td>
      <td style="font-weight: bold;">รายการสินค้า</td>
      <td style="width: 12%;font-weight: bold;">ราคาต่อหน่วย</td>
      <td style="width: 20%;font-weight: bold;">จำนวนเงินไม่รวมภาษี</td>
    </tr>
  </thead>
  <tbody>
    @php
        $j=0;
        $diffTotal = ((count($datas_chuck_item) < 30) ? (30-count($datas_chuck_item)-6) : 0);
    @endphp
    @foreach ($datas_chuck_item as $data)
	<?php
		$test_pera = ($data->product_type_id == 1) ? number_format(($data->size_unit*$data->price_item*$data->item_send_qty),2) : number_format(($data->size_unit*0.35*$data->price_item*$data->item_send_qty),2);
		if ($test_pera == 0) { continue;}
	?>
    <tr>
        <td style="font-weight: bold;" class="text-center">{{++$j}}</td>
		{{--<td style="font-weight: bold;" class="text-right">{{$data->item_number_order}}</td>--}}
		<td style="font-weight: bold;" class="text-right">{{$data->item_send_qty}}</td>
        <td style="font-weight: bold;" class="text-center">{{countunitstr($data->count_unit)}}</td>
        <td style="font-weight: bold;"> <span style="padding-left:1rem">{{$data->product_name}} {{$data->size_unit.' '.$data->size_name.' '.$data->pera}}</span></td>
		<!--td style="font-weight: bold;"> <span style="padding-left:1rem">{{$data->product_name}}</span> <span style="float: right;padding-right:1rem">{{$data->size_unit.' '.$data->size_name}}</span></td-->

        <td style="font-weight: bold;" class="text-right">{{($data->order_delivery_render_price == "N") ? 'n/a' : number_format($data->price,2)}}</td>
		<!--td style="font-weight: bold;" class="text-right">{{number_format($data->price_item,2)}}</td-->
		<?php
			$total_price_without_tax = ($data->product_type_id == 1) ? number_format(($data->size_unit*$data->price_item*$data->item_send_qty),2) : number_format(($data->size_unit*0.35*$data->price_item*$data->item_send_qty),2);
		?>
        <td style="font-weight: bold;"  class="text-right">{{($data->order_delivery_render_price == "N") ? 'n/a' : $total_price_without_tax }}</td>
      </tr>
    @endforeach

    @for ($i = 0; $i < ($diffTotal - 1); $i++)
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
      <td class="text-right" style="font-weight: bold; border: 1px solid;">รวมเงิน</td>
      <td style="font-weight: bold;" class="text-right total_all_bill" style="border: 1px solid;"></td>
    </tr>

    {{-- <tr>
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
    </tr> --}}
  </tfoot>

</table>
<div style="font-weight: bold; color:white; print-color-adjust: exact; background:grey; font-size: 16px;padding-top: 1.5rem;">จำนวนเงินทั้งสิ้น (ตัวอักษร) (<span class="convertText"></span>)</div>
<br>
  <div class="row pt30 pb10">
	<label class="number_text col-sm-12 text-left">{{$sentences_on_quotation}}</label>
  </div>
</p>


<div class="row">
                        <label class="number_text col-sm-12 text-left">หมายเหตุ {{$data_for_remarks->remark_1}}</label>
                    </div>
					<div class="row">
                        <label class="number_text col-sm-12 text-left">{{$data_for_remarks->remark_2}}</label>
                    </div>
					<div class="row">
                        <label class="number_text col-sm-12 text-left">{{$data_for_remarks->remark_3}}</label>
                    </div>
					<div class="row">
                        <label class="number_text col-sm-12 text-left">{{$data_for_remarks->remark_4}}</label>
                    </div>
					<div class="row">
                        <label class="number_text col-sm-12 text-left">{{$data_for_remarks->remark_5}}</label>
                    </div>

<div style="font-size: 16px;padding-top: 1.5rem;">
  <div style="font-weight: bold; display: inline-block;">ลงชื่อ .......................................................... ผู้รับสินค้า</div>
  <div style="font-weight: bold; display: inline-block;float: right;">ลงชื่อ ........................................................... ผู้ส่งของ</div>
</div>

</div>
<div style="page-break-after: always"></div>
@endforeach

@endfor
</div>


  