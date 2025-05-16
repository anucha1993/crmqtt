<style media="print">
    @page {
        size: auto;
        margin: 0;
    }
</style>

@extends('layout.master')
@push('plugin-styles')
    {!! Html::style('/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') !!}
    {!! Html::style('/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') !!}
    {{-- {!! Html::style('/plugins/daterangepicker/daterangepicker.css') !!} --}}
@endpush
@push('style')
    <style>
        .p-send {
            color: #33AF8A;
        }

        .p-not-send {
            color: #FF753A;
        }

        .p-number_order {
            color: #4C9FFE;
        }

        p.warning-text {
            font-size: 14px;
            margin-bottom: 5px;
        }

        input.form-control.form-control-sm.col-sm-1 {
            display: inline-flex;
            width: 13px;
            height: 13px;
        }

        label.label_vat {
            display: inline-block;
            margin-bottom: 0.5em;
        }

        div.left-border {
            border-left: 1px solid #C7C7C7;
        }

        div.bottom-border {
            border-bottom: 1px solid #C7C7C7;
        }

        p.m-b-15 {
            margin-bottom: 15px;
        }

        p.m-t-15 {
            margin-top: 15px;
        }

        .crad {
            padding: 20px;
            background: #F6F8FB;
            margin-top: 25px;
        }

        select.form-control.form-control-sm {
            font-size: 12px;
            padding: 2px;
        }

        .d-flex.flex-row.justify-content-center.align-items {
            margin-top: 25px;
        }

        p.text-date-send {
            padding-right: 0;
            border-left: 1px solid #C7C7C7;
        }

        @media print {
            @page {
                size: A4;
            }

            #printBillings {
                width: 100%;
            }

            body {
                overflow: hidden;
                padding: 1rem;
                /* padding-bottom: 1rem;
                                            padding-top: 1rem;
                                            padding-bottom: 1rem; */
            }

            #tableBilling>thead>tr,
            #tableBilling>thead>tr>td {
                border: 1px solid;
                text-align: center;
            }

            #tableBilling>tbody>tr,
            #tableBilling>tbody>tr>td {
                border-right: 1px solid;
                border-left: 1px solid;
            }

            /* #title_header_delivery {
                                            background-color: ;
                                            border: 1px solid ;
                                            padding: 7px;
                                        } */
        }
    </style>
@endpush
@section('content')
    <div class="row">
        {!! breadcrumb($breadcrumb) !!}
        <div class="col-lg-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="form-inline">
                        <div class="col-sm-6">
                            <h4 class="title-page">เพิ่มรายบิลย่อย {{ $deliverys->order_delivery_number }} (รายการบิลหลัก
                                {{ $orders->order_number }})</h4>
                        </div>

                        <div class="text-right col-sm-6">
                            <a href="#"data-toggle="modal" data-target="#exampleModalCenter">แจ้งชำระเงิน</a> &nbsp;
                            {{-- <button href="#" class="btn btn-print " onclick="printBilling('tax')" {{(($orders->on_vat != '1') || $orders->status_payment == '0') ? 'disabled' : ''}}  ><i class="fa fa-print" aria-hidden="true"></i> Print ใบกำกับภาษี</button> --}}
                            
                            
                            {{-- <button type="button" class="btn btn-print " onclick="printBilling()"><i class="fa fa-print"
                                    aria-hidden="true"></i> Print ใบส่งของ</button> --}}
                            {{-- <a href="{{ route('MPDF.delivery', $deliverys->order_delivery_id) }}"
                                class="btn btn-sm btn-danger"><i class="fa fa-print"></i>ใบส่งของ (NEW)</a> --}}

                          <button class="btn btn-success" data-toggle="modal" data-target="#printPreviewModal"><i class="fa fa-print"></i> ฟอร์มปริ้นใบส่งของ</button> 
                          <a href="#"  data-toggle="modal" data-target="#logPirnt"> ประวัติการปริ้น</a>

                        </div>


                    </div>
                     {{-- Log Print --}}
                     <div class="modal fade" id="logPirnt" tabindex="-1" role="dialog"
                     aria-labelledby="printPreviewModalTitle" aria-hidden="true">
                     <div class="modal-dialog modal-lg" role="document" style="width: 1000px">
                         <div class="modal-content">
                             <div class="modal-header">
                                 ประวัติการปริ้น
                             </div>
                             <div class="modal-body" >
                                
                                   <table class="table table">
                                    <thead>
                                        <tr>
                                            <th>ปริ้นครั้งที่</th>
                                            <th>เลขที่บิลย่อย</th>
                                            <th>เลขที่บิลย่อย</th>
                                            <th>สถานะ</th>
                                            <th>วันที่ปริ้น</th>
                                            <th>ผู้ปริ้น</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($printLogs as $log)
                                        <tr>
                                            <td>{{$log->print_log_count}}</td>
                                            <td>{{$log->Order->order_number}}</td>
                                            <td>{{$log->Delivery->order_delivery_number}}</td>
                                            <td>{{$log->order_delivery_status}}</td>
                                            <td>{{date('d/m/Y H:m:s',strtotime($log->created_at))}}</td>
                                            <td>{{$log->User->name}}</td>
                                        </tr>
                                        @empty
                                     
                                        @endforelse
                                    </tbody>

                                   </table>
                               
                             </div>
                         </div>
                     </div>
                 </div>


                     {{-- Modal Print --}}
                    <div class="modal fade" id="printPreviewModal" tabindex="-1" role="dialog"
                        aria-labelledby="printPreviewModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    ตัวเลือกการพิมพ์เอกสาร
                                </div>
                                <div class="modal-body" >
                                    <form action="{{ route('MPDF.delivery', $deliverys->order_delivery_id) }}" id="form-print" method="post">
                                        @method('post')
                                        @csrf
                                        <input type="radio" name="method_print" value="preview"><label> แสดงตัวอย่างการพิมพ์</label> &nbsp;
                                        <input type="radio" name="method_print" value="print" checked><label> ปริ้นเอกสาร</label>
                                        <br>
                                        <hr>
                                         <span>ตัวเลือกการแสดง</span><br>

                                         <input type="checkbox" name="price1" value="price1"><label> &nbsp;แสดงราคาหน้า 1 </label> <br>
                                         <input type="checkbox" name="price2" value="price2"><label> &nbsp;แสดงราคาหน้า 2 </label> &nbsp;<br>
                                         <input type="checkbox" name="price3" value="price3"><label> &nbsp;แสดงราคาหน้า 3 </label> &nbsp;<br>
                                        <br>
                                         <b class="text-danger">หมายเหตุ : </b>
                                         <br>
                                         <b>แสดงตัวอย่างการพิมพ์ :</b> <span>ระบบจะ Stamp ข้อความตัวอย่าง</span>
                                        <br>
                                        <b>ปริ้นเอกสาร :</b> <span>ระบบจะทำการบันทึก Log และจำนวนการปริ้นเอกสารนี้</span>
                                        <br>
                                        <button type="submit" class="btn btn-sm btn-success" form="form-print"> ยืนยัน</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>



                    <hr>
                    <input type="hidden" value="{{ $orders->on_vat }}" id="check_vat">
                    {{ csrf_field() }}
                    <div class="table-responsive">
                        <table class="table table-width">
                            <thead class="head-table">
                                <tr>
                                    <th style="width: 50px;"> ลำดับ </th>
                                    <th style="width: 100px;"> ชื่อสินค้า/รายละเอียดสินค้า </th>
                                    <th style="width: 100px;"> ประเภทสินค้า </th>
                                    <th style="width: 100px;"> ความยาว </th>
                                    <th style="width: 100px;"> ราคาต่อหน่อย </th>
                                    <th style="width: 100px;"> จำนวน </th>
                                    <th style="width: 100px;"> หน่วยนับ </th>
                                    <th style="width: 100px;"> จำนวนเงิน </th>
                                    <th style="width: 100px;"> หมายเหตุ </th>
                                    <th style="width: 100px;"> ค้างส่ง </th>
                                    <th style="width: 100px;"> ส่งสินค้า </th>
                                    <th style="width: 100px;"> จำนวนเงินในการส่งสินค้าครั้งนี้ </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    <?php
                                    $test_pera = $data->product_type_id == 1 ? number_format($data->size_unit * $data->price_item * $data->item_send_qty, 2) : number_format($data->size_unit * 0.35 * $data->price_item * $data->item_send_qty, 2);
                                    #$test_pera = $data->order_delivery_items_qty_pera;
                                    if ($test_pera == 0) {
                                        continue;
                                    }
                                    ?>
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $data->product_name }}</td>
                                        <td>{{ $data->type_name }}</td>
                                        <td>
                                            {{ $data->size_unit . ' ' . $data->size_name }}
                                            <input type="hidden" value="{{ $data->size_unit }}"
                                                class="size_unit_id_{{ $data->order_itmes_id }}">
                                            <input type="hidden" value="{{ $data->size_name }}"
                                                class="size_name_{{ $data->order_itmes_id }}">
                                        </td>
                                        <td class="price_item_{{ $data->order_itmes_id }} item_price text-right"
                                            data-id="{{ $data->order_itmes_id }}">
                                            {{ number_format($data->price_item, 2) }}
                                        </td>
                                        <td class="text-right">
                                            <p class="p-number_order">{{ number_format($data->item_number_order) }}</p>
                                            <input type="hidden" value="{{ $data->total_item }}" class="total_item">
                                        </td>
                                        <td>{{ countunitstr($data->count_unit) }}</td>
                                        <td class="text-right">{{ number_format($data->total_item_all, 2) }}</td>
                                        <td>{{ $data->note }}</td>
                                        <td class="text-right">
                                            <!-- {{ $data->item_number_order - $data->item_send }} -->
                                            {{ $data->item_send_qty_padding }}
                                        </td>
                                        <td class=" text-right p-send items_send_{{ $data->order_itmes_id }}">
                                            <!-- {{ $data->item_send }} -->
                                            {{ $data->item_send_qty }}
                                        </td>
                                        <td class="text-right">
                                            {{ $data->product_type_id == 1 ? number_format($data->size_unit * $data->price_item * $data->item_send_qty, 2) : number_format($data->size_unit * 0.35 * $data->price_item * $data->item_send_qty, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8">
                            <div class="form-inline">
                                <div class="col-sm-6">
                                    <div class="form-inline ">
                                        <p class="col-sm-6 m-t-15 warning-text">ยอดสั่งซื้อรวมในบิลหลัก</p>
                                        <p class="col-sm-6 m-t-15 warning-text text-right">
                                            {{ number_format($orders->price_all, 2) }}</p>
                                        <p class="col-sm-6 warning-text">
                                            <input type="checkbox" disabled {{ $orders->on_vat == 1 ? 'checked' : '' }}
                                                value="1" class="form-control form-control-sm col-sm-1">
                                            ภาษีมูลค่าเพิ่ม 7%
                                        </p>
                                        <p class="col-sm-6 warning-text text-right">
                                            {{ $orders->on_vat == 1 || $quotation->qo_on_vat == 'Y' ? number_format($orders->vat, 2) : '0.00' }}
                                        </p>
                                        
                                        <p class="col-sm-6 warning-text">ยอดสั่งซื้อรวมทั้งสิ้น</p>
                                        <p class="col-sm-6 warning-text text-right" id="order_total_all">
                                            {{ number_format($orders->total, 2) }}</p>
                                        <p class="col-sm-6 warning-text">ประเภทการจ่ายเงิน</p>
                                        <p class="col-sm-6 warning-text text-right">
                                            {{ PatmentType($orders->payment_type) }}</p>
                                        @if ($orders->payment_type == 1)
                                            <p class="col-sm-6 warning-text">ยอดเงินที่มัดจำไว้</p>
                                            <p class="col-sm-6 warning-text text-right" id="money_deposit">
                                                {{ $orders->GetDeposit ? number_format($orders->GetDeposit->total, 2) : '0.00' }}
                                            </p>
                                        @else
                                            <p class="col-sm-6 warning-text text-right" id="money_deposit"
                                                style="display: none">
                                                {{ $orders->GetDeposit ? number_format($orders->GetDeposit->total, 2) : '0.00' }}
                                            </p>
                                        @endif
                                        {{-- <p class="col-sm-6 warning-text">ยอดเงินที่มัดจำไว้</p>
                                <p class="col-sm-6 warning-text text-right" id="money_deposit">{{number_format($orders->GetDeposit->total,2)}}</p> --}}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-inline left-border bottom-border">
                                        <p class="col-sm-6 m-t-15 warning-text">ยอดในบิลย่อยครั้งนี้</p>
                                        
                                        <p class="col-sm-6 m-t-15 warning-text text-right" id="total_long_order">0.00</p>
                                        <p class="col-sm-6 warning-text">
                                            <input type="checkbox" disabled {{ $orders->on_vat == 1 ? 'checked' : '' }}
                                                value="1" class="form-control form-control-sm col-sm-1">
                                            ภาษีมูลค่าเพิ่ม 7%
                                        </p>
                                        <p class="col-sm-6 warning-text text-right" id="vat_long">
                                            {{ $orders->on_vat == 1 || $quotation->qo_on_vat == 'Y' ? number_format($orders->vat, 2) : '0.00' }}
                                        </p>
                                        <p class="col-sm-6 m-b-15 warning-text">ประเภทการจ่ายเงิน</p>
                                        <p class="col-sm-6 m-b-15 warning-text text-right">
                                            {{ PatmentType($orders->payment_type) }}</p>
                                    </div>
                                    <div class="form-inline left-border">
                                        <p class="col-sm-6 m-t-15 warning-text">ราคารวมในบิลย่อย</p>
                                        <p class="col-sm-6 m-t-15 warning-text text-right" id="total_all">
                                            {{ number_format($orders->price_all, 2) }}</p>
                                        @if ($orders->payment_type == 1)
                                            <p class="col-sm-6 warning-text">หักจากเงินมัดจำ</p>
                                            <p class="col-sm-6 warning-text text-right" id="cutdeposit">
                                                {{ number_format($deliverys->money_deposit, 2) }}</p>
                                        @else
                                            <p class="col-sm-6 warning-text text-right" id="cutdeposit"
                                                style="display: none">{{ number_format($deliverys->money_deposit, 2) }}
                                            </p>
                                        @endif
                                        <p class="col-sm-6 warning-text">ยอดที่ต้องชำระ</p>
                                        <p class="col-sm-6 warning-text text-right" id="HavToPayment">
                                            {{ number_format($deliverys->total - $deliverys->money_deposit, 2) }}</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="{{ $deliverys->file }}" id="name_file_have">
                    <input type="hidden" value="{{ date('d/m/Y', strtotime($deliverys->date_send)) }}" id="date_send">
                    <form id="updatedelivery" action="{{ route('delivery.update') }}" method="POST"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="crad">
                                    <div class="card-body">
                                        <div class="col-sm-12">
                                            <div
                                                class="d-flex flex-row justify-content-center align-items box-note-status">
                                                <p class="col-sm-2 text-right">ที่อยู่จัดส่งปัจจุบัน :</p>
                                                <input class="col-sm-6 form-control form-control-sm" type="text"
                                                    name="current_delivery_location" id="current_delivery_location"
                                                    value="{{ $location_name }}" disabled placeholder="">
                                            </div>
                                            <div
                                                class="d-flex flex-row justify-content-center align-items box-note-status">
                                                <p class="col-sm-2 text-right">ผู้ติดต่อของที่อยู่จัดส่งปัจจุบัน :</p>
                                                <input class="col-sm-6 form-control form-control-sm" type="text"
                                                    name="current_delivery_location" id="current_delivery_location"
                                                    value="{{ $location_contact_person_name }}" disabled placeholder="">
                                            </div>
                                            <div
                                                class="d-flex flex-row justify-content-center align-items box-note-status">
                                                <p class="col-sm-2 text-right">เบอร์โทรศัพท์ของผู้ติดต่อ :</p>
                                                <input class="col-sm-6 form-control form-control-sm" type="text"
                                                    name="current_delivery_location" id="current_delivery_location"
                                                    value="{{ $location_contact_person_phone_no }}" disabled
                                                    placeholder="">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row justify-content-center align-items box-note">
                                            <input type="checkbox" name="change_delivery_location_check_box"
                                                id="change_delivery_location_check_box" value="yes"
                                                onclick="toggle_new_location();">
                                            <label> &nbsp; ต้องการป้อนที่อยู่จัดส่งใหม่ <span
                                                    class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-12">
                                            <div
                                                class="d-flex flex-row justify-content-center align-items box-note-status">
                                                <p class="col-sm-2 text-right">ที่อยู่จัดส่ง :</p>
                                                <select onchange="render_more_info()"
                                                    class="col-sm-6 form-control form-control-sm" type="text"
                                                    name="existing_delivery_location_id" disabled
                                                    id="existing_delivery_location_id">
                                                    <option>เลือกที่อยู่จัดส่ง</option>
                                                    @foreach ($delivery_location as $data)
                                                        <option value={{ $data->id }}>{{ $data->location }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div
                                                class="d-flex flex-row justify-content-center align-items box-note-status">
                                                <p class="col-sm-2 text-right">ผู้ติดต่อของที่อยู่จัดส่ง :</p>
                                                <input class="col-sm-6 form-control form-control-sm" type="text"
                                                    name="existing_delivery_location_contact_person_name"
                                                    id="existing_delivery_location_contact_person_name"
                                                    value="{{ $location_contact_person_name }}" disabled placeholder="">
                                            </div>
                                            <div
                                                class="d-flex flex-row justify-content-center align-items box-note-status">
                                                <p class="col-sm-2 text-right">เบอร์โทรศัพท์ของผู้ติดต่อ :</p>
                                                <input class="col-sm-6 form-control form-control-sm" type="text"
                                                    name="existing_delivery_location_contact_person_phone_no"
                                                    id="existing_delivery_location_contact_person_phone_no"
                                                    value="{{ $location_contact_person_phone_no }}" disabled
                                                    placeholder="">
                                            </div>
                                        </div>

                                        <script>
                                            <?php
                                            echo 'const existing_delivery_location_contact_person_name = [];';
                                            echo 'const existing_delivery_location_contact_person_phone_no = [];';
                                            foreach ($delivery_location as $data) {
                                                echo 'existing_delivery_location_contact_person_name[' . $data->id . "] = '" . $data->onsite_contact_name . "';";
                                                echo 'existing_delivery_location_contact_person_phone_no[' . $data->id . "] = '" . $data->onsite_contact_phone_no . "';";
                                            }
                                            ?>
                                        </script>

                                        <script>
                                            function render_more_info() {
                                                var e = document.getElementById("existing_delivery_location_id");
                                                var strUser = e.options[e.selectedIndex].value;

                                                //alert[strUser];
                                                //alert(existing_delivery_location_contact_person_name[strUser]);

                                                document.getElementById("existing_delivery_location_contact_person_name").value =
                                                    existing_delivery_location_contact_person_name[strUser];
                                                document.getElementById("existing_delivery_location_contact_person_phone_no").value =
                                                    existing_delivery_location_contact_person_phone_no[strUser];
                                            }

                                            function toggle_new_location() {
                                                if (document.getElementById('existing_delivery_location_id').disabled == true) {
                                                    document.getElementById('existing_delivery_location_id').disabled = false;
                                                    document.getElementById('existing_delivery_location_contact_person_name').disabled = false;
                                                    document.getElementById('existing_delivery_location_contact_person_phone_no').disabled = false;

                                                    document.getElementById('delivery_location').disabled = true;
                                                } else {
                                                    document.getElementById('existing_delivery_location_id').disabled = true;
                                                    document.getElementById('existing_delivery_location_contact_person_name').disabled = true;
                                                    document.getElementById('existing_delivery_location_contact_person_phone_no').disabled = true;

                                                    document.getElementById('delivery_location').disabled = true;
                                                }

                                                if (document.getElementById('change_delivery_location_check_box').checked) {
                                                    document.getElementById('new_delivery_location_check_box').disabled = false;
                                                } else {
                                                    document.getElementById('new_delivery_location_check_box').checked = false;
                                                    document.getElementById('new_delivery_location_check_box').disabled = true;
                                                    document.getElementById('new_delivery_location_contact_person_name').disabled = true;
                                                    document.getElementById('new_delivery_location_contact_person_phone_no').disabled = true;

                                                    document.getElementById('existing_delivery_location_id').disabled = true;
                                                    document.getElementById('existing_delivery_location_contact_person_name').disabled = true;
                                                    document.getElementById('existing_delivery_location_contact_person_phone_no').disabled = true;

                                                }
                                            }

                                            function toggle_add_new_location() {
                                                if (document.getElementById('delivery_location').disabled == true) {
                                                    document.getElementById('existing_delivery_location_id').disabled = true;
                                                    document.getElementById('existing_delivery_location_contact_person_name').disabled = true;
                                                    document.getElementById('existing_delivery_location_contact_person_phone_no').disabled = true;

                                                    document.getElementById('delivery_location').disabled = false;
                                                    document.getElementById('new_delivery_location_contact_person_name').disabled = false;
                                                    document.getElementById('new_delivery_location_contact_person_phone_no').disabled = false;
                                                } else {
                                                    document.getElementById('existing_delivery_location_id').disabled = false;
                                                    document.getElementById('existing_delivery_location_contact_person_name').disabled = false;
                                                    document.getElementById('existing_delivery_location_contact_person_phone_no').disabled = false;

                                                    document.getElementById('delivery_location').disabled = true;
                                                    document.getElementById('new_delivery_location_contact_person_name').disabled = true;
                                                    document.getElementById('new_delivery_location_contact_person_phone_no').disabled = true;
                                                }
                                            }
                                        </script>
                                        <div class="d-flex flex-row justify-content-center align-items box-note">
                                            <input type="checkbox" name="new_delivery_location_check_box"
                                                id="new_delivery_location_check_box" value="yes" disabled
                                                onclick="toggle_add_new_location();">
                                            <label> &nbsp; ต้องการเพิ่มที่อยู่จัดส่งใหม่ <span
                                                    class="red">*</span></label>
                                        </div>
                                        <div class="col-sm-12">
                                            <div
                                                class="d-flex flex-row justify-content-center align-items box-note-status">
                                                <p class="col-sm-2 text-right">ที่อยู่จัดส่งใหม่ :</p>
                                                <input class="col-sm-6 form-control form-control-sm" type="text"
                                                    name="delivery_location" id="delivery_location" disabled
                                                    placeholder="">
                                            </div>
                                            <div
                                                class="d-flex flex-row justify-content-center align-items box-note-status">
                                                <p class="col-sm-2 text-right">ผู้ติดต่อของที่อยู่จัดส่ง :</p>
                                                <input class="col-sm-6 form-control form-control-sm" type="text"
                                                    name="new_delivery_location_contact_person_name"
                                                    id="new_delivery_location_contact_person_name"
                                                    value="{{ $location_contact_person_name }}" disabled placeholder="">
                                            </div>
                                            <div
                                                class="d-flex flex-row justify-content-center align-items box-note-status">
                                                <p class="col-sm-2 text-right">เบอร์โทรศัพท์ของผู้ติดต่อ :</p>
                                                <input class="col-sm-6 form-control form-control-sm" type="text"
                                                    name="new_delivery_location_contact_person_phone_no"
                                                    id="new_delivery_location_contact_person_phone_no"
                                                    value="{{ $location_contact_person_phone_no }}" disabled
                                                    placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-sm-6 header-title">หมายเหตุ ที่ 1 <input
                                        class="form-control form-control-sm" type="text" id=remark_1 name=remark_1
                                        value="{{ $data_for_remarks->remark_1 }}"></label>
                                <label class="col-sm-6 header-title">หมายเหตุ ที่ 2 <input
                                        class="form-control form-control-sm" type="text" id=remark_2 name=remark_2
                                        value="{{ $data_for_remarks->remark_2 }}"></label>
                                <label class="col-sm-6 header-title">หมายเหตุ ที่ 3 <input
                                        class="form-control form-control-sm" type="text" id=remark_3 name=remark_3
                                        value="{{ $data_for_remarks->remark_3 }}"></label>
                                <label class="col-sm-6 header-title">หมายเหตุ ที่ 4 <input
                                        class="form-control form-control-sm" type="text" id=remark_4 name=remark_4
                                        value="{{ $data_for_remarks->remark_4 }}"></label>
                                <label class="col-sm-6 header-title">หมายเหตุ ที่ 5 <input
                                        class="form-control form-control-sm" type="text" id=remark_5 name=remark_5
                                        value="{{ $data_for_remarks->remark_5 }}"></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="crad">
                                    <div class="card-body">
                                        <div class="form-inline">
                                            <div class="col-sm-6">
                                                <div class="form-inline">
                                                    <p class="col-sm-8 m-t-15 warning-text text-right">xสถานะการจัดส่ง
                                                        :<span class="red">*</span></p>
                                                    <div class="input-group col-sm-4">
                                                        <input type="hidden" name="status_text"
                                                            value="{{ $deliverys->status }}">
                                                        <select required class="form-control" name="status"
                                                            {{ $deliverys->status == 1 ? ' disabled' : '' }}>
                                                            <option value=""> เลือกสถานะ</option>
                                                            <option {{ $deliverys->status == 0 ? 'selected' : '' }}
                                                                value="0"> กำลังดำเนินการ</option>
                                                            <option {{ $deliverys->status == 1 ? 'selected' : '' }}
                                                                value="1"> จัดส่งสำเร็จ</option>
                                                            <option {{ $deliverys->status == 2 ? 'selected' : '' }}
                                                                value="2"> ยกเลิก</option>
                                                            <select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-inline">
                                                    <p class="col-sm-2 m-t-15 warning-text text-date-send">วันที่จัดส่ง
                                                        :<span class="red">*</span></p>
                                                    <div class="input-group col-sm-5">
                                                        <input type="hidden" name="date_send_text datepicker"
                                                            value="{{ date('d/m/Y', strtotime($deliverys->date_send)) }}">
                                                        <input type="text" class="form-control datepicker"
                                                            {{ $deliverys->status == 1 ? ' disabled' : '' }} required
                                                            autocomplete="off" name="date_send" value="">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="form-inline">
                                            <div class="col-sm-6">
                                                <div class="form-inline">
                                                    <p class="col-sm-8 m-t-15 warning-text text-right">สถานะการชำระเงิน
                                                        :<span class="red">*</span></p>
                                                    <div class="input-group col-sm-4">
                                                        <input type="hidden" name="status_payment_text"
                                                            value="{{ $deliverys->status_payment }}">
                                                        <select class="form-control" required name="status_payment"
                                                            {{ $deliverys->status_payment == 1 ? ' disabled' : '' }}>
                                                            <option value=""> เลือกสถานะ</option>
                                                            <option {{ $deliverys->status_payment == 0 ? 'selected' : '' }}
                                                                value="0">รอชำระ</option>
                                                            <option {{ $deliverys->status_payment == 1 ? 'selected' : '' }}
                                                                value="1"> ชำระแล้ว</option>
                                                            <select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="form-inline inputfile">
                                            <div class="col-sm-6">
                                                <div class="form-inline">
                                                    <p class="col-sm-8 m-t-15 warning-text text-right">ไฟล์แนบ :</p>
                                                    <div class="input-group col-sm-4">
                                                        <label for="file-upload" class="btn btn-upload changfile">
                                                            <i class="fa fa-upload"></i> <span
                                                                class="label_upload">เลือกไฟล์แนบ</span>
                                                        </label>
                                                        <input id="file-upload" type="file" name="file"
                                                            value="" style="display: none;"
                                                            accept=" .jpg, .png, .pdf">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-inline inputfile">
                                            <div class="col-sm-6">
                                                <div class="form-inline">
                                                    <p class="col-sm-12 warning-text text-right text-disable"
                                                        id="name_file">ยังไม่ได้แนมไฟล์(.pdf,.jpg,.png)</p>
                                                </div>
                                            </div>
                                        </div>
                                        --}}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="order_delivery_id" value="{{ $deliverys->order_delivery_id }}">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="d-flex flex-row justify-content-center align-items">
                                    <a href="{{ url('orders/delivery/list/' . $orders->id) }}"
                                        class="btn btn-secondary btn-fw">ยกเลิก</a>
                                    <!-- button type="submit" class="btn btn-primary btn-fw">ยืนยันทำรายการ</button -->
                                    <button type="submit" id="render_price" name="render_price" value="Y"
                                        class="btn btn-primary btn-fw">ยืนยันทำรายการแบบ => แสดงราคา</button>
                                    <button type="submit" id="render_price" name="render_price" value="N"
                                        class="btn btn-primary btn-fw">ยืนยันทำรายการแบบ => ไม่แสดงราคา</button>
                                    <?php
                                    $disabled = '';
                                    if ($check_if_already_added_for_payment_history_flag_or_not_update_payment_method_yet == 'Yes') {
                                        $disabled = 'disabled';
                                    }
                                    ?>
                                    <button type="button" {{ $disabled }}
                                        onclick="add_payment_history_record(
																'{{ $deliverys->order_delivery_id }}',
																{{ $orders->id }},
																{{ $deliverys->total }},
																'<?= date('Y/m/d H:i:s') ?>',
															);"
                                        class="btn btn-primary btn-fw">บันทึกประวัติการชำระเงิน สำหรับบิลย่อย</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">แจ้งชำระเงิน &nbsp; <span
                                id="pocket-grand"></span> <span class="pull-right text-success"
                                id="total-calculate"></span>
                        </h5>
                        <input type="hidden" id="order-total" value="{{ $orders->total - $orders->GetDepositSum() }}">
                        <input type="hidden" id="proket-total"
                            value="{{ $orders->customer_type == 'customer' ? (!empty($customer->PocketMoney) ? $customer->PocketMoney->pocket_money : '0.00') : '0.00' }}">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('payment.store') }}" enctype="multipart/form-data" id="paymnet-store"
                        method="POST">
                        @csrf

                        <div class="modal-body">

                            <input type="hidden" name="order_id" value="{{ $orders->id }}">
                            <input type="hidden" name="order_number" value="{{ $orders->order_number }}">
                            <input type="hidden" name="order_delivery_id" value="{{ $deliverys->order_delivery_id }}">

                            <label for="">ประเภทการชำระเงิน <span class="text-danger"> *</span></label>


                            <select name="payment_type" id="payment-type" class="form-select form-control mb-3" required>
                                <option value="">--เลือกการจ่ายเงิน--</option>
                                @forelse ($paymentType as $item)
                                    <option data-method="{{ $item->payment_type_method }}"
                                        data-pocket-money="{{ $item->payment_pocket_money }}"
                                        @if ($item->payment_type_id === $orders->payment_type) selected @endif
                                        value="{{ $item->payment_type_id }}">{{ $item->payment_type_name }}</option>
                                @empty
                                @endforelse
                            </select>


                            <label>จำนวนเงินที่ชำระ <span class="text-danger"> *</span></label>
                            <input type="number" name="total" class="form-control mb-3 payment-total" step="0.01"
                                value="{{ $deliverys->total - $deliverys->money_deposit }}">


                            <label>วันที่ชำระ <span class="text-danger"> *</span></label>
                            <input type="datetime-local" name="date_playment" class="form-control mb-3 "
                                value="{{ date('Y-m-d H:m:s') }}">

                            <label for="">แนบหลักฐานกาารชำระ</label><br>
                            <input type="file" name="file" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-primary" form="paymnet-store">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>





        <script>
            let printCount = 0; // ตัวนับการพิมพ์
            let isPrinting = false; // ตัวบ่งชี้ว่ากำลังพิมพ์อยู่

            // จับเหตุการณ์ก่อนการพิมพ์
            window.addEventListener('beforeprint', function() {
                isPrinting = true; // ตั้งค่ากำลังพิมพ์
                console.log('Preparing to print...');
            });

            // จับเหตุการณ์หลังจากการพิมพ์
            window.addEventListener('afterprint', function() {
                console.log('Print dialog closed...');

                // ตรวจสอบว่าเป็นการพิมพ์จริง
                if (isPrinting) {
                    // ใช้ setTimeout เพื่อเลื่อนการนับสักครู่
                    setTimeout(() => {
                        // ตรวจสอบว่าการพิมพ์เสร็จสมบูรณ์โดยการสร้างตัวแปรใหม่
                        let completedPrinting = confirm(
                            'หากคุณได้ปริ้นเอกสารกรุณา กดใช่ ตกลง ไม่ได้ปริ้นกด ยกเลิก เพื่อบันทึกจำนวนการปริ้นฟอร์ม'
                        );
                        window.location.reload();

                        if (completedPrinting) {
                            printCount++; // เพิ่มค่าตัวนับการพิมพ์
                            console.log('Printing completed...');

                            // ตรวจสอบว่า meta tag csrf-token มีอยู่หรือไม่
                            const csrfTokenMeta = document.querySelector('meta[name="_token"]');
                            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

                            // ส่งข้อมูลไปยังเซิร์ฟเวอร์ด้วย AJAX
                            fetch('{{ route('form.print') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken // CSRF token
                                    },
                                    body: JSON.stringify({
                                        count: printCount
                                    }) // ส่งจำนวนการพิมพ์
                                })
                                .then(response => {
                                    if (response.ok) {
                                        return response.json();
                                    }
                                    throw new Error('Network response was not ok.');
                                })
                                .then(data => console.log(data))
                                .catch(error => console.error('Error:', error));
                        } else {
                            console.log('Printing was canceled or closed without printing.');
                        }

                        // รีเซ็ตตัวบ่งชี้การพิมพ์
                        isPrinting = false;
                    }, 100); // รอ 100ms ก่อนการตรวจสอบ
                } else {
                    console.log('Printing was canceled or closed without printing.');
                }
            });
        </script>




    </div>


    <script>
        function add_payment_history_record(order_delivery_id, order_id, amount, date_time) {
            $.ajax({
                url: "{{ route('payment_history.add_payment_history_record') }}",
                type: 'get',
                data: {
                    order_delivery_id: order_delivery_id,
                    order_id: order_id,
                    amount: amount,
                    date_time: date_time
                },
                //cache: false,
                success: function(res) {
                    //alert(res);
                },
                beforeSend: function() {
                    swal.fire({
                        title: 'กำลังบันทึกประวัติการชำระเงิน',
                        onOpen: () => {
                            swal.showLoading()
                        }
                    });
                },
                complete: function() {
                    swal.fire({
                        title: 'บันทึกประวัติการชำระเงินเรียบร้อย',
                        onOpen: () => {
                            swal.hideLoading()
                        }
                    });
                },
                error: function(data) {
                    console.log('error!' + JSON.stringify(data))
                }
            });
        }
    </script>




    {{-- @include('orders.billing') --}}
    @include('delivery.billing')


    @push('plugin-scripts')
        {!! Html::script('/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') !!}
        {!! Html::script('/js/converCurrencyToText.js') !!}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script src="{{ asset('js/print.min.js') }}"></script>
    @endpush

    @push('custom-scripts')
        <script>
            $(document).ready(function() {
                date = $('#date_send').val()

                date = $('#date_send').val().split('/');

                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true
                });
                mydate = new Date(date[2], date[1] - 1, date[0]);
                $(".datepicker").datepicker("setDate", mydate);


                $('li.nav-item.sidebar-nav-item.orders-meun').addClass('active');

                $('#file-upload').on('change', function(e) {
                    fullPath = $(this).val()
                    if (fullPath) {
                        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath
                            .lastIndexOf('/'));
                        var filename = fullPath.substring(startIndex);
                        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                            filename = filename.substring(1);
                        }
                        checkfile(filename)
                        // $('#name_file').html(filename)
                    } else {


                    }
                })
                $('#updatedelivery').on("submit", function(event) {
                    swal.fire({
                        title: 'กำลังบันทึกข้อมูล..',
                        onOpen: () => {
                            swal.showLoading()
                        }
                    });
                });

            });
            checkPrice()
            checkfile()

            function checkPrice() {
                var total = 0;
                var someArray = $('.item_price')
                for (var i = 0; i < someArray.length; i++) {
                    var id = $(someArray[i]).data('id');
                    var price_item_str = $('.price_item_' + id).html()
                    var res = price_item_str.replace(",", "");
                    var res1 = res.replace(",", "");
                    var res2 = res1.replace(",", "");
                    var res3 = res2.replace(",", "");
                    var res4 = res3.replace(",", "");
                    var res5 = res4.replace(",", "");
                    price_item = Number(res5)
                    var items_send = $('.items_send_' + id).html()
                    var size_unit = $('.size_unit_id_' + id).val()
                    var size_name = $('.size_name_' + id).val()
                    if (size_name == 'x 0.35 ตร.ม.') {
                        total += price_item * items_send * size_unit * 0.35
                    } else {
                        total += price_item * items_send * size_unit
                    }
                }
                var totalfor = total.toFixed(2)
                addCommasTotal(totalfor)
                CehckVat(total)
            }

            function addCommasTotal(nStr) {
                nStr += '';
                x = nStr.split('.');
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '.0';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                var total = x1 + x2;
                $('#total_long_order').html(total)
            }

            function CehckVat(total) {
                checkvat = $('#check_vat').val()
                if (checkvat == '1') {
                    vat = total / 100 * 7
                    var vatfor = vat.toFixed(2)
                    addCommasVat(vatfor)
                    CountTotalAll(total, vat)
                } else {
                    $('#vat_long').html('0.00')
                    CountTotalAll(total, 0)
                }
            }

            function addCommasVat(nStr) {
                nStr += '';
                x = nStr.split('.');
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '.0';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                var total = x1 + x2;
                $('#vat_long').html(total);
            }

            function CountTotalAll(total, vat) {
                count = total + vat
                counttotal = count.toFixed(2)
                // Payment(counttotal)
                nStr = counttotal
                nStr += '';
                x = nStr.split('.');
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '.0';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                var total = x1 + x2;
                $('#total_all').html(total)
                <?php
                if ($datas[0]->order_delivery_render_price == 'N') {
                    echo "$('.total_all_bill').text('n/a');";
                    echo "$('.convertText').text('n/a');";
                } else {
                    echo "$('.total_all_bill').text(total);";
                    echo "$('.convertText').text(BAHTTEXT(total));";
                }
                ?>
                //$('.total_all_bill').text(total)
                //$('.convertText').text(BAHTTEXT(total));
                // console.log(total,'dddd');
            }

            function checkfile(newfile) {

                if (newfile) {
                    $('#name_file').html(newfile)
                } else {
                    name_file_have = $('#name_file_have').val()
                    if (name_file_have != null && name_file_have != '') {
                        urlfile = window.location.origin + '/storage/delivery/' + name_file_have
                        console.log(urlfile);
                        html = '<a href="' + urlfile + '" target="_blank" class="btn-link">' + name_file_have + '</a>'
                        $('#name_file').html(html)
                    } else {
                        $('#name_file').html('ยังไม่ได้แนมไฟล์(.pdf,.jpg,.png)')
                    }
                }
            }

            function printBilling(name) {

                var printContents = document.getElementById('printBillings').innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;

            }
        </script>

        // Pocket Money
        <script>
            $(document).ready(function() {
                // จับ event change ของ #payment-type และ .payment-total
                $('#payment-type, .payment-total').on('change keyup', function() {
                    var pocket = $('#payment-type').find(':selected').data(
                    'pocket-money'); // ดึงค่าจาก option ที่เลือก
                    var pocketTotal = parseFloat($('#proket-total').val()); // ค่าจากกระเป๋าเงิน
                    var total = parseFloat($('.payment-total').val()); // ยอดเงินที่ต้องชำระ

                    if (pocket === 'Y') {
                        var remaining = pocketTotal - total;
                        if (remaining < 0) {
                            // ยอดในกระเป๋าไม่พอ
                            alert('ยอดเงินในกระเป๋าไม่เพียงพอ');
                            // เซ็ต total ให้เท่ากับ pocketTotal
                            $('.payment-total').val(pocketTotal.toFixed(2));
                            $('#pocket-grand').text('0.00'); // ยอดคงเหลือเป็น 0
                        } else {
                            $('#pocket-grand').text(remaining.toFixed(2)); // แสดงยอดคงเหลือ
                        }
                    }
                });

                // ตรวจสอบก่อน submit
                // $('#paymnet-store').on('submit', function(e) {
                //     var pocketTotal = parseFloat($('#proket-total').val()); // ค่าจากกระเป๋าเงิน
                //     var total = parseFloat($('.payment-total').val()); // ยอดเงินที่ต้องชำระ

                //     if (total > pocketTotal) {
                //         // ถ้ายอดชำระมากกว่ายอดกระเป๋าเงิน ให้ป้องกันการ submit form
                //         alert('ยอดชำระมากกว่ายอดในกระเป๋าเงิน');
                //         e.preventDefault(); // ยกเลิกการ submit
                //     }
                // });
            });
        </script>
    @endpush


@stop
