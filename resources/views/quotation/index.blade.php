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
                    <h3 class="title-page">ใบเสนอราคา</h3>
                </div>
                <div class="text-right col-sm-6">
                    <a href="{{url('quotation/add')}}" class="btn btn-add mb-2"><i class="fa fa-plus" aria-hidden="true"></i> สร้างใบเสนอราคา</a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="input-group col-sm-3">
                    <input class="form-control" type="text" name="search" value="" placeholder="ค้นหาใบเสนอราคา , ชื่อร้าน, ผู้ติดต่อ, เบอร์โทร,..." onkeyup="search_page()">
                </div>
                <div class="input-group col-sm-3">
                    <input class="form-control datepicker" type="text" name="date" value="" placeholder="วันที่เริ่มต้น-วันที่สิ้นสุด" onchange="search_page()">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
                <div class="input-group col-sm-2">
                    <select name="s" class="form-control" onchange="search_page()">
                        <option value="">สถานะทั้งหมด</option>
                        <option value="0">รอยืนยัน</option>
                        <option value="1">ยืนยัน</option>
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
                    <th> วันที่สร้างใบเสนอราคา </th>
                    <th> ใบเสนอราคา </th>
                    <th> ชื่อร้าน </th>
                    <th> ผู้ติดต่อ </th>
                    <th> เบอร์โทรศัพท์ </th>
                    <th> ที่อยู่ </th>
                    <th> ยอดสั่งซื้อ </th>
                    <th> สถานะ </th>
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
<script>
   
</script>


@push('plugin-scripts')
  {!! Html::script('/js/searachquotation.js') !!}
@endpush

@push('custom-scripts')
    <script>
        var date = '';
        $(document).ready(function() {
            $('.datepicker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
        });

        $('.datepicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            search_page()
        })
        $('.datepicker').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $('.datepicker').data('daterangepicker').setStartDate(moment());
			$('.datepicker').data('daterangepicker').setEndDate(moment());
            search_page()
        })


        let data = {'page': 0};
            searachquotation(data);
            async function search_page(page = 0)
            {
                let data = {
                    'page': page,
                    'search': $('[name="search"]').val(),
                    'd': $('[name="date"]').val(),
                    's': $('[name="s"]').val(),
                };

                await searachquotation(data);
            }

    </script>
@endpush


@stop
