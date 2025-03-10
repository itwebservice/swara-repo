<?php
include "../../../model/model.php";
$enquiry_type = isset($_POST['enquiry_type']) ? $_POST['enquiry_type'] : 'Package Booking';

if(isset($_POST['enquiry_id'])){

	$enquiry_id = $_POST['enquiry_id'];
	$sq_enquiry = mysqli_fetch_assoc(mysqlQuery("select * from enquiry_master where enquiry_id='$enquiry_id'"));
	$enquiry_content = $sq_enquiry['enquiry_content'];
	$enquiry_content_arr1 = json_decode($enquiry_content, true);	
}
if($enquiry_type=="Group Booking" || $enquiry_type=="Package Booking"){

	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){

			if($enquiry_content_arr2['name']=="tour_name"){ $sq_c['tour_name'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_members"){ $sq_c['total_members'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_adult"){ $sq_c['total_adult'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_infant"){ $sq_c['total_infant'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_single_person"){ $sq_c['total_single_person'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="hotel_type"){ $sq_c['hotel_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="children_without_bed"){ $sq_c['children_without_bed'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="children_with_bed"){ $sq_c['children_with_bed'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="travel_from_date"){ $sq_c['travel_from_date'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="travel_to_date"){ $sq_c['travel_to_date'] = $enquiry_content_arr2['value']; }
		}
	}
	else{
		$sq_c['tour_name'] = $sq_c['budget'] = $sq_c['total_members'] = $sq_c['total_adult'] = $sq_c['total_infant'] = $sq_c['children_without_bed'] = $sq_c['children_with_bed'] = $sq_c['travel_from_date'] = $sq_c['travel_to_date']  = $sq_c['hotel_type'] = "";
		$sq_c['total_single_person'] = 0;
	}
	$total_single_person = isset($sq_c['total_single_person']) ? intval($sq_c['total_single_person']) : 0;
?>

<div class="row">

	<div class="col-md-4 col-sm-6 mg_bt_10">
		<input type="text" id="tour_name" name="tour_name" onchange="validate_spaces(this.id)" placeholder="*Interested Tour" title="Interested Tour" value="<?= $sq_c['tour_name'] ?>" class="form-control">
	</div>

	<div class="col-md-4 col-sm-6 mg_bt_10">

		<input type="text" id="travel_from_date" name="travel_from_date"  title="Travel From Date" placeholder="*Travel From Date" value="<?= $sq_c['travel_from_date'] ?>" class="form-control" onchange="get_to_date(this.id,'travel_to_date')">

	</div>

	<div class="col-md-4 col-sm-6 mg_bt_10">
		<input type="text" id="travel_to_date" name="travel_to_date"  title="Travel To Date" placeholder="*Travel To Date" value="<?= $sq_c['travel_to_date'] ?>" onchange="validate_validDate('travel_from_date',this.id)" class="form-control">
	</div>	  

	<div class="col-md-4 col-sm-6 mg_bt_10">
	    <input type="text" id="budget" name="budget" placeholder="Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">
	</div>

	<div class="col-md-4 col-sm-6 mg_bt_10">

        <input type="number" id="total_adult" name="total_adult" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Adults" title="Total Adults" value="<?= $sq_c['total_adult'] ?>" class="form-control">            

    </div>

	<div class="col-md-4 col-sm-6 mg_bt_10">

	    <input type="number" id="children_with_bed" name="children_with_bed" onchange="validate_balance(this.id);total_members_calculate()" placeholder="*Child With Bed" title="Child With Bed" value="<?= $sq_c['children_with_bed'] ?>" class="form-control">

	</div> 
	<div class="col-md-4 col-sm-6 mg_bt_10">
	    <input type="number" id="children_without_bed" name="children_without_bed" onchange="validate_balance(this.id);total_members_calculate()" placeholder="*Child Without Bed" title="Child Without Bed" value="<?= $sq_c['children_without_bed'] ?>" class="form-control">
	</div>

	<div class="col-md-4 col-sm-6 mg_bt_10">

        <input type="number" id="total_infant" name="total_infant" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Infant" title="Total Infant" value="<?= $sq_c['total_infant'] ?>" class="form-control" >            

    </div>
	<div class="col-md-4 col-sm-6 mg_bt_10">

        <input type="number" id="total_single_person" name="total_single_person" onchange="validate_balance(this.id); total_members_calculate()" placeholder="Total Single Person" title="Total Single Person" value="<?= $total_single_person ?>" class="form-control" >            

    </div>  

    <div class="col-md-4 col-sm-6 mg_bt_10">

	    <input type="text" id="total_members" name="total_members" onchange="number_validate(this.id)" placeholder="Total Passenger" title="Total Passenger" value="<?= $sq_c['total_members'] ?>" class="form-control" readonly>

	</div>
	<div class="col-md-4 col-sm-6 mg_bt_10">
			<select name="hotel_type" id="hotel_type" title="Hotel Category" class="form-control">
				<?php 
				if($sq_c['hotel_type']!=""){
					?>
					<option value="<?= $sq_c['hotel_type'] ?>"><?= $sq_c['hotel_type'] ?></option>
					<?php } ?>
					<option value="">*Hotel Category</option>
					<option value="1-Star">1-Star</option>
					<option value="2-Star">2-Star</option>
					<option value="3-Star">3-Star</option>
					<option value="4-Star">4-Star</option>
					<option value="5-Star">5-Star</option>
					<option value="Economy">Economy</option>
					<option value="Resort">Resort</option>
					<option value="Other">Other</option>
			</select>
	</div>

	

</div>

<script>

$('#travel_from_date, #travel_to_date').datetimepicker({ timepicker:false, format: 'd-m-Y' });

function total_members_calculate()
{
	var total_adult = $('#total_adult').val();
	var cwb = $('#children_with_bed').val();
	var cwob = $('#children_without_bed').val();
	var total_infant = $('#total_infant').val();
	var total_single_person = $('#total_single_person').val();

	if(total_adult==""){ total_adult = 0; }
	if(total_infant==""){ total_infant = 0; }
	if(total_single_person==""){ total_single_person = 0; }
	if(cwb==""){ cwb = 0; }
	if(cwob==""){ cwob = 0; }

	var total_members = parseFloat(total_adult) + parseFloat(total_infant) + parseFloat(cwb) + parseFloat(cwob) + parseFloat(total_single_person);
	$('#total_members').val(total_members);
}
</script>
<?php

}





if($enquiry_type=="Visa")
{
	if(isset($_POST['enquiry_id']))
	{
		foreach($enquiry_content_arr1 as $enquiry_content_arr2)
		{
			if($enquiry_content_arr2['name']=="visa_country_name"){ $sq_c['visa_country_name']= $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="visa_type"){ $sq_c['visa_type'] = $enquiry_content_arr2['value'];	}
			if($enquiry_content_arr2['name']=="total_adult"){ $sq_c['total_adult'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_children"){ $sq_c['total_children'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_infant"){ $sq_c['total_infant'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_members"){	$sq_c['total_members'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }
		}
	}
	else
	{
		$sq_c['visa_country_name'] = $sq_c['budget'] = $sq_c['visa_type'] = $sq_c['total_members'] = $sq_c['total_adult'] = $sq_c['total_children'] = $sq_c['total_infant'] = $sq_c['total_members'] = "";
	}
?>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<select name="visa_country_name" id="visa_country_name" class="form-control" title="Visa Country Name" style="width:100%">
		<?php if($sq_c['visa_country_name'] != ''){ ?>
			<option value="<?= $sq_c['visa_country_name'] ?>"><?= $sq_c['visa_country_name'] ?></option>
		<?php } ?>
			<option value="">*Visa Country Name</option>
			<?php
			$sq_country = mysqlQuery("select country_name from country_list_master");
			while ($row_country = mysqli_fetch_assoc($sq_country)) {
			?>
				<option value="<?= $row_country['country_name'] ?>"><?= $row_country['country_name'] ?></option>
			<?php
			}
			?>
		</select>

	</div>

	<div class="col-md-4">

		<select name="visa_type" id="visa_type" title="Visa Type">

			<?php if($sq_c['visa_type']!=""){?>

			 <option value="<?= $sq_c['visa_type'] ?>"><?= $sq_c['visa_type'] ?></option> <?php } ?>

            <option value="">Visa Type</option>

            <?php 

            $sq_visa_type = mysqlQuery("select * from visa_type_master");

            while($row_visa_type = mysqli_fetch_assoc($sq_visa_type)){

                ?>

                <option value="<?= $row_visa_type['visa_type'] ?>"><?= $row_visa_type['visa_type'] ?></option>

                <?php

            	}
           ?>

        </select>

	</div>

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_adult" name="total_adult" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Adults" title="Total Adults" value="<?= $sq_c['total_adult'] ?>" class="form-control">            

    </div>    

</div>

<div class="row mg_bt_10">

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_children" name="total_children" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Children" title="Total Children" value="<?= $sq_c['total_children'] ?>" class="form-control">            

    </div>  

    <div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_infant" name="total_infant" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Infant" title="Total Infant" value="<?= $sq_c['total_infant'] ?>" class="form-control">            

    </div>    

	<div class="col-md-4">

		<input type="text" id="total_members" name="total_members" placeholder="Total Passenger" title="Total Passenger" value="<?= $sq_c['total_members'] ?>" class="form-control" readonly>

	</div>
	<div class="col-md-4 col-sm-6 mg_tp_10">

	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

</div>

<script>

$('#travel_from_date, #travel_to_date').datetimepicker({ timepicker:false, format: 'd-m-Y' });
$('#visa_country_name').select2();

function total_members_calculate()

{
	var total_adult = $('#total_adult').val();	
	var total_children = $('#total_children').val();
	var total_infant = $('#total_infant').val();

	if(total_adult==""){ total_adult = 0; }
	if(total_children==""){ total_children = 0; }
	if(total_infant==""){ total_infant = 0; }
	var total_members = parseFloat(total_adult) + parseFloat(total_children) + parseFloat(total_infant);

	$('#total_members').val(total_members);
}

</script>

<?php

}



if($enquiry_type=="Flight Ticket"){
	$sq_f = array();
	if(isset($_POST['enquiry_id'])){
		// echo $iter;
		foreach($enquiry_content_arr1 as $values){
				$sq_c['travel_datetime'] = $values['travel_datetime']; 
				$sq_c['sector_from'] = $values['sector_from']; 
				$sq_c['sector_to'] = $values['sector_to'];
				$sq_c['preffered_airline'] = $values['preffered_airline']; 
				$sq_c['class_type'] = $values['class_type']; 
				$sq_c['total_seats'] = isset($values['total_seats']) ? $values['total_seats'] : 0;
				$sq_c['total_adults_flight'] = $values['total_adults_flight']; 
				$sq_c['total_child_flight'] = $values['total_child_flight']; 
				$sq_c['total_infant_flight'] = $values['total_infant_flight']; 
				$sq_c['from_city_id_flight'] = $values['from_city_id_flight'];
				$sq_c['to_city_id_flight'] = $values['to_city_id_flight'];
				$sq_c['budget'] = $values['budget']; 
				array_push($sq_f, $sq_c);
			}
	}

	else{

		$sq_c['budget'] = $sq_c['sector_from'] = $sq_c['sector_to'] = $sq_c['preffered_airline'] = $sq_c['class_type'] = $sq_c['total_adults_flight'] = $sq_c['total_child_flight'] = $sq_c['total_infant_flight'] = $sq_c['total_seats'] = $sq_c['to_city_id_flight'] = $sq_c['from_city_id_flight'] = "";
		$sq_c['travel_datetime'] = date('d-m-Y');
		array_push($sq_f, $sq_c);
	}



?>
<input type="hidden" id="flight_table_rows" name="flight_table_rows" value='1'>
<div class="row" style="margin-top: 5px">
	<div class="col-md-6">
		<button type="button" class="btn btn-excel btn-sm" title="Add Airport/Airline" onclick="airport_airline_save_modal()"><i class="fa fa-plus"></i></button>
	</div>
	<div class="col-md-6 text-right">
    	<button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_enquiry_flight');event_airport('tbl_enquiry_flight',3,4)" title="Add Row"><i class="fa fa-plus"></i></button>
    	<button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_enquiry_flight');" title="Delete Row">><i class="fa fa-trash"></i></button>
	</div>
</div>
<div class="row mg_bt_10">
	<div class="col-md-12">
		<div class="table-responsive">
            <table id="tbl_enquiry_flight" class="table table-bordered table-hover table-striped" style="width: 100%;">
			<?php
				$count = 0;
				foreach($sq_f as $values){
			?>
				<tr>
					<td><input id="check-btn-enq-<?= $count ?>" type="checkbox" checked ></td>
					<td><input maxlength="15" type="text" name="username"  value="<?= $count+1 ?>" placeholder="Sr. No." disabled/></td>
					<td><input type="text" id="travel_datetime-<?= $count ?>" style="width:150px" name="travel_datetime" placeholder="*Travel Date/Time" title="Travel Date/Time" value="<?= get_datetime_user($values['travel_datetime']) ?>" class="form-control"></td>
					<td><input type="text" style="width:365px" id="from_sector-<?= $count ?>" name="from_sector" placeholder="*Sector From" title="Sector From" value="<?= $values['sector_from'] ?>" class="form-control"></td>
					<td><input type="text" style="width:365px" id="to_sector-<?= $count ?>" name="to_sector" placeholder="*Sector To" title="Sector To" value="<?= $values['sector_to'] ?>" class="form-control"></td>
					<td><select id="preffered_airline-<?= $count ?>" name="preffered_airline" class="app_select2" style="width:130px" name="" title="Preferred Airline" class="form-control">
					<?php
						if($values['preffered_airline'] != ''){
							$sq_airline_show = mysqli_fetch_assoc(mysqlQuery("SELECT airline_name, airline_code from airline_master where airline_id = ".$values['preffered_airline']));
							
							echo '<option value="'.$values['preffered_airline'].'">'.$sq_airline_show['airline_name'] .'('.$sq_airline_show['airline_code'] .') </option>';
						}
					?>
						<option value="">Select Airline</option>
						<?php
							$sq_airline = mysqlQuery("select * from airline_master where active_flag!='Inactive' order by airline_name asc");
							while($row = mysqli_fetch_assoc($sq_airline))
								echo "<option value=".$row['airline_id'].">".$row['airline_name']." (".$row['airline_code'].") </option>";
						?>
						</select>
					</td>
					<td><select name="class_type" id="class_type-<?= $count ?>" title="Class Type" class="form-control" style="width:130px;">
					<?php
						if($values['class_type'] != ''){
							echo '<option value="'.$values['class_type'].'">'.$values['class_type'].'</option>';
						}
						get_flight_class_dropdown();
					?>
							
						</select>
					</td>
					<td><input type="text" id="total_adults_flight-<?= $count ?>" name="total_adults_flight" title="Total Adult(s)" class="form-control" style="width:110px" value="<?= $values['total_adults_flight'] ?>" placeholder="Total Adult(s)">
					</td>
					<td><input type="text" id="total_child_flight-<?= $count ?>" name="total_child_flight" title="Total Child(ren)" class="form-control" style="width:120px" value="<?= $values['total_child_flight'] ?>" placeholder="Total Child(ren)">
					</td>
					<td><input type="text" id="total_infant_flight-<?= $count ?>" name="total_infant_flight" title="Total Infant(s)" class="form-control" style="width:120px" value="<?= $values['total_infant_flight'] ?>" placeholder="Total Infant(s)">
					</td>
					<td><input type="hidden" id="from_city-<?= $count ?>" value="<?= $values['from_city_id_flight'] ?>">
					</td>
					<td><input type="hidden" id="to_city-<?= $count ?>" value="<?= $values['to_city_id_flight'] ?>">
					</td>
					<script>
						$('#travel_datetime-<?= $count++ ?>').datetimepicker({ format:'d-m-Y H:i' });
					</script>
			<?php
				}
			?>
				</tr>
			</table>
		</div>
	</div>
</div>
<div class="row mg_bt_10">
	<div class="col-md-4">
		<input type="text" id="budget" name="budget" class="form-control" placeholder="Budget" title="Budget" value="<?= $sq_f[0]['budget'] ?>">
	</div>
</div>
<script>
$('#travel_datetime-1').datetimepicker({ format:'d-m-Y H:i' });

$('select[name=preffered_airline]').select2();

event_airport('tbl_enquiry_flight',3,4);
function table_count(){
	var table = document.getElementById('tbl_enquiry_flight');
	var rows = table.rows;
	var selected_rows = 0;

	for(var i=0; i<rows.length; i++){
		var row = table.rows[i];
        if(row.cells[0].childNodes[0].checked){
			selected_rows++;
		}
	}
	$('#flight_table_rows').val(selected_rows);
}
</script>

<?php

}



if($enquiry_type=="Train Ticket"){

	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){

			if($enquiry_content_arr2['name']=="travel_datetime"){ $sq_c['travel_datetime'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="location_from"){ $sq_c['location_from'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="location_to"){ $sq_c['location_to'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="class_type"){ $sq_c['class_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="trip_type"){ $sq_c['trip_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_adult"){ $sq_c['total_adult'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_children"){ $sq_c['total_children'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_infant"){ $sq_c['total_infant'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="total_seats"){ $sq_c['total_seats'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="travel_type"){ $sq_c['travel_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }
		}

	}

	else{

		$sq_c['travel_datetime'] = $sq_c['budget'] = $sq_c['location_from'] =  $sq_c['location_to'] =  $sq_c['class_type'] =  $sq_c['trip_type'] = $sq_c['total_adult'] = $sq_c['total_children'] = $sq_c['total_infant'] =  $sq_c['total_seats'] = $sq_c['travel_type'] = "";	

	}

?>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<input type="text" id="travel_datetime" name="travel_datetime"  placeholder="*Travel Date/Time" title="Travel Date/Time" value="<?= get_datetime_user($sq_c['travel_datetime']) ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="location_from" name="location_from" onchange="validate_specialChar(this.id);" placeholder="*Location From" title="Location From" value="<?= $sq_c['location_from'] ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="location_to" name="location_to" onchange="validate_specialChar(this.id);" placeholder="*Location To" title="Location To" value="<?= $sq_c['location_to'] ?>" class="form-control">

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<select name="class_type" id="class_type" title="Class Type" class="form-control">

			<?php 

			if($sq_c['class_type']!=""){

				?>

				<option value="<?= $sq_c['class_type'] ?>"><?= $sq_c['class_type'] ?></option>

				<?php

			}

			?>
			<option value="">Class Type</option>

			<option value="1A">1A</option>

			<option value="2A">2A</option>

			<option value="3A">3A</option>

			<option value="FC">FC</option>

			<option value="CC">CC</option>

			<option value="SL">SL</option>

			<option value="2S">2S</option>

		</select>

	</div>

	<div class="col-md-4">

		<select name="trip_type" id="trip_type" placeholder="*Trip type" class="form-control">

			<?php 

			if($sq_c['trip_type']!=""){

				?>

				<option value="<?= $sq_c['trip_type'] ?>"><?= $sq_c['trip_type'] ?></option>

				<?php

			}

			?>

			<option value="">*Trip Type</option>

			<option value="Single">Single</option>

			<option value="Round">Round</option>

		</select>

	</div>	

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_adult" name="total_adult" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Adults" title="Total Adults" value="<?= $sq_c['total_adult'] ?>" class="form-control">            

    </div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_children" name="total_children" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Children" title="Total Children" value="<?= $sq_c['total_children'] ?>" class="form-control">            

    </div>  

    <div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

        <input type="text" id="total_infant" name="total_infant" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Infant" title="Total Infant" value="<?= $sq_c['total_infant'] ?>" class="form-control">            

    </div>    

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">

		<input type="text" id="total_seats" name="total_seats" onchange="validate_balance(this.id)" placeholder="*Total Seats" title="Total Seats" value="<?= $sq_c['total_seats'] ?>" class="form-control" readonly>

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs " class="form-control">

		<select name="travel_type" id="travel_type" title="Ticket Status">

			<?php 

			if($sq_c['travel_type']!=""){

				?>

				<option value="<?= $sq_c['travel_type'] ?>"><?= $sq_c['travel_type'] ?></option>

				<?php

			}

			?>

			<option value="General">General</option>

			<option value="Tatkal">Tatkal</option>

		</select>

	</div>
	<div class="col-md-4 col-sm-6 mg_bt_10">

	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

</div>

<script>

$('#travel_datetime').datetimepicker({ format:'d-m-Y H:i' });



function total_members_calculate()

{

	var total_adult = $('#total_adult').val();	

	var total_children = $('#total_children').val();

	var total_infant = $('#total_infant').val();



	if(total_adult==""){ total_adult = 0; }

	if(total_children==""){ total_children = 0; }

	if(total_infant==""){ total_infant = 0; }



	var total_seats = parseFloat(total_adult) + parseFloat(total_children) + parseFloat(total_infant);

	$('#total_seats').val(total_seats);

}

</script>

<?php

}



if($enquiry_type=="Hotel"){



	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){
			
			if($enquiry_content_arr2['name']=="hotel_requirements"){ $sq_c['hotel_requirements'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_adult"){ $sq_c['total_adult'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_cwb"){ $sq_c['total_cwb'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_cwob"){ $sq_c['total_cwob'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_infant"){ $sq_c['total_infant'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="total_members"){ $sq_c['total_members'] = $enquiry_content_arr2['value']; }
			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

		}

	}

	else{

		$sq_c['hotel_requirements'] = $sq_c['total_adult'] = $sq_c['total_cwb'] = $sq_c['total_cwob'] = $sq_c['total_infant'] = $sq_c['total_members'] = $sq_c['budget'] =  "";		

	}
?>
<div class="row mg_bt_10">
	<div class="col-md-12 col-sm-6 mg_bt_10_sm_xs">
		<h3 class="editor_title">Hotel Requirements</h3>
		<textarea name="hotel_requirements" id="hotel_requirements" class="feature_editor form_control" cols="30" rows="10"><?= $sq_c['hotel_requirements']?></textarea>
	</div>
</div>
<div class="row mg_bt_10">
	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">
        <input type="text" id="total_adult" name="total_adult" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Adult(s)" title="Total Adult(s)" value="<?= $sq_c['total_adult'] ?>" class="form-control">            
    </div>
	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">
        <input type="text" id="total_cwb" name="total_cwb" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Child With Bed" title="Total Child With Bed" value="<?= $sq_c['total_cwb'] ?>" class="form-control">            
    </div>  
	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">
        <input type="text" id="total_cwob" name="total_cwob" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Child Without Bed" title="Total Child Without Bed" value="<?= $sq_c['total_cwob'] ?>" class="form-control">            
    </div> 
</div>
<div class="row mg_bt_10">
	<div class="col-md-4 col-sm-6 mg_bt_10_sm_xs">
        <input type="text" id="total_infant" name="total_infant" onchange="validate_balance(this.id); total_members_calculate()" placeholder="*Total Infant(s)" title="Total Infant(s)" value="<?= $sq_c['total_infant'] ?>" class="form-control">            
    </div>
	<div class="col-md-4">
		<input type="text" id="total_members" name="total_members" placeholder="Total Guest(s)" title="Total Guest(s)" value="<?= $sq_c['total_members'] ?>" class="form-control" readonly>
	</div>
	<div class="col-md-4 col-sm-6 mg_bt_10">
	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">
	</div>
</div>
<script>
$('#check_in_date, #check_out_date').datetimepicker({ timepicker:true, format:'d-m-Y H:i' });
city_lzloading('#city_id');


function total_members_calculate(){

	var total_adult = $('#total_adult').val();	
	var total_cwb = $('#total_cwb').val();
	var total_cwob = $('#total_cwob').val();
	var total_infant = $('#total_infant').val();

	if(total_adult==""){ total_adult = 0; }
	if(total_cwb==""){ total_cwb = 0; }
	if(total_cwob==""){ total_cwob = 0; }
	if(total_infant==""){ total_infant = 0; }

	var total_members = parseFloat(total_adult) + parseFloat(total_cwb) + parseFloat(total_cwob) + parseFloat(total_infant);
	$('#total_members').val(total_members);

}

</script>

<?php

}



if($enquiry_type=="Car Rental"){



	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){

			if($enquiry_content_arr2['name']=="total_pax"){ $sq_c['total_pax'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="days_of_traveling"){ $sq_c['days_of_traveling'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="traveling_date"){ $sq_c['traveling_date'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="vehicle_type"){ $sq_c['vehicle_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="travel_type"){ $sq_c['travel_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="places_to_visit"){ $sq_c['places_to_visit'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

		}

	}

	else{

		$sq_c['total_pax'] = $sq_c['budget'] = $sq_c['days_of_traveling'] = $sq_c['vehicle_type'] = $sq_c['travel_type'] = $sq_c['places_to_visit'] = "";

		$sq_c['traveling_date'] = date('d-m-Y H:i');

	}



?>

<div class="row mg_bt_10">

	<div class="col-md-4">

	    <input type="text" id="total_pax" name="total_pax" placeholder="*No Of Pax" title="No Of Pax" value="<?= $sq_c['total_pax'] ?>" class="form-control" onchange="validate_balance(this.id)">

	</div>  

	<div class="col-md-4">

	    <input type="text" id="days_of_traveling" name="days_of_traveling" placeholder="*Days Of Travelling" title="Days Of Travelling" value="<?= $sq_c['days_of_traveling'] ?>"  class="form-control" onchange="validate_balance(this.id)">

	</div> 

	<div class="col-md-4">

    	<input type="text" id="traveling_date" name="traveling_date" placeholder="*Travel From Date" title="Travel From Date" value="<?= get_datetime_user($sq_c['traveling_date']) ?>" class="form-control">

  	</div>       

</div>

<div class="row mg_bt_10">
	<div class="col-md-4">
	    <select name="vehicle_type" id="vehicle_type" title="Vehicle Name" class="form-control">
	      <?php 
	      if($sq_c['vehicle_type']!=""){
	      	?>
			<option value="<?= $sq_c['vehicle_type'] ?>"><?= $sq_c['vehicle_type'] ?></option>
	      	<?php
	      }
	      ?>
	      <option value="">*Select Vehicle</option>
		  <?php
			$sql = mysqlQuery("select * from b2b_transfer_master where status='Active'");
			while($row = mysqli_fetch_assoc($sql)){ 
			?>
				<option value="<?= $row['vehicle_name']?>"><?= $row['vehicle_name']?></option>
		<?php }  ?>
	    </select>

	</div>

	<div class="col-md-4">
	    <select name="travel_type" id="travel_type" title="Travel Type" class="form-control">
	      <?php 
	      if($sq_c['travel_type']!=""){
	      	?>
			<option value="<?= $sq_c['travel_type'] ?>"><?= $sq_c['travel_type'] ?></option>
	      	<?php
	      }
	      ?>
	      <option value="">*Travel Type</option>
	      <option value="Local">Local</option>
	      <option value="Outstation">Outstation</option>

	    </select>

	</div>
	<div class="col-md-4 col-sm-6 mg_bt_10">

	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

	<div class="col-md-4">

	    <textarea name="places_to_visit" onchange="validate_spaces(this.id)" id="places_to_visit" placeholder="*Route" title="Route"><?= $sq_c['places_to_visit'] ?></textarea>

	</div>


</div>

<script>

$('#traveling_date').datetimepicker({ format:'d-m-Y H:i' });

</script>

<?php	

}



if($enquiry_type=="Bus"){



	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){

			if($enquiry_content_arr2['name']=="travel_datetime"){ $sq_c['travel_datetime'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="location_from"){ $sq_c['location_from'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="location_to"){ $sq_c['location_to'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="seat_type"){ $sq_c['seat_type'] = $enquiry_content_arr2['value']; } 

			if($enquiry_content_arr2['name']=="total_seats"){ $sq_c['total_seats'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="bus_name_and_type"){ $sq_c['bus_name_and_type'] = $enquiry_content_arr2['value']; }

			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

		}

	}

	else{

		$sq_c['travel_datetime'] = $sq_c['budget'] = $sq_c['location_from'] = $sq_c['location_to'] = $sq_c['seat_type'] = $sq_c['total_seats'] = $sq_c['bus_name_and_type'] = "";	

	}



?>

<div class="row mg_bt_10">

	<div class="col-md-4">

		<input type="text" id="travel_datetime" name="travel_datetime"  placeholder="*Travel Date/Time" title="Travel Date/Time" value="<?= get_datetime_user($sq_c['travel_datetime']) ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="location_from" name="location_from" onchange="validate_specialChar(this.id)" placeholder="*Location From" title="Location From" value="<?= $sq_c['location_from'] ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="location_to" name="location_to" placeholder="*Location To" onchange="validate_specialChar(this.id)" title="Location To" value="<?= $sq_c['location_to'] ?>" class="form-control">

	</div>

</div>

<div class="row mg_bt_10">

	<div class="col-md-4">		

		<select name="seat_type" id="seat_type" title="Seat Type" placeholder="*Seat Type"  class="form-control">

			<?php 

			if($sq_c['seat_type']!=""){

				?>

				<option value="<?= $sq_c['seat_type'] ?>"><?= $sq_c['seat_type'] ?></option>

				<?php

			}

			?>

			<option value="Seating">Seating</option>

			<option value="Semi sleeper">Semi sleeper</option>

			<option value="Sleeper">Sleeper</option>

			<option value="Window">Window</option>

		</select>

	</div>	

	<div class="col-md-4">

		<input type="text" id="total_seats" name="total_seats" onchange="validate_balance(this.id)" placeholder="*Total Seats" title="Total Seats" value="<?= $sq_c['total_seats'] ?>" class="form-control">

	</div>

	<div class="col-md-4">

		<input type="text" id="bus_name_and_type" name="bus_name_and_type" onchange="validate_specialChar(this.id)" placeholder="*Bus Name & Type" title="Bus Name & Type" value="<?= $sq_c['bus_name_and_type'] ?>" class="form-control">

	</div>

	<div class="col-md-4 col-sm-6 mg_tp_10">

	    <input type="text" id="budget" name="budget" onchange="number_validate(this.id)" placeholder="Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

</div>

<script>

$('#travel_datetime').datetimepicker({ format:'d-m-Y H:i' });

</script>

<?php

}

if($enquiry_type=="Passport"){



	if(isset($_POST['enquiry_id'])){

		foreach($enquiry_content_arr1 as $enquiry_content_arr2){
			 
			if($enquiry_content_arr2['name']=="budget"){ $sq_c['budget'] = $enquiry_content_arr2['value']; }

		}

	}

	else{

		  $sq_c['budget'] = "";	

	}



?>
 

<div class="row mg_bt_10">

	<div class="col-md-4 col-sm-6 mg_tp_10">

	    <input type="text" id="budget" name="budget" onchange="validate_balance(this.id)" placeholder="Budget" title="Budget" value="<?= $sq_c['budget'] ?>" class="form-control">

	</div>

</div>
<?php 
}
?>
<script>
$('.loader').remove();

if("<?= $enquiry_type ?>" =="Group Booking" || "<?= $enquiry_type ?>" == "Package Booking"){
	$("#tour_name").autocomplete({

		source: JSON.parse($('#destinations').val()),
		select: function (event, ui) {
			$("#tour_name").val(ui.item.label);
		},
		open: function(event, ui) {
			$(this).autocomplete("widget").css({
				"width": document.getElementById("tour_name").offsetWidth
			});
		}
	}).data("ui-autocomplete")._renderItem = function(ul, item) {
			return $("<li disabled>")
			.append("<a>" + item.label +"</a>")
			.appendTo(ul);
		
	};
}
</script>
<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>