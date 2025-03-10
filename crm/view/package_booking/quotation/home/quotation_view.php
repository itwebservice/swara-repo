<?php
include "../../../../model/model.php";
/*======******Header******=======*/
include_once('../../../layouts/fullwidth_app_header.php'); 
global $similar_text;
$quotation_id = $_GET['quotation_id'];
$role = $_SESSION['role'];
$sq_quotation = mysqli_fetch_assoc(mysqlQuery("select * from package_tour_quotation_master where quotation_id='$quotation_id'"));
$sq_package = mysqli_fetch_assoc(mysqlQuery("select * from custom_package_master where package_id ='$sq_quotation[package_id]'"));
$quotation_date = $sq_quotation['quotation_date'];
$yr = explode("-", $quotation_date);
$year = $yr[0];

$sq_cost1 = mysqlQuery("select * from package_tour_quotation_costing_entries where quotation_id = '$sq_quotation[quotation_id]'");
while($sq_cost = mysqli_fetch_assoc($sq_cost1)){

	$basic_cost = $sq_cost['basic_amount'];
	$service_charge = $sq_cost['service_charge'];
	$tour_cost= $basic_cost + $service_charge;
	$service_tax_amount = 0;
	$tax_show = '';
	$bsmValues = json_decode($sq_cost['bsmValues']);
	$name = '';
	if($sq_cost['service_tax_subtotal'] !== 0.00 && ($sq_cost['service_tax_subtotal']) !== ''){
	$service_tax_subtotal1 = explode(',',$sq_cost['service_tax_subtotal']);
	for($i=0;$i<sizeof($service_tax_subtotal1);$i++){
		$service_tax = explode(':',$service_tax_subtotal1[$i]);
		$service_tax_amount +=  $service_tax[2];
		$name .= $service_tax[0] . ' ';
		$percent = $service_tax[1];
	}
	}
	if($bsmValues[0]->service != ''){   //inclusive service charge
	$newBasic = $tour_cost + $service_tax_amount;
	$tax_show = '';
	}
	else{
	$tax_show =  $name . $percent. ($service_tax_amount);
	$newBasic = $tour_cost;
	}
	
	////////////Basic Amount Rules
	if($bsmValues[0]->basic != ''){ //inclusive markup
	$newBasic = $tour_cost + $service_tax_amount;
	$tax_show = '';
	}
}

$sq_login = mysqli_fetch_assoc(mysqlQuery("select * from roles where id='$sq_quotation[login_id]'"));
$sq_emp_info = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id='$sq_login[emp_id]'"));
if($sq_emp_info['first_name']==''){
	$emp_name = 'Admin';
}
else{
	$emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];
}
$cust_user_name = '';
if($sq_quotation['user_id'] != 0){ 
	$row_user = mysqli_fetch_assoc(mysqlQuery("Select name from customer_users where user_id ='$sq_quotation[user_id]'"));
	$cust_user_name = ' ('.$row_user['name'].')';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Online Booking</title>	

	<?php admin_header_scripts(); ?>

</head>
 
<input type="hidden" id="base_url" name="base_url" value="<?= BASE_URL ?>">

<?= begin_panel('Quotation View') ?>	

<div class="container">

<div class="main_block mg_tp_30">
</div>
<h3 class="editor_title main_block">Enquiry Details</h3>
<div class="app_panel_content Filter-panel">
	<div class="row">
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Package Name</label> : <?= $sq_package['package_name'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Tour Name</label> : <?= $sq_quotation['tour_name'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>From Date</label> : <?= date('d/m/Y', strtotime($sq_quotation['from_date'])) ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>To Date</label> : <?= date('d/m/Y', strtotime($sq_quotation['to_date'])) ?> </div>
	</div>
	<div class="row">
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Total Night(s)</label> : <?= $sq_quotation['total_days'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Customer Name</label> : <?= $sq_quotation['customer_name'].$cust_user_name ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Adults</label> : <?= $sq_quotation['total_adult'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Infants</label> : <?= $sq_quotation['total_infant'] ?> </div>
	</div>
	<div class="row">
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Child Without Bed</label> : <?= $sq_quotation['children_without_bed'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Child With Bed</label> : <?= $sq_quotation['children_with_bed'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Total Guest</label> : <?= $sq_quotation['total_passangers'] ?> </div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label>Quotation Date</label> : <?= date('d/m/Y', strtotime($sq_quotation['quotation_date'])) ?> </div>
	</div>
	<div class="row">
		<?php if($sq_quotation['discount']!=0){ ?><div class="col-md-3 mg_bt_10_xs" style="border-right: 1px solid #ddd;"> <div class="highlighted_cost"><label>Discount Cost</label> : <?= number_format($sq_quotation['discount'],2) ?> </div></div><?php }?>
		<div class="col-md-3" style="border-right: 1px solid #ddd;"> <div class="highlighted_cost"><label>Created By</label> : <?= $emp_name ?> </div></div>
		<div class="col-md-3 mg_bt_10" style="border-right: 1px solid #ddd;"> <label class="highlighted_cost">Quotation ID : <?= get_quotation_id($sq_quotation['quotation_id'],$year) ?> </label></div>
		<?php

        if($sq_quotation['price_str_url']!=""){

        	$newUrl1 = preg_replace('/(\/+)/','/',$sq_quotation['price_str_url']); 
        ?>	
		<div class="col-md-3 mg_bt_10"> <label class="highlighted_cost">Price Structure :<a href="<?php echo $newUrl1; ?>" download title="Download Price Structure">&nbsp;&nbsp;&nbsp;<i class="fa fa-download" aria-hidden="true"></i></a></label></div>

        <?php }

        ?>
	</div>
</div>
<div class="main_block mg_tp_30">
</div>
<!-- Flight-->
<?php $sq_f_count = mysqli_num_rows(mysqlQuery("select * from package_tour_quotation_plane_entries where quotation_id='$quotation_id'"));
if($sq_f_count != '0'){
?>
<div class="row mg_tp_30">
<div class="col-md-12">
<h3 class="editor_title">Flight Details</h3>
	<div class="table-responsive">
	<table class="table table-hover table-bordered no-marg">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Sector_From</th>
			<th>Sector_To</th>
			<th>Airline_Name</th>
			<th>Class</th>
			<th>Departure_Date_Time</th>
			<th>Arrival_Date_Time</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_train = mysqlQuery("select * from package_tour_quotation_plane_entries where quotation_id='$quotation_id'");
		while($row_train = mysqli_fetch_assoc($sq_train))
		{
			$sq_airline = mysqli_fetch_assoc(mysqlQuery("select * from airline_master where airline_id='$row_train[airline_name]'"));
			$airline = ($row_train['airline_name'] != '') ? $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')' : 'NA';
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $row_train['from_location'] ?></td>
				<td><?= $row_train['to_location'] ?></td>
				<td><?= $airline ?></td>
				<td><?php echo ($row_train['class']!='')?$row_train['class']:'NA'; ?></td>
				<td><?= get_datetime_user($row_train['dapart_time']) ?></td>
				<td><?= get_datetime_user($row_train['arraval_time']) ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
	</div>
	</div>
	</div>
<?php } ?>

<!-- Train-->
<?php $sq_t_count = mysqli_num_rows(mysqlQuery("select * from package_tour_quotation_train_entries where quotation_id='$quotation_id'"));
if($sq_t_count != '0'){
?>
<div class="row mg_tp_30">
<div class="col-md-12">
<h3 class="editor_title">Train Details</h3>
	<div class="table-responsive">
	<table class="table table-hover table-bordered no-marg">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Location_From</th>
			<th>Location_To</th>
			<th>Class</th>
			<th>Departure_Date_Time</th>
			<th>Arrival_Date_Time</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_train = mysqlQuery("select * from package_tour_quotation_train_entries where quotation_id='$quotation_id'");
		while($row_train = mysqli_fetch_assoc($sq_train))
		{
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $row_train['from_location'] ?></td>
				<td><?= $row_train['to_location'] ?></td>
				<td><?php echo ($row_train['class']!='')?$row_train['class']:'NA'; ?></td>
				<td><?= get_datetime_user($row_train['departure_date']) ?></td>
				<td><?= get_datetime_user($row_train['arrival_date']) ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
	</div>
	</div>
	</div>
<?php } ?>


<?php $sq_h_count = mysqli_num_rows(mysqlQuery("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id'"));
if($sq_h_count != '0'){
?>
<!-- Hotel -->
<div class="row mg_tp_30">
<div class="col-md-12">
<h3 class="editor_title">Hotel Details</h3>
	<div class="table-responsive">
	<table class="table table-hover table-bordered no-marg">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Package_type</th>
			<th>City</th>
			<th>Hotel_Name</th>
			<th>Hotel_Category</th>
			<th>Check_IN</th>
			<th>Check_OUT</th>
			<th>Nights</th>
			<th>Rooms</th>
			<th>Extra_Bed</th>
			<th>Meal_plan</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_hotel = mysqlQuery("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id'");
		while($row_hotel = mysqli_fetch_assoc($sq_hotel))
		{
			$hotel_name = mysqli_fetch_assoc(mysqlQuery("select * from hotel_master where hotel_id='$row_hotel[hotel_name]'"));
			$city_name = mysqli_fetch_assoc(mysqlQuery("select * from city_master where city_id='$row_hotel[city_name]'"));
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $row_hotel['package_type'] ?></td>
				<td><?= $city_name['city_name'] ?></td>
				<td><?= $hotel_name['hotel_name'].$similar_text ?></td>
				<td><?= $row_hotel['hotel_type'] ?></td>
				<td><?= get_date_user($row_hotel['check_in']) ?></td>
				<td><?= get_date_user($row_hotel['check_out']) ?></td>
				<td><?= $row_hotel['total_days'] ?></td>
				<td><?= $row_hotel['total_rooms'] ?></td>
				<td><?= $row_hotel['extra_bed'] ?></td>
				<td><?= $row_hotel['meal_plan'] ?></td>
			</tr>
			<?php
		}	
		?>
	</tbody>
</table>
</div>
</div>
</div>
<?php } ?>

<?php $sq_t_count = mysqli_num_rows(mysqlQuery("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'"));
if($sq_t_count != '0'){
?>
<!-- Transport -->
<div class="row mg_tp_30">
<div class="col-md-12">
<h3 class="editor_title">Transport Details</h3>
	<div class="table-responsive">
	<table class="table table-hover table-bordered no-marg">

	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Vehicle_Name</th>
			<th>Start_Date</th>
			<th>End_Date</th>
			<th>Pickup_Location</th>
			<th>Drop_Location</th>
			<th>Service_duration</th>
			<th>Total_vehicles</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_hotel = mysqlQuery("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'");
		while($row_hotel = mysqli_fetch_assoc($sq_hotel))
		{
			$transport_name = mysqli_fetch_assoc(mysqlQuery("select * from b2b_transfer_master where entry_id='$row_hotel[vehicle_name]'"));
			// Pickup
			if($row_hotel['pickup_type'] == 'city'){
				$row = mysqli_fetch_assoc(mysqlQuery("select city_id,city_name from city_master where city_id='$row_hotel[pickup]'"));
				$pickup = $row['city_name'];
			}
			else if($row_hotel['pickup_type'] == 'hotel'){
				$row = mysqli_fetch_assoc(mysqlQuery("select hotel_id,hotel_name from hotel_master where hotel_id='$row_hotel[pickup]'"));
				$pickup = $row['hotel_name'];
			}
			else{
				$row = mysqli_fetch_assoc(mysqlQuery("select airport_name, airport_code, airport_id from airport_master where airport_id='$row_hotel[pickup]'"));
				$airport_nam = clean($row['airport_name']);
				$airport_code = clean($row['airport_code']);
				$pickup = $airport_nam." (".$airport_code.")";
			}
			//Drop-off
			if($row_hotel['drop_type'] == 'city'){
				$row = mysqli_fetch_assoc(mysqlQuery("select city_id,city_name from city_master where city_id='$row_hotel[drop]'"));
				$drop = $row['city_name'];
			}
			else if($row_hotel['drop_type'] == 'hotel'){
				$row = mysqli_fetch_assoc(mysqlQuery("select hotel_id,hotel_name from hotel_master where hotel_id='$row_hotel[drop]'"));
				$drop = $row['hotel_name'];
			}
			else{
				$row = mysqli_fetch_assoc(mysqlQuery("select airport_name, airport_code, airport_id from airport_master where airport_id='$row_hotel[drop]'"));
				$airport_nam = clean($row['airport_name']);
				$airport_code = clean($row['airport_code']);
				$drop = $airport_nam." (".$airport_code.")";
			}
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $transport_name['vehicle_name'].$similar_text ?></td>
				<td><?= date('d-m-Y', strtotime($row_hotel['start_date'])) ?></td>
				<td><?= date('d-m-Y', strtotime($row_hotel['end_date'])) ?></td>
				<td><?= $pickup ?></td>
				<td><?= $drop ?></td>
				<td><?= $row_hotel['service_duration'] ?></td>
				<td><?= $row_hotel['vehicle_count'] ?></td>
			</tr>
			<?php
		}	
		?>
	</tbody>
</table>
	</div>
	</div>
	</div>
<?php } ?>

<?php $sq_e_count = mysqli_num_rows(mysqlQuery("select * from package_tour_quotation_excursion_entries where quotation_id='$quotation_id'"));
if($sq_e_count != '0'){
?>
<div class="row mg_tp_30">
<div class="col-md-12">
<h3 class="editor_title">Activity Details</h3>
	<div class="table-responsive">
	<table class="table table-hover table-bordered no-marg">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Activity_Date</th>
			<th>City_Name</th>
			<th>Activity_Name</th>
			<th>Transfer_Option</th>
			<th>Adult(s)</th>
			<th>Child_with_Bed</th>
			<th>Child_without_Bed</th>
			<th>Infant(s)</th>
			<th>Vehicle(s)</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$count = 0;
		$sq_ex = mysqlQuery("select * from package_tour_quotation_excursion_entries where quotation_id='$quotation_id'");
		while($row_ex = mysqli_fetch_assoc($sq_ex)){
			
			$sq_city = mysqli_fetch_assoc(mysqlQuery("select * from city_master where city_id='$row_ex[city_name]'"));
			$sq_ex_name = mysqli_fetch_assoc(mysqlQuery("select * from excursion_master_tariff where entry_id='$row_ex[excursion_name]'"));
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= get_datetime_user($row_ex['exc_date']) ?></td>
				<td><?= $sq_city['city_name'] ?></td>
				<td><?= $sq_ex_name['excursion_name'] ?></td>
				<td><?= $row_ex['transfer_option'] ?></td>
				<td><?= $row_ex['adult'] ?></td>
				<td><?= $row_ex['chwb'] ?></td>
				<td><?= $row_ex['chwob'] ?></td>
				<td><?= $row_ex['infant'] ?></td>
				<td><?= $row_ex['vehicles'] ?></td>
			</tr>
			<?php
		}	
		?>
	</tbody>
</table>
	</div>
	</div>
	</div>
<?php } ?>

<?php $sq_c_count = mysqli_num_rows(mysqlQuery("select * from package_tour_quotation_cruise_entries where quotation_id='$quotation_id'"));
if($sq_c_count != '0'){
?>
<!-- Cruise -->
<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Cruise Details</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Departure_Date_Time</th>
			<th>Arrival_Date_Time</th>
			<th>Route</th>
			<th>Cabin</th>
			<th>Sharing</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_train = mysqlQuery("select * from package_tour_quotation_cruise_entries where quotation_id='$quotation_id'");
		while($row_train = mysqli_fetch_assoc($sq_train))
		{
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= get_datetime_user($row_train['dept_datetime']) ?></td>
				<td><?= get_datetime_user($row_train['arrival_datetime']) ?></td>
				<td><?= $row_train['route'] ?></td>
				<td><?= ($row_train['cabin']) ?></td>
				<td><?= ($row_train['sharing']) ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
<?php } ?>

<div class="main_block mg_tp_30"></div>
<h3 class="editor_title main_block">Itinerary Details</h3>
<table class="table table-bordered">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th style="min-width:200px ;">Date</th>
			<th>Special_Attraction</th>
			<th>Day-wise_Program</th>
			<th>Overnight_Stay</th>
			<th>Meal_Plan</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$i = 0;
		$dates =(array) get_dates_for_package_itineary($_GET['quotation_id']);
		$sq_package_program = mysqlQuery("select * from package_quotation_program where quotation_id='$quotation_id'");
		
		while($row_itinarary = mysqli_fetch_assoc($sq_package_program)){
			$date_format = isset($dates[$i]) ? $dates[$i] : 'NA';;
			$i++;
			?>
			<tr>
				<td><?= ++$count ?></td>
				<td><?= $date_format ?></td>
				<td><?= $row_itinarary['attraction'] ?></td>
				<td><pre class="real_text"><?= $row_itinarary['day_wise_program'] ?></pre></td>
				<td><?= $row_itinarary['stay'] ?></td>
				<td><?= $row_itinarary['meal_plan'] ?></td>
			</tr>
			<?php
		}	
		?>
	</tbody>
</table>

<?php
$count = 0;
?>
<div class="row mg_tp_30">
<div class="col-md-12">
<h3 class="editor_title">Costing Details</h3>
	<div class="table-responsive">
	<table class="table table-hover table-bordered no-marg">
		<thead>
		<?php if($sq_quotation['costing_type'] == 1){ ?>
			<tr class="table-heading-row">
				<?php if($role != 'B2b'){		 ?>
				<th>Package_type</th>
				<th>Hotel</th>
				<th>Transport</th>
				<th>Activity</th>
				<?php }else{ ?>
				<th>Tour</th>
			    <?php } ?>
				<th>Basic_Amount</th>
				<th>Service_Charge</th>
				<th>Discount_In</th>
				<th>Discount</th>
				<th>Tax</th>
				<th>Tcs</th>
				<th>Total_Tour</th>
				<th>Train</th>
				<th>Flight</th>
				<th>Cruise</th>
				<th>TOTAL_TRAVEL</th>
				<th>Visa</th>
				<th>Guide</th>
				<th>Misc</th>
				<th>Net_total</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sq_cost1 = mysqlQuery("select * from package_tour_quotation_costing_entries where quotation_id = '$sq_quotation[quotation_id]'");
			while($sq_cost = mysqli_fetch_assoc($sq_cost1)){
				$tour_cost = $sq_cost['tour_cost'] + $sq_cost['transport_cost'] + $sq_cost['excursion_cost'];
				$basic_costing = $sq_cost['tour_cost'] + $sq_cost['transport_cost'] +  $sq_cost['excursion_cost'];
				?>
				<?php
				$bsmValues=json_decode($sq_cost['bsmValues'],true);
			
				?>
				<tr>
					<td><?= $sq_cost['package_type'] ?></td>
					<?php if($role != 'B2b'){		 ?>
					<td><?= number_format($sq_cost['tour_cost'],2) ?></td>
					<td><?= number_format($sq_cost['transport_cost'],2) ?></td>
					<td><?= number_format($sq_cost['excursion_cost'],2) ?></td> 
					<?php }else{ ?>
					<td><?= number_format($tour_cost,2) ?></td>
					<?php } ?>
					<td><?= number_format($basic_costing,2) ?></td>
					<td><?= number_format($sq_cost['service_charge'],2) ?></td>
					<td><?= $sq_cost['discount_in'] ?></td>
					<td><?= number_format($sq_cost['discount'],2) ?></td>
					<td><?= $sq_cost['service_tax_subtotal'] ?></td>
					<td>Tcs:(<?=$bsmValues[0]['tcsper']?>%):<?=$bsmValues[0]['tcsvalue']?></td>
					<td><b><?= number_format($sq_cost['total_tour_cost'],2) ?></b></td>
					<td><?= number_format($sq_quotation['train_cost'],2)  ?></td>
					<td><?= number_format($sq_quotation['flight_cost'],2)  ?></td>
					<td><?= number_format($sq_quotation['cruise_cost'],2)  ?></td>
					<td><b><?= number_format($sq_quotation['train_cost'] + $sq_quotation['flight_cost'] + $sq_quotation['cruise_cost'],2) ?></b></td>
					<td><b><?= number_format($sq_quotation['visa_cost'],2)  ?></b></td>
					<td><b><?= number_format($sq_quotation['guide_cost'],2)  ?></b></td>
					<td><b><?= number_format($sq_quotation['misc_cost'],2)  ?></b></td>
					<td><b><?= number_format($sq_cost['total_tour_cost']+$sq_quotation['visa_cost']+$sq_quotation['guide_cost']+$sq_quotation['misc_cost']+$sq_quotation['train_cost'] + $sq_quotation['flight_cost'] + $sq_quotation['cruise_cost'],2)  ?></b></td>
				</tr>
				<?php } ?>
		</tbody>
		<?php }else{?>
			<tr class="table-heading-row">
				<th>Package_Type</th>
				<th>Adult</th>
				<th>CWB</th>
				<th>CWOB</th>
				<th>Infant</th>
				<th>Service_Charge</th>
				<th>Tax</th>
				<th>Tcs</th>
				<th>Flight(A/C/I)</th>
				<th>Train(A/C/I)</th>
				<th>Cruise(A/C/I)</th>
				<th>Visa</th>
				<th>Guide</th>
				<th>Misc</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sq_cost1 = mysqlQuery("select * from package_tour_quotation_costing_entries where quotation_id = '$sq_quotation[quotation_id]'");
			while($sq_cost = mysqli_fetch_assoc($sq_cost1)){
				?>

<?php
				$bsmValues=json_decode($sq_cost['bsmValues'],true);
			
				?>
			<tr>
				<td><?= $sq_cost['package_type'] ?></td>
				<td><?= number_format($sq_cost['adult_cost'],2) ?></td>
				<td><?= number_format($sq_cost['child_with'],2)  ?></td>
				<td><?= number_format($sq_cost['child_without'],2)  ?></td>
				<td><?= number_format($sq_cost['infant_cost'],2)  ?></td>
				<td><?= number_format($sq_cost['service_charge'],2) ?></td>
		
				<td><?= ($sq_cost['service_tax_subtotal']!='')?$sq_cost['service_tax_subtotal']:'0.00' ?></td>
				<td>Tcs:(<?=$bsmValues[0]['tcsper']?>%):<?=$bsmValues[0]['tcsvalue']?></td>
				<td><?= number_format($sq_quotation['flight_acost'],2).'/'.number_format($sq_quotation['flight_ccost'],2).'/'.number_format($sq_quotation['flight_icost'],2)  ?></td>
				<td><?= number_format($sq_quotation['train_acost'],2).'/'.number_format($sq_quotation['train_ccost'],2).'/'.number_format($sq_quotation['train_icost'],2)  ?></td>
				<td><?= number_format($sq_quotation['cruise_acost'],2).'/'.number_format($sq_quotation['cruise_ccost'],2).'/'.number_format($sq_quotation['cruise_icost'],2)  ?></td>
				<td><?= number_format($sq_quotation['visa_cost'],2)  ?></td>
				<td><?= number_format($sq_quotation['guide_cost'],2)  ?></td>
				<td><?= number_format($sq_quotation['misc_cost'],2)  ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<?php } ?>
	</table>
	</div>
</div>
</div>


<?php if($sq_quotation['inclusions'] != ''){ ?>
<div class="row mg_tp_30">
	<div class="col-md-12">
		<h3 class="editor_title">Inclusions</h3>
		<div class="panel panel-default panel-body app_panel_style">
			<?= $sq_quotation['inclusions'] ?>
		</div>
	</div>
</div>
<?php }
if($sq_quotation['exclusions'] != ''){ ?>
<div class="row mg_tp_10">
	<div class="col-md-12">
		<h3 class="editor_title">Exclusions</h3>
		<div class="panel panel-default panel-body app_panel_style">
			<?= $sq_quotation['exclusions'] ?>
		</div>
	</div>
</div>
<?php }
if($sq_package['note'] != ''){ ?>
<div class="row mg_tp_10">
	<div class="col-md-12">
		<h3 class="editor_title">Note</h3>
		<div class="panel panel-default panel-body app_panel_style">
			<?= $sq_package['note'] ?>
		</div>
	</div>
</div>
<?php }
if($sq_quotation['other_desc'] != ''){ ?>
<div class="row mg_tp_10">
	<div class="col-md-12">
		<h3 class="editor_title">Miscellaneous Description</h3>
		<div class="panel panel-default panel-body app_panel_style">
			<?= $sq_quotation['other_desc'] ?>
		</div>
	</div>
</div>
<?php } ?>
<div class="row mg_tp_10 hidden">
	<div class="col-md-12">
		<h3 class="editor_title">Terms & Conditions</h3>
		<div class="panel panel-default panel-body app_panel_style">
			<?php
			$sq_query = mysqli_fetch_assoc(mysqlQuery("select * from terms_and_conditions where type='Package Quotation' and active_flag='Active'")); 
			echo $sq_query['terms_and_conditions'] ?>
		</div>
	</div>
</div>


</div>
<?= end_panel() ?>

<?php
/*======******Footer******=======*/
include_once('../../../layouts/fullwidth_app_footer.php');
?>