<?php
include "../../../model/model.php";
$customer_id = $_POST['customer_id'];
$info_arr = array();
$total_amount = 0;

if($customer_id != '' || $customer_id != 0){
	//Customer Info
	$sq_cust_info = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));

	$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust_info['contact_no'], $secret_key);
	$email_id = $encrypt_decrypt->fnDecrypt($sq_cust_info['email_id'], $secret_key);

	$info_arr['first_name'] = $sq_cust_info['first_name'];
	$info_arr['middle_name'] = $sq_cust_info['middle_name'];
	$info_arr['last_name'] = $sq_cust_info['last_name'];	
	$info_arr['address'] = $sq_cust_info['address'];	
	$info_arr['contact_no'] = $contact_no;
	$info_arr['email_id'] = $email_id;
	$info_arr['company_name'] = $sq_cust_info['company_name'];

	//Credit Note Info
	$sq_credit_note = mysqlQuery("select * from credit_note_master where customer_id='$customer_id'");
	while($row = mysqli_fetch_assoc($sq_credit_note)){
		$total_amount += $row['payment_amount'];
	}
		
	$info_arr['payment_amount'] = $total_amount;
}
else{
	$info_arr['first_name'] ='';
	$info_arr['middle_name'] = '';
	$info_arr['last_name'] = '';	
	$info_arr['address'] = '';	
	$info_arr['contact_no'] ='';
	$info_arr['email_id'] = '';
	$info_arr['company_name'] = '';
	$info_arr['city'] = '';
	$info_arr['state_id'] = '';
}

echo json_encode($info_arr);
?>