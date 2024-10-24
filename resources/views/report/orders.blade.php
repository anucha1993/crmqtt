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
                    <h3 class="title-page">รายงานการสั่งซื้อของบิลหลัก</h3>
                </div>
                <div class="text-right col-sm-6">
                    <form action="{{url('report/exportorder')}}" id="exportform" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="searchexport" value="">
                        <input type="hidden" name="dateexport" value="">
                        <input type="hidden" name="ptexport" value="">
                        <input type="hidden" name="psexport" value="">
                        <input type="hidden" name="dsexport" value="">
                        <button type="submit" class="btn btn-export-excle mb-2"><i class="mdi mdi-file-export"></i> Export File</button>
                    </form>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="input-group col-sm-3">
                    <input class="form-control" type="text" name="search" value="" placeholder="ค้นหา...." onkeyup="search_page()">
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
                    <select name="pt" class="form-control" onchange="search_page()">
                        <option value="">ประเภทการจ่ายทั้งหมด</option>
                        <option value="1">มัดจำ</option>
                        <option value="2">หน้างาน</option>
                        <option value="3">เครดิต</option>
                        <option value="4">Pocket Money</option>
                    </select>
                </div>
                <div class="input-group col-sm-2">
                    <select name="ds" class="form-control" onchange="search_page()">
                        <option value="">สถานะจัดส่ง</option>
                        <option value="0">กำลังดำเนินการ</option>
                        <option value="1">จัดส่งสำเร็จ</option>
                        <option value="2">ยกเลิก</option>
                    </select>
                </div>
                <div class="input-group col-sm-2">
                    <select name="ps" class="form-control" onchange="search_page()">
                        <option value="">สถานะการชำระ</option>
                        <option value="0">รอชำระ</option>
                        <option value="1">ชำระแล้ว</option>
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
                    <th> เลขที่บิล </th>
                    <th> ชื่อร้าน </th>
                    <th> เลขประจำตัวผู้เสียภาษี </th>
                    <th> ผู้ติดต่อ </th>
                    <th> เบอร์โทรศัพท์ </th>
                    <th> อีเมล </th>
                    <th> ยอดสั่งซื้อ </th>
                    <th> ประเภทการจ่าย </th>
                    <th> สถานะจัดส่ง </th>
                    <th> สถานะชำระเงิน </th>
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
{!! Html::script('/js/searachreport.js') !!}
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
        searachreport(data,'orderslist');
        async function search_page(page = 0)
        {
            let data = {
                'page': page,
                'search': $('[name="search"]').val(),
                'ds': $('[name="ds"]').val(),
                'pt': $('[name="pt"]').val(),
                'ps': $('[name="ps"]').val(),
                'date': $('[name="date"]').val(),
            };
            await searachreport(data,'orderslist');
        }

    </script>
@endpush


@stop




