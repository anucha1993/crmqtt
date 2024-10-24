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
                    <h3 class="title-page">ตรวจสอบยอดเงิน บิลหลัก/บิลย่อย</h3>
                </div>
                <div class="text-right col-sm-6">
                    <!--a href="{{url('/customer/add')}}" class="btn btn-add mb-2" id="btn_expense"><i class="fa fa-plus" aria-hidden="true"></i> เพิ่มร้านค้า</a-->
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="input-group col-4">
                    <input class="form-control" type="text" name="mother_bill_code" id="mother_bill_code" value="" placeholder="เลขที่บิลหลัก..." onkeyup="scan_ch_mother_bill(event);">
@push('custom-scripts')
<script>
$(document).ready(function() {$( "#mother_bill_code" ).focus(); });
i = 1;
function scan_ch_mother_bill(e){
	if(e.keyCode === 13){
		alert(document.getElementById("mother_bill_code").value);
		$.ajax({
                  url: '{{route('orders.barcode_ajax')}}',
				  headers: {
                  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  },
				  data: { id: document.getElementById("mother_bill_code").value} ,
                  type: 'POST',
                  success: function(data) {
					  alert(data);
                      document.getElementById("total_amount_pera").innerHTML = data;
		  },
	       	  error: function(data){
                      alert(JSON.stringify(data));
     		  }
        });
		document.getElementById("sub_bill_code[1]").focus();
	}
}
function scan_ch_sub_bill(e){
	if(e.keyCode === 13){
		$.ajax({
			  url: '{{route('orders.barcode_sub_ajax')}}',
			  headers: {
			  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
			  },
			  data: { id: document.getElementById("mother_bill_code").value} ,
			  type: 'POST',
			  success: function(data) {
				  alert("in success");
				  alert("i:"+i);
				  alert(data);
				  index_sub_pera = "sub_bill_amount["+i+"]";
				  alert("index_sub_pera: "+index_sub_pera);
				  document.getElementById(index_sub_pera).value = data;
				  i++;
				  index_pera = "sub_bill_code["+i+"]";
				  alert(i);
				  alert(index_pera);
				  //alert(document.getElementById(index_pera).value);
				  BBB = '<input class="form-control" type="text" name='+index_pera+' id='+index_pera+' placeholder="เลขที่บิลย่อย..." onkeyup="scan_ch_sub_bill(event);">';
				  BBB = BBB + '<input class="form-control col-sm-4 col-md-4" type="text" name=sub_bill_amount['+i+'] id=sub_bill_amount['+i+'] placeholder="ยอดเงิน...">';
				  next_sub_bill = '<div class="row" ><div class="input-group col-6">'+BBB+'</div></div>';
				  document.getElementById("div_sub_bill").insertAdjacentHTML('beforeend', next_sub_bill);
				  document.getElementById(index_pera).focus();
			  },
	       	  error: function(data){
                      alert(JSON.stringify(data));
     		  }
        });
		
	}
}
</script>
@endpush
                </div>
            </div><p>
			<table border=1 cellpadding=10>
				<tr>
					<td>
						ยอดเงินทั้งหมด 
					</td>
					<td>
						<font size=4 id=total_amount_pera>2000</font> บาท
					</td>
				</tr>
				<tr>
					<td>
						ยอดเงินรวมจากบิลย่อย
					</td>
					<td>
						<font size=4 id=total_sub_amount_pera>2000</font> บาท
					</td>
				</tr>
			</table>
			<p>
			<div class="row">
				<div class="text-left col-sm-6">
					<h4 class="title-page">บิลย่อย</h4>
				</div>
			</div>
			<div id=div_sub_bill>
				<div class="row" >
					<div class="input-group col-6">
						<input class="form-control" type="text" name=sub_bill_code[1] id=sub_bill_code[1] value="" placeholder="เลขที่บิลย่อย..." onkeyup="scan_ch_sub_bill(event);">
						<input class="form-control col-sm-4 col-md-4" type="text" name=sub_bill_amount[1] id=sub_bill_amount[1] value="" placeholder="ยอดเงิน...">
					</div>
				</div>
			</div>
			<p>
            {{-- <h4 class="card-title">Orders</h4> --}}
            <!--div class="table-responsive">
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
            </div >
            <div class="row footer-table">
                <div class="col-sm-12 col-md-5">
                    <div class="con-data" id="box_tltal"></div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="pagination"></div>
                </div>
            </div -->
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




