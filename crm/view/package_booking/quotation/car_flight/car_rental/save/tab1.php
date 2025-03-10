<form id="frm_tab1">
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
		    <input type="hidden" id="emp_id" name="emp_id" value="<?= $emp_id ?>">
		    <input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
		    <input type="hidden" id="financial_year_id" name="financial_year_id" value="<?= $financial_year_id ?>" >
			<input type="hidden" id="login_id" name="login_id" value="<?= $login_id ?>">

			<select name="enquiry_id" id="enquiry_id" title="Enquiry No" style="width:100%" onchange="get_enquiry_details();">
				<option value="">*Enquiry No</option>
				<option value="0"><?= "New Enquiry" ?></option>
				<?php
				if($role=='Admin'){
					$sq_enq = mysqlQuery("select * from enquiry_master where enquiry_type in('Car Rental') and status!='Disabled' order by enquiry_id desc");
				}
				else{
					if($branch_status=='yes'){
						if($role=='Branch Admin' || $role=='Accountant' || $role_id>'7'){
							$sq_enq = mysqlQuery("select * from enquiry_master where enquiry_type in('Car Rental') and status!='Disabled' and branch_admin_id='$branch_admin_id' order by enquiry_id desc");
						}
						elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
							
                            if($show_entries_switch == 'No'){
								$q = "select * from enquiry_master where enquiry_type in('Car Rental') and assigned_emp_id='$emp_id' and status!='Disabled' order by enquiry_id desc";
							}
                            else{
                                if($role == 'Backoffice'){
                                    $q = "select * from enquiry_master where enquiry_type in('Car Rental') and assigned_emp_id in(select emp_id from emp_master where branch_id='$branch_admin_id') and status!='Disabled' order by enquiry_id desc";
                                }else{
                                    $q = "select * from enquiry_master where enquiry_type in('Car Rental') and assigned_emp_id='$emp_id' and status!='Disabled' order by enquiry_id desc";
                                }
                            }
							$sq_enq = mysqlQuery($q);
						}
					}
					elseif($branch_status!='yes' && ($role=='Branch Admin' || $role_id=='7')){
						
						$sq_enq = mysqlQuery("select * from enquiry_master where enquiry_type in('Car Rental') and status!='Disabled' order by enquiry_id desc");
					}
					elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
						
						if($show_entries_switch == 'No'){
							$q = "select * from enquiry_master where enquiry_type in('Car Rental') and assigned_emp_id='$emp_id' and status!='Disabled' order by enquiry_id desc";
						}
						else{
							if($role == 'Backoffice'){
								$q = "select * from enquiry_master where enquiry_type in('Car Rental') and assigned_emp_id in(select emp_id from emp_master where branch_id='$branch_admin_id') and status!='Disabled' order by enquiry_id desc";
							}else{
								$q = "select * from enquiry_master where enquiry_type in('Car Rental') and assigned_emp_id='$emp_id' and status!='Disabled' order by enquiry_id desc";
							}
						}
						$sq_enq = mysqlQuery($q);
					}
				}
				while($row_enq = mysqli_fetch_assoc($sq_enq)){
					$sq_enq1 = mysqli_fetch_assoc(mysqlQuery("SELECT followup_status FROM `enquiry_master_entries` WHERE `enquiry_id` = '$row_enq[enquiry_id]' ORDER BY `entry_id` DESC"));
					if($sq_enq1['followup_status'] != 'Dropped'){
					?>
					<option value="<?= $row_enq['enquiry_id'] ?>">Enq<?= $row_enq['enquiry_id'] ?> : <?= $row_enq['name'] ?></option>
					<?php
					}
				}
				?>
			</select>
		</div>	
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	    	<input type="text" id="customer_name" name="customer_name" onchange="fname_validate(this.id)" placeholder="*Customer Name" title="Customer Name">
            <input type="hidden" id="cust_data" name="cust_data" value='<?= get_customer_hint($branch_status) ?>'>
	    </div>
        <div class="col-md-3 col-sm-6 mg_bt_10">
            <div class="col-md-3" style="padding-left:0px;">
                <select style="width:125px !important;" class="form-control" name="country_code" id="country_code" title="Country code">
                    <?= get_country_code(); ?>
                </select>
            </div>
            <div class="col-md-9" style="padding-left:60px;padding-right:0px;">
                <input type="text" class="form-control" id="mobile_no" onchange="mobile_validate(this.id);" name="mobile_no" placeholder="*WhatsApp No" title="WhatsApp No">
            </div>
        </div>
	    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="email_id" name="email_id" placeholder="Email ID" title="Email ID">
		</div>	
	</div>
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	    	<input type="text" id="total_pax" name="total_pax"  onchange="validate_balance(this.id)" placeholder="No Of Pax" title="No Of Pax">
	    </div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<select name="travel_type" id="travel_type" title="Travel Type" onchange="get_car_cost();reflect_feilds();">
	            <option value="">Select Travel Type</option>
	            <option value="Local">Local</option>
	            <option value="Outstation">OutStation</option>
	        </select>
	    </div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<select name="vehicle_name" id="vehicle_name" title="Select Vehicle Name" class="form-control" onchange="get_capacity();get_car_cost();">
                <option value="">*Select Vehicle</option>
                <?php
                    $sql = mysqlQuery("select * from b2b_transfer_master where status!='Inactive'");
                    while($row = mysqli_fetch_assoc($sql)){ 
                    ?>
                        <option value="<?= $row['vehicle_name']?>"><?= $row['vehicle_name']?></option>
                <?php } ?>
            </select>
	    </div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	    	<input type="text" id="capacity" name="capacity"  placeholder="*Capacity" title="*Capacity" onchange="">
	    </div>
	</div>
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<textarea name="local_places_to_visit" id="local_places_to_visit" onchange="validate_specialChar(this.id)" rows="1" placeholder="Route" title="Route"></textarea>
		
			<select name="places_to_visit" id="places_to_visit" title="Select Route" class="form-control" onchange="get_car_cost();">
				<option value="">*Select Route</option>
				
					<?php
						$sql = mysqlQuery("select * from car_rental_tariff_entries where tour_type='Outstation'");
						while($row = mysqli_fetch_assoc($sql)){ 
						?>
							<option value="<?= $row['route']?>"><?= $row['route']?></option>
					<?php } ?>
			</select>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="extra_km_cost" name="extra_km_cost" placeholder="Extra KM Cost" title="Extra KM Cost" onchange="validate_balance(this.id)">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	    	<input type="text" class="form-control" id="extra_hr_cost" name="extra_hr_cost" placeholder="Extra Hr Cost" title="Extra Hr Cost" onchange=";validate_balance(this.id)"> 
	    </div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	    	<input type="text" id="rate" name="rate"  placeholder="*Daily Rate" title="Daily Rate" onchange="validate_balance(this.id)">
	    </div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="from_date" name="from_date" placeholder="Travel From Date" title="Travel From Date" value="<?= date('d-m-Y')?>" onchange="get_to_date(this.id,'to_date');total_days_reflect()">
		</div>	
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	    	<input type="text" id="to_date" name="to_date" placeholder="Travel To Date" title="Travel To Date" value="<?= date('d-m-Y')?>" onchange="validate_validDate('from_date','to_date');total_days_reflect()">
	    </div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="days_of_traveling" name="days_of_traveling" onchange="validate_balance(this.id);" placeholder="Days Of Travelling" title="Days Of Travelling" readonly>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="total_hr" name="total_hr" placeholder="Total Hrs" title="Total Hrs" onchange="validate_balance(this.id)">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="total_km" name="total_km" placeholder="Total Km" title="Total Km" onchange="validate_balance(this.id)">
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	    	<input type="text" id="total_max_km" name="total_max_km" placeholder="Total Max Km" title="Total Max Km" onchange=";validate_balance(this.id)">
	    </div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	    	<input type="text" id="traveling_date" name="traveling_date" placeholder="Travelling Date" title="Travelling Date" class="form-control" value="<?= date('d-m-Y H:i') ?>">
	    </div>
	</div>
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
	    	<input type="text" class="form-control" id="quotation_date" name="quotation_date" placeholder="Quotation Date" title="Quotation Date" onchange="get_auto_values('quotation_date','subtotal','payment_mode','service_charge','markup_cost','save','true','service_charge', true);" value="<?= date('d-m-Y')?>"> 
	    </div>
	</div>
	<br><br>
	<div class="row text-center">
		<div class="col-xs-12">
			<button class="btn btn-info btn-sm ico_right" onclick=get_basic_amount()>Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
		</div>
	</div>
</form>
<script>
$('#country_code').select2();
$("#customer_name").autocomplete({
    source: JSON.parse($('#cust_data').val()),
    select: function(event, ui) {
        $("#customer_name").val(ui.item.label);
        $('#mobile_no').val(ui.item.contact_no);
        $('#country_code').val(ui.item.country_id);
		$('#country_code').trigger('change');
        $('#email_id').val(ui.item.email_id);
    },
    open: function(event, ui) {
        $(this).autocomplete("widget").css({
            "width": document.getElementById("customer_name").offsetWidth
        });
    }
}).data("ui-autocomplete")._renderItem = function(ul, item) {
    return $("<li disabled>")
        .append("<a>" + item.label + "</a>")
        .appendTo(ul);
};
$('#from_date,#to_date').datetimepicker({ timepicker:false,format:'d-m-Y' });
$('#traveling_date').datetimepicker({ format:'d-m-Y H:i' });

$('#total_hr,#total_km,#total_max_km,#driver_allowance,#permit_charges,#toll_parking,#state_entry,#other_charges,#places_to_visit,#traveling_date').hide();

// New Customization ----start
$(document).ready(function(){
	let searchParams = new URLSearchParams(window.location.search);
	if( searchParams.get('enquiry_id') ){
		$('#enquiry_id').val(searchParams.get('enquiry_id'));
		$('#enquiry_id').trigger('change');
	}
});
// New Customization ----end
// full form validation 
$('input').keyup(function(){	$(this).removeAttr('style');	});
$('#frm_tab1').validate({

	rules:{
		customer_name : { required : true },
		vehicle_name : { required : true },
		capacity : { required : true },
		extra_km_cost	:	{	regex	:	/^[0-9\.]*$/	},
		extra_hr_cost	:	{	regex	:	/^[0-9\.]*$/	},
		places_to_visit : { required : function(){  if($('#travel_type').val()=="Outstation"){ return true; }else{ return false; }  }  },
		traveling_date : { required : function(){  if($('#travel_type').val()=="Outstation"){ return true; }else{ return false; }  }  },
		mobile_no : { required : true },
		country_code : { required : true }
	},
	messages:{
		extra_km_cost	:	"Only Numbers Allowed",
		extra_hr_cost	:	"Only Numbers Allowed",
	},
	onkeyup: false,
	// errorPlacement: function(error, element) {
	// 	$(element).css({ border: '1px solid red' });
	// 	error_msg_alert(error[0].innerText);
	// },

	submitHandler:function(form){
		if($("#rate").val() <= 0){
			error_msg_alert("Please enter correct rate!");
			return false;
		}
		$('a[href="#tab2"]').tab('show');

	}

});

function get_basic_amount(){
	
	var travel_type = $('#travel_type').val();
	var capacity = $('#capacity').val();
	var rate = $('#rate').val();
	var days_of_traveling = $('#days_of_traveling').val();
	var total_pax = $('#total_pax').val();

	if(capacity==""){ capacity = 0;}
	if(rate==""){ rate = 0;}
	if(days_of_traveling==""){ days_of_traveling = 0;}
	if(total_pax==""){ total_pax = 0;}

	var no_of_vehicle = Math.ceil(parseFloat(total_pax)/parseFloat(capacity)); 
	var basic_amount = parseFloat(rate)*parseFloat(days_of_traveling)*parseFloat(no_of_vehicle);

	if(isNaN(basic_amount)){ basic_amount = 0;}
	$('#subtotal').val(basic_amount.toFixed(2));
	get_auto_values('quotation_date','subtotal','payment_mode','service_charge','markup_cost','save','true','service_charge');
	quotation_cost_calculate();
}
function total_days_reflect(offset=''){
    var from_date = $('#from_date'+offset).val(); 
    var to_date = $('#to_date'+offset).val(); 

    var edate = from_date.split("-");
    e_date = new Date(edate[2],edate[1]-1,edate[0]).getTime();
    var edate1 = to_date.split("-");
    e_date1 = new Date(edate1[2],edate1[1]-1,edate1[0]).getTime();

    var one_day=1000*60*60*24;

    var from_date_ms = new Date(e_date).getTime();
    var to_date_ms = new Date(e_date1).getTime();
    
    var difference_ms = to_date_ms - from_date_ms;
    var total_days = Math.round(Math.abs(difference_ms)/one_day); 

	total_days = parseFloat(total_days)+1;
	
    $('#days_of_traveling'+offset).val(total_days);
}
function get_capacity(){

	var vehicle_name = $('#vehicle_name').val();
	var travel_type = $('#travel_type').val();
	var base_url = $('#base_url').val();
	$.ajax({
		type:'post',
		url: base_url+'view/package_booking/quotation/car_flight/car_rental/get_capacity.php', 
		// dataType: "json",
		data: { travel_type : travel_type,vehicle_name:vehicle_name },
		success: function(result){
			// var res = JSON.parse(result);
			// var resp = JSON.parse(res.vehicle_data);
			// console.log(resp[0].seating_capacity);
		$('#capacity').val(result);
		}
	});
}
</script>

