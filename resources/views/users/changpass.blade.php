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
                        <h3 class="title-page">เปลี่ยนรหัสผ่าน</h3>
                    </div>
                    <div class="text-right col-sm-6">
                        {{-- <a href="{{url('/customer/add')}}" class="btn btn-add mb-2" id="btn_expense"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มร้านค้า</a> --}}
                    </div>
                </div>

                <form action="{{route('users.changpass')}}" method="POST" id="userform">
                    {{ csrf_field() }}
                    <div class="box-conten">
                        <div class="row">
                            <div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8">
                                    <label class="col-sm-12 title-input-customer">อีเมล <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="text" disabled id="emailinput" name="email" value="{{ Auth::user()->email }}" placeholder="กรอกอีเมล์เข้าใช้งาน...">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">รหัสผ่านใหม่ <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="password" id="password" required onchange="check.regepassword(this.value)" name="password" value="{{isset($edit) ? $edit->password : ''}}"  placeholder="กรอกรหัสผ่าน..." >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">ยืนยันรหัสผ่านใหม่ <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="password" required id="conpassword" name="confirmpassword" value="{{isset($edit) ? $edit->password : ''}}" onchange="check.checkpassword()"  placeholder="กรอกยืนยันรหัสผ่าน...">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="d-flex flex-row justify-content-center align-items">
                                <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
                                <a href="{{url('/')}}"  class="btn btn-secondary btn-fw">ยกเลิก</a>
                                <button type="submit" id="submit"   class="btn btn-primary btn-fw">ยืนยันทำรายการ</button>
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
    // $('li.nav-item.sidebar-nav-item.users-meun').addClass('active');
    var checksubmit = true
    var check =
    {

        regepassword : (pass) =>{

            if(pass.length <= 7)
            {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสไม่ถูกต้อง',
                    text: 'รหัสผ่านต้องมีอย่างน้อย 6 ตัว และต้องเป็นตัวเลขหรือ ภาษาอังกฤษเท่านั้น',
                    showConfirmButton: true,
                    confirmButtonText: 'ปิดหน้าต่าง',
                });
            }else{
                if(/^[a-zA-Z0-9\s\\\/]+$/.test(pass))
                {

                }else{
                    $('#password').val('')
                    Swal.fire({
                        icon: 'error',
                        title: 'รหัสไม่ถูกต้อง',
                        text: 'รหัสผ่านต้องมีอย่างน้อย 6 ตัว และต้องเป็นตัวเลขหรือ ภาษาอังกฤษเท่านั้น',
                        showConfirmButton: true,
                        confirmButtonText: 'ปิดหน้าต่าง',
                    });
                }
            }

        },
        checkpassword:() =>{
            checksubmit = false
            pass = $('#password').val()
            conpass = $('#conpassword').val()
            if(conpass != '')
            {
                if(pass == conpass)
                {
                    $('#conpassword').removeClass('errorinput')
                    $('#password').removeClass('errorinput')
                    checksubmit = true
                    $('#submit').prop('disabled', false);
                }else{
                    checksubmit = false
                    $('#submit').prop('disabled', true);
                    $('#conpassword').val('')
                    $('#conpassword').addClass('errorinput')
                    $('#password').addClass('errorinput')
                    Swal.fire({
                        icon: 'error',
                        title: 'รหัสไม่ตรงกัน',
                        showConfirmButton: true,
                        confirmButtonText: 'ปิดหน้าต่าง',
                    });
                }
            }
        }
    }



    $('#userform').on('submit',function(e){
        if(checksubmit === false)
        {
            e.preventDefault()
        }else{
            swal.fire({
                title: 'กำลังบันทึกข้อมูล..',
                onOpen: () => {
                  swal.showLoading()
                }
            });
        }
    })

</script>
@endpush

@stop
