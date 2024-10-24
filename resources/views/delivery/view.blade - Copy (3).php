@extends('layout.master')
@push('plugin-styles')
{!! Html::style('/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') !!}
{!! Html::style('/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') !!}
{{-- {!! Html::style('/plugins/daterangepicker/daterangepicker.css') !!} --}}
@endpush
@push('style')
    <style>
        .p-send{
            color: #33AF8A;
        }
        .p-not-send{
            color: #FF753A;
        }
        .p-number_order{
            color: #4C9FFE;
        }
        p.warning-text {
            font-size: 14px;
            margin-bottom: 5px;
        }
        input.form-control.form-control-sm.col-sm-1 {
            display: inline-flex;
            width: 13px;
            height: 13px;
        }
        label.label_vat{
            display: inline-block;
            margin-bottom: 0.5em;
        }
        div.left-border
        {
            border-left: 1px solid #C7C7C7;
        }
        div.bottom-border
        {
            border-bottom: 1px solid #C7C7C7;
        }
        p.m-b-15{
            margin-bottom: 15px;
        }
        p.m-t-15{
            margin-top: 15px;
        }
        .crad {
            padding: 20px;
            background: #F6F8FB;
            margin-top: 25px;
        }
        select.form-control.form-control-sm {
            font-size: 12px;
            padding: 2px;
        }
        .d-flex.flex-row.justify-content-center.align-items {
            margin-top: 25px;
        }
        p.text-date-send{
            padding-right: 0;
            border-left: 1px solid #C7C7C7;
        }

        @media  print {
            @page {
                size:A4;
            }

            #printBillings{
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
                border: 1px solid ;
                text-align: center;
            }

            #tableBilling > tbody > tr,
            #tableBilling > tbody > tr > td {
                border-right: 1px solid ;
                border-left: 1px solid ;
            }

            /* #title_header_delivery {
                background-color: ;
                border: 1px solid ;
                padding: 7px;
            } */
        }


    </style>
@endpush
@section('content')
<div class="row">
    {!!breadcrumb($breadcrumb)!!}
    <div class="col-lg-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="form-inline">
                <div class="col-sm-6">
                    <h4 class="title-page">เพิ่มรายบิลย่อย {{$deliverys->order_delivery_number}}  (รายการบิลหลัก {{$orders->order_number}})</h4>
                </div>
                <div class="text-right col-sm-6">
                    {{-- <button href="#" class="btn btn-print " onclick="printBilling('tax')" {{(($orders->on_vat != '1') || $orders->status_payment == '0') ? 'disabled' : ''}}  ><i class="fa fa-print" aria-hidden="true"></i> Print ใบกำกับภาษี</button> --}}
                    <button type="button" class="btn btn-print "  onclick="printBilling()"><i class="fa fa-print" aria-hidden="true"></i> Print ใบส่งของ</button>
                </div>
            </div>
            <hr>
            <input type="hidden" value="{{$orders->on_vat}}" id="check_vat">
            {{ csrf_field() }}
            <div class="table-responsive">
            <table class="table table-width">
                <thead class="head-table">
                <tr>
                    <th style="width: 50px;"> ลำดับ </th>
                    <th style="width: 100px;"> ชื่อสินค้า/รายละเอียดสินค้า </th>
                    <th style="width: 100px;"> ประเภทสินค้า </th>
                    <th style="width: 100px;"> ความยาว </th>
                    <th style="width: 100px;"> ราคาต่อหน่อย </th>
                    <th style="width: 100px;"> จำนวน </th>
                    <th style="width: 100px;"> หน่วยนับ </th>
                    <th style="width: 100px;"> จำนวนเงิน </th>
                    <th style="width: 100px;"> หมายเหตุ </th>
                    <th style="width: 100px;"> ค้างส่ง </th>
                    <th style="width: 100px;"> ส่งสินค้า </th>
                    <th style="width: 100px;"> จำนวนเงินในการส่งสินค้าครั้งนี้ </th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$data->product_name}}</td>
                            <td>{{$data->type_name}}</td>
                            <td>
                                {{$data->size_unit.' '.$data->size_name}}
                                <input type="hidden" value="{{$data->size_unit}}" class="size_unit_id_{{$data->order_itmes_id}}">
                                <input type="hidden" value="{{$data->size_name}}" class="size_name_{{$data->order_itmes_id}}">
                            </td>
                            <td class="price_item_{{$data->order_itmes_id}} item_price text-right"  data-id="{{$data->order_itmes_id}}">{{number_format($data->price_item,2)}}</td>
                            <td class="text-right">
                                <p class="p-number_order">{{number_format($data->item_number_order)}}</p>
                                <input type="hidden" value="{{$data->total_item}}" class="total_item">
                            </td>
                            <td >{{countunitstr($data->count_unit)}}</td>
                            <td class="text-right">{{number_format($data->total_item_all,2)}}</td>
                            <td>{{$data->note}}</td>
                            <td class="text-right">
                                <!-- {{$data->item_number_order-$data->item_send}} -->
                                {{$data->item_send_qty_padding}}
                            </td>
                            <td class=" text-right p-send items_send_{{$data->order_itmes_id}}">
                                <!-- {{$data->item_send}} -->
                                {{$data->item_send_qty}}
                            </td>
                            <td class="text-right">{{($data->product_type_id == 1) ? number_format(($data->size_unit*$data->price_item*$data->item_send_qty),2) : number_format(($data->size_unit*0.35*$data->price_item*$data->item_send_qty),2)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <div class="form-inline">
                        <div class="col-sm-6">
                            <div class="form-inline ">
                                <p class="col-sm-6 m-t-15 warning-text">ยอดสั่งซื้อรวมในบิลหลัก</p>
                                <p class="col-sm-6 m-t-15 warning-text text-right">{{number_format($orders->price_all,2)}}</p>
                                <p class="col-sm-6 warning-text">
                                    <input type="checkbox" disabled {{$orders->on_vat == 1 ? 'checked' : ''}}  value="1" class="form-control form-control-sm col-sm-1">
                                    ภาษีมูลค่าเพิ่ม 7%
                                </p>
                                <p class="col-sm-6 warning-text text-right">{{$orders->on_vat == 1 ? number_format($orders->vat,2) : '0.00'}}</p>
                                <p class="col-sm-6 warning-text">ยอดสั่งซื้อรวมทั้งสิ้น</p>
                                <p class="col-sm-6 warning-text text-right" id="order_total_all">{{number_format($orders->total,2)}}</p>
                                <p class="col-sm-6 warning-text">ประเภทการจ่ายเงิน</p>
                                <p class="col-sm-6 warning-text text-right">{{PatmentType($orders->payment_type)}}</p>
                                @if($orders->payment_type == 1)
                                <p class="col-sm-6 warning-text">ยอดเงินที่มัดจำไว้</p>
                                <p class="col-sm-6 warning-text text-right" id="money_deposit">{{$orders->GetDeposit ? number_format($orders->GetDeposit->total,2):'0.00'}}</p>
                                @else
                                <p class="col-sm-6 warning-text text-right" id="money_deposit" style="display: none">{{$orders->GetDeposit ? number_format($orders->GetDeposit->total,2):'0.00'}}</p>
                                @endif
                                {{-- <p class="col-sm-6 warning-text">ยอดเงินที่มัดจำไว้</p>
                                <p class="col-sm-6 warning-text text-right" id="money_deposit">{{number_format($orders->GetDeposit->total,2)}}</p> --}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-inline left-border bottom-border">
                                <p class="col-sm-6 m-t-15 warning-text">ยอดในบิลย่อยครั้งนี้</p>
                                <p class="col-sm-6 m-t-15 warning-text text-right" id="total_long_order">0.00</p>
                                <p class="col-sm-6 warning-text">
                                    <input type="checkbox" disabled {{$orders->on_vat == 1 ? 'checked' : ''}}  value="1" class="form-control form-control-sm col-sm-1">
                                    ภาษีมูลค่าเพิ่ม 7%
                                </p>
                                <p class="col-sm-6 warning-text text-right" id="vat_long">{{number_format($orders->vat,2)}}</p>
                                <p class="col-sm-6 m-b-15 warning-text">ประเภทการจ่ายเงิน</p>
                                <p class="col-sm-6 m-b-15 warning-text text-right">{{PatmentType($orders->payment_type)}}</p>
                            </div>
                            <div class="form-inline left-border">
                                <p class="col-sm-6 m-t-15 warning-text" >ราคารวมในบิลย่อย</p>
                                <p class="col-sm-6 m-t-15 warning-text text-right" id="total_all">{{number_format($orders->price_all,2)}}</p>
                                @if($orders->payment_type == 1)
                                <p class="col-sm-6 warning-text">หักจากเงินมัดจำ</p>
                                <p class="col-sm-6 warning-text text-right" id="cutdeposit">{{number_format($deliverys->money_deposit,2)}}</p>
                                @else
                                <p class="col-sm-6 warning-text text-right" id="cutdeposit" style="display: none">{{number_format($deliverys->money_deposit,2)}}</p>
                                @endif
                                <p class="col-sm-6 warning-text">ยอดที่ต้องชำระ</p>
                                <p class="col-sm-6 warning-text text-right" id="HavToPayment">{{number_format($deliverys->total - $deliverys->money_deposit,2)}}</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" value="{{$deliverys->file}}" id="name_file_have">
            <input type="hidden" value="{{date('d/m/Y',strtotime($deliverys->date_send))}}" id="date_send">
            <form id="updatedelivery" action="{{route('delivery.update')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
				<div class="row">
                    <div class="col-sm-12">
                        <div class="crad">
                            <div class="card-body">
								<div class="d-flex flex-row justify-content-center align-items box-note-status">
                                    <p class="col-sm-2 text-right">ที่อยู่จัดส่งปัจจุบัน :</p>
									<input class="col-sm-6 form-control form-control-sm" type="text" name="current_delivery_location" id="current_delivery_location" value="{{ $location_name }}" disabled placeholder="">
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="crad">
                            <div class="card-body">
                                <div class="form-inline">
                                    <div class="col-sm-6">
                                        <div class="form-inline">
                                            <p class="col-sm-8 m-t-15 warning-text text-right">xสถานะการจัดส่ง :<span class="red">*</span></p>
                                            <div class="input-group col-sm-4">
                                              <input type="hidden" name="status_text" value="{{$deliverys->status}}">
                                                <select required class="form-control" name="status"  {{$deliverys->status == 1 ? ' disabled' : ''}}>
                                                    <option value=""> เลือกสถานะ</option>
                                                    <option {{$deliverys->status == 0 ? 'selected' : ''}} value="0"> กำลังดำเนินการ</option>
                                                    <option {{$deliverys->status == 1 ? 'selected' : ''}} value="1"> จัดส่งสำเร็จ</option>
                                                    <option {{$deliverys->status == 2 ? 'selected' : ''}} value="2"> ยกเลิก</option>
                                                <select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-inline">
                                            <p class="col-sm-2 m-t-15 warning-text text-date-send">วันที่จัดส่ง :<span class="red">*</span></p>
                                            <div class="input-group col-sm-5">
                                              <input type="hidden" name="date_send_text datepicker" value="{{date('d/m/Y',strtotime($deliverys->date_send))}}">
                                                <input type="text" class="form-control datepicker" {{$deliverys->status == 1 ? ' disabled' : ''}} required autocomplete="off" name="date_send" value="">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="col-sm-6">
                                        <div class="form-inline">
                                            <p class="col-sm-8 m-t-15 warning-text text-right">สถานะการชำระเงิน :<span class="red">*</span></p>
                                            <div class="input-group col-sm-4">
                                              <input type="hidden" name="status_payment_text" value="{{$deliverys->status_payment}}">
                                                <select class="form-control" required name="status_payment" {{$deliverys->status_payment == 1 ? ' disabled' : ''}}>
                                                    <option value=""> เลือกสถานะ</option>
                                                    <option {{$deliverys->status_payment == 0 ? 'selected' : ''}}   value="0">รอชำระ</option>
                                                    <option {{$deliverys->status_payment == 1 ? 'selected' : ''}} value="1"> ชำระแล้ว</option>
                                                <select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-inline inputfile">
                                    <div class="col-sm-6">
                                        <div class="form-inline">
                                            <p class="col-sm-8 m-t-15 warning-text text-right">ไฟล์แนบ :</p>
                                            <div class="input-group col-sm-4">
                                                <label for="file-upload" class="btn btn-upload changfile">
                                                    <i class="fa fa-upload"></i> <span class="label_upload">เลือกไฟล์แนบ</span>
                                                </label>
                                                <input id="file-upload" type="file" name="file" value="" style="display: none;" accept=" .jpg, .png, .pdf">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-inline inputfile">
                                    <div class="col-sm-6">
                                        <div class="form-inline">
                                            <p class="col-sm-12 warning-text text-right text-disable" id="name_file">ยังไม่ได้แนมไฟล์(.pdf,.jpg,.png)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="order_delivery_id" value="{{$deliverys->order_delivery_id}}">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex flex-row justify-content-center align-items">
                            <a href="{{url('orders/delivery/list/'.$orders->id)}}"  class="btn btn-secondary btn-fw">ยกเลิก</a>
                            <button type="submit" class="btn btn-primary btn-fw">ยืนยันทำรายการ</button>
                        </div>
                    </div>
                </div>
            </form>
          </div>
        </div>
    </div>
</div>
{{-- @include('orders.billing') --}}
@include('delivery.billing')
@push('plugin-scripts')
    {!! Html::script('/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') !!}
    {!! Html::script('/js/converCurrencyToText.js') !!}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/print.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            date = $('#date_send').val()

            date = $('#date_send').val().split('/');

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true
            });
            mydate = new Date(date[2], date[1] -1 , date[0]);
            $( ".datepicker" ).datepicker( "setDate", mydate);


            $('li.nav-item.sidebar-nav-item.orders-meun').addClass('active');

             $('#file-upload').on('change',function(e){
                fullPath =  $(this).val()
                if (fullPath) {
                    var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                    var filename = fullPath.substring(startIndex);
                    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                        filename = filename.substring(1);
                    }
                    checkfile(filename)
                    // $('#name_file').html(filename)
                }else{


                }
             })
             $('#updatedelivery').on("submit", function(event) {
                swal.fire({
                        title: 'กำลังบันทึกข้อมูล..',
                        onOpen: () => {
                        swal.showLoading()
                        }
                });
            });

        });
        checkPrice()
        checkfile()
        function checkPrice()
        {
            var total = 0;
            var someArray = $('.item_price')
            for (var i = 0; i < someArray.length; i++)
            {
                var id =  $(someArray[i]).data('id');
                var price_item_str = $('.price_item_'+id).html()
                var res = price_item_str.replace(",", "");
                var res1 = res.replace(",", "");
                var res2 = res1.replace(",", "");
                var res3 = res2.replace(",", "");
                var res4 = res3.replace(",", "");
                var res5 = res4.replace(",", "");
                price_item = Number(res5)
                var items_send = $('.items_send_'+id).html()
                var size_unit = $('.size_unit_id_'+id).val()
                var size_name = $('.size_name_'+id).val()
                if(size_name == 'x 0.35 ตร.ม.')
                {
                    total += price_item*items_send * size_unit * 0.35
                }else{
                    total += price_item*items_send * size_unit
                }
            }
            var totalfor =  total.toFixed(2)
            addCommasTotal(totalfor)
            CehckVat(total)
        }

        function addCommasTotal(nStr)
        {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '.0';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            var total = x1+x2;
            $('#total_long_order').html(total)
        }

        function CehckVat(total)
        {
            checkvat = $('#check_vat').val()
            if(checkvat == '1')
            {
                vat = total/100*7
                var vatfor = vat.toFixed(2)
                addCommasVat(vatfor)
                CountTotalAll(total,vat)
            }else{
                $('#vat_long').html('0.00')
                CountTotalAll(total,0)
            }
        }

        function addCommasVat(nStr)
        {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '.0';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            var total = x1+x2;
            $('#vat_long').html(total)
        }

        function CountTotalAll(total,vat)
        {
            count = total+vat
            counttotal = count.toFixed(2)
            // Payment(counttotal)
            nStr = counttotal
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
            $('.total_all_bill').text(total)
            $('.convertText').text(BAHTTEXT(total));
            // console.log(total,'dddd');
        }

        function checkfile(newfile)
        {

            if(newfile)
            {
                $('#name_file').html(newfile)
            }else{
                name_file_have = $('#name_file_have').val()
                if(name_file_have != null && name_file_have != '')
                {
                    urlfile =  window.location.origin+'/storage/delivery/'+name_file_have
                    console.log(urlfile);
                    html = '<a href="'+urlfile+'" target="_blank" class="btn-link">'+name_file_have+'</a>'
                    $('#name_file').html(html)
                }else{
                    $('#name_file').html('ยังไม่ได้แนมไฟล์(.pdf,.jpg,.png)')
                }
            }
        }

        function printBilling(name){

            var printContents = document.getElementById('printBillings').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

        }
    </script>
@endpush


@stop
