<?php 

class app_settings_master{



public function app_basic_info_save()

{

	$app_version = trim($_POST['app_version']);
	$currency_code = $_POST['currency_code'];
	$app_contact_no = $_POST['app_contact_no'];
	$app_landline_no = $_POST['app_landline_no'];
	$tax_name = trim(strtoupper($_POST['tax_name']));
	$service_tax_no = strtoupper($_POST['service_tax_no']);
	$app_address = trim($_POST['app_address']);
	$app_website = $_POST['app_website'];
	$app_name = trim($_POST['app_name']);
	$app_cin = $_POST['cin_no'];

	$cancel_pdf_url = $_POST['pdf_upload_url'];
	$credit_card = $_POST['credit_card'];

	$state = $_POST['state'];
	$country = $_POST['country'];
	$acc_email = $_POST['acc_email'];
	$tax_type = $_POST['tax_type'];
	$tax_pay_date = $_POST['tax_pay_date'];
	$tax_pay_date1 = get_date_db($tax_pay_date);
	$qr_url = $_POST['qr_url'];
	$sign_url = $_POST['sign_url'];
	
 	$sq_app_setting_count = mysqli_num_rows(mysqlQuery("select * from app_settings"));
 	$app_address = addslashes($app_address);	
 	$app_name = addslashes($app_name);
	if($sq_app_setting_count == '0'){
		$sq_max = mysqli_fetch_assoc(mysqlQuery("select max(setting_id) as max from app_settings"));

		$setting_id = $sq_max['max'] + 1;

		$query = "insert into app_settings ( setting_id, app_version,currency, app_contact_no, app_landline_no,  tax_name, service_tax_no, app_address, app_website, app_name,app_cin, policy_url , state_id, accountant_email, tax_type, tax_pay_date, credit_card_charges,country,qr_url,sign_url) 
		values ( '$setting_id', '$app_version','$currency_code', '$app_contact_no', '$app_landline_no', '$tax_name', '$service_tax_no', '$app_address', '$app_website', '$app_name','$app_cin', '$cancel_pdf_url','$state','$acc_email','$tax_type','$tax_pay_date1','$credit_card','$country','$qr_url','$sign_url')";

		$sq_setting = mysqlQuery($query);

		if($sq_setting){


			echo "Company profile details has been successfully saved.";

			exit;

		}

		else{

			echo "error--Sorry, Company profile details are not saved!";

		}

	}

	else{
		$checkUpload = mysqli_fetch_assoc(mysqlQuery("select * from app_settings"));
		if(empty($qr_url) && !empty($checkUpload['qr_url']))
		{
			$qr_url = $checkUpload['qr_url'];
		}
		if(empty($sign_url) && !empty($checkUpload['sign_url']))
		{
			$sign_url = $checkUpload['sign_url'];
		}
		$query = "update app_settings set app_version = '$app_version', app_contact_no = '$app_contact_no', app_landline_no='$app_landline_no', tax_name='$tax_name', service_tax_no = '$service_tax_no', app_address = '$app_address', app_website = '$app_website', app_name = '$app_name',app_cin='$app_cin', policy_url='$cancel_pdf_url', currency='$currency_code', state_id='$state', accountant_email='$acc_email', tax_type='$tax_type', tax_pay_date='$tax_pay_date1',credit_card_charges='$credit_card',country='$country',qr_url='$qr_url',sign_url='$sign_url' where setting_id='1'";
		$sq_setting = mysqlQuery($query);
	
		if($sq_setting){
			echo "Company profile details has been successfully updated.";
			exit;
		}
		else{
			echo "error--Sorry, Company profile details are not saved!";
		}
	}
}

function app_cred_info_save()

{
	global $encrypt_decrypt,$secret_key;
	$app_email_id = trim($_POST['app_email_id']);
	$sms_username = trim($_POST['sms_username']);
	$app_email_id = trim($_POST['app_email_id']);
	$sms_password = $_POST['sms_password'];
	$app_smtp_status = $_POST['app_smtp_status'];
	$app_smtp_host = trim($_POST['app_smtp_host']);
	$app_smtp_port = trim($_POST['app_smtp_port']);
	$app_smtp_password = $_POST['app_smtp_password'];
	$app_smtp_method = trim($_POST['app_smtp_method']);
	$current_password = $_POST['current_password'];
	$new_password = $_POST['new_password'];
	$re_password = $_POST['re_password'];
	$ip_address = trim($_POST['ip_address']);

 	$sq_app_setting_count = mysqli_num_rows(mysqlQuery("select * from app_settings"));

	if($sq_app_setting_count==0){

		$sq_max = mysqli_fetch_assoc(mysqlQuery("select max(setting_id) as max from app_settings"));

		$setting_id = $sq_max['max'] + 1;

		$sq_setting = mysqlQuery("insert into app_settings ( setting_id, app_email_id,sms_username, sms_password,  app_smtp_status, app_smtp_host, app_smtp_port, app_smtp_password, app_smtp_method,ip_addresses ) values ( '$setting_id', '$app_email_id' ,'$sms_username', '$sms_password', '$app_smtp_status', '$app_smtp_host', '$app_smtp_port', '$app_smtp_password', '$app_smtp_method','$ip_address')");

				
		if($sq_setting){

			echo "Company profile details are saved successfully!";

			exit;

		}

		else{

			echo "error--Sorry, Company profile details are not saved!";

		}



	}

	else{

		

		$sq_setting = mysqlQuery("update app_settings set app_email_id = '$app_email_id', sms_username = '$sms_username', sms_password = '$sms_password', app_smtp_status='$app_smtp_status', app_smtp_host='$app_smtp_host', app_smtp_port='$app_smtp_port', app_smtp_password='$app_smtp_password', app_smtp_method='$app_smtp_method',ip_addresses='$ip_address' where setting_id='1'");

		if($new_password !='' and $re_password !=''){

			$current_password = $encrypt_decrypt->fnEncrypt($current_password, $secret_key);
			$sq_password_count = mysqli_num_rows(mysqlQuery("select * from roles where emp_id='0' and password='$current_password'"));

			if($sq_password_count==1){

				if(strcasecmp($new_password, $re_password) == 0){
					$new_password = $encrypt_decrypt->fnEncrypt($new_password, $secret_key);
					$sq_password = mysqlQuery("update roles set password = '$new_password' where emp_id='0'");
				}

				

			}

			else

			{

				echo "error--Sorry, Current password is incorrect!";

				exit;

			}

	    }

		if($sq_setting){

			echo "Company profile details are saved successfully!";

			exit;

		}

		else{

			echo "Company profile details are not saved!";

		}

	}
}

function app_format_save(){

	$invoice_format_list = $_POST['invoice_format_list'];
	$quot_format = $_POST['quot_format'];
	$image = $_POST['image'];
	$dest_id = $_POST['dest_id'];
	$sq_invoice = mysqlQuery("update generic_count_master set invoice_format='$invoice_format_list' where id='1'");

	$sq_app_setting_count = mysqli_num_rows(mysqlQuery("select setting_id from app_settings"));
	if($sq_app_setting_count == '0'){
		$sq_max = mysqli_fetch_assoc(mysqlQuery("select max(setting_id) as max from app_settings"));

		$setting_id = $sq_max['max'] + 1;

		$query = "insert into app_settings ( setting_id, quot_format, quot_img_url,format_dest_id) values ( '$setting_id', '$quot_format', '$image','$dest_id')";

		$sq_setting = mysqlQuery($query);
	}
	else{
		if(empty($dest_id))
		{
			$sq_invoice = mysqlQuery("update app_settings set quot_format='$quot_format',quot_img_url='$image',format_dest_id='0' where setting_id='1'");
		}
		mysqlQuery("UPDATE `format_image_master` SET `is_selected` = '0' WHERE dest_id='$dest_id'");
		mysqlQuery("UPDATE `format_image_master` SET `is_selected` = '1' WHERE dest_id='$dest_id' and img_url='$image'");
	}


	if($sq_invoice){
		echo "Company profile details are saved successfully!! ";
		exit;
	}
	else{
		echo "error--Sorry, Company profile details are not saved!";
	}

}




}

