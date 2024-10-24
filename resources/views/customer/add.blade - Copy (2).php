@extends('layout.master')
@push('plugin-styles')

@endpush
@push('style')
<style>
    ul.tab_customer{
        margin-top: 15px;
        border-bottom: 1px solid #C7C7C7;
    }
    li.nav-item.item_tab_customer {
        background: #EFEFEF;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        margin-right: 3px;
        padding-bottom: 2px;
    }
    li.nav-item.item_tab_customer.active {
        background: #Ad0018;
        color: #FFFF;
    }

    a.link_customer{
        color: #A9A9A9;
        font-size: 14px;
    }
    a.link_customer.active{
        color: #FFFF;
    }
    .box-conten {
        padding-top: 30px;
        padding-bottom: 30px;
    }
    label.title-input-customer {
        text-align: left;
        display: block;
        margin-top: 15px;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .padding-rifht-0{
        padding-right: 0
    }
    .padding-left-0{
        padding-left: 0
    }
    .padding-0{
        padding: 0
    }
    .btn:disabled:hover {
    color: #A9A9A9;
    }
    .btn:disabled {
        border: none;
    }
    .form-control.errorinput{
        border: 1px solid #e72424;
    }
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="form-inline">
                    <div class="col-sm-6">
                        <h3 class="title-page">รายชื่อร้านค้า</h3>
                    </div>
                    <div class="text-right col-sm-6">
                        {{-- <a href="{{url('/customer/add')}}" class="btn btn-add mb-2" id="btn_expense"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มร้านค้า</a> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="nav tab_customer">
                            <li class="nav-item item_tab_customer item_customer active">
                            <a class="nav-link link_customer active" href="javascript:void(0)">ข้อมูลร้านค้า</a>
                            </li>
                            <li class="nav-item item_tab_customer item_pocket">
                            <a class="nav-link link_customer" href="javascript:void(0)">ข้อมูล Pocket Money</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <form action="{{route('customer.save')}}" method="POST" id="customerform">
                    {{ csrf_field() }}
                    <div class="box-conten">
                        <div class="row">
                            <div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">ประเภทร้านค้า <span class="red">*</span></label>
									<div class="input-group col-sm-12">
										<select class="form-control" id="customer_type" name="customer_type">
											<option value=1>ร้านค้าประจำ</option>
											<option value=2>ร้านค้าขาจร</option>
										</select>
                                    </div>
                                </div>
                            </div>
							<div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">เลขประจำตัวผู้เสียภาษี <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="text" required name="text_number_vat" id="text_number_vat" value="" maxlength="13" minlength ="13" placeholder="กรอกเลขประจำตัวผู้เสียภาษี..." onchange="search_text_vat(this.value)">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">ชื่อร้านค้า <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="text" required name="store_name" id="store_name" value="" placeholder="กรอกขื่อร้านค้า..." onchange="search_store_name(this.value)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4 form-inline padding-0">
                                    <div class="col-sm-6 padding-rifht-0">
                                        <label class="col-sm-12 title-input-customer">ผู้ติดต่อ <span class="red">*</span></label>
                                        <div class="input-group col-sm-12">
                                            <input class="form-control" type="text" required name="fname" value="" placeholder="กรอกชื่อผู้ติดต่อ...">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 padding-left-0">
                                        <label class="col-sm-12 title-input-customer"><br></label>
                                        <div class="input-group col-sm-12">
                                            <input class="form-control" type="text" required name="lname" value="" placeholder="กรอกนามสกุล...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 form-inline padding-0">
                                    <div class="col-sm-6 padding-rifht-0">
                                        <label class="col-sm-12 title-input-customer">เบอร์โทรศัพท์ <span class="red">*</span></label>
                                        <div class="input-group col-sm-12">
                                            <input class="form-control" type="text" required name="customer_phone" max="10" min="10" value="" placeholder="กรอกเบอร์โทรศัพท์...">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 padding-left-0">
                                        <label class="col-sm-12 title-input-customer">อีเมล <span class="red"></span></label>
                                        <div class="input-group col-sm-12">
                                            <input class="form-control" type="email" name="customer_mail" id="customer_mail" onchange="search_customer_mail(this.value)" value="" placeholder="กรอกอีเมล...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8">
                                    <label class="col-sm-12 title-input-customer">ที่อยู่ร้านค้า <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="text" required name="customer_address" value="" placeholder="กรอกที่อยู่ร้านค้า บ้านเลขที่,ชื่ออาคาร,ซอย,ถนน...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4 form-inline padding-0">
                                    <div class="col-sm-6 padding-rifht-0">
                                        <label class="col-sm-12 title-input-customer">จังหวัด <span class="red">*</span></label>
                                        <div class="input-group col-sm-12">
                                            <select name="province_code" required class="form-control" id="provinceselect"  onchange="selectprovince(this.value)">
                                                <option value="">-เลือก-</option>
                                                @foreach($provinces as $province)
                                                <option value="{{$province->province_code}}">{{$province->province_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 padding-left-0">
                                        <label class="col-sm-12 title-input-customer">อำเภอ <span class="red"></span></label>
                                        <div class="input-group col-sm-12">
                                            <select name="amphoe_code" class="form-control" id="amphoeselect" onchange="selectamphoe(this.value)">
                                                <option value="">-เลือก-</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 form-inline padding-0">
                                    <div class="col-sm-6 padding-rifht-0">
                                        <label class="col-sm-12 title-input-customer">ตำบล <span class="red"></span></label>
                                        <div class="input-group col-sm-12">
                                            <select name="district_code" class="form-control" id="districtselect" onchange="selectdistrict(this.options[this.selectedIndex].getAttribute('zipcode'))">
                                                <option value="">-เลือก-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 padding-left-0">
                                        <label class="col-sm-12 title-input-customer">รหัสไปรษณีย์ <span class="red"></span></label>
                                        <div class="input-group col-sm-12">
                                            <input class="form-control" type="text" name="zip_code" id="zip_code" value="" placeholder="กรอกรหัสไปรษณีย์...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="d-flex flex-row justify-content-center align-items">
                                <a href="{{url('customer')}}"  class="btn btn-secondary btn-fw">ยกเลิก</a>
                                <button type="submit" id="submit" class="btn btn-primary btn-fw">ยืนยันทำรายการ</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('plugin-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@endpush

@push('custom-scripts')
<script>
    $('li.nav-item.sidebar-nav-item.customer-meun').addClass('active');


    check_submit = true;

    function selectprovince(proviceselect)
    {	alert(proviceselect);
        $.ajax({
            url: "{{route('customer.amphoe')}}",
            type: 'post',
            data: {proviceselect : proviceselect},
            success: function(res) {
				alert("in success"+JSON.stringify(res));
                $('#amphoeselect').html(res.html)
            },
			error: function(body) {
				alert("in error"+body);
				alert(JSON.stringify(body));
            }
        })
    }

    function selectamphoe(amphoeselect)
    {
        proviceselect = $('#provinceselect').val()
        $.ajax({
            url: "{{route('customer.district')}}",
            type: 'post',
            data: {amphoeselect : amphoeselect , proviceselect:proviceselect},
            success: function(res) {
                $('#districtselect').html(res.html)
            }
        })
    }

    function selectdistrict(zipcode)
    {
        $('#zip_code').val(zipcode)
    }

    function search_store_name(name)
    {
        check_submit = false
        $.ajax({
            url: "{{route('customer.cehckstore')}}",
            type: 'post',
            data: {store_name : name},
            success: function(res) {
                check_submit = res.status
                if(res.status === false)
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'ชื่อร้านค้านี้มีอยู่แล้ว',
                        showConfirmButton: true,
                        confirmButtonText: 'ปิดหน้าต่าง',
                    });
                    $('#submit').prop('disabled', true);
                    $('#store_name').addClass('errorinput')
                }else{
                    $('#submit').prop('disabled', false);
                    $('#store_name').removeClass('errorinput')
                }


            }
        })
        // console.log(name);
    }

    function search_text_vat(textvat)
    {
        check_submit = false
        $.ajax({
            url: "{{route('customer.cehcktextvat')}}",
            type: 'post',
            data: {text_number_vat : textvat},
            success: function(res) {
                check_submit = res.status
                if(res.status === false)
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'เลขประจำตัวผู้เสียภาษีนี้มีอยู่แล้ว',
                        showConfirmButton: true,
                        confirmButtonText: 'ปิดหน้าต่าง',
                    });
                    $('#submit').prop('disabled', true);
                    $('#text_number_vat').addClass('errorinput')
                }else{
                    $('#submit').prop('disabled', false);
                    $('#text_number_vat').removeClass('errorinput')
                }


            }
        })
        // console.log(name);
    }

    function search_customer_mail(customer_mail)
    {
        check_submit = false
        $.ajax({
            url: "{{route('customer.cehckcustomermail')}}",
            type: 'post',
            data: {customer_mail : customer_mail},
            success: function(res) {
                check_submit = res.status
                if(res.status === false)
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'อีเมลนี้มีอยู่แล้ว',
                        showConfirmButton: true,
                        confirmButtonText: 'ปิดหน้าต่าง',
                    });
                    $('#submit').prop('disabled', true);
                    $('#customer_mail').addClass('errorinput')
                }else{
                    $('#submit').prop('disabled', false);
                    $('#customer_mail').removeClass('errorinput')
                }


            }
        })
        // console.log(name);
    }

    $('#customerform').on('submit',function(e){
        if(check_submit === false)
        {

            e.preventDefault()
            // Swal.fire({
            //     icon: 'error',
            //     title: 'ชื่อร้านค้านี้มีอยู่แล้ว',
            //     showConfirmButton: true,
            //     confirmButtonText: 'ปิดหน้าต่าง',
            // });
        }else{
            swal.fire({
                title: 'กำลังบันทึกข้อมูล..',
                onOpen: () => {
                  swal.showLoading()
                }
          });
        }

    });
</script>
@endpush

@stop
