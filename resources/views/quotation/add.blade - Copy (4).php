@extends('layout.master')
@push('plugin-styles')
{!! Html::style('plugins/jquery-ui/jquery-ui.css') !!}
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
            font-size: 12px;
            margin-bottom: 2px;
        }
        div.box-note{
            padding-top: 15px;
            padding-bottom: 15px;
            background:#F6F8FB;
        }
        div.box-heard{
            padding: 20px 15px 10px 15px;
            background:#666983;
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
        .card-body.header-card {
            padding: 0px;
        }
        p.s-customer {
            padding-right: 10px;
            margin-top: 2px;
            color: #FFFF;
        }
        button.btn-search-customer {
            background: #2D2D35;
            color: #FFFF;
        }
        .search-customer { padding: 0px 10px 0px 10px; }
        button.btn.btn-reload {
            border: 1px solid #ffff;
            color: #ffff;
        }
        select.form-control.form-control-sm {
            font-size: 12px;
            padding: 2px;
        }
        .btn.btn-icons {
            width: 20px;
            height: 20px;
            padding: 0px;
            font-size: 12px;
        }

        /* .btn-remove-row{

        } */

        @media (max-width: 576px) {
            div.box-note-status,div.box-note {
                padding-right: 15px;
            }
            .btn-search-customer{
                width: 100%;
                margin-top: 15px;
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
                    <h3 class="title-page">สร้างใบเสนอราคา </h3>
                </div>
                <div class="text-right col-sm-6">
                    {{-- <a href="{{url('quotation/add')}}" class="btn btn-add mb-2" id="btn_expense"><i class="fa fa-plus" aria-hidden="true"></i> สร้างใบเสนอราคา</a> --}}
                </div>
            </div>
            {{-- <div class="row"> --}}
                {{-- <div class="col-sm-12"> --}}
                    <div class="crad">
                        <div class="card-body header-card">
                            <div class="box-heard">
                                <div class="form-row">
                                    <p class="s-customer col-sm-auto">ชื่อร้านค้า</p>
                                    <div class="col-sm-auto col-md-3 ">
                                        <input class="form-control form-control-sm" id="searchcustomer" autocomplete="off"  type="text" name="customer" value="" placeholder="ค้นหาร้านค้า...">
                                    </div>
                                    <div class="search-customer col-sm-auto col-md-5">
                                        <button type="button" onclick="customer()" class="btn btn-search-customer"><i class="fa fa-search" aria-hidden="true"></i></button>
                                        <button type="button" onclick="customerreset()" class="btn btn-reload"><i class="mdi mdi-reload"></i> Reset</button>
                                    </div>
                                    {{-- <div class="search-customer col-sm-auto col-md-2">
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- </div> --}}
            {{-- </div> --}}
            <form id="createquotation" action="{{route('quotation.save')}}" method="POST">
                <input type="hidden" name="customerid" id="customerid">
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="input-group col-sm-3 input-header">
                        <label class="col-sm-12 header-title">วันที่สร้างใบเสนอราคา</label>
                        <div class="col-sm-12">
                            <input class="form-control form-control-sm" type="text"  disabled value="{{date('d/m/Y H:i:s')}}">
                            <input type="hidden" name="created_at" value="{{date('d/m/Y H:i:s')}}">
                        </div>
                    </div>
                    <div class="input-group col-sm-3 input-header">
                      <label class="col-sm-12 header-title">ชื่อร้านค้า </label>
                        <!-- <label class="col-sm-12 header-title">ชื่อร้านค้า <span class="red">*</span> </label> -->
                        <div class="col-sm-12">
                            <input class="form-control form-control-sm" type="text" name="store_name" id="store_name"  value="">
                        </div>
                    </div>
                    <div class="input-group col-sm-3 input-header">
                        <label class="col-sm-12 header-title">เลขประจำตัวผู้เสียภาษี</label>
                        <div class="col-sm-12">
                            <input class="form-control form-control-sm" type="text" name="text_number_vat"  id="text_number_vat"  value="">
                        </div>
                    </div>
                    <div class="input-group col-sm-3 input-header">
                        <label class="col-sm-12 header-title">Pocket Money</label>
                        <div class="col-sm-12">
                            <input class="form-control form-control-sm" type="text" name="pocketmoney" id="pocketmoney"  disabled   value="">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group col-sm-3 input-header">
                      <label class="col-sm-12 header-title">ผู้ติดต่อ </label>
                        <!-- <label class="col-sm-12 header-title">ผู้ติดต่อ <span class="red">*</span></label> -->
                        <div class="col-sm-12">
                            <input class="form-control form-control-sm" type="text" name="customer_name" id="customer_name"  value="">
                        </div>
                    </div>
                    <div class="input-group col-sm-3 input-header">
                      <label class="col-sm-12 header-title">เบอร์โทรศัพท์ </label>
                        <!-- <label class="col-sm-12 header-title">เบอร์โทรศัพท์ <span class="red">*</span></label> -->
                        <div class="col-sm-12">
                            <input class="form-control form-control-sm" type="text" name="customer_phone" id="customer_phone"  value="">
                        </div>
                    </div>
                    <div class="input-group col-sm-3 input-header">
                        <label class="col-sm-12 header-title">อีเมล์</label>
                        <div class="col-sm-12">
                            <input class="form-control form-control-sm" type="text" name="customer_mail" id="customer_mail"   value="">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group col-sm-12 input-header">
                        <label class="col-sm-12 header-title">ที่อยู่ <span class="red">*</span></label>
                        <div class="col-sm-12">
                            <input class="form-control form-control-sm" required type="text" name="customer_address" id="customer_address"  value="">
                        </div>
                    </div>
                </div>
				<div class="form-row">
                    <div class="input-group col-sm-12 input-header">
                        <label class="col-sm-12 header-title">ที่อยู่จัดส่ง <span class="red">*</span></label>
                        <div class="col-sm-12">
                            <input class="form-control form-control-sm" required type="text" name="customer_address_onsite" id="customer_address_onsite"  value="">
                        </div>
                    </div>
                </div>
				<div class="form-row">
                    <div class="input-group col-sm-12 input-header">
                        <label class="col-sm-12 header-title">ชื่อผู้ติดต่อหน้างาน <span class="red">*</span></label>
                        <div class="col-sm-12">
                            <input class="form-control form-control-sm" required type="text" name="contact_person_onsite_name" id="contact_person_onsite_name"  value="">
                        </div>
                    </div>
                </div>
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
                        {{-- <tr class="column" id="row_1" data-id="1">
                            <td><label class="number_on" id="number_1">1</label></td>
                            <td>
                                <input type="text" name="product_name[]" value="" class="form-control form-control-sm">
                            </td>
                            <td>
                                <select name="product_type_id[]" class="form-control form-control-sm select_product_type_id_1"  required  data-product-type="1">
                                    <option value="">-เลือก-</option>
                                        @foreach ($producttypes as $producttype)
                                            <option  value="{{$producttype->id}}">{{$producttype->product_type_name}}</option>
                                        @endforeach
                                </select>
                            </td>
                            <td style="width:8%">
                                <input type="text" name="size_unit[]" value="" class="form-control form-control-sm">
                            </td>
                            <td>
                                <select name="product_size_id[]" class="form-control form-control-sm select_product_size_id_1"  required  data-product-size="1">
                                    <option value="">-เลือก-</option>
                                        @foreach ($productsizes as $producsize)
                                            <option  value="{{$producsize->id}}">{{$producsize->product_size_name}}</option>
                                        @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="price[]" value="" class="form-control form-control-sm text-right">
                            </td>
                            <td>
                                <input type="text" name="number_order[]" value="" class="form-control form-control-sm text-right">
                            </td>
                            <td>
                                <select name="countunit[]" class="form-control form-control-sm select_countunit_1"  required  data-countunit="1">
                                    <option value="">-เลือก-</option>
                                    @foreach (countunit() as $countunit)
                                    <option   value="{{$countunit->id}}">{{$countunit->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="total_item[]" value="" class="form-control form-control-sm text-right">
                            </td>
                            <td>
                                <input type="text" name="note[]" value="" class="form-control form-control-sm text-right">
                            </td>
                            <td>

                            </td>
                        </tr> --}}
                    </tbody>
                </table>
                </div>
                <div class="col-sm-12 m-t-15">
<script>
i = 0;
</script>
                    <a class="btn btn-apped" onclick="i++; alert('i = '+i); append.appendrow(i)"  >+ เพิ่มรายการ</a>
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
							<label class="col-sm-6 header-title">หมายเหตุ ที่ 1 <input class="form-control form-control-sm" type="text" id=remark_1 name=remark_1  value="-"></label>
							<label class="col-sm-6 header-title">หมายเหตุ ที่ 2 <input class="form-control form-control-sm" type="text" id=remark_2 name=remark_2  value="-"></label>
							<label class="col-sm-6 header-title">หมายเหตุ ที่ 3 <input class="form-control form-control-sm" type="text" id=remark_3 name=remark_3  value="-"></label>
							<label class="col-sm-6 header-title">หมายเหตุ ที่ 4 <input class="form-control form-control-sm" type="text" id=remark_4 name=remark_4  value="-"></label>
							<label class="col-sm-6 header-title">หมายเหตุ ที่ 5 <input class="form-control form-control-sm" type="text" id=remark_5 name=remark_5  value="-"></label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex flex-row justify-content-center align-items">
                            <button type="button" onclick="window.location.href='/quotation'"  class="btn btn-secondary btn-fw">ยกเลิก</button>
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
{!! Html::script('/js/inputmoney.js') !!}
{!! Html::script('/js/inputnumber.js') !!}
{!! Html::script('/plugins/jquery-ui/jquery-ui.js') !!}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@endpush

@push('custom-scripts')
    <script>
        $('#createquotation').on("submit", function(event) {
          swal.fire({
                title: 'กำลังบันทึกข้อมูล..',
                onOpen: () => {
                  swal.showLoading()
                }
          });
        });
        $(document).ready(function() {
            $('li.nav-item.sidebar-nav-item.quotation-meun').addClass('active');
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $( "#searchcustomer" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url:"{{route('customer.searchcustomer')}}",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                $('#customerid').val(ui.item.id);
            }
        })
        function customer()
        {
            var customerid = $('#customerid').val()
            searchcustomer = $('#searchcustomer').val()

            if(customerid == '')
            {
                if(searchcustomer != '')
                {
                    Swal.fire({
                            icon: 'error',
                            text: 'ไม่พบร้านค้าในระบบ',
                            showConfirmButton: true,
                            confirmButtonText: 'ปิดหน้าต่าง',
                    });
                }else{
                    Swal.fire({
                            icon: 'error',
                            text: 'กรุณาค้นหาชื่อร้านค้าก่อน',
                            showConfirmButton: true,
                            confirmButtonText: 'ปิดหน้าต่าง',
                    });
                }

            }else{
                $.ajax({
                    url: "{{route('customer.datacustomer')}}",
                    type: 'post',
                    data: {customerid:customerid},
                    cache: false,
                    success: function(res){
                        console.log(res);
                        var address = res.customer_address+' ตำบล.'+res.district_name+' อำเภอ.'+res.amphoe_name+' จังหวัด.'+res.province_name+' '+res.zip_code
                        $('#store_name').val(res.store_name)
                        $('#text_number_vat').val(res.text_number_vat)
                        if(res.pocket_money != null)
                        {
                            $('#pocketmoney').val(res.pocket_money.pocket_money)
                        }else{
                            $('#pocketmoney').val(0.00)
                        }
                        $('#customer_name').val(res.customer_name)
                        $('#customer_phone').val(res.customer_phone)
                        $('#customer_mail').val(res.customer_mail)
                        $('#customer_address').val(address)
                },
                error: function(){
                    alert('error!')
                }
            });
            }


        }

        function customerreset()
        {
            $('#searchcustomer').val('')
            $('#customerid').val('')
            $('#store_name').val('');
            $('#text_number_vat').val('')
            $('#pocketmoney').val('')
            $('#customer_name').val('')
            $('#customer_phone').val('')
            $('#customer_mail').val('')
            $('#customer_address').val('')
        }
        var append =
        {
                appendrow:(iii) =>
                {
                    var ck_customer = true;
                    // if($('#store_name').val() == '')
                    // {
                    //     ck_customer = false
                    // }
                    // if($('#customer_name').val() == '')
                    // {
                    //     ck_customer = false
                    // }
                    // if($('#customer_phone').val() == '')
                    // {
                    //     ck_customer = false
                    // }
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
                                    // html +='<textarea  name="product_name[]" class="form-control form-control-sm" rows="1" cols="50"></textarea>'
                                    html +='<input type="text" name="product_name['+iii+']" id="product_name['+iii+']" required value="" class="form-control form-control-sm">'
									html +='<br/><button type="button" class="btn btn-primary btn-fw" onclick="open_to_select_product('+iii+');">เลือกสินค้า</button>'
                                html +='</td>'
                                html +='<td>'
                                    html +='<select name="product_type_id['+iii+']" class="form-control form-control-sm select_product_type_id_'+newid+'" onchange="append.productsize('+newid+');"  required  data-product-type="'+newid+'">'
                                        html +='<option value="">-เลือก-</option>'
                                    html +='</select>'
// Pera adds 	
									html +='<select name="pera['+iii+']" class="form-control form-control-sm" >'
                                        html +='<option value="">-เลือก-</option>'
										html +='<option value="ลวด 4 เส้น">-ลวด 4 เส้น-</option>'
										html +='<option value="ลวด 5 เส้น">-ลวด 5 เส้น-</option>'
										html +='<option value="ลวด 6 เส้น">-ลวด 6 เส้น-</option>'
										html +='<option value="ลวด 7 เส้น">-ลวด 7 เส้น-</option>'

                                    html +='</select>'
									html +='<select name="pera_2['+iii+']" class="form-control form-control-sm" >'
										html +='<option value="">ไม่ show เหล็กข้าง</option>'
										html +='<option value="เหล็กข้าง">show เหล็กข้าง</option>'
                                    html +='</select>'
// Pera end add 										
                                html +='</td>'
                                html +='<td style="width:8%">'
                                    html +='<input type="text" name="size_unit['+iii+']" disabled value="" data-type="number" class="form-control form-control-sm size_unit_'+newid+'" required  onkeyup="append.summoney('+newid+')">'
                                    html +='<input type="hidden" name="size_unit_count['+iii+']" value="" class="form-control form-control-sm size_unit_count_'+newid+'" >'
                                html +='</td>'
                                html +='<td>'
                                    html +='<select name="product_size_id['+iii+']" class="form-control form-control-sm select_product_size_id_'+newid+'" onchange="append.summoney('+newid+')"  required  data-product-size="'+newid+'">'
                                        html +='<option value="">-เลือก-</option>'
                                    html +='</select>'
                                html +='</td>'
                                html +='<td>'
                                    html +='<input type="text" name="price['+iii+']" disabled data-type="currency" value="" class="form-control form-control-sm text-right price_check price_'+newid+'" required onkeyup="append.summoney('+newid+')">'
                                html +='</td>'
                                html +='<td>'
                                    html +='<input type="text" name="number_order['+iii+']" disabled data-type="number" value="" class="form-control form-control-sm text-right number_order_'+newid+'" required onkeyup="append.summoney('+newid+')">'
                                html +='</td>'
                                html +='<td>'
                                    html +='<select name="countunit['+iii+']" class="form-control form-control-sm select_countunit_'+newid+'"  required  data-countunit="'+newid+'">'
                                        html +='<option value="">-เลือก-</option>'
                                    html +='</select>'
                                html +='</td>'
                                html +='<td>'
                                    html +='<input type="text" name="total_item['+iii+']" readonly value="" class="form-control form-control-sm text-right total_item total_item_'+newid+'">'
                                html +='</td>'
                                html +='<td>'
                                    html +='<input type="text" name="note['+iii+']" value="" class="form-control form-control-sm text-right">'
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
                    append.CountTotalAll()
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
                        }
                        else if (select_size == 'จำนวน * ราคา' || select_size == 'เมตร') {
                          var price = $('.price_'+id).val()
                          var number_order = $('.number_order_'+id).val()
                          var count = (number_order*1)*(price*1)
                          $('.size_unit_count_'+id).val(count)
                        }
                        else{
                            var count = $('.size_unit_'+id).val()
                            $('.size_unit_count_'+id).val(count)
                        }
                    }
                },
                summoney : (id) => {
                    var total = 0;
                    var select_size_val = $('.select_product_size_id_'+id).attr('data-size');

                      if(select_size_val != ''){
                            var select_size = $('option:selected', '.select_product_size_id_'+id).attr('data-size');
                            if(select_size == 'x 0.35 ตร.ม.'){
                              var sizeunitstr = $('.size_unit_'+id).val()
                              var ressizeunit = sizeunitstr.replaceAll(",", "");
                              var sizeunit = Number(ressizeunit);
                              var count = sizeunit*0.35
                              $('.size_unit_count_'+id).val(count)
                            }
                            // else if (select_size == 'จำนวน * ราคา' || select_size == 'เมตร') {
                            //   var price = $('.price_'+id).val()
                            //   var ressizeprice = price.replaceAll(",", "");
                            //   var priceunit = Number(ressizeprice);
                            //
                            //   var number_order = $('.number_order_'+id).val()
                            //   var ressizenumber_order = number_order.replaceAll(",", "");
                            //   var number_orderunit = Number(ressizenumber_order);
                            //
                            //   var count = priceunit*number_orderunit
                            //   $('.size_unit_count_'+id).val(count)
                            // }
                            else{
                                var sizeunitstr = $('.size_unit_'+id).val()
                                var ressizeunit = sizeunitstr.replaceAll(",", "");
                                var sizeunit = Number(ressizeunit);
                                var count = sizeunit
                                $('.size_unit_count_'+id).val(count)
                            }
                        }

                        // if (select_size == 'จำนวน * ราคา' || select_size == 'เมตร') {
                        //   var total = $('.size_unit_count_'+id).val();
                        //   var totalfixed = total.toLocaleString(undefined, {minimumFractionDigits: 2});
                        //   append.CountTotalRow(totalfixed,id)
                        // }
                        // else {
                          var size_unit_count =$('.size_unit_count_'+id).val()
                          var number_orderstr =$('.number_order_'+id).val()
                          var resnumber_orderstr = number_orderstr.replaceAll(",", "");
                          var number_order = Number(resnumber_orderstr);
                          var str = $('.price_'+id).val()
                          // console.log('dddddd :'+str+' : '+str.replace(",", ""));
                          var price  = 0
                          if(typeof(str) !== "undefined")
                          {
                              var res = str.replaceAll(",", "");
                              var price = Number(res);

                              if (price > 1000000) {
                                  price = 1000000;
                                  $('.price_'+id).val('1,000,000')
                              }

                              console.log('eeeeee ' + price);


                          }
                          total = size_unit_count * number_order * price;
                          totalfixed = total.toFixed(2)
                          append.CountTotalRow(totalfixed,id)
                        // }

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
                        var res = str.replaceAll(",", "");
                        // var res = str.replace(",", "");
                        // var res1 = res.replace(",", "");
                        // var res2 = res1.replace(",", "");
                        // var res3 = res2.replace(",", "");
                        // var res4 = res3.replace(",", "");
                        // var res5 = res4.replace(",", "");
                        // var res6 = res5.replace(",", "");
                        // var res7 = res6.replace(",", "");
                        total += Number(res);
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
    </script>
@endpush

@push('custom-scripts')
<script>
	function open_to_select_product(i_pera)
	 {
		var url_safe_username = encodeURIComponent("username");
		var url_safe_password = encodeURIComponent("password");
		window.open("list_products?i_pera="+i_pera,"_blank","height=500,width=400,status=yes,toolbar=no,menubar=no,location=no")
	 }
</script>
@endpush
@stop