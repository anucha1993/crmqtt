@extends('layout.master')
@push('plugin-styles')
{!! Html::style('css/model.css') !!}
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
    .d-flex.flex-row.justify-content-center.align-items {
        padding-top: 35px;
        padding-bottom: 35px;
    }
    label.title {
        font-weight: bolder;
        padding-right: 20px;
        font-family: 'Sarabun';
    }
    label.pocketmoney {
        color: #4c9ffe;
        font-weight: bolder;
        font-size: 20px;
    }
    p.text-detail {
        text-align: right;
        color: #A9A9A9;
    }
    .box-left {
        border-right: 2px solid #C7C7C7;
        padding-right: 15px;
    }
    .box-right {
        padding-left: 15px;
        margin-top: auto;
        margin-bottom: auto;
    }
    button.btn.btn-add-pocket {
        color: #FFFF;
        background: #33467A;
    }

    .modal-body {
     background: #FFFF;
     padding-left: 30px;
    padding-right: 30px;
    }

    .modal {
        text-align: center;
        padding: 0!important;
    }

    .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px;
    }

    .modal-dialog {
        position: absolute;
        left: 42%;
        top: 15%;
        width: 350px;
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
    a.btn.btn-secondary.btn-fw {
        width: 100%;
        padding: 10px;
    }
    button.btn.btn-fw {
        width: 100%;
        margin: 0;
    }
    div.btn-model-box {
        border-top: 1px solid #ced4da;
        padding-top: 15px;
    }
    p#name_file {
        overflow: hidden;
    }

    @media (max-width: 991px)
    {
        .modal-dialog {
            position: unset;
            left: 0;
            top: 0;
        }
    }
    @media (max-width: 451px)
    {
        .modal-dialog {
            width: 300px;
            display: inline-block;
            text-align: left;
            vertical-align: middle;
        }
        button.btn.btn-primary.btn-fw {
            margin-top: 10px;
        }
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
                            <li class="nav-item item_tab_customer item_customer">
                                <a class="nav-link link_customer " href="{{url('customer/edit/'.$id)}}">ข้อมูลร้านค้า</a>
                            </li>
                            <li class="nav-item item_tab_customer item_pocket active">
                                <a class="nav-link link_customer active" href="javascript:void(0)">ข้อมูล Pocket Money</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex flex-row justify-content-center align-items">
                            <div class="box-left">
                                <h5 class="text-right">
                                    <label class="title">Pocket Money</label>
                                    <label class="pocketmoney" id="pocketmoney">{{isset($pockethistory) ? number_format($pockethistory->pocket_money,2) : '0.00'}}</label>
                                </h5>
                                <p class="text-detail">ข้อมูล ณ วันที่ <span id="time">{{date('d/m/Y H:i:s')}}</span></p>
                            </div>
                            <div class="box-right">
                                <button class="btn btn-add-pocket" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่ม Pocket</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                      <thead class="head-table">
                        <tr>
                          <th> ลำดับ </th>
                          <th> วันที่ทำรายการ </th>
                          <th> หัวข้อ </th>
                          <th> หมายเหตุ </th>
                          <th> ไฟล์ </th>
                          <th> Pocket ปัจจุบัน </th>
                          <th> เพิ่ม Pocket </th>
                          <th> ใช้ Pocket </th>
                          <th> Pocket Money คงเหลือ </th>
                        </tr>
                      </thead>
                      <tbody id="datatable">

                      </tbody>
                    </table>
                  </div>
            </div>
        </div>
    </div>
<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <form id="customerform" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="customerid" value="{{$id}}">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="col-sm-12"><label class="title">เพิ่ม Pocket Money</label></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="col-sm-8">กรอกจำนวนเงิน :<span class="red">*</span></label>
                            <div class="input-group col-sm-12">
                                <input class="form-control text-right" type="text" data-type="currency" required name="recieve_pocket" id="recieve_pocket" value="" placeholder="กรอกจำนวนเงิน....">
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-15">
                        <div class="col-sm-12">
                            <label class="col-sm-8">หมายเหตุ :</label>
                            <div class="input-group col-sm-12">
                                <textarea  class="form-control" name="note_text" id="note_text" rows="6" placeholder="กรอกหมายเหตุ...."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-15">
                        <div class="col-sm-12">
                            <label class="col-sm-8">แนบไฟล์ :</label>
                            <div class="input-group col-sm-12">
                                <label for="file-upload" class="btn btn-upload changfile">
                                    <i class="fa fa-upload"></i> <span class="label_upload">เลือกไฟล์แนบ</span>
                                </label>
                                <input id="file-upload" type="file" name="file"  value="" style="display: none;" accept=" .jpg, .png, .pdf">
                            </div>
                            <p class="col-sm-12 warning-text text-disable" id="name_file">ยังไม่ได้แนบไฟล์ (.pdf,.jpg,.png)</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-group btn-model-box">
                                <div class="col-sm-6">
                                    <a href="javascript:void(0)" class="btn btn-secondary btn-fw " id="colsemodel" data-dismiss="modal">ยกเลิก</a>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" id="btnsubmit" class="btn btn-primary btn-fw">ยืนยันทำรายการ</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--End The Modal -->
</div>

@push('plugin-scripts')
    {!! Html::script('/js/inputmoney.js') !!}
    {!! Html::script('/js/datapocketcustomer.js') !!}
    {!! Html::script('/js/bootstrap.js') !!}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@endpush

@push('custom-scripts')
<script>
    $('li.nav-item.sidebar-nav-item.customer-meun').addClass('active');

    let data = {
        'page': 0,
        'customerid': $('[name="customerid"]').val(),
    };
    // console.log(data);
    datapocketcustomer(data);
    async function data_pocket(page = 0 , customerid = 0)
    {

        let data = {
                    'page': page,
                    'customerid': customerid,
                };
        await datapocketcustomer(data);
    }

    $('#file-upload').on('change',function(e){
        fullPath =  $(this).val()
        if(this.files[0].size > 5242880)
        {
            Swal.fire({
                icon: 'error',
                title: 'ขนาดไฟล์ต้องไม่เกิน 5 MB',
                confirmButtonText: 'ตกลง',
            })
            $(this).val('')
        }else{

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
                $('#name_file').html('ยังไม่ได้แนบไฟล์ (.pdf,.jpg,.png)')

            }
        }
    })



    $('#btnsubmit').on('click',function(e)
    {
        e.preventDefault()
        Swal.fire({
            title: 'ยืนยันการทำรายการ',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0c7cd5',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed)
            {
                var forms =  $('#customerform');
                var myForm = document.getElementById('customerform');
                var formData = new FormData(myForm);
                var csrf = $('meta[name="_token"]').attr('content');
                formData.append('_token', csrf);
                checksubmit = true
                if($('input[name="recieve_pocket"]').val() == '')
                {
                    checksubmit = false
                }
                if(checksubmit == false)
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'กรุณากรอกจำนวนเงินก่อน',
                        showConfirmButton: true,
                        confirmButtonText: 'ปิดหน้าต่าง',
                    });
                }else{
                    $.ajax({
                        url: '{{route('customer.pocketmoneysave')}}',
                        type: 'post',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            swal.fire({
                                title: 'กำลังบันทึกข้อมูล..',
                                onOpen: () => {
                                swal.showLoading()
                                }
                            });
                            if(res.status == 'success')
                            {
                                swal.close()
                                data_pocket(0,$('[name="customerid"]').val())
                                $('#myModal').modal('toggle');
                                $('.modal-backdrop').remove();
                                $('#pocketmoney').html(res.pocket_money);
                                $('#time').html(res.time);
                                $('#recieve_pocket').val('');
                                $('#note_text').val('');
                            }else{
                                swal.close()
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดความผิดพลาด',
                                    text: res.message,
                                    showConfirmButton: true,
                                    confirmButtonText: 'ปิดหน้าต่าง',
                                });
                            }

                        }
                    });
                }
            }
        })
    })


    $('.btn-add-pocket').on('click',function(){
        $('#recieve_pocket').val('')
        $('#note_text').val('')
        $('#file-upload').val('')
        $('#name_file').html('ยังไม่ได้แนบไฟล์ (.pdf,.jpg,.png)')
    });




</script>
@endpush


@stop
