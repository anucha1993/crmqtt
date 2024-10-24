<style>
    /* body {
        background: #FFFF;
    } */
    .content-wrapper.print-pdf {
        background: #FFFF;
    }
    .grid-margin-pdf{
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .color-title{
        color: #33467A;
    }
    .name-crmqtt{
        font-size: 25px;
        font-weight: 1000;
    }
    .detail-crmqtt{
        font-size: 18px;
    }
    .pdf-title{
        font-size: 22px;
    }
	table.pera {
	  border: 1px solid black;
	}
    table.table-pdf>tbody>tr>th,
    table.table-pdf>tbody>tr>td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    table.table-pdf>tbody>tr>th{
        padding: 7px;
    }
    table.table-pdf{
        margin-top: 20px;
    }
    table.table-pdf>tbody>tr>td.colspan {
        border: none;
    }
    td.table-conclude {
        background: #c6d9f6;
    }
    .row.pt30{
        padding-top: 30px
    }
    .row.pb10{
        padding-bottom: 10px;
    }
    table.table-pdf>tbody>tr>td.table-nulldata{
        padding: 15px;
    }
    .text-right.text-bold{
        font-weight: bold;
        font-size: 30px;
    }

</style>
<div class="container-fluid">
    <div class="content-wrapper print-pdf">
        <div class="row">
            <div class="col-lg-12 grid-margin-pdf">
                <div class="text-center">
                    <label class="title-page color-title col-md-12 name-crmqtt">บริษัท เจริญมั่น คอนกรีต จำกัด(สำนักงานใหญ่)</label>
                    <label class="title-page color-title col-md-12 detail-crmqtt">ที่อยู่ 99/35 หมู่ 9 ตำบลละหาร อำเภอบางบัวทอง จังหวัดนนทบุรี 11110 โทร 082-4789197</label>
                    <label class="title-page color-title col-md-12 detail-crmqtt">เลขประจำตัวผู้เสียภาษี 0125560015546</label>
                    <h4 class="title-page col-md-12 color-title pdf-title">ใบเสนอราคา/ใบแจ้งหนี้</h4>
                    <div class="row">
                        <label class="text-right col-md-8 color-title title-page">วันที่ {{date('d/m/Y',strtotime($quotation->created_at))}}</label>
                        <!--label class="text-left col-md-4 color-title title-page">{{date('d/m/Y',strtotime($quotation->created_at))}}</label-->
                    </div>
                    <div class="row">
                        {{-- <div class="col-md-1"></div> --}}
                        <label class="title-page text-left color-title col-md-2">ชื่อร้านค้า: </label>
                        <label class="title-page text-left color-title col-md-4">{{$customer->store_name}} </label>
                        <label class="title-page text-left color-title col-md-2">ผู้ซื้อ/ผู้ติดต่อ: </label>
                        <label class="title-page text-left color-title col-md-4">{{$customer->customer_name}} </label>

                    </div>
                    <div class="row">
                        {{-- <div class="col-md-1"></div> --}}
                        <label class="title-page text-left color-title col-md-2">ที่อยู:่ </label>
                        @if($quotation->customer_type =='customer')
                            <label class="title-page text-left color-title col-md-8">{{$customer->customer_address .' ตำบล '.$customer->district_name.' อำเภอ '.$customer->amphoe_name.' จังหวัด '.$customer->province_name.' '.$customer->zip_code}} </label>
                        @else
                            <label class="title-page text-left color-title col-md-8">{{$customer->customer_address}}</label>
                        @endif
                    </div>
                    <div class="row">
                        {{-- <div class="col-md-1"></div> --}}
                        <label class="title-page text-left color-title col-md-3">เลขประจำตัวผู้เสียภาษีอากร: </label>
                        <label class="title-page text-left color-title col-md-8">{{$customer->text_number_vat}} </label>
                    </div>
					<div class="row">
                        {{-- <div class="col-md-1"></div> --}}
                        <label class="title-page text-left color-title col-md-3">ที่อยู่จัดส่ง: </label>
                        <label class="title-page text-left color-title col-md-8">{{$customer->customer_address}} </label>
                    </div>
					<div class="row">
                        {{-- <div class="col-md-1"></div> --}}
                        <label class="title-page text-left color-title col-md-3">ผู้ติดต่อหน้างาน: </label>
                        <label class="title-page text-left color-title col-md-8">{{$customer->customer_name}} </label>
                    </div>
                    <div class="row">
                        <table border=1 style="width:100%; border-color:#e8ebe9;">
                            <tr style="width:100%;">
                              <th style="width:10%">ลำดับ</th>
                              <th style="width:10%">จำนวน</th>
                              <th style="width:10%">หน่วยนับ</th>
                              <th style="width:40%">รายการสินค้า</th>
                              <th style="width:15%">ราคาต่อหน่วย</th>
                              <th style="width:15%">จำนวนเงินไม่รวมภาษี</th>
                            </tr>
                            @foreach ($datas as $key => $data)
                            <tr style="width:100%;">
                                <td>{{$key+1}}</td>
                                <td>{{number_format($data->number_order)}}</td>
                                <td>{{countunitstr($data->count_unit)}}</td>
								
								<?php if ($data->product_type_id == 1) { ?>
									<td>{{$data->product_name}} {{$data->size_unit.' ต้น '}}</td>
								<?php }elseif ($data->product_type_id == 2) { ?>
									<td>{{$data->product_name}} {{" ยาว ".$data->size_unit." เมตร ".$data->pera}}</td>
								<?php }else { ?> ?>
									<td>{{$data->product_name}} {{$data->size_unit.' '.$data->size_name</td>
								<?php } ?>
								
                                <!-- <td>{{$data->product_name}} {{$data->type_name}} {{$data->size_unit.' '.$data->size_name}}</td> -->
                                <td>{{number_format($data->price,2)}}</td>
                                <td class="total_item_print_pdf">{{number_format($data->total_item,2)}}</td>
                            </tr>
                            @endforeach

                            @for($i = count($datas)+1; $i <= 21-count($datas); ++ $i)
                                <tr style="width:100%;">
                                    <td class="table-nulldata"></td>
                                    <td class="table-nulldata"></td>
                                    <td class="table-nulldata"></td>
                                    <td class="table-nulldata"></td>
                                    <td class="table-nulldata"></td>
                                    <td class="table-nulldata"></td>
                                </tr>
                            @endfor
                            <tr style="width:100%;">
                                <td colspan="4" class="colspan"></td>
                                <td class="table-conclude">รวมเป็นเงิน</td>
                                <td id="total_all_item_pdf" class="text-right text-bold">0.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="colspan"></td>
                                <td class="table-conclude">ภาษี 7%</td>
                                <td id="tatal_vat_pdf" class="text-right text-bold">0.00</td>
                            </tr>
                            <<tr>
                                <td colspan="4" class="colspan"></td>
                                <td class="table-conclude">ยอดเงินสุทธิ</td>
                                <td id="total_all_pdf" class="text-right text-bold">0.00</td>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        {{-- <label class="number_text col-sm-12 text-left">จำนวนเงินทั้งสิ้น(ตัวอกษร) ({{baht_text($quotation->total)}})</label> --}}
                        <label class="number_text col-sm-12 text-left">จำนวนเงินทั้งสิ้น(ตัวอกษร) ({{baht_text(round($quotation->total + ($quotation->total/100)*7))}})</label>
                    </div>
                    <div class="row pt30 pb10">
                        <label class="number_text col-sm-12 text-left">ลงชื่อ..............................................................................ผู้แจ้งหนี้</label>
                    </div>
                    <div class="row">
                        <label class="number_text col-sm-3 text-left">หมายเหตุ เงื่อนไขการชำระเงิน</label>
                        <label class="col-sm-9 text-left">1. โอนก่อนจัดส่งสินค้า</label>
                    </div>
                    <div class="row">
                        <label class="number_text col-sm-3 text-left"></label>
                        <label class="col-sm-9 text-left">2. ชำระเป็นเงินสด เมื่อตรวจรับสินค้าเรียบร้อย</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('custom-scripts')
<script>
    function totalitempdf ()
    {

        var total = 0;
        var someArray = $('.total_item_print_pdf')
        for (var i = 0; i < someArray.length; i++) {
            var str =  $(someArray[i]).html();
            var res = str.replace(",", "");
            var res1 = res.replace(",", "");
            var res2 = res1.replace(",", "");
            var res3 = res2.replace(",", "");
            var res4 = res3.replace(",", "");
            var res5 = res4.replace(",", "");
            var res6 = res5.replace(",", "");
            var res7 = res6.replace(",", "");
            total += Number(res7);
        }
        var totalfor =  total.toFixed(2)
        addCommasNumber(totalfor,'total_all_item_pdf')
        VatTotalPDF(totalfor)
        TotalAllPdf()
    }

    function addCommasNumber(nStr,textid)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '.00';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        var total = x1+x2;
        $('#'+textid).html(total)
    }

    function VatTotalPDF(totalfor)
    {

           vat =  Math.round(totalfor/100*7)
           addCommasNumber(vat,'tatal_vat_pdf')


    }

    function TotalAllPdf()
    {
        var str =  $('#total_all_item_pdf').html()
        var res = str.replace(",", "");
        var res1 = res.replace(",", "");
        var res2 = res1.replace(",", "");
        var res3 = res2.replace(",", "");
        var res4 = res3.replace(",", "");
        var res5 = res4.replace(",", "");
        var res6 = res5.replace(",", "");
        var res7 = res6.replace(",", "");
        totalitem = Number(res7)

        var str =  $('#tatal_vat_pdf').html()
        // var res = str.replace(",", "");
        var res1 = res.replace(",", "");
        var res2 = res1.replace(",", "");
        var res3 = res2.replace(",", "");
        var res4 = res3.replace(",", "");
        var res5 = res4.replace(",", "");
        var res6 = res5.replace(",", "");
        var res7 = res6.replace(",", "");
        totalvat = Number(res7)

        totalall = totalitem+totalvat
        // $('#total_all_pdf').html('6666')
        addCommasNumber(totalall,'total_all_pdf')
        // total_all_pdf

    }
</script>
@endpush
