{!! Html::script('js/jquery.min.js') !!}
<!-- common css -->
{!! Html::style('css/app.css') !!}
{!! Html::style('css/main.css') !!}
<!-- end common css -->

@push('plugin-scripts')
{!! Html::script('/js/inputmoney.js') !!}
{!! Html::script('/js/inputnumber.js') !!}
{!! Html::script('/plugins/jquery-ui/jquery-ui.js') !!}
@endpush
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<?php
	$i_pera = $_REQUEST['i_pera'];
?>
<script>
function update_payment_method_type_code_and_pocket_money(payment_method_type_code, id, amount) {
	$.ajax({
				url: "{{route('orders.update_payment_method_type_code')}}",
				type: 'get',
				data: {payment_method_type_code:payment_method_type_code, id:id},
				//cache: false,
				success: function(res){
					$.ajax({
							url: "{{route('payment_method_pocket_money.add_payment_method_pocket_money')}}",
							type: 'get',
							data: {id:id, amount:amount},
							//cache: false,
							success: function(res){
								swal.fire({
									title: "เรียบร้อย",
									onOpen: () => {
									  swal.hideLoading()
									}
								}).then((result) => {
									  if (result.value) {
										window.close();
									  }
									});
							},
							beforeSend: function(){
								
							},
							complete: function(){
								
							},
							error: function(data){
								console.log('error!'+JSON.stringify(data))
							}
						});
				},
				beforeSend: function(){
					swal.fire({
						title: id+" บันทึกประเภทการชำระเงินและจำนวนเงิน",
						onOpen: () => {
						  swal.showLoading()
						}
					});
				},
				complete: function(){
					
					},
				error: function(data){
					console.log('error!'+JSON.stringify(data))
				}
            });
}
</script>

<table border=0 cellpadding=10 cellspacing=10>
	<tr>
		<td>ระบุจำนวน Pocket Money: <input id= "payment_method_pocket_money_amount_id" type=text value=998877> บาท
		</td>
		<td><button class="btn btn-primary btn-fw" onclick="update_payment_method_type_code_and_pocket_money(3, {{$i_pera}}, document.getElementById('payment_method_pocket_money_amount_id').value);">กำหนด</button>
		</td>
	<tr>
</table>


