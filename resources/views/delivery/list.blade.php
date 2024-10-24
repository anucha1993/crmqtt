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
                    <h3 class="title-page">รายการบิลย่อยทั้งหมด {{$order->order_number}} </h3>
                </div>
                <div class="text-right col-sm-6">
                    <a href="{{url('payments/order/'.$order->id)}}">ประวัติการชำระเงิน</a>
                    <a href="{{url('orders/delivery/add/'.$order->id)}}" class="btn btn-add mb-2" disabled ><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มบิลย่อย</a>

                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">วันที่สร้างใบเสนอราคา</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{date('d/m/Y H:i:s',strtotime($quotation->created_at))}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">ชื่อร้านค้า</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$customer->store_name}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">เลขประจำตัวผู้เสียภาษี</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$customer->text_number_vat}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">Pocket Money</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$quotation->customer_type == 'customer' ? (!empty($customer->PocketMoney) ? number_format($customer->PocketMoney->pocket_money,2) : '0.00') : '0.00'}}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">ผู้ติดต่อ</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$customer->customer_name}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">เบอร์โทรศัพท์</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$customer->customer_phone}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-3 input-header">
                    <label class="col-sm-12 header-title">อีเมล์</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$customer->customer_mail}}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="input-group col-sm-12 input-header">
                    <label class="col-sm-12 header-title">ที่อยู่</label>
                    <div class="col-sm-12">
                        @if($quotation->customer_type =='customer')
                            <p class="title_order">{{$customer->customer_address .' ตำบล '.$customer->district_name.' อำเภอ '.$customer->amphoe_name.' จังหวัด '.$customer->province_name.' '.$customer->zip_code}}</p>
                        @else
                            <p class="title_order">{{$customer->customer_address}}</p>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">ประเภทการชำระเงิน</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{PatmentType($order->payment_type)}}</p>
                    </div>
                </div>
                @if($order->payment_type == 1)
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">ยอดเงินมัดจำ</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{number_format($order->GetDepositSum())}}</p>
                    </div>
                </div>
                @endif
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">ยอดรวมสั่งซื้อ</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{number_format($order->price_all)}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">ภาษีมูลค่าเพิ่ม</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{$order->on_vat == 1 ? number_format($order->vat) : '0.00'}}</p>
                    </div>
                </div>
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">ยอดรวมสั่งซื้อทั้งสิ้น</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{number_format($order->total)}}</p>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
              <table class="table table-width">
                <thead class="head-table">
                  <tr>
                    <th> วันที่สร้างบิลย่อย </th>
                    <th> รายการ </th>
                    @if($order->payment_type == 3)
                    <th> วันที่ชำระ </th>
                    @endif
                    @if($order->payment_type == 4)
                    <th> Pocket ปัจจุบัน </th>
                    @endif
                    @if($order->payment_type != 2 && $order->payment_type != 3)
                    <th> ราคารวมในบิลย่อย </th>
                    @endif
                    @if($order->payment_type == 1)
                    <th> หักจากเงินมัดจำ </th>
                    @endif
                    <th> ยอดที่ต้องชำระ </th>
                    @if($order->payment_type == 4)
                    <th> Pocket คงเหลือ </th>
                    @endif
                    <th> สถานะการชำระเงิน </th>
                    {{-- <th> ไฟล์แนบ </th> --}}
                    <th> วันที่จัดส่ง </th>
                    <th> สถานะจัดส่ง </th>
                    {{-- <th> Print ใบส่งของ </th> --}}
                    @if($order->payment_type == 3)
                    <th> Print ใบวางบิล </th>
                    @endif
                    <th> จัดการ </th>
                  </tr>
                </thead>
                <tbody>

                    @forelse ($datas as $item)
                        <tr>
                            <td>{{date('d/n/Y H:i:s',strtotime($item->created_at))}}</td>
                            <td>บิลย่อย {{$item->order_delivery_number}}</td>
                            @if($order->payment_type == 3)
                            <td>{{date('d-m-Y',strtotime($item->date_playment))}}</td>
                            @endif
                            @if($order->payment_type == 4)
                            <td>{{number_format($item->pocket_present,2)}}</td>
                            @endif
                            @if($order->payment_type != 2  && $order->payment_type != 3)
                            <td>{{number_format($item->total,2)}}</td>
                            @endif
                            @if($order->payment_type == 1)
                            <td>{{number_format($item->money_deposit,2)}}</td>
                            @endif
                            <td>{{number_format($item->total - $item->money_deposit,2)}}</td>
                            @if($order->payment_type == 4)
                            <td>{{number_format($item->pocket_money,2)}}</td>
                            @endif
                            <td>{!! statusPaymentStr($item->status_payment) !!}</td>

                            
                            {{-- <td  style="text-align: center;">{!! ($item->file ? '<a target="_blank" href="'.url('storage/delivery/'.$item->file).'" class="btn-link"> <i class="fa fa-paperclip" aria-hidden="true"></i></a>' : '') !!}</td> --}}
                            <td>{{date('d/n/Y',strtotime($item->date_send))}}</td>
                            <td>{!! statusStr($item->status_delivery,'delivery') !!}</td>
                            {{-- <td> --}}
                              {{-- <button type="button" class="btn btn-link" onclick="btn_printJS()"><i class="fa fa-print" aria-hidden="true"></i> ใบส่งของ</button> --}}
                                <!-- <a href="{{url('orders/delivery/view/'.$item->order_delivery_id.'/1')}}" class="btn-link"><i class="fa fa-print" aria-hidden="true" onclick="btn_printJS()"></i> ใบส่งของ</a> -->
                            {{-- </td> --}}
                            @if($order->payment_type == 3)
                            <td>
                                <a href="#" class="btn-link"><i class="fa fa-print" aria-hidden="true"></i> ใบวางบิล</a>
                            </td>
                            @endif
                            <td>
                                <a href="{{url('orders/delivery/view/'.$item->order_delivery_id)}}" class="btn-link">รายละเอียดบิลย่อย</a>

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
{{-- @include('delivery.printpdf') --}}
@push('plugin-scripts')

@endpush

@push('custom-scripts')
<script src="{{ asset('js/print.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('li.nav-item.sidebar-nav-item.orders-meun').addClass('active');
    });
    function btn_printJS(){
      printJS({
        printable: "printJS-form",
        type: "html",
        header: null,
        style: `
          @media print {
            table { border-collapse: collapse;}
            th, td { word-break: all; text-align:center;height: 24px;}
            .header-footer , .header-footer > tbody > tr > td {text-align:left; border:unset}
            body{
              font-size: 16pt;
              font-family: 'THSarabunNew' !important;

            }
            .font-content > td {font-size: 7pt !important;}
          }
          @page {size: 22cm 17.7cm landscape;margin-top:0.2cm}
          `
      });
    }
</script>
@endpush


@stop
