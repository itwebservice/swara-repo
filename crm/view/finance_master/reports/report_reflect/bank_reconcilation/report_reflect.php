<?php
include "../../../../../model/model.php";
$branch_admin_id = $_SESSION['branch_admin_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_status = $_POST['branch_status'];
$filter_date = $_POST['filter_date'];
$bank_id = $_POST['bank_id'];

$query = "SELECT * FROM `bank_reconcl_master` where 1 ";
if($bank_id != ''){
	$query .= " and bank_id ='$bank_id'";
}
if($filter_date != ''){
	$filter_date = get_date_db($filter_date);
	$query .= " and reconcl_date <='$filter_date'";
}
if($branch_status=='yes'){
    $query .= " and branch_admin_id = '$branch_admin_id'";
}
$sq_query = mysqlQuery($query);
$array_s = array();
$temp_arr = array();
$count = 1;
while($row_query = mysqli_fetch_assoc($sq_query)){

	$sq_bank = mysqli_fetch_assoc(mysqlQuery("select * from bank_master where bank_id='$row_query[bank_id]'"));

	$temp_arr = array( "data" => array(
		(int)($count++),
		$sq_bank['bank_name'].'('.$sq_bank['branch_name'].')',
		get_date_user($row_query['reconcl_date']),
		$row_query['book_balance'] ,
		$row_query['cheque_deposit'],
		$row_query['cheque_payment'],
		$row_query['bank_debit_amount'],
		$row_query['bank_credit_amount'],
		'<button class="btn btn-info btn-sm" onclick="display_modal('.$row_query['id'] .')" data-toggle="tooltip" title="View Details" id="adminv-'.$row_query['id'] .'"><i class="fa fa-eye"></i></button></td>',
		$row_query['reconcl_amount'],
		$row_query['bank_book_balance'],
		$row_query['diff_amount'],
		), "bg" =>'');
		array_push($array_s, $temp_arr);
}
echo json_encode($array_s);
?>