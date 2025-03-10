<form id="frm_tab_1">

    <div class="app_panel">
        <div class="container-fluid mg_tp_10">
            <div class="">
                <div class="app_panel_content no-pad">
                    <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_20">
                        <legend>Package Tour Details</legend>
                        <div class="app_panel_content Filter-panel">
                            <div class="row ">
                                <?php
                                if ($sq_booking_info['quotation_id'] != 0) {
                                    $sq_cost =  mysqli_fetch_assoc(mysqlQuery("select * from package_tour_quotation_costing_entries where quotation_id = '$sq_booking_info[quotation_id]'"));
                                    $sq_quo =  mysqli_fetch_assoc(mysqlQuery("select * from package_tour_quotation_master where quotation_id = '$sq_booking_info[quotation_id]'"));
                                    $basic_cost = $sq_cost['basic_amount'];
                                    $service_charge = $sq_cost['service_charge'];
                                    $tour_cost = $basic_cost + $service_charge;
                                    $service_tax_amount = 0;
                                    $tax_show = '';
                                    $bsmValues = json_decode($sq_cost['bsmValues']);
                                    $name = '';
                                    if ($sq_cost['service_tax_subtotal'] !== 0.00 && ($sq_cost['service_tax_subtotal']) !== '') {
                                        $service_tax_subtotal1 = explode(',', $sq_cost['service_tax_subtotal']);
                                        for ($i = 0; $i < sizeof($service_tax_subtotal1); $i++) {
                                            $service_tax = explode(':', $service_tax_subtotal1[$i]);
                                            $service_tax_amount +=  $service_tax[2];
                                            $name .= $service_tax[0] . ' ';
                                            $percent = $service_tax[1];
                                        }
                                    }
                                    if ($bsmValues[0]->service != '') {   //inclusive service charge
                                        $newBasic = $tour_cost + $service_tax_amount;
                                        $tax_show = '';
                                    } else {
                                        // $tax_show = $service_tax_amount;
                                        $tax_show =  $name . $percent . ($service_tax_amount);
                                        $newBasic = $tour_cost;
                                    }

                                    ////////////Basic Amount Rules
                                    if ($bsmValues[0]->basic != '') { //inclusive markup
                                        $newBasic = $tour_cost + $service_tax_amount;
                                        $tax_show = '';
                                    }

                                    $quotation_cost = $basic_cost + $service_charge + $service_tax_amount + $sq_quo['train_cost'] + $sq_quo['cruise_cost'] + $sq_quo['flight_cost'] + $sq_quo['visa_cost'] + $sq_quo['guide_cost'] + $sq_quo['misc_cost'];
                                ?>
                                <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs"><input type="text"
                                        title="Quotation ID"
                                        value="<?= 'PTQ-' . $sq_booking_info['quotation_id'] . ' : ' . $sq_quo['customer_name'] . ' : ' . $quotation_cost . ' /-' ?>"
                                        readonly>
                                </div> <?php } ?>
                                <?php
                                if ($sq_booking_info['new_package_id'] != 0) {
                                    $sq_pack =  mysqli_fetch_assoc(mysqlQuery("select * from custom_package_master where package_id = '$sq_booking_info[new_package_id]'"));
                                ?>
                                <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs"><input type="text"
                                        title="Package Name" value="<?= $sq_pack['package_name'] ?>" readonly>
                                </div> <?php } ?>
                                <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10">
                                    <input type="text" id="txt_package_tour_name" name="txt_package_tour_name"
                                        placeholder="Package Tour Name" title="Package Tour Name"
                                        value="<?php echo $sq_booking_info['tour_name'] ?>">
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10">
                                    <select name="tour_type" id="tour_type" title="Tour Type" disabled>
                                        <option value="<?= $sq_booking_info['tour_type'] ?>">
                                            <?= $sq_booking_info['tour_type'] ?></option>
                                        <option value="">Tour Type</option>
                                        <option value="Domestic">Domestic</option>
                                        <option value="International">International</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10">
                                    <input type="text" id="txt_package_from_date" name="txt_package_from_date"
                                        placeholder="*From Date" title="From Date" onchange="get_to_date(this.id,'txt_package_to_date');total_days_reflect();" value="<?php echo get_date_user($sq_booking_info['tour_from_date']) ?>" required>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10">
                                    <input type="text" id="txt_package_to_date" name="txt_package_to_date"
                                        placeholder="*To Date" title="To Date" onchange="validate_validDate('txt_package_from_date','txt_package_to_date');total_days_reflect();" value="<?php echo get_date_user($sq_booking_info['tour_to_date']) ?>" required>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
                                    <input type="text" id="txt_tour_total_days" name="txt_tour_total_days"
                                        placeholder="Total Tour Days" title="Total Tour Days"
                                        onchange="validate_balance(this.id);"
                                        value="<?php echo $sq_booking_info['total_tour_days'] ?>" readonly>
                                </div>
                                <?php if ($sq_booking_info['package_type'] != '') { ?>
                                <div class="col-md-3 col-sm-4 col-xs-12 mg_bt_10_sm_xs">
                                    <input type="text" id="package_type" name="package_type" placeholder="Package Type"
                                        title="Package Type" value="<?php echo $sq_booking_info['package_type'] ?>"
                                        readonly>
                                </div>
                                <?php } ?>
                                <div class="col-md-3 col-sm-4 col-xs-12 hidden">
                                    <select name="taxation_type" id="taxation_type" title="Taxation Type">
                                        <option value="<?= $sq_booking_info['taxation_type'] ?>">
                                            <?= $sq_booking_info['taxation_type'] ?></option>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $count = 0;
                    $sq_tours_count = mysqli_num_rows(mysqlQuery("select * from package_tour_schedule_master where booking_id = '$sq_booking_info[booking_id]'"));
                    if ($sq_booking_info['quotation_id'] == 0) { ?>
                    <input type="hidden" id="sq_tours_count" value="<?=$sq_tours_count?>"/>
                    <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_20">

                        <legend>Tour Itinerary Details</legend>
                        <?php
                        $sq_tours = mysqlQuery("select * from package_tour_schedule_master where booking_id = '$sq_booking_info[booking_id]'");
                        $incl = $sq_booking_info['inclusions'];
                        $excl = $sq_booking_info['exclusions']; ?>
                        <div class="app_panel_content Filter-panel">
                            <div class="row mg_bt_10">
                                <div class="col-xs-12 text-right text_center_xs">
                                    <button type="button" class="btn btn-excel btn-sm" onClick="addRow('package_program_list')" title="Add row"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-6 col-xs-12 mg_bt_10">
                                    <table style="width:100%" id="package_program_list" name="package_program_list"
                                        class="table mg_bt_0 table-bordered">
                                        <tbody>
                                            <?php
                                            if($sq_tours_count > 0){
                                                while ($row_tours = mysqli_fetch_assoc($sq_tours)) {
                                                    $count++; ?>
                                                    <tr>
                                                        <td width="27px;" style="padding-right: 10px !important;"><input
                                                                class="css-checkbox mg_bt_10 labelauty" id="chk_program<?= $count ?>-u"
                                                                type="checkbox" checked style="display: none;"><label
                                                                for="chk_program1"><span
                                                                    class="labelauty-unchecked-image"></span><span
                                                                    class="labelauty-checked-image"></span></label></td>
                                                        <td width="50px;"><input maxlength="15" value="<?= $count ?>"
                                                                type="text" name="username" placeholder="Sr. No."
                                                                class="form-control mg_bt_10" disabled=""></td>
                                                        <td class="col-md-3 no-pad" style="padding-left: 5px !important;"><input
                                                                type="text" id="special_attaraction<?= $count ?>-u"
                                                                onchange="validate_spaces(this.id);validate_spattration(this.id);"
                                                                name="special_attaraction" class="form-control mg_bt_10"
                                                                placeholder="Special Attraction" title="Special Attraction"
                                                                value="<?= $row_tours['attraction'] ?>"></td>
                                                        <td class="col-md-5 no-pad" style="padding-left: 5px !important;"><textarea id="day_program<?= $count ?>-u" name="day_program"
                                                                class="form-control mg_bt_10" title="" rows="3"
                                                                placeholder="*Day-wise Program"
                                                                onchange="validate_spaces(this.id);validate_dayprogram(this.id);"
                                                                title="Day-wise Program"><?= $row_tours['day_wise_program'] ?></textarea>
                                                        </td>
                                                        <td class="col-md-2 no-pad" style="padding-left: 5px !important;"><input type="text" id="overnight_stay<?= $count ?>-u" name="overnight_stay" onchange="validate_spaces(this.id);validate_onstay(this.id);" class="form-control mg_bt_10" placeholder="Overnight Stay" title="Overnight Stay" value="<?= $row_tours['stay'] ?>"></td>
                                                        <td class="col-md-2 no-pad" style="padding-left: 5px !important;"><select id="meal_plan<?= $count ?>" title="" name="meal_plan"
                                                                class="form-control mg_bt_10" data-original-title="Meal Plan">
                                                                <?php if ($row_tours['meal_plan'] != '') { ?>
                                                                <option value="<?= $row_tours['meal_plan'] ?>">
                                                                    <?= $row_tours['meal_plan'] ?></option><?php } ?>
                                                                <?php get_mealplan_dropdown(); ?>
                                                            </select>
                                                        </td>
                                                        <td class='col-md-1 pad_8'><button type="button" class="btn btn-info btn-iti btn-sm" title="Add Itinerary" onClick="add_itinerary('dest_name','special_attaraction<?php echo $count; ?>-u','day_program<?php echo $count; ?>-u','overnight_stay<?php echo $count; ?>-u','Day-<?= $count ?>')"><i class="fa fa-plus"></i></button>
                                                        </td>
                                                        <td style="display:none"><input type="text" value="<?php echo $row_tours['entry_id'] ?>"></td>
                                                    </tr>
                                                <?php }
                                            } else{
                                                ?><tr>
                                                <td><input class="css-checkbox mg_bt_10 labelauty" id="chk_program1" type="checkbox" checked style="display: none;"><label for="chk_program1"><span class="labelauty-unchecked-image"></span><span class="labelauty-checked-image"></span></label></td>
                                                <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled=""></td>
                                                <td style="padding-left: 5px !important;"><input type="text" id="special_attaraction" onchange="validate_spaces(this.id);" name="special_attaraction" class="form-control mg_bt_10" placeholder="Special Attraction" title="Special Attraction"></td>
                                                <td style="padding-left: 5px !important;"><textarea id="day_program" name="day_program" class="form-control mg_bt_10" title="Day-wise Program" rows="3" placeholder="*Day-wise Program" onchange="validate_spaces(this.id);"></textarea></td>
                                                <td style="padding-left: 5px !important;"><input type="text" id="overnight_stay" name="overnight_stay" onchange="validate_spaces(this.id);" class="form-control mg_bt_10" placeholder="Overnight Stay" title="Overnight Stay"></td>
                                                <td style="padding-left: 5px !important;"><select id="meal_plan" title="meal plan" name="meal_plan" class="form-control mg_bt_10" data-original-title="Meal Plan">
                                                        <?php get_mealplan_dropdown(); ?>
                                                        </select></td>
                                                <td class='col-md-1 pad_8'><button type="button" class="btn btn-info btn-iti btn-sm" id="itinerary<?php echo '1'; ?>" title="Add Itinerary" onClick="add_itinerary('dest_name2','special_attaraction','day_program','overnight_stay','Day-1')"><i class="fa fa-plus"></i></button>
                                                </td>
                                                <td style="display:none"><input type="text" name="package_id_n" value="" autocomplete="off" class="form-control" data-original-title="" title=""></td>
                                                </tr>
                                                <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mg_tp_20">
                                <div class="col-md-6">
                                    <h4>Inclusions</h4>
                                    <textarea class="feature_editor" name="incl" id="incl" style="width:100% !important"
                                        rows="8"><?= $incl ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <h4>Exclusions</h4>
                                    <textarea class="feature_editor" name="excl" id="excl" style="width:100% !important"
                                        rows="8"><?= $excl ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_20">
                        <legend>Customer Details</legend>

                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                                <select name="customer_id" id="customer_id1" class="customer_dropdown"
                                    title="Customer Name" style="width:100%" onchange="customer_info_load(this.id,'1')"
                                    disabled>
                                    <?php
                                    $sq_customer = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_booking_info[customer_id]'"));
                                    if ($sq_customer['type'] == 'Corporate' || $sq_customer['type'] == 'B2B') {
                                    ?>
                                    <option value="<?= $sq_customer['customer_id'] ?>">
                                        <?= $sq_customer['company_name'] ?>
                                    </option>
                                    <?php } else { ?>
                                    <option value="<?= $sq_customer['customer_id'] ?>">
                                        <?= $sq_customer['first_name'] . ' ' . $sq_customer['middle_name'] . ' ' . $sq_customer['last_name'] ?>
                                    </option>
                                    <?php } ?>
                                    <?php get_customer_dropdown($role, $branch_admin_id, $branch_status); ?>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                                <input type="text" id="txt_contact_person_name1" name="txt_contact_person_name"
                                    placeholder="Contact Person Name" title="Contact Person Name"
                                    value="<?php echo $sq_booking_info['contact_person_name'] ?>">
                            </div>
                            <?php if ($sq_customer['company_name'] != '') { ?>
                            <div class="company_class">
                                <input type="text" id="company_name1" name="company_name" class="hidden"
                                    title="Company Name" value="<?php echo $sq_customer['company_name'] ?>">
                            </div>
                            <?php } ?>
                            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                                <input type="text" id="txt_m_mobile_no1" name="txt_m_mobile_no" placeholder="Mobile No."
                                    title="Mobile No." value="<?php echo $sq_booking_info['mobile_no'] ?>">
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                                <input type="text" value="<?php echo $sq_booking_info['email_id']; ?>"
                                    name="txt_m_email_id" id="txt_m_email_id1" title="Email ID" placeholder="Email ID"
                                >
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                                <input type="text" id="txt_m_address1" name="txt_m_address" placeholder="Address"
                                    title="Address" value="<?php echo $sq_booking_info['address'] ?>">
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                                <input type="text" id="txt_m_city1" name="txt_m_city" onchange="validate_city(this.id)"
                                    placeholder="City" title="city" value="<?php echo $sq_booking_info['city'] ?>">
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
                                <select id="txt_m_state1" name="txt_m_state" title="State/Country Name" style="width:100%">
                                    <?php
                                    if ($sq_booking_info['state'] != "") {
                                    ?>
                                    <option value="<?php echo $sq_booking_info['state'] ?>">
                                        <?php echo $sq_booking_info['state'] ?></option>
                                    <?php
                                    } ?>
                                    <option value="">State/Country Name</option>

                                    <?php
                                    $sq_country = mysqlQuery("select distinct(state_name) from state_master");
                                    while ($row_country = mysqli_fetch_assoc($sq_country)) {
                                    ?>
                                    <option value="<?= $row_country['state_name'] ?>"><?= $row_country['state_name'] ?>
                                    </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php
                    $tourtype_display = ($sq_booking_info['tour_type'] == "Domestic") ? "disabled" : "";
                    ?>

                    <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_20">
                        <legend>Passenger Details</legend>

                        <div class="row text-right mg_bt_10">
                            <div class="col-xs-12">
                                <button type="button" class="btn btn-excel btn-sm" title="Add Passenger"
                                    onClick="addRow('tbl_package_tour_member')"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table id="tbl_package_tour_member"
                                        class="table table-bordered table-hover table-striped no-marg"
                                        style="width:1504px">
                                        <?php
                                        $count_m = 0;
                                        $bg;
                                        $sq_traveler_grop_details = mysqlQuery("select * from package_travelers_details where booking_id='$booking_id'");
                                        while ($row_traveler_grop_details = mysqli_fetch_assoc($sq_traveler_grop_details)) {
                                            $count_m++;
                                            ($row_traveler_grop_details['status'] == 'Cancel') ? $bg = 'danger' : $bg = "";
                                            ($row_traveler_grop_details['status'] == 'Cancel') ? $disabled = 'disabled' : $disabled = "";
                                            if ($row_traveler_grop_details['gender'] == 'Male') {
                                                $gender = 'M';
                                            } else {
                                                $gender = 'F';
                                            }
                                        ?>
                                        <tr class="<?= $bg ?>">
                                            <td><input id="<?php echo "check-btn-member-" . $count_m . 'm' ?>"
                                                    type="checkbox"
                                                    onchange="get_auto_values('booking_date','total_basic_amt','payment_mode','service_charge','markup','update','true','service_charge')"
                                                    checked disabled></td>
                                            <td><input maxlength="15" type="text" name="username"
                                                    value="<?php echo $count_m ?>" placeholder="ID" disabled /></td>
                                            <td><select id="cmb_m_honorific1" name="cmb_m_honorific1"
                                                    onchange="changeGender(this.id)" title="Honorific"
                                                    <?= $disabled; ?>>
                                                    <option
                                                        value="<?php echo $row_traveler_grop_details['m_honorific'] ?>">
                                                        <?php echo $row_traveler_grop_details['m_honorific'] ?></option>
                                                    <option value="Mr."> Mr. </option>
                                                    <option value="Mrs"> Mrs </option>
                                                    <option value="Miss"> Miss </option>
                                                    <option value="Smt"> Smt </option>
                                                    <option value="Infant"> Infant </option>
                                                </select>
                                            </td>
                                            <td style="width: 129px;"><input type="text" style="width: 129px;"
                                                    id="<?php echo 'txt_m_first_name' . $count_m . 'm' ?>"
                                                    name="<?php echo 'txt_m_first_name' . $count_m . 'm' ?>"
                                                    <?= $disabled; ?> onchange="fname_validate(this.id);"
                                                    placeholder="First Name" title="First Name"
                                                    value="<?php echo $row_traveler_grop_details['first_name'] ?>" />
                                            </td>
                                            <td><input type="text" style="width: 129px;"
                                                    id="<?php echo 'txt_m_middle_name' . $count_m . 'm' ?>"
                                                    name="<?php echo 'txt_m_middle_name' . $count_m . 'm' ?>"
                                                    <?= $disabled; ?> placeholder="Middle Name"
                                                    onchange="fname_validate(this.id);" title="Middle Name"
                                                    value="<?php echo $row_traveler_grop_details['middle_name'] ?>" />
                                            </td>
                                            <td style="width: 129px;"><input type="text" style="width: 129px;"
                                                    id="<?php echo 'txt_m_last_name' . $count_m . 'm' ?>"
                                                    name="<?php echo 'txt_m_last_name' . $count_m . 'm' ?>"
                                                    onchange="fname_validate(this.id);" <?= $disabled; ?>
                                                    placeholder="Last Name" title="Last Name"
                                                    value="<?php echo $row_traveler_grop_details['last_name'] ?>" />
                                            </td>
                                            <td><select id="<?php echo 'cmb_m_gender' . $count_m . 'm' ?>"
                                                    name="<?php echo 'cmb_m_gender' . $count_m . 'm' ?>"
                                                    <?= $disabled; ?>>
                                                    <option value="<?php echo $row_traveler_grop_details['gender'] ?>">
                                                        <?php echo $gender ?></option>
                                                    <option value="Male"> M </option>
                                                    <option value="Female"> F </option>
                                                </select>
                                            </td>
                                            <td><input type="text" maxlength="20" style="width: 129px;"
                                                    id="<?php echo 'm_birthdate' . $count_m . 'm' ?>"
                                                    name="<?php echo 'm_birthdate' . $count_m . 'm' ?>"
                                                    <?= $disabled; ?>
                                                    value="<?php if ($row_traveler_grop_details['birth_date'] != "") {
                                                                                                                                                                                                                                                echo date("d-m-Y", strtotime($row_traveler_grop_details['birth_date']));
                                                                                                                                                                                                                                            } ?>"
                                                    onchange="calculate_age_member(this.id);" placeholder="Birth Date"
                                                    title="Birth Date" /></td>
                                            <td style="width: 130px;"><input type="text"
                                                    id="<?php echo 'txt_m_age' . $count_m . 'm' ?>"
                                                    name="<?php echo 'txt_m_age' . $count_m . 'm' ?>" <?= $disabled; ?>
                                                    value="<?php echo $row_traveler_grop_details['age'] ?>"
                                                    placeholder="Age" title="Age(Y:M:D)"
                                                    onkeyup="adolescence_reflect(this.id)" /></td>
                                            <td><select id="<?php echo 'txt_m_adolescence' . $count_m . 'm' ?>"
                                                    name="<?php echo 'txt_m_adolescence' . $count_m . 'm' ?>" disabled>
                                                    <?php
                                                        if ($row_traveler_grop_details['adolescence'] == "Adult") {
                                                            $adlc = "A";
                                                        }
                                                        if ($row_traveler_grop_details['adolescence'] == "Children") {
                                                            $adlc = "C";
                                                        }
                                                        if ($row_traveler_grop_details['adolescence'] == "Infant") {
                                                            $adlc = "I";
                                                        }
                                                        ?>
                                                    <option
                                                        value="<?php echo $row_traveler_grop_details['adolescence'] ?>">
                                                        <?php echo $adlc; ?></option>
                                                    <option value="Adult">A</option>
                                                    <option value="Children">C</option>
                                                    <option value="Infant">I</option>
                                                </select>
                                            </td>
                                            <td style="width: 139px;"><input type="text" style="width: 129px;"
                                                    id="txt_m_passport_no<?= $count_m ?>m" name="txt_m_passport_no1"
                                                    <?= $disabled; ?> placeholder="Passport No"
                                                    onchange=" validate_specialChar(this.id)" title="Passport No"
                                                    value="<?= $row_traveler_grop_details['passport_no'] ?>"
                                                    style="text-transform: uppercase;" <?= $tourtype_display ?>></td>
                                            <td style="width: 130px;"><input type="text" style="width: 129px;"
                                                    id="txt_m_passport_issue_date<?= $count_m ?>m"
                                                    name="txt_m_passport_issue_date1" <?= $disabled; ?>
                                                    placeholder="Issue Date"
                                                    onchange="validate_validDate('txt_m_passport_issue_date<?= $count_m ?>m','txt_m_passport_expiry_date<?= $count_m ?>m')"
                                                    title="Passport Issue Date"
                                                    value="<?= get_date_user($row_traveler_grop_details['passport_issue_date']) ?>"
                                                    <?= $tourtype_display ?>></td>
                                            <td style="width: 132px;"><input type="text" style="width: 129px;"
                                                    id="txt_m_passport_expiry_date<?= $count_m ?>m"
                                                    name="txt_m_passport_expiry_date1" <?= $disabled; ?>
                                                    placeholder="Expiry Date"
                                                    onchange="validate_issueDate('txt_m_passport_issue_date<?= $count_m ?>m','txt_m_passport_expiry_date<?= $count_m ?>m')"
                                                    title="Expiry Date"
                                                    value="<?= get_date_user($row_traveler_grop_details['passport_expiry_date']) ?>"
                                                    <?= $tourtype_display ?>></td>
                                            <td style="display:none"><input type="text" <?= $disabled ?>
                                                    value="<?php echo $row_traveler_grop_details['traveler_id'] ?>">
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                                    <input type="hidden" id="txt_member_date_generate" value="<?php echo $count_m ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default main_block bg_light pad_8 text-center mg_bt_150" style="background-color: #fff; border: none;">
                    <button class="btn btn-sm btn-info ico_right">Next&nbsp;&nbsp;<i
                            class="fa fa-arrow-right"></i></button>
                </div>
            </div>

</form>

<?= end_panel() ?>

<script>
$(document).ready(function() {
    customer_info_load('customer_id1', '1');
    $("#country_name1,#txt_m_state1,#customer_id1").select2();
});
$(function() {
    $('#frm_tab_1').validate({
        rules: {},
        submitHandler: function(form) {
            var quotation_id = $('#quotation_id1').val();
            var tours_count = $('#sq_tours_count').val();

            var valid_state = package_tour_booking_tab1_validate();
            if (valid_state == false) {
                return false;
            }
            quotation_id = (typeof quotation_id === 'undefined') ? 0 : quotation_id;
            if (quotation_id == 0) {
                var table = document.getElementById("package_program_list");
                var rowCount = table.rows.length;
                for (var i = 0; i < rowCount; i++) {
                    var row = table.rows[i];
                    if (row.cells[0].childNodes[0].checked) {
                        if (row.cells[3].childNodes[0].value == "") {
                            error_msg_alert("Daywise Program is mandatory in row-" + (i + 1) +"<br>");
                            return false;
                        }                    }
                }
            }
            $('#tab_1_head').addClass('done');
            $('#tab_2_head').addClass('active');
            $('.bk_tab').removeClass('active');
            $('#tab_2').addClass('active');
            $('html, body').animate({
                scrollTop: $('.bk_tab_head').offset().top
            }, 200);
            return false;
        }
    });
});

function generating_member_date() {
    var count = $("#txt_member_date_generate").val();
    for (var i = 0; i <= count; i++) {
        var date = new Date();
        var yest = date.setDate(date.getDate() - 1);
        $("#m_birthdate" + i + 'm').datetimepicker({
            timepicker: false,
            maxDate: yest,
            format: "d-m-Y"
        });
        $("#txt_m_passport_issue_date" + i + 'm').datetimepicker({
            timepicker: false,
            format: "d-m-Y"
        });
        $("#txt_m_passport_expiry_date" + i + 'm').datetimepicker({
            timepicker: false,
            format: "d-m-Y"
        });
    }
}
generating_member_date();
</script>
<script src="../js/tab_1.js"></script>