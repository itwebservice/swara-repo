<form id="frm_tab41_c">
	<div class="row mg_bt_10">
	<?php
		$basic_cost1 = $sq_quotation['subtotal'];
		$service_charge = $sq_quotation['service_charge'];
		$markup = $sq_quotation['markup_cost'];

		$bsmValues = json_decode($sq_quotation['bsm_values']);
		$service_tax_amount = 0;
		if($sq_quotation['service_tax_subtotal'] !== 0.00 && ($sq_quotation['service_tax_subtotal']) !== ''){
			$service_tax_subtotal1 = explode(',',$sq_quotation['service_tax_subtotal']);
			for($i=0;$i<sizeof($service_tax_subtotal1);$i++){
				$service_tax = explode(':',$service_tax_subtotal1[$i]);
				$service_tax_amount = $service_tax_amount + $service_tax[2];
			}
		}
		$markupservice_tax_amount = 0;
		if($sq_quotation['markup_cost_subtotal'] !== 0.00 && $sq_quotation['markup_cost_subtotal'] !== ""){
			$service_tax_markup1 = explode(',',$sq_quotation['markup_cost_subtotal']);
			for($i=0;$i<sizeof($service_tax_markup1);$i++){
				$service_tax = explode(':',$service_tax_markup1[$i]);
				$markupservice_tax_amount = $markupservice_tax_amount+ $service_tax[2];
			}
		}
		foreach($bsmValues[0] as $key => $value){
			switch($key){
				case 'basic' : $basic_cost = ($value != "") ? $basic_cost1 + $service_tax_amount : $basic_cost1;$inclusive_b = $value;break;
				case 'service' : $service_charge = ($value != "") ? $service_charge + $service_tax_amount : $service_charge;$inclusive_s = $value;break;
				case 'markup' : $markup = ($value != "") ? $markup + $markupservice_tax_amount : $markup;$inclusive_m = $value;break;
			}
		}
		if($bsmValues[0]->tax_apply_on == '1') { 
			$tax_apply_on = 'Basic Amount';
		}
		else if($bsmValues[0]->tax_apply_on == '2') { 
			$tax_apply_on = 'Service Charge';
		}
		else if($bsmValues[0]->tax_apply_on == '3') { 
			$tax_apply_on = 'Total';
		}else{
			$tax_apply_on = '';
		}
    ?>
	<input type="hidden" id="tax_apply_on" name="tax_apply_on" value="<?php echo $tax_apply_on ?>">
	<input type="hidden" id="atax_apply_on" name="atax_apply_on" value="<?php echo $bsmValues[0]->tax_apply_on ?>">
	<input type="hidden" id="tax_value1" name="tax_value1" value="<?php echo $bsmValues[0]->tax_value ?>">
	<input type="hidden" id="markup_tax_value1" name="markup_tax_value1" value="<?php echo $bsmValues[0]->markup_tax_value ?>">
	<div class="col-md-2">
		<small id="basic_show1"><?= ($inclusive_b == '') ? '&nbsp;' : 'Inclusive Amount : <span>'.$inclusive_b ?></span></small>
		<input type="text" id="subtotal1" name="subtotal1" placeholder="Basic Cost"  class="form-control" title="Basic Cost" onchange="validate_balance(this.id);get_auto_values('quotation_date1','subtotal1','payment_mode','service_charge1','markup_cost1','update','true','basic');" value="<?= $basic_cost ?>">  

	</div>
	<div class="col-md-2 col-sm-6 col-xs-12 mg_bt_10">
		<small id="service_show1"><?= ($inclusive_s == '') ? '&nbsp;' : 'Inclusive Amount : <span>'.$inclusive_s ?></span></small>
    	<input type="text" name="service_charge1" id="service_charge1" placeholder="*Service Charge" title="Service Charge" value="<?= $service_charge ?>" onchange="validate_balance(this.id);get_auto_values('quotation_date1','subtotal1','payment_mode','service_charge1','markup_cost1','update','false','service_charge');  ">
	</div>
	<div class="col-md-2">
		<small>&nbsp;</small>
		<input type="text" id="service_tax_subtotal1" name="service_tax_subtotal1" class="form-control" readonly placeholder="Tax Amount" title="Tax Amount" value="<?php echo $sq_quotation['service_tax_subtotal']; ?>">

	</div>
	<div class="col-md-2">
		<small id="markup_show1"><?= ($inclusive_m == '') ? '&nbsp;' : 'Inclusive Amount : <span>'.$inclusive_m ?></span></small>
		<input type="text" id="markup_cost1" name="markup_cost1" class="form-control" placeholder="Markup Amount" title="Markup Amount" onchange="validate_balance(this.id);get_auto_values('quotation_date1','subtotal1','payment_mode','service_charge1','markup_cost1','update','false','markup');  " value="<?= $markup ?>">  
	</div>
		<div class="col-md-2 col-sm-6 col-xs-12 mg_bt_10">
		<small>&nbsp;</small>
		<input type="text" id="service_tax_markup1" name="service_tax_markup1" placeholder="*Tax on Markup" title="Tax on Markup" onchange="  " value="<?= $sq_quotation['markup_cost_subtotal'] ?>" readonly>
	</div> 
	<div class="col-md-2"> 
		<small>&nbsp;</small>
		<input type="text" id="permit1" name="permit1" class="form-control" placeholder="Permit charges" title="Permit charges" value="<?php echo $sq_quotation['permit']; ?>" onchange="quotation_cost_calculate1(); validate_balance(this.id)">  
	</div>
</div>
<div class="row mg_bt_10">
    <div class="col-md-2">
		<input type="text" id="toll_parking1" name="toll_parking1" class="form-control" placeholder="Toll & Parking charges" title="Toll & Parking charges" value="<?php echo $sq_quotation['toll_parking']; ?>" onchange="quotation_cost_calculate1(); validate_balance(this.id)"> 
	</div>
	<div class="col-md-2">
	    <input type="text" id="driver_allowance1" name="driver_allowance1" class="form-control" placeholder="Driver Allowance" title="Driver Allowance" value="<?php echo $sq_quotation['driver_allowance']; ?>" onchange="quotation_cost_calculate1(); validate_balance(this.id)">
	</div>
	<div class="col-md-2">
		<input type="text" id="state_entry1" class="form-control" name="state_entry1" placeholder="State Entry" title="State Entry" value="<?php echo $sq_quotation['state_entry']; ?>" onchange="quotation_cost_calculate1();validate_balance(this.id)" > 
	</div>
	<div class="col-md-2">
		<input type="text"  id="other_charges1" name="other_charges1" class="form-control" placeholder="Other Charges" title="Other Charges" onchange="quotation_cost_calculate1();validate_balance(this.id)" value="<?php echo $sq_quotation['other_charge']; ?>" > 
	</div>
</div>	 
<div class="row">
	<div class="col-md-2">
		<input type="text" id="roundoff1" name="roundoff1" class="form-control" placeholder="Round Off" title="Round Off" value="<?= $sq_quotation['roundoff'] ?>" onchange="validate_balance(this.id)" readonly>
	</div>
	<div class="col-md-2">
	    <input type="text" id="total_tour_cost1" name="total_tour_cost1" class="form-control" onchange="validate_balance(this.id);" placeholder="Total" title="Total" value="<?php echo $sq_quotation['total_tour_cost']; ?>" readonly>
	</div>
</div>
	<div class="row mg_tp_20 text-center">
		<div class="col-md-12">
			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab1()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>
			&nbsp;&nbsp;
			<button class="btn btn-sm btn-success" id="btn_quotation_update"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
		</div>
	</div>
</form>

<script>

function quotation_cost_calculate1(){
	var subtotal = $('#subtotal1').val();  
	var markup_cost = $('#markup_cost1').val(); 
    var markup_cost_subtotal = $('#service_tax_markup1').val(); 
	var permit = $('#permit1').val();
	var toll_parking = $('#toll_parking1').val();
	var driver_allowance = $('#driver_allowance1').val();
	var state_entry = $('#state_entry1').val();
	var other_charges = $('#other_charges1').val();

	var service_tax_markup = $('#service_tax_markup1').val();
	var service_tax_subtotal = $('#service_tax_subtotal1').val();
	var service_charge = $('#service_charge1').val();

    if(subtotal==""||subtotal=='0'){ subtotal = 0;}
    if(markup_cost==""||markup_cost=="0"){ markup_cost = 0;}
    if(service_charge==""||service_charge=='0'){ service_charge = 0;}
    if(permit==""||permit=='0'){permit = 0;}
    if(toll_parking==""||toll_parking=='0'){ toll_parking = 0;}
	if(driver_allowance==""||driver_allowance=='0'){ driver_allowance = 0;}
	if(state_entry==""||state_entry=='0'){ state_entry = 0;}
	if(other_charges==""||other_charges=='0'){ other_charges = 0;}

	var service_tax_amount = 0;
    if(parseFloat(service_tax_subtotal) !== 0.00 && (service_tax_subtotal) !== ''){

	var service_tax_subtotal1 = service_tax_subtotal.split(",");
	for(var i=0;i<service_tax_subtotal1.length;i++){
        var service_tax = service_tax_subtotal1[i].split(':');
        service_tax_amount = parseFloat(service_tax_amount) + parseFloat(service_tax[2]);
		}
	}
	var markupservice_tax_amount = 0;
    if(parseFloat(service_tax_markup) !== 0.00 && (service_tax_markup) !== ""){
		var service_tax_markup1 = service_tax_markup.split(",");
		for(var i=0;i<service_tax_markup1.length;i++){
			var service_tax = service_tax_markup1[i].split(':');
			markupservice_tax_amount = parseFloat(markupservice_tax_amount) + parseFloat(service_tax[2]);
		}
	}

	subtotal = ($('#basic_show1').html() == '&nbsp;') ? subtotal : parseFloat($('#basic_show1').text().split(' : ')[1]);
	service_charge = ($('#service_show1').html() == '&nbsp;') ? service_charge : parseFloat($('#service_show1').text().split(' : ')[1]);
	markup_cost = ($('#markup_show1').html() == '&nbsp;') ? markup_cost : parseFloat($('#markup_show1').text().split(' : ')[1]);

	total_tour_cost = 	parseFloat(subtotal) + 
						parseFloat(markupservice_tax_amount) + 
						parseFloat(permit) + 
						parseFloat(toll_parking) + 
						parseFloat(driver_allowance) + 
						parseFloat(service_tax_amount) + 
						parseFloat(state_entry) + 
						parseFloat(other_charges) +
						parseFloat(markup_cost) +
						parseFloat(service_charge);
	
	var roundoff = Math.round(total_tour_cost)-total_tour_cost;
	$('#roundoff1').val(roundoff.toFixed(2));
	var total_cost = parseFloat(total_tour_cost) + parseFloat(roundoff);
	$('#total_tour_cost1').val(total_cost.toFixed(2));
}
//   
function switch_to_tab1(){ $('a[href="#tab_1_c"]').tab('show'); }

$('#frm_tab41_c').validate({
	rules:{
		tour_cost : { required : true, number: true },
		taxation_id : { required : true },
	},
	submitHandler:function(form){
		$('#btn_quotation_update').prop('disabled', true);
		var enquiry_id = $("#enquiry_id1").val();
		var quotation_id = $('#quotation_id1').val();
		var customer_name = $('#customer_name1').val();
		var email_id = $('#email_id1').val();
		var mobile_no = $('#mobile_no1').val();
		var country_code = $('#country_code1').val();
		var total_pax = $("#total_pax1").val();
		var days_of_traveling = $('#days_of_traveling1').val();
		var traveling_date = $('#traveling_date1').val();
		var capacity = $('#capacity1').val();
		var travel_type = $('#travel_type1').val();
		var route = $('#places_to_visit1').val();
		var vehicle_name = $('#vehicle_name1').val();
		var from_date = $('#from_date1').val();
		var to_date = $('#to_date1').val();
		var extra_km_cost = $('#extra_km_cost1').val();
		var extra_hr_cost = $('#extra_hr_cost1').val();		
		var total_hrs = $('#total_hr1').val();
		var local_places_to_visit = $('#local_places_to_visit1').val();
		var total_km = $('#total_km1').val();
		var rate = $('#rate1').val();
		var total_max_km = $('#total_max_km').val();
		var subtotal = $('#subtotal1').val();
		var markup_cost = $('#markup_cost1').val();
		var markup_cost_subtotal = $('#service_tax_markup1').val();
		var taxation_id = $('#taxation_id1').val();
		var service_charge = $('#service_charge1').val();
		var service_tax_subtotal = $('#service_tax_subtotal1').val();
		var permit = $('#permit1').val();
		var toll_parking = $('#toll_parking1').val();
		var driver_allowance = $('#driver_allowance1').val();
		var total_tour_cost = $('#total_tour_cost1').val();
		var state_entry = $('#state_entry1').val();
		var markup_show = $('#markup_show1').val();
		var roundoff = $('#roundoff1').val();
		var active_flag = $('#active_flag1').val();
		var tax_apply_on = $('#atax_apply_on').val();
		var tax_value = $('#tax_value1').val();
		var markup_tax_value = $('#markup_tax_value1').val();

		var bsmValues = [];
		bsmValues.push({
			"basic" : $('#basic_show1').find('span').text(),
			"service" : $('#service_show1').find('span').text(),
			"markup" : $('#markup_show1').find('span').text(),
			'tax_apply_on':tax_apply_on,
			'tax_value':tax_value,
			'markup_tax_value':markup_tax_value
		});

		var other_charges = $('#other_charges1').val();
		var quotation_date = $('#quotation_date1').val();

		var base_url = $('#base_url').val();

		if(parseFloat(taxation_id) == "0"){ error_msg_alert("Please select Tax Percentage");
		$('#btn_quotation_update').prop('disabled', false); return false; }

		$('#btn_quotation_save').button('loading');

		$.ajax({

			type:'post',

			url: base_url+'controller/package_tour/quotation/car_rental/quotation_update.php',

			data:{quotation_id : quotation_id, enquiry_id : enquiry_id , total_pax : total_pax, days_of_traveling : days_of_traveling,traveling_date : traveling_date, travel_type : travel_type,vehicle_name : vehicle_name, from_date : from_date, to_date : to_date, route : route,extra_km_cost : extra_km_cost , extra_hr_cost : extra_hr_cost, subtotal : subtotal,markup_cost : markup_cost,markup_cost_subtotal : markup_cost_subtotal, taxation_id : taxation_id, service_charge : service_charge , service_tax_subtotal : service_tax_subtotal, permit : permit, toll_parking : toll_parking, driver_allowance : driver_allowance , total_tour_cost : total_tour_cost, customer_name : customer_name,quotation_date : quotation_date,email_id : email_id, mobile_no : mobile_no,country_code:country_code,other_charges:other_charges,state_entry:state_entry,capacity:capacity,total_hrs:total_hrs,total_km:total_km,rate:rate,total_max_km:total_max_km,local_places_to_visit:local_places_to_visit, roundoff : roundoff, bsmValues : bsmValues,active_flag:active_flag},
			success: function(message){			
                	$('#btn_quotation_update').button('reset');
                	var msg = message.split('--');
					if(msg[0]=="error"){
						error_msg_alert(msg[1]);
						$('#btn_quotation_update').prop('disabled', false);
						return false;
					}
					else{
						$('#vi_confirm_box').vi_confirm_box({
						            false_btn: false,
						            message: message,
						            true_btn_text:'Ok',
						    callback: function(data1){
						        if(data1=="yes"){
					        		$('#quotation_update_modal').modal('hide');
									$('#btn_quotation_update').prop('disabled', false);
									document.location.reload();
						        }
						    }
						});
					}

                }  
		});

	}
});

        	 
</script>
