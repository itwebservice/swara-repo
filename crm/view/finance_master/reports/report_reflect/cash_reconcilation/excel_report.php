<?php

include "../../../../../model/model.php";



/** Error reporting */

error_reporting(E_ALL);

ini_set('display_errors', TRUE);

ini_set('display_startup_errors', TRUE);

date_default_timezone_set('Europe/London');



if (PHP_SAPI == 'cli')

  die('This example should only be run from a Web Browser');



/** Include PHPExcel */

require_once '../../../../../classes/PHPExcel-1.8/Classes/PHPExcel.php';



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
$filter_date = $_GET['filter_date'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_status = $_GET['branch_status'];

if($filter_date != ''){
    $filter_date1 = get_date_user($filter_date);
}

// Add some data

$objPHPExcel->setActiveSheetIndex(0)

            ->setCellValue('B2', 'Report Name')

            ->setCellValue('C2', 'Cash Reconciliation')

            ->setCellValue('B3', 'Date')

            ->setCellValue('C3', $filter_date1);


$objPHPExcel->getActiveSheet()->getStyle('B2:C2')->applyFromArray($header_style_Array);

$objPHPExcel->getActiveSheet()->getStyle('B2:C2')->applyFromArray($borderArray);    

$objPHPExcel->getActiveSheet()->getStyle('B3:C3')->applyFromArray($header_style_Array);

$objPHPExcel->getActiveSheet()->getStyle('B3:C3')->applyFromArray($borderArray);     

$row_count = 5;

$objPHPExcel->setActiveSheetIndex(0)

        ->setCellValue('B'.$row_count, 'SR.No')
        ->setCellValue('C'.$row_count, 'Date')
        ->setCellValue('D'.$row_count, 'Cash as per System')
        ->setCellValue('E'.$row_count, 'Cash as per Tills')
        ->setCellValue('F'.$row_count, 'Difference')
        ->setCellValue('G'.$row_count, 'Reconciliation Amount')
        ->setCellValue('H'.$row_count, 'Reconciliation difference');

  
  $objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':H'.$row_count)->applyFromArray($header_style_Array);
  $objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':H'.$row_count)->applyFromArray($borderArray); 

$row_count++;
$query = "select * from cash_reconcl_master where 1 ";
if($filter_date != ''){
  $filter_date = get_date_db($filter_date);
  $query .= " and reconcl_date = '$filter_date'";
}
if($branch_status=='yes'){
    $query .= " and branch_admin_id = '$branch_admin_id'";
}
$sq_query = mysqlQuery($query);
$count = 1;
while($row_query = mysqli_fetch_assoc($sq_query))
{
       
        $objPHPExcel->setActiveSheetIndex(0)

        ->setCellValue('B'.$row_count, $count++)
        ->setCellValue('C'.$row_count, get_date_user($row_query['reconcl_date']))
        ->setCellValue('D'.$row_count, number_format($row_query['system_cash'],2))
        ->setCellValue('E'.$row_count, number_format($row_query['till_cash'],2))
        ->setCellValue('F'.$row_count, number_format($row_query['diff_prior'],2))
        ->setCellValue('G'.$row_count, number_format($row_query['reconcl_amount'],2))
        ->setCellValue('H'.$row_count, number_format($row_query['diff_reconcl'],2));

  
  $objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':H'.$row_count)->applyFromArray($content_style_Array);
  $objPHPExcel->getActiveSheet()->getStyle('B'.$row_count.':H'.$row_count)->applyFromArray($borderArray);    

  $row_count++;

}

  $row_count++;
    
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

header('Content-Disposition: attachment;filename="Cash_Reconciliation('.date('d-m-Y H:i').').xls"');

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

