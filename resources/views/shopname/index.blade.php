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
                    <h3 class="title-page">รายชื่อร้านค้า</h3>
                </div>
                <div class="text-right col-sm-6">
                    <a href="{{url('quotation/add')}}" class="btn btn-add mb-2" id="btn_expense"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มร้านค้า</a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="input-group col-sm-3">
                    <input class="form-control" type="text" name="search" value="" placeholder="ค้นหา...." onkeyup="search_page()">
                </div>

            </div>
            {{-- <h4 class="card-title">Orders</h4> --}}
            <div class="table-responsive">
              <table class="table">
                <thead class="head-table">
                  <tr>
                    <th> ลำดับ </th>
                    <th> วันที่าสร้างใบเสนอราคา </th>
                    <th> ใบเสนอราคา </th>
                    <th> ชื่อร้าน </th>
                    <th> ผู้ติดต่อ </th>
                    <th> เบอร์โทรศัพท์ </th>
                    <th> ยอดสั่งซื้อ </th>
                    <th> สถานะ </th>
                    <th> การจัดการ </th>
                  </tr>
                </thead>
                <tbody id="datatable">

                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
</div>

@stop
