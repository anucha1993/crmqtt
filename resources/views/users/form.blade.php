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
    {!!breadcrumb($breadcrumb)!!}
    <div class="col-lg-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="form-inline">
                    <div class="col-sm-6">
                        <h3 class="title-page">สร้างผู้ดูแลระบบ</h3>
                    </div>
                    <div class="text-right col-sm-6">
                        {{-- <a href="{{url('/customer/add')}}" class="btn btn-add mb-2" id="btn_expense"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มร้านค้า</a> --}}
                    </div>
                </div>

                <form action="{{ isset($edit) ? route('users.update') : route('users.save')}}" method="POST" id="userform">
                    {{ csrf_field() }}
                    <div class="box-conten">
                        <div class="row">
                            <div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8">
                                    <label class="col-sm-12 title-input-customer">อีเมล <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="text" required id="emailinput" name="email" value="{{isset($edit) ? $edit->email : ''}}" placeholder="กรอกอีเมล์เข้าใช้งาน..." onchange="check.email(this.value)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">สิทธิการใช้งาน <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <select required class="form-control" name="role_id">
                                            <option value="">เลือกสิทธิการใช้งาน</option>
                                            @foreach($roles as $role)
                                                <option {{isset($edit) && $edit->role_id == $role->id ? 'selected' : ''}} value="{{$role->id}}">{{$role->role_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">สถานะการใช้งาน <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <select class="form-control" required name="status">
                                            <option value="">เลือกสถานะการใช้งาน</option>
                                            <option {{isset($edit) && $edit->status == 0 ? 'selected' : ''}} value="0">ปิดการใช้งาน</option>
                                            <option {{isset($edit) && $edit->status == 1 ? 'selected' : 'selected'}}  value="1">เปิดการใช้งาน</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if(!isset($edit))
                            <div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">รหัสผ่าน <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="password" id="password" required onchange="check.regepassword(this.value)" name="password" value="{{isset($edit) ? $edit->password : ''}}"  placeholder="กรอกรหัสผ่าน..." >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">ยืนยันรหัสผ่าน <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="password" required id="conpassword" name="confirmpassword" value="{{isset($edit) ? $edit->password : ''}}" onchange="check.checkpassword()"  placeholder="กรอกยืนยันรหัสผ่าน...">
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-sm-12 form-inline">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">ชื่อผู้ใช้งาน <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="text" required name="fname" value="{{isset($edit) ? explode(' ', $edit->name)[0] : ''}}" id="fname" onchange="check.name()"  placeholder="กรอกชื่อ..." >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 title-input-customer">นามสกุล <span class="red">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input class="form-control" type="text" required name="lname" value="{{isset($edit) ? explode(' ', $edit->name)[1] : ''}}" id="lname" onchange="check.name()" placeholder="กรอกนามสกุล...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="d-flex flex-row justify-content-center align-items">
                                <input type="hidden" name="userid" id="userid" value="{{isset($edit) ? $edit->id : ''}}">
                                <a href="{{url('users')}}"  class="btn btn-secondary btn-fw">ยกเลิก</a>
                                <button type="submit" id="submit" {{isset($edit) ? '' : 'disabled'}}  class="btn btn-primary btn-fw">ยืนยันทำรายการ</button>
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
    $('li.nav-item.sidebar-nav-item.users-meun').addClass('active');
    var checksubmit = true
    var userid = $('#userid').val()
    var check =
    {
        email:(email) =>
        {
            checksubmit = false
            $.ajax({
                url: "{{route('users.cehckemail')}}",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'post',
                data: {email : email,userid:userid},
                success: function(res) {
                    if(res.status === false)
                    {
                        Swal.fire({
                            icon: 'error',
                            title: res.msg,
                            showConfirmButton: true,
                            confirmButtonText: 'ปิดหน้าต่าง',
                        });
                        $('#emailinput').addClass('errorinput')
                        $('#submit').prop('disabled', true);
                    }else{
                        $('#emailinput').removeClass('errorinput')
                        $('#submit').prop('disabled', false);
                    }
                    checksubmit = res.status
                }
            })
        },
        name:() => {
            checksubmit = false
            fname = $('#fname').val()
            lname = $('#lname').val()
            $.ajax({
                url: "{{route('users.cehckname')}}",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'post',
                data: {fname : fname,lname:lname,userid:userid},
                success: function(res) {
                    if(res.status === false)
                    {
                        Swal.fire({
                            icon: 'error',
                            title: res.msg,
                            showConfirmButton: true,
                            confirmButtonText: 'ปิดหน้าต่าง',
                        });
                        $('#fname').addClass('errorinput')
                        $('#lname').addClass('errorinput')
                        $('#submit').prop('disabled', true);
                    }else{
                        $('#fname').removeClass('errorinput')
                        $('#lname').removeClass('errorinput')
                        $('#submit').prop('disabled', false);
                    }
                    checksubmit = res.status
                }
            })
        },
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
