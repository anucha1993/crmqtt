<!-- common css -->
  {!! Html::style('css/app.css') !!}
  {!! Html::style('css/main.css') !!}
<!-- end common css -->
<?php
	$i_pera = $_REQUEST['i_pera'];
?>
<script>
function fill_product_name(selected_product_name, selected_product_weight, selected_product_id) {
		window.opener.document.getElementById('product_name[<?=$i_pera?>]').value = selected_product_name;
		window.opener.document.getElementById('weight_per_unit_pera[<?=$i_pera?>]').value = selected_product_weight;
		window.opener.document.getElementById('product_id[<?=$i_pera?>]').value = selected_product_id;
		close();
	}
</script>
 
<?php
	foreach ($datas as $data) {
		echo "<table cellpadding=10 cellspacing=10 border=1>";
		echo "<tr>";
		echo "<td width=200px>";
		echo $data->product_name;
		echo "</td>";
		echo "<td>";
		echo '<button class="btn btn-primary btn-fw" onclick="alert('.$data->id.'); fill_product_name(\''.$data->product_name.'   \' ,'.$data->weight.' ,'.$data->id.' )             ;">เลือก</button>';
		echo "</td>";
		echo "</tr>";
		echo "</table>";
	}
?>