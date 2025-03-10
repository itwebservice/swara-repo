<?php
$flag = true;
class refund_estimate{

public function refund_estimate_save(){
      
  $row_spec ='sales';
	$booking_id = $_POST['booking_id'];
  $cancel_amount = $_POST['cancel_amount'];
  $total_refund_amount = $_POST['total_refund_amount'];
  $tax_value = $_POST['tax_value'];
  $tour_service_tax_subtotal = $_POST['tour_service_tax_subtotal'];
  $cancel_amount_exc = $_POST['cancel_amount_exc'];

	//**Starting transaction
	begin_t();
	$sq_est = mysqlQuery("update bus_booking_master set cancel_amount='$cancel_amount', refund_net_total='$total_refund_amount',cancel_flag='1',`tax_value`='$tax_value', `tax_amount`='$tour_service_tax_subtotal', `cancel_amount_exc`='$cancel_amount_exc' where booking_id='$booking_id'");
	if(!$sq_est){
		$GLOBALS['flag'] = false;
		echo "error--Refund estimate has not been saved.";
	}

	//Finance save
    $this->finance_save($booking_id,$row_spec);

    if($GLOBALS['flag']){
    	commit_t();
    	echo "Refund estimate has been successfully saved.";
    	exit;
    }
    else{
    	rollback_t();
    	exit;
    }
}

public function finance_save($booking_id,$row_spec)
{
  $cancel_amount = $_POST['cancel_amount'];
  $ledger_posting = $_POST['ledger_posting'];
  $cancel_amount_exc = $_POST['cancel_amount_exc'];
  $tour_service_tax_subtotal_cancel = $_POST['tour_service_tax_subtotal'];

  $created_at = date("Y-m-d");
	$year1 = explode("-", $created_at);
	$yr1 =$year1[0];

  $sq_bus_booking = mysqli_fetch_assoc(mysqlQuery("select * from bus_booking_master where booking_id='$booking_id'"));
  $customer_id = $sq_bus_booking['customer_id'];
  $taxation_type = $sq_bus_booking['taxation_type'];
  $service_tax_subtotal = $sq_bus_booking['service_tax_subtotal'];

  $markup = $sq_bus_booking['markup'];
  $service_tax_markup = $sq_bus_booking['markup_tax'];
  $reflections = json_decode($sq_bus_booking['reflections']);
  //Getting customer Ledger
  $sq_cust = mysqli_fetch_assoc(mysqlQuery("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  $bus_sale_amount = $sq_bus_booking['basic_cost'] ;
  global $transaction_master;
	$sq_cust = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
	$cust_name = $sq_cust['first_name'].' '.$sq_cust['last_name'];
  $sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from bus_booking_entries where booking_id='$booking_id'"));

  $particular = get_cancel_sales_particular(get_bus_booking_id($booking_id,$yr1), $customer_id);

    //////////Sales/////////////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $bus_sale_amount;
    $payment_date = $created_at;
    $payment_particular = $particular;
    $ledger_particular = '';
    $gl_id = 11;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,'',$ledger_particular,'REFUND');

    /////////Service Charge////////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $sq_bus_booking['service_charge'];
    $payment_date = $created_at;
    $payment_particular = $particular;
    $ledger_particular = '';
    $gl_id = ($reflections[0]->hotel_sc != '') ? $reflections[0]->hotel_sc : 190;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,'',$ledger_particular,'REFUND');

    /////////Service Charge Tax Amount////////
    // Eg. CGST:(9%):24.77, SGST:(9%):24.77
    $service_tax_subtotal = explode(',',$service_tax_subtotal);
    $tax_ledgers = explode(',',$reflections[0]->hotel_taxes);
    for($i=0;$i<sizeof($service_tax_subtotal);$i++){

      $service_tax = explode(':',$service_tax_subtotal[$i]);
      $tax_amount = $service_tax[2];
      $ledger = isset($tax_ledgers[$i]) ? $tax_ledgers[$i] : '';

      $module_name = "Bus Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = $tax_amount;
      $payment_date = $created_at;
      $payment_particular = $particular;
      $ledger_particular = '';
      $gl_id = $ledger;
      $payment_side = "Debit";
      $clearance_status = "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,'',$ledger_particular,'REFUND');
    }

    ///////////Markup//////////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $markup;
    $payment_date = $created_at;
    $payment_particular = $particular;
    $ledger_particular = '';
    $gl_id = ($reflections[0]->hotel_markup != '') ? $reflections[0]->hotel_markup : 202;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,'',$ledger_particular,'REFUND');

    /////////Markup Tax Amount////////
    // Eg. CGST:(9%):24.77, SGST:(9%):24.77
    $service_tax_markup = explode(',',$service_tax_markup);
    $tax_ledgers = explode(',',$reflections[0]->hotel_markup_taxes);
    for($i=0;$i<sizeof($service_tax_markup);$i++){

      $service_tax = explode(':',$service_tax_markup[$i]);
      $tax_amount = $service_tax[2];
      $ledger = isset($tax_ledgers[$i]) ? $tax_ledgers[$i] : '';

      $module_name = "Bus Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = $tax_amount;
      $payment_date = $created_at;
      $payment_particular = $particular;
      $ledger_particular = '';
      $gl_id = $ledger;
      $payment_side = "Debit";
      $clearance_status = "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,'',$ledger_particular,'REFUND');
    }

    ////Roundoff Value
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $sq_bus_booking['roundoff'];
    $payment_date = $created_at;
    $payment_particular = $particular;
    $ledger_particular = '';
    $gl_id = 230;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,'',$ledger_particular,'REFUND');

    ////////Customer Sale Amount//////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $sq_bus_booking['net_total'];
    $payment_date = $created_at;
    $payment_particular = $particular;
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,'',$ledger_particular,'REFUND');    

    $service_tax_subtotal = explode(',',$tour_service_tax_subtotal_cancel);
    $tax_ledgers = explode(',',$ledger_posting);
    for($i=0;$i<sizeof($service_tax_subtotal);$i++){

      $service_tax = explode(':',$service_tax_subtotal[$i]);
      $tax_amount = $service_tax[2];
      $ledger = isset($tax_ledgers[$i]) ? $tax_ledgers[$i] : '';

      $module_name = "Bus Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = $tax_amount;
      $payment_date = $created_at;
      $payment_particular = $particular;
      $ledger_particular = '';
      $gl_id = $ledger;
      $payment_side = "Credit";
      $clearance_status = "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,'',$ledger_particular,'REFUND');
    }

    ////////Cancel Amount//////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount_exc;
    $payment_date = $created_at;
    $payment_particular = $particular;
    $ledger_particular = '';
    $gl_id = 161;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,'',$ledger_particular,'REFUND');    

    ////////Customer Cancel Amount//////
    $module_name = "Bus Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $cancel_amount;
    $payment_date = $created_at;
    $payment_particular = $particular;
    $ledger_particular = '';
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,'',$ledger_particular,'REFUND'); 

}


}

?>