<?php 
include "../../../model/model.php";
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_POST['branch_status'];
$array_s = array();
$temp_arr = array();

$count = 0;
$query = "select * from sms_group_master where 1";
if($branch_status=='yes' && $role=='Branch Admin'){
	$query .=" and branch_admin_id = '$branch_admin_id'";
}
	
$sq_sms_group = mysqlQuery($query);
while($row_sms_group = mysqli_fetch_assoc($sq_sms_group)){

	$temp_arr = array( "data" => array(

		(int)(++$count),
		$row_sms_group['sms_group_name'],
		'<button class="btn btn-info btn-sm" onclick="sms_group_edit_modal(\''.$row_sms_group['sms_group_id'] .'\')" data-toggle="tooltip" id="sms_btn-'.$row_sms_group['sms_group_id'] .'" title="Update Details"><i class="fa fa-pencil-square-o"></i></button>'	
		), "bg" =>'');
	array_push($array_s,$temp_arr);
	
}
echo json_encode($array_s);
?>
	
