<?php
include "../../../../../model/model.php";
$year = $_POST['year'];
$month = $_POST['month'];
$emp_id = $_POST['emp_id'];
$branch_status =$_POST['branch_status'];
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];

$from_date = "1-$month-$year";
$from_date = get_date_db($from_date);
$days = date("t", strtotime($from_date));
$to_date = "$days-$month-$year";
$to_date = get_date_db($to_date);
$new = array();

$query = "select * from emp_master where 1 and active_flag!='Inactive'";
if($emp_id!=''){
  $query .= " and emp_id = '$emp_id'";
} 
if($branch_status=='yes' && $role!='Admin'){
    $query .=" and branch_id='$branch_admin_id'";
}
$sq_a = mysqlQuery($query);
while($row_emp = mysqli_fetch_assoc($sq_a)){
  
  $temp_arr = array(
    (int)($row_emp['emp_id']),
    $row_emp['first_name'].' '.$row_emp['last_name']);
    $i=0;
    while($i<$days) {

      $p_count=0;
      $a_count=0;
      $ot_count=0;
      $hd_count=0;
      $wfh_count=0;
      $ho_count=0;
      $wo_count=0;
      $j = $i+1;
      $query1 =mysqli_fetch_assoc(mysqlQuery( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and day(att_date)='$j'"));
    
      if(isset($query1['status'])){ $status = $query1['status'];}
      else{ $status = '-';}
      array_push($temp_arr, $status);
      $i++; 
    }
    $p_count =mysqli_num_rows(mysqlQuery( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Present'"));
    $a_count =mysqli_num_rows(mysqlQuery( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Absent'"));
    $ot_count =mysqli_num_rows(mysqlQuery( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='On Tour'"));
    $hd_count =mysqli_num_rows(mysqlQuery( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Half Day'"));
    $wfh_count =mysqli_num_rows(mysqlQuery( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Work From Home'"));
    $ho_count =mysqli_num_rows(mysqlQuery( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Holiday Off'"));
    $wo_count =mysqli_num_rows(mysqlQuery( "select * from employee_attendance_log where emp_id='$row_emp[emp_id]' and month(att_date)= '$month' and year(att_date) = '$year' and status='Weekly Off'"));     
    $temp2 = array(
      $p_count ,
      $a_count ,
      $ot_count ,
      $hd_count,
      $wfh_count,
      $ho_count,
      $wo_count
            );
    foreach($temp2 as $val)
      array_push($temp_arr, $val);
    array_push($new, $temp_arr);
}
echo json_encode($new);
?>