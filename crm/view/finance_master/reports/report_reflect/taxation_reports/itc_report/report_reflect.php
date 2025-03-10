<?php
include "../../../../../../model/model.php";
include_once('../itc_report/vendor_generic_functions.php');
// include '../../../../../../view/vendor_login/view/bookings/vendor_generic_functions.php';

$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$branch_status = $_POST['branch_status'];
$branch_admin_id = $_POST['branch_admin_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$emp_id = $_SESSION['emp_id'];
$array_s = array();
$temp_arr = array();
$tax_total = 0;
$query = "select * from vendor_estimate where status='' and delete_status='0' ";
if($from_date !='' && $to_date != ''){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);
	$query .= " and purchase_date between '$from_date' and '$to_date' ";
}
include "../../../../../../model/app_settings/branchwise_filteration.php";
$sq_query = mysqlQuery($query);
$sq_setting = mysqli_fetch_assoc(mysqlQuery("select * from app_settings where setting_id='1'"));
$count = 1;
while($row_query = mysqli_fetch_assoc($sq_query))
{
	$bg = ($row_query['purchase_return'] == '2') ? 'warning' : '';
	$estimate_type_val = get_estimate_type_name($row_query['estimate_type'], $row_query['estimate_type_id']);
	$vendor_name = get_vendor_name($row_query['vendor_type'],$row_query['vendor_type_id']);
	$vendor_info = get_vendor_info($row_query['vendor_type'], $row_query['vendor_type_id']);
	if($row_query['estimate_type'] == 'B2C'||$row_query['estimate_type'] == 'B2B'){

		$sq_b2c = mysqli_fetch_assoc(mysqlQuery("select service from b2c_sale where booking_id='$row_query[estimate_type_id]'"));
		$service_sac = ($sq_b2c['service'] == 'Holiday') ? "Package Tour" : "Group Tour";
		$hsn_code = get_service_info($service_sac);
	}else{
		$hsn_code = get_service_info($row_query['estimate_type']);
	}

	$sq_state = mysqli_fetch_assoc(mysqlQuery("select * from state_master where id='$vendor_info[state_id]'"));
	$sq_supply = mysqli_fetch_assoc(mysqlQuery("select * from state_master where id='$sq_setting[state_id]'"));

	//Service tax
	$tax_per = 0;
	$service_tax_amount = 0;
	$tax_name = 'NA';
	if($row_query['service_tax_subtotal'] !== 0.00 && ($row_query['service_tax_subtotal']) !== ''){
		$service_tax_subtotal1 = explode(',',$row_query['service_tax_subtotal']);
		$tax_name = '';
		for($i=0;$i<sizeof($service_tax_subtotal1);$i++){
			$service_tax = explode(':',$service_tax_subtotal1[$i]);
			$service_tax_amount +=  $service_tax[2];
			$tax_name .= $service_tax[0] . $service_tax[1].' ';
			$tax_per += str_replace( array('(',')', '%'),'', $service_tax[1]);
		}
	}
	//Taxable amount
	$taxable_amount = ($tax_per!=0) ? ($service_tax_amount / $tax_per) * 100 : 0;
	$tax_total += $service_tax_amount;
	$temp_arr = array( "data" => array(
		(int)($count++),
		$row_query['estimate_type'] ,
		$hsn_code ,
		$vendor_name ,
		($vendor_info['service_tax'] == '') ? 'NA' : strtoupper($vendor_info['service_tax']),
		($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ,
		$row_query['estimate_id'].' ('.$estimate_type_val.')' ,
		get_date_user($row_query['purchase_date']) ,
		($vendor_info['service_tax'] == '') ? 'Unregistered' : 'Registered',
		($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'],
		$row_query['net_total'],
		number_format($taxable_amount,2),
		$tax_name,
		number_format($service_tax_amount,2),
		"0.00" ,
		"0.00",
		'',
		''
		), "bg" =>$bg);
	array_push($array_s,$temp_arr);
}
//Expense Booking
$query = "select * from other_expense_master where 1 and delete_status='0'";
if($from_date !='' && $to_date != ''){
	$from_date = get_date_db($from_date);
	$to_date = get_date_db($to_date);
	$query .= " and expense_date between '$from_date' and '$to_date' ";
}
$sq_query = mysqlQuery($query);
while($row_query = mysqli_fetch_assoc($sq_query))
{
	$tax_amount = 0;
	$taxable_amount = $row_query['amount'];
	$sq_income_type_info = mysqli_fetch_assoc(mysqlQuery("select * from ledger_master where ledger_id='$row_query[expense_type_id]'"));
	$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from other_vendors where vendor_id='$row_query[supplier_id]'"));

	$sq_state = mysqli_fetch_assoc(mysqlQuery("select * from state_master where id='$sq_customer[state_id]'"));
	$sq_supply = mysqli_fetch_assoc(mysqlQuery("select * from state_master where id='$sq_setting[state_id]'"));
	$sq_sac = get_service_info('Other Expense');

	$tax_amount = $row_query['service_tax_subtotal'];
	$ledgers = explode(',',$row_query['ledgers']);
	$sq_tax_name1 = mysqli_fetch_assoc(mysqlQuery("select ledger_name from ledger_master where ledger_id ='$ledgers[0]'"));
	$ledger_name = $sq_tax_name1['ledger_name'];
	$tax_total += $tax_amount;
	//For second selected ledger
	if(isset($ledgers[1]) && $ledger_name!=''){

		$sq_tax_name2 = mysqli_fetch_assoc(mysqlQuery("select ledger_name from ledger_master where ledger_id ='$ledgers[1]'"));
		$ledger_name .= ','.$sq_tax_name2['ledger_name'];
	}
	$temp_arr = array( "data" => array(
		(int)($count++),
		$sq_income_type_info['ledger_name'] ,
		$sq_sac ,
		$sq_customer['vendor_name'] ,
		($sq_customer['service_tax_no'] == '') ? 'NA' : strtoupper($sq_customer['service_tax_no']),
		($sq_state['state_name'] == '') ? 'NA' : $sq_state['state_name'] ,
		$row_query['expense_id'] ,
		get_date_user($row_query['expense_date']) ,
		($sq_customer['service_tax_no'] == '') ? 'Unregistered' : 'Registered' ,
		($sq_supply['state_name'] == '') ? 'NA' : $sq_supply['state_name'] ,
		$row_query['total_fee'],
		number_format($taxable_amount,2) ,
		$ledger_name,
		number_format($tax_amount,2),
		"0.00" ,
		"0.00",
		"",
		""
		), "bg" =>'');
	array_push($array_s,$temp_arr);	
} 
	
$footer_data = array("footer_data" => array(
	'total_footers' => 4,
	
	'foot0' => 'Total TAX :'.number_format($tax_total,2),
	'col0' => 14,
	'class0' =>"info text-right",

	'foot1' => '',
	'col1' => 1,
	'class1' =>"info text-left",

	'foot2' => '',
	'col2' => 2,
	'class2' =>"info text-left",

	'foot3' => '',
	'col3' => 10,
	'class3' =>"info text-left"
	)
);
array_push($array_s, $footer_data);
echo json_encode($array_s);
?>
