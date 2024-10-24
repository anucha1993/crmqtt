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
            font-size: 11px;
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
        div.d-flex-s{
            display: flex;
        }
        a.btn.btn-secondary.btn-fw {
            padding: 10px;
        }
        @media (max-width: 576px) {
            div.box-note-status,div.box-note {
                padding-right: 15px;
            }
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
                    <h3 class="title-page">สร้างบิลหลัก {{$quotation->quotation_number}}</h3>
                </div>
                <div class="text-right col-sm-6">
                    <button href="#" class="btn btn-print " disabled ><i class="fa fa-print" aria-hidden="true"></i> Print ใบกำกับภาษี</button>
                    <button href="#" class="btn btn-print " disabled ><i class="fa fa-print" aria-hidden="true"></i> Print บิล</button>
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">วันที่สร้างใบเสนอราคา</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{date('d/m/Y H:i:s',strtotime($quotation->created_at))}}</p>
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
                        <p class="title_order">{{$quotation->customer_type == 'customer' ? (!empty($customer->PocketMoney) ? number_format($customer->PocketMoney->pocket_money,2) : '0.00') : '0.00'}}</p>
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
                        @if($quotation->customer_type =='customer')
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
                    <button href="#" disabled class="btn btn-order" >รายการบิลย่อย</button>
                    <button href="#" disabled class="btn btn-history" >ประวัติการชำระ</button>
                </div>
            </div>
            <div class="table-responsive">
              <table class="table table-width">
                <thead class="head-table">
                  <tr>
                    <th> ลำดับ </th>
                    <th> ชื่อสินค้า/รายละเอียดสินค้า </th>
                    <th> ประเภทสินค้า </th>
                    <th> ความยาว </th>
                    <th> ราคาต่อหน่วย </th>
                    <th> จำนวน </th>
                    <th> หน่วยนับ </th>
                    <th> จำนวนเงิน </th>
                    <th> ส่งแล้ว </th>
                    <th> ค้างส่ง </th>
                    <th> หมายเหตุ </th>
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
                            <td><p class="p-number_order">{{number_format($data->number_order)}}</p></td>
                            <td>{{countunitstr($data->count_unit)}}</td>
                            <td>
                                {{number_format($data->total_item,2)}}
                                <input type="hidden" value="{{$data->total_item}}" class="total_item">
                            </td>
                            <td><p class="p-send">0<p></td>
                            <td><p class="p-not-send">0</p></td>
                            <td>{{$data->note}}</td>
                        </tr>
                    @endforeach

                </tbody>
              </table>
            </div>
            <form id="createorder" action="{{route('orders.save')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="quotationid" value="{{$quotation->id}}">
                <div class="row">
                    <p class="col-sm-10 text-right m-t-15 warning-text">ยอดรวมสั่งซื้อ</p>
                    <p class="col-sm-2 text-right m-t-15 warning-text " id="total_items_all">
                        0.00
                    </p>
                    <input type="hidden" name="total_items_all" value=""  id="input_total_items_all">
                    <p class="col-sm-10 text-right warning-text">
                        <input type="checkbox" name="on_vat"  {{Auth::user()->role_id == 2 ? 'disabled' : ''}}  {{Auth::user()->role_id == 2 ? 'checked' : ''}} value="1" class="form-control form-control-sm col-sm-1 on_vat" onclick="checkvat()">
                        <label >ภาษีมูลค่าเพิ่ม 7%</label>
                    </p>
                    <input type="hidden" name="vat" value="0.00" id="vat">
                    <p class="col-sm-2 text-right warning-text" id="vat_total">0.00</p>

                    <p class="col-sm-10 text-right warning-text">ประเภทจ่ายเงิน</p>
                    <p class="col-sm-2 text-right warning-text money-color">
                        <select name="payment_type" id="select_payment"  class="form-control form-control-sm select_payment" onchange="selectpaymenttype()">
                            <option value="">เลือกการจ่ายเงิน</option>
                            <option value="1">มัดจำ</option>
                            <option value="2">จ่ายหน้างาน</option>
                            <option value="3">จ่ายด้วย Credit</option>
                            @if($quotation->customer_type == 'customer')
                                <option value="4">จ่ายด้วย Pocket Money</option>
                            @endif
                        </select>
                    </p>

                    <p class="col-sm-10 text-right warning-text type_deposit">จำนวนเงินมัดจำ</p>
                    <p class="col-sm-2 text-right warning-text money-color type_deposit">
                        <input type="text" name="payment" id="paymentdeposit" data-type='currency' onkeyup="inputpaymentdeposit(this.value)" class="form-control form-control-sm" value="">
                    </p>

                    <p class="col-sm-10 text-right warning-text type_pocket">ยอด Pocket ปัจจุบัน</p>
                    <p class="col-sm-2 text-right warning-text type_pocket" id="pocket_money">{{number_format($pocketmoney,2)}}</p>

                    <p class="col-sm-10 text-right warning-text">ยอดรวมทั้งสิ้น</p>
                    <p class="col-sm-2 text-right warning-text" id="total_all">0.00</p>
                    <input type="hidden" name="total_all" value="" id="input_total_all">

                    <p class="col-sm-10 text-right warning-text type_deposit">ยอดที่ยังไม่ได้ชำระคงเหลือ</p>
                    <p class="col-sm-2 text-right warning-text type_deposit" id="type_deposit_payment">0.00</p>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="crad">
                            <div class="card-body">
                                <div class="d-flex flex-row justify-content-center align-items box-note-status">
                                    <p class="col-sm-2 text-right">หมายเหตุ :</p>
                                    <input class="col-sm-6 form-control form-control-sm" type="text" name="note" value="" placeholder="กรอกหมายเหตุ...">
                                </div>
                                <div class="d-flex-s flex-row justify-content-center align-items box-note box-file">
                                    <p class="col-sm-2 text-right">ไฟล์แนบ :</p>
                                    <label for="file-upload" class="btn btn-upload changfile">
                                        <i class="fa fa-upload"></i> <span class="label_upload">เลือกไฟล์แนบ</span>
                                    </label>
                                    <input id="file-upload" type="file" name="file" value="" style="display: none;" accept=" .jpg, .png, .pdf"><span class="warning-file" id="name_file">ยังไม่ได้แนบไฟล์(.pdf,.jpg,.png)</span>
                                </div>
                                @if(Auth::user()->role_id == 1)
                                <div class="d-flex flex-row justify-content-center align-items box-note">
                                    <p class="col-sm-2 text-right">สถานะบิลหลัก <span class="red">*</span> :</p>
                                    <select name="status" onchange="selectStatus(this.value)" required class="col-sm-2 form-control form-control-sm select_status">
                                        <option value="">สถานะทั้งหมด</option>
                                        <option selected value="0">รอยืนยัน</option>
                                        <option value="1">ยืนยัน</option>
                                        <option value="2">ยกเลิก</option>
                                    </select>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex flex-row justify-content-center align-items">
                            <a href="{{url('quotation')}}" class="btn btn-secondary btn-fw">ย้อนกลับ</a>
                            <button type="submit" class="btn btn-primary btn-fw">ยืนยันทำรายการ</button>
							<button type="submit" class="btn btn-primary btn-fw">ยืนยันทำรายการแบบ => ไม่แสดงราคา</button>
                        </div>
                    </div>
                </div>
            </form>
          </div>
        </div>
    </div>
</div>

@push('plugin-scripts')
    {!! Html::script('/js/inputmoney.js') !!}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@endpush

@push('custom-scripts')
    <script>

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
        $('#createorder').on("submit", function(event) {
            if($('.select_payment').val() == 4)
            {
                var input_total_all = $('#input_total_all').val()
                var pocket_money_str = $('#pocket_money').html()
                var res = pocket_money_str.replace(",", "");
                var res1 = res.replace(",", "");
                var res2 = res1.replace(",", "");
                var res3 = res2.replace(",", "");
                var res4 = res3.replace(",", "");
                var res5 = res4.replace(",", "");
                pocket_money = Number(res5);
                var check = pocket_money
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
        $(document).ready(function() {
            $('li.nav-item.sidebar-nav-item.quotation-meun').addClass('active');
            $('.box-file').hide()
            $('.type_deposit').hide()
            $('.type_pocket').hide()

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
                    total += Number(res5);
                }
                var totalfor =  total.toFixed(2)
                var vat = Math.round(totalfor/100*7)
                var vatfixed =  vat.toFixed(2)
                addCommasTotalItemsAll(totalfor)
                $('#input_total_items_all').val(totalfor)
                $('#total_all').html(totalfor)
                $('#input_total_all').val(totalfor)
                $('#vat').val(vatfixed)
            }
            checkvat()
        });
        function addCommasTotalItemsAll (nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '.0';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            var total = x1+x2;
            $('#total_items_all').html(total)
        }
        function checkvat()
        {
            var checkedValue = $('.on_vat:checked').val();
            // console.log(checkedValue);
            if(checkedValue == null)
            {
                addCommasTotalVat('0.00');
                input_total_items_all = $('#input_total_items_all').val()
                addCommasTotalAll(input_total_items_all)
                $('#input_total_all').val(input_total_items_all)
            }else{
                getvat = $('#vat').val()
                // console.log(getvat);
                input_total_items_all = $('#input_total_items_all').val()
                addCommasTotalVat(getvat);
                total_all = input_total_items_all*1 + getvat*1
                total_all_toFixed =  total_all.toFixed(2)
                addCommasTotalAll(total_all_toFixed)
                $('#input_total_all').val(total_all_toFixed)
            }
        }

        function addCommasTotalAll (nStr) {
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
            type_deposit_payment()
        }
        function addCommasTotalVat (nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '.0';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            var total = x1+x2;
            $('#vat_total').html(total)
            type_deposit_payment()
        }

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
                $('.type_deposit').hide()
                $('.type_pocket').hide()
                $('.box-file').hide()
                $('#paymentdeposit').prop('required',false);
            }
        }
        function inputpaymentdeposit(intput)
        {
            type_deposit_payment()
        }
        function type_deposit_payment()
        {
            totalall = 0
           totalalls = $('#total_all').html()
           var str =  totalalls;
            var res = str.replace(",", "");
            var res1 = res.replace(",", "");
            var res2 = res1.replace(",", "");
            var res3 = res2.replace(",", "");
            var res4 = res3.replace(",", "");
            var res5 = res4.replace(",", "");
            totalall += Number(res5);

            var str =   $('#paymentdeposit').val();
            var res = str.replace(",", "");
            var res1 = res.replace(",", "");
            var res2 = res1.replace(",", "");
            var res3 = res2.replace(",", "");
            var res4 = res3.replace(",", "");
            var res5 = res4.replace(",", "");
            paymentdeposit = Number(res5);

            addCommasTotalDeposit(totalall-paymentdeposit)
        }

        function addCommasTotalDeposit (nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '.0';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            var total = x1+x2;
            $('#type_deposit_payment').html(total)

        }

        function selectStatus(val)
        {
            console.log(val);
            if(val == 1)
            {
                $('#select_payment').prop('required',true)
            }else{
                $('#select_payment').prop('required',false)
            }
        }

    </script>
@endpush


@stop




