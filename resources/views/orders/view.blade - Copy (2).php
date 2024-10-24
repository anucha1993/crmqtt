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
        /* .table-width{
            width: 1500px;
        } */
        .m-t-15{
            margin-top: 15px;
        }
        a.btn.btn-apped {
            width: 100%;
            border: 1px solid #666983;
            border-style: dashed;
            color: #666983;
        }
        .money-color{
            color: #4C9FFE;
        }
        p.warning-text {
            font-size: 14px;
            margin-bottom: 5px;
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

        .btn-order{
            background: #FF753A;
            color: #FFFF;
        }
        .btn-history{
            background: #8469E1;
            color: #FFFF;
        }
        .p-number_order{
            color: #4C9FFE;
        }
        .p-send{
            color: #33AF8A;
        }
        .p-not-send{
            color: #FF753A;
        }
        input.form-control.form-control-sm.col-sm-1 {
            display: inline-flex;
            width: 13px;
            height: 13px;
        }
        select.form-control.form-control-sm {
            font-size: 12px;
            padding: 2px;
        }
        .select_payment{
            font-size: 12px;
            padding: 4px;
            width: 115px;
            float: right;
            border: 0px;
            color: #4C9FFE;
            text-decoration: underline;
        }
        button.btn.btn-file {
            background: #33467A;
            color: #FFFF;
            font-size: 10px;
            height: fit-content;
        }
        span.warning-file {
            font-size: 12px;
            text-decoration: underline;
            color: #A9A9A9;
            padding-top: 4px;
            padding-left: 10px;
        }
        .d-flex.flex-row.justify-content-center.align-items.box-note.box-file {
            padding-top: 5px;
            padding-bottom: 0px;
        }
        div.d-flex-s {
            display: flex;
        }

        /* select.form-control.form-control-sm.select_payment {
            background: none;
            color: black;
        } */

        @media (max-width: 576px) {
            div.box-note-status,div.box-note {
                padding-right: 15px;
            }
        }

        @media  print {
            @page {
                size:A4;
            }

            #printBilling{
                width:100%;
            }

            body{
                overflow: hidden;
                padding: 1rem;
                /* padding-bottom: 1rem;
                padding-top: 1rem;
                padding-bottom: 1rem; */
            }

            #tableBilling > thead > tr,
            #tableBilling > thead > tr > td {
                border: 1px solid;
                text-align: center;
            }

            #tableBilling > tbody > tr,
            #tableBilling > tbody > tr > td {
                border-right: 1px solid;
                border-left: 1px solid;
            }
        }

    </style>
@endpush
<script>
	function printBilling_pera(name){
		alert("400");
		if (name == 'tax') {
			$('.nameBilling').text('ใบเสร็จรับเงิน / ใบกำกับภาษี')
		} else {
			$('.nameBilling').text('ใบเสร็จรับเงิน')
		}
		var printContents = document.getElementById('printBilling').innerHTML;
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = printContents;

		window.print();

		document.body.innerHTML = originalContents;
}
</script>
@section('content')

<div class="row">
    {!!breadcrumb($breadcrumb)!!}
    <div class="col-lg-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="form-inline">
                <div class="col-sm-6">
                    <h3 class="title-page">รายการบิลหลัก {{$orders->order_number}}</h3>
                </div>
                <div class="text-right col-sm-6">
                    <button href="#" class="btn btn-print " onclick="printBilling('tax')" {{(($orders->on_vat != '1') || $orders->status_payment == '0') ? 'disabled' : ''}}  ><i class="fa fa-print" aria-hidden="true"></i> Print ใบกำกับภาษี</button>
                    <button type="button" class="btn btn-print "  onclick="alert('100'); printBilling_pera('bill');"><i class="fa fa-print" aria-hidden="true"></i> yPrint บิล</button>
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">วันที่สร้างใบเสนอราคา</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{date('d/m/Y H:i:s',strtotime($orders->created_at))}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">ชื่อร้านค้า</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$customer->store_name}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">เลขประจำตัวผู้เสียภาษี</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$customer->text_number_vat}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">Pocket Money</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$orders->customer_type == 'customer' ? (!empty($customer->PocketMoney) ? number_format($customer->PocketMoney->pocket_money,2) : '0.00') : '0.00'}}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">ผู้ติดต่อ</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$customer->customer_name}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">เบอร์โทรศัพท์</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$customer->customer_phone}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">อีเมล์</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$customer->customer_mail}}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group col-sm-12 input-header">
                    <label class="col-sm-12 header-title">ที่อยู่</label>
                    <div class="col-sm-12">
                        @if($orders->customer_type =='customer')
                            <p class="title_order">{{$customer->customer_address .' ตำบล '.$customer->district_name.' อำเภอ '.$customer->amphoe_name.' จังหวัด '.$customer->province_name.' '.$customer->zip_code}}</p>
                        @else
                            <p class="title_order">{{$customer->customer_address}}</p>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="col-sm-6">
                    <h4 class="title-page">รายการสั่งซื้อ</h4>
                </div>
                <div class="text-right col-sm-6">
                    {{-- {{dd($orders->status != '1' &&  $orders->payment_type == null)}} --}}
                    @if($orders->status != '1')
                        <button type="button" class="btn btn-order" disabled onClick="GotoUrl('/orders/delivery/list/{{$orders->id}}')">รายการบิลย่อย</button>
                    @elseif($orders->payment_type != null)
                        <button type="button" class="btn btn-order" disabled  onClick="GotoUrl('/orders/delivery/list/{{$orders->id}}')">รายการบิลย่อย</button>
                    @else
                        <button type="button" class="btn btn-order"  onClick="GotoUrl('/orders/delivery/list/{{$orders->id}}')">รายการบิลย่อย</button>
                    @endif
                    <a href="{{url('orders/payment/'.$orders->id)}}" class="btn btn-history" {{$orders->status != '1' ? 'disabled' : ''}} >ประวัติการชำระ</a>
                </div>
            </div>
            {{-- @php
                dd($datas);
            @endphp --}}
            <div class="table-responsive">
              <table class="table table-width">
                <thead class="head-table">
                  <tr>
                    <th style="width: 50px;"> ลำดับ </th>
                    <th style="width: 100px;"> ชื่อสินค้า/รายละเอียดสินค้า </th>
                    <th style="width: 100px;"> ประเภทสินค้า </th>
                    <th style="width: 100px;"> ความยาว </th>
                    <th style="width: 100px;"> ราคาต่อหน่วย </th>
                    <th style="width: 100px;"> จำนวน </th>
                    <th style="width: 100px;"> หน่วยนับ </th>
                    <th style="width: 100px;"> จำนวนเงิน </th>
                    <th style="width: 100px;"> หมายเหตุ </th>
                    <th style="width: 100px;"> ส่งแล้ว </th>
                    <th style="width: 100px;"> ค้างส่ง </th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$data->product_name}}</td>
                            <td>{{$data->type_name}}</td>
                            <td>{{$data->size_unit.' '.$data->size_name}}</td>
                            <td>{{number_format($data->price,2)}}</td>
                            <td>
                                <p class="p-number_order">{{number_format($data->number_order)}}</p>
                                <input type="hidden" value="{{$data->total_item}}" class="total_item">
                            </td>
                            <td>{{countunitstr($data->count_unit)}}</td>
                            <td>{{number_format($data->total_item,2)}}</td>
                            <td>{{$data->note}}</td>
                            <td><p class="p-send">{{$data->item_send}}<p></td>
                            <td><p class="p-not-send">{{$data->number_order-$data->item_send}}</p></td>
                        </tr>
                    @endforeach

                </tbody>
              </table>
            </div>
            <form id="updateorder" action="{{route('orders.update')}}" method="POST">
            <div class="row">
                <p class="col-sm-10 text-right m-t-15 warning-text">ยอดรวมสั่งซื้อ</p>
                <p class="col-sm-2 text-right m-t-15 warning-text">{{number_format($orders->price_all,2)}}</p>

                <p class="col-sm-10 text-right warning-text">
                    <input type="checkbox" disabled {{$orders->on_vat == 1 ? 'checked' : ''}}  value="1" class="form-control form-control-sm col-sm-1">
                    <label >ภาษีมูลค่าเพิ่ม 7%</label>
                </p>
                <p class="col-sm-2 text-right warning-text">{{ $orders->on_vat == 1 ? number_format($orders->vat,2) : '0.00'}}</p>

                <p class="col-sm-10 text-right warning-text">ประเภทจ่ายเงิน</p>
                <p class="col-sm-2 text-right warning-text ">
                    <select name="payment_type" required {{$orders->payment_type != null ? 'disabled' : ''}}  class="form-control form-control-sm select_payment" onchange="selectpaymenttype()">
                        <option value="">เลือกการจ่ายเงิน</option>
                        <option {{$orders->payment_type == '1' ? 'selected' : ''}} value="1">มัดจำ</option>
                        <option {{$orders->payment_type == '2' ? 'selected' : ''}} value="2">จ่ายหน้างาน</option>
                        <option {{$orders->payment_type == '3' ? 'selected' : ''}} value="3">จ่ายด้วย Credit</option>
                        <option {{$orders->payment_type == '4' ? 'selected' : ''}} value="4">จ่ายด้วย Pocket Money</option>
                    </select>
                </p>
                <p class="col-sm-10 text-right warning-text type_deposit">จำนวนเงินมัดจำ</p>
                    <p class="col-sm-2 text-right warning-text money-color type_deposit">
                        <input type="text" name="payment" data-type='currency' id="paymentdeposit" class="form-control form-control-sm" value="">
                </p>
                <p class="col-sm-10 text-right warning-text type_pocket">ยอด Pocket ปัจจุบัน</p>
                <p class="col-sm-2 text-right warning-text type_pocket" id="pocket_money">{{$pocketmoney}}</p>
                @if($orders->payment_type == 1)
                    <p class="col-sm-10 text-right warning-text">จำนวนเงินมัดจำ</p>
                    <p class="col-sm-2 text-right warning-text">
                        {{(isset($historypayment->total) ? number_format($historypayment->total,2) : 0)}}
                    </p>
                @endif
                @if($orders->payment_type == 4)
                    <p class="col-sm-10 text-right warning-text">ยอด Pocket ปัจจุบัน</p>
                    <p class="col-sm-2 text-right warning-text">{{number_format($pocketmoney,2)}}</p>
                @endif
                <p class="col-sm-10 text-right warning-text">ยอดรวมทั้งสิ้น</p>
                <p class="col-sm-2 text-right warning-text">{{number_format($orders->total,2)}}</p>
                @if($orders->payment_type == 1)
                    <p class="col-sm-10 text-right warning-text">ยอดที่ยังไม่ได้ชำระคงเหลือ</p>
                    <p class="col-sm-2 text-right warning-text">{{number_format($orders->total - $sumpayment,2)}}</p>
                @endif
            </div>

                {{ csrf_field() }}
                <input type="hidden" name="orderid" value="{{$orders->id}}">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="crad">
                            <div class="card-body">
								<div class="d-flex flex-row justify-content-center align-items box-note-status">
                                    <p class="col-sm-2 text-right">ที่อยู่จัดส่งปัจจุบัน :</p> {{$orders_pera_sql}}
									<input class="col-sm-6 form-control form-control-sm" type="text" name="current_delivery_location" id="current_delivery_location" value="{{$location_name}}" disabled placeholder="" >
                                </div>
								<div class="d-flex flex-row justify-content-center align-items box-note">
									<input type="checkbox" name="change_delivery_location_check_box" id="change_delivery_location_check_box" value="yes" onclick="toggle_new_location();">
									<label> &nbsp; ต้องการป้อนที่อยู่จัดส่งใหม่ <span class="red">*</span></label>
                                </div>
								<div class="d-flex flex-row justify-content-center align-items box-note-status">
                                    <p class="col-sm-2 text-right">ที่อยู่จัดส่ง :</p>
									<select  class="col-sm-6 form-control form-control-sm" type="text" name="existing_delivery_location_id" disabled id="existing_delivery_location_id">
										<option>เลือกที่อยู่จัดส่ง</option>
										@foreach ($delivery_location as $data)
											<option value={{$data->id}}>{{$data->location}}</option>
										@endforeach
									</select>
                                </div>
								
								
<script>
function toggle_new_location () {
	if (document.getElementById('existing_delivery_location_id').disabled == true) {
		document.getElementById('existing_delivery_location_id').disabled = false;
		document.getElementById('delivery_location').disabled = true;
	}else {
		document.getElementById('existing_delivery_location_id').disabled = true;
		document.getElementById('delivery_location').disabled = true;
	}
	
	if(document.getElementById('change_delivery_location_check_box').checked) {
		document.getElementById('new_delivery_location_check_box').disabled = false;
	} else {
		document.getElementById('new_delivery_location_check_box').disabled = true;
	}
}

function toggle_add_new_location () {
	if (document.getElementById('existing_delivery_location_id').disabled == true) {
		document.getElementById('existing_delivery_location_id').disabled = false;
		document.getElementById('delivery_location').disabled = true;

	}else {
		document.getElementById('existing_delivery_location_id').disabled = true;
		document.getElementById('delivery_location').disabled = false;
	}
}
</script>								
								<div class="d-flex flex-row justify-content-center align-items box-note">
									<input type="checkbox" name="new_delivery_location_check_box" id="new_delivery_location_check_box" value="yes" disabled onclick="toggle_add_new_location();">
									<label> &nbsp; ต้องการเพิ่มที่อยู่จัดส่งใหม่ <span class="red">*</span></label>
                                </div>
								<div class="d-flex flex-row justify-content-center align-items box-note-status">
                                    <p class="col-sm-2 text-right">ที่อยู่จัดส่งใหม่ :</p>
                                    <input class="col-sm-6 form-control form-control-sm" type="text" name="delivery_location" id="delivery_location" disabled placeholder="">
                                </div>
                                <div class="d-flex flex-row justify-content-center align-items box-note-status">
                                    <p class="col-sm-2 text-right">หมายเหตุ :</p>
                                    <input class="col-sm-6 form-control form-control-sm" readonly type="text" name="note" value="{{$orders->note}}" placeholder="">
                                </div>
                                <div class="d-flex-s flex-row justify-content-center align-items box-note box-file">
                                    <p class="col-sm-2 text-right">ไฟล์แนบ :</p>
                                    <label for="file-upload" class="btn btn-upload changfile">
                                        <i class="fa fa-upload"></i> <span class="label_upload">เลือกไฟล์แนบ</span>
                                    </label>
                                    <input id="file-upload" type="file" name="file" value="" style="display: none;" accept=" .jpg, .png, .pdf"><span class="warning-file" id="name_file">ยังไม่ได้แนบไฟล์(.pdf,.jpg,.png)</span>
                                </div>
                                @if($orders->file)
                                <div class="d-flex flex-row justify-content-center align-items box-note box-file">
                                    <p class="col-sm-2 text-right">แนบไฟล์ :</p>
                                    <p>{!!$orders->file ? '<a href="'.url('storage/delivery/'.$orders->file).'" target="_blank" class="btn btn-link">'.$orders->file.'</a>':''!!}</p>
                                </div>
                                @endif
                                <div class="d-flex flex-row justify-content-center align-items box-note">
                                    <label class="col-sm-2 text-right">สถานะบิลหลัก <span class="red">*</span></label>
                                    <select name="status"  {{$orders->status == 1 ? 'disabled' : ''}} class="col-sm-2 form-control form-control-sm">
                                        <option value="">สถานะทั้งหมด</option>
                                        <option {{$orders->status == '0' ? 'selected' : ''}}  value="0">รอยืนยัน</option>
                                        <option {{$orders->status == '1' ? 'selected' : ''}}  value="1">ยืนยัน</option>
                                        <option {{$orders->status == '2' ? 'selected' : ''}}  value="2">ยกเลิก</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex flex-row justify-content-center align-items">
                            <button type="button" class="btn btn-secondary btn-fw">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary btn-fw">ยืนยันทำรายการ</button>
                        </div>
                    </div>
                </div>
            </form>
          </div>
        </div>
    </div>

</div>


@include('orders.billing')


@push('plugin-scripts')
{!! Html::script('/js/converCurrencyToText.js') !!}
@endpush

@push('custom-scripts')
{{-- converCurrencyToText.js --}}
{!! Html::script('js/jquery.min.js') !!}
    <script>
        $(document).ready(function() {
            $('li.nav-item.sidebar-nav-item.orders-meun').addClass('active');
            $('.box-file').hide();
            $('.type_deposit').hide();
            $('.type_pocket').hide();

            $('.convertText').text(BAHTTEXT("{{number_format($orders->total,2)}}"));

        });
        $('#file-upload').on('change',function(e)
        {
            fullPath =  $(this).val()
            if (fullPath) {
                var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                var filename = fullPath.substring(startIndex);
                if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                    filename = filename.substring(1);
                }
                $('.changfile').html('<i class="fa fa-times" aria-hidden="true"></i> <span class="label_upload">เลือกไฟล์</span>')
                $('#name_file').html(filename)
            }else{
                $('.changfile').html('<i class="fa fa-upload"></i> <span class="label_upload">เลือกไฟล์แนบ</span>')
                $('#name_file').html('ยังไม่ได้แนมไฟล์(.pdf,.jpg,.png)')

            }
        })
        $('#updateorder').on("submit", function(event) {
            if($('.select_payment').val() == 4)
            {
                var input_total_all = $('#input_total_all').val()
                var pocket_money = $('#pocket_money').html()
                var check = pocket_money*1
                if(input_total_all >= check)
                {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'จำนวน Pocket Money ไม่พอ',
                        text: 'กรุณาเติมก่อน',
                        showConfirmButton: true,
                        confirmButtonText: 'ปิดหน้าต่าง',
                    });
                }else{
                    swal.fire({
                        title: 'กำลังบันทึกข้อมูล..',
                        onOpen: () => {
                        swal.showLoading()
                        }
                    });
                }
            }else{

                swal.fire({
                    title: 'กำลังบันทึกข้อมูล..',
                    onOpen: () => {
                    swal.showLoading()
                    }
                });
            }
        });
        function selectpaymenttype()
        {
            select_payment = $('.select_payment').val()
            if(select_payment == 1)
            {
                $('.type_deposit').show()
                $('.box-file').show()
                $('.type_pocket').hide()
                $('#paymentdeposit').prop('required',true);
            }else if(select_payment == 4)
            {
                $('.type_deposit').hide()
                $('.box-file').hide()
                $('.type_pocket').show()
                $('#paymentdeposit').prop('required',false);
            }else{
                $('#paymentdeposit').prop('required',false);
                $('.type_deposit').hide()
                $('.type_pocket').hide()
                $('.box-file').hide()
            }
        }

        function GotoUrl(part)
        {
            window.location.href = window.location.origin + part;
            // console.log(part);
        }

        function printBilling(name){
alert("200");
            if (name == 'tax') {
                $('.nameBilling').text('ใบเสร็จรับเงิน / ใบกำกับภาษี')
            } else {
                $('.nameBilling').text('ใบเสร็จรับเงิน')
            }
            var printContents = document.getElementById('printBilling').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

        }
		
		
</script>
@endpush


@stop




