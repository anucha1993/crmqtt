@extends('layout.master')
@push('plugin-styles')

@endpush
@push('style')
    <style>
        .header-title{
            font-size: 12px;
            margin-top: 10px;
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
                    <h3 class="title-page">ประวัติการชำระ {{$orders->order_number}}</h3>
                </div>
                <div class="text-right col-sm-6">
                    {{-- <a href="#" class="btn btn-add mb-2" disabled ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มบิลย่อย</a> --}}

                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">ประเภทการชำระเงิน</label>
                    <div class="col-sm-12">
                        <p class="title_order">{!! PatmentType($orders->payment_type) !!}</p>
                    </div>
                </div>
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">ยอดเงินมัดจำ</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$orders->GetDeposit ? number_format($orders->GetDeposit->total,2):'0.00'}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">ยอดรวมสั่งซื้อ</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{number_format($orders->price_all,2)}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">ภาษีมูลค่าเพิ่ม 7%</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{number_format($orders->vat,2)}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">ยอดรวมสั่งซื้อทั้งสิ้น</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{number_format($orders->total,2)}}</p>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
              <table class="table table-width">
                <thead class="head-table">
                  <tr>
                    <th> วันที่สร้างบิลย่อย </th>
                    <th> รายการ </th>
                    <th> วันที่ชำระ </th>
                    <th> ยอดที่ต้องชำระ </th>
                    <th> สถานะการชำระเงิน </th>
                    <th> ไฟล์แนบ </th>
                    <th> จัดการ </th>
                  </tr>
                </thead>
                <tbody>
                    @forelse ($datas as $item)
                    <tr>
                        <td>{{date('d/n/Y H:i:s',strtotime($item->created_at))}}</td>
                        <td>{{TitlePagePayment($item->order_delivery_number,$item->order_number,$item->check)}}</td>
                        <td>{{date('d/n/Y',strtotime($item->date_playment))}}</td>
                        <td>{{number_format($item->total,2)}}</td>
                        <td>

                            @if ($item->status === 3)
                                <a href="">ยืนยันการชำระ</a>
                            @else
                            {!! statusPaymentStr($item->status) !!}
                            @endif
                           
                        </td>
                        <td>
                            <a href="{{url('storage/'.$item->file)}}" target="_blank" class="btn-link">{{$item->file}}</a>
                        </td>
                        <td>
                            @if($item->check == 1)
                            <a href="{{url('orders/view/'.$item->order_id)}}" target="_blank" class="btn-link">รายละอียดบิลย่อย</a>
                            @else
                            <a href="{{url('orders/delivery/view/'.$item->order_delivery_id)}}" target="_blank" class="btn-link">รายละอียดบิลหลัก</a>
                            @endif
                        </td>
                    </tr>
                    @empty

                    @endforelse

                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
</div>

@push('plugin-scripts')

@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('li.nav-item.sidebar-nav-item.orders-meun').addClass('active');
        });
    </script>
@endpush


@stop




