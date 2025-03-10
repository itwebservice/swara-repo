<?php

include "../../../model/model.php";

/** Error reporting */

error_reporting(E_ALL);

ini_set('display_errors', TRUE);

ini_set('display_startup_errors', TRUE);

date_default_timezone_set('Europe/London');



if (PHP_SAPI == 'cli')

  die('This example should only be run from a Web Browser');



/** Include PHPExcel */

require_once '../../../classes/PHPExcel-1.8/Classes/PHPExcel.php';



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
global $currency;
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_status = $_GET['branch_status'];
$emp_id = $_SESSION['emp_id'];
$customer_id = $_GET['customer_id'];

$booking_id = $_GET['booking_id'];

$from_date = $_GET['from_date'];

$to_date = $_GET['to_date'];

$cust_type = $_GET['cust_type'];

$company_name = (isset($_GET['company_name'])) ? $_GET['company_name'] : '';



if($customer_id!=""){

  $sq_customer_info = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
  if($sq_customer_info['type']=='Corporate'||$sq_customer_info['type'] == 'B2B'){
      $cust_name = $sq_customer_info['company_name'];
  }else{
      $cust_name = $sq_customer_info['first_name'].' '.$sq_customer_info['last_name'];
  }

}

else{

  $cust_name = "";

}

if($booking_id!=""){
  $query = mysqli_fetch_assoc(mysqlQuery("select * from package_tour_booking_master where booking_id='$booking_id' and delete_status='0'"));
  $date = $query['booking_date'];
  $yr = explode("-", $date);
  $year =$yr[0];
  $invoice_id =  get_package_booking_id($booking_id,$year);
}



if($from_date!="" && $to_date!=""){

    $date_str = $from_date.' to '.$to_date;

}

else{

    $date_str = "";

}

if($company_name == 'undefined') { $company_name = ''; }



// Add some data

$objPHPExcel->setActiveSheetIndex(0)

            ->setCellValue('B2', 'Report Name')

            ->setCellValue('C2', 'Package Booking')

            ->setCellValue('B3', 'Customer Name')

            ->setCellValue('C3', $cust_name)

            ->setCellValue('B4', 'Booking ID')

            ->setCellValue('C4', $invoice_id)

            ->setCellValue('B5', 'From-To-Date')

            ->setCellValue('C5', $date_str)

            ->setCellValue('B6', 'Customer Type')

            ->setCellValue('C6', $cust_type)

            ->setCellValue('B7', 'Company Name')

            ->setCellValue('C7', $company_name);

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

$query = "select * from package_tour_booking_master where financial_year_id='$financial_year_id' and delete_status='0'";

  if($customer_id!=""){

    $query .=" and customer_id='$customer_id'";

  }

  if($booking_id!=""){

    $query .=" and booking_id='$booking_id'";

  }

  if($from_date!="" && $to_date!=""){

    $from_date = get_date_db($from_date);

    $to_date = get_date_db($to_date);



    $query .= " and date(booking_date) between '$from_date' and '$to_date'";

  }

  if($cust_type != ""){

    $query .= " and customer_id in (select customer_id from customer_master where type = '$cust_type')";

  }

  if($company_name != ""){

    $query .= " and customer_id in (select customer_id from customer_master where company_name = '$company_name')";

  }

  include "../../../model/app_settings/branchwise_filteration.php";
  $query .= ' order by booking_id desc';

$count = 0;
$total_sale=0;$total_cancel=0;$total_paid=0;$total_balance=0;

$row_count = 9;

$objPHPExcel->setActiveSheetIndex(0)

        ->setCellValue('B'.$row_count, "Invoice_No")
        ->setCellValue('C'.$row_count, "Booking ID")
        ->setCellValue('D'.$row_count, "Customer Name")
        ->setCellValue('E'.$row_count, "Tour Date")
        ->setCellValue('F'.$row_count, "Tour")
        ->setCellValue('G'.$row_count, "Total PAX")
        ->setCellValue('H'.$row_count, "Train Amount")
        ->setCellValue('I'.$row_count, "Flight Amount")
        ->setCellValue('J'.$row_count, "Cruise Amount")
        ->setCellValue('K'.$row_count, "Basic Amount") 
        ->setCellValue('L'.$row_count, "Service Charge")
        ->setCellValue('M'.$row_count, "Discount")
        ->setCellValue('N'.$row_count, "Credit card Charges")
        ->setCellValue('O'.$row_count, "Tax Amount")
        ->setCellValue('P'.$row_count, "TCS")
        ->setCellValue('Q'.$row_count, "TDS")
        ->setCellValue('R'.$row_count, "Cancel Amount")
        ->setCellValue('S'.$row_count, "Total Amount")
        ->setCellValue('T'.$row_count, "Paid Amount")
        ->setCellValue('U'.$row_count, "Balance Amount")
        ->setCellValue('V'.$row_count, "Due Date")
        ->setCellValue('W'.$row_count, "Created By")
        ->setCellValue('X'.$row_count, "Booking Date");



$objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':X'.$row_count)->applyFromArray($header_style_Array);

$objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':X'.$row_count)->applyFromArray($borderArray);    



$row_count++;

$sq_booking = mysqlQuery($query);
  while($row_booking = mysqli_fetch_assoc($sq_booking)){
    
    $sq_emp =  mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id = '$row_booking[emp_id]'"));
    $emp_name = ($row_booking['emp_id'] != 0) ? $sq_emp['first_name'].' '.$sq_emp['last_name'] : 'Admin';

    $sq_total_members = mysqli_num_rows(mysqlQuery("select traveler_id from package_travelers_details where booking_id='$row_booking[booking_id]'"));

    $sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$row_booking[customer_id]'"));
    if($sq_customer['type']=='Corporate'||$sq_customer['type'] == 'B2B'){
      $customer_name = $sq_customer['company_name'];
    } else{
      $customer_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
    }

    $total_rooms= ($row_booking['required_rooms']!="") ? $row_booking['required_rooms']: 0;
    $total_travel_amount = $row_booking['total_train_expense'] + $row_booking['total_plane_expense'];
    $visa_total_amount= ($row_booking['visa_total_amount']!="") ? $row_booking['visa_total_amount']: 0.00;
    $insuarance_total_amount= ($row_booking['insuarance_total_amount']!="") ? $row_booking['insuarance_total_amount']: 0.00;

    //Tour TOtal
    $tour_amount= ($row_booking['total_hotel_expense']!="") ? $row_booking['total_hotel_expense']: 0;

    $sq_tour_refund = mysqli_fetch_assoc(mysqlQuery("select * from package_refund_traveler_estimate where booking_id='$row_booking[booking_id]'"));
    $cancel_amount = $sq_tour_refund['cancel_amount'];

    $total_tour_amount = $tour_amount - $cancel_amount ;
    //Travel Total 
    $travel_amount= ($row_booking['total_travel_expense']!="") ? $row_booking['total_travel_expense']: 0;

    $sq_tour_refund = mysqli_fetch_assoc(mysqlQuery("select * from package_refund_traveler_estimate where booking_id='$row_booking[booking_id]'"));
    $cancel_amount = $sq_tour_refund['cancel_amount'];

    $sq_credit = mysqli_fetch_assoc(mysqlQuery("SELECT sum(`credit_charges`) as sumc FROM `package_payment_master` WHERE `booking_id`='$row_booking[booking_id]' and clearance_status != 'Pending' and `clearance_status`!='Cancelled'"));

    $total_travel_amount = $travel_amount - $cancel_amount;
    $total_tour_amount = $tour_amount - $cancel_amount;

    $total_booking_amt = $row_booking['net_total']  + $sq_credit['sumc'];

    $sq_paid = mysqli_fetch_assoc(mysqlQuery("select sum(amount) as paid_amount from package_payment_master where booking_id='$row_booking[booking_id]' and clearance_status !='Pending' and clearance_status !='Cancelled'"));
    $paid_amount = $sq_paid['paid_amount'];

    if ($cancel_amount != '') {
      if ($cancel_amount <= $paid_amount) {
        $balance_amount = 0;
      } else {
        $balance_amount =  $cancel_amount - $paid_amount;
      }
    } else {
      $cancel_amount = ($cancel_amount == '') ? '0' : $cancel_amount;
      $balance_amount = $row_booking['net_total'] - $paid_amount;
    }
    $after_cancel_amt = floatval($total_booking_amt) - floatval($cancel_amount);
    // currency conversion
    $currency_amount1 = currency_conversion($currency,$row_booking['currency_code'],$after_cancel_amt);
    if($row_booking['currency_code'] !='0' && $currency != $row_booking['currency_code']){
      $currency_amount = ' ('.$currency_amount1.')';
    }else{
      $currency_amount = '';
    }
    $date = $row_booking['booking_date'];
    $yr = explode("-", $date);
    $year =$yr[0];
    $discount_in = ($row_booking['discount_in'] == 'Percentage') ? '%' : '';

    $cust_user_name = '';
    $sq_quo = mysqli_fetch_assoc(mysqlQuery("select user_id from package_tour_quotation_master where quotation_id='$row_booking[quotation_id]'"));
    if($sq_quo['user_id'] != 0){ 
      $row_user = mysqli_fetch_assoc(mysqlQuery("Select name from customer_users where user_id ='$sq_quo[user_id]'"));
      $cust_user_name = ' ('.$row_user['name'].')';
    }
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('B'.$row_count, $row_booking['invoice_pr_id'])
      ->setCellValue('C'.$row_count, get_package_booking_id($row_booking['booking_id'],$year))
      ->setCellValue('D'.$row_count, $customer_name.$cust_user_name)
      ->setCellValue('E'.$row_count,get_date_user($row_booking['tour_from_date']).' to '.get_date_user($row_booking['tour_to_date']))
      ->setCellValue('F'.$row_count, $row_booking['tour_name'])
      ->setCellValue('G'.$row_count, $sq_total_members)
      ->setCellValue('H'.$row_count, number_format($row_booking['total_train_expense'],2))
      ->setCellValue('I'.$row_count, number_format($row_booking['total_plane_expense'],2))
      ->setCellValue('J'.$row_count, number_format($row_booking['total_cruise_expense'],2))
      ->setCellValue('K'.$row_count, number_format($row_booking['basic_amount'],2))
      ->setCellValue('L'.$row_count, number_format($row_booking['service_charge'], 2))
      ->setCellValue('M'.$row_count, number_format($row_booking['discount'], 2).$discount_in)
      ->setCellValue('N'.$row_count, number_format($sq_credit['sumc'], 2))
      ->setCellValue('O'.$row_count, $row_booking['tour_service_tax_subtotal'])
      ->setCellValue('P'.$row_count, $row_booking['tcs_tax'])
      ->setCellValue('Q'.$row_count, $row_booking['tds'])
      ->setCellValue('R'.$row_count, number_format($cancel_amount, 2))
      ->setCellValue('S'.$row_count, number_format($after_cancel_amt, 2).$currency_amount)
      ->setCellValue('T'.$row_count, number_format($sq_paid['paid_amount']+$sq_credit['sumc'], 2))
      ->setCellValue('U'.$row_count, number_format($balance_amount, 2))
      ->setCellValue('V'.$row_count, ($row_booking['due_date']=='1970-01-01') ? 'NA' : date('d-m-Y',strtotime($row_booking['due_date'])))
      ->setCellValue('W'.$row_count,$emp_name)
      ->setCellValue('X'.$row_count,get_date_user($row_booking['booking_date']));

    $total_sale += $after_cancel_amt;
    $total_cancel += $cancel_amount;
    $total_paid += $sq_paid['paid_amount']+$sq_credit['sumc'];
    $total_balance += $balance_amount;

  $objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':X'.$row_count)->applyFromArray($content_style_Array);
  $objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':x'.$row_count)->applyFromArray($borderArray);    
  $row_count++;
}

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('B'.$row_count, '')
->setCellValue('C'.$row_count, '')
->setCellValue('D'.$row_count, '')
->setCellValue('E'.$row_count, '')
->setCellValue('F'.$row_count, '')
->setCellValue('G'.$row_count, '')
->setCellValue('H'.$row_count, '')
->setCellValue('I'.$row_count, '')
->setCellValue('J'.$row_count, '')
->setCellValue('K'.$row_count, '')
->setCellValue('L'.$row_count, '')
->setCellValue('M'.$row_count, '')
->setCellValue('N'.$row_count, '')
->setCellValue('O'.$row_count, '')
->setCellValue('P'.$row_count, '')
->setCellValue('Q'.$row_count, '')
->setCellValue('R'.$row_count, 'Total')
->setCellValue('S'.$row_count, number_format($total_sale,2))
->setCellValue('T'.$row_count, number_format($total_cancel,2))
->setCellValue('U'.$row_count, number_format($total_paid,2))
->setCellValue('V'.$row_count, number_format($total_balance,2))
->setCellValue('W'.$row_count, '')
->setCellValue('X'.$row_count, '');
$objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':X'.$row_count)->applyFromArray($header_style_Array);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':X'.$row_count)->applyFromArray($borderArray);    


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

header('Content-Disposition: attachment;filename="Package Booking('.date('d-m-Y H:i').').xls"');

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

