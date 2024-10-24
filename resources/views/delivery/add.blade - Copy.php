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


    </style>
@endpush
@section('content')
<div class="row">
    {!!breadcrumb($breadcrumb)!!}
    <div class="col-lg-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="form-inline">
                <div class="col-sm-12">
                    <h4 class="title-page">xเพิ่มรายบิลย่อย {{$order_delivery_number}} (รายการบิลหลัก {{$orders->order_number}})</h4>
                </div>
            </div>
            <hr>
            <form id="createdelivery" action="{{route('delivery.save')}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="orderid" value="{{$orders->id}}">
                <input type="hidden" name="order_delivery_number" value="{{$order_delivery_number}}">
                <input type="hidden" name="total" id="total_send" value="">
                <input type="hidden" name="hav_to_payment" value="" class="" id="Hav_To_Payment">
                <input type="hidden" name="cutdeposit" value="" class="" id="Cut_Deposit">
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
                                    <input type="hidden" value="{{$data->size_unit}}" class="size_unit_id_{{$data->id}}">
                                    <input type="hidden" value="{{$data->size_name}}" class="size_name_{{$data->id}}">
                                </td>
                                <td class="price_item_{{$data->id}} item_price"  data-id="{{$data->id}}">{{number_format($data->price,2)}}</td>
                                <td>
                                    <p class="p-number_order">{{number_format($data->number_order)}}</p>
                                    <input type="hidden" value="{{$data->total_item}}" class="total_item">
                                </td>
                                <td>{{countunitstr($data->count_unit)}}</td>
                                <td>{{number_format($data->total_item,2)}}</td>
                                <td>{{$data->note}}</td>
                                <td class="p-send">{{$data->number_order-$data->item_send}}</td>
                                <td>
                                    <input type="text" name="items_send[{{$data->id}}]" class="form-control form-control-sm items_send_{{$data->id}}" onblur="UpdateItmes({{$data->id}})"  {{$data->number_order == $data->item_send ? 'disabled' : ''}} value="{{$data->number_order-$data->item_send}}" max="{{$data->number_order-$data->item_send}}" min="0" data-max="{{$data->number_order-$data->item_send}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div class="row">
                    <input type="hidden" value="{{$orders->on_vat}}" id="check_vat">
                    <input type="hidden" value="{{$paymentcount}}" id="paymentcount">
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
                                    <p class="col-sm-6 warning-text text-right">{{ $orders->on_vat == 1 ? number_format($orders->vat,2) : '0.00'}}</p>
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
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-inline left-border bottom-border">
                                    <p class="col-sm-6 m-t-15 warning-text">ยอดในบิลย่อยครั้งนี้</p>
                                    <p class="col-sm-6 m-t-15 warning-text text-right" id="total_long_order">{{number_format($orders->price_all,2)}}</p>
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
                                    <p class="col-sm-6 warning-text text-right" id="cutdeposit">{{number_format($orders->vat,2)}}</p>
                                    @else
                                    <p class="col-sm-6 warning-text text-right" id="cutdeposit" style="display: none">{{number_format($orders->vat,2)}}</p>
                                    @endif

                                    <p class="col-sm-6 warning-text">ยอดที่ต้องชำระ</p>
                                    <p class="col-sm-6 warning-text text-right" id="HavToPayment">{{number_format($orders->vat,2)}}</p>
                                    @if($orders->payment_type == 3)
                                    <p class="col-sm-6 warning-text">วันที่ชำระ</p>
                                    <p class="col-sm-6 input-group warning-text text-right">
                                        <input type="text" name="date_payment" class="form-control datepicker" value="">
                                        <span class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </span>
                                        </span>
                                    </p>
                                    @endif

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
                                            <p class="col-sm-8 m-t-15 warning-text text-right">สถานะการจัดส่ง :<span class="red">*</span></p>
                                            <div class="input-group col-sm-4">
                                                <select required class="form-control" name="status">
                                                    <option value=""> เลือกสถานะ</option>
                                                    <option selected value="0"> กำลังดำเนินการ</option>
                                                    <option value="1"> จัดส่งสำเร็จ</option>
                                                    <option value="2"> ยกเลิก</option>
                                                <select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-inline">
                                            <p class="col-sm-2 m-t-15 warning-text text-date-send">วันที่จัดส่ง :<span class="red">*</span></p>
                                            <div class="input-group col-sm-5">
                                                <input type="text" class="form-control datepicker" required autocomplete="off" name="date_send" value="">
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
                                                <select class="form-control" required name="status_payment">
                                                    <option value=""> เลือกสถานะ</option>
                                                    <option selected value="0">รอชำระ</option>
                                                    <option value="1"> ชำระแล้ว</option>
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
@push('plugin-scripts')
{!! Html::script('/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') !!}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true
            });


            $('li.nav-item.sidebar-nav-item.orders-meun').addClass('active');
            UpdateItmes()
             $('#file-upload').on('change',function(e){
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
            $('#createdelivery').on("submit", function(event) {

                total_long_order_str = $('#total_long_order').html();
                total_all = $('#total_all').html()
                Hav_To_Payment = $('#HavToPayment').html()
                cutdeposit = $('#cutdeposit').html()
                $('#total_send').val(total_all)
                $('#Hav_To_Payment').val(Hav_To_Payment)
                $('#Cut_Deposit').val(cutdeposit)
                var res = total_long_order_str.replace(",", "");
                var res1 = res.replace(",", "");
                var res2 = res1.replace(",", "");
                var res3 = res2.replace(",", "");
                var res4 = res3.replace(",", "");
                var res5 = res4.replace(",", "");
                total_long_order = Number(res5)
               if(total_long_order <= 0)
               {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'เตือน!',
                        text: 'จำเป็นต้องเลือกส่งสินค้าอย่างน้อย 1 รายการ',
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

            });
        });

        function UpdateItmes(i)
        {
            cehckvalue = true
            if(i != null)
            {
                max = $('.items_send_'+i).data('max');
                value = $('.items_send_'+i).val();
                if(value != null)
                {
                    var regex = /\d+/g;
                    var string = $('.items_send_'+i).val();
                    var matches = string.match(regex);  // creates array from matches
                    if(matches==null)
                    {
                        $('.items_send_'+i).val(max);
                        Swal.fire({
                            icon: 'error',
                            title: 'กรอกได้เฉพาะตัวเลขเท่านั้น',
                            showConfirmButton: true,
                            confirmButtonText: 'ปิดหน้าต่าง',
                        });
                    }else{
                        if(isNaN(string))//not number
                        {
                            $('.items_send_'+i).val(max);
                            Swal.fire({
                                icon: 'error',
                                title: 'กรอกได้เฉพาะตัวเลขเท่านั้น',
                                showConfirmButton: true,
                                confirmButtonText: 'ปิดหน้าต่าง',
                            });
                        }
                    }
                    // return false
                }
                if(value > max)
                {
                    $('.items_send_'+i).val(max)
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่สามารถกรอกจำนวนนี้ได้',
                        text: 'เนื่องจากจำนวนที่กรอกเกินจำนวนสินค้าที่เหลืออยู่',
                        showConfirmButton: true,
                        confirmButtonText: 'ปิดหน้าต่าง',
                    });
                }
            }
            if(cehckvalue == true)
            {
                var total = 0;
                var someArray = $('.item_price')
                for (var i = 0; i < someArray.length; i++) {
                    var id =  $(someArray[i]).data('id');
                    var price_item_str = $('.price_item_'+id).html()
                    var res = price_item_str.replace(",", "");
                    var res1 = res.replace(",", "");
                    var res2 = res1.replace(",", "");
                    var res3 = res2.replace(",", "");
                    var res4 = res3.replace(",", "");
                    var res5 = res4.replace(",", "");
                    price_item = Number(res5)
                    var items_send = $('.items_send_'+id).val()
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
                console.log(total);
                console.log(vat);
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
            Payment(counttotal)
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
        }

        function Payment(counttotal)
        {
            money_deposit_str = $('#money_deposit').html()
            var res_deposit = money_deposit_str.replace(",", "");
            var res_deposit1 = res_deposit.replace(",", "");
            var res_deposit2 = res_deposit1.replace(",", "");
            var res_deposit3 = res_deposit2.replace(",", "");
            var res_deposit4 = res_deposit3.replace(",", "");
            var res_deposit5 = res_deposit4.replace(",", "");
            money_deposit = Number(res_deposit5)

            paymentcount = $('#paymentcount').val()

            payment = Number(counttotal) + money_deposit + Number(paymentcount)

            order_total_all_str = $('#order_total_all').html()
            var res = order_total_all_str.replace(",", "");
            var res1 = res.replace(",", "");
            var res2 = res1.replace(",", "");
            var res3 = res2.replace(",", "");
            var res4 = res3.replace(",", "");
            var res5 = res4.replace(",", "");
            order_total_all = Number(res5)

            if(payment >= order_total_all)
            {
                //counttotal ราคารวมในบิลย่อย
                //usepayment จ่ายแล้วทั้งหมด
                usepayment = order_total_all - (Number(money_deposit) + Number(paymentcount))

                use_money_deposit = Number(counttotal) - Number(usepayment)

                use_money_depositfommat = use_money_deposit.toFixed(2)
                usepaymentfommat = usepayment.toFixed(2)
                addCommasCutDeposit(use_money_depositfommat)
                addCommasHavPay(usepaymentfommat)

            }else{
                counttotal = Number(counttotal)
                usepaymentfommat = counttotal.toFixed(2)
                addCommasCutDeposit('0.00')
                addCommasHavPay(usepaymentfommat)
            }

        }

        function addCommasHavPay(nStr)
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
            $('#HavToPayment').html(total)
        }

        function addCommasCutDeposit(nStr)
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
            $('#cutdeposit').html(total)
        }


    </script>
@endpush


@stop
