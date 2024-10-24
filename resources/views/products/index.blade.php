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
                    <h3 class="title-page">รายการสินค้า</h3>
                </div>
				<div class="text-right col-sm-6">
                    <a href="{{url('products/add')}}" class="btn btn-add mb-2"><i class="fa fa-plus" aria-hidden="true"></i> สร้างรายการสินค้า</a>
                </div>
            </div>
            <hr>
            <!-- div class="row">
                <div class="input-group col-sm-3">
                    <input class="form-control" type="text" name="search" value="" placeholder="ค้นหา...." onkeyup="search_page()">
                </div>
            </div -->
            <div class="table-responsive">
              <table class="table">
                <thead class="head-table">
                  <tr>
                    <th> ลำดับ </th>
                    <th> เลขที่สินค้า </th>
                    <th> ชื่อสินค้า</th>
                    <th>นน. (kgs) ต่อเมตร </th>
                    <th> Note </th>
                    <th style="text-align: center;"> การจัดการ </th>
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
  {!! Html::script('/js/searachproducts.js') !!}
@endpush

@push('custom-scripts')
    <script>
        let data = {'page': 0};
            searachproducts(data);
            async function search_page(page = 0)
            {
                let data = {
                    'page': page,
                    'search': $('[name="search"]').val(),
                    'ds': $('[name="ds"]').val(),
                    's': $('[name="s"]').val(),
                };

                await searachproducts(data);
            }

    </script>

@endpush
@stop
