<?php
include '../../../model/model.php';
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$sq = mysqli_fetch_assoc(mysqlQuery("select branch_status from branch_assign where link='miscellaneous/index.php'"));
$branch_status = $sq['branch_status'];
?>
<input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>">
<input type="hidden" id="unique_timestamp" name="unique_timestamp" value="<?= md5(time()) ?>">
<input type="hidden" id="misc_sc" name="misc_sc">
<input type="hidden" id="misc_markup" name="misc_markup">
<input type="hidden" id="misc_taxes" name="misc_taxes">
<input type="hidden" id="misc_markup_taxes" name="misc_markup_taxes">
<input type="hidden" id="misc_tds" name="misc_tds">

<div class="modal fade" id="visa_save_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document" style="min-width: 90%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">New Miscellaneous Booking</h4>
			</div>
			<div class="modal-body">

				<form id="frm_visa_save" name="frm_visa_save">

					<div class="panel panel-default panel-body fieldset">
						<legend>Customer Details</legend>

						<div class="row">
							<div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
								<select name="customer_id" id="customer_id" class="form-control customer_dropdown" title="Customer Name" style="width:100%" onchange="customer_info_load();get_auto_values('balance_date','visa_issue_amount','payment_mode','service_charge','markup','save','true','service_charge');">
									<?php get_new_customer_dropdown($role, $branch_admin_id, $branch_status); ?>
								</select>
							</div>
							<div id="cust_details">
								<div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
									<input type="text" id="mobile_no" name="mobile_no" title="Mobile Number" placeholder="Mobile No" title="Mobile No" class="form-control" readonly>
								</div>
								<div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
									<input type="text" id="email_id" name="email_id" title="Email Id" placeholder="Email ID" title="Email ID" class="form-control" readonly>
								</div>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="text" id="company_name" class="hidden form-control" name="company_name" title="Company Name" placeholder="Company Name" class="form-control" title="Company Name" readonly>
								</div>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="text" id="credit_amount" class="hidden form-control" name="credit_amount" placeholder="Credit Note Balance" title="Credit Note Balance" readonly>
								</div>
							</div>
						</div>
						<div class="row mg_tp_10">
							<div class="col-md-4">
								<input id="copy_details1" name="copy_details1" type="checkbox" onClick="copy_details();">
								&nbsp;&nbsp;<label for="copy_details1">Passenger Details same as above</label>
							</div>
						</div>

					</div>
					<div id="new_cust_div"></div>
					<div class="panel panel-default panel-body fieldset">
						<legend>Service Details</legend>

						<div class="row">
							<div class="col-md-4 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
								<label>Enter Services</label>
								<input type="text" name="service" class="form-control" title="Enter Services" id="service" data-role="tagsinput" required>
							</div>

							<div class="col-md-6 col-sm-6 col-xs-12 mg_bt_10_sm_xs">
								<label>Narration</label>
								<input type="text" id="narration" class="form-control" name="narration" placeholder="Narration" title="Narration">
							</div>
						</div>

					</div>

					<div class="panel panel-default panel-body fieldset mg_tp_30">
						<legend>Passenger Details</legend>

						<div class="row mg_bt_10">
							<div class="col-xs-12 text-right text_center_xs">
                                <button type="button" class="btn btn-excel" title="Add Row" onclick="addRow('tbl_dynamic_miscellaneous')"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-pdf btn-sm" title="Delete Row" onclick="deleteRow('tbl_dynamic_miscellaneous');"><i class="fa fa-trash"></i></button>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<?php $offset = ""; ?>
									<table id="tbl_dynamic_miscellaneous" name="tbl_dynamic_miscellaneous" class="table table-bordered no-marg pd_bt_51" style="min-width:1500px">
										<?php include_once('miscellaneous_member_tbl.php'); ?>
									</table>
								</div>
							</div>
						</div>


					</div>

					<div class="panel panel-default panel-body fieldset mg_tp_30">
						<legend>Costing Details</legend>

						<div class="row mg_bt_10">
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10_xs">
								<small id="basic_show" style="color:red">&nbsp;</small>
								<input class="form-control" type="text" id="visa_issue_amount" name="visa_issue_amount" placeholder="*Basic Amount" title="Basic Amount" onchange="validate_balance(this.id);get_auto_values('balance_date','visa_issue_amount','payment_mode','service_charge','markup','save','true','service_charge');">
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
								<small id="service_show" style="color:red">&nbsp;</small>
								<input class="form-control" type="text" name="service_charge" id="service_charge" placeholder="Service Charge" title="Service Charge" onchange="validate_balance(this.id);get_auto_values('balance_date','visa_issue_amount','payment_mode','service_charge','markup','save','false','service_charge')">
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
								<small>&nbsp;</small>
								<select title="Tax Apply On" id="tax_apply_on" name="tax_apply_on" class="form-control" onchange="get_auto_values('balance_date','visa_issue_amount','payment_mode','service_charge','markup','save','false','service_charge');">
									<option value="">*Tax Apply On</option>
									<option value="1">Basic Amount</option>
									<option value="2">Service Charge</option>
									<option value="3">Total</option>
								</select>
							</div> 
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
								<small>&nbsp;</small>
								<select title="Select Tax" id="tax_value" name="tax_value" class="form-control" onchange="get_auto_values('balance_date','visa_issue_amount','payment_mode','service_charge','markup','save','false','service_charge');">
									<option value="">*Select Tax</option>
									<?php get_tax_dropdown('Income') ?>
								</select>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mg_tp_10">
								<input class="form-control" type="text" id="service_tax_subtotal" name="service_tax_subtotal" placeholder="Tax Amount" title="Tax Amount" readonly>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
								<small id="markup_show" style="color:red">&nbsp;</small>
								<input class="form-control" type="text" id="markup" name="markup" placeholder="Markup Amount" title="Markup Amount" onchange="get_auto_values('balance_date','visa_issue_amount','payment_mode','service_charge','markup','save','true','markup');">
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
								<small>&nbsp;</small>
								<select title="Select Markup Tax" id="markup_tax_value" name="markup_tax_value" class="form-control" onchange="get_auto_values('balance_date','visa_issue_amount','payment_mode','service_charge','markup','save','false','service_charge');">
									<option value="">*Select Markup Tax</option>
									<?php get_tax_dropdown('Income') ?>
								</select>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
								<small>&nbsp;</small>
								<input class="form-control" type="text" id="service_tax_markup" name="service_tax_markup" placeholder="Markup Tax" title="Markup Tax" readonly>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
								<small>&nbsp;</small>
								<input class="form-control" type="text" name="roundoff" id="roundoff" class="text-right" placeholder="Round Off" title="RoundOff" readonly>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
								<small>&nbsp;</small>
								<input type="text" name="visa_total_cost" id="visa_total_cost" class="form-control amount_feild_highlight text-right" placeholder="Net Total" title="Net Total" readonly>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
								<small>&nbsp;</small>
								<input class="form-control" type="text" name="due_date" id="due_date" placeholder="Due Date" title="Due Date" value="<?= date('d-m-Y') ?>">
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
								<small>&nbsp;</small>
								<input class="form-control" type="text" name="balance_date" id="balance_date" value="<?= date('d-m-Y') ?>" placeholder="Booking Date" title="Booking Date" onchange="check_valid_date(this.id);get_auto_values('balance_date','visa_issue_amount','payment_mode','service_charge','markup','save','true','service_charge',true);">
							</div>
						</div>

					</div>

					<div class="panel panel-default panel-body fieldset mg_tp_30">
						<legend>Advance Details</legend>

						<div class="row">
							<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
								<input class="form-control" type="text" id="payment_date" name="payment_date" placeholder="*Date" title="Date" value="<?= date('d-m-Y') ?>" onchange="check_valid_date(this.id)">
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
								<select class="form-control" name="payment_mode" id="payment_mode" title="Mode" onchange="payment_master_toggles(this.id, 'bank_name', 'transaction_id', 'bank_id');get_identifier_block('identifier','payment_mode','credit_card_details','credit_charges');get_credit_card_charges('identifier','payment_mode','payment_amount','credit_card_details','credit_charges')">
									<?php get_payment_mode_dropdown(); ?>
								</select>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
								<input class="form-control" type="text" id="payment_amount" name="payment_amount" placeholder="*Payment Amount" title="Payment Amount" onchange="validate_balance(this.id);payment_amount_validate(this.id,'payment_mode','bank_name','transaction_id','bank_id');get_credit_card_charges('identifier','payment_mode','payment_amount','credit_card_details','credit_charges');">
							</div>
						</div>
						<div class="row mg_bt_10">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<input class="hidden form-control" type="text" id="credit_charges" name="credit_charges" title="Credit card charges" disabled>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<select class="hidden form-control" id="identifier" onchange="get_credit_card_data('identifier','payment_mode','credit_card_details')" title="Identifier(4 digit)" required>
									<option value=''>Select Identifier</option>
								</select>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<input class="hidden form-control" type="text" id="credit_card_details" name="credit_card_details" title="Credit card details" disabled>
							</div>
						</div>
						<div class="row mg_bt_10">
							<div class="col-md-4 col-sm-6 col-xs-12 mg_bt_10">
								<input class="form-control bank_suggest" type="text" id="bank_name" name="bank_name" placeholder="Bank Name" title="Bank Name" readonly>
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
								<span class="note" style="color: red;line-height: 35px;" data-original-title="" title=""><?= $txn_feild_note ?></span>
							</div>
						</div>

					</div>

					<div class="row text-center">
						<div class="col-md-12">
							<button id="btn_visa_master_save" class="btn btn-sm btn-success"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>


<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>

<script>
$('#visa_save_modal').modal('show');
$("#service").tagsinput('items');
$('#payment_date, #due_date,#balance_date').datetimepicker({
	timepicker: false,
	format: 'd-m-Y'
});
var date = new Date();
var yest = date.setDate(date.getDate() - 1);
var tom = date.setDate(date.getDate() + 1);

$('#birth_date1').datetimepicker({
	timepicker: false,
	maxDate: yest,
	format: 'd-m-Y'
});
$('#issue_date1').datetimepicker({
	timepicker: false,
	format: 'd-m-Y'
});
$('#appointment1,#expiry_date1').datetimepicker({
	timepicker: false,
	minDate: tom,
	format: 'd-m-Y'
});
$('#customer_id').select2();

function business_rule_load() {
	get_auto_values('balance_date', 'visa_issue_amount', 'payment_mode', 'service_charge', 'markup', 'save', 'true', 'service_charge');
}

	$(function() {

		$('#frm_visa_save').validate({
			rules: {
				customer_id: {
					required: true
				},
				visa_issue_amount: {
					required: true,
					number: true
				},
				service: {
					required: true,
					number: true
				},
				service_charge: {
					required: true,
					number: true
				},
				markup: {
					required: true,
					number: true
				},
				visa_total_cost: {
					required: true,
					number: true
				},
				balance_date: {
					required: true
				},
				payment_date: {
					required: true
				},
				payment_amount: {
					required: true,
					number: true
				},
				payment_mode: {
					required: true
				},
				bank_id: {
					required: function() {
						if ($('#payment_mode').val() != "Cash") {
							return true;
						} else {
							return false;
						}
					}
				},
				tax_apply_on : { required:true},
				tax_value : { required:true},
				markup_tax_value : { required:true}
			},
			submitHandler: function(form, e) {
				e.preventDefault();
				$('#btn_visa_master_save').prop('disabled', true);
				var base_url = $('#base_url').val();
				var customer_id = $('#customer_id').val();
				var cust_first_name = $('#cust_first_name').val();
				var cust_middle_name = $('#cust_middle_name').val();
				var cust_last_name = $('#cust_last_name').val();
				var gender = $('#cust_gender').val();
				var cust_birth_date = $('#cust_birth_date').val();
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
				var credit_charges = $('#credit_charges').val();
				var credit_card_details = $('#credit_card_details').val();

				var credit_amount = $('#credit_amount').val();
				var emp_id = $('#emp_id').val();
				var misc_issue_amount = $('#visa_issue_amount').val();
				var service_charge = $('#service_charge').val();
				var markup = $('#markup').val();
				var service_tax_subtotal = $('#service_tax_subtotal').val();
				var service_tax_markup = $('#service_tax_markup').val();
				var roundoff = $('#roundoff').val();
				var misc_total_cost = $('#visa_total_cost').val();
				var due_date = $('#due_date').val();
				var balance_date = $('#balance_date').val();

				var payment_date = $('#payment_date').val();
				var payment_amount = $('#payment_amount').val();
				var payment_mode = $('#payment_mode').val();
				var bank_name = $('#bank_name').val();
				var transaction_id = $('#transaction_id').val();
				var bank_id = $('#bank_id').val();
				var service = $('#service').val();
				var narration = $('#narration').val();
				var misc_sc = $('#misc_sc').val();
				var misc_markup = $('#misc_markup').val();
				var misc_taxes = $('#misc_taxes').val();
				var misc_markup_taxes = $('#misc_markup_taxes').val();
				var tax_apply_on = $('#tax_apply_on').val();
				var tax_value = $('#tax_value').val();
				var markup_tax_value = $('#markup_tax_value').val();
				var reflections = [];
				reflections.push({
					'misc_sc': misc_sc,
					'misc_markup': misc_markup,
					'misc_taxes': misc_taxes,
					'misc_markup_taxes': misc_markup_taxes,
					'tax_apply_on':tax_apply_on,
					'tax_value':tax_value,
					'markup_tax_value':markup_tax_value
				});
				var bsmValues = [];
				bsmValues.push({
					"basic": $('#basic_show').find('span').text(),
					"service": $('#service_show').find('span').text(),
					"markup": $('#markup_show').find('span').text()
				});
				if (service == '') {
					error_msg_alert("Enter Services in Service details!");
					$('#btn_visa_master_save').prop('disabled', false);
					return false;
				}

				if(payment_mode=="Advance"){
					error_msg_alert("Please select another payment mode.");
					$('#btn_visa_master_save').prop('disabled',false);
					return false;
				}
				if (payment_mode == "Credit Note" && credit_amount != '') {
					if (parseFloat(payment_amount) > parseFloat(credit_amount)) {
						error_msg_alert('Credit Note Balance is not available');
						$('#btn_visa_master_save').prop('disabled', false);
						return false;
					}
				} else if (payment_mode == "Credit Note" && credit_amount == '') {
					error_msg_alert("Credit Note Balance is not available");
					$('#btn_visa_master_save').prop('disabled', false);
					return false;
				}

				if (parseFloat(payment_amount) > parseFloat(misc_total_cost)) {
					error_msg_alert("Payment amount cannot be greater than selling amount.");
					$('#btn_visa_master_save').prop('disabled', false);
					return false;
				}
				var first_name_arr = new Array();
				var middle_name_arr = new Array();
				var last_name_arr = new Array();
				var birth_date_arr = new Array();
				var adolescence_arr = new Array();
				var passport_id_arr = new Array();
				var issue_date_arr = new Array();
				var expiry_date_arr = new Array();


				var table = document.getElementById("tbl_dynamic_miscellaneous");
				var rowCount = table.rows.length;
				var checked_count = 0;
				for (var i = 0; i < rowCount; i++) {
				var row = table.rows[i];
				if (row.cells[0].childNodes[0].checked) {
					checked_count++;
				}
				}
				if(checked_count==0){
				error_msg_alert("Atleast one passenger is required!");
					$('#btn_visa_master_save').prop('disabled', false);
					return false;
				}
				for (var i = 0; i < rowCount; i++) {
					
					var row = table.rows[i];
					if (row.cells[0].childNodes[0].checked) {

						var first_name = row.cells[2].childNodes[0].value;
						var middle_name = row.cells[3].childNodes[0].value;
						var last_name = row.cells[4].childNodes[0].value;
						var birth_date = row.cells[5].childNodes[0].value;
						var adolescence = row.cells[6].childNodes[0].value;
						var passport_id = row.cells[7].childNodes[0].value;
						var issue_date = row.cells[8].childNodes[0].value;
						var expiry_date = row.cells[9].childNodes[0].value;
						var msg = "";


						if (first_name == "") {
							msg += "First name is required in row:" + (i + 1) + "<br>";
						}
						if (passport_id != "") {
							if (issue_date == "") {
								msg += "Issue Date is required in row:" + (i + 1) + "<br>";
							}
							if (expiry_date == "") {
								msg += "Expiry Date is required in row:" + (i + 1) + "<br>";
							}

						}
						if (msg != "") {
							error_msg_alert(msg);
							$('#btn_visa_master_save').prop('disabled', false);
							return false;
						}

						first_name_arr.push(first_name);
						middle_name_arr.push(middle_name);
						last_name_arr.push(last_name);
						birth_date_arr.push(birth_date);
						adolescence_arr.push(adolescence);
						passport_id_arr.push(passport_id);
						issue_date_arr.push(issue_date);
						expiry_date_arr.push(expiry_date);
					}
				}
				$('#btn_visa_master_save').button('loading');
			//Validation for booking and payment date in login financial year						
			var check_date1 = $('#balance_date').val();
			$.post(base_url + 'view/load_data/finance_date_validation.php', {
				check_date: check_date1
			}, function(data) {
				if (data !== 'valid') {
					$('#btn_visa_master_save').button('reset');
					error_msg_alert("The Booking date does not match between selected Financial year.");
					$('#btn_visa_master_save').prop('disabled', false);
					return false;
				} else {
					var payment_date = $('#payment_date').val();
					$.post(base_url + 'view/load_data/finance_date_validation.php', {
						check_date: payment_date
					}, function(data) {
						if (data !== 'valid') {
							$('#btn_visa_master_save').button('reset');
							error_msg_alert("The Payment date does not match between selected Financial year.");
							$('#btn_visa_master_save').prop('disabled', false);
							return false;
						} else {
							//New Customer save
							if (customer_id == '0') {
								$.ajax({
									type: 'post',
									url: base_url + 'controller/customer_master/customer_save.php',
									data: {
										first_name: cust_first_name,
										middle_name: cust_middle_name,
										last_name: cust_last_name,
										gender: gender,
										birth_date: cust_birth_date,
										age: age,
										contact_no: contact_no,
										email_id: email_id,
										address: address,
										address2: address2,
										city: city,
										active_flag: active_flag,
										service_tax_no: service_tax_no,
										landline_no: landline_no,
										alt_email_id: alt_email_id,
										company_name: company_name,
										cust_type: cust_type,
										state: state,
										branch_admin_id: branch_admin_id,
										country_code: country_code
									},
									success: function(result) {
										var error_arr = result.split('--');
										if (error_arr[0] == 'error') {
											error_msg_alert(error_arr[1]);
											$('#btn_visa_master_save').button('reset');
											$('#btn_visa_master_save').prop('disabled', false);
											return false;
										} else {
											saveData();
										}
									}
								});
							} else {
								saveData();
							}

							function saveData() {
								$('#btn_visa_master_save').button('loading');
								//Visa Save
								$.post(base_url +'controller/miscellaneous/miscellaneous_master_save.php',
								{
									emp_id: emp_id,
									customer_id: customer_id,
									misc_issue_amount: misc_issue_amount,
									service_charge: service_charge,
									markup: markup,
									service_tax_subtotal: service_tax_subtotal,
									service_tax_markup: service_tax_markup,
									misc_total_cost: misc_total_cost,
									payment_date: payment_date,
									payment_amount: payment_amount,
									payment_mode: payment_mode,
									bank_name: bank_name,
									transaction_id: transaction_id,
									first_name_arr: first_name_arr,
									middle_name_arr: middle_name_arr,
									last_name_arr: last_name_arr,
									birth_date_arr: birth_date_arr,
									adolescence_arr: adolescence_arr,
									passport_id_arr: passport_id_arr,
									issue_date_arr: issue_date_arr,
									expiry_date_arr: expiry_date_arr,
									bank_id: bank_id,
									due_date: due_date,
									balance_date: balance_date,
									branch_admin_id: branch_admin_id,
									narration: narration,
									service: service,
									reflections: reflections,
									bsmValues: bsmValues,
									roundoff: roundoff,
									credit_charges: credit_charges,
									credit_card_details: credit_card_details
								},
								function (data) {
									var msg = data.split('-');

									if (msg[0] == 'error') {
										error_msg_alert(msg[1]);
										$('#btn_visa_master_save').prop('disabled', false);
										$('#btn_visa_master_save').button('reset');
										return false;
									} else {
										var msg1 = data.split('-');
										$('#btn_visa_master_save').prop('disabled', false);
										$('#btn_visa_master_save').button('reset');
										reset_form('frm_visa_save');
										$('#visa_save_modal').modal('hide');
										success_msg_alert(msg1[0]);
										visa_customer_list_reflect();
										window.open(base_url+'view/vendor/dashboard/estimate/estimate_save_modal.php?type=Miscellaneous&amount='+misc_issue_amount+'&booking_id='+msg1[1]);
										setTimeout(() => {
											if ($('#whatsapp_switch').val() == "on") whatsapp_send(emp_id, customer_id, balance_date, base_url, country_code+contact_no,email_id);
										}, 1000);
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