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


<div class="card">
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
                    <label class="col-sm-12 header-title">ยอดชำระแล้ว</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{number_format($orders->GetDepositSum(),2)}}</p>
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
                <div class="input-group col-sm-2 input-header">
                    <label class="col-sm-12 header-title">Pocket Money</label>
                    <div class="col-sm-12">
                        <p class="title_order">{{ optional($CustomerPocket)->pocket_money ? number_format(optional($CustomerPocket)->pocket_money, 2) : '0.00' }}</p>

                    </div>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-width">
                  <thead class="head-table">
                    <tr>
                      <th> ลำดับ </th>
                      <th> ประเภทการชำระ </th>
                      <th> เลขที่บิลย่อย </th>
                      <th> วันที่ชำระ </th>
                      <th> ยอดชำระ </th>
                      <th> สถานะการชำระเงิน </th>
                      <th> ไฟล์แนบ </th>
                      {{-- <th> จัดการ </th> --}}
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($paymentHistory as $key => $item)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>
                               @forelse ($paymentType as $itemPayment)
                                   @if ($itemPayment->payment_type_id === $item->payment_type)
                                       {{$itemPayment->payment_type_name}}
                                   @endif
                               @empty
                                   
                               @endforelse
                            </td>
                            <td>{{$item->order_delivery_number}}</td>
                            <td>{{date('d-m-Y',strtotime($item->date_playment))}} เวลา {{date('H:m:s',strtotime($item->date_playment))}} น.</td>
                            <td>
                                {{number_format($item->total, 2)}}
                            </td>
                            <td>
                                {!! statusPaymentStr($item->status) !!}
                            </td>
                            <td>
                                @if ($item->file)
                                <a href="{{url('storage/'.$item->file)}}" onclick="openPdfPopup(this.href); return false;" class="btn-link">{{$item->file}}</a>
                                @else
                                    -
                                @endif
                                
                            </td>
                            <td>
                                @if (Auth::user()->role_id === 1)
                                @if ($item->status === 3)
                                {{-- <a href="{{route('payment.approvePayment',$item->id)}}"  onclick="return confirm('คุณต้องการอนุมัติยอดชำระ : ' + {{$item->total}} +' ใช่ไหม')" class="btn btn-sm btn-success "> ยืนยันยอด</a> --}}
                                {{-- <a href="{{route('payment.cancelPayment',$item->id)}}" class="btn btn-sm btn-danger " onclick="return confirm('คุณต้องการยกเลิกใช่ หรือไม่ ?')" > ยกเลิกยอด</a> --}}
                                @elseif ($item->status === 4)
                                <span class="dot close"></span> {{$item->note}}
                                @elseif ($item->status === 1)
                                <span class="dot approve"></span> ทำรายการสำเร็จ
                                @endif
                                @else
                                    -
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
</div>



@push('plugin-scripts')

@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('li.nav-item.sidebar-nav-item.orders-meun').addClass('active');
        });
    </script>

<script>
    function openPdfPopup(url) {
        var width = 800; // กำหนดความกว้างของหน้าต่าง
        var height = 600; // กำหนดความสูงของหน้าต่าง
        var left = (window.innerWidth - width) / 2; // คำนวณตำแหน่งจากด้านซ้ายของหน้าจอ
        var top = (window.innerHeight - height) / 2; // คำนวณตำแหน่งจากด้านบนของหน้าจอ

        // เปิดหน้าต่างใหม่ด้วยการคำนวณตำแหน่งและขนาด
        window.open(url, 'PDFPopup', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
    }
</script>

@endpush


@stop




