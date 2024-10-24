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
                    <a href="{{url('/customer/add')}}" class="btn btn-add mb-2" id="btn_expense"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มร้านค้า</a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="input-group col-sm-4">
                    <input class="form-control" type="text" name="search" value="" placeholder="ค้นหา...." onkeyup="search_page()">
                </div>
            </div>
            {{-- <h4 class="card-title">Orders</h4> --}}
            <div class="table-responsive">
              <table class="table">
                <thead class="head-table">
                  <tr>
                    <th> ลำดับ </th>
                    <th> ชื่อร้านค้า </th>
                    <th> เลขประจำตัวผู้เสียภาษี </th>
                    <th> ผู้ติดต่อ </th>
                    <th> เบอร์โทรศัพท์ </th>
                    <th> อีเมล </th>
                    <th> Pocket Money </th>
                    <th style="text-align: center"> การจัดการ </th>
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
  {!! Html::script('/js/searachcustomer.js') !!}
@endpush

@push('custom-scripts')
    <script>

        let data = {'page': 0};
            searachcustomer(data);
            async function search_page(page = 0)
            {
                let data = {
                    'page': page,
                    'search': $('[name="search"]').val(),
                };

                await searachcustomer(data);
            }

    </script>
@endpush


@stop




