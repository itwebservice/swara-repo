<?php
$flag = true;
class booking_save{
/////////////Start Complete Booking Information Save////////////////////////////////////////

public function complete_booking_information_save(){

  $row_spec = 'sales';
  $unique_timestamp = $_POST['unique_timestamp'];
  //** Getting tour information
  $tour_id = $_POST['tour_id']; 
  $tour_group_id = $_POST['tour_group_id'];
  $emp_id = $_POST['emp_id'];
  $taxation_type = isset($_POST['taxation_type']) ? $_POST['taxation_type'] : '';
  $customer_id = $_POST['customer_id'];
  $branch_admin_id = $_POST['branch_admin_id1'];
  $financial_year_id = $_POST['financial_year_id'];
  //** Getting member information    
  $m_honorific = $_POST['m_honorific'];
  $m_first_name = $_POST['m_first_name'] ?: [];

  $m_middle_name = $_POST['m_middle_name'];
  $m_last_name = $_POST['m_last_name'];
  $m_gender = $_POST['m_gender'];
  $m_birth_date = $_POST['m_birth_date'];
  $m_age = $_POST['m_age'];
  $m_adolescence = $_POST['m_adolescence'];
  $m_passport_no = $_POST['m_passport_no'];
  $m_passport_issue_date = $_POST['m_passport_issue_date'];
  $m_passport_expiry_date = $_POST['m_passport_expiry_date'];
  $m_email_id = $_POST['m_email_id'];
  $m_mobile_no = $_POST['m_mobile_no'];
  $m_address = $_POST['m_address'];
  $m_handover_adnary = $_POST['m_handover_adnary'];
  $m_handover_gift = $_POST['m_handover_gift'];
  //**Getting relatives details
  $relative_honorofic = $_POST['relative_honorofic'];
  $relative_name = $_POST['relative_name'];
  $relative_relation = $_POST['relative_relation'];
  $relative_mobile_no = $_POST['relative_mobile_no'];

  //**Hoteling facility
  $double_bed_room = $_POST['double_bed_room'];
  $single_bed_room = $_POST['single_bed_room'];
  $extra_bed = $_POST['extra_bed'];
  $on_floor = $_POST['on_floor'];

  //** Traveling information overall
  $train_expense = $_POST['train_expense'];
  $train_service_charge = $_POST['train_service_charge'];
  $train_taxation_id = $_POST['train_taxation_id'];
  $train_service_tax = $_POST['train_service_tax'];
  $train_service_tax_subtotal = $_POST['train_service_tax_subtotal'];
  $total_train_expense = $_POST['total_train_expense'];

  $plane_expense = $_POST['plane_expense'];
  $plane_service_charge = $_POST['plane_service_charge'];
  $plane_taxation_id = $_POST['plane_taxation_id'];
  $plane_service_tax = $_POST['plane_service_tax'];
  $plane_service_tax_subtotal = $_POST['plane_service_tax_subtotal'];
  $total_plane_expense = $_POST['total_plane_expense'];

  $cruise_expense = $_POST['cruise_expense'];
  $cruise_service_charge = $_POST['cruise_service_charge'];
  $cruise_taxation_id = $_POST['cruise_taxation_id'];
  $cruise_service_tax = $_POST['cruise_service_tax'];
  $cruise_service_tax_subtotal = $_POST['cruise_service_tax_subtotal'];
  $total_cruise_expense = $_POST['total_cruise_expense'];

  $total_travel_expense = $_POST['total_travel_expense'];
  $train_ticket_path = $_POST['train_ticket_path'];
  $plane_ticket_path = $_POST['plane_ticket_path'];
  $cruise_ticket_path = $_POST['cruise_ticket_path'];

  //**Traveling information for train
  $train_travel_date = isset($_POST['train_travel_date']) ?$_POST['train_travel_date'] : [];
  $train_from_location = isset($_POST['train_from_location']) ? $_POST['train_from_location'] : [];
  $train_to_location = isset($_POST['train_to_location']) ? $_POST['train_to_location'] : [];
  $train_train_no = isset($_POST['train_train_no']) ? $_POST['train_train_no'] : [];
  $train_travel_class = isset($_POST['train_travel_class']) ? $_POST['train_travel_class'] : [];
  $train_travel_priority = isset($_POST['train_travel_priority']) ? $_POST['train_travel_priority'] : [];
  $train_amount = isset($_POST['train_amount']) ? $_POST['train_amount'] : [];
  $train_seats = isset($_POST['train_seats']) ? $_POST['train_seats'] : [];

  //**Traveling information for plane
  $from_city_id_arr = isset($_POST['from_city_id_arr']) ? $_POST['from_city_id_arr'] : [];
  $to_city_id_arr = isset($_POST['to_city_id_arr']) ? $_POST['to_city_id_arr'] : [];
  $plane_travel_date = isset($_POST['plane_travel_date']) ?$_POST['plane_travel_date']: [];
  $plane_from_location = isset($_POST['plane_from_location']) ? $_POST['plane_from_location'] : [];
  $plane_to_location = isset($_POST['plane_to_location']) ? $_POST['plane_to_location'] : [];
  $plane_amount = isset($_POST['plane_amount']) ? $_POST['plane_amount'] : [];
  $plane_seats = isset($_POST['plane_seats']) ? $_POST['plane_seats'] : [];
  $plane_company = isset($_POST['plane_company']) ? $_POST['plane_company'] : [];
  $flight_class_arr = isset($_POST['flight_class_arr']) ? $_POST['flight_class_arr'] : [];
  $arravl_arr = isset($_POST['arravl_arr']) ? $_POST['arravl_arr'] : [];

  // Cruise
  $cruise_dept_date_arr = isset($_POST['cruise_dept_date_arr']) ? $_POST['cruise_dept_date_arr']: [];
  $cruise_arrival_date_arr = isset($_POST['cruise_arrival_date_arr']) ? $_POST['cruise_arrival_date_arr'] : [];
  $route_arr = isset($_POST['route_arr']) ? $_POST['route_arr'] : [];
  $cabin_arr = isset($_POST['cabin_arr']) ? $_POST['cabin_arr'] : [];
  $sharing_arr = isset($_POST['sharing_arr']) ? $_POST['sharing_arr'] : [];
  $cruise_seats_arr = isset($_POST['cruise_seats_arr']) ? $_POST['cruise_seats_arr']: [];
  $cruise_amount_arr = isset($_POST['cruise_amount_arr']) ? $_POST['cruise_amount_arr']: [];


  //**Visa Details
  $visa_country_name = $_POST['visa_country_name'];
  $visa_amount = $_POST['visa_amount'];
  $visa_service_charge = $_POST['visa_service_charge'];
  $visa_taxation_id = $_POST['visa_taxation_id'];
  $visa_service_tax = $_POST['visa_service_tax'];
  $visa_service_tax_subtotal = $_POST['visa_service_tax_subtotal'];
  $visa_total_amount = $_POST['visa_total_amount'];
  $insuarance_company_name = $_POST['insuarance_company_name'];
  $insuarance_amount = $_POST['insuarance_amount'];
  $insuarance_service_charge = $_POST['insuarance_service_charge'];
  $insuarance_taxation_id = $_POST['insuarance_taxation_id'];
  $insuarance_service_tax = $_POST['insuarance_service_tax'];
  $insuarance_service_tax_subtotal = $_POST['insuarance_service_tax_subtotal'];
  $insuarance_total_amount = $_POST['insuarance_total_amount'];







  //**Discount details



  $adult_expense = $_POST['adult_expense'];
  $child_with_bed = $_POST['child_with_bed'];
  $child_without_bed = $_POST['child_without_bed'];
  $infant_expense = $_POST['infant_expense'];
  $tour_fee = $_POST['tour_fee'];
  $repeater_discount = $_POST['repeater_discount'];
  $single_person = $_POST['single_person'];
  $single_person_cost = $_POST['single_person_cost'];


  $adjustment_discount = $_POST['adjustment_discount'];



  $tour_fee_subtotal_1 = $_POST['tour_fee_subtotal_1'];



  $tour_taxation_id = $_POST['tour_taxation_id'];



  $service_tax_per = $_POST['service_tax_per'];



  $service_tax = $_POST['service_tax'];



  $tour_fee_subtotal_2 = $_POST['tour_fee_subtotal_2'];
  $basic_amount = $_POST['basic_amount'];
  $net_total = $_POST['net_total']; 
  $currency_code = $_POST['currency_code'];

  //**Payment details
  $payment_date = isset($_POST['payment_date']) ? $_POST['payment_date'] : [];
  $payment_mode = isset($_POST['payment_mode']) ? $_POST['payment_mode'] : [];
  $payment_amount = isset($_POST['payment_amount']) ? $_POST['payment_amount'] : [];
  $bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : [];
  $transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : [];
  $payment_for = isset($_POST['payment_for']) ? $_POST['payment_for'] : [];
  $p_travel_type = isset($_POST['p_travel_type']) ? $_POST['p_travel_type'] : [];
  $bank_id_arr = isset($_POST['bank_id_arr']) ? $_POST['bank_id_arr'] : [];

  $credit_charges = isset($_POST['credit_charges']) ? $_POST['credit_charges'] : 0;
  $credit_card_details = isset($_POST['credit_card_details']) ? $_POST['credit_card_details'] : [];
  //**Form information
  $special_request = $_POST['special_request'];
  $special_request = addslashes($special_request);
  $due_date = $_POST['due_date'];
  $roundoff = $_POST['roundoff'];
  $total_discount = $_POST['total_discount'];
  $tcs_tax = $_POST['tcs_tax'];
  $tcs = $_POST['tcs'];

  $form_date = $_POST['form_date'];
  $reflections = json_decode(json_encode($_POST['reflections']));
  
  $bsmValues = json_decode(json_encode($_POST['bsmValues']));
    foreach($bsmValues[0] as $key => $value){
      switch($key){
      case 'basic' : $basic_amount = ($value != "") ? $value : $basic_amount;break;
      case 'discount' : $total_discount = ($value != "") ? $value : $total_discount;break;
      }
    }
    $reflections = json_encode($reflections);
    $bsmValues = json_encode($bsmValues);
  $unique_timestamp_count = mysqli_num_rows(mysqlQuery("select unique_timestamp from tourwise_traveler_details where unique_timestamp='$unique_timestamp'"));


    if($unique_timestamp_count>0)

    {
      echo "Error! Booking is already saved.";
      exit;
    } 

  //Transaction start
  begin_t();

  $member_group_id = $this->traveler_member_details_save($m_honorific, $m_first_name, $m_middle_name, $m_last_name, $m_gender, $m_birth_date, $m_age, $m_adolescence, $m_passport_no, $m_passport_issue_date, $m_passport_expiry_date, $m_handover_adnary, $m_handover_gift);

  //** This function saves tourwise traveler details and returns tourwise traveler id
  $current_booked_seats = 0;
  $tourwise_traveler_id = $this->tourwise_traveler_detail_save($unique_timestamp, $member_group_id, $tour_id, $tour_group_id, $emp_id, $taxation_type, $customer_id, $relative_honorofic, $relative_name, $relative_relation, $relative_mobile_no,$single_bed_room, $double_bed_room, $extra_bed, $on_floor, $train_expense, $train_service_charge, $train_taxation_id, $train_service_tax, $train_service_tax_subtotal, $total_train_expense, $plane_expense, $plane_service_charge, $plane_taxation_id, $plane_service_tax, $plane_service_tax_subtotal, $total_plane_expense, $cruise_expense, $cruise_service_charge, $cruise_taxation_id, $cruise_service_tax, $cruise_service_tax_subtotal, $total_cruise_expense, $total_travel_expense, $train_ticket_path, $plane_ticket_path, $cruise_ticket_path, $visa_country_name, $visa_amount, $visa_service_charge, $visa_taxation_id, $visa_service_tax, $visa_service_tax_subtotal, $visa_total_amount, $insuarance_company_name, $insuarance_amount, $insuarance_service_charge, $insuarance_taxation_id, $insuarance_service_tax, $insuarance_service_tax_subtotal, $insuarance_total_amount, $adult_expense, $child_with_bed, $child_without_bed, $infant_expense, $tour_fee, $repeater_discount, $adjustment_discount, $tour_fee_subtotal_1, $tour_taxation_id, $service_tax_per, $service_tax, $tour_fee_subtotal_2, $net_total, $special_request, $due_date, $form_date, $current_booked_seats,$branch_admin_id,$financial_year_id,$reflections,$basic_amount,$roundoff,$bsmValues,$total_discount,$currency_code,$tcs_tax,$tcs,$single_person,$single_person_cost);

  //**Traveler ersonal information save
  $this->traveler_personal_information_save($tourwise_traveler_id,$branch_admin_id, $m_email_id, $m_mobile_no, $m_address);
  //** This function stores the traveling information
  $this->save_traveling_information( $tourwise_traveler_id, $train_travel_date, $train_from_location, $train_to_location, $train_train_no, $train_travel_class, $train_travel_priority, $train_amount, $train_seats, $plane_travel_date, $from_city_id_arr, $plane_from_location, $to_city_id_arr, $plane_to_location, $plane_amount, $plane_seats, $plane_company, $arravl_arr,$cruise_dept_date_arr, $cruise_arrival_date_arr, $route_arr, $cabin_arr, $sharing_arr, $cruise_seats_arr, $cruise_amount_arr,$flight_class_arr);

  //** This function saves payment details
  $this->save_payment_details($tourwise_traveler_id, $payment_date, $payment_mode, $payment_amount, $bank_name, $transaction_id, $payment_for, $p_travel_type, $bank_id_arr, $branch_admin_id, $emp_id,$credit_charges,$credit_card_details);
  //** This function sends message to the traveler
  $this->booking_successfull_sms_send($m_mobile_no,$customer_id,$tour_id);

  //**=============**Finance Entries save start**============**//
  //Get Particular
  $particular = $this->get_particular($customer_id,$tour_id,$tour_group_id,$tourwise_traveler_id);
  $booking_save_transaction = new booking_save_transaction;
  $booking_save_transaction->finance_save($tourwise_traveler_id, $row_spec, $branch_admin_id,$particular);

  //**=============**Finance Entries save end**============**//
  $payment_amount1 = 0;
  if($GLOBALS['flag']){

    commit_t();
    $sq = mysqlQuery("select max(payment_id) as max from payment_master");
    for($i=0; $i<sizeof($payment_amount); $i++){
      $payment_amount1 += $payment_amount[$i];
    }

    //Update customer credit note balance
    $payment_amount2 = isset($payment_amount[0]) ? $payment_amount[0] : 0;
    $payment_mode2 = isset($payment_mode[0]) ? $payment_mode[0] : '';
    $payment_date2 = isset($payment_date[0]) ? $payment_date[0] : '';
    if($payment_mode2 == 'Credit Note'){
      $sq_credit_note = mysqlQuery("select * from credit_note_master where customer_id='$customer_id'");
      $i=0;
      while($row_credit = mysqli_fetch_assoc($sq_credit_note)) 
      {   
          if($row_credit['payment_amount'] <= $payment_amount2 && $payment_amount2 != '0'){       
              $payment_amount2 = $payment_amount2 - $row_credit['payment_amount'];
              $temp_amount = 0;
          }
          else{
              $temp_amount = $row_credit['payment_amount'] - $payment_amount2;
              $payment_amount2 = 0;
          }
          $sq_credit = mysqlQuery("update credit_note_master set payment_amount ='$temp_amount' where id='$row_credit[id]'");
      }
    }
    $total_tour_expense = $net_total;
    $balance_amount = $total_tour_expense - $payment_amount1;

    // This function sends confirmation mail to traveler  
    $this->send_mail_to_traveler($tourwise_traveler_id, $tour_id, $tour_group_id, $customer_id, $m_email_id,$m_first_name,$total_tour_expense,$balance_amount);
    
    // payment email send
    $payment_master  = new booking_payment;
    $payment_master->payment_email_notification_send($tourwise_traveler_id, $payment_amount1,$payment_mode2, $payment_date2,'');

    // payment sms send
    $payment_master->payment_notification_sms_send( $payment_amount1, $payment_mode2,$tourwise_traveler_id);

    echo "Group Tour has been successfully saved-". $tour_id.'-'.$tour_group_id;  
    exit;
  }
  else{
    rollback_t();
    exit;
  }
}
///////////***** Saving Traveleres Members Information ///

function get_particular($customer_id,$tour_id,$tour_group_id,$id){

  $sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from tourwise_traveler_details where id='$id'"));
  $date = $sq_booking['form_date'];
  $yr = explode("-", $date);
  $year = $yr[0];

	$pass_count= mysqli_num_rows(mysqlQuery("select * from travelers_details where traveler_group_id='$sq_booking[traveler_group_id]' and status!='Cancel'"));
	$sq_pass= mysqli_fetch_assoc(mysqlQuery("select * from travelers_details where traveler_group_id='$sq_booking[traveler_group_id]' and status!='Cancel'"));

  $sq_ct = mysqli_fetch_assoc(mysqlQuery("select first_name,last_name from customer_master where customer_id='$customer_id'"));
  $cust_name = $sq_ct['first_name'].' '.$sq_ct['last_name'];
  $sq_tour = mysqli_fetch_assoc(mysqlQuery("select tour_name from tour_master where tour_id='$tour_id'"));
  $tour_name = $sq_tour['tour_name'];
  $sq_tourgroup = mysqli_fetch_assoc(mysqlQuery("select from_date,to_date from tour_groups where group_id='$tour_group_id'"));
  $from_date = new DateTime($sq_tourgroup['from_date']);
  $to_date = new DateTime($sq_tourgroup['to_date']);
  $numberOfNights= $from_date->diff($to_date)->format("%a");

  return get_group_booking_id($id,$year).' and '.$tour_name.' for '.$cust_name. '('.$sq_pass['first_name'].' '.$sq_pass['last_name'].') *'.$pass_count.' for '.$numberOfNights.' Nights starting from '.get_date_user($sq_tourgroup['from_date']);
}


public function traveler_member_details_save($m_honorific, $m_first_name, $m_middle_name, $m_last_name, $m_gender, $m_birth_date, $m_age, $m_adolescence, $m_passport_no, $m_passport_issue_date, $m_passport_expiry_date, $m_handover_adnary, $m_handover_gift)



{



  $sq = mysqlQuery("select max(traveler_group_id) as max from travelers_details");



  $value = mysqli_fetch_assoc($sq);



  $max_traveler_group_id = $value['max']+1;


    $m_handover_adnary = mysqlREString($m_handover_adnary);    



    $m_handover_gift = mysqlREString($m_handover_gift);


  for($i=0; $i<sizeof($m_first_name); $i++){
    //$max_traveler_id = $max_traveler_id + 1;
    $sq = mysqlQuery("select max(traveler_id) as max from travelers_details");
    $value = mysqli_fetch_assoc($sq);



    $max_traveler_id = $value['max'] + 1;







    $m_honorific[$i] = mysqlREString($m_honorific[$i]);



    $m_first_name[$i] = mysqlREString($m_first_name[$i]);



    $m_middle_name[$i] = mysqlREString($m_middle_name[$i]);



    $m_last_name[$i] = mysqlREString($m_last_name[$i]);



    $m_gender[$i] = mysqlREString($m_gender[$i]);



    $m_birth_date[$i] = mysqlREString($m_birth_date[$i]);



    $m_age[$i] = mysqlREString($m_age[$i]);



    $m_adolescence[$i] = mysqlREString($m_adolescence[$i]);



    $m_passport_no[$i] = mysqlREString($m_passport_no[$i]);



    $m_passport_issue_date[$i] = mysqlREString($m_passport_issue_date[$i]);



    $m_passport_expiry_date[$i] = mysqlREString($m_passport_expiry_date[$i]);







    if($m_birth_date[$i]!=""){  $m_birth_date[$i] = date("Y-m-d", strtotime($m_birth_date[$i])); }



    if($m_passport_issue_date[$i]!=""){  $m_passport_issue_date[$i] = date("Y-m-d", strtotime($m_passport_issue_date[$i])); }



    if($m_passport_expiry_date[$i]!=""){  $m_passport_expiry_date[$i] = date("Y-m-d", strtotime($m_passport_expiry_date[$i])); }



     



    $sq = mysqlQuery("insert into travelers_details (traveler_id, traveler_group_id, m_honorific, first_name, middle_name, last_name, gender, birth_date, age, adolescence, passport_no, passport_issue_date, passport_expiry_date, handover_adnary, handover_gift, status) values ('$max_traveler_id','$max_traveler_group_id', '$m_honorific[$i]', '$m_first_name[$i]', '$m_middle_name[$i]', '$m_last_name[$i]', '$m_gender[$i]', '$m_birth_date[$i]', '$m_age[$i]', '$m_adolescence[$i]', '$m_passport_no[$i]', '$m_passport_issue_date[$i]', '$m_passport_expiry_date[$i]', '$m_handover_adnary', '$m_handover_gift', 'Active')");   







    if(!$sq)



    {



      $GLOBALS['flag'] = false;



      echo "Error at row".($i+1)." for traveler members.";



      //exit;



    }  



    



  }  



  



  return $max_traveler_group_id;  







}


///////////////////////////////////***** Saving Tourwise Traveler Information *********/////////////////////////////////////////////////////////////



public function tourwise_traveler_detail_save($unique_timestamp, $member_group_id, $tour_id, $tour_group_id, $emp_id, $taxation_type, $customer_id, $relative_honorofic, $relative_name, $relative_relation, $relative_mobile_no,$single_bed_room, $double_bed_room, $extra_bed, $on_floor, $train_expense, $train_service_charge, $train_taxation_id, $train_service_tax, $train_service_tax_subtotal, $total_train_expense, $plane_expense, $plane_service_charge, $plane_taxation_id, $plane_service_tax, $plane_service_tax_subtotal, $total_plane_expense, $cruise_expense, $cruise_service_charge, $cruise_taxation_id, $cruise_service_tax, $cruise_service_tax_subtotal, $total_cruise_expense, $total_travel_expense, $train_ticket_path, $plane_ticket_path, $cruise_ticket_path, $visa_country_name, $visa_amount, $visa_service_charge, $visa_taxation_id, $visa_service_tax, $visa_service_tax_subtotal, $visa_total_amount, $insuarance_company_name, $insuarance_amount, $insuarance_service_charge, $insuarance_taxation_id, $insuarance_service_tax, $insuarance_service_tax_subtotal, $insuarance_total_amount, $adult_expense, $child_with_bed, $child_without_bed, $infant_expense, $tour_fee, $repeater_discount, $adjustment_discount, $tour_fee_subtotal_1, $tour_taxation_id, $service_tax_per, $service_tax, $tour_fee_subtotal_2, $net_total, $special_request, $due_date, $form_date, $current_booked_seats,$branch_admin_id,$financial_year_id,$reflections,$basic_amount,$roundoff,$bsmValues,$total_discount,$currency_code,$tcs_tax,$tcs,$single_person,$single_person_cost)
{



  $sq = mysqlQuery("select max(id) as max from tourwise_traveler_details");
  $value = mysqli_fetch_assoc($sq);
  $tourwise_traveler_id = $value['max']+1;

  $form_date=date("Y-m-d H:i", strtotime($form_date));
  $due_date=date("Y-m-d", strtotime($due_date));

  //Invoice number reset to one in new financial year
  $sq_count = mysqli_num_rows(mysqlQuery("select entry_id from invoice_no_reset_master where service_name='group' and financial_year_id='$financial_year_id'"));
  if($sq_count > 0){ // Already having bookings for this financial year
  
    $sq_invoice = mysqli_fetch_assoc(mysqlQuery("select max_booking_id from invoice_no_reset_master where service_name='group' and financial_year_id='$financial_year_id'"));
    $invoice_pr_id = $sq_invoice['max_booking_id'] + 1;
    $sq_invoice = mysqlQuery("update invoice_no_reset_master set max_booking_id = '$invoice_pr_id' where service_name='group' and financial_year_id='$financial_year_id'");
  }
  else{ // This financial year's first booking
  
    // Get max entry_id of invoice_no_reset_master here
    $sq_entry_id = mysqli_fetch_assoc(mysqlQuery("select max(entry_id) as entry_id from invoice_no_reset_master"));
    $max_entry_id = $sq_entry_id['entry_id'] + 1;
    
    // Insert booking-id(1) for new financial_year only for first the time
    $sq_invoice = mysqlQuery("insert into invoice_no_reset_master(entry_id ,service_name, financial_year_id ,max_booking_id) values ('$max_entry_id','group','$financial_year_id','1')");
    $invoice_pr_id = 1;
  }

  $sq = mysqlQuery("insert into tourwise_traveler_details (id, traveler_group_id, tour_id, tour_group_id, emp_id, taxation_type, customer_id, branch_admin_id,financial_year_id,  relative_honorofic, relative_name, relative_relation, relative_mobile_no,s_single_bed_room, s_double_bed_room, s_extra_bed, s_on_floor, train_expense, train_service_charge, train_taxation_id, train_service_tax, train_service_tax_subtotal, total_train_expense, plane_expense, plane_service_charge, plane_taxation_id, plane_service_tax, plane_service_tax_subtotal, total_plane_expense,cruise_expense, cruise_service_charge, cruise_taxation_id, cruise_service_tax, cruise_service_tax_subtotal, total_cruise_expense, total_travel_expense, train_upload_ticket, plane_upload_ticket,cruise_upload_ticket, visa_country_name, visa_amount, visa_service_charge, visa_taxation_id, visa_service_tax, visa_service_tax_subtotal, visa_total_amount, insuarance_company_name, insuarance_amount, insuarance_service_charge, insuarance_taxation_id, insuarance_service_tax, insuarance_service_tax_subtotal, insuarance_total_amount, adult_expense, child_with_bed, child_without_bed, infant_expense, tour_fee, repeater_discount, adjustment_discount, tour_fee_subtotal_1, tour_taxation_id, service_tax_per, service_tax, tour_fee_subtotal_2, net_total, special_request, balance_due_date, form_date, current_booked_seats, unique_timestamp,reflections,basic_amount,roundoff,bsm_values,total_discount,currency_code,tcs_tax,tcs_per,invoice_pr_id,single_person,single_person_cost) values ('$tourwise_traveler_id', '$member_group_id', '$tour_id', '$tour_group_id', '$emp_id', '$taxation_type', '$customer_id', '$branch_admin_id','$financial_year_id', '$relative_honorofic', '$relative_name', '$relative_relation', '$relative_mobile_no', '$single_bed_room', '$double_bed_room', '$extra_bed', '$on_floor', '$train_expense', '$train_service_charge', '$train_taxation_id', '$train_service_tax', '$train_service_tax_subtotal', '$total_train_expense', '$plane_expense', '$plane_service_charge', '$plane_taxation_id', '$plane_service_tax', '$plane_service_tax_subtotal', '$total_plane_expense', '$cruise_expense', '$cruise_service_charge', '$cruise_taxation_id', '$cruise_service_tax', '$cruise_service_tax_subtotal', '$total_cruise_expense', '$total_travel_expense', '$train_ticket_path', '$plane_ticket_path', '$cruise_ticket_path', '$visa_country_name', '$visa_amount', '$visa_service_charge', '$visa_taxation_id', '$visa_service_tax', '$visa_service_tax_subtotal', '$visa_total_amount', '$insuarance_company_name', '$insuarance_amount', '$insuarance_service_charge', '$insuarance_taxation_id', '$insuarance_service_tax', '$insuarance_service_tax_subtotal', '$insuarance_total_amount', '$adult_expense', '$child_with_bed','$child_without_bed', '$infant_expense', '$tour_fee', '$repeater_discount', '$adjustment_discount', '$tour_fee_subtotal_1', '$tour_taxation_id', '$service_tax_per', '$service_tax', '$tour_fee_subtotal_2', '$net_total', '$special_request', '$due_date', '$form_date', '$current_booked_seats', '$unique_timestamp','$reflections','$basic_amount','$roundoff','$bsmValues','$total_discount','$currency_code','$tcs_tax','$tcs','$invoice_pr_id','$single_person','$single_person_cost') ");

    if(!$sq){
      $GLOBALS['flag'] = false;
      echo "<br><b>Sorry, Booking not Saved!</b><br>";

    }    







  return $tourwise_traveler_id;



}







///////////////////////////////////***** Traveler personal information save*********/////////////////////////////////////////////////////////////



public function traveler_personal_information_save($tourwise_traveler_id,$branch_admin_id, $m_email_id, $m_mobile_no, $m_address)



{



    $m_email_id = mysqlREString($m_email_id);



    // $m_mobile_no1 = mysqlREString($m_mobile_no1);



    $m_address1 = mysqlREString($m_address);







    $sq_max_id = mysqli_fetch_assoc(mysqlQuery("select max(personal_info_id) as max from traveler_personal_info"));



    $personal_info_id = $sq_max_id['max']+1;







    $sq = mysqlQuery("insert into traveler_personal_info ( personal_info_id, tourwise_traveler_id,branch_admin_id, email_id, mobile_no, address ) values ( '$personal_info_id', '$tourwise_traveler_id', '$branch_admin_id', '$m_email_id', '$m_mobile_no', '$m_address1' )");







    if(!$sq){



      $GLOBALS['flag'] = false;



      echo "Traveler personal information not saved.";



      //exit;



    }



}











 ///////////////////////////////////***** Saving Traveling Information *********/////////////////////////////////////////////////////////////



 public function save_traveling_information($tourwise_traveler_id, $train_travel_date, $train_from_location, $train_to_location, $train_train_no, $train_travel_class, $train_travel_priority, $train_amount, $train_seats, $plane_travel_date, $from_city_id_arr, $plane_from_location, $to_city_id_arr,  $plane_to_location, $plane_amount, $plane_seats, $plane_company, $arravl_arr,$cruise_dept_date_arr, $cruise_arrival_date_arr, $route_arr, $cabin_arr, $sharing_arr, $cruise_seats_arr, $cruise_amount_arr,$flight_class_arr)



 {







      //**Saves Train Information    



      



      for($i=0; $i<sizeof($train_travel_date); $i++)



      {



        //$max_train_id = $max_train_id + 1;



        $sq = mysqlQuery("select max(train_id) as max from train_master");



        $value = mysqli_fetch_assoc($sq);



        $max_train_id = $value['max'] + 1;







        $train_travel_date[$i] = mysqlREString($train_travel_date[$i]);



        $train_from_location[$i] = mysqlREString($train_from_location[$i]);



        $train_to_location[$i] = mysqlREString($train_to_location[$i]);



        $train_train_no[$i] = mysqlREString($train_train_no[$i]);



        $train_travel_class[$i] = mysqlREString($train_travel_class[$i]);



        $train_travel_priority[$i] = mysqlREString($train_travel_priority[$i]);



        $train_amount[$i] = mysqlREString($train_amount[$i]);



        $train_seats[$i] = mysqlREString($train_seats[$i]);







        $train_travel_date[$i] = date("Y-m-d H:i", strtotime($train_travel_date[$i]));







        $sq = mysqlQuery("insert into train_master (train_id, tourwise_traveler_id, date, from_location, to_location, train_no, amount, seats, train_priority, train_class) values ('$max_train_id', '$tourwise_traveler_id', '$train_travel_date[$i]', '$train_from_location[$i]', '$train_to_location[$i]', '$train_train_no[$i]', '$train_amount[$i]', '$train_seats[$i]', '$train_travel_priority[$i]', '$train_travel_class[$i]') ");



        if(!$sq)



        {



          $GLOBALS['flag'] = false;



          echo "Error at row".($i+1)." for train information.";



          //exit;



        }   







      }  



     







      //**Saves Plane Information          



      for($i=0; $i<sizeof($plane_travel_date); $i++)



      {



        //$max_plane_id = $max_plane_id + 1;



        $sq = mysqlQuery("select max(plane_id) as max from plane_master");

        $value = mysqli_fetch_assoc($sq);
        $max_plane_id = $value['max'] + 1;
        $plane_travel_date[$i] = mysqlREString($plane_travel_date[$i]);
        $from_city_id_arr[$i] = mysqlREString($from_city_id_arr[$i]);
        $plane_from_location[$i] = mysqlREString($plane_from_location[$i]);
        $to_city_id_arr[$i] = mysqlREString($to_city_id_arr[$i]);
        $plane_to_location[$i] = mysqlREString($plane_to_location[$i]);
        $plane_amount[$i] = mysqlREString($plane_amount[$i]);
        $plane_seats[$i] = mysqlREString($plane_seats[$i]);
        $plane_company[$i] = mysqlREString($plane_company[$i]);
        $arravl_arr[$i] = mysqlREString($arravl_arr[$i]);
        $flight_class_arr[$i] = mysqlREString($flight_class_arr[$i]);


        $plane_travel_date[$i] = date("Y-m-d H:i", strtotime($plane_travel_date[$i]));
        $from_location = array_slice(explode(' - ', $plane_from_location[$i]), 1);
        $from_location = implode(' - ',$from_location);
        $to_location = array_slice(explode(' - ', $plane_to_location[$i]), 1);
        $to_location = implode(' - ',$to_location);


        $arravl_arr[$i] = date('Y-m-d H:i', strtotime($arravl_arr[$i]));


        $sq = mysqlQuery(" insert into plane_master (plane_id, tourwise_traveler_id, date, from_city, from_location, to_city, to_location, company, amount, seats, arraval_time,class ) values ('$max_plane_id', '$tourwise_traveler_id',   '$plane_travel_date[$i]','$from_city_id_arr[$i]', '$from_location', '$to_city_id_arr[$i]', '$to_location', '$plane_company[$i]', '$plane_amount[$i]', '$plane_seats[$i]', '$arravl_arr[$i]','$flight_class_arr[$i]') ");

        if(!$sq)



        {



          $GLOBALS['flag'] = false;



          echo "Error at row".($i+1)." for Flight information.";



          //exit;



        }  



      }

      //**Saves Cruise Information    

      

      for($i=0; $i<sizeof($cruise_dept_date_arr); $i++)

      {


        $sq = mysqlQuery("select max(cruise_id) as max from group_cruise_master");

        $value = mysqli_fetch_assoc($sq);

        $max_cruise_id = $value['max'] + 1;



        $cruise_dept_date_arr[$i] = mysqlREString($cruise_dept_date_arr[$i]);
        $cruise_arrival_date_arr[$i] = mysqlREString($cruise_arrival_date_arr[$i]);
        $route_arr[$i] = mysqlREString($route_arr[$i]);
        $cabin_arr[$i] = mysqlREString($cabin_arr[$i]);
        $sharing_arr[$i] = mysqlREString($sharing_arr[$i]);
        $cruise_seats_arr[$i] = mysqlREString($cruise_seats_arr[$i]);
        $cruise_amount_arr[$i] = mysqlREString($cruise_amount_arr[$i]);

        $cruise_dept_date_arr[$i] = date("Y-m-d, H:i", strtotime($cruise_dept_date_arr[$i]));        
        $cruise_arrival_date_arr[$i] = date("Y-m-d, H:i", strtotime($cruise_arrival_date_arr[$i])); 
       



        $sq = mysqlQuery("insert into group_cruise_master (cruise_id, booking_id, dept_datetime, arrival_datetime, route, cabin,sharing, seats, amount ) values ('$max_cruise_id', '$tourwise_traveler_id', '$cruise_dept_date_arr[$i]', '$cruise_arrival_date_arr[$i]', '$route_arr[$i]', '$cabin_arr[$i]', '$sharing_arr[$i]', '$cruise_seats_arr[$i]', '$cruise_amount_arr[$i]') ");

        if(!$sq)

        {

          $GLOBALS['flag'] = false;

          echo "Error at row".($i+1)." for cruise information.";

          //exit;

        }   
      }  
 }







 ///////////////////////////////////***** Saving Payment Information *********/////////////////////////////////////////////////////////////



 public function save_payment_details($tourwise_traveler_id, $payment_date, $payment_mode, $payment_amount, $bank_name, $transaction_id, $payment_for, $p_travel_type, $bank_id_arr, $branch_admin_id, $emp_id,$credit_charges,$credit_card_details)



 {
    $financial_year_id = $_SESSION['financial_year_id'];


    for($i=0; $i<sizeof($payment_date); $i++)



    {



      $payment_date[$i] = date("Y-m-d", strtotime($payment_date[$i]));

    if($payment_mode[$i]=="Cheque" || $payment_mode[$i]=="Credit Card"){ 
      $clearance_status = "Pending"; } 
    else {  $clearance_status = ""; } 


     $sq = mysqlQuery("select max(payment_id) as max from payment_master");

     $value = mysqli_fetch_assoc($sq);


     $max_payment_id = $value['max'] + 1;
      $str=" insert into payment_master (payment_id, tourwise_traveler_id, financial_year_id, branch_admin_id, emp_id, date, payment_mode, amount, bank_name, transaction_id, bank_id, payment_for, travel_type, clearance_status, advance_status,credit_charges,credit_card_details) values ('$max_payment_id', '$tourwise_traveler_id', '$financial_year_id', '$branch_admin_id', '$emp_id', '$payment_date[$i]', '$payment_mode[$i]', '$payment_amount[$i]', '$bank_name[$i]', '$transaction_id[$i]', '$bank_id_arr[$i]', '$payment_for[$i]', '$p_travel_type[$i]', '$clearance_status','true','$credit_charges','$credit_card_details') ";
     $sq= mysqlQuery($str);



     include_once(BASE_URL.'model/group_tour/booking_payment.php');


     $booking_payment = new booking_payment();


     $booking_payment->booking_registration_reciept_save( $tourwise_traveler_id, $max_payment_id, $payment_for[$i]);


     if(!$sq)
      {

        $GLOBALS['flag'] = false;

        echo "Error for payment information for $payment_for[$i].";

      }  

      $booking_save_transaction = new booking_save_transaction;



      $booking_save_transaction->payment_finance_save($tourwise_traveler_id, $max_payment_id, $payment_date[0], $payment_mode[$i], $payment_amount[$i], $transaction_id[$i],$bank_id_arr[$i], $branch_admin_id,$credit_charges,$credit_card_details);


      //Bank and Cash Book Save


      if($payment_mode[$i] != 'Credit Note'){
      $booking_save_transaction->bank_cash_book_save($tourwise_traveler_id, $max_payment_id, $payment_date[$i], $payment_mode[$i], $payment_amount[$i], $transaction_id[$i], $bank_name[$i], $bank_id_arr[$i], $branch_admin_id);

      }

    }  
 }

////////////***** Send mail to //////////////////////
function send_mail_to_traveler($tourwise_traveler_id, $tour_id, $tour_group_id, $customer_id, $m_email_id,$m_first_name,$total_tour_expense,$balance_amount)
{

  global $app_email_id, $encrypt_decrypt,$secret_key,$currency_logo,$currency;

  
  if($m_email_id=="")
  {
    $m_email_id=$app_email_id;
    $name = "Admin";
  }
  else
  $name = $m_first_name[0];  
  $sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from tourwise_traveler_details where id='$tourwise_traveler_id'"));

  $traveler_group_id = $sq_booking['traveler_group_id'];
  $date = $sq_booking['form_date'];
  $yr = explode("-", $date);
  $year =$yr[0];

  $tour_name1 = mysqli_fetch_assoc(mysqlQuery("select tour_name from tour_master where tour_id= '$tour_id'"));
  $tour_name = $tour_name1['tour_name'];

  $tour_group1 = mysqli_fetch_assoc(mysqlQuery("select from_date, to_date from tour_groups where group_id= '$tour_group_id'"));
  $from_date = get_date_user($tour_group1['from_date']).' To '.get_date_user($tour_group1['to_date']);

  $subject = 'Booking confirmation acknowledgement! ( '.get_group_booking_id($tourwise_traveler_id,$year). ' )';


  $sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
  $name = ($sq_customer['type'] == 'Corporate' || $sq_customer['type'] == 'B2B') ? $sq_customer['company_name'] : $sq_customer['first_name'].' '.$sq_customer['last_name'];

  $username = $encrypt_decrypt->fnDecrypt($sq_customer['contact_no'], $secret_key);
  $password = $encrypt_decrypt->fnDecrypt($sq_customer['email_id'], $secret_key);

  $sq_total_paid = mysqli_fetch_assoc(mysqlQuery("select sum(amount) as sum,sum(credit_charges) as sumc from payment_master where tourwise_traveler_id='$tourwise_traveler_id' and clearance_status!='Cancelled'"));

  $passengers_infant = mysqli_fetch_assoc(mysqlQuery("select count(*) as cnt from travelers_details where traveler_group_id = ".$traveler_group_id." and adolescence = 'Infant'"));
  $passengers_adult = mysqli_fetch_assoc(mysqlQuery("select count(*) as cnt from travelers_details where traveler_group_id = ".$traveler_group_id." and adolescence = 'Adult'"));
  $passengers_child = mysqli_fetch_assoc(mysqlQuery("select count(*) as cnt from travelers_details where traveler_group_id = ".$traveler_group_id." and (adolescence = 'Child With Bed' or adolescence = 'Child Without Bed')"));

  $credit_card_amount = $sq_total_paid['sumc'];
  $total_amount = $sq_booking['net_total'] + $credit_card_amount;
  $paid_amount = $sq_total_paid['sum']+$credit_card_amount;
  $outstanding =  $total_amount - ($paid_amount);

  $total_amount1 = currency_conversion($currency,$sq_booking['currency_code'],$total_amount);
  $paid_amount1 = currency_conversion($currency,$sq_booking['currency_code'],$paid_amount);
  $outstanding1 = currency_conversion($currency,$sq_booking['currency_code'],$outstanding);

  $link = BASE_URL.'/view/customer/';
  $content ='
  <tr>
  <table width="85%" cellspacing="0" cellpadding="5" style="color: #888888;border: 1px solid #888888;margin: 0px auto;margin-top:20px; min-width: 100%;" role="presentation">
    <tr><td style="text-align:left;border: 1px solid #888888;">Tour Name</td>   <td style="text-align:left;border: 1px solid #888888;">'.$tour_name.'</td></tr>
    <tr><td style="text-align:left;border: 1px solid #888888;">Tour Date</td>   <td style="text-align:left;border: 1px solid #888888;" >'. $from_date.'</td></tr>
    <tr><td style="text-align:left;border: 1px solid #888888;">Total Guest</td>   <td style="text-align:left;border: 1px solid #888888;">'.$passengers_adult['cnt'].' Adult(s),'.$passengers_child['cnt'].' Child(ren),'.$passengers_infant['cnt'].' Infant(s)</td></tr> 
    <tr><td style="text-align:left;border: 1px solid #888888;">Total Amount</td>   <td style="text-align:left;border: 1px solid #888888;">'.$total_amount1.'</td></tr>
    <tr><td style="text-align:left;border: 1px solid #888888;">Paid Amount</td>   <td style="text-align:left;border: 1px solid #888888;">'.$paid_amount1.'</td></tr>
    <tr><td style="text-align:left;border: 1px solid #888888;">Balance Amount</td>   <td style="text-align:left;border: 1px solid #888888;">'.$outstanding1.'</td></tr>
  </table>
</tr>
  ';
  $content .= mail_login_box($username, $password,$link);
  global $model,$backoffice_email_id;
  $model->app_email_send('13',$name,$m_email_id, $content,$subject); 
  if($backoffice_email_id!="")
    $model->app_email_send('13','Team',$backoffice_email_id, $content,$subject);
}

//////////////////////////**Booking successfull sms send to traveler start**////////////////
function booking_successfull_sms_send($m_mobile_no,$customer_id,$tour_id){
  global $app_name,$encrypt_decrypt,$secret_key,$app_contact_no;

  $sq_tour = mysqli_fetch_assoc(mysqlQuery("select tour_name from tour_master where tour_id= '$tour_id'"));
  $tour_name = $sq_tour['tour_name'];
  $sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));

  $message = "Dear ".$sq_customer['first_name']." ".$sq_customer['last_name'].", your Group Tour booking for ".$tour_name." is confirmed. Youcher details will send you within 2 working days prior to the tour. Please contact for more details ".$app_contact_no."." ;
  
  global $model;
  $model->send_message($m_mobile_no, $message);
 }
 public function whatsapp_send(){
  global $app_contact_no,$encrypt_decrypt,$secret_key,$app_name,$session_emp_id;
	$tour_id = $_POST['tour_id'];
  $customer_id = $_POST['customer_id'];
	$tour_group_id = $_POST['tour_group_id'];
	$sq_tour = mysqli_fetch_assoc(mysqlQuery("select tour_name from tour_master where tour_id= '$tour_id'"));
  $tour_name = $sq_tour['tour_name'];
  $sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
  $contact_no = $encrypt_decrypt->fnDecrypt($sq_customer['contact_no'], $secret_key);
  
  $customer_name = ($sq_customer['type'] == 'Corporate' || $sq_customer['type'] == 'B2B') ? $sq_customer['company_name'] : $sq_customer['first_name'].' '.$sq_customer['last_name'];

  $tour_group1 = mysqli_fetch_assoc(mysqlQuery("select from_date, to_date from tour_groups where group_id= '$tour_group_id'"));
  $sq_emp_info = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id= '$session_emp_id'"));
  if($session_emp_id == 0){
		$contact = $app_contact_no;
	}
	else{
		$contact = $sq_emp_info['mobile_no'];
	}
	
	$whatsapp_msg = rawurlencode('Dear '.$customer_name.',
Hope you are doing great. This is to inform you that your booking is confirmed with us. We look forward to provide you a great experience.
*Tour Name* : '.$tour_name.'
*Travel Date* : '.get_date_user($tour_group1['from_date']).'

Please contact for more details : '.$app_name.' '.$contact.'
Thank you.');
	$link = 'https://web.whatsapp.com/send?phone='.$contact_no.'&text='.$whatsapp_msg;
	echo $link;
}
}
?>