<?php
include "../../../../model/model.php";

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../../../../classes/PHPExcel-1.8/Classes/PHPExcel.php';

//This function generates the background color
function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
        )
    ));
}

//This array sets the font atrributes
$header_style_Array = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 12,
        'name'  => 'Verdana'
    ));
$table_header_style_Array = array(
    'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
        'size'  => 11,
        'name'  => 'Verdana'
    ));
$content_style_Array = array(
    'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
        'size'  => 9,
        'name'  => 'Verdana'
    ));

//This is border array
$borderArray = array(
          'borders' => array(
              'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
              )
          )
      );

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                             ->setLastModifiedBy("Maarten Balliauw")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Test result file");

//////////////////////////****************Content start**************////////////////////////////////

$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$branch_status = $_GET['branch_status'];
$customer_id = $_GET['customer_id'];
$train_ticket_id = $_GET['train_ticket_id'];
$payment_from_date = $_GET['payment_from_date'];
$payment_to_date = $_GET['payment_to_date'];
$cust_type = $_GET['cust_type'];
$company_name = (isset($_GET['company_name'])) ? $_GET['company_name'] : '';
$booker_id = $_GET['booker_id'];
$branch_id = $_GET['branch_id'];

if($customer_id!=""){
    $sq_customer_info = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
	if($sq_customer_info['type'] == 'Corporate'||$sq_customer_info['type']=='B2B'){
		$cust_name = $sq_customer_info['company_name'];
	}else{
		$cust_name = $sq_customer_info['first_name'].' '.$sq_customer_info['last_name'];
	}
}
else{
    $cust_name = "";
}

if($payment_from_date!="" && $payment_to_date!=""){
    $date_str = $payment_from_date.' to '.$payment_to_date;
}
else{
    $date_str = "";
}       

if($train_ticket_id!=""){
    $sql_booking_date = mysqli_fetch_assoc(mysqlQuery("select * from train_ticket_master where train_ticket_id = '$train_ticket_id' and delete_status='0'")) ;
    $booking_date = $sql_booking_date['created_at'];
    $yr = explode("-", $booking_date);
    $year = $yr[0];
    $invoice_id = get_train_ticket_booking_id($train_ticket_id,$year);
}
else{
    $invoice_id = '';
}

if($company_name == 'undefined') { $company_name = ''; }

if($booker_id != '')
{
    $sq_emp = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id='$booker_id'"));
    if($sq_emp['first_name'] == '') { $emp_name='Admin';}
    else{ $emp_name = $sq_emp['first_name'].' '.$sq_emp['last_name']; }
}

if($branch_id != '') { 
    $sq_branch = mysqli_fetch_assoc(mysqlQuery("select * from branches where branch_id='$branch_id'"));
    $branch_name = $sq_branch['branch_name']==''?'NA':$sq_branch['branch_name'];
}
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', 'Report Name')
            ->setCellValue('C2', 'Train Ticket Summary')
            ->setCellValue('B3', 'Booking ID')
            ->setCellValue('C3', $invoice_id)
            ->setCellValue('B4', 'Customer')
            ->setCellValue('C4', $cust_name)
            ->setCellValue('B5', 'From-To Date')
            ->setCellValue('C5', $date_str)
            ->setCellValue('B6', 'Customer Type')
            ->setCellValue('C6', $cust_type)
            ->setCellValue('B7', 'Company Name')
            ->setCellValue('C7', $company_name)
            ->setCellValue('B8', 'Booked By')
            ->setCellValue('C8', $emp_name)
            ->setCellValue('B9', 'Branch')
            ->setCellValue('C9', $branch_name);

$objPHPExcel->getActiveSheet()->getStyle('B2:C2')->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B2:C2')->applyFromArray($borderArray);    

$objPHPExcel->getActiveSheet()->getStyle('B3:C3')->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B3:C3')->applyFromArray($borderArray);    

$objPHPExcel->getActiveSheet()->getStyle('B4:C4')->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B4:C4')->applyFromArray($borderArray); 

$objPHPExcel->getActiveSheet()->getStyle('B5:C5')->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B5:C5')->applyFromArray($borderArray);    

$objPHPExcel->getActiveSheet()->getStyle('B6:C6')->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B6:C6')->applyFromArray($borderArray);

$objPHPExcel->getActiveSheet()->getStyle('B7:C7')->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B7:C7')->applyFromArray($borderArray);

$objPHPExcel->getActiveSheet()->getStyle('B8:C8')->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B8:C8')->applyFromArray($borderArray); 

$objPHPExcel->getActiveSheet()->getStyle('B9:C9')->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B9:C9')->applyFromArray($borderArray); 

$query = "select * from train_ticket_master where 1 and delete_status='0' ";
if($customer_id!=""){
    $query .= " and customer_id='$customer_id'";
}
if($from_date!="" && $to_date!=""){
    $from_date = get_date_db($from_date);
    $to_date = get_date_db($to_date);
    $query .= " and created_at between '$from_date' and '$to_date'";
}
if($train_ticket_id!="")
{
    $query .=" and train_ticket_id='$train_ticket_id'";
}
if($cust_type != ""){
    $query .= " and customer_id in (select customer_id from customer_master where type = '$cust_type')";
}
if($company_name != ""){
    $query .= " and customer_id in (select customer_id from customer_master where company_name = '$company_name')";
}
if($booker_id!=""){
    $query .= " and emp_id='$booker_id'";
}
if($branch_id!=""){
    $query .= " and emp_id in(select emp_id from emp_master where branch_id = '$branch_id')";
}
if($branch_status=='yes' && $role!='Admin'){
    $query .= " and branch_admin_id = '$branch_admin_id'";
}
elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
$query .= " and emp_id='$emp_id'";
}
$query .= " order by train_ticket_id desc";
$row_count = 11;
$vendor_name1 = '';

$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$row_count, "Sr. No")
                ->setCellValue('C'.$row_count, "Booking ID")
                ->setCellValue('D'.$row_count, "Customer_Name")
                ->setCellValue('E'.$row_count, "Mobile")
                ->setCellValue('F'.$row_count, "EMAIL_ID")
                ->setCellValue('G'.$row_count, "Total_pax")
                ->setCellValue('H'.$row_count, "Trip_Type")
                ->setCellValue('I'.$row_count, "Booking_Date")
                ->setCellValue('J'.$row_count, "Basic_Amount")
                ->setCellValue('K'.$row_count, "Service_Charge") 
                ->setCellValue('L'.$row_count, "Delivery_Charges")  
                ->setCellValue('M'.$row_count, "Tax")
                ->setCellValue('N'.$row_count, "Credit card charges")
                ->setCellValue('O'.$row_count, "Sale")
                ->setCellValue('P'.$row_count, "Cancel")
                ->setCellValue('Q'.$row_count, "Total")
                ->setCellValue('R'.$row_count, "Paid")
                ->setCellValue('S'.$row_count, "Outstanding Balance")
                ->setCellValue('T'.$row_count, "Due_Date")
                ->setCellValue('U'.$row_count, "Purchase")
                ->setCellValue('V'.$row_count, "Purchased_From")
                ->setCellValue('W'.$row_count, "Branch")
                ->setCellValue('X'.$row_count, "Booked_By")
                ->setCellValue('Y'.$row_count, "Incentive");

$objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':Y'.$row_count)->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':Y'.$row_count)->applyFromArray($borderArray); 

$row_count++;
$count = 0;
        $total_balance=0;
        $total_refund=0;    
        $cancel_total =0;
        $sale_total = 0;
        $paid_total = 0;
        $balance_total = 0;
        $sq_ticket = mysqlQuery($query);
        while($row_ticket = mysqli_fetch_assoc($sq_ticket)){

            $pass_count = mysqli_num_rows(mysqlQuery("select * from  train_ticket_master_entries where train_ticket_id='$row_ticket[train_ticket_id]'"));
            $cancel_count = mysqli_num_rows(mysqlQuery("select * from  train_ticket_master_entries where train_ticket_id='$row_ticket[train_ticket_id]' and status='Cancel'"));
            if($pass_count==$cancel_count) 	{
                $bg="danger";
            }
            else  {
                $bg="#fff";
            }
            $date = $row_ticket['created_at'];
            $yr = explode("-", $date);
            $year =$yr[0];
            $sq_customer_info = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$row_ticket[customer_id]'"));
            $contact_no = $encrypt_decrypt->fnDecrypt($sq_customer_info['contact_no'], $secret_key);
            $email_id = $encrypt_decrypt->fnDecrypt($sq_customer_info['email_id'], $secret_key);
            if($sq_customer_info['type']=='Corporate'||$sq_customer_info['type'] == 'B2B'){
                $customer_name = $sq_customer_info['company_name'];
            }else{
                $customer_name = $sq_customer_info['first_name'].' '.$sq_customer_info['last_name'];
            }
        
            $sq_emp = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id='$row_ticket[emp_id]'"));
            if($sq_emp['first_name'] == '') { $emp_name='Admin';}
            else{ $emp_name = $sq_emp['first_name'].' '.$sq_emp['last_name']; }
        
            $sq_branch = mysqli_fetch_assoc(mysqlQuery("select * from branches where branch_id='$sq_emp[branch_id]'"));
            $branch_name = $sq_branch['branch_name']==''?'NA':$sq_branch['branch_name'];
            $sq_total_member = mysqli_num_rows(mysqlQuery("select train_ticket_id from train_ticket_master_entries where train_ticket_id = '$row_ticket[train_ticket_id]'"));
        
            $sq_payment = mysqli_fetch_assoc(mysqlQuery("select sum(payment_amount) as sum_pay,sum(credit_charges) as sumc from train_ticket_payment_master where train_ticket_id='$row_ticket[train_ticket_id]' and clearance_status!='Pending' and clearance_status!='Cancelled'"));
            $credit_card_charges = $sq_payment['sumc'];
        
            $paid_amount = $sq_payment['sum_pay'];			
            $paid_amount = ($paid_amount == '')?0:$paid_amount;
            $total_sale = $row_ticket['net_total'];
            $cancel_amount = $row_ticket['cancel_amount'];
            if($cancel_amount == ""){$cancel_amount =0;}
            $total_bal = $total_sale - $cancel_amount;	
            
            if($pass_count == $cancel_count){
                if($paid_amount > 0){
                    if($cancel_amount >0){
                        if($paid_amount > $cancel_amount){
                            $bal = 0;
                        }else{
                            $bal = $cancel_amount - $paid_amount;
                        }
                    }else{
                    $bal = 0;
                    }
                }
                else{
                    $bal = $cancel_amount;
                }
            }
            else{
                $bal = $total_sale - $paid_amount;
            }	
            
            if($bal>=0){
                $total_balance = $total_balance+$bal;
            }
            else{
                $total_refund = $total_refund +abs($bal);
            }
        
            $due_date = ($row_ticket['payment_due_date'] == '1970-01-01') ? 'NA' : get_date_user($row_ticket['payment_due_date']);
            //Footer
            $cancel_total = $cancel_total + $cancel_amount;
            $sale_total = $sale_total + $total_bal;
            $paid_total = $paid_total + $paid_amount;
            $balance_total = $balance_total + $bal;
        
            /////// Purchase ////////
            $total_purchase = 0;
            $purchase_amt = 0;
            $i=0;
            $p_due_date = '';
            $sq_purchase_count = mysqli_num_rows(mysqlQuery("select * from vendor_estimate where status!='Cancel' and estimate_type='Train' and estimate_type_id='$row_ticket[train_ticket_id]' and delete_status='0'"));
            if($sq_purchase_count == 0){  $p_due_date = 'NA'; }
            $sq_purchase = mysqlQuery("select * from vendor_estimate where status!='Cancel' and estimate_type='Train' and estimate_type_id='$row_ticket[train_ticket_id]' and delete_status='0'");
            while($row_purchase = mysqli_fetch_assoc($sq_purchase)){	
                if($row_purchase['purchase_return'] == 0){
                    $total_purchase += $row_purchase['net_total'];
                }
                else if($row_purchase['purchase_return'] == 2){
                    $cancel_estimate = json_decode($row_purchase['cancel_estimate']);
                    $p_purchase = ($row_purchase['net_total'] - floatval($cancel_estimate[0]->net_total));
                    $total_purchase += $p_purchase;
                }
                $vendor_name = get_vendor_name_report($row_purchase['vendor_type'], $row_purchase['vendor_type_id']);
                if($vendor_name != ''){ $vendor_name1 .= $vendor_name.','; }
            }
            $vendor_name1 = substr($vendor_name1, 0, -1);
        
            // Invoice
            $paid_amount = ($paid_amount == '') ? 0 : $paid_amount;
            $sale_amount = $row_ticket['net_total'] - $row_ticket['cancel_amount'];
            $bal_amount = $sale_amount - $paid_amount;
        
            $cancel_amt = $row_ticket['cancel_amount'];
            if($cancel_amt == ""){ $cancel_amt = 0;}
            
            $total_sale = $row_ticket['net_total'];
            $total_cancelation_amount = $total_cancelation_amount+$cancel_amt;
            $total_balance = $total_balance+$sale_amount;	
        
            $invoice_no = get_train_ticket_booking_id($row_ticket['train_ticket_id'],$year);
            $invoice_date = date('d-m-Y',strtotime($row_ticket['created_at']));
            $customer_id = $row_ticket['customer_id'];
            $service_name = "Train Invoice";
            $train_ticket_id = $row_ticket['train_ticket_id'];
            $taxation_type = $row_ticket['taxation_type'];
            $service_tax_per = $row_ticket['service_tax'];
            $service_charge = $row_ticket['service_charge'];
            $service_tax = $row_ticket['service_tax_subtotal'];
            
            $basic_cost = $row_ticket['basic_fair'] - $row_ticket['cancel_amount'];
            $net_amount = $row_ticket['net_total'] - $row_ticket['cancel_amount'];
        
            $sq_sac = mysqli_fetch_assoc(mysqlQuery("select * from sac_master where service_name='Train'"));   
            $sac_code = $sq_sac['hsn_sac_code'];
        
            //Service Tax and Markup Tax
            $service_tax_amount = 0;
            if($row_ticket['service_tax_subtotal'] !== 0.00 && ($row_ticket['service_tax_subtotal']) !== ''){
                $service_tax_subtotal1 = explode(',',$row_ticket['service_tax_subtotal']);
                for($i=0;$i<sizeof($service_tax_subtotal1);$i++){
                $service_tax = explode(':',$service_tax_subtotal1[$i]);
                $service_tax_amount +=  $service_tax[2];
                }
            }
            $sq_incentive = mysqli_fetch_assoc(mysqlQuery("select * from booker_sales_incentive where booking_id='$row_ticket[train_ticket_id]' and service_type='Train Ticket Booking'"));
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B'.$row_count, ++$count)
            ->setCellValue('C'.$row_count, get_train_ticket_booking_id($row_ticket['train_ticket_id'],$year))
            ->setCellValue('D'.$row_count, $customer_name)
            ->setCellValue('E'.$row_count, $contact_no)
            ->setCellValue('F'.$row_count, $email_id)
            ->setCellValue('G'.$row_count, $sq_total_member)
            ->setCellValue('H'.$row_count, $row_ticket['type_of_tour'])
            ->setCellValue('I'.$row_count, get_date_user($row_ticket['created_at']))
            ->setCellValue('J'.$row_count, number_format($row_ticket['basic_fair'],2))
            ->setCellValue('K'.$row_count, number_format($row_ticket['service_charge'],2))
            ->setCellValue('L'.$row_count, number_format($row_ticket['delivery_charges'],2))
            ->setCellValue('M'.$row_count, number_format($service_tax_amount,2))
            ->setCellValue('N'.$row_count, number_format($credit_card_charges,2))
            ->setCellValue('O'.$row_count, number_format($total_sale,2))
            ->setCellValue('P'.$row_count, number_format($cancel_amt,2))
            ->setCellValue('Q'.$row_count, number_format($total_bal,2))
            ->setCellValue('R'.$row_count, number_format($paid_amount,2))
            ->setCellValue('S'.$row_count, number_format($bal,2))
            ->setCellValue('T'.$row_count, get_date_user($row_ticket['payment_due_date']))
            ->setCellValue('U'.$row_count, number_format($total_purchase,2))
            ->setCellValue('V'.$row_count, $vendor_name1)
            ->setCellValue('W'.$row_count, $branch_name)
            ->setCellValue('X'.$row_count, $emp_name)
            ->setCellValue('Y'.$row_count, number_format($sq_incentive['incentive_amount'],2));

        $objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':Y'.$row_count)->applyFromArray($content_style_Array);
    	$objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':Y'.$row_count)->applyFromArray($borderArray);    

    	$row_count++;

        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$row_count, "")
        ->setCellValue('C'.$row_count, "")
        ->setCellValue('D'.$row_count, "")
        ->setCellValue('E'.$row_count, "")
        ->setCellValue('F'.$row_count, "")
        ->setCellValue('G'.$row_count, "")
        ->setCellValue('H'.$row_count, "")
        ->setCellValue('J'.$row_count, "")
        ->setCellValue('K'.$row_count, "")
        ->setCellValue('L'.$row_count, "")
        ->setCellValue('M'.$row_count, "")
        ->setCellValue('N'.$row_count, "")
        ->setCellValue('O'.$row_count, "")
        ->setCellValue('P'.$row_count, 'TOTAL CANCEL : '.number_format($cancel_total,2))
        ->setCellValue('Q'.$row_count, 'TOTAL SALE :'.number_format($sale_total,2))
        ->setCellValue('R'.$row_count, 'TOTAL PAID : '.number_format($paid_total,2))
        ->setCellValue('S'.$row_count, 'TOTAL BALANCE :'.number_format($balance_total,2))
        ->setCellValue('T'.$row_count, "");

$objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':T'.$row_count)->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':T'.$row_count)->applyFromArray($borderArray);

        }
	
//////////////////////////****************Content End**************////////////////////////////////
	

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


for($col = 'A'; $col !== 'N'; $col++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
}


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="TrainTicketSummary('.date('d-m-Y H:i').').xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
