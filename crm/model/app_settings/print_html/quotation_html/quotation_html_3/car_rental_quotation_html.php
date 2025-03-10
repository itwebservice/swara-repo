<?php 
//Generic Files
include "../../../../model.php"; 
include "printFunction.php";
global $app_quot_img,$currency;

$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$sq = mysqli_fetch_assoc(mysqlQuery("select * from branch_assign where link='package_booking/quotation/car_flight/car_rental/index.php'"));
$branch_status = $sq['branch_status'];
if ($branch_admin_id != 0) {
  $branch_details = mysqli_fetch_assoc(mysqlQuery("select * from branches where branch_id='$branch_admin_id'"));
  $sq_bank_count = mysqli_num_rows(mysqlQuery("select * from bank_master where branch_id='$branch_admin_id' and active_flag='Active'"));
  $sq_bank_branch = mysqli_fetch_assoc(mysqlQuery("select * from bank_master where branch_id='$branch_admin_id' and active_flag='Active'"));
} else {
  $branch_details = mysqli_fetch_assoc(mysqlQuery("select * from branches where branch_id='1'"));
  $sq_bank_count = mysqli_num_rows(mysqlQuery("select * from bank_master where branch_id='1' and active_flag='Active'"));
  $sq_bank_branch = mysqli_fetch_assoc(mysqlQuery("select * from bank_master where branch_id='1' and active_flag='Active'"));
}

$quotation_id = $_GET['quotation_id'];

$sq_terms_cond = mysqli_fetch_assoc(mysqlQuery("select * from terms_and_conditions where type='Car Rental Quotation' and active_flag ='Active'"));

$sq_quotation = mysqli_fetch_assoc(mysqlQuery("select * from car_rental_quotation_master where quotation_id='$quotation_id'"));
$sq_login = mysqli_fetch_assoc(mysqlQuery("select * from roles where id='$sq_quotation[login_id]'"));
$sq_emp_info = mysqli_fetch_assoc(mysqlQuery("select * from emp_master where emp_id='$sq_login[emp_id]'"));
$quotation_date = $sq_quotation['quotation_date'];
$yr = explode("-", $quotation_date);
$year =$yr[0];

if($sq_emp_info['first_name']==''){
  $emp_name = 'Admin';
}
else{
  $emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];
}

$tax_show = '';
$service_charge = $sq_quotation['service_charge'];
$newBasic = $basic_cost1 = $sq_quotation['subtotal'] + $sq_quotation['other_charge'] + $sq_quotation['state_entry'] + $service_charge + $sq_quotation['markup_cost'];
$bsmValues = json_decode($sq_quotation['bsm_values']);
//////////////////Service Charge Rules
$service_tax_amount = 0;
$percent = '';
if ($sq_quotation['service_tax_subtotal'] !== 0.00 && ($sq_quotation['service_tax_subtotal']) !== '') {
    $service_tax_subtotal1 = explode(',', $sq_quotation['service_tax_subtotal']);
    for ($i = 0; $i < sizeof($service_tax_subtotal1); $i++) {
        $service_tax = explode(':', $service_tax_subtotal1[$i]);
        $service_tax_amount +=  $service_tax[2];
        $percent .= $service_tax[0]  . $service_tax[1] .', ';
    }
}
////////////////////Markup Rules
$markupservice_tax_amount = 0;
if($sq_quotation['markup_cost_subtotal'] !== 0.00 && $sq_quotation['markup_cost_subtotal'] !== ""){
  $service_tax_markup1 = explode(',',$sq_quotation['markup_cost_subtotal']);
  for($i=0;$i<sizeof($service_tax_markup1);$i++){
    $service_tax = explode(':',$service_tax_markup1[$i]);
    $markupservice_tax_amount += $service_tax[2];
  }
}

// if(($bsmValues[0]->service != '' || $bsmValues[0]->basic != '')  && $bsmValues[0]->markup != ''){
//   $tax_show = '';
//   $newBasic = $basic_cost1 + $sq_quotation['markup_cost'] + $markupservice_tax_amount + $service_charge + $service_tax_amount;
// }
// elseif(($bsmValues[0]->service == '' || $bsmValues[0]->basic == '')  && $bsmValues[0]->markup == ''){
//   $tax_show = $percent.' '. ($markupservice_tax_amount + $service_tax_amount);
//   $newBasic = $basic_cost1 + $sq_quotation['markup_cost'] + $service_charge;
// }
// elseif(($bsmValues[0]->service != '' || $bsmValues[0]->basic != '') && $bsmValues[0]->markup == ''){
//   $tax_show = $percent.' '. ($markupservice_tax_amount);
//   $newBasic = $basic_cost1 + $sq_quotation['markup_cost'] + $service_charge + $service_tax_amount;
// }
// else{
//   $tax_show = $percent.' '. ($service_tax_amount);
//   $newBasic = $basic_cost1 + $sq_quotation['markup_cost'] + $service_charge + $markupservice_tax_amount;
// }
$total_tax = currency_conversion($currency, $currency, ($markupservice_tax_amount + $service_tax_amount));
$tax_show = $percent . ' ' .$total_tax;
$quotation_cost = currency_conversion($currency,$currency,$sq_quotation['total_tour_cost']);
?>

<!-- landingPage -->
<section class="landingSec main_block">

  <div class="landingPageTop main_block">
    <img src="<?= $app_quot_img?>" class="img-responsive">
    <span class="landingPageId"><?= get_quotation_id($quotation_id,$year) ?></span>
    <div class="landingdetailBlock">
      <div class="detailBlock text-center" style="border-top:0px;">
        <div class="detailBlockIcon detailBlockBlue">
                <i class="fa fa-calendar"></i>
        </div>
        <div class="detailBlockContent">
          <h3 class="contentValue"><?= get_date_user($sq_quotation['quotation_date']) ?></h3>
          <span class="contentLabel">QUOTATION DATE</span>
        </div>
      </div>

      <div class="detailBlock text-center">
        <div class="detailBlockIcon detailBlockGreen">
            <i class="fa fa-hourglass-half"></i>
        </div>
        <div class="detailBlockContent">
          <h3 class="contentValue"><?= $sq_quotation['days_of_traveling'] ?></h3>
          <span class="contentLabel">TOTAL DAYS</span>
        </div>
      </div>

      <div class="detailBlock text-center">
        <div class="detailBlockIcon detailBlockYellow">
                <i class="fa fa-users"></i>
        </div>
        <div class="detailBlockContent">
          <h3 class="contentValue"><?= $sq_quotation['total_pax'] ?></h3>
          <span class="contentLabel">TOTAL GUEST</span>
        </div>
      </div>

      <div class="detailBlock text-center">
        <div class="detailBlockIcon detailBlockRed">
                <i class="fa fa-tag"></i>
        </div>
        <div class="detailBlockContent">
          <h3 class="contentValue"><?= $quotation_cost ?></h3>
          <span class="contentLabel">PRICE</span>
        </div>
      </div>
    </div>
  </div>

  <div class="ladingPageBottom main_block side_pad">

    <div class="row">
      <div class="col-md-4">
        <div class="landigPageCustomer mg_tp_20">
          <h3 class="customerFrom">PREPARED FOR</h3>
          <?php if($sq_quotation['customer_name'] != ''){?><span class="customerName mg_tp_10"><i class="fa fa-user"></i> : <?= $sq_quotation['customer_name'] ?></span><br><?php } ?>
          <?php if($sq_quotation['email_id'] != ''){?><span class="customerMail mg_tp_10"><i class="fa fa-envelope"></i> : <?= $sq_quotation['email_id'] ?></span><br><?php } ?>
          <?php if($sq_quotation['mobile_no'] != ''){?><span class="customerMobile mg_tp_10"><i class="fa fa-phone"></i> : <?= $sq_quotation['mobile_no']?></span><br><?php } ?>
          <span class="generatorName mg_tp_10">PREPARED BY <?= $emp_name?></span><br>
        </div>
      </div>
      <div class="col-md-8">
        <div class="print_header_logo main_block">
          <img src="<?= $admin_logo_url ?>" class="img-responsive">
        </div>
        <div class="print_header_contact text-right main_block">
          <span class="title"><?php echo $app_name; ?></span><br>
          <p class="address no-marg"><?php echo ($branch_status=='yes' && $role!='Admin') ? $branch_details['address1'].','.$branch_details['address2'].','.$branch_details['city'] : $app_address; ?></p>
          <p class="no-marg"><i class="fa fa-phone" style="margin-right: 5px;"></i><?php echo ($branch_status=='yes' && $role!='Admin') ? $branch_details['contact_no']  : $app_contact_no; ?></p>
          <p class="no-marg"><i class="fa fa-envelope" style="margin-right: 5px;"></i><?php echo ($branch_status=='yes' && $role!='Admin' && $branch_details['email_id'] != '') ? $branch_details['email_id'] : $app_email_id; ?></p>
          <?php if($app_website != ''){ ?><p><i class="fa fa-globe" style="margin-right: 5px;"></i><?php echo $app_website; ?></p><?php } ?>
        </div>
      </div>
    </div>
    
  </div>
</section>
<?php $no_of_car = ceil($sq_quotation['total_pax']/$sq_quotation['capacity']); ?>
<!-- traveling Information -->
<section class="travelingDetails main_block mg_tp_30">
    <!-- transport -->
    <section class="transportDetails main_block side_pad">
      <div class="row mg_tp_30">
        <div class="col-md-8">
          <div class="table-responsive">
            <table class="table table-bordered no-marg" id="tbl_emp_list">
              <thead>
              <tr class="table-heading-row">
                  <th>ROUTE</th>
                  <?php if($sq_quotation['travel_type']=='Local'){ ?>
                  <th>FROM DATE</th>
                  <th>TO DATE</th>
                  <?php }else{ ?>
                  <th>TRAVELLING DATE</th>
                  <th>FROM DATE</th>
                  <th>TO DATE</th>
                    <?php } ?>
                  <!-- <th>VEHICLE TYPE</th>
                  <th>TRIP TYPE</th> -->
                  <th>NO OF VEHICLE</th>
                </tr>
              </thead>
              <tbody>
              <tr>
                  <td><?= ($sq_quotation['travel_type']=='Outstation')? $sq_quotation['places_to_visit']:$sq_quotation['local_places_to_visit'] ?></td>
                  <?php if($sq_quotation['travel_type']=='Local'){ ?>
                  <td><?= get_date_user($sq_quotation['from_date']) ?></td>
                  <td><?= get_date_user($sq_quotation['to_date']) ?></td>
                  <?php }else{ ?>
                    <td><?= get_datetime_user($sq_quotation['traveling_date']) ?></td>
                    <td><?= get_date_user($sq_quotation['from_date']) ?></td>
                    <td><?= get_date_user($sq_quotation['to_date']) ?></td>
                  <?php } ?>
                  <!-- <td><?= $sq_quotation['vehicle_type'] ?></td>
                  <td><?= $sq_quotation['trip_type'] ?></td> -->
                  <td><?=  $no_of_car ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>  
      </div>
      <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
          <div class="transportImg">
            <img src="<?= BASE_URL ?>images/quotation/car.png" class="img-responsive">
          </div>
        </div>
      </div>
      <div class="row mg_tp_30">
        <div class="col-md-4">
        </div>
        <div class="col-md-8">
          <div class="table-responsive">
            <table class="table table-bordered no-marg" id="tbl_emp_list">
              <thead>
                <tr class="table-heading-row">
                  <th>VEHICLE NAME</th>
                  <!-- <th>PLACES TO VISIT</th> -->
                  <!-- <th>DAILY KM</th> -->
                  <th>EXTRA KM COST</th>
                  <th>EXTRA HR COST</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?= $sq_quotation['vehicle_name'] ?></td>
                  <!-- <td><?= $sq_quotation['places_to_visit'] ?></td> -->
                  <!-- <td><?= $sq_quotation['daily_km'] ?></td> -->
                  <td><?= $sq_quotation['extra_km_cost'] ?></td>
                  <td><?= $sq_quotation['extra_hr_cost'] ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>  
      </div>
    </section>
</section>



<?php if(isset($sq_terms_cond['terms_and_conditions'])){?>
<!-- Inclusion Exclusion --><!-- Terms and Conditions -->
<section class="incluExcluTerms main_block fullHeightLand">


  <!-- Terms and Conditions -->
  <div class="row mg_tp_30">
    
    <div class="col-md-12 mg_tp_30">
      <div class="incluExcluTermsTabPanel main_block mg_tp_30">
          <h3 class="tncTitle">Terms &amp; Conditions</h3>
          <div class="tncContent">
              <pre class="real_text"><?= $sq_terms_cond['terms_and_conditions'] ?></pre>
          </div>
      </div>
    </div>
  </div>
              
</section>
<?php } ?>


<!-- Ending Page -->
<section class="endPageSection main_block">

  <div class="row">
    
    <!-- Costing -->
    <div class="col-md-4 constingBankingPanel endPageLeft fullHeightLand">
          <h3 class="costBankTitle text-right">COSTING Details</h3>
          <div class="col-md-12 text-right mg_bt_20">
            <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/subtotal.png" class="img-responsive"></div>
            <h4 class="no-marg"><?= currency_conversion($currency, $currency, $newBasic) ?></h4>
            <p>TOTAL FARE</p>
          </div>
          <div class="col-md-12 text-right mg_bt_20">
            <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/tax.png" class="img-responsive"></div>
            <h4 class="no-marg"><?= $tax_show ?></h4>
            <p>TAX</p>
          </div>
          <?php if($sq_quotation['travel_type']=="Outstation"){ ?>
          <div class="col-md-12 text-right mg_bt_20">
            <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/permit.png" class="img-responsive"></div>
            <h4 class="no-marg"><?= currency_conversion($currency, $currency, $sq_quotation['permit']) ?></h4>
            <p>PERMIT</p>
          </div>
          <div class="col-md-12 text-right mg_bt_20">
            <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/toll_parking.png" class="img-responsive"></div>
            <h4 class="no-marg"><?= currency_conversion($currency, $currency, $sq_quotation['toll_parking']) ?></h4>
            <p>TOLL/PARKING</p>
          </div>
          <div class="col-md-12 text-right mg_bt_20">
            <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/driver_allowance.png" class="img-responsive"></div>
            <h4 class="no-marg"><?= currency_conversion($currency, $currency, $sq_quotation['driver_allowance']) ?></h4>
            <p>DRIVER ALLOWANCE</p>
          </div>
          <?php } ?>
          <div class="col-md-12 text-right">
            <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/quotationCost.png" class="img-responsive"></div>
            <h4 class="no-marg"><?= currency_conversion($currency, $currency, $sq_quotation['total_tour_cost']) ?></h4>
            <p>QUOTATION COST</p>
          </div>
         
    </div>
    
    <!-- Guest Detail -->
    <div class="col-md-4 passengerPanel endPagecenter fullHeightLand">
          <h3 class="costBankTitle text-center">Guest</h3>
          <div class="col-md-12 text-center mg_bt_30">
            <div class="icon"><img src="<?= BASE_URL ?>images/quotation/Icon/adultIcon.png" class="img-responsive"></div>
            <p>Adult</p>
          </div>
          <div class="col-md-12 text-center mg_bt_30">
            <div class="icon"><img src="<?= BASE_URL ?>images/quotation/Icon/childIcon.png" class="img-responsive"></div>
            <p>Child</p>
          </div>
          <div class="col-md-12 text-center mg_bt_30">
            <div class="icon"><img src="<?= BASE_URL ?>images/quotation/Icon/infantIcon.png" class="img-responsive"></div>
            <p>Infant</p>
          </div>
          <?php 
              if(check_qr()) { ?>
          <div class="col-md-12 text-center" style="margin-top:20px; margin-bottom:20px;">
                        <?= get_qr('Landscape Creative') ?>
                        <br>
                        <h4 class="no-marg">Scan & Pay </h4>
          </div>
          <?php } ?>
    </div>
    
    <!-- Bank Detail -->
    <div class="col-md-4 constingBankingPanel endPageright fullHeightLand">
      <h3 class="costBankTitle text-left">BANK DETAILS</h3>
        <div class="col-md-12 text-left mg_bt_20">
          <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/bankName.png" class="img-responsive"></div>
          <h4 class="no-marg"><?= ($sq_bank_count>0 || $sq_bank_branch['bank_name'] != '') ? $sq_bank_branch['bank_name'] : $bank_name_setting ?></h4>
          <p>BANK NAME</p>
        </div>
        <div class="col-md-12 text-left mg_bt_20">
          <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/branchName.png" class="img-responsive"></div>
          <h4 class="no-marg"><?= ($sq_bank_count>0 || $sq_bank_branch['branch_name'] != '') ? $sq_bank_branch['branch_name'] : $bank_branch_name ?></h4>
      <p>BRANCH</p>
        </div>
        <div class="col-md-12 text-left mg_bt_20">
          <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/accName.png" class="img-responsive"></div>
          <h4 class="no-marg"><?= ($sq_bank_count>0 || $sq_bank_branch['account_type'] != '') ? $sq_bank_branch['account_type'] : $acc_name ?></h4>
          <p>A/C TYPE</p>
        </div>
        <div class="col-md-12 text-left mg_bt_20">
          <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/accNumber.png" class="img-responsive"></div>
          <h4 class="no-marg"><?= ($sq_bank_count>0 || $sq_bank_branch['account_no'] != '') ? $sq_bank_branch['account_no'] : $bank_acc_no  ?></h4>
          <p>A/C NO</p>
        </div>
        <div class="col-md-12 text-left mg_bt_20">
          <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/code.png" class="img-responsive"></div>
          <h4 class="no-marg"><?= ($sq_bank_count>0 || $sq_bank_branch['account_name'] != '') ? $sq_bank_branch['account_name'] : $bank_account_name  ?></h4>
          <p>BANK ACCOUNT NAME</p>
        </div>
        <div class="col-md-12 text-left">
          <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/code.png" class="img-responsive"></div>
          <h4 class="no-marg"><?= ($sq_bank_count>0 || $sq_bank_branch['swift_code'] != '') ? strtoupper($sq_bank_branch['swift_code']) :  strtoupper($bank_swift_code)  ?></h4>
          <p>Swift Code</p>
        </div>
    </div>
  
  </div>

</section>

</body>
</html>