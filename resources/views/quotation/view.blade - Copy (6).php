@extends('layout.master')
@push('plugin-styles')

@endpush
@push('style')
    <style>
        .header-title{
            font-size: 12px;
            margin-top: 10px;
        }
        input.text-right{
            text-align: right;
        }
        .table-width{
            width: 1500px;
        }
        .m-t-15{
            margin-top: 15px;
        }
        button.btn.btn-apped {
            width: 100%;
            border: 1px solid #666983;
            border-style: dashed;
            color: #666983;
        }
        .money-color{
            color: #4C9FFE;
        }
        p.warning-text {
            font-size: 11px;
            margin-bottom: 2px;
        }
        div.box-note{
            padding-top: 15px;
            padding-bottom: 15px;
            background:#F6F8FB;
        }
        div.box-note-status{
            padding-top: 15px;
            padding-bottom: 5px;
            background:#F6F8FB;
        }
        button.btn.btn-fw {
            padding: 10px;
            margin-left: 15px;
        }
        .btn-secondary {
            color: #FFFF;
            background-color: #656566;
            border-color: #656566;
        }
        select.form-control.form-control-sm {
            font-size: 11px;
            padding: 2px;
        }
        div.d-flex-s{
            display: flex;
        }
        button.btn.btn-create-order.btn-fw {
            color: #FFFF;
            background: #FF7D00;
        }
        option.disabled{
            background: #efefef;
            color: #A9A9A9;
        }
        a.btn.btn-secondary.btn-fw {
            padding: 10px;
        }

        .content-wrapper.print-pdf {
            background: #FFFF;
        }
        .grid-margin-pdf{
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .color-title{
            color: #33467A;
        }
        .name-crmqtt{
            font-size: 25px;
            font-weight: 1000;
        }
        .detail-crmqtt{
            font-size: 18px;
        }
        .pdf-title{
            font-size: 22px;
        }
        table.table-pdf>tbody>tr>th,
        table.table-pdf>tbody>tr>td {
            border: 2px solid black;
            border-collapse: collapse;
        }
        table.table-pdf>tbody>tr>th{
            padding: 7px;
        }
        table.table-pdf{
            margin-top: 20px;
        }
        table.table-pdf>tbody>tr>td.colspan {
            border: none;
        }
        td.table-conclude {
            background: #c6d9f6;
        }
        .row.pt30{
            padding-top: 30px
        }
        .row.pb10{
            padding-bottom: 10px;
        }
        .form-print-pdf{
            display: none;
        }
        table.table-pdf>tbody>tr>td.table-nulldata{
            padding: 15px;
        }

        @media (max-width: 576px) {
            div.box-note-status,div.box-note {
                padding-right: 15px;
            }
        }

        @media print {
            body {
                background: #FFFF;
            }
        }
    </style>
@endpush
@section('content')

<script>
	i = 0;
</script>

<div class="row">
    {!!breadcrumb($breadcrumb)!!}
    <div class="col-lg-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="form-inline">
                <div class="col-sm-6">
                    <h3 class="title-page">ใบเสนอราคา {{$quotation->quotation_number}}</h3>
                </div>
                <div class="text-right col-sm-6">
                    {{-- <a href="{{url('quotation/add')}}" class="btn btn-add mb-2" id="btn_expense"><i class="fa fa-plus" aria-hidden="true"></i> สร้างใบเสนอราคา</a> --}}
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">วันที่สร้างใบเสนอราคา</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text"  disabled value="{{date('d/m/Y H:i:s',strtotime($quotation->created_at))}}">
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">ชื่อร้านค้า</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text" disabled  value="{{$customer->store_name}}">
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">เลขประจำตัวผู้เสียภาษี</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text" disabled  value="{{$customer->text_number_vat}}">
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">Pocket Money</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text"  disabled  value="{{$quotation->customer_type == 'customer' ? (!empty($customer->PocketMoney) ? $customer->PocketMoney->pocket_money : '0.00') : '0.00'}}">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">ผู้ติดต่อ</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text"  disabled value="{{$customer->customer_name}}">
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">เบอร์โทรศัพท์</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text" disabled  value="{{$customer->customer_phone}}">
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">อีเมล์</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text" disabled  value="{{$customer->customer_mail}}">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group col-sm-12 input-header">
                    <label class="col-sm-12 header-title">ที่อยู่</label>
                    <div class="col-sm-12">
                        @if($quotation->customer_type =='customer')
                        <input class="form-control" type="text"  disabled value="{{$customer->customer_address .' ตำบล '.$customer->district_name.' อำเภอ '.$customer->amphoe_name.' จังหวัด '.$customer->province_name.' '.$customer->zip_code}}">
                        @else
                        <input class="form-control" type="text"  disabled value="{{$customer->customer_address}}">
                        @endif
                    </div>
                </div>
            </div>
			<div class="form-row">
                <div class="input-group col-sm-12 input-header">
                    <label class="col-sm-12 header-title">ที่อยู่จัดส่ง</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text"  disabled value="{{$datas_quotation_pera->location}}">
                    </div>
                </div>
            </div>
			<div class="form-row">
                <div class="input-group col-sm-12 input-header">
                    <label class="col-sm-12 header-title">ชื่อผู้ติดต่อหน้างาน</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text"  disabled value="{{$datas_quotation_pera->onsite_contact_name}}">
                    </div>
                </div>
            </div>
            <form id="updatequotation" action="{{route('quotation.update')}}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="quotationid" value="{{$quotation->id}}">
                <div class="table-responsive" style="overflow-x:auto;">
                <table class="table table-width">
                    <thead class="head-table">
                    <tr>
                        <th style="width: 50px;"> ลำดับ </th>
                        <th style="width: 100px;"> ชื่อสินค้า/รายละเอียดสินค้า </th>
                        <th style="width: 100px;"> ประเภทสินค้า </th>
                        <th style="width: 100px;"> ความยาว </th>
                        <th style="width: 100px;"> </th>
                        <th style="width: 100px;"> ราคาต่อหน่วย </th>
                        <th style="width: 100px;"> จำนวน </th>
                        <th style="width: 100px;"> หน่วยนับ </th>
                        <th style="width: 100px;"> จำนวนเงิน </th>
                        <th style="width: 100px;"> หมายเหตุ </th>
                        <th style="width: 50px;">  </th>
                    </tr>
                    </thead>
                    <tbody id="datatable">
	<?php $j_pera = 0 ?>
                        @foreach ($datas as $key => $data)
							<?php $j_pera++; ?>
                            <tr class="column" id="row_{{$key+1}}" data-id="{{$key+1}}">
                                <td><label class="number_on" id="number_{{$key+1}}">{{$i++}}</label></td>
                                <td>
                                    <input type="text" name="product_name[]" value="{{$data->product_name}}" class="form-control form-control-sm" {{$quotation->status != '0' ? 'disabled' : ''}}>
									<input type="hidden" name="product_id[]" id="product_id[]" value="{{$data->product_id}}" class="form-control form-control-sm text-right">
								</td>
                                <td>
                                    <select name="product_type_id[]" class="form-control form-control-sm select_product_type_id_{{$key+1}}" onchange="append.productsize({{$key+1}});"  required  data-product-type="{{$key+1}}" {{$quotation->status != '0' ? 'disabled' : ''}}>
                                        <option value="">-เลือก-</option>
                                            @foreach ($producttypes as $producttype)
                                                <option {{$data->product_type_id == $producttype->id ? 'selected' : ''}} value="{{$producttype->id}}">{{$producttype->product_type_name}}</option>
                                            @endforeach
                                    </select> 
									<?php 
										$selected_1_4 = "";
										$selected_1_5 = "";
										$selected_1_6 = "";
										$selected_1_7 = "";
										
										switch (mb_substr($data->pera,0,5,'UTF-8')) {
										  case "ลวด 4":
											$selected_1_4 = "selected";
											break;
										  case "ลวด 5":
											$selected_1_5 = "selected";
											break;
										  case "ลวด 6":
											$selected_1_6 = "selected";
											break;
										  case "ลวด 7":
											$selected_1_7 = "selected";
											break;
										  default:
											//code block
										}
									?>
									<select name="pera[]" class="form-control form-control-sm" required>
										<option value="">-เลือก-</option>
										<option value="ลวด 4 เส้น" {{$selected_1_4}}>-ลวด 4 เส้น-</option>
										<option value="ลวด 5 เส้น" {{$selected_1_5}}>-ลวด 5 เส้น-</option>
										<option value="ลวด 6 เส้น" {{$selected_1_6}}>-ลวด 6 เส้น-</option>
										<option value="ลวด 7 เส้น" {{$selected_1_7}}>-ลวด 7 เส้น-</option>
                                    </select> 
									<?php 
										$selected_2_1 = "";
										$selected_2_2 = "";
										
										switch (mb_substr($data->pera,6,15,'UTF-8')) {
										  case "":
											$selected_2_1 = "selected";
											break;
										  case "เหล็กข้าง":
											$selected_2_2 = "selected";
											break;
										  default:
											//code block
										}
									?>
									<select name="pera_2[]" class="form-control form-control-sm">
                                        <option value="" {{$selected_2_1}}>ไม่ show เหล็กข้าง</option>
                                        <option value="เหล็กข้าง" {{$selected_2_2}}>show เหล็กข้าง</option>
                                    </select> 
                                </td>
                                <td style="width:8%">
                                    <input type="text" name="size_unit[]" value="{{$data->size_unit}}" data-type="number" class="form-control form-control-sm  size_unit_{{$key+1}}" {{$quotation->status != '0' ? 'disabled' : ''}} onkeyup="append.summoney({{$key+1}})">
                                    <input type="hidden" name="size_unit_count[]" value="{{$data->size_unit}}" class="form-control form-control-sm size_unit_count_{{$key+1}}" >
                                </td>
                                <td>
                                    <select name="product_size_id[]" class="form-control form-control-sm select_product_size_id_{{$key+1}}" onchange="append.summoney({{$key+1}})"  required  data-product-size="{{$key+1}}" {{$quotation->status != '0' ? 'disabled' : ''}}>
                                        <option value="">-เลือก-</option>
                                            @foreach ($productsizes as $producsize)
                                                @if($producsize->prosuct_type_id == $data->product_type_id)
                                                    <option {{$data->product_size_id == $producsize->id ? 'selected' : ''}} value="{{$producsize->id}}" data-size="{{$producsize->product_size_name}}">{{$producsize->product_size_name}}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="price[]" value="{{number_format($data->price,2)}}" data-type="currency" class="form-control form-control-sm text-right price_{{$key+1}}" {{$quotation->status != '0' ? 'disabled' : ''}} onkeyup="append.summoney({{$key+1}})">
                                </td>
                                <td>
                                    <input type="text" name="number_order[]" value="{{$data->number_order}}"  data-type="number" class="form-control form-control-sm text-right number_order_{{$key+1}}" {{$quotation->status != '0' ? 'disabled' : ''}} onkeyup="append.summoney({{$key+1}})">
                                </td>
                                <td>
                                    <select name="countunit[]" class="form-control form-control-sm select_countunit_{{$key+1}}"  required  data-countunit="{{$key+1}}" {{$quotation->status != '0' ? 'disabled' : ''}}>
                                        <option value="">-เลือก-</option>
                                        @foreach (countunit() as $countunit)
                                        <option {{$data->count_unit == $countunit->id ? 'selected' : ''}}  value="{{$countunit->id}}">{{$countunit->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="total_item[]" readonly value="{{number_format($data->total_item,2)}}" class="form-control form-control-sm total_item text-right total_item_{{$key+1}}" {{$quotation->status != '0' ? 'disabled' : ''}}>
                                </td>
                                <td>
                                    <input type="text" name="note[]" value="{{$data->note}}" class="form-control form-control-sm text-right" {{$quotation->status != '0' ? 'disabled' : ''}}>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-icons btn-rounded removerow_{{$key+1}}" onclick="append.removerow({{$key+1}})" {{$quotation->status != '0' ? 'disabled' : ''}}><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div class="col-sm-12 m-t-15">
                    <button class="btn btn-apped" {{$quotation->status != '0' ? 'disabled' : ''}} onclick="append.appendrow()">+ เพิ่มรายการ</button>
                </div>
                <div class="row">
                    <label class="col-sm-10 text-right m-t-15 ">ยอดรวมสั่งซื้อ</label>
                    <label class="col-sm-2 text-right m-t-15 money-color" id="total_all">0.00</label>
                </div>
                <div class="row">
                    <p class="col-sm-12 text-right warning-text"><span class="red">*</span> หมายเหตุ ยังไม่รวามภาษีมูลค่าเพิ่ม 7%</p>
                    <p class="col-sm-12 text-right warning-text">หากต้องการภาษีมูลค่าเพิ่มสามารถเลือกได้ที่บิลหลัก</p>
                </div>
				<div class="row">
					<div class="col-sm-12">
							<label class="col-sm-6 header-title">หมายเหตุ ที่ 1  {{$quotation->remark_1}}</label>
							<label class="col-sm-6 header-title">หมายเหตุ ที่ 2 {{$quotation->remark_2}}</label>
							<label class="col-sm-6 header-title">หมายเหตุ ที่ 3  {{$quotation->remark_3}}</label>
							<label class="col-sm-6 header-title">หมายเหตุ ที่ 4  {{$quotation->remark_4}}</label>
							<label class="col-sm-6 header-title">หมายเหตุ ที่ 5  {{$quotation->remark_5}}</label>
					</div>
				</div>
                <div class="row"   {!! $quotation->status == 1 ? 'style="display: none;"' : ''!!}>
                    <div class="col-sm-12">
                        <div class="crad">
                            <div class="card-body">
                                <div class="d-flex flex-row justify-content-center align-items box-note-status">
                                    <label class="col-sm-2 text-right">xสถานะ <span class="red">*</span></label>
                                    <select name="status"  {{isset($order) && $order->status != '0' ? 'disabled' : ''}}  onchange="changestatus()" class="col-sm-2 form-control form-control-sm changestatus">
                                        <option value="">สถานะทั้งหมด</option>
                                        <option {{$quotation->status == '0' ? 'selected' : ''}} value="0">รอยืนยัน</option>
                                        <option {{$quotation->status == '1' ? 'selected' : ''}} value="1">ยืนยัน</option>
                                        <option {{$quotation->status == '2' ? 'selected' : ''}} value="2">ยกเลิก</option>
                                    </select>
									<?php
									?>
									<label class="col-sm-2 text-right">สาเหตุการยกเลิก : <span class="red">*</span></label>
									<textarea cols=40 rows=8  class="col-sm-4 form-control form-control-sm" name="note" value="{{$quotation->note}}" placeholder="สาเหตุการยกเลิก..." disabled></textarea>
                                </div>
                                <!--div class="d-flex-s flex-row justify-content-center align-items box-note">
                                    <p class="col-sm-2 text-right">หมายเหตุ :</p>
                                    <input class="col-sm-6 form-control form-control-sm" type="text" name="note" value="{{$quotation->note}}" placeholder="กรอกหมายเหตุ...">
                                </div-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"  {!! $quotation->status == 1 ? 'style="display: none;"' : ''!!}>
                    <div class="col-sm-12">
                        <div class="d-flex flex-row justify-content-center align-items">
                          <button type="button" onclick="orderPrintPDF()" class="btn btn-print btn-fw"> <i class="fa fa-print" aria-hidden="true"></i> print ใบเสนอราคา</button>
                          <button type="button" class="btn btn-secondary btn-fw" onclick="location.href='{{url('quotation')}}';" >ยกเลิก</button>
                            <!-- <a href="{{url('quotation')}}" class="btn btn-secondary btn-fw">ยกเลิก</a> -->
                            <button type="button" onclick="update()" class="btn btn-primary btn-fw">xยืนยันทำรายการ</button>
                            <!-- <button type="button" onclick="changquotation({{$quotation->id}})" {{isset($order) ? 'disabled' : ''}} class="btn btn-primary btn-fw">ยืนยันทำรายการ</button> -->

                        </div>
                    </div>
                </div>
            </form>

            <div class="form-create_order" {!!  $quotation->status != 1 ? 'style="display: none;"' : ''!!}>
                <form action="{{route('orders.updatequotation')}}" method="POST" id="updatequotationorders">
                    {{ csrf_field() }}
                    <input type="hidden" name="quotationid" value="{{$quotation->id}}">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="crad">
                                <div class="card-body">
                                    <div class="d-flex flex-row justify-content-center align-items box-note-status">
                                        <p class="col-sm-1 text-right">สถานะ <span class="red">*</span> :</p>
                                        <select name="status"  {{!empty($order) ? ($order->status == '1' ||  $order->status == '2'? 'disabled' : '') : ''}}  onchange="changestatus()" id="statusquotation" class="col-sm-2 form-control form-control-sm changestatus">
                                            <option value="">สถานะทั้งหมด</option>
                                            <option {{$quotation->status == '0' ? 'selected' : ''}} value="0">รอยืนยัน</option>
                                            <option {{$quotation->status == '1' ? 'selected' : ''}} value="1">ยืนยัน</option>
                                            <option {{$quotation->status == '2' ? 'selected' : ''}} value="2">ยกเลิก</option>
                                        </select>
                                    </div>
                                    <div class="d-flex-s flex-row justify-content-center align-items box-note">
                                        <p class="col-sm-2 text-right">หมายเหตุ :</p>
                                        <input class="col-sm-6 form-control form-control-sm" type="text" name="note" value="" placeholder="กรอกหมายเหตุ...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="d-flex flex-row justify-content-center align-items">
                                <button type="button" onclick="orderPrintPDF()" class="btn btn-print btn-fw"> <i class="fa fa-print" aria-hidden="true"></i> print ใบเสนอราคา</button>
                                <button type="button" onclick="changquotation({{$quotation->id}})" {{isset($order) ? 'disabled' : ''}} class="btn btn-primary btn-fw">ยืนยันทำรายการ</button>
                                @if(isset($order))
                                 <button type="button" onclick="GotoUrl('/orders/view/{{$order->id}}')" class="btn btn-create-order btn-fw"><i class="mdi mdi-file"></i> ไปหน้าบิลหลัก</button>
                                @else
                                <button type="submit" class="btn btn-create-order btn-fw"><i class="mdi mdi-file"></i> สร้างบิลหลัก</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
        <div id="form-print-pdf" class="form-print-pdf">
            @include('quotation.printpdf',['items'=>$datas,'customer' => $customer])
        </div>
    </div>
</div>

@push('plugin-scripts')
    {!! Html::script('/js/inputmoney.js') !!}
    {!! Html::script('/js/inputnumber.js') !!}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@endpush

@push('custom-scripts')
    <script>

    function update() {
      Swal.fire({
          title: 'คุณต้องการแก้ไข้รายการนี้',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'ตกลง',
          cancelButtonText: 'ยกเลิก',
          reverseButtons: true
      }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
              swal.fire({
                  title: 'กำลังบันทึกข้อมูล..',
                  onOpen: () => {
                    swal.showLoading()
                  }
              });

              $.ajax({
                  url: '{{route('quotation.update')}}',
                  headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  type: 'POST',
                  data: $('#updatequotation').serialize(),
                  success: function(data) {
                      window.location.href = window.location.origin + data.url
		  },
	       	  error: function(data){
                      alert(JSON.stringify(data));
     		  }
              });

          } else if (
              result.dismiss === Swal.DismissReason.cancel
          ) {
              event.preventDefault();

          }
      })
    }
        // $('#updatequotation').on("submit", function(event) {
        //     event.preventDefault();
        //     Swal.fire({
        //         title: 'คุณต้องการแก้ไข้รายการนี้',
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'ตกลง',
        //         cancelButtonText: 'ยกเลิก',
        //         reverseButtons: true
        //     }).then((result) => {
        //     /* Read more about isConfirmed, isDenied below */
        //         if (result.isConfirmed) {
        //             swal.fire({
        //                 title: 'กำลังบันทึกข้อมูล..',
        //                 onOpen: () => {
        //                   swal.showLoading()
        //                 }
        //             });
        //
        //             $.ajax({
        //                 url: '{{route('quotation.update')}}',
        //                 headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 type: 'POST',
        //                 data: $('#updatequotation').serialize(),
        //                 success: function(data) {
        //                     window.location.href = window.location.origin + data.url
        //                 }
        //             });
        //
        //         } else if (
        //             result.dismiss === Swal.DismissReason.cancel
        //         ) {
        //             event.preventDefault();
        //
        //         }
        //     })
        //
        //
        // });
        $('#updatequotationorders').on("submit", function(event) {
            swal.fire({
                title: 'กำลังบันทึกข้อมูล..',
                onOpen: () => {
                  swal.showLoading()
                }
            });
        });
        $(document).ready(function() {
            // $('.box-note').hide()
            $('li.nav-item.sidebar-nav-item.quotation-meun').addClass('active');
            if($('.total_item').length > 0)
            {
                var total = 0;
                    var someArray = $('.total_item')
                    for (var i = 0; i < someArray.length; i++) {
                        var str =  $(someArray[i]).val();
                        var res = str.replace(",", "");
                        var res1 = res.replace(",", "");
                        var res2 = res1.replace(",", "");
                        var res3 = res2.replace(",", "");
                        var res4 = res3.replace(",", "");
                        var res5 = res4.replace(",", "");
                        var res6 = res5.replace(",", "");
                        var res7 = res6.replace(",", "");
                        total += Number(res7);
                    }
                    var totalfor =  total.toFixed(2)
                    append.addCommasTotalAll(totalfor)
            }
        });
        changestatus()
        function changestatus()
        {
            var status = $('.changestatus').val()
            // console.log(status);
            if(status == '2')
            {
                $('.box-note').show()
            }else{
                $('.box-note').hide()
            }
            // box-note
        }

        var append =
        {
                appendrow:() =>
                {
                    var ck_customer = true;
                    if($('#store_name').val() == '')
                    {
                        ck_customer = false
                    }
                    if($('#customer_name').val() == '')
                    {
                        ck_customer = false
                    }
                    if($('#customer_phone').val() == '')
                    {
                        ck_customer = false
                    }
                    if($('#customer_address').val() == '')
                    {
                        ck_customer = false
                    }

                    if (ck_customer == false) {
                        Swal.fire({
                            icon: 'error',
                            text: 'กรุณากรอกข้อมูลร้านค้าก่อน',
                            showConfirmButton: true,
                            confirmButtonText: 'ปิดหน้าต่าง',
                        });
                    }else {
                        if($('.column').last().attr('data-id') == null)
                        {
                            var get_num  = 0;
                        }else{
                            var get_num = parseInt($('.column').last().attr('data-id'));
                        }
                        var newid = get_num + 1;
                        var ck_text = true;

                        $('[name="product_name[]"]').each(function() {
                            if ($(this).find('option:selected').val() == '-') {
                                ck_text = false;
                                return false;
                            }
                        });

                        $('[name="size_unit[]"]').each(function() {
                            if ($(this).find('option:selected').val() == '-') {
                                ck_text = false;
                                return false;
                            }
                        });

                        $('[name="price[]"]').each(function() {
                            if ($(this).val() == '') {
                                ck_text = false;
                                return false;
                            }
                        });


                        $('[name="number_order[]"]').each(function() {
                            if ($(this).val() == '') {
                                ck_text = false;
                                return false;
                            }
                        });

                        $('[name="total_item[]"]').each(function() {
                            if ($(this).val() == '') {
                                ck_text = false;
                                return false;
                            }
                        });

                        if (ck_text == false) {
                            Swal.fire({
                                icon: 'error',
                                text: 'กรุณากรอกข้อมูลลำดับก่อนหน้าให้เรียบร้อยก่อนจะเพิ่มรายการถัดไป',
                                showConfirmButton: true,
                                confirmButtonText: 'ปิดหน้าต่าง',
                            });
                        }else {
                            var html =''
                            html +='<tr class="column" id="row_'+newid+'" data-id="'+newid+'">'
                                html +='<td class="num_'+newid+'" data-num="'+newid+'">'+newid+'</td>'
                                html +='<td>'
                                html +='<input type="text" name="product_name[]" required value="" class="form-control form-control-sm">'
                                html +='</td>'
                                html +='<td>'
                                    html +='<select name="product_type_id[]" class="form-control form-control-sm select_product_type_id_'+newid+'" onchange="append.productsize('+newid+');"  required  data-product-type="'+newid+'">'
                                        html +='<option value="">-เลือก-</option>'
                                    html +='</select>'

// Pera adds 	
									html +='<select name="pera[]" class="form-control form-control-sm" >'
                                        html +='<option value="">-เลือก-</option>'
										html +='<option value="ลวด 4">-ลวด 4-</option>'
										html +='<option value="ลวด 5">-ลวด 5-</option>'
										html +='<option value="ลวด 6">-ลวด 6-</option>'
										html +='<option value="ลวด 7">-ลวด 7-</option>'

                                    html +='</select>'
									html +='<select name="pera_2[]" class="form-control form-control-sm" >'
										html +='<option value="">ไม่ show เหล็กข้าง</option>'
										html +='<option value="เหล็กข้าง">show เหล็กข้าง</option>'
                                    html +='</select>'
// Pera end add 										

                                html +='</td>'
                                html +='<td style="width:8%">'
                                    html +='<input type="text" name="size_unit[]" value="" disabled data-type="number" class="form-control form-control-sm size_unit_'+newid+'" required  onkeyup="append.summoney('+newid+')">'
                                    html +='<input type="hidden" name="size_unit_count[]" value="" class="form-control form-control-sm size_unit_count_'+newid+'" >'
                                html +='</td>'
                                html +='<td>'
                                    html +='<select name="product_size_id[]" class="form-control form-control-sm select_product_size_id_'+newid+'" onchange="append.summoney('+newid+')"  required  data-product-size="'+newid+'">'
                                        html +='<option value="">-เลือก-</option>'
                                    html +='</select>'
                                html +='</td>'
                                html +='<td>'
                                    html +='<input type="text" name="price[]" disabled data-type="currency" value="" class="form-control form-control-sm text-right price_check price_'+newid+'" required onkeyup="append.summoney('+newid+')">'
                                html +='</td>'
                                html +='<td>'
                                    html +='<input type="text" name="number_order[]" disabled data-type="number" value="" class="form-control form-control-sm text-right number_order_'+newid+'" required onkeyup="append.summoney('+newid+')">'
                                html +='</td>'
                                html +='<td>'
                                    html +='<select name="countunit[]" class="form-control form-control-sm select_countunit_'+newid+'"  required  data-countunit="'+newid+'">'
                                        html +='<option value="">-เลือก-</option>'
                                    html +='</select>'
                                html +='</td>'
                                html +='<td>'
                                    html +='<input type="text" name="total_item[]" readonly value="" class="form-control form-control-sm text-right total_item total_item_'+newid+'">'
                                html +='</td>'
                                html +='<td>'
                                    html +='<input type="text" name="note[]" value="" class="form-control form-control-sm text-right">'
                                html +=' </td>'
                                html +='<td>'
                                    html +='<button type="button" class="btn btn-danger btn-icons btn-rounded removerow_'+newid+'" onclick="append.removerow('+newid+')"><i class="fa fa-times-circle" aria-hidden="true"></i></button>'
                                html +='</td>'
                            html +='</tr>'
                            append.producttype(newid)
                            append.productcountunit(newid)
                            $('#datatable').append(html)
                            append.inputmoney();
                            append.inputnumber();
                        }
                    }
                },
                producttype:(id) =>
                {
                    $.ajax({
                        url: "{{route('product.producttype')}}",
                        type: 'post',
                        data: {id : id},
                        success: function(res) {
                            $('.select_product_type_id_'+id).html(res.html)
                        }
                    })
                },
                productsize:(id) =>
                {
                    selecttype = $('.select_product_type_id_'+id).val()
                    if(selecttype != '')
                    {
                        $('.size_unit_'+id).prop('disabled', false);
                        $('.price_'+id).prop('disabled', false);
                        $('.number_order_'+id).prop('disabled', false);

                    }else{
                        $('.size_unit_'+id).prop('disabled', true);
                        $('.price_'+id).prop('disabled', true);
                        $('.number_order_'+id).prop('disabled', true);
                    }

                    $.ajax({
                        url: "{{route('product.productsize')}}",
                        type: 'post',
                        data: {id : id,select : $('.select_product_type_id_'+id).val()},
                        success: function(res) {
                            $('.select_product_size_id_'+id).html(res.html)
                        }
                    })
                    $('.total_item_'+id).val(0)
                    $('.size_unit_'+id).val('')
                    $('.price_'+id).val('')
                    $('.number_order_'+id).val('')
                    append.CountTotalAll()
                },
                productcountunit:(id) =>
                {
                    $.ajax({
                        url: "{{route('product.productcountunit')}}",
                        type: 'post',
                        data: {id : id},
                        success: function(res) {
                            $('.select_countunit_'+id).html(res.html)
                        }
                    })
                },
                removerow:(id) =>
                {
                    $('#row_'+id).remove();
                    if($('tbody').find('tr').length != 0)
                    {
                         $.each($('tbody').find('tr'), function( key, value ) {
                            var oldid = key + 2;
                            if(oldid > id){
                                newid = oldid-1
                                $('#row_'+oldid).attr('data-id',newid)
                                $('#row_'+oldid).attr('id','row_'+newid)
                                $('.num_'+oldid).attr('data-num',newid)
                                $('.num_'+oldid).html(newid)
                                $('.num_'+oldid).removeClass('num_'+oldid).addClass('num_'+newid);
                                $('.select_product_type_id_'+oldid).attr('onchange','append.productsize('+newid+')');
                                $('.select_product_type_id_'+oldid).removeClass('select_product_type_id_'+oldid).addClass('select_product_type_id_'+newid);
                                // $('.select_product_size_id_'+oldid).removeClass('select_product_size_id_'+oldid).addClass('select_product_size_id_'+newid);
                                $('.select_countunit_'+oldid).removeClass('select_countunit_'+oldid).addClass('select_countunit_'+newid);
                                $('.removerow_'+oldid).attr('onclick','append.removerow('+newid+')');
                                $('.removerow_'+oldid).removeClass('removerow_'+oldid).addClass('removerow_'+newid);

                                $('.size_unit_'+oldid).attr('onclick','append.summoney('+newid+')');
                                $('.size_unit_'+oldid).removeClass('size_unit_'+oldid).addClass('size_unit_'+newid);
                                $('.select_product_size_id_'+oldid).attr('onclick','append.summoney('+newid+')');
                                $('.select_product_size_id_'+oldid).removeClass('select_product_size_id_'+oldid).addClass('select_product_size_id_'+newid);
                                $('.price_'+oldid).attr('onclick','append.summoney('+newid+')');
                                $('.price_'+oldid).removeClass('price_'+oldid).addClass('price_'+newid);
                                $('.number_order_'+oldid).attr('onclick','append.summoney('+newid+')');
                                $('.number_order_'+oldid).removeClass('number_order_'+oldid).addClass('number_order_'+newid);
                                $('.total_item_'+oldid).removeClass('total_item_'+oldid).addClass('total_item_'+newid);
                            }
                        });
                    }
                },
                 inputmoney: () =>
                {
                    $("input[data-type='currency']").on({
                        keyup: function() {
                        formatCurrency($(this));
                        },
                        blur: function() {
                        formatCurrency($(this), "blur");
                        }
                    });
                },
                inputnumber: () =>
                {
                    $("input[data-type='number']").on({
                        keyup: function() {
                        formatCurrencys($(this));
                        },
                        blur: function() {
                        formatCurrencys($(this), "blur");
                        }
                    });
                },
                sizeunit : (id) =>{
                    var select_size_val = $('.select_product_size_id_'+id).attr('data-size');
                    if(select_size_val != '')
                    {
                        var select_size = $('option:selected', '.select_product_size_id_'+id).attr('data-size');
                        if(select_size == 'x 0.35 ตร.ม.')
                        {
                           var sizeunit = $('.size_unit_'+id).val()
                           var count = sizeunit*0.35
                           $('.size_unit_count_'+id).val(count)
                        }else{
                            var count = $('.size_unit_'+id).val()
                            $('.size_unit_count_'+id).val(count)
                        }
                    }
                },
                summoney : (id) => {
                    var total = 0;
                    var select_size_val = $('option:selected', '.select_product_size_id_'+id).attr('data-size');
                      if(select_size_val != ''){

                            var select_size = $('option:selected', '.select_product_size_id_'+id).attr('data-size');

                            if(select_size == 'x 0.35 ตร.ม.')
                            {
                            var sizeunit_str = $('.size_unit_'+id).val()
                            if(typeof(sizeunit_str) !== "undefined")
                            {
                                var res = sizeunit_str.replace(",", "");
                                var res1 = res.replace(",", "");
                                var res2 = res1.replace(",", "");
                                var res3 = res2.replace(",", "");
                                var res4 = res3.replace(",", "");
                                var res5 = res4.replace(",", "");
                                var res6 = res5.replace(",", "");
                                var res7 = res6.replace(",", "");
                                var sizeunit = Number(res7);
                            }
                            var count = sizeunit*0.35
                            $('.size_unit_count_'+id).val(count)
                            }else{

                                var sizeunit_str = $('.size_unit_'+id).val()
                                if(typeof(sizeunit_str) !== "undefined")
                                {
                                    var res = sizeunit_str.replace(",", "");
                                    var res1 = res.replace(",", "");
                                    var res2 = res1.replace(",", "");
                                    var res3 = res2.replace(",", "");
                                    var res4 = res3.replace(",", "");
                                    var res5 = res4.replace(",", "");
                                    var res6 = res5.replace(",", "");
                                    var res7 = res6.replace(",", "");
                                    var sizeunit = Number(res7);
                                }
                                var count = sizeunit
                                $('.size_unit_count_'+id).val(count)
                            }

                        }
                        var size_unit_count = $('.size_unit_count_'+id).val()
                        var number_order_str =$('.number_order_'+id).val()
                        if(typeof(number_order_str) !== "undefined")
                        {
                            var res = number_order_str.replace(",", "");
                            var res1 = res.replace(",", "");
                            var res2 = res1.replace(",", "");
                            var res3 = res2.replace(",", "");
                            var res4 = res3.replace(",", "");
                            var res5 = res4.replace(",", "");
                            var res6 = res5.replace(",", "");
                            var res7 = res6.replace(",", "");
                            var number_order = Number(res7);
                        }

                        var str = $('.price_'+id).val()
                        // var price  = 0

                        if(typeof(str) !== "undefined")
                        {
                            var res = str.replace(",", "");
                            var res1 = res.replace(",", "");
                            var res2 = res1.replace(",", "");
                            var res3 = res2.replace(",", "");
                            var res4 = res3.replace(",", "");
                            var res5 = res4.replace(",", "");
                            var res6 = res5.replace(",", "");
                            var res7 = res6.replace(",", "");
                            var price = Number(res7);
                        }
                        total = size_unit_count * number_order * price;

                        // console.log(number_order);
                        // console.log(price);
                        // console.log(total);
                        totalfixed = total.toFixed(2)
                        append.CountTotalRow(totalfixed,id)
                },
                CountTotalRow: (nStr,id) =>{
                    nStr += '';
                    x = nStr.split('.');
                    x1 = x[0];
                    x2 = x.length > 1 ? '.' + x[1] : '.0';
                    var rgx = /(\d+)(\d{3})/;
                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                    }
                    var total = x1+x2;
                    $('.total_item_'+id).val(total);
                    append.CountTotalAll()
                },
                CountTotalAll : () => {
                    var total = 0;
                    var someArray = $('.total_item')
                    for (var i = 0; i < someArray.length; i++) {
                        var str =  $(someArray[i]).val();
                        var res = str.replace(",", "");
                        var res1 = res.replace(",", "");
                        var res2 = res1.replace(",", "");
                        var res3 = res2.replace(",", "");
                        var res4 = res3.replace(",", "");
                        var res5 = res4.replace(",", "");
                        var res6 = res5.replace(",", "");
                        var res7 = res6.replace(",", "");
                        total += Number(res7);
                    }
                    var totalfor =  total.toFixed(2)
                    append.addCommasTotalAll(totalfor)
                },
                addCommasTotalAll : (nStr) => {
                    nStr += '';
                    x = nStr.split('.');
                    x1 = x[0];
                    x2 = x.length > 1 ? '.' + x[1] : '.0';
                    var rgx = /(\d+)(\d{3})/;
                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                    }
                    var total = x1+x2;
                    $('#total_all').html(total)
                }
        }

        function changquotation(quotationid)
        {
            swal.fire({
                title: 'กำลังบันทึกข้อมูล..',
                onOpen: () => {
                    swal.showLoading()
                }
            });
            status =    $('#statusquotation').val()
            $.ajax({
                url: '{{route('quotation.updatestatus')}}',
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'POST',
                data: {status:status,quotationid:quotationid},
                success: function(data) {
                    window.location.href = window.location.origin + data.url
                }
            });

        }
        function GotoUrl(part)
        {
            window.location.href = window.location.origin + part;
            // console.log(part);
        }

        function orderPrintPDF()
        {
            totalitempdf()
            var Contents = document.getElementById('form-print-pdf').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = Contents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
@push('custom-scripts')
<script>
	function open_to_select_product(i_pera)
	 {
		//var url_safe_username = encodeURIComponent("username");
		//var url_safe_password = encodeURIComponent("password");
		window.open("list_products?i_pera="+i_pera,"_blank","height=500,width=400,status=yes,toolbar=no,menubar=no,location=no")
	 }
</script>
@endpush

@stop
