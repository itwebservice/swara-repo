<?php
//Generic Files
include "../../../../model.php";
include "../../print_functions.php";
require("../../../../../classes/convert_amount_to_word.php");
//Parameters
$invoice_no = $_GET['invoice_no'];
$booking_id = $_GET['booking_id'];
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
$credit_card_charges = $_GET['credit_card_charges'];
$bg = $_GET['bg'];
$canc_amount = $_GET['canc_amount'];

$basic_cost = number_format($basic_cost1, 2);
$sq_hotel = mysqli_fetch_assoc(mysqlQuery("select * from bus_booking_master where booking_id='$booking_id' and delete_status='0'"));
$branch_admin_id = isset($_SESSION['branch_admin_id']) ? $_SESSION['branch_admin_id'] : $sq_hotel['branch_admin_id'];
$roundoff = $sq_hotel['roundoff'];
$bsmValues = json_decode($sq_hotel['bsm_values']);
$tax_show = '';
$newBasic = $basic_cost1;

$charge = ($credit_card_charges != '') ? $credit_card_charges : 0;
$balance_amount = ($balance_amount < 0) ? 0 : $balance_amount;
$total_paid = floatval($total_paid) + floatval($charge);

//Header
if ($app_invoice_format == "Standard") {
  include "../headers/standard_header_html.php";
}
if ($app_invoice_format == "Regular") {
  include "../headers/regular_header_html.php";
}
if ($app_invoice_format == "Advance") {
  include "../headers/advance_header_html.php";
}
//////////////////Service Charge Rules
$service_tax_amount = 0;
$name = '';
if ($sq_hotel['service_tax_subtotal'] !== 0.00 && ($sq_hotel['service_tax_subtotal']) !== '') {
  $service_tax_subtotal1 = explode(',', $sq_hotel['service_tax_subtotal']);
  for ($i = 0; $i < sizeof($service_tax_subtotal1); $i++) {
    $service_tax = explode(':', $service_tax_subtotal1[$i]);
    $service_tax_amount +=  $service_tax[2];
    $name .= $service_tax[0]  . $service_tax[1] . ', ';
  }
}
if ($bsmValues[0]->service != '') {   //inclusive service charge
  $newBasic = $basic_cost1;
  $newSC = $service_tax_amount + $service_charge;
} else {
  $tax_show =  rtrim($name, ', ') . ' : ' . number_format($service_tax_amount,2);
  $newSC = $service_charge;
}
////////////////////Markup Rules
$markupservice_tax_amount = 0;
if ($sq_hotel['markup_tax'] !== 0.00 && $sq_hotel['markup_tax'] !== "") {
  $service_tax_markup1 = explode(',', $sq_hotel['markup_tax']);
  for ($i = 0; $i < sizeof($service_tax_markup1); $i++) {
    $service_tax = explode(':', $service_tax_markup1[$i]);
    $markupservice_tax_amount += $service_tax[2];
  }
}
if ($bsmValues[0]->markup != '') { //inclusive markup
  $newBasic = $basic_cost1 + $sq_hotel['markup'] + $markupservice_tax_amount;
  $tax_show = '';
} else {
  $newBasic = $basic_cost1;
  $newSC = intval($service_charge);
}
////////////Basic Amount Rules
if ($bsmValues[0]->basic != '') { //inclusive markup

  $newBasic = intval($basic_cost1) + intval($service_tax_amount) + intval($sq_hotel['markup']) + intval($markupservice_tax_amount);
}
$other_charges = $markupservice_tax_amount + intval($sq_hotel['markup']);

if($service_charge_switch == 'No'){
  $basic_service_amt = floatval($newBasic) + floatval($newSC);
}
$net_amount1 = 0;
$net_amount1 =  (intval($basic_cost1) + intval($service_charge)  + intval($sq_hotel['markup']) + $markupservice_tax_amount + $service_tax_amount) + $roundoff;
$amount_in_word = $amount_to_word->convert_number_to_words($net_amount1);
?>

<hr class="no-marg">
<div class="col-md-12 mg_tp_20">
  <p class="border_lt"><span class="font_5">BUS DETAILS : </span></p>
</div>
<div class="main_block inv_rece_table main_block">
  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive">
        <table class="table table-bordered no-marg" id="tbl_emp_list" style="padding: 0 !important;">
          <thead>
            <tr class="table-heading-row">
              <th>SR.NO</th>
              <th>Bus_Operator</th>
              <th>Seat_Type</th>
              <th>Bus_Type</th>
              <th>PNR_No</th>
              <th>Journey DateTime</th>
              <th>Source</th>
              <th>Destination</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $count = 1;
            $sq_vehicle_entries = mysqlQuery("select * from bus_booking_entries where booking_id='$booking_id'");
            while ($row_vehicle = mysqli_fetch_assoc($sq_vehicle_entries)) {

            ?>
              <tr class="odd">
                <td><?php echo $count; ?></td>
                <td><?php echo $row_vehicle['company_name']; ?></td>
                <td><?= $row_vehicle['seat_type'] ?></td>
                <td><?php echo ($row_vehicle['bus_type']); ?></td>
                <td><?php echo $row_vehicle['pnr_no']; ?></td>
                <td><?php echo date('d-m-y H:i', strtotime($row_vehicle['date_of_journey'])); ?></td>
                <td><?php echo $row_vehicle['origin']; ?></td>
                <td><?php echo $row_vehicle['destination']; ?></td>
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
      <div class="main_block inv_rece_calculation border_block">
        <?php if($service_charge_switch == 'No'){ ?>
          <div class="col-md-6"><p class="border_lt"><span class="font_5">BASIC AMOUNT </span><span class="float_r"><?php echo $currency_code." ".number_format($basic_service_amt,2); ?></span></p></div>
        <?php }else{ ?>
          <div class="col-md-6"><p class="border_lt"><span class="font_5">BASIC AMOUNT </span><span class="float_r"><?php echo $currency_code." ".number_format($newBasic,2); ?></span></p></div>
        <?php } ?>
        <div class="col-md-6">
          <p class="border_lt"><span class="font_5">TOTAL </span><span class="font_5 float_r"><?= $currency_code . " " . number_format($net_amount1, 2) ?></span></p>
        </div>
        <div class="col-md-6"><p class="border_lt"><span class="font_5">OTHER CHARGES AND TAXES </span><span class="float_r">
            <?php  echo $currency_code." ".number_format($other_charges,2) ?></span></p>
        </div>
        <div class="col-md-6">
          <p class="border_lt"><span class="font_5">CREDIT CARD CHARGES </span><span class="float_r"><?= $currency_code . " " . number_format($charge, 2) ?></span></p>
        </div>
        <?php if($service_charge_switch == 'Yes'){ ?>
          <div class="col-md-6">
              <p class="border_lt"><span class="font_5">SERVICE CHARGE </span><span class="float_r"><?php echo $currency_code." ".number_format($newSC,2); ?></span></p>
          </div>
        <?php }else{ ?>
          <div class="col-md-6">
              <p class="border_lt"><span class="font_5"> </span><span class="float_r"></span></p>
          </div>
        <?php } ?>
        <div class="col-md-6">
          <p class="border_lt"><span class="font_5">ADVANCE PAID </span><span class="font_5 float_r"><?= $currency_code . " " . number_format($total_paid, 2) ?></span></p>
        </div>
        <div class="col-md-6">
          <p class="border_lt"><span class="font_5">TAX</span><span class="float_r"><?= $currency_code . " " . $tax_show ?></span></p>
        </div>
        <?php
        if($bg != ''){ ?>
          <div class="col-md-6">
            <p class="border_lt"><span class="font_5">CANCELLATION CHARGES </span><span class="font_5 float_r"><?= $currency_code . " " . number_format($canc_amount, 2) ?></span></p>
          </div>
          <div class="col-md-6">
            <p class="border_lt"><span class="font_5">ROUNDOFF</span><span class="float_r"><?= $currency_code . " " . $roundoff ?></span></p>
          </div>
        <?php } ?>
        <div class="col-md-6">
          <p class="border_lt"><span class="font_5">CURRENT DUE </span><span class="font_5 float_r"><?= $currency_code . " " . number_format($balance_amount, 2) ?></span></p>
        </div>
        <?php
        if($bg == ''){ ?>
          <div class="col-md-6">
            <p class="border_lt"><span class="font_5">RoundOff</span><span class="float_r"><?= $currency_code . " " . $roundoff ?></span></p>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</section>
<?php
//Footer
include "../generic_footer_html.php"; ?>