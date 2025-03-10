<?php

include "../../../../model/model.php";



$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : '';

$booking_id = $_POST['booking_id'];

$from_date = $_POST['from_date'];

$to_date = $_POST['to_date'];

$cust_type = isset($_POST['cust_type']) ? $_POST['cust_type'] : '';

$company_name = isset($_POST['company_name']) ? $_POST['company_name'] : '';
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_POST['financial_year_id'];
$branch_status = isset($_POST['branch_status']) ? $_POST['branch_status'] : '';


$query = "select * from bus_booking_master where financial_year_id='$financial_year_id' and delete_status='0' ";

if($booking_id!=""){

	$query .= " and booking_id='$booking_id'";

}

if($customer_id!=""){

	$query .= " and customer_id='$customer_id'";

}

if($from_date!='' && $to_date!=''){

			$from_date = get_date_db($from_date);

			$to_date = get_date_db($to_date);

			$query .=" and created_at between '$from_date' and '$to_date'";

}

if($cust_type != ""){

	$query .= " and customer_id in (select customer_id from customer_master where type = '$cust_type')";

}
if($role == "B2b"){

	$query .= " and emp_id='$emp_id'";

}


if($company_name != ""){

	$query .= " and customer_id in (select customer_id from customer_master where company_name = '$company_name')";

}
include "../../../../model/app_settings/branchwise_filteration.php";
$query .= " order by booking_id desc";


		$count = 0;
		$array_s = array();
		$temp_arr = array();
		$footer_data = array();
		$total_sale = 0;
		$total_cancelation_amount = 0;
		$total_balance = 0;
		$sq_booking = mysqlQuery($query);

		while($row_booking = mysqli_fetch_assoc($sq_booking)){
			$sq_emp =  mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id = '$row_booking[emp_id]'"));
			$emp_name = ($row_booking['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';

			$pass_count = mysqli_num_rows(mysqlQuery("select * from bus_booking_entries where booking_id='$row_booking[booking_id]'"));
			$cancel_count = mysqli_num_rows(mysqlQuery("select * from bus_booking_entries where booking_id='$row_booking[booking_id]' and status='Cancel'"));
			if($pass_count==$cancel_count){
				$bg="danger";
				$update_btn = '';
				$delete_btn = '';
			}
			else{
				$bg="";
				$update_btn = '<button data-toggle="tooltip" class="btn btn-info btn-sm" onclick="update_modal('.$row_booking['booking_id'].')" id="editb-'.$row_booking['booking_id'].'" title="Update Details"><i class="fa fa-pencil-square-o"></i></button>';
				$delete_btn = '<button class="'.$delete_flag.' btn btn-danger btn-sm" onclick="delete_entry('.$row_booking['booking_id'].')" title="Delete Entry"><i class="fa fa-trash"></i></button>';
			}

			$date = $row_booking['created_at'];
			$yr = explode("-", $date);
			$year =$yr[0];

			$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$row_booking[customer_id]'"));
			if($sq_customer['type'] == 'Corporate'||$sq_customer['type'] == 'B2B'){
				$customer_name = $sq_customer['company_name'];
			}else{
				$customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
			}

			$sq_bus = mysqli_fetch_assoc(mysqlQuery("select * from bus_booking_entries where booking_id='$row_booking[booking_id]'"));
			$sq_total_seates = mysqli_num_rows(mysqlQuery("select booking_id from bus_booking_entries where booking_id='$row_booking[booking_id]'")); 
			$sq_paid_amount = mysqli_fetch_assoc(mysqlQuery("SELECT sum(payment_amount) as sum,sum(`credit_charges`) as sumc from bus_booking_payment_master where booking_id='$row_booking[booking_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));
			$credit_card_charges = $sq_paid_amount['sumc'];

			$paid_amount = $sq_paid_amount['sum'] + $credit_card_charges;
			$paid_amount = ($paid_amount == '') ? 0 : $paid_amount;
			$sale_amount = ($row_booking['net_total'] + $credit_card_charges);
			
			$cancel_amt = $row_booking['cancel_amount'];
			if($cancel_amt == ""){ $cancel_amt = 0;}

			$total_sale = $total_sale+$row_booking['net_total'] + $credit_card_charges;
			$total_cancelation_amount = $total_cancelation_amount+$cancel_amt;
			$total_balance = $total_balance+$sale_amount-$cancel_amt;

			if($bg != ''){
				$bal_amount = ($paid_amount > $cancel_amt) ? 0 : floatval($cancel_amt) - floatval($paid_amount);
			}else{
				$bal_amount = floatval($row_booking['net_total']) - floatval($paid_amount) - $credit_card_charges;
			}

			$invoice_no = get_bus_booking_id($row_booking['booking_id'],$year);
			$booking_id = $row_booking['booking_id'];
			$invoice_date = date('d-m-Y',strtotime($row_booking['created_at']));
			$customer_id = $row_booking['customer_id'];
			$service_name = "Bus Invoice";

			//**Service tax
			$taxation_type = $row_booking['taxation_type'];
			$service_tax_per = $row_booking['service_tax'];
			$service_charge = $row_booking['service_charge'];
			$service_tax = $row_booking['service_tax_subtotal'];

			//**Basic Cost
			$basic_cost = $row_booking['basic_cost'];			
			$net_amount = $row_booking['net_total'];
			$sq_sac = mysqli_fetch_assoc(mysqlQuery("select * from sac_master where service_name='Bus'"));   
			$sac_code = $sq_sac['hsn_sac_code'];

			if($app_invoice_format == 4)
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/tax_invoice_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&service_charge=$service_charge&total_paid=$paid_amount&balance_amount=$bal_amount&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id&pass_count=$pass_count&credit_card_charges=$credit_card_charges";
			else
			$url1 = BASE_URL."model/app_settings/print_html/invoice_html/body/bus_body_html.php?invoice_no=$invoice_no&invoice_date=$invoice_date&customer_id=$customer_id&service_name=$service_name&basic_cost=$basic_cost&taxation_type=$taxation_type&service_tax_per=$service_tax_per&service_tax=$service_tax&net_amount=$net_amount&service_charge=$service_charge&total_paid=$paid_amount&balance_amount=$bal_amount&sac_code=$sac_code&branch_status=$branch_status&booking_id=$booking_id&credit_card_charges=$credit_card_charges&canc_amount=$cancel_amt&bg=$bg";
			$temp_arr = array( "data" => array(
				$row_booking['invoice_pr_id'],
				get_bus_booking_id($row_booking['booking_id'],$year),
				$customer_name,
				$sq_total_seates,
				$sq_bus['company_name'],
				number_format($row_booking['net_total']+$credit_card_charges,2),
				$cancel_amt,
				number_format(($row_booking['net_total']-$row_booking['cancel_amount']+$credit_card_charges), 2),
				$emp_name,
				$invoice_date,
				'<a data-toggle="tooltip" onclick="loadOtherPage(\'' .$url1 .'\')" class="btn btn-info btn-sm" title="Download Invoice"><i class="fa fa-print"></i></a>
				'.$update_btn.'<button data-toggle="tooltip" class="btn btn-info btn-sm" id="viewb-'.$row_booking['booking_id'].'" onclick="view_modal('.$row_booking['booking_id'] .')" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></button>'.$delete_btn
				), "bg" =>$bg );
				array_push($array_s,$temp_arr); 
			}
			$footer_data = array("footer_data" => array(
				'total_footers' => 5,
				'foot0' => "Total",
				'col0' => 5,
				'class0' => "text-right",
				'foot1' => number_format($total_sale, 2),
				'col1' => 1,	//colspan 
				'class1' => "info",
				'foot2' =>  number_format($total_cancelation_amount, 2),
				'col2' => 1,
				'class2' => "danger",
				'foot3' => number_format($total_balance, 2),
				'col3' => 1,
				'class3' => "success",
				'foot4' => '',
				'col4' => 3,
				'class4' => "",
				)
			);
			array_push($array_s, $footer_data);
		echo json_encode($array_s);	
?>