<?php
include "../../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$sq = mysqli_fetch_assoc(mysqlQuery("select branch_status from branch_assign where link='excursion/index.php'"));
$branch_status = $sq['branch_status'];
?>
<input type="hidden" id="unique_timestamp" name="unique_timestamp" value="<?= md5(time()) ?>">
<input type="hidden" id="act_sc" name="act_sc">
<input type="hidden" id="act_markup" name="act_markup">
<input type="hidden" id="act_taxes" name="act_taxes">
<input type="hidden" id="act_markup_taxes" name="act_markup_taxes">

<input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
<div class="modal fade" id="exc_save_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document" style="min-width: 90%;">
		<div class="modal-content">
		<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Activity Booking</h4>
		</div>
		<div class="modal-body">

		<form id="frm_exc_save" name="frm_exc_save">
        
	        <div class="panel panel-default panel-body app_panel_style feildset-panel">
	        	<legend>Customer Details</legend>

	        	<div class="row">
	        		<div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
	        			<select name="customer_id" id="customer_id" class="form-control customer_dropdown" title="Customer Name" style="width:100%" onchange="customer_info_load();get_auto_values('balance_date','exc_issue_amount','payment_mode','service_charge','markup','save','true','service_charge');">
	        				<?php get_new_customer_dropdown($role,$branch_admin_id,$branch_status); ?>
	        			</select>
	        		</div>
	        		<div id="cust_details">	  
	        		<div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
	                	<input class="form-control" type="text" id="mobile_no" name="mobile_no" title="Mobile Number" placeholder="Mobile No" title="Mobile No" readonly>
	                </div>        
	        		<div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
	                	<input class="form-control" type="text" id="email_id" name="email_id" title="Email Id" placeholder="Email ID" title="Email ID" readonly>
	                </div>		
	                <div class="col-md-3 col-sm-4 col-xs-12">
	                	<input class="form-control hidden" type="text" id="company_name" name="company_name" title="Company Name" placeholder="Company Name" title="Company Name" readonly>
	                </div> 
	                <div class="col-md-3 col-sm-4 col-xs-12">
	                	<input class="form-control hidden" type="text" id="credit_amount" name="credit_amount" placeholder="Credit Note Balance" title="Credit Note Balance" readonly>
	                </div> 
	                </div>
	        	</div>

	        </div>

			<div id="new_cust_div"></div>
	        <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_30">
	        	<legend>Activity Details</legend>
				
				<div class="row mg_bt_10">
	                <div class="col-xs-6 text_center_xs">
        				<button	button type="button" class="btn btn-excel btn-sm" title="Add Activity" onclick="activity_save_modal()"><i class="fa fa-plus"></i></button>
					</div>
	                <div class="col-xs-6 text-right text_center_xs">
						<button type="button" class="btn btn-excel" title="Add Row" onclick="addRow('tbl_dynamic_exc_booking');city_lzloading('.city_name_exc');"><i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-pdf btn-sm" title="Delete Row" onclick="deleteRow('tbl_dynamic_exc_booking');calculate_exc_expense('tbl_dynamic_exc_booking')"><i class="fa fa-trash"></i></button>
	                </div>
	            </div>    	            
	            <div class="row">
	                <div class="col-md-12">
	                    <div class="table-responsive">
	                    <?php $offset = ""; ?>
	                    <table id="tbl_dynamic_exc_booking" name="tbl_dynamic_exc_booking" class="table table-bordered no-marg pd_bt_51">
	                    	<?php include_once('exc_tbl.php'); ?>
	                    </table>
	                    </div>
	                </div>
	            </div>        

	        </div>

	        <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_30">
	        	<legend>Costing Details</legend>

	        	<div class="row mg_bt_10">
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
					<small id="basic_show" style="color:red">&nbsp;</small>
		        		<input class="form-control" type="text" id="exc_issue_amount" name="exc_issue_amount" placeholder="*Basic Amount" title="Basic Amount" onchange="validate_balance(this.id);get_auto_values('balance_date','exc_issue_amount','payment_mode','service_charge','markup','save','true','basic','basic');">
		        	</div>
		        	<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
					<small id="service_show" style="color:red">&nbsp;</small>
		        		<input class="form-control" type="text" name="service_charge" id="service_charge" placeholder="Service Charge" title="Service Charge" onchange="validate_balance(this.id);get_auto_values('balance_date','exc_issue_amount','payment_mode','service_charge','markup','save','true','service_charge','basic');">
		        	</div>	
					<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small>&nbsp;</small>
						<select title="Tax Apply On" id="tax_apply_on" name="tax_apply_on" class="form-control" onchange="get_auto_values('balance_date','exc_issue_amount','payment_mode','service_charge','markup','save','true','service_charge','basic');">
							<option value="">*Tax Apply On</option>
							<option value="1">Basic Amount</option>
							<option value="2">Service Charge</option>
							<option value="3">Total</option>
						</select>
					</div> 
					<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small>&nbsp;</small>
						<select title="Select Tax" id="tax_value" name="tax_value" class="form-control" onchange="get_auto_values('balance_date','exc_issue_amount','payment_mode','service_charge','markup','save','true','service_charge','basic');">
							<option value="">*Select Tax</option>
							<?php get_tax_dropdown('Income') ?>
						</select>
					</div>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small>&nbsp;</small>
				        <input class="form-control" type="text" id="service_tax_subtotal" name="service_tax_subtotal" placeholder="Tax Amount" title="Tax Amount" readonly>
	        		</div>
					<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small id="markup_show" style="color:red">&nbsp;</small>
        				<input class="form-control" type="text" id="markup" name="markup" placeholder="Markup Amount" title="Markup Amount" onchange="get_auto_values('balance_date','exc_issue_amount','payment_mode','service_charge','markup','save','true','markup','basic');validate_balance(this.id)">
            		</div>
					<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small>&nbsp;</small>
						<select title="Select Markup Tax" id="markup_tax_value" name="markup_tax_value" class="form-control" onchange="get_auto_values('balance_date','exc_issue_amount','payment_mode','service_charge','markup','save','true','service_charge','basic');">
							<option value="">*Select Markup Tax</option>
							<?php get_tax_dropdown('Income') ?>
						</select>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small>&nbsp;</small>
                		<input class="form-control" type="text" id="service_tax_markup" name="service_tax_markup" placeholder="Tax on Markup" title="Tax on Markup" readonly>
            		</div>
					<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small>&nbsp;</small>
						<input class="form-control" type="text" name="roundoff" id="roundoff" class="text-right" placeholder="Round Off" title="RoundOff" readonly>
					</div> 
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small>&nbsp;</small>
	        			<input class="form-control amount_feild_highlight text-right" type="text" name="exc_total_cost" id="exc_total_cost" placeholder="Net Total" title="Net Total" readonly>
	        		</div>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small>&nbsp;</small>
	        			<input class="form-control" type="text" name="due_date" id="due_date" placeholder="Due Date" title="Due Date" value="<?= date('d-m-Y') ?>">
	        		</div>
	        		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small>&nbsp;</small>
	        			<input class="form-control" type="text" name="balance_date" id="balance_date" value="<?= date('d-m-Y') ?>" placeholder="Booking Date" title="Booking Date" onchange="check_valid_date(this.id);get_auto_values('balance_date','exc_issue_amount','payment_mode','service_charge','markup','save','true','service_charge','basic');">
	        		</div>
					<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
						<small>&nbsp;</small>
						<select class="form-control" name="currency_code" id="acurrency_code" title="Currency" style="width:100%" data-toggle="tooltip" required>
							<?php
							$sq_app_setting = mysqli_fetch_assoc(mysqlQuery("select currency from app_settings"));
							if($sq_app_setting['currency']!='0'){

								$sq_currencyd = mysqli_fetch_assoc(mysqlQuery("SELECT `id`,`currency_code` FROM `currency_name_master` WHERE id=" . $sq_app_setting['currency']));
								?>
								<option value="<?= $sq_currencyd['id'] ?>"><?= $sq_currencyd['currency_code'] ?></option>
							<?php } ?>
							<?php
							$sq_currency = mysqlQuery("select * from currency_name_master order by currency_code");
							while($row_currency = mysqli_fetch_assoc($sq_currency)){
							?>
							<option value="<?= $row_currency['id'] ?>"><?= $row_currency['currency_code'] ?></option>
							<?php } ?>
						</select>
					</div>
	        	</div> 
	        </div>

	        <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_30">
	        	<legend>Advance Details</legend>
				
				<div class="row">
					<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
						<input class="form-control" type="text" id="payment_date" name="payment_date" placeholder="Date" title="Date" value="<?= date('d-m-Y')?>" onchange="check_valid_date(this.id)">
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
						<select class="form-control" name="payment_mode" id="payment_mode" title="Mode" onchange="get_auto_values('balance_date','exc_issue_amount','payment_mode','service_charge','markup','save','true','service_charge',true);payment_master_toggles(this.id, 'bank_name', 'transaction_id', 'bank_id');get_identifier_block('identifier','payment_mode','credit_card_details','credit_charges');get_credit_card_charges('identifier','payment_mode','payment_amount','credit_card_details','credit_charges');">
							<?php get_payment_mode_dropdown(); ?>
						</select>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
						<input class="form-control" type="text" id="payment_amount" name="payment_amount" placeholder="*Amount" title="Amount" onchange="payment_amount_validate(this.id,'payment_mode','transaction_id','bank_name','bank_id');validate_balance(this.id);get_credit_card_charges('identifier','payment_mode','payment_amount','credit_card_details','credit_charges');">
					</div>
					</div>
					<div class="row mg_bt_10">
						<div class="col-md-4 col-sm-6 col-xs-12">
							<input class="hidden form-control" type="text" id="credit_charges" name="credit_charges" title="Credit card charges" disabled>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<select class="hidden form-control" id="identifier" onchange="get_credit_card_data('identifier','payment_mode','credit_card_details')" title="Identifier(4 digit)" required
							><option value=''>Select Identifier</option></select>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<input class="form-control hidden" type="text" id="credit_card_details" name="credit_card_details" title="Credit card details" disabled>
						</div>
					</div>
					<div class="row mg_bt_10">
					<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
						<input class="form-control bank_suggest" type="text" id="bank_name" name="bank_name" onchange="fname_validate(this.id)" placeholder="Bank Name" title="Bank Name" readonly>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
						<input class="form-control" type="number" id="transaction_id" name="transaction_id" onchange="validate_specialChar(this.id)" placeholder="Cheque No/ID" title="Cheque No/ID" readonly>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
			            <select class="form-control" name="bank_id" id="bank_id" title="Select Bank" disabled>
			            	<?php get_bank_dropdown(); ?>
			            </select>
			        </div>
				</div>
		        <div class="row">
					<div class="col-md-9 col-sm-9">
					<span style="color: red;line-height: 35px;" data-original-title="" title="" class="note"><?= $txn_feild_note ?></span>
					</div>
		        </div>

	        </div>

	        <div class="row text-center">
	        	<div class="col-md-12">
	        		<button id="btn_exc_master_save" class="btn btn-sm btn-success"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
	        	</div>
	        </div>
        </form>
	</div>
</div>
</div>
</div>

<script src="js/calculation.js"></script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
$('#exc_save_modal').modal('show');
$('#payment_date, #due_date,#balance_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
$('#acurrency_code').select2();

$('#customer_id').select2();
function business_rule_load(){
	get_auto_values('balance_date','exc_issue_amount','payment_mode','service_charge','markup','save','true','service_charge');
}

$(function(){

$('#frm_exc_save').validate({
	rules:{
			customer_id: { required: true },
			exc_issue_amount: { required: true, number: true },
			service_charge :{ required : true, number:true },
			markup :{ required : true, number:true },
			exc_total_cost :{ required : true, number:true },
			balance_date:{ required : true },

			payment_date : { required : true },
			payment_amount : { required : true },
			payment_mode : { required : true },
			country_code : { required : function(){  if($('#customer_id').val()=='0'){ return true; }else{ return false; }  }  },
            bank_id : { required : function(){  if($('#payment_mode').val()!="Cash"){ return true; }else{ return false; }  }  },  
            //due_date : { required : true },   
			tax_apply_on : { required:true},
			tax_value : { required:true},
			markup_tax_value : { required:true}
	},
	submitHandler:function(form){
		$('#btn_exc_master_save').prop('disabled',true);
		var base_url = $('#base_url').val();	
		var customer_id = $('#customer_id').val();
		var first_name = $('#cust_first_name').val();
		var cust_middle_name = $('#cust_middle_name').val();
		var cust_last_name = $('#cust_last_name').val();
		var gender = $('#cust_gender').val();
		var birth_date = $('#cust_birth_date').val();
		var age = $('#cust_age').val();
		var contact_no = $('#cust_contact_no').val();
		var email_id = $('#cust_email_id').val();
		var address = $('#cust_address1').val();
		var address2 = $('#cust_address2').val();
		var city = $('#city').val();
		var service_tax_no = $('#cust_service_tax_no').val();  
		var landline_no = $('#cust_landline_no').val();
		var alt_email_id = $('#cust_alt_email_id').val();
		var company_name = $('#corpo_company_name').val();
		var cust_type = $('#cust_type').val();
		var country_code = $('#country_code').val();
		var state = $('#cust_state').val();
		var active_flag = 'Active';
		var branch_admin_id = $('#branch_admin_id1').val();
		var credit_amount = $('#credit_amount').val();
		var credit_charges = $('#credit_charges').val();
		var credit_card_details = $('#credit_card_details').val();
		var currency_code = $('#acurrency_code').val();

		var emp_id = $('#emp_id').val();
		var exc_issue_amount = $('#exc_issue_amount').val();	
		var service_charge = $('#service_charge').val();
		var service_tax_subtotal = $('#service_tax_subtotal').val();
		var roundoff = $('#roundoff').val();

		var exc_total_cost = $('#exc_total_cost').val();
		var due_date = $('#due_date').val();
		var balance_date = $('#balance_date').val();

		var payment_date = $('#payment_date').val();
		var payment_amount = $('#payment_amount').val();
		var payment_mode = $('#payment_mode').val();
		var bank_name = $('#bank_name').val();
		var transaction_id = $('#transaction_id').val();	
		var bank_id = $('#bank_id').val();	
		var markup = $('#markup').val();
		var service_tax_markup = $('#service_tax_markup').val();
		
		var exc_date_arr = new Array();
		var city_id_arr = new Array();
		var exc_name_arr = new Array();
		var transfer_arr = new Array();
		var total_adult_arr = new Array();
		var total_child_arr = new Array();
		var adult_cost_arr = new Array();
		var child_cost_arr = new Array();
		var total_amt_arr = new Array();
		var total_infant_arr = [];
		var infant_cost_arr = [];
		var total_vehicle_arr = [];
		var transfer_cost_arr = [];

		if(payment_mode=="Advance"){
			error_msg_alert("Please select another payment mode.");
			$('#btn_exc_master_save').prop('disabled',false);
			return false;
		}
			if(payment_mode=="Credit Note" && credit_amount != ''){ 
	        	if(parseFloat(payment_amount) > parseFloat(credit_amount)) { error_msg_alert('Credit Note Balance is not available'); $('#btn_exc_master_save').prop('disabled',false); return false; }
	        }else if(payment_mode=="Credit Note" && credit_amount == ''){
				error_msg_alert("Credit Note Balance is not available"); $('#btn_exc_master_save').prop('disabled',false); return false;
			}
	        if(parseFloat(payment_amount)>parseFloat(exc_total_cost)){
				error_msg_alert("Payment amount cannot be greater than selling amount.");
				$('#btn_exc_master_save').prop('disabled',false);
				return false;
			}
	        var table = document.getElementById("tbl_dynamic_exc_booking");
	        var rowCount = table.rows.length;
			var checked_count = 0;
			for (var i = 0; i < rowCount; i++) {
				var row = table.rows[i];
				if (row.cells[0].childNodes[0].checked) {
					checked_count++;
				}
			}
			if(checked_count==0){
				error_msg_alert("Atleast one Activity is required!");
				$('#btn_exc_master_save').prop('disabled', false);
				return false;
			}
	        for(var i=0; i<rowCount; i++)
	        {
				var row = table.rows[i];
				if(row.cells[0].childNodes[0].checked){

					var exc_date = row.cells[2].childNodes[0].value;
					var city_id = row.cells[3].childNodes[0].value;
					var exc_name = row.cells[4].childNodes[0].value;
					var transfer_option = row.cells[5].childNodes[0].value;
					var total_adult = row.cells[6].childNodes[0].value;
					var total_child = row.cells[7].childNodes[0].value;
					var total_infant = row.cells[8].childNodes[0].value;
					var adult_cost = row.cells[9].childNodes[0].value;
					var child_cost = row.cells[10].childNodes[0].value;
					var infant_cost = row.cells[11].childNodes[0].value;
					var total_vehicle = row.cells[12].childNodes[0].value;
					var transfer_cost = row.cells[13].childNodes[0].value;
					var total_amt = row.cells[14].childNodes[0].value;

					var msg = "";

					if(exc_date==""){ msg +="Activity Date is required in row:"+(i+1)+"<br>"; }
					if(city_id==""){ msg +="City name is required in row:"+(i+1)+"<br>"; }
					if(exc_name==""){ msg +="Activity Name is required in row:"+(i+1)+"<br>"; }
					if(transfer_option==""){ msg +="Transfer option is required in row:"+(i+1)+"<br>"; }
					if(total_adult==""){ msg +="Total Adult(s) is required in row:"+(i+1)+"<br>"; }
					if(total_child==""){ msg +="Total Child(ren) is required in row:"+(i+1)+"<br>"; }

					if(msg!=""){
						error_msg_alert(msg);
						$('#btn_exc_master_save').prop('disabled',false);
						return false;
					}

					exc_date_arr.push(exc_date);
					city_id_arr.push(city_id);
					exc_name_arr.push(exc_name);
					total_adult_arr.push(total_adult);
					total_child_arr.push(total_child);
					adult_cost_arr.push(adult_cost);
					child_cost_arr.push(child_cost);
					total_amt_arr.push(total_amt);             
					transfer_arr.push(transfer_option);
					total_infant_arr.push(total_infant);
					infant_cost_arr.push(infant_cost);
					total_vehicle_arr.push(total_vehicle);
					transfer_cost_arr.push(transfer_cost);
				}
	        }
			//Validation for booking and payment date in login financial year
			var act_sc = $('#act_sc').val();
			var act_markup = $('#act_markup').val();
			var act_taxes = $('#act_taxes').val();
			var act_markup_taxes = $('#act_markup_taxes').val();
			var act_tds = $('#act_tds').val();
			var tax_apply_on = $('#tax_apply_on').val();
			var tax_value = $('#tax_value').val();
			var markup_tax_value = $('#markup_tax_value').val();

			var reflections = [];
			reflections.push({
			'act_sc':act_sc,
			'act_markup':act_markup,
			'act_taxes':act_taxes,
			'act_markup_taxes':act_markup_taxes,
			'act_tds':act_tds,
			'tax_apply_on':tax_apply_on,
			'tax_value':tax_value,
			'markup_tax_value':markup_tax_value
			});
			var bsmValues = [];
			bsmValues.push({
				"basic" : $('#basic_show').find('span').text(),
				"service" : $('#service_show').find('span').text(),
				"markup" : $('#markup_show').find('span').text()
			});
			var check_date1 = $('#balance_date').val();

			$('#btn_exc_master_save').button('loading');
				$.post(base_url+'view/load_data/finance_date_validation.php', { check_date: check_date1 }, function(data){
				if(data !== 'valid'){
					error_msg_alert("The Booking date does not match between selected Financial year.");
					$('#btn_exc_master_save').button('reset');
					$('#btn_exc_master_save').prop('disabled',false);
					return false;
				}
				else{
					var payment_date = $('#payment_date').val();
					$.post(base_url+'view/load_data/finance_date_validation.php', { check_date: payment_date }, function(data){
						if(data !== 'valid'){
							error_msg_alert("The Payment date does not match between selected Financial year.");
							$('#btn_exc_master_save').prop('disabled',false);
							$('#btn_exc_master_save').button('reset');
							return false;
						}
						else{
							//New Customer save
							if(customer_id == '0'){
								$.ajax({
									type: 'post',
									url: base_url+'controller/customer_master/customer_save.php',
									data:{ first_name : first_name, middle_name : cust_middle_name, last_name : cust_last_name, gender : gender, birth_date : birth_date, age : age, contact_no : contact_no, email_id : email_id, address : address,address2 : address2,city:city,  active_flag : active_flag ,service_tax_no : service_tax_no, landline_no : landline_no, alt_email_id : alt_email_id,company_name : company_name, cust_type : cust_type,state : state, branch_admin_id : branch_admin_id, country_code : country_code},
									success: function(result){
										var error_arr = result.split('--');
										if(error_arr[0]=='error'){
										error_msg_alert(error_arr[1]);
										$('#btn_exc_master_save').button('reset');
										$('#btn_exc_master_save').prop('disabled',false);
										return false;
										}
										else{
										saveData();
										}
									}
								});
							}
							else{
								saveData();
							}

							function saveData(){
								
								$.ajax({
									type: 'post',
									url: base_url+'controller/excursion/exc_master_save.php',
									data:{ emp_id : emp_id, customer_id : customer_id, exc_issue_amount : exc_issue_amount, service_charge : service_charge, service_tax_subtotal : service_tax_subtotal, exc_total_cost : exc_total_cost, payment_date : payment_date, payment_amount : payment_amount, payment_mode : payment_mode, bank_name : bank_name, transaction_id : transaction_id, bank_id : bank_id, due_date : due_date,balance_date : balance_date,exc_date_arr : exc_date_arr,city_id_arr : city_id_arr,exc_name_arr : exc_name_arr, total_adult_arr : total_adult_arr,total_child_arr : total_child_arr,adult_cost_arr : adult_cost_arr,child_cost_arr : child_cost_arr,total_amt_arr : total_amt_arr,total_infant_arr:total_infant_arr,infant_cost_arr:infant_cost_arr,total_vehicle_arr:total_vehicle_arr,transfer_cost_arr:transfer_cost_arr,transfer_arr:transfer_arr, branch_admin_id : branch_admin_id, markup : markup, service_tax_markup : service_tax_markup, reflections : reflections,roundoff:roundoff,bsmValues:bsmValues,credit_charges:credit_charges,credit_card_details:credit_card_details,currency_code:currency_code },
									success: function(result){
										$('#btn_exc_master_save').button('reset');
										var msg = result.split('-');
										
										if(msg[0]=='error'){
											$('#btn_exc_master_save').prop('disabled',false);
											btn_exc_master_save
											msg_alert(result);
											return false;
										}
										else{
                        					var msg1 = result.split('-');
											$('#btn_exc_master_save').prop('disabled',false);
											$('#btn_exc_master_save').button('reset');
											msg_alert(msg1[0]);
											reset_form('frm_exc_save');
											$('#exc_save_modal').modal('hide');	
											exc_customer_list_reflect();
                        					window.open(base_url+'view/vendor/dashboard/estimate/estimate_save_modal.php?type=Activity&amount='+exc_issue_amount+'&booking_id='+msg1[1]);
											setTimeout(() => {
												if($('#whatsapp_switch').val() == "on") whatsapp_send(emp_id, customer_id, balance_date, base_url, country_code+contact_no,email_id);
											}, 1000);
										}
									}
								});
							}
						}
					});
				}
			});
		}
});

});
</script>