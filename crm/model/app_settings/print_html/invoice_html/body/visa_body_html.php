<?php
//Generic Files
include "../../../../model.php"; 
include "../../print_functions.php";
require("../../../../../classes/convert_amount_to_word.php"); 
global $currency;
//Parameters
$invoice_no = $_GET['invoice_no'];
$visa_id = $_GET['visa_id'];
$invoice_date = $_GET['invoice_date'];
$customer_id = $_GET['customer_id'];
$service_name = $_GET['service_name'];
$basic_cost1 = $_GET['basic_cost'];
$service_charge = $_GET['service_charge'];
$taxation_type = $_GET['taxation_type'];
$service_tax_per = $_GET['service_tax_per'];
$service_tax = $_GET['service_tax'];
$net_amount = $_GET['net_amount'];
$bank_name = isset($_GET['bank_name']) ? $_GET['bank_name'] : '';
$total_paid = $_GET['total_paid'];
$balance_amount = $_GET['balance_amount'];
$sac_code = $_GET['sac_code'];
$pass_count = isset($_GET['pass_count']) ? $_GET['pass_count'] : 0;
$credit_card_charges = $_GET['credit_card_charges'];
$bg = $_GET['bg'];
$canc_amount = $_GET['canc_amount'];

$charge = ($credit_card_charges!='') ? $credit_card_charges : 0 ;
$balance_amount = ($balance_amount < 0) ? 0 : $balance_amount;

$basic_cost = number_format($basic_cost1,2);
$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from visa_master where visa_id='$visa_id' and delete_status='0'"));
$branch_admin_id = isset($_SESSION['branch_admin_id']) ? $_SESSION['branch_admin_id'] : $sq_hotel['branch_admin_id'];
$total_paid = $total_paid + $charge;

$currency_code1 = $sq_hotel['currency_code'];
$bsmValues = json_decode($sq_hotel['bsm_values']);
$tax_show = ''; 
$newBasic = $basic_cost1;
//////////////////Service Charge Rules
$service_tax_amount = 0;
$name = '';
if($sq_hotel['service_tax_subtotal'] !== 0.00 && ($sq_hotel['service_tax_subtotal']) !== ''){
  $service_tax_subtotal1 = explode(',',$sq_hotel['service_tax_subtotal']);
  for($i=0;$i<sizeof($service_tax_subtotal1);$i++){
    $service_tax = explode(':',$service_tax_subtotal1[$i]);
    $service_tax_amount +=  $service_tax[2];
    $name .= $service_tax[0] . $service_tax[1].' ';
  }
}
$service_tax_amount_show = currency_conversion($currency,$currency_code1,$service_tax_amount);

if($bsmValues[0]->service != ''){   //inclusive service charge
  $newBasic = $basic_cost1;
  $newSC = $service_tax_amount + $service_charge;
  $tax_name='';
}
else{
  $tax_show =  rtrim($name, ', ').' : ' . ($service_tax_amount);
  $newSC = $service_charge;
}
////////////////////Markup Rules
$markupservice_tax_amount = 0;
if($sq_hotel['markup_tax'] !== 0.00 && $sq_hotel['markup_tax'] !== ""){
  $service_tax_markup1 = explode(',',$sq_hotel['markup_tax']);
  for($i=0;$i<sizeof($service_tax_markup1);$i++){
    $mservice_tax = explode(':',$service_tax_markup1[$i]);
    $markupservice_tax_amount += $mservice_tax[2];
  }
}
$markupservice_tax_amount_show = currency_conversion($currency,$currency_code1,$markupservice_tax_amount);

if($bsmValues[0]->markup != ''){ //inclusive markup
  $newBasic = $basic_cost1 + $sq_hotel['markup'] + $markupservice_tax_amount;
}
else{
  $newBasic = $basic_cost1;
  $newSC = $service_charge;
  $tax_show = rtrim($name, ', ') .' : ' . ($markupservice_tax_amount + $service_tax_amount);
}
////////////Basic Amount Rules
if($bsmValues[0]->basic != ''){ //inclusive markup
  
  $newBasic = $basic_cost1 + $service_tax_amount;
  $tax_show = '';
}
$net_amount1 = 0;
$net_amount1 =  $basic_cost1 + $service_charge  + $sq_hotel['markup'] + $markupservice_tax_amount + $service_tax_amount;
if($service_charge_switch == 'No'){
  $basic_service_amt = floatval($newBasic) + floatval($newSC);
}

if($bg != ''){
  $due = ($total_paid > $canc_amount) ? 0 : floatval($canc_amount) - floatval($total_paid) + floatval($credit_card_charges);
}else{
  
  $due = (floatval($net_amount1) + floatval($sq_hotel['roundoff'])+ floatval($credit_card_charges)) - floatval($total_paid);
}

$net_total1 = currency_conversion($currency,$sq_hotel['currency_code'],$net_amount1);
$amount_in_word = $amount_to_word->convert_number_to_words($net_total1,$sq_hotel['currency_code']);
//Header
if($app_invoice_format == "Standard"){include "../headers/standard_header_html.php"; }
if($app_invoice_format == "Regular"){include "../headers/regular_header_html.php"; }
if($app_invoice_format == "Advance"){include "../headers/advance_header_html.php"; }
?>

<hr class="no-marg">
<div class="row">
<div class="col-md-12 mg_tp_20"><p class="border_lt"><span class="font_5">PASSENGER(S) :  </span><span><?= $pass_count ?></span></p></div></div>
<div class="main_block inv_rece_table main_block">
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
        <table class="table table-bordered no-marg" id="tbl_emp_list" style="padding: 0 !important;">
          <thead>
            <tr class="table-heading-row">
              <th>SR.NO</th>
              <th>PASSENGER_name</th>
              <th>Visa_country</th>
              <th>Visa Type</th>
            </tr>
          </thead>
          <tbody>   
          <?php 
          $count = 1;
          $sq_passenger = mysqlQuery("select * from visa_master_entries where visa_id = '$visa_id'");
          while($row_passenger = mysqli_fetch_assoc($sq_passenger))
          {
            ?>
            <tr class="odd">
              <td><?php echo $count; ?></td>
              <td><?php echo $row_passenger['first_name'].' '.$row_passenger['last_name']; ?></td>
              <td><?php echo $row_passenger['visa_country_name']; ?></td>
              <td><?php echo $row_passenger['visa_type']; ?></td>
            </tr>
            <?php   
              $count++;
            } ?>
          </tbody>
        </table>
      </div>
    </div>
    </div>
  </div>

<section class="print_sec main_block">

<!-- invoice_receipt_body_calculation -->
  <div class="row">
    <div class="col-md-12">
      <?php
      $total1 = currency_conversion($currency,$sq_hotel['currency_code'],$net_amount1+$sq_hotel['roundoff']);
      $newSC1 = currency_conversion($currency,$sq_hotel['currency_code'],$newSC);
      $charge1 = currency_conversion($currency,$sq_hotel['currency_code'],$charge);
      $total_paid1 = currency_conversion($currency,$sq_hotel['currency_code'],$total_paid);
      $roundoff1 = currency_conversion($currency,$sq_hotel['currency_code'],$sq_hotel['roundoff']);

      $service_tax_amount_show = explode(' ',$service_tax_amount_show);
      $service_tax_amount_show1 = str_replace(',','',$service_tax_amount_show[1]);
      $markupservice_tax_amount_show = explode(' ',$markupservice_tax_amount_show);
      $markupservice_tax_amount_show1 = str_replace(',','',$markupservice_tax_amount_show[1]);
      $other_charges = $markupservice_tax_amount + $sq_hotel['markup'];
      $other_charges = currency_conversion($currency,$sq_hotel['currency_code'],$other_charges);
      
      $due1 = currency_conversion($currency,$sq_hotel['currency_code'],$due);
      $canc_amount = currency_conversion($currency,$sq_hotel['currency_code'],$canc_amount);
      ?>
      <div class="main_block inv_rece_calculation border_block">
        <?php if($service_charge_switch == 'No'){ ?>
          <div class="col-md-6"><p class="border_lt"><span class="font_5">BASIC AMOUNT </span><span class="float_r"><?php echo $currency_code." ".number_format($basic_service_amt,2); ?></span></p></div>
        <?php }else{ 
          $newBasic1 = currency_conversion($currency,$sq_hotel['currency_code'],$newBasic);?>
          <div class="col-md-6"><p class="border_lt"><span class="font_5">BASIC AMOUNT </span><span class="float_r"><?php echo $newBasic1; ?></span></p></div>
        <?php } ?>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">TOTAL </span><span class="font_5 float_r"><?= $total1 ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">OTHER CHARGES AND TAXES </span><span class="float_r"><?= $other_charges ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">CREDIT CARD CHARGES </span><span class="float_r"><?= $charge1 ?></span></p></div>
        <?php if($service_charge_switch == 'Yes'){ ?>
          <div class="col-md-6">
              <p class="border_lt"><span class="font_5">SERVICE CHARGE </span><span class="float_r"><?php echo $newSC1; ?></span></p>
          </div>
        <?php }else{ ?>
          <div class="col-md-6">
              <p class="border_lt"><span class="font_5"> </span><span class="float_r"></span></p>
          </div>
        <?php } ?>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">ADVANCE PAID </span><span class="font_5 float_r"><?= $total_paid1 ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">TAX </span><span class="float_r"><?= str_replace(',','',$name).$service_tax_amount_show[0].' '.number_format($service_tax_amount_show1,2) ?></span></p></div>
        <?php
        if($bg != ''){ ?>
          <div class="col-md-6"><p class="border_lt"><span class="font_5">CANCELLATION CHARGES </span><span class="font_5 float_r"><?= $canc_amount ?></span></p></div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">ROUND OFF </span><span class="font_5 float_r"><?= $roundoff1 ?></span></p></div>
        <?php } ?>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">CURRENT DUE </span><span class="font_5 float_r"><?= $due1 ?></span></p></div>
        <?php
        if($bg == ''){ ?>
          <div class="col-md-6"><p class="border_lt"><span class="font_5">ROUND OFF </span><span class="font_5 float_r"><?= $roundoff1 ?></span></p></div>
        <?php } ?>
      </div>
    </div>
  </div>

</section>
<?php 
//Footer
include "../generic_footer_html.php"; ?>