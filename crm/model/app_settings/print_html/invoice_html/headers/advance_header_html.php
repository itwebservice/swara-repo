<?php
global $service_charge_switch;
$sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$customer_id'"));
$sq_settings = mysqli_fetch_assoc(mysqlQuery("select * from app_settings"));
$sq_state = mysqli_fetch_assoc(mysqlQuery("select * from state_master where id='$sq_settings[state_id]'"));
$branch_status = $_GET['branch_status'];

$sq_terms_cond = mysqli_fetch_assoc(mysqlQuery("select * from terms_and_conditions where type='Invoice' and active_flag ='Active'")); 
$emp_id = isset($_SESSION['emp_id']) ? $_SESSION['emp_id'] : 1;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$branch_details = mysqli_fetch_assoc(mysqlQuery("select * from branches where branch_id='$branch_admin_id'"));

$sq_emp = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id='$emp_id'"));
if($emp_id == '0'){ $emp_name = 'Admin';}
else { $emp_name = $sq_emp['first_name'].' ' .$sq_emp['last_name']; }
?>
<section class="print_sec_tp_s main_block ">
<!-- invloice_receipt_hedaer_top-->
<div class="main_block inv_rece_header_top mg_bt_10">
  <div class="row">
    <div class="col-md-4">
      <div class="inv_rece_header_left">
        <div class="inv_rece_header_logo mg_tp_30">
          <img src="<?php echo $admin_logo_url ?>" class="img-responsive">
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="inv_rece_header_right text-right">
        <ul class="no-pad no-marg font_s_12 noType">
          <li><h3 class=" font_5 font_s_16 no-marg no-pad caps_text"><?php echo $app_name; ?></h3></li>
          <li><p><?php echo ($branch_status=='yes') ? $branch_details['address1'].','.$branch_details['address2'].','.$branch_details['city'] : $app_address ?></p></li>
          <li><i class="fa fa-phone" style="margin-right: 5px;"></i> <?php echo ($branch_status=='yes') ? 
          $branch_details['contact_no'] : $app_contact_no ?></li>
          <li><i class="fa fa-envelope" style="margin-right: 5px;"></i><?php echo ($branch_status=='yes' && $branch_details['email_id'] != '') ? $branch_details['email_id'] : $app_email_id; ?></li>
          <li><span class="font_5">TAX NO : </span><?php echo ($service_tax_no=='') ? 'NA' : strtoupper($service_tax_no); ?></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<hr class="no-marg">

<!-- invloice_receipt_bottom-->
<div class="main_block inv_rece_header_bottom mg_tp_10">
  <div class="row">
    <div class="col-md-7">
      <?php
      if($customer_id != '' && $customer_id != 0){
      ?>
      <div class="inv_rece_header_left mg_bt_10">
        <ul class="no-marg no-pad noType">
          <li><h3 class="title font_5 font_s_16 no-marg no-pad">TO,</h3></li>
          <li><h3 class=" font_5 font_s_14 no-marg no-pad"><?php echo  $sq_customer['company_name'] ?></h3></li>
          <li><i class="fa fa-user"></i> : <?php echo $sq_customer['first_name'].' '.$sq_customer['last_name']; ?></li>
          <li><i class="fa fa-map-marker"></i> : <?php echo $sq_customer['address']; ?></li>
          <li><i class="fa fa-thumb-tack"></i> : <?php echo $sq_customer['address2']; ?></li>
          <li><i class="fa fa-globe"></i> : <?php echo $sq_customer['city']; ?></li>
          <li><i class="fa fa-phone"></i> : <?php echo $contact_no = $encrypt_decrypt->fnDecrypt($sq_customer['contact_no'], $secret_key);; ?></li>
          <li><span class="font_5">TAX NO : </span> <?php echo $sq_customer['service_tax_no']; ?></li>
        </ul>
      </div>
      <?php } ?>
    </div>
    <div class="col-md-5">
      <div class="inv_rece_header_right">
        <div class="inv_rec_no_detail mg_bt_10">
          <h2 class="inv_rec_no_title font_5 font_s_21 no-marg no-pad">INVOICE</h2>
          <h4 class="inv_rec_no font_5 font_s_14 no-marg no-pad"><?php echo $invoice_no; ?></h4>
        </div>
        <ul class="no-marg no-pad noType">
          <li><span class="font_5">INVOICE FOR </span>: <?php echo $service_name; ?></li>
          <li><span class="font_5">INVOICE DATE </span>: <?php echo $invoice_date; ?></li>
          <li><span class="font_5">PLACE OF SUPPLY </span>: <?php echo $sq_state['state_name']; ?></li>
          <li><span class="font_5">SAC Code </span>: <?php echo $sac_code; ?></li>
        </ul>
      </div>
    </div>
  </div>
</div>
</section>