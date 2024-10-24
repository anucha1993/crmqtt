@extends('layout.master')
@push('plugin-styles')
   {!! Html::style('/plugins/daterangepicker/daterangepicker.css') !!}
@endpush
@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="form-inline">
                <div class="col-sm-6">
                    <h3 class="title-page">รายการบิลหลัก</h3>
                </div>
                <div class="text-right col-sm-6">
                    {{-- <a href="{{url('quotation/add')}}" class="btn btn-add mb-2" id="btn_expense"><i class="fa fa-plus" aria-hidden="true"></i> สร้างใบเสนอราคา</a> --}}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="input-group col-sm-3">
                    <input class="form-control" type="text" name="search" value="" placeholder="ค้นหา...." onkeyup="search_page()">
                </div>
                <div class="input-group col-sm-3">
                    <select name="s" class="form-control" onchange="search_page()">
                        <option value="">สถานะบิลหลักทั้งหมด</option>
                        <option value="0">รอยืนยัน</option>
                        <option value="1">ยืนยัน</option>
                        <option value="2">ยกเลิก</option>
                    </select>
                </div>
                <div class="input-group col-sm-3">
                    <select name="ds" class="form-control" onchange="search_page()">
                        <option value="">สถานะจัดส่งทั้งหมด</option>
                        <option value="0">กำลังดำเนินการ</option>
                        <option value="1">จัดส่งสำเร็จ</option>
                        <option value="2">ยกเลิก</option>
                    </select>
                </div>
            </div>
            {{-- <h4 class="card-title">Orders</h4> --}}
            <div class="table-responsive">
              <table class="table">
                <thead class="head-table">
                  <tr>
                    <th> ลำดับ </th>
                    <th> เลขที่บิล </th>
                    <th> ชื่อร้านค้า </th>
                    <th> ผู้ติดต่อ </th>
                    <th> เบอร์โทรศัพท์ </th>
                    <th> ที่อยู่ </th>
                    <th> ยอดสั่งซื้อ </th>
                    <th> ไฟล์แนบ </th>
                    <th> สถานะบิลหลัก </th>
                    <th> สถานะจัดส่ง </th>
                    <th> สถานะการชำระ </th>
                    {{-- <th> Print ใบกำกับภาษี </th> --}}
                    <th> การจัดการ </th>
                  </tr>
                </thead>
                <tbody id="datatable">

                </tbody>
              </table>
            </div>
            <div class="row footer-table">
                <div class="col-sm-12 col-md-5">
                    <div class="con-data" id="box_tltal"></div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="pagination"></div>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
@push('plugin-scripts')
  {!! Html::script('/js/searachorders.js') !!}
@endpush

@push('custom-scripts')
    <script>

        let data = {'page': 0};
            searachorders(data);
            async function search_page(page = 0)
            {
                let data = {
                    'page': page,
                    'search': $('[name="search"]').val(),
                    'ds': $('[name="ds"]').val(),
                    's': $('[name="s"]').val(),
                };

                await searachorders(data);
            }

    </script>

@endpush


@stop
