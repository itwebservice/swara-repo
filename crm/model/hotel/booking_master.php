<?php
$flag = true;
class booking_master{

  public function booking_save(){

    $row_spec ='sales';
    $unique_timestamp = $_POST['unique_timestamp'];
    $customer_id = $_POST['customer_id'];
    $emp_id = $_POST['emp_id'];
    $pass_name = mysqlREString($_POST['pass_name']);
    $adults = $_POST['adults'];
    $childrens = $_POST['childrens'];
    $child_with_bed = $_POST['child_with_bed'];
    $infants = $_POST['infants'];
    $sub_total = $_POST['sub_total'];
    $service_charge = $_POST['service_charge'];
    $service_tax_subtotal = $_POST['service_tax_subtotal'];
    $markup = $_POST['markup'];
    $discount = $_POST['discount'];
    $tds = $_POST['tds'];
    $total_fee = $_POST['total_fee'];
    $roundoff = $_POST['roundoff'];
    $due_date = $_POST['due_date'];
    $booking_date = $_POST['booking_date'];
    $branch_admin_id = $_POST['branch_admin_id'];
    $quotation_id = $_POST['quotation_id'];
    $hotel_options = $_POST['hotel_options'];
    $currency_code = $_POST['currency_code'];

    $payment_date = $_POST['payment_date'];
    $payment_amount = $_POST['payment_amount'];
    $payment_mode = $_POST['payment_mode'];
    $bank_name = $_POST['bank_name'];
    $transaction_id = $_POST['transaction_id'];
    $bank_id = $_POST['bank_id'];
    
    $tour_type_arr = $_POST['tour_type_arr'];
    $city_id_arr = $_POST['city_id_arr'];
    $hotel_id_arr = $_POST['hotel_id_arr'];
    $check_in_arr = $_POST['check_in_arr'];
    $check_out_arr = $_POST['check_out_arr'];
    $no_of_nights_arr = $_POST['no_of_nights_arr'];
    $rooms_arr = $_POST['rooms_arr'];
    $room_type_arr = $_POST['room_type_arr'];
    $category_arr = $_POST['category_arr'];
    $accomodation_type_arr = $_POST['accomodation_type_arr'];
    $extra_beds_arr = $_POST['extra_beds_arr'];
    $meal_plan_arr = $_POST['meal_plan_arr'];
    $conf_no_arr = $_POST['conf_no_arr'];
    $reflections = json_decode(json_encode($_POST['reflections']));
    $service_tax_markup = $_POST['service_tax_markup'];
    $credit_charges = $_POST['credit_charges'];
    $credit_card_details = $_POST['credit_card_details'];
    $tcs_tax = $_POST['tcs_tax'];
    $tcs_per = $_POST['tcs_per'];
    $financial_year_id = $_SESSION['financial_year_id'];
    
    $bsmValues = json_decode(json_encode($_POST['bsmValues']));
    foreach($bsmValues[0] as $key => $value){
        switch($key){
          case 'basic' : $sub_total = ($value != "") ? $value : $sub_total;break;
          case 'service' : $service_charge = ($value != "") ? $value : $service_charge;break;
          case 'markup' : $markup = ($value != "") ? $value : $markup;break;
          case 'discount' : $discount = ($value != "") ? $value : $discount;break;
        }
    }
    if($quotation_id!=''){
      $sq_quot = mysqli_fetch_assoc(mysqlQuery("select quotation_date,enquiry_details from hotel_quotation_master where quotation_id='$quotation_id'"));
      $enqDetails = json_decode($sq_quot['enquiry_details']);
      $yr = explode("-", $sq_quot['quotation_date']);
      $quotation_id1 = get_quotation_id($quotation_id, $yr[0]) . ' : ' . $enqDetails->customer_name.' : '.'Option-'.$hotel_options;
    }else{
      $quotation_id1 = '';
    }

    //Invoice number reset to one in new financial year
    $sq_count = mysqli_num_rows(mysqlQuery("select entry_id from invoice_no_reset_master where service_name='hotel' and financial_year_id='$financial_year_id'"));
    if($sq_count > 0){ // Already having bookings for this financial year
    
      $sq_invoice = mysqli_fetch_assoc(mysqlQuery("select max_booking_id from invoice_no_reset_master where service_name='hotel' and financial_year_id='$financial_year_id'"));
      $invoice_pr_id = $sq_invoice['max_booking_id'] + 1;
      $sq_invoice = mysqlQuery("update invoice_no_reset_master set max_booking_id = '$invoice_pr_id' where service_name='hotel' and financial_year_id='$financial_year_id'");
    }
    else{ // This financial year's first booking
    
      // Get max entry_id of invoice_no_reset_master here
      $sq_entry_id = mysqli_fetch_assoc(mysqlQuery("select max(entry_id) as entry_id from invoice_no_reset_master"));
      $max_entry_id = $sq_entry_id['entry_id'] + 1;
      
      // Insert booking-id(1) for new financial_year only for first the time
      $sq_invoice = mysqlQuery("insert into invoice_no_reset_master(entry_id ,service_name, financial_year_id ,max_booking_id) values ('$max_entry_id','hotel','$financial_year_id','1')");
      $invoice_pr_id = 1;
    }

    if (floatval($discount) > (floatval($service_charge) + floatval($markup))) {
      echo "error--Discount can't be greater than service charge + markup !";
      exit;
    }
    
    //Get Customer id
    if($customer_id == '0'){
      $sq_max = mysqli_fetch_assoc(mysqlQuery("select max(customer_id) as max from customer_master"));
      $customer_id = $sq_max['max'];
    }
    $sq_max = mysqli_fetch_assoc(mysqlQuery("select max(booking_id) as max from hotel_booking_master"));
    $booking_id = $sq_max['max'] + 1;
    $due_date = date('Y-m-d',strtotime($due_date));
    $booking_date = date('Y-m-d',strtotime($booking_date));
    $payment_date = date('Y-m-d', strtotime($payment_date));
    $created_at = date('Y-m-d H:i');

    if($payment_mode=="Cheque" || $payment_mode == 'Credit Card'){ 
      $clearance_status = "Pending"; } 
    else {  $clearance_status = ""; } 

    begin_t();
    $reflections = json_encode($reflections);
    $bsmValues = json_encode($bsmValues);
    $sq_booking = mysqlQuery("INSERT INTO hotel_booking_master(booking_id, quotation_id,customer_id, branch_admin_id,financial_year_id,pass_name, adults, childrens,child_with_bed, infants, sub_total, service_charge, service_tax_subtotal,markup,markup_tax, discount, tds, total_fee, unique_timestamp, due_date , created_at, emp_id,reflections,bsm_values,roundoff,currency_code,tcs_tax,tcs_per,invoice_pr_id) VALUES ('$booking_id', '$quotation_id1','$customer_id', '$branch_admin_id','$financial_year_id','$pass_name', '$adults', '$childrens', '$child_with_bed','$infants', '$sub_total', '$service_charge', '$service_tax_subtotal', '$markup','$service_tax_markup','$discount', '$tds', '$total_fee', '$unique_timestamp', '$due_date', '$booking_date', '$emp_id','$reflections','$bsmValues','$roundoff','$currency_code','$tcs_tax','$tcs_per','$invoice_pr_id')");
      if(!$sq_booking){
          rollback_t();
          echo "error--Sorry Hotel information not saved successfully!";
          exit;
      }
      else{

        for($i=0; $i<sizeof($city_id_arr); $i++){

          $check_in_arr[$i] = date('Y-m-d H:i', strtotime($check_in_arr[$i]));
          $check_out_arr[$i] = date('Y-m-d H:i', strtotime($check_out_arr[$i]));
          $sq_max = mysqli_fetch_assoc(mysqlQuery("select max(entry_id) as max from hotel_booking_entries"));
          $entry_id = $sq_max['max'] + 1;
          $sq_entry = mysqlQuery("insert into hotel_booking_entries (entry_id, booking_id, tour_type, city_id, hotel_id, check_in, check_out, no_of_nights, rooms, room_type, category, accomodation_type, extra_beds, meal_plan, conf_no) values('$entry_id', $booking_id,'$tour_type_arr[$i]', '$city_id_arr[$i]', '$hotel_id_arr[$i]', '$check_in_arr[$i]', '$check_out_arr[$i]', '$no_of_nights_arr[$i]', '$rooms_arr[$i]', '$room_type_arr[$i]', '$category_arr[$i]', '$accomodation_type_arr[$i]', '$extra_beds_arr[$i]', '$meal_plan_arr[$i]', '$conf_no_arr[$i]')");
          if(!$sq_entry){
            $GLOBALS['flag'] = false;
            echo "error--Sorry, Some hotels are not saved!";
          }
        }
        $sq_max = mysqli_fetch_assoc(mysqlQuery("select max(payment_id) as max from hotel_booking_payment"));
        $payment_id = $sq_max['max']+1;
        $sq_payment = mysqlQuery("insert into hotel_booking_payment(payment_id, booking_id, financial_year_id, branch_admin_id, payment_date, payment_mode, payment_amount, bank_name, transaction_id, bank_id, clearance_status, created_at,credit_charges,credit_card_details) values ('$payment_id', '$booking_id', '$financial_year_id', '$branch_admin_id', '$payment_date', '$payment_mode', '$payment_amount', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status', '$created_at','$credit_charges','$credit_card_details')");
        if(!$sq_payment){
          $GLOBALS['flag'] = false;
          echo "error--Sorry, Payment not done!".$booking_id;
          exit;
        }

        //Update customer credit note balance
        $payment_amount1 = $payment_amount;
        if($payment_mode=='Credit Note'){
          $sq_credit_note = mysqlQuery("select * from credit_note_master where customer_id='$customer_id'");
          $i=0;
          while($row_credit = mysqli_fetch_assoc($sq_credit_note)) 
          { 
            if($row_credit['payment_amount'] <= $payment_amount1 && $payment_amount1 != '0'){   
              $payment_amount1 = $payment_amount1 - $row_credit['payment_amount'];
              $temp_amount = 0;
            }
            else{
              $temp_amount = $row_credit['payment_amount'] - $payment_amount1;
              $payment_amount1 = 0;
            }
            $sq_credit = mysqlQuery("update credit_note_master set payment_amount ='$temp_amount' where id='$row_credit[id]'");
          }
        }
        //Get Particular
        $pax = intval($adults) + intval($childrens);
        $particular = $this->get_particular($customer_id,$pax,$no_of_nights_arr[0],$check_in_arr[0],$category_arr[0],$hotel_id_arr[0],$booking_id);
        //Finance save
        $this->finance_save($booking_id, $payment_id, $row_spec, $branch_admin_id,$particular);
        if($payment_mode != 'Credit Note'){
          //Cash/bank book
          $this->bank_cash_book_save($booking_id, $payment_id, $branch_admin_id);
        }

      if($GLOBALS['flag']){
        commit_t();
        //Hotel Booking email send
        $this->hotel_booking_email_send($booking_id,$total_fee,$payment_amount,$credit_charges);
        $this->booking_sms($booking_id, $customer_id, $booking_date);
              
        //payment email send
        $payment_master  = new payment_master;            
        $payment_master->payment_email_notification_send($booking_id, $payment_amount, $payment_mode, $payment_date);

        // payment sms send
        if($payment_amount != 0){
          $payment_master->payment_sms_notification_send($booking_id, $payment_amount, $payment_mode);
        }
        echo "Hotel Booking has been successfully saved-".$booking_id;
        exit;   
      }
      else{
        rollback_t();
        exit;
      }
    }
  }
  function hotel_booking_delete(){

      global $delete_master,$transaction_master;
      $booking_id = $_POST['booking_id'];
  
      $deleted_date = date('Y-m-d');
      $row_spec = "sales";
    
      $row_hotel = mysqli_fetch_assoc(mysqlQuery("select * from hotel_booking_master where booking_id='$booking_id' and delete_status='0'"));
      $row_hotel_entry = mysqli_fetch_assoc(mysqlQuery("select * from hotel_booking_entries where booking_id='$booking_id'"));
      $reflections = json_decode($row_hotel['reflections']);
      $service_tax_markup = $row_hotel['markup_tax'];
      $service_tax_subtotal = $row_hotel['service_tax_subtotal'];
      $customer_id = $row_hotel['customer_id'];
      $booking_date = $row_hotel['created_at'];
      $nights = $row_hotel_entry['no_of_nights'];
      $check_in = get_date_user($row_hotel_entry['check_in']);
      $room_type = $row_hotel_entry['room_type'];
      $hotel_id = $row_hotel_entry['hotel_id'];
      $yr = explode("-", $booking_date);
      $year = $yr[0];
      
      $sq_ct = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
      if($sq_ct['type']=='Corporate'||$sq_ct['type'] == 'B2B'){
        $cust_name = $sq_ct['company_name'];
      }else{
        $cust_name = $sq_ct['first_name'].' '.$sq_ct['last_name'];
      }
      $pax = intval($row_hotel['adults']) + intval($row_hotel['childrens']);
      $particular = $this->get_particular($customer_id,$pax,$nights,$check_in,$room_type,$hotel_id,$booking_id);
  
      /////////////////// // Update entries log//////////////////////
      global $transaction_master;

      $yr = explode("-", $booking_date);
      $year = $yr[0];
      $sq_ct = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
      if($sq_ct['type']=='Corporate'||$sq_ct['type'] == 'B2B'){
          $cust_name = $sq_ct['company_name'];
      }else{
          $cust_name = $sq_ct['first_name'].' '.$sq_ct['last_name'];
      }

      $trans_id = get_hotel_booking_id($booking_id,$year).' : '.$cust_name;
      $transaction_master->updated_entries('Hotel Sale',$booking_id,$trans_id,$row_hotel['total_fee'],0);
      /////////////////// // Update entries log end//////////////////
      
      $delete_master->delete_master_entries('Invoice','Hotel',$booking_id,get_hotel_booking_id($booking_id,$year),$cust_name,$row_hotel['total_fee']);
  
      //Getting Customer Ledger
      $sq_cust = mysqli_fetch_assoc(mysqlQuery("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
      $cust_gl = $sq_cust['ledger_id'];
  
      ////////////Sales/////////////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = 0;
      $payment_date = $deleted_date;
      $payment_particular = $particular;
      $ledger_particular = get_ledger_particular('To','Hotel Sales');
      $old_gl_id = $gl_id = 63;
      $payment_side = "Credit";
      $clearance_status = "";
      $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

      ////////////service charge/////////////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = 0;
      $payment_date = $deleted_date;
      $payment_particular = $particular;
      $ledger_particular = get_ledger_particular('To','Hotel Sales');
      $old_gl_id = $gl_id = ($reflections[0]->hotel_sc != '') ? $reflections[0]->hotel_sc : 186;
      $payment_side = "Credit";
      $clearance_status = "";
      $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

      /////////Service Charge Tax Amount////////
      // Eg. CGST:(9%):24.77, SGST:(9%):24.77
      $service_tax_subtotal = explode(',',$service_tax_subtotal);
      $tax_ledgers = explode(',',$reflections[0]->hotel_taxes);
      for($i=0;$i<sizeof($service_tax_subtotal);$i++){

        $ledger = $tax_ledgers[$i];

        $module_name = "Hotel Booking";
        $module_entry_id = $booking_id;
        $transaction_id = "";
        $payment_amount = 0;
        $payment_date = $deleted_date;
        $payment_particular = $particular;
        $ledger_particular = get_ledger_particular('To','Hotel Sales');
        $old_gl_id = $gl_id = $ledger;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');
      }
      
      ////////////tcs charge/////////////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = 0;
      $payment_date = $deleted_date;
      $payment_particular = $particular;
      $ledger_particular = get_ledger_particular('To','Hotel Sales');
      $old_gl_id = $gl_id = 232;
      $payment_side = "Credit";
      $clearance_status = "";
      $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

      ////////////markup/////////////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = 0;
      $payment_date = $deleted_date;
      $payment_particular = $particular;
      $ledger_particular = get_ledger_particular('To','Hotel Sales');
      $old_gl_id = $gl_id = ($reflections[0]->hotel_markup != '') ? $reflections[0]->hotel_markup : 198;
      $payment_side = "Credit";
      $clearance_status = "";
      $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');
      
      /////////Markup Tax Amount////////
      // Eg. CGST:(9%):24.77, SGST:(9%):24.77
      $service_tax_markup = explode(',',$service_tax_markup);
      $tax_ledgers = explode(',',$reflections[0]->hotel_markup_taxes);
      for($i=0;$i<sizeof($service_tax_markup);$i++){

        $service_tax = explode(':',$service_tax_markup[$i]);
        $ledger = $tax_ledgers[$i];

        $module_name = "Hotel Booking";
        $module_entry_id = $booking_id;
        $transaction_id = "";
        $payment_amount = 0;
        $payment_date = $deleted_date;
        $payment_particular = $particular;
        $ledger_particular = get_ledger_particular('To','Hotel Sales');
        $old_gl_id = $gl_id = $ledger;
        $payment_side = "Credit";
        $clearance_status = "";
        $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'1', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');
      }
      /////////roundoff/////////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = 0;
      $payment_date = $deleted_date;
      $payment_particular = $particular;
      $ledger_particular = get_ledger_particular('To','Hotel Sales');
      $old_gl_id = $gl_id = 230;
      $payment_side = "Credit";
      $clearance_status = "";
      $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

      /////////Discount////////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = 0;
      $payment_date = $deleted_date;
      $payment_particular = $particular;
      $ledger_particular = get_ledger_particular('To','Hotel Sales');
      $old_gl_id = $gl_id = 36;
      $payment_side = "Debit";
      $clearance_status = "";
      $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

      /////////TDS////////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = 0;
      $payment_date = $deleted_date;
      $payment_particular = $particular;
      $ledger_particular = get_ledger_particular('To','Hotel Sales');
      $old_gl_id = $gl_id = ($reflections[0]->hotel_tds != '') ? $reflections[0]->hotel_tds : 127;
      $payment_side = "Debit";
      $clearance_status = "";
      $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

      ////////Customer Amount//////
      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = 0;
      $payment_date = $deleted_date;
      $payment_particular = $particular;
      $ledger_particular = get_ledger_particular('To','Hotel Sales');
      $old_gl_id = $gl_id = $cust_gl;
      $payment_side = "Debit";
      $clearance_status = "";
      $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');
      
      $sq_delete = mysqlQuery("update hotel_booking_master set sub_total = '0',markup='0',markup_tax='', service_tax_subtotal='',service_charge='0', total_fee ='0',tds='0',discount='0', roundoff='0', delete_status='1',tcs_tax='0' where booking_id='$booking_id'");
      if($sq_delete){
        echo 'Entry deleted successfully!';
        exit;
      }
  }
  function get_particular($customer_id,$pax,$nights,$check_date,$room_type,$hotel_id,$booking_id){

    $sq_hotel = mysqli_fetch_assoc(mysqlQuery("select pass_name,created_at from hotel_booking_master where booking_id='$booking_id'"));

    $year2 = explode("-", $sq_hotel['created_at']);
    $yr1 = $year2[0];

    $sq_ct = mysqli_fetch_assoc(mysqlQuery("select first_name,last_name,company_name,type from customer_master where customer_id='$customer_id'"));
    $cust_name = ($sq_ct['type'] != 'Corporate' && $sq_ct['type'] != 'B2B') ? $sq_ct['first_name'].' '.$sq_ct['last_name'] : $sq_ct['company_name'];
    $guest_name = (($sq_ct['type']=='Corporate' || $sq_ct['type'] == 'B2B') && $sq_hotel['pass_name']!='') ? '('.$sq_hotel['pass_name'].')' : '';

    $sq_ht = mysqli_fetch_assoc(mysqlQuery("select hotel_name from hotel_master where hotel_id='$hotel_id'"));
    $hotel_name = $sq_ht['hotel_name'];

    return get_hotel_booking_id($booking_id,$yr1).' and '.$nights.' Night(s) stay from '.get_date_user($check_date).' in '.$room_type.' Room at '.$hotel_name .' for '.$cust_name.$guest_name.' * '.$pax;
  }

  public function employee_sign_up_mail($first_name, $last_name, $username, $password, $email_id)
  {
    // global $app_email_id, $app_name, $app_contact_no, $admin_logo_url, $app_website;
    // global $mail_em_style, $mail_em_style1, $mail_font_family, $mail_strong_style, $mail_color;
    $link = BASE_URL.'view/customer';
    $content = mail_login_box($username, $password, $link);
    $subject ='Welcome aboard!';
    global $model;
    $model->app_email_send('2',$first_name,$email_id, $content,$subject,'1');
  }

public function finance_save($booking_id, $payment_id, $row_spec, $branch_admin_id,$particular)
{
	$customer_id = $_POST['customer_id'];
	$sub_total = $_POST['sub_total'];
	$service_charge = $_POST['service_charge'];
	$markup = $_POST['markup'];
	$discount = $_POST['discount'];
	$tds = $_POST['tds'];
  $total_fee = $_POST['total_fee'];
  $roundoff = $_POST['roundoff'];
  $tcs_tax = $_POST['tcs_tax'];
	$payment_date = $_POST['payment_date'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	// $bank_name =$_POST['bank_name'];
	$transaction_id1 = $_POST['transaction_id'];
	$bank_id = $_POST['bank_id'];
  $reflections = json_decode(json_encode($_POST['reflections']));
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
  $service_tax_markup = $_POST['service_tax_markup'];
  $credit_charges = $_POST['credit_charges'];
	$credit_card_details = $_POST['credit_card_details'];
  $bsmValues = json_decode(json_encode($_POST['bsmValues']));
  foreach($bsmValues[0] as $key => $value){
      switch($key){
      case 'basic' : $sub_total = ($value != "") ? $value : $sub_total;break;
      case 'service' : $service_charge = ($value != "") ? $value : $service_charge;break;
      case 'markup' : $markup = ($value != "") ? $value : $markup;break;
      case 'discount' : $discount = ($value != "") ? $value : $discount;break;
      }
    }
	$booking_date = get_date_db($_POST['booking_date']);
	$payment_date1 = get_date_db($payment_date);
	$year1 = explode("-", $booking_date);
	$yr1 = $year1[0];
	$year2 = explode("-", $payment_date1);
  $yr2 = $year2[0];
	$payment_amount1 = floatval($payment_amount1) + floatval($credit_charges);

  $hotel_sale_amount = $sub_total;
  // $balance_amount = $total_fee - $payment_amount1;

  //Get Customer id
  if($customer_id == '0'){
    $sq_max = mysqli_fetch_assoc(mysqlQuery("select max(customer_id) as max from customer_master"));
    $customer_id = $sq_max['max'];
  }
  //Getting customer Ledger
  $sq_cust = mysqli_fetch_assoc(mysqlQuery("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  //Getting cash/Bank Ledger
  if($payment_mode == 'Cash') {  $pay_gl = 20; $type='CASH RECEIPT';}
  else{ 
    $sq_bank = mysqli_fetch_assoc(mysqlQuery("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
    $pay_gl = isset($sq_bank['ledger_id']) ? $sq_bank['ledger_id'] : '';
    $type='BANK RECEIPT';
  }

    global $transaction_master;
    ////////////Sales/////////////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $hotel_sale_amount;
    $payment_date = $booking_date;
    $payment_particular = $particular;
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = 63;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'INVOICE');

    /////////Service Charge////////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $service_charge;
    $payment_date = $booking_date;
    $payment_particular = $particular;
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = ($reflections[0]->hotel_sc != '') ? $reflections[0]->hotel_sc : 186;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'INVOICE');

    /////////Service Charge Tax Amount////////
    // Eg. CGST:(9%):24.77, SGST:(9%):24.77
    $service_tax_subtotal = explode(',',$service_tax_subtotal);
    $tax_ledgers = explode(',',$reflections[0]->hotel_taxes);
    for($i=0;$i<sizeof($service_tax_subtotal);$i++){

      $service_tax = explode(':',$service_tax_subtotal[$i]);
      $tax_amount = isset($service_tax[2]) ? $service_tax[2] : 0;
      $ledger = $tax_ledgers[$i];

      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = $tax_amount;
      $payment_date = $booking_date;
      $payment_particular = $particular;
      $ledger_particular = get_ledger_particular('To','Hotel Sales');
      $gl_id = $ledger;
      $payment_side = "Credit";
      $clearance_status = "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'INVOICE');
    }

    ///////////Markup//////////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $markup;
    $payment_date = $booking_date;
    $payment_particular = $particular;
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = ($reflections[0]->hotel_markup != '') ? $reflections[0]->hotel_markup : 198;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'INVOICE');

    /////////Markup Tax Amount////////
    // Eg. CGST:(9%):24.77, SGST:(9%):24.77
    $service_tax_markup = explode(',',$service_tax_markup);
    $tax_ledgers = explode(',',$reflections[0]->hotel_markup_taxes);
    for($i=0;$i<sizeof($service_tax_markup);$i++){

      $service_tax = explode(':',$service_tax_markup[$i]);
      $tax_amount = $service_tax[2];
      $ledger = $tax_ledgers[$i];

      $module_name = "Hotel Booking";
      $module_entry_id = $booking_id;
      $transaction_id = "";
      $payment_amount = $tax_amount;
      $payment_date = $booking_date;
      $payment_particular = $particular;
      $ledger_particular = get_ledger_particular('To','Hotel Sales');
      $gl_id = $ledger;
      $payment_side = "Credit";
      $clearance_status = "";
      $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'1', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'INVOICE');
    }
    ////tcs Value
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $tcs_tax;
    $payment_date = $booking_date;
    $payment_particular = $particular;
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = 232;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'INVOICE');

    ////Roundoff Value
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $roundoff;
    $payment_date = $booking_date;
    $payment_particular = $particular;
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = 230;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'INVOICE');
    
    /////////Discount////////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $discount;
    $payment_date = $booking_date;
    $payment_particular = $particular;
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = 36;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'INVOICE');

    /////////TDS////////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $tds;
    $payment_date = $booking_date;
    $payment_particular = $particular;
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = ($reflections[0]->hotel_tds != '') ? $reflections[0]->hotel_tds : 127;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'INVOICE');

    ////////Customer Amount//////
    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $total_fee;
    $payment_date = $booking_date;
    $payment_particular = $particular;
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $gl_id = $cust_gl;
    $payment_side = "Debit";
    $clearance_status = "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,'INVOICE');

    //////////Payment Amount///////////
	  if($payment_mode != 'Credit Note'){
		
		if($payment_mode == 'Credit Card'){

			//////Customer Credit charges///////
			$module_name = "Hotel Booking Payment";
			$module_entry_id = $payment_id;
			$transaction_id = $transaction_id1;
			$payment_amount = $credit_charges;
			$payment_date = $payment_date1;
			$payment_particular = get_sales_paid_particular(get_hotel_booking_id($booking_id,$yr1), $payment_date1, $credit_charges, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1),$bank_id,$transaction_id1);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
			$gl_id = $cust_gl;
			$payment_side = "Debit";
			$clearance_status = ($payment_mode=="Cheque"||$payment_mode=="Credit Card") ? "Pending" : "";
			$transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,$type);

			//////Credit charges ledger///////
			$module_name = "Hotel Booking Payment";
			$module_entry_id = $payment_id;
			$transaction_id = $transaction_id1;
			$payment_amount = $credit_charges;
			$payment_date = $payment_date1;
			$payment_particular = get_sales_paid_particular(get_hotel_booking_id($booking_id,$yr1), $payment_date1, $credit_charges, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1),$bank_id,$transaction_id1);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
			$gl_id = 224;
			$payment_side = "Credit";
			$clearance_status = ($payment_mode=="Cheque"||$payment_mode=="Credit Card") ? "Pending" : "";
			$transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,$type);

			//////Get Credit card company Ledger///////
			$credit_card_details = explode('-',$credit_card_details);
			$entry_id = $credit_card_details[0];
			$sq_cust1 = mysqli_fetch_assoc(mysqlQuery("select * from ledger_master where customer_id='$entry_id' and user_type='credit company'"));
			$company_gl = $sq_cust1['ledger_id'];
			//////Get Credit card company Charges///////
			$sq_credit_charges = mysqli_fetch_assoc(mysqlQuery("select * from credit_card_company where entry_id='$entry_id'"));
			//////company's credit card charges
			$company_card_charges = ($sq_credit_charges['charges_in'] =='Flat') ? $sq_credit_charges['credit_card_charges'] : ($payment_amount1 * ($sq_credit_charges['credit_card_charges']/100));
			//////company's tax on credit card charges
			$tax_charges = ($sq_credit_charges['tax_charges_in'] =='Flat') ? $sq_credit_charges['tax_on_credit_card_charges'] : ($company_card_charges * ($sq_credit_charges['tax_on_credit_card_charges']/100));
			$finance_charges = $company_card_charges + $tax_charges;
      $finance_charges = number_format($finance_charges,2);
			$credit_company_amount = $payment_amount1 - $finance_charges;

			//////Finance charges ledger///////
			$module_name = "Hotel Booking Payment";
			$module_entry_id = $payment_id;
			$transaction_id = $transaction_id1;
			$payment_amount = $finance_charges;
			$payment_date = $payment_date1;
			$payment_particular = get_sales_paid_particular(get_hotel_booking_id($booking_id,$yr1), $payment_date1, $finance_charges, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1),$bank_id,$transaction_id1);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
			$gl_id = 231;
			$payment_side = "Debit";
			$clearance_status = ($payment_mode=="Cheque"||$payment_mode=="Credit Card") ? "Pending" : "";
			$transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,$type);
			//////Credit company amount///////
			$module_name = "Hotel Booking Payment";
			$module_entry_id = $payment_id;
			$transaction_id = $transaction_id1;
			$payment_amount = $credit_company_amount;
			$payment_date = $payment_date1;
			$payment_particular = get_sales_paid_particular(get_hotel_booking_id($booking_id,$yr1), $payment_date1, $credit_company_amount, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1),$bank_id,$transaction_id1);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
			$gl_id = $company_gl;
			$payment_side = "Debit";
			$clearance_status = ($payment_mode=="Cheque"||$payment_mode=="Credit Card") ? "Pending" : "";
			$transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,$type);
		}
		else{

			$module_name = "Hotel Booking Payment";
			$module_entry_id = $payment_id;
			$transaction_id = $transaction_id1;
			$payment_amount = $payment_amount1;
			$payment_date = $payment_date1;
			$payment_particular = get_sales_paid_particular(get_hotel_booking_id($booking_id,$yr1), $payment_date1, $payment_amount1, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1),$bank_id,$transaction_id1);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
			$gl_id = $pay_gl;
			$payment_side = "Debit";
			$clearance_status = ($payment_mode=="Cheque"||$payment_mode=="Credit Card") ? "Pending" : "";
			$transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, '',$payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,$type);
		}

    //////Customer Payment Amount///////
    $module_name = "Hotel Booking Payment";
    $module_entry_id = $payment_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date1;
    $payment_particular = get_sales_paid_particular(get_hotel_booking_id($booking_id,$yr1), $payment_date1, $payment_amount1, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1),$bank_id,$transaction_id1);
    $ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = ($payment_mode=="Cheque" || $payment_mode=="Credit Card") ? "Pending" : "";
    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id,'', $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular,$type);
  }
}

public function bank_cash_book_save($booking_id, $payment_id, $branch_admin_id)
{
  global $bank_cash_book_master;

  $customer_id = $_POST['customer_id'];
  $payment_date = $_POST['payment_date'];
  $payment_amount = $_POST['payment_amount'];
  $payment_mode = $_POST['payment_mode'];
  $bank_name =$_POST['bank_name'];
  $transaction_id = $_POST['transaction_id'];
  $bank_id = $_POST['bank_id'];
	$payment_date = get_date_db($payment_date);
	$year2 = explode("-", $payment_date);
  $yr1 = $year2[0];
  $credit_charges = $_POST['credit_charges'];
	$credit_card_details = $_POST['credit_card_details'];

	
  if($payment_mode == 'Credit Card')
  {
    $payment_amount = $payment_amount + $credit_charges;
    $credit_card_details = explode('-',$credit_card_details);
    $entry_id = $credit_card_details[0];
    
    $sq_credit_charges = mysqli_fetch_assoc(mysqlQuery("select bank_id from credit_card_company where entry_id ='$entry_id'"));
    $bank_id = $sq_credit_charges['bank_id'];

  }
  //Get Customer id
  if($customer_id == '0'){
    $sq_max = mysqli_fetch_assoc(mysqlQuery("select max(customer_id) as max from customer_master"));
    $customer_id = $sq_max['max'];
  }
  
  $module_name = "Hotel Booking Payment";
  $module_entry_id = $payment_id;
  $payment_date = $payment_date;
  $payment_amount = $payment_amount;
  $payment_mode = $payment_mode;
  $bank_name = $bank_name;
  $transaction_id = $transaction_id;
  $bank_id = $bank_id; 
  $particular = get_sales_paid_particular(get_hotel_booking_payment_id($payment_id,$yr1), $payment_date, $payment_amount, $customer_id, $payment_mode, get_hotel_booking_id($booking_id,$yr1),$bank_id,$transaction_id);
  $clearance_status = ($payment_mode=="Cheque"  || $payment_mode=="Credit Card") ? "Pending" : "";
  $payment_side = "Debit";
  $payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

  $bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type, $branch_admin_id);
  
}

public function booking_update()
{
  $row_spec = 'sales';
	$booking_id = $_POST['booking_id'];
	$customer_id = $_POST['customer_id'];
  $pass_name = mysqlREString($_POST['pass_name']);
	$adults = $_POST['adults'];
	$childrens = $_POST['childrens'];
  $child_with_bed = $_POST['child_with_bed'];
	$infants = $_POST['infants'];
	$sub_total = $_POST['sub_total'];
	$service_charge = $_POST['service_charge'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
  $markup = $_POST['markup'];
	$discount = $_POST['discount'];
	$tds = $_POST['tds'];
  $total_fee = $_POST['total_fee'];
  $roundoff = $_POST['roundoff'];
	$due_date1 = $_POST['due_date1'];
  $booking_date1 = $_POST['booking_date1'];
  $tcs_tax = $_POST['tcs_tax'];
  $tcs_per = $_POST['tcs_per'];
  $currency_code = $_POST['currency_code'];
  $old_total = $_POST['old_total'];

  $tour_type_arr = $_POST['tour_type_arr'];
	$city_id_arr = $_POST['city_id_arr'];
	$hotel_id_arr = $_POST['hotel_id_arr'];
	$check_in_arr = $_POST['check_in_arr'];
	$check_out_arr = $_POST['check_out_arr'];
	$no_of_nights_arr = $_POST['no_of_nights_arr'];
	$rooms_arr = $_POST['rooms_arr'];
	$room_type_arr = $_POST['room_type_arr'];
	$category_arr = $_POST['category_arr'];
	$accomodation_type_arr = $_POST['accomodation_type_arr'];
	$extra_beds_arr = $_POST['extra_beds_arr'];
	$meal_plan_arr = $_POST['meal_plan_arr'];
	$conf_no_arr = $_POST['conf_no_arr'];
	$entry_id_arr = $_POST['entry_id_arr'];
	$e_checkbox_arr = $_POST['e_checkbox_arr'];
  $reflections = json_encode($_POST['reflections']);
  $service_tax_markup = $_POST['service_tax_markup'];
  $bsmValues = json_decode(json_encode($_POST['bsmValues']));
  foreach($bsmValues[0] as $key => $value){
      switch($key){
      case 'basic' : $sub_total = ($value != "") ? $value : $sub_total;break;
      case 'service' : $service_charge = ($value != "") ? $value : $service_charge;break;
      case 'markup' : $markup = ($value != "") ? $value : $markup;break;
      case 'discount' : $discount = ($value != "") ? $value : $discount;break;
      }
    }
	$booking_date1 = date('Y-m-d', strtotime($booking_date1));
	$due_date1 = date('Y-m-d',strtotime($due_date1));

	$sq_booking_info = mysqli_fetch_assoc(mysqlQuery("select * from hotel_booking_master where booking_id='$booking_id'"));

	begin_t();

  $bsmValues = json_encode($bsmValues);
	$sq_booking = mysqlQuery("UPDATE hotel_booking_master SET customer_id='$customer_id',pass_name='$pass_name', adults='$adults', childrens='$childrens',child_with_bed='$child_with_bed', infants='$infants', sub_total='$sub_total', service_charge='$service_charge',service_tax_subtotal='$service_tax_subtotal',markup='$markup', discount='$discount', tds='$tds', total_fee='$total_fee', due_date='$due_date1',created_at='$booking_date1',reflections='$reflections',markup_tax='$service_tax_markup',bsm_values='$bsmValues',roundoff='$roundoff',tcs_tax='$tcs_tax',tcs_per='$tcs_per',currency_code='$currency_code' WHERE booking_id='$booking_id'");
	if($sq_booking){

		for($i=0; $i<sizeof($city_id_arr); $i++){

			$check_in_arr[$i] = get_datetime_db($check_in_arr[$i]);
			$check_out_arr[$i] = get_datetime_db($check_out_arr[$i]);

      if($e_checkbox_arr[$i] == 'true'){
        if($entry_id_arr[$i]==""){

          $sq_max = mysqli_fetch_assoc(mysqlQuery("select max(entry_id) as max from hotel_booking_entries"));
          $entry_id = $sq_max['max'] + 1;
          $sq_entry = mysqlQuery("insert into hotel_booking_entries (`entry_id`, `booking_id`, `tour_type`, `city_id`, `hotel_id`, `check_in`, `check_out`, `no_of_nights`, `rooms`, `room_type`, `category`, `accomodation_type`, `extra_beds`, `meal_plan`, `conf_no`) values('$entry_id','$booking_id','$tour_type_arr[$i]', '$city_id_arr[$i]', '$hotel_id_arr[$i]', '$check_in_arr[$i]', '$check_out_arr[$i]', '$no_of_nights_arr[$i]', '$rooms_arr[$i]', '$room_type_arr[$i]', '$category_arr[$i]', '$accomodation_type_arr[$i]', '$extra_beds_arr[$i]', '$meal_plan_arr[$i]', '$conf_no_arr[$i]')");
          if(!$sq_entry){
            $GLOBALS['flag'] = false;
            echo "error--Sorry, Some hotels are not saved!";
            //exit;
          }

        }
        else{
          $sq_entry = mysqlQuery("UPDATE hotel_booking_entries set tour_type='$tour_type_arr[$i]',city_id='$city_id_arr[$i]', hotel_id='$hotel_id_arr[$i]', check_in='$check_in_arr[$i]', check_out='$check_out_arr[$i]', no_of_nights='$no_of_nights_arr[$i]', rooms='$rooms_arr[$i]', room_type='$room_type_arr[$i]', category='$category_arr[$i]', accomodation_type='$accomodation_type_arr[$i]', extra_beds='$extra_beds_arr[$i]', meal_plan='$meal_plan_arr[$i]', conf_no='$conf_no_arr[$i]' where entry_id='$entry_id_arr[$i]'");
          if(!$sq_entry){
            $GLOBALS['flag'] = false;
            echo "error--Sorry, Some hotels are not updated!";
            //exit;
          }
          $pax = intval($adults) + intval($childrens);

        }
      }else{
        
					$sq_entry = mysqlQuery("delete from hotel_booking_entries where entry_id='$entry_id_arr[$i]'");
					if(!$sq_entry){
						$GLOBALS['flag'] = false;
						echo "error--Some entries not deleted!";
					}
      }
		}
    //Get Particular
    $sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from hotel_booking_entries where booking_id='$booking_id'"));
    $check_in = get_datetime_user($sq_hotel['check_in']);

    $particular = $this->get_particular($customer_id,$pax,$sq_hotel['no_of_nights'],$check_in,$sq_hotel['category'],$sq_hotel['hotel_id'],$booking_id);
		//Finance update
		$this->finance_update($sq_booking_info, $row_spec,$particular);

    /////////////////// // Update entries log//////////////////////
    global $transaction_master;
    if(floatval($old_total) != floatval($total_fee)){

      $yr = explode("-", $booking_date1);
      $year = $yr[0];
      $sq_ct = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
      if($sq_ct['type']=='Corporate'||$sq_ct['type'] == 'B2B'){
          $cust_name = $sq_ct['company_name'];
      }else{
          $cust_name = $sq_ct['first_name'].' '.$sq_ct['last_name'];
      }

      $trans_id = get_hotel_booking_id($booking_id,$year).' : '.$cust_name;
      $transaction_master->updated_entries('Hotel Sale',$booking_id,$trans_id,$old_total,$total_fee);
    }
    /////////////////// // Update entries log end//////////////////
    
		if($GLOBALS['flag']){
			commit_t();
			echo "Hotel Booking has been successfully updated.";
			exit;	
		}
		else{
			rollback_t();
			exit;
		}
	}
	else{
		rollback_t();
		echo "error--Sorry, Booking not updated!";
		exit;
	}
	
}

public function finance_update($sq_booking_info, $row_spec,$particular){

  $row_spec = 'sales';
	$booking_id = $_POST['booking_id'];
	$customer_id = $_POST['customer_id'];
	$sub_total = $_POST['sub_total'];
	$service_charge = $_POST['service_charge'];
	$service_tax_subtotal = $_POST['service_tax_subtotal'];
  $markup = $_POST['markup'];
	$discount = $_POST['discount'];
	$tds = $_POST['tds'];
	$total_fee = $_POST['total_fee'];
	$booking_date1 = $_POST['booking_date1'];
  $reflections = json_decode(json_encode($_POST['reflections']));
  $service_tax_markup = $_POST['service_tax_markup'];
  $roundoff = $_POST['roundoff'];
  $tcs_tax = $_POST['tcs_tax'];
  $booking_date = get_date_db($booking_date1);
	$year1 = explode("-", $booking_date);
  $yr1 =$year1[0];
  $bsmValues = json_decode(json_encode($_POST['bsmValues']));
  foreach($bsmValues[0] as $key => $value){
      switch($key){
      case 'basic' : $sub_total = ($value != "") ? $value : $sub_total;break;
      case 'service' : $service_charge = ($value != "") ? $value : $service_charge;break;
      case 'markup' : $markup = ($value != "") ? $value : $markup;break;
      case 'discount' : $discount = ($value != "") ? $value : $discount;break;
      }
    }
  $hotel_sale_amount = $sub_total;

  //Getting customer Ledger
  $sq_cust = mysqli_fetch_assoc(mysqlQuery("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
  $cust_gl = $sq_cust['ledger_id'];

  global $transaction_master;
  ////////////Sales/////////////
  $module_name = "Hotel Booking";
  $module_entry_id = $booking_id;
  $transaction_id = "";
  $payment_amount = $hotel_sale_amount;
  $payment_date = $booking_date;
  $payment_particular = $particular;
  $ledger_particular = get_ledger_particular('To','Hotel Sales');
  $old_gl_id = $gl_id = 63;
  $payment_side = "Credit";
  $clearance_status = "";
  $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

  ////////////service charge/////////////
  $module_name = "Hotel Booking";
  $module_entry_id = $booking_id;
  $transaction_id = "";
  $payment_amount = $service_charge;
  $payment_date = $booking_date;
  $payment_particular = $particular;
  $ledger_particular = get_ledger_particular('To','Hotel Sales');
  $old_gl_id = $gl_id = ($reflections[0]->hotel_sc != '') ? $reflections[0]->hotel_sc : 186;
  $payment_side = "Credit";
  $clearance_status = "";
  $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

  /////////Service Charge Tax Amount////////
  // Eg. CGST:(9%):24.77, SGST:(9%):24.77
  $service_tax_subtotal = explode(',',$service_tax_subtotal);
  $tax_ledgers = explode(',',$reflections[0]->hotel_taxes);
  for($i=0;$i<sizeof($service_tax_subtotal);$i++){

    $service_tax = explode(':',$service_tax_subtotal[$i]);
    $tax_amount = $service_tax[2];
    $ledger = $tax_ledgers[$i];

    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $tax_amount;
    $payment_date = $booking_date;
    $payment_particular = $particular;
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $old_gl_id = $gl_id = $ledger;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');
  }
  
  ////////////tcs charge/////////////
  $module_name = "Hotel Booking";
  $module_entry_id = $booking_id;
  $transaction_id = "";
  $payment_amount = $tcs_tax;
  $payment_date = $booking_date;
  $payment_particular = $particular;
  $ledger_particular = get_ledger_particular('To','Hotel Sales');
  $old_gl_id = $gl_id = 232;
  $payment_side = "Credit";
  $clearance_status = "";
  $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

  ////////////markup/////////////
  $module_name = "Hotel Booking";
  $module_entry_id = $booking_id;
  $transaction_id = "";
  $payment_amount = $markup;
  $payment_date = $booking_date;
  $payment_particular = $particular;
  $ledger_particular = get_ledger_particular('To','Hotel Sales');
  $old_gl_id = $gl_id = ($reflections[0]->hotel_markup != '') ? $reflections[0]->hotel_markup : 198;
  $payment_side = "Credit";
  $clearance_status = "";
  $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');
  
  /////////Markup Tax Amount////////
  // Eg. CGST:(9%):24.77, SGST:(9%):24.77
  $service_tax_markup = explode(',',$service_tax_markup);
  $tax_ledgers = explode(',',$reflections[0]->hotel_markup_taxes);
  for($i=0;$i<sizeof($service_tax_markup);$i++){

    $service_tax = explode(':',$service_tax_markup[$i]);
    $tax_amount = $service_tax[2];
    $ledger = $tax_ledgers[$i];

    $module_name = "Hotel Booking";
    $module_entry_id = $booking_id;
    $transaction_id = "";
    $payment_amount = $tax_amount;
    $payment_date = $booking_date;
    $payment_particular = $particular;
    $ledger_particular = get_ledger_particular('To','Hotel Sales');
    $old_gl_id = $gl_id = $ledger;
    $payment_side = "Credit";
    $clearance_status = "";
    $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'1', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');
  }
  /////////roundoff/////////
  $module_name = "Hotel Booking";
  $module_entry_id = $booking_id;
  $transaction_id = "";
  $payment_amount = $roundoff;
  $payment_date = $booking_date;
  $payment_particular = $particular;
  $ledger_particular = get_ledger_particular('To','Hotel Sales');
  $old_gl_id = $gl_id = 230;
  $payment_side = "Credit";
  $clearance_status = "";
  $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

  /////////Discount////////
  $module_name = "Hotel Booking";
  $module_entry_id = $booking_id;
  $transaction_id = "";
  $payment_amount = $discount;
  $payment_date = $booking_date;
  $payment_particular = $particular;
  $ledger_particular = get_ledger_particular('To','Hotel Sales');
  $old_gl_id = $gl_id = 36;
  $payment_side = "Debit";
  $clearance_status = "";
  $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

  /////////TDS////////
  $module_name = "Hotel Booking";
  $module_entry_id = $booking_id;
  $transaction_id = "";
  $payment_amount = $tds;
  $payment_date = $booking_date;
  $payment_particular = $particular;
  $ledger_particular = get_ledger_particular('To','Hotel Sales');
  $old_gl_id = $gl_id = ($reflections[0]->hotel_tds != '') ? $reflections[0]->hotel_tds : 127;
  $payment_side = "Debit";
  $clearance_status = "";
  $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

  ////////Customer Amount//////
  $module_name = "Hotel Booking";
  $module_entry_id = $booking_id;
  $transaction_id = "";
  $payment_amount = $total_fee;
  $payment_date = $booking_date;
  $payment_particular = $particular;
  $ledger_particular = get_ledger_particular('To','Hotel Sales');
  $old_gl_id = $gl_id = $cust_gl;
  $payment_side = "Debit";
  $clearance_status = "";
  $transaction_master->transaction_update($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular,$old_gl_id, $gl_id,'', $payment_side, $clearance_status, $row_spec,$ledger_particular,'INVOICE');

}

public function hotel_booking_email_send($booking_id,$total_fee,$payment_amount,$credit_card_amount){

	global $encrypt_decrypt,$secret_key,$currency;
	$link = BASE_URL.'view/customer';

	$sq_hotel_booking = mysqli_fetch_assoc(mysqlQuery("select * from hotel_booking_master where booking_id='$booking_id'"));
  $date = $sq_hotel_booking['created_at'];
  $yr = explode("-", $date);
  $year =$yr[0];
  
	$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_hotel_booking[customer_id]'"));
  $name = ($sq_customer['type'] == 'Corporate' || $sq_customer['type'] == 'B2B') ? $sq_customer['company_name'] : $sq_customer['first_name'].' '.$sq_customer['last_name'];

  $username = $encrypt_decrypt->fnDecrypt($sq_customer['contact_no'], $secret_key);
  $email_id = $encrypt_decrypt->fnDecrypt($sq_customer['email_id'], $secret_key);
  $password= $email_id;
  $balance = $total_fee - $payment_amount;
  
  $total_fee1 = currency_conversion($currency,$sq_hotel_booking['currency_code'],($total_fee+floatval($credit_card_amount)));
  $payment_amount1 = currency_conversion($currency,$sq_hotel_booking['currency_code'],($payment_amount+floatval($credit_card_amount)));
  $balance1 = currency_conversion($currency,$sq_hotel_booking['currency_code'],$balance);

  $content = '<tr>
  <table width="85%" cellspacing="0" cellpadding="5" style="color: #888888;border: 1px solid #888888;margin: 0px auto;margin-top:20px; min-width: 100%;" role="presentation">
  <tr><td style="text-align:left;border: 1px solid #888888; width:50%">Total Amount</td>   <td style="text-align:left;border: 1px solid #888888;">'.$total_fee1.'</td></tr>
  <tr><td style="text-align:left;border: 1px solid #888888; width:50%">Paid Amount</td>   <td style="text-align:left;border: 1px solid #888888;">'.$payment_amount1.'</td></tr>
  <tr><td style="text-align:left;border: 1px solid #888888; width:50%">Balance Amount</td>   <td style="text-align:left;border: 1px solid #888888;">'.$balance1.'</td></tr>
  </table>
  </tr>
  ';
  $hotels = mysqlQuery("select hotel_id,check_in, check_out, rooms  from hotel_booking_entries where booking_id = ".$booking_id);
  while($row = mysqli_fetch_assoc($hotels)){
    $hotel_name = mysqli_fetch_assoc(mysqlQuery("select hotel_name from hotel_master where hotel_id = ".$row['hotel_id']));
    $content .= '<tr>
  <table width="85%" cellspacing="0" cellpadding="5" style="color: #888888;border: 1px solid #888888;margin: 0px auto;margin-top:20px; min-width: 100%;" role="presentation">
    <tr><td style="text-align:left;border: 1px solid #888888;width:50%">Hotel Name</td>   <td style="text-align:left;border: 1px solid #888888;">'.$hotel_name['hotel_name'].'</td></tr>
    <tr><td style="text-align:left;border: 1px solid #888888;width:50%">Check-In Date</td>   <td style="text-align:left;border: 1px solid #888888;" >'. get_date_user($row['check_in']).'</td></tr>
    <tr><td style="text-align:left;border: 1px solid #888888;width:50%">Check-Out Date</td>   <td style="text-align:left;border: 1px solid #888888;">'.get_date_user($row['check_out']).'</td></tr> 
    <tr><td style="text-align:left;border: 1px solid #888888;width:50%">Total Rooms</td>   <td style="text-align:left;border: 1px solid #888888;">'.$row['rooms'].'</td></tr>
  </table>
</tr>';
  }

  $subject = 'Booking confirmation acknowledgement! ( '.get_hotel_booking_id($booking_id,$year). ' )';
  
	$content .= mail_login_box($username, $password, $link);

	global $model,$backoffice_email_id;
	
  $model->app_email_send('18',$name,$email_id, $content, $subject);
  if($backoffice_email_id != "")
  $model->app_email_send('18',"Team",$backoffice_email_id, $content, $subject);
}

public function booking_sms($booking_id, $customer_id, $created_at){

  global $model, $app_name, $encrypt_decrypt,$secret_key,$app_contact_no;
  $sq_customer_info = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
  $mobile_no = $encrypt_decrypt->fnDecrypt($sq_customer_info['contact_no'], $secret_key);
  $date = $created_at;
  $created_at1 = get_date_user($created_at);
  $yr = explode("-", $date);
  $year =$yr[0];

  $message = "Dear ".$sq_customer_info['first_name']." ".$sq_customer_info['last_name'].", your hotel booking is confirmed. Hotel Voucher details will send you shortly. Please contact for more details ".$app_contact_no."";
  $model->send_message($mobile_no, $message);  
}

public function whatsapp_send(){
  global $app_contact_no, $encrypt_decrypt, $secret_key,$app_name,$session_emp_id;

  $booking_date = $_POST['booking_date'];
  $customer_id = $_POST['customer_id'];

    if($customer_id == '0'){
      $sq_customer = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM customer_master ORDER BY customer_id DESC LIMIT 1"));
    }
    else{
    $sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
    }
  $mobile_no = $encrypt_decrypt->fnDecrypt($sq_customer['contact_no'], $secret_key);
  $customer_name = ($sq_customer['type'] == 'Corporate' || $sq_customer['type'] == 'B2B') ? $sq_customer['company_name'] : $sq_customer['first_name'].' '.$sq_customer['last_name'];

  $sq_emp_info = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id= '$session_emp_id'"));
  if($session_emp_id == 0){
    $contact = $app_contact_no;
  }
  else{
    $contact = $sq_emp_info['mobile_no'];
  }

$whatsapp_msg = rawurlencode('Dear '.$customer_name.',
Hope you are doing great. This is to inform you that your booking is confirmed with us. We look forward to provide you a great experience.
*Booking Date* : '.get_date_user($booking_date).'

Please contact for more details : '.$app_name.' '.$contact);
  if ($customer_id == '0') {

    //Customer Whatsapp message
    $username = $_POST['contact_no'];
    $password = $_POST['email_id'];
    $whatsapp_msg .= whatsapp_login_box($username,$password);
  }
  $whatsapp_msg .= '%0aThank%20you.%0a';
  $link = 'https://web.whatsapp.com/send?phone='.$mobile_no.'&text='.$whatsapp_msg;
  echo $link;
}

}
?>