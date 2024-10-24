@extends('layout.master')
@push('plugin-styles')

@endpush
@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="form-inline">
                <div class="col-sm-6">
                    <h3 class="title-page">ผู้ดูแลระบบ</h3>
                </div>
                <div class="text-right col-sm-6">
                    <a href="{{url('users/add')}}" class="btn btn-add mb-2"><i class="fa fa-plus" aria-hidden="true"></i> สร้างผู้ดูแลระบบ</a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="input-group col-sm-3">
                    <input class="form-control" type="text" name="search" value="" placeholder="ค้นหา...." onkeyup="search_page()">
                </div>
                <div class="input-group col-sm-2">
                    <select name="r" class="form-control" onchange="search_page()">
                        <option value="">สิทธิทั้งหมด</option>
                        @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->role_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group col-sm-2">
                    <select name="s" class="form-control" onchange="search_page()">
                        <option value="">สถานะทั้งหมด</option>
                        <option value="0">ปิดใช้งาน</option>
                        <option value="1">เปิดใช้งาน</option>
                    </select>
                </div>
            </div>
            {{-- <h4 class="card-title">Orders</h4> --}}
            <div class="table-responsive">
              <table class="table">
                <thead class="head-table">
                  <tr>
                    <th> ลำดับ </th>
                    <th> ชื่อ-สกุล </th>
                    <th> E-mail </th>
                    <th> สิทธิการใช้งาน </th>
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

@push('plugin-scripts')
{!! Html::script('/js/searachuser.js') !!}
@endpush

@push('custom-scripts')
    <script>
        let data = {'page': 0};
        searachuser(data);
        async function search_page(page = 0)
        {
            let data = {
                'page': page,
                'search': $('[name="search"]').val(),
                'role': $('[name="r"]').val(),
                'status': $('[name="s"]').val(),
            };
            console.log(data);
            await searachuser(data);
        }




    </script>
@endpush


@stop




