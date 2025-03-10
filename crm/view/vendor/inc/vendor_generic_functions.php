<?php
function get_estimate_type_name($estimate_type, $estimate_type_id){

	$estimate_type_val = '';
	if($estimate_type=="Group Tour"){

		$sq_tour_group = mysqli_fetch_assoc(mysqlQuery("select * from tour_groups where group_id='$estimate_type_id'"));
		$tour_group = date('d-m-Y', strtotime($sq_tour_group['from_date'])).' to '.date('d-m-Y', strtotime($sq_tour_group['to_date']));

		$sq_tour = mysqli_fetch_assoc(mysqlQuery("select * from tour_master where active_flag='Active' and tour_id='$sq_tour_group[tour_id]'"));
		$tour_name = $sq_tour['tour_name'];
		$estimate_type_val = $tour_name."( ".$tour_group." )";

	}
	if($estimate_type=="Package Tour"){
		$sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from package_tour_booking_master where booking_id='$estimate_type_id' "));
		$date = $sq_booking['booking_date'];
         $yr = explode("-", $date);
         $year =$yr[0];
		
		$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_booking[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
		  $cust_name = $sq_customer['company_name'];
		}else{
		  $cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}			
		$estimate_type_val = get_package_booking_id($sq_booking['booking_id'],$year)." : ".$cust_name;
	}
	if($estimate_type=="Car Rental"){
		$sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from car_rental_booking where booking_id='$estimate_type_id' "));	
		$date = $sq_booking['created_at'];
         $yr = explode("-", $date);
         $year =$yr[0];
		$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_booking[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
		  $cust_name = $sq_customer['company_name'];
		}else{
		  $cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}			
		$estimate_type_val = get_car_rental_booking_id($sq_booking['booking_id'],$year).' : '.$cust_name;
	}
	if($estimate_type=="Visa"){
		$sq_visa = mysqli_fetch_assoc(mysqlQuery("select * from visa_master where visa_id='$estimate_type_id' "));
		$date = $sq_visa['created_at'];
         $yr = explode("-", $date);
         $year =$yr[0];
		$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_visa[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
		  $cust_name = $sq_customer['company_name'];
		}else{
		  $cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}
   		$estimate_type_val = get_visa_booking_id($sq_visa['visa_id'],$year).' : '.$cust_name;
	}
	if($estimate_type=="Flight"){
		$sq_ticket = mysqli_fetch_assoc(mysqlQuery("select * from ticket_master where ticket_id='$estimate_type_id' "));
		$date = $sq_ticket['created_at'];
         $yr = explode("-", $date);
         $year =$yr[0];
		$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_ticket[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
		  $cust_name = $sq_customer['company_name'];
		}else{
		  $cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}
   		$estimate_type_val = get_ticket_booking_id($sq_ticket['ticket_id'],$year).' : '.$cust_name;
	}
	if($estimate_type=="Train"){
		$sq_ticket = mysqli_fetch_assoc(mysqlQuery("select * from train_ticket_master where train_ticket_id='$estimate_type_id' "));
		$date = $sq_ticket['created_at'];
		$yr = explode("-", $date);
		$year =$yr[0];
		$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_ticket[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
		  $cust_name = $sq_customer['company_name'];
		}else{
		  $cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}
   		$estimate_type_val = get_train_ticket_booking_id($sq_ticket['train_ticket_id'],$year).' : '.$cust_name;
	}
	if($estimate_type=="Hotel"){
		$sq_hotel_booking = mysqli_fetch_assoc(mysqlQuery("select * from hotel_booking_master where booking_id='$estimate_type_id' "));
		$date = $sq_hotel_booking['created_at'];
		$yr = explode("-", $date);
		$year =$yr[0];
		$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_hotel_booking[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
			$cust_name = $sq_customer['company_name'];
		}else{
			$cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}
		$estimate_type_val = get_hotel_booking_id($sq_hotel_booking['booking_id'],$year).' : '.$cust_name;
	}
	if($estimate_type=="Bus"){
		$sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from bus_booking_master where booking_id='$estimate_type_id' "));
		$date = $sq_booking['created_at'];
         $yr = explode("-", $date);
         $year =$yr[0];
        $sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_booking[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
		  $cust_name = $sq_customer['company_name'];
		}else{
		  $cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}
        $estimate_type_val = get_bus_booking_id($sq_booking['booking_id'],$year).' : '.$cust_name;
	}
	if($estimate_type=="Miscellaneous"){
		$sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from miscellaneous_master where misc_id='$estimate_type_id' "));
		$date = $sq_booking['created_at'];
         $yr = explode("-", $date);
         $year =$yr[0];
        $sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_booking[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
		  $cust_name = $sq_customer['company_name'];
		}else{
		  $cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}
        $estimate_type_val = get_misc_booking_id($sq_booking['misc_id'],$year).' : '.$cust_name;
	}
	if($estimate_type=="Activity"){
		$sq_exc = mysqli_fetch_assoc(mysqlQuery("select * from excursion_master where exc_id='$estimate_type_id' "));
		$date = $sq_exc['created_at'];
		$yr = explode("-", $date);
		$year =$yr[0];
		$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_exc[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
			$cust_name = $sq_customer['company_name'];
		}else{
			$cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}
		$estimate_type_val = get_exc_booking_id($sq_exc['exc_id'],$year).' : '.$cust_name;
	}
	if($estimate_type=="B2B"){
		$sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from b2b_booking_master where booking_id='$estimate_type_id'"));
		$date = $sq_booking['created_at'];
         $yr = explode("-", $date);
         $year =$yr[0];
		$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_booking[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
		  $cust_name = $sq_customer['company_name'];
		}else{
		  $cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}
		$estimate_type_val = get_b2b_booking_id($sq_booking['booking_id'],$year).' : '.$cust_name;
	}
	if($estimate_type=="B2C"){
		$sq_booking = mysqli_fetch_assoc(mysqlQuery("select * from b2c_sale where booking_id='$estimate_type_id'"));
		$date = $sq_booking['created_at'];
		$yr = explode("-", $date);
		$year =$yr[0];
		$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_booking[customer_id]'"));
		if($sq_customer['type'] == 'Corporate'||$sq_customer['type']=='B2B'){
			$cust_name = $sq_customer['company_name'];
		}else{
			$cust_name = $sq_customer['first_name'].' '.$sq_customer['last_name'];
		}
		$estimate_type_val = get_b2c_booking_id($sq_booking['booking_id'],$year).' : '.$cust_name;
	}

	return $estimate_type_val;

}

function get_vendor_name($vendor_type, $vendor_type_id)
{
	$vendor_type_val = '';
	if($vendor_type=="Hotel Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from hotel_master where hotel_id='$vendor_type_id'"));
		$vendor_type_val = $sq_hotel['hotel_name'];
	}	
	if($vendor_type=="Transport Vendor"){
		$sq_transport = mysqli_fetch_assoc(mysqlQuery("select * from transport_agency_master where transport_agency_id='$vendor_type_id'"));
		$vendor_type_val = $sq_transport['transport_agency_name'];
	}	
	if($vendor_type=="Car Rental Vendor"){
		$sq_cra_rental_vendor = mysqli_fetch_assoc(mysqlQuery("select * from car_rental_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_val = $sq_cra_rental_vendor['vendor_name'];
	}
	if($vendor_type=="DMC Vendor"){
		$sq_dmc_vendor = mysqli_fetch_assoc(mysqlQuery("select * from dmc_master where dmc_id='$vendor_type_id'"));
		$vendor_type_val = $sq_dmc_vendor['company_name'];
	}
	if($vendor_type=="Visa Vendor"){
		$sq_visa_vendor = mysqli_fetch_assoc(mysqlQuery("select * from visa_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_val = $sq_visa_vendor['vendor_name'];
	}
	if($vendor_type=="Ticket Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from ticket_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_val = $sq_vendor['vendor_name'];
	}
	if($vendor_type=="Train Ticket Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from train_ticket_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_val = $sq_vendor['vendor_name'];
	}
	if($vendor_type=="Excursion Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from site_seeing_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_val = $sq_vendor['vendor_name'];
	}
	if($vendor_type=="Insurance Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from insuarance_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_val = $sq_vendor['vendor_name'];
	}
	if($vendor_type=="Other Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from other_vendors where vendor_id='$vendor_type_id'"));
		$vendor_type_val = $sq_vendor['vendor_name'];
	}
	if($vendor_type=="Cruise Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from cruise_master where cruise_id='$vendor_type_id'"));
		$vendor_type_val = $sq_vendor['company_name'];
	}

	return $vendor_type_val;
}

function get_vendor_email($vendor_type, $vendor_type_id)
{
	global $encrypt_decrypt, $secret_key;
	if($vendor_type=="Hotel Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from hotel_master where hotel_id='$vendor_type_id'"));
		$vendor_type_email = $sq_hotel['email_id'];
	}	
	if($vendor_type=="Transport Vendor"){
		$sq_transport = mysqli_fetch_assoc(mysqlQuery("select * from transport_agency_master where transport_agency_id='$vendor_type_id'"));
		$vendor_type_email = $sq_transport['email_id'];
	}	
	if($vendor_type=="Car Rental Vendor"){
		$sq_cra_rental_vendor = mysqli_fetch_assoc(mysqlQuery("select * from car_rental_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_email = $sq_cra_rental_vendor['email'];
	}
	if($vendor_type=="DMC Vendor"){
		$sq_dmc_vendor = mysqli_fetch_assoc(mysqlQuery("select * from dmc_master where dmc_id='$vendor_type_id'"));
		$vendor_type_email = $sq_dmc_vendor['email_id'];
	}
	if($vendor_type=="Visa Vendor"){
		$sq_visa_vendor = mysqli_fetch_assoc(mysqlQuery("select * from visa_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_email = $sq_visa_vendor['email_id'];
	}
	if($vendor_type=="Passport Vendor"){
		$sq_passport_vendor = mysqli_fetch_assoc(mysqlQuery("select * from passport_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_email = $sq_passport_vendor['email_id'];
	}
	if($vendor_type=="Ticket Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from ticket_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_email = $sq_vendor['email_id'];
	}
	if($vendor_type=="Train Ticket Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from train_ticket_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_email = $sq_vendor['email_id'];
	}
	if($vendor_type=="Excursion Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from site_seeing_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_email = $sq_vendor['email_id'];
	}
	if($vendor_type=="Insurance Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from insuarance_vendor where vendor_id='$vendor_type_id'"));
		$vendor_type_email = $sq_vendor['email_id'];
	}
	if($vendor_type=="Other Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from other_vendors where vendor_id='$vendor_type_id'"));
		$vendor_type_email = $sq_vendor['email_id'];
	}
	if($vendor_type=="Cruise Vendor"){
		$sq_vendor = mysqli_fetch_assoc(mysqlQuery("select * from cruise_master where cruise_id='$vendor_type_id'"));
		$vendor_type_email = $sq_vendor['email_id'];
	}

	$vendor_type_email = $encrypt_decrypt->fnDecrypt($vendor_type_email, $secret_key);
	return $vendor_type_email;
}

function get_opening_bal($vendor_type, $vendor_type_id)
{
	$values = array();
	if($vendor_type=="Hotel Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from hotel_master where hotel_id='$vendor_type_id'"));
	}	
	if($vendor_type=="Transport Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from transport_agency_master where transport_agency_id='$vendor_type_id'"));
	}	
	if($vendor_type=="Car Rental Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from car_rental_vendor where vendor_id='$vendor_type_id'"));
	}
	if($vendor_type=="DMC Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from dmc_master where dmc_id='$vendor_type_id'"));
	}
	if($vendor_type=="Visa Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from visa_vendor where vendor_id='$vendor_type_id'"));
	}
	if($vendor_type=="Passport Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from passport_vendor where vendor_id='$vendor_type_id'"));
	}
	if($vendor_type=="Ticket Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from ticket_vendor where vendor_id='$vendor_type_id'"));
	}
	if($vendor_type=="Train Ticket Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from train_ticket_vendor where vendor_id='$vendor_type_id'"));
	}
	if($vendor_type=="Excursion Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from site_seeing_vendor where vendor_id='$vendor_type_id'"));
	}
	if($vendor_type=="Insurance Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from insuarance_vendor where vendor_id='$vendor_type_id'"));
	}
	if($vendor_type=="Other Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from other_vendors where vendor_id='$vendor_type_id'"));
	}
	if($vendor_type=="Cruise Vendor"){
		$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from cruise_master where cruise_id='$vendor_type_id'"));
	}
	$values[0] = $sq_hotel['opening_balance'];
	$values[1] = $sq_hotel['side'];

	return array('opening_balance'=>$values[0],'side'=>$values[1]);
}
?>