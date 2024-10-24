<form method="post" action="#" id="printJS-form" class="d-none" style="font-size: 16px !important;">
  <br>
 <table style="width:100%">
   <tr>
     <th style="text-align: left; width:10%; "></th> <!-- Book No.-->
     <th style="text-align: left; width:30%; ">{{$order->order_number}}</th>
     <th style="text-align: center; width:20%; "></th>
     <th style="text-align: left; width:17%; "></th> <!-- เลขที่-->
     <th style="text-align: left; width:23%; ">{{$order->order_number}}</th>
   </tr>
 </table>
 <table style="width:100%" >
   <tr>
     <td style="text-align: left;"></td> <!--ชื่อ Line-->
     <td colspan="3" style="text-align: left;"></td>
   </tr>
   <tr>
     <td style="text-align: left;"></td> <!--ชื่อบริษัท/ร้าน-->
     <td colspan="3" style="text-align: left;"> {{$customer->store_name}}</td>
   </tr>
   <tr>
     <td style="text-align: left; width:10%"> </td> <!--ชื่อผู้ติดต่อ/ร้าน-->
     <td style="text-align: left; width:56.6%">{{$customer->customer_name}}</td>
     <td style="text-align: left; width:10%"></td> <!--เบอร์โทร.-->
     <td style="text-align: left; width:23.3%">{{$customer->customer_phone}}</td>
   </tr>
   <tr>
     <td style="text-align: left; width:10%"></td> <!--ชื่อผู้ติดต่อ-->
     <td style="text-align: left; width:56.6%"></td>
     <td style="text-align: left; width:10%"></td> <!--เบอร์โทร-->
     <td style="text-align: left; width:23.3%"></td>
   </tr>
   <tr>
     <td style="text-align: left; width:10%"></td> <!-- ชื่อหน้างาน-->
     <td style="text-align: left; width:56.6%"></td>
     <td style="text-align: left; width:10%"></td> <!--เบอร์โทร-->
     <td style="text-align: left; width:23.3%"></td>
   </tr>
 </table>
 <table style="width:100%" >
   <tr>
     <th style="width:33.3%; "></th> <!-- DELIVERY BILL-->
     <th style="width:33.3%; font-size: 24px !important;"></th> <!-- ใบส่งของ-->
     <th style="width:33.3%; "></th> <!-- DELIVERY BILL-->
   </tr>
 </table>
 <table style="width:100%">
   <tr>
     <td style="text-align: left; width:10%"></td> <!-- ชื่อ-->
     <td style="text-align: left; width:56.6%"></td>
     <td style="text-align: left; width:10%"></td> <!-- วันที่-->
     <td style="text-align: left; width:23.3%">{{date('d/m/Y H:i:s',strtotime($quotation->created_at))}}</td>
   </tr>
   <tr>
     @if ($quotation->customer_type =='customer')
      <td style="text-align: left; width:10%"></td> <!-- ที่อยู่  -->
      <td style="text-align: left; width:56.6%">{{$customer->customer_address .' ตำบล '.$customer->district_name.' อำเภอ '.$customer->amphoe_name.' จังหวัด '.$customer->province_name.' '.$customer->zip_code}}</td>
     @else
      <td style="text-align: left; width:10%"></td> <!-- ที่อยู่  -->
      <td style="text-align: left; width:56.6%">{{$customer->customer_address}}</td>
     @endif

     <td style="text-align: left; width:10%"></td> <!-- โทร.-->
     <td style="text-align: left; width:23.3%">{{$customer->customer_phone}}</td>
   </tr>
 </table>

 <table style="width:100%">
     <tr>
       <th></th><!-- จำนวน -->
       <th></th><!-- รายการ -->
       <th></th><!-- หน่วยละ -->
       <th colspan="2"></th><!-- จำนวนเงิน -->
     </tr>
    @foreach ($datas as $data)
     <tr>
       <td style="width:10%;">{{number_format($data->item_number_order)}}</td>
       <td style="width:60%;">{{$data->product_name.' '.$data->size_unit.' '.$data->size_name}}</td>
       <td style="width:10%;">{{number_format($data->price_item,2)}}</td>
       <td style="width:10%;">{{number_format($data->total_item_all,2)}}</td>
       <td style="width:10%;"></td>
     </tr>
    @endforeach
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
 </table>

 <table style="width:100%">
   <tr>
     <td colspan="2"></td> <!-- โปรดตรวจรัยสินค้าให้ถูกต้อง มิฉะนั้นจะไม่รับผิดชอบ และเมื่อชำระเงินแล้ว จะออกใบเสร็จรับเงินถูกต้องตามกฎหมาย-->
   </tr>
   <tr>
     <td style="text-align: left; width:10%"></td><!-- ผู้รับของ-->
     <td style="text-align: left; width:40%"></td>
     <td style="text-align: left; width:10%"></td><!-- ผู้ส่งของ-->
     <td style="text-align: left; width:40%"></td>
   </tr>
 </table>

</form>
