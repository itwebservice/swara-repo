<?php
include '../../../model/model.php';
$section_name = $_POST['section_name'];
$query = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM `b2b_settings`"));
$query1 = mysqli_fetch_assoc(mysqlQuery("SELECT * FROM `b2b_settings_second`"));
?>
<input type="hidden" value="<?= $section_name ?>" id="section_name" name="section_name" />
<?php
if ($section_name == '1') { ?>
    <legend>Banner Images</legend>
    <div class="row mg_bt_20">
        <div class="col-md-3">
            <select class="form-control" style="width:100%" name="banner_count" id="banner_count" onchange="banner_images_reflect(this.id);" title="Select No of Images" data-toggle="tooltip">
                <option value=""><?= 'No of Images' ?></option>
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div id='banner_images'></div>
<?php } elseif ($section_name == '2') {
    $why_choose_us = isset($query['why_choose_us']) ? json_decode($query['why_choose_us']) : [];
?>
    <legend>Why Choose Us ?</legend>
    <div class="row mg_bt_20">
        <div class="col-md-3">
            <label>Select Display Status</label>
            <select class="form-control" style="width:100%" name="display_status" id="display_status" title="Display Status" data-toggle="tooltip">
                <?php if ($query['why_choose_flag'] != '') { ?>
                    <option value="<?= $query['why_choose_flag'] ?>"><?= $query['why_choose_flag'] ?></option>
                <?php } ?>
                <?php if ($query['why_choose_flag'] != 'Hide') { ?>
                    <option value="Hide">Hide</option>
                <?php } ?>
                <?php if ($query['why_choose_flag'] != 'Show') { ?>
                    <option value="Show">Show</option>
                <?php } ?>
            </select>
        </div>
    </div>
<?php }
if ($section_name == '3') {
    $amazing_dest_ideas = isset($query['amazing_dest_ideas']) ? json_decode($query['amazing_dest_ideas']) : []; ?>
    <legend>Amazing Destination Ideas for you!</legend>
    <div class="row mg_bt_20">
        <div class="col-md-3">
            <label>Select Display Status</label>
            <select class="form-control" style="width:100%" name="display_status" id="display_status" title="Display Status" data-toggle="tooltip">
                <?php if ($query['amazing_destideas_flag'] != '') { ?>
                    <option value="<?= $query['amazing_destideas_flag'] ?>"><?= $query['amazing_destideas_flag'] ?></option>
                <?php } ?>
                <?php if ($query['amazing_destideas_flag'] != 'Hide') { ?>
                    <option value="Hide">Hide</option>
                <?php } ?>
                <?php if ($query['amazing_destideas_flag'] != 'Show') { ?>
                    <option value="Show">Show</option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="row mg_bt_20">
        <div class="col-md-3">
            <select class="form-control" style="width:100%" name="ideas_count" id="ideas_count" onchange="ideas_cms_reflect(this.id);" title="Select No of Ideas" data-toggle="tooltip">
                <option value=""><?= 'No of Ideas' ?></option>
                <?php for ($i = 1; $i <= 6; $i++) { ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-6 col-sm-4">
            <input type="text" id="heading" placeholder="Enter Heading" title="Enter Heading" data-toggle="tooltip" class="form-control" value="<?php echo $amazing_dest_ideas[0]->heading; ?>" onchange="validate_char_size(this.id,50);" />
        </div>
    </div>
    <div id="image_modal"></div>
    <div id='ideas_data'></div>
<?php } elseif ($section_name == '4') {
    $popular_dest = isset($query['popular_dest']) ? json_decode($query['popular_dest']) : [];
?>
    <legend>Popular Destinations</legend>
    <div class="row mg_bt_20">
        <div class="col-md-4">
            <label>Select Display Status</label>
            <select class="form-control" style="width:100%" name="display_status" id="display_status" title="Display Status" data-toggle="tooltip">
                <?php if ($query['popular_dest_flag'] != '') { ?>
                    <option value="<?= $query['popular_dest_flag'] ?>"><?= $query['popular_dest_flag'] ?></option>
                <?php } ?>
                <?php if ($query['popular_dest_flag'] != 'Hide') { ?>
                    <option value="Hide">Hide</option>
                <?php } ?>
                <?php if ($query['popular_dest_flag'] != 'Show') { ?>
                    <option value="Show">Show</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-8 text-right mg_tp_20">
            <button type="button" class="btn btn-excel btn-sm" title="Note : For saving package keep checkbox selected!"><i class="fa fa-question-circle"></i></button>
            <button type="button" class="btn btn-excel btn-sm" onclick="addRow('tbl_dest_packages_footer')" title="Add Row"><i class="fa fa-plus"></i></button>
            <button type="button" class="btn btn-pdf btn-sm" onclick="deleteRow('tbl_dest_packages_footer');" title="Delete Row"><i class="fa fa-trash"></i></button>
        </div>
    </div>

    <div class="row mg_bt_20">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="tbl_dest_packages_footer" name="tbl_dest_packages_footer" class="table table-bordered" style="width:1300px;">
                    <?php
                    if (sizeof($popular_dest) == 0) { ?>
                        <tr>
                            <td><input id="chk_dest1" type="checkbox" checked></td>
                            <td><input maxlength="15" value="1" type="text" name="no" placeholder="Sr. No." class="form-control" disabled /></td>
                            <td><select name="dest_name-1" id="dest_name-1" onchange="package_dynamic_reflect(this.id)" style="width:350px" class="form-control app_select2" title="Select Destination">
                                    <option value="">*Select Destination</option>
                                    <?php
                                    $sq_query = mysqlQuery("select * from destination_master where status != 'Inactive'");
                                    while ($row_dest = mysqli_fetch_assoc($sq_query)) { ?>
                                        <option value="<?php echo $row_dest['dest_id']; ?>"><?php echo $row_dest['dest_name']; ?></option>
                                    <?php } ?>
                                </select></td>
                            <td><select id="package-1" name="package-1" title="Select Package" class="form-control" style="width:350px">
                                    <option value="">*Select Package</option>
                                </select></td>
                            <script>
                                $('#dest_name-1').select2();
                            </script>
                        </tr>
                        <?php
                    } else {
                        for ($i = 0; $i < sizeof($popular_dest); $i++) {
                            $dest_id = $popular_dest[$i]->dest_id;
                            $sq_dest = mysqli_fetch_assoc(mysqlQuery("select dest_id,dest_name from destination_master where dest_id='$dest_id'"));
                            $package_id = $popular_dest[$i]->package_id;
                            $sq_package = mysqli_fetch_assoc(mysqlQuery("select package_id,package_name from custom_package_master where package_id='$package_id'"));
                        ?>
                            <tr>
                                <td><input id="chk_dest1<?= $i ?>_u" type="checkbox" checked></td>
                                <td><input maxlength="15" value="<?= ($i + 1) ?>" type="text" name="no" placeholder="Sr. No." class="form-control" disabled /></td>
                                <td><select name="dest_name-1<?= $i ?>_u" id="dest_name-1<?= $i ?>_u" onchange="package_dynamic_reflect(this.id)" class="form-control app_select2" title="Select Destination" style="width:350px">
                                        <?php if ($dest_id != '0') { ?>
                                            <option value="<?= $sq_dest['dest_id'] ?>"><?= $sq_dest['dest_name'] ?></option>
                                        <?php } ?>
                                        <option value="">*Select Destination</option>
                                        <?php
                                        $sq_query = mysqlQuery("select * from destination_master where status != 'Inactive'");
                                        while ($row_dest = mysqli_fetch_assoc($sq_query)) { ?>
                                            <option value="<?php echo $row_dest['dest_id']; ?>"><?php echo $row_dest['dest_name']; ?></option>
                                        <?php } ?>
                                    </select></td>
                                <td><select id="package-1<?= $i ?>_u" name="package-1<?= $i ?>_u" title="Select Package" class="form-control" style="width:350px">
                                        <?php if ($package_id != '0') { ?>
                                            <option value="<?= $sq_package['package_id'] ?>"><?= $sq_package['package_name'] ?></option>
                                        <?php } ?>
                                        <option value="">*Select Package</option>
                                    </select></td>
                            </tr>
                            <script>
                                $('#dest_name-1<?= $i ?>_u').select2();
                            </script>
                    <?php }
                    } ?>
                </table>
            </div>
        </div>
    </div>
<?php } elseif ($section_name == '5') {

    $popular_honey_hotels = isset($query['popular_honey_hotels']) ? json_decode($query['popular_honey_hotels']) : [];
    $hotels = isset($popular_honey_hotels[0]->hotel) ? $popular_honey_hotels[0]->hotel : [];
?>
    <legend>Popular Hotels for Honeymoon</legend>
    <div class="row mg_bt_20">
        <div class="col-md-3">
            <label>Select Display Status</label>
            <select class="form-control" style="width:100%" name="display_status" id="display_status" title="Display Status" data-toggle="tooltip">
                <?php if ($query['popular_honey_hotels_flag'] != '') { ?>
                    <option value="<?= $query['popular_honey_hotels_flag'] ?>"><?= $query['popular_honey_hotels_flag'] ?></option>
                <?php } ?>
                <?php if ($query['popular_honey_hotels_flag'] != 'Hide') { ?>
                    <option value="Hide">Hide</option>
                <?php } ?>
                <?php if ($query['popular_honey_hotels_flag'] != 'Show') { ?>
                    <option value="Show">Show</option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="row mg_bt_20">
        <div class="col-md-3 col-sm-4">
            <input type="text" id="heading" placeholder="Enter Heading" title="Enter Heading" data-toggle="tooltip" class="form-control" value="<?php echo $popular_honey_hotels[0]->heading; ?>" onchange="validate_char_size(this.id,30);" />
        </div>
        <div class="col-md-6 col-sm-4">
            <textarea id="title" placeholder="Enter Title" title="Enter Title" data-toggle="tooltip" class="form-control" rows="1" onchange="validate_char_size(this.id,120);"><?php echo $popular_honey_hotels[0]->title; ?></textarea>
        </div>
    </div>
    <h5 class="no-pad">Select Hotels(Max 4 Hotels you can add!)</h5>
    <?php
    if (sizeof($hotels) > 0) {
        for ($i = 0; $i < 4; $i++) {
            $city_id = $hotels[$i]->city_id;
            $sq_city = mysqli_fetch_assoc(mysqlQuery("select city_id,city_name from city_master where city_id='$city_id'"));
            $hotel_id = $hotels[$i]->hotel_id;
            $sq_hotel = mysqli_fetch_assoc(mysqlQuery("select hotel_id,hotel_name from hotel_master where hotel_id='$hotel_id'"));
            ?>
            <div class="row mg_bt_20">
                <div class="col-md-3">
                    <select id='city_name-<?= $i + 1 ?>' title="Select City" class="form-control city_id_cms" onchange="hotel_names_load(this.id);">
                        <?php if ($city_id != '') { ?>
                            <option value="<?= $sq_city['city_id'] ?>"><?= $sq_city['city_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id='hotel_name-<?= $i + 1 ?>' class="form-control" title="Select Hotel">
                        <?php if ($hotel_id != '') { ?>
                            <option value="<?= $sq_hotel['hotel_id'] ?>"><?= $sq_hotel['hotel_name'] ?></option>
                        <?php } ?>
                        <option value="">Hotel Name</option>
                    </select>
                </div>
            </div>
            <script>
                $('#hotel_name-<?= ($i + 1) ?>').select2();
            </script>
        <?php }
    } else {
        for ($i = 0; $i < 4; $i++) { ?>
            <div class="row mg_bt_20">
                <div class="col-md-3">
                    <select id='city_name-<?= $i + 1 ?>' title="Select City" class="form-control city_id_cms" onchange="hotel_names_load(this.id);">
                        <?php if ($city_id != '') { ?>
                            <option value="<?= $sq_city['city_id'] ?>"><?= $sq_city['city_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id='hotel_name-<?= ($i + 1) ?>' class="form-control" title="Select Hotel">
                        <?php if ($hotel_id != '') { ?>
                            <option value="<?= $sq_hotel['hotel_id'] ?>"><?= $sq_hotel['hotel_name'] ?></option>
                        <?php } ?>
                        <option value="">Hotel Name</option>
                    </select>
                </div>
            </div>
            <script>
                $('#hotel_name-<?= ($i + 1) ?>').select2();
            </script>
    <?php }
    }
    ?>
    <script>
    $(document).ready(function() {
        city_lzloading('.city_id_cms');
    });
    </script>
    <?php
} elseif ($section_name == '6') {

    $popular_activities = isset($query['popular_activities']) ? json_decode($query['popular_activities']) : [];
    ?>
    <legend>Popular Activities</legend>
    <div class="row mg_bt_20">
        <div class="col-md-4">
            <label>Select Display Status</label>
            <select class="form-control" style="width:100%" name="display_status" id="display_status" title="Display Status" data-toggle="tooltip">
                <?php if ($query['popular_activities_flag'] != '') { ?>
                    <option value="<?= $query['popular_activities_flag'] ?>"><?= $query['popular_activities_flag'] ?></option>
                <?php } ?>
                <?php if ($query['popular_activities_flag'] != 'Hide') { ?>
                    <option value="Hide">Hide</option>
                <?php } ?>
                <?php if ($query['popular_activities_flag'] != 'Show') { ?>
                    <option value="Show">Show</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-8 text-right mg_tp_20">
            <button type="button" class="btn btn-excel btn-sm" title="Note : For saving activities keep checkbox selected!"><i class="fa fa-question-circle"></i></button>
            <button type="button" class="btn btn-excel btn-sm" onclick="addRow('tbl_activities','2');city_lzloading('.pop_city')" title="Add Row"><i class="fa fa-plus"></i></button>
            <button type="button" class="btn btn-pdf btn-sm" onclick="deleteRow('tbl_activities','2');" title="Delete Row"><i class="fa fa-trash"></i></button>
        </div>
    </div>

    <div class="row mg_bt_20">
        <div class="col-md-8">
            <table id="tbl_activities" name="tbl_activities" class="table border_0 table-hover no-marg">
                <?php
                if (sizeof($popular_activities) == 0) {
                ?>
                    <tr>
                        <td><input id="chk_city1" type="checkbox" checked></td>
                        <td><input maxlength="15" value="1" type="text" name="no" placeholder="Sr. No." class="form-control" disabled /></td>
                        <td><select name="city_name-1" id="city_name-1" title="Select City" onchange="excursion_dynamic_reflect(this.id);" style="width:250px" class="form-control app_select2 pop_city">
                                <?php if ($city_id != '') { ?>
                                    <option value="<?= $sq_city['city_id'] ?>"><?= $sq_city['city_name'] ?></option>
                                <?php } ?>
                            </select>
                            </select></td>
                        <td><select id="exc-1" name="exc-1" title="Select Activity" class="form-control" style="width:250px">
                                <?php if ($popular_activities[$i]->exc_id != '') { ?>
                                    <option value="<?= $popular_activities[$i]->exc_id ?>"><?= $popular_activities[$i]->exc_id ?></option>
                                <?php } ?>
                                <option value="">*Select Activity</option>
                            </select></td>
                    </tr>
                    <script>
                        $(document).ready(function() {
                            city_lzloading('.pop_city');
                        });
                    </script>
                    <?php } else {
                    for ($i = 0; $i < sizeof($popular_activities); $i++) {
                        $city_id = $popular_activities[$i]->city_id;
                        $sq_city = mysqli_fetch_assoc(mysqlQuery("select city_id,city_name from city_master where city_id='$city_id'"));
                    ?>
                        <tr>
                            <td><input id="chk_city1<?= $i ?>_u" type="checkbox" checked></td>
                            <td><input maxlength="15" value="<?= ($i + 1) ?>" type="text" name="no" placeholder="Sr. No." class="form-control" disabled /></td>
                            <td><select name="city_name-1<?= $i ?>_u" id="city_name-1<?= $i ?>_u" title="Select City" onchange="excursion_dynamic_reflect(this.id)" style="width:250px" class="form-control app_select2 pop_city">
                                    <?php if ($city_id != '') { ?>
                                        <option value="<?= $sq_city['city_id'] ?>"><?= $sq_city['city_name'] ?></option>
                                    <?php } ?>
                                </select>
                                </select></td>
                            <td><select id="exc-1<?= $i ?>_u" name="exc-1<?= $i ?>_u" title="Select Activity" class="form-control" style="width:250px">
                                    <?php if ($popular_activities[$i]->exc_id != '') { ?>
                                        <option value="<?= $popular_activities[$i]->exc_id ?>"><?= $popular_activities[$i]->exc_id ?></option>
                                    <?php } ?>
                                    <option value="">*Select Activity</option>
                                </select></td>
                        </tr>
                        <script>
                            $(document).ready(function() {
                                city_lzloading('#city_name-1<?= ($i) ?>_u');
                            });
                        </script>
                <?php }
                } ?>
            </table>
        </div>
    </div>
<?php } elseif ($section_name == '7') {

    $call_to_action = isset($query['call_to_action']) ? json_decode($query['call_to_action']) : [];
?>
    <legend>Call To Action</legend>
    <div class="row mg_bt_20">
        <div class="col-md-3">
            <label>Select Display Status</label>
            <select class="form-control" style="width:100%" name="display_status" id="display_status" title="Display Status" data-toggle="tooltip">
                <?php if ($query['call_to_action_flag'] != '') { ?>
                    <option value="<?= $query['call_to_action_flag'] ?>"><?= $query['call_to_action_flag'] ?></option>
                <?php } ?>
                <?php if ($query['call_to_action_flag'] != 'Hide') { ?>
                    <option value="Hide">Hide</option>
                <?php } ?>
                <?php if ($query['call_to_action_flag'] != 'Show') { ?>
                    <option value="Show">Show</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-6 col-sm-6 mg_tp_20">
            <div class="div-upload">
                <div id="id_upload_btn" class="upload-button1"><span>Upload</span></div>
                <span id="id_proof_status"></span>
                <ul id="files"></ul>
                <input type="hidden" id="image_upload_url" value="<?php echo $call_to_action[0]->image_url; ?>" name="image_upload_url">
            </div>&nbsp;
            <button type="button" data-toggle="tooltip" class="btn btn-excel" title="Upload Image size below 100KB, Format : JPEG,PNG."><i class="fa fa-question-circle"></i></button>
        </div>
    </div>
    <?php if(isset($call_to_action[0]->image_url)) {
        $url = $call_to_action[0]->image_url;
        $pos = strstr($url, 'uploads');
        if ($pos != false) {
            $newUrl1 = preg_replace('/(\/+)/', '/', $call_to_action[0]->image_url);
            $newUrl = BASE_URL . str_replace('../', '', $newUrl1);
        } else {
            $newUrl =  $call_to_action[0]->image_url;
        }
    ?>
        <div class="c-radioIcon type-image">
            <input class="css-checkbox" name="image_check" id="image_select<?php echo $count; ?>" type="radio" value="<?php echo $newUrl; ?>" checked>
            <label for="image_select<?php echo $count; ?>" class="iconWrap" title="Image" data-toggle="tooltip">
                <img src="<?php echo $newUrl; ?>" id="image<?php echo $count; ?>" alt="title">
            </label>
        </div>
    <?php } ?>
    <div class="row mg_bt_20">
        <div class="col-md-6 col-sm-4">
            <textarea type="text" id="title" placeholder="Enter title" title="Enter title" data-toggle="tooltip" class="form-control" onchange="validate_char_size(this.id,105);"><?php echo $call_to_action[0]->title; ?></textarea>
        </div>
        <div class="col-md-6 col-sm-4">
            <input type="text" id="subtitle" placeholder="Enter Subtitle" title="Enter Subtitle" data-toggle="tooltip" class="form-control" rows="1" onchange="validate_char_size(this.id,75);" value="<?php echo $call_to_action[0]->subtitle; ?>" />
        </div>
    </div>
<?php } elseif ($section_name == '8') {
    $popular_hotels = isset($query['popular_hotels']) ? json_decode($query['popular_hotels']) : [];
?>
    <legend>Popular Hotels</legend>
    <div class="row mg_bt_20">
        <div class="col-md-4">
            <label>Select Display Status</label>
            <select class="form-control" style="width:100%" name="display_status" id="display_status" title="Display Status" data-toggle="tooltip">
                <?php if ($query['popular_hotels_flag'] != '') { ?>
                    <option value="<?= $query['popular_hotels_flag'] ?>"><?= $query['popular_hotels_flag'] ?></option>
                <?php } ?>
                <?php if ($query['popular_hotels_flag'] != 'Hide') { ?>
                    <option value="Hide">Hide</option>
                <?php } ?>
                <?php if ($query['popular_hotels_flag'] != 'Show') { ?>
                    <option value="Show">Show</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-8 text-right mg_tp_20">
            <button type="button" class="btn btn-excel btn-sm" title="Note : For saving hotels keep checkbox selected!"><i class="fa fa-question-circle"></i></button>
            <button type="button" class="btn btn-excel btn-sm" onclick="addRow('tbl_hotels');city_lzloading('.hotel_city')" title="Add Row"><i class="fa fa-plus"></i></button>
            <button type="button" class="btn btn-pdf btn-sm" onclick="deleteRow('tbl_hotels');" title="Delete Row"><i class="fa fa-trash"></i></button>
        </div>
    </div>

    <div class="row mg_bt_20">
        <div class="col-md-8">
            <table id="tbl_hotels" name="tbl_hotels" class="table border_0 table-hover no-marg">
                <?php
                if (sizeof($popular_hotels) == 0) { ?>
                    <tr>
                        <td><input id="chk_city1" type="checkbox" checked></td>
                        <td><input maxlength="15" value="1" type="text" name="no" placeholder="Sr. No." class="form-control" disabled /></td>
                        <td><select name="city_name-1" id="city_name-1" title="Select City" onchange="hotel_names_load(this.id)" style="width:250px" class="form-control app_select2 hotel_city">
                                <?php if ($city_id != '') { ?>
                                    <option value="<?= $sq_city['city_id'] ?>"><?= $sq_city['city_name'] ?></option>
                                <?php } ?>
                            </select>
                            </select></td>
                        <td><select id='hotel_name-1' name='hotel_name-1' class="form-control" title="Select Hotel" style="width:250px">
                                <?php if ($hotel_id != '') { ?>
                                    <option value="<?= $sq_hotel['hotel_id'] ?>"><?= $sq_hotel['hotel_name'] ?></option>
                                <?php } ?>
                                <option value="">*Hotel Name</option>
                            </select></td>
                    </tr>
                    <script>
                        $(document).ready(function() {
                            city_lzloading('#city_name-1');
                        });
                    </script>
                    <?php
                } else {
                    for ($i = 0; $i < sizeof($popular_hotels); $i++) {
                        $city_id = $popular_hotels[$i]->city_id;
                        $sq_city = mysqli_fetch_assoc(mysqlQuery("select city_id,city_name from city_master where city_id='$city_id'"));
                        $hotel_id = $popular_hotels[$i]->hotel_id;
                        $sq_hotel = mysqli_fetch_assoc(mysqlQuery("select hotel_id,hotel_name from hotel_master where hotel_id='$hotel_id'"));
                    ?>
                        <tr>
                            <td><input id="chk_city1<?= $i ?>_u" type="checkbox" checked></td>
                            <td><input maxlength="15" value="<?= ($i + 1) ?>" type="text" name="no" placeholder="Sr. No." class="form-control" disabled /></td>
                            <td><select name="city_name-1<?= $i ?>_u" id="city_name-1<?= $i ?>_u" title="Select City" onchange="hotel_names_load(this.id)" style="width:250px" class="form-control app_select2 hotel_city">
                                    <?php if ($city_id != '') { ?>
                                        <option value="<?= $sq_city['city_id'] ?>"><?= $sq_city['city_name'] ?></option>
                                    <?php } ?>
                                </select>
                                </select></td>
                            <td><select id='hotel_name-1<?= $i ?>_u' name='hotel_name-1<?= $i ?>_u' class="form-control" title="Select Hotel" style="width:250px">
                                    <?php if ($hotel_id != '') { ?>
                                        <option value="<?= $sq_hotel['hotel_id'] ?>"><?= $sq_hotel['hotel_name'] ?></option>
                                    <?php } ?>
                                    <option value="">*Hotel Name</option>
                                </select></td>
                        </tr>
                        <script>
                            $(document).ready(function() {
                                city_lzloading('#city_name-1<?= $i ?>_u');
                            });
                        </script>
                <?php }
                } ?>
            </table>
        </div>
    </div>
<?php } elseif ($section_name == '9') {
    $popular_honey_dest = isset($query['popular_honey_dest']) ? json_decode($query['popular_honey_dest']) : [];
    $destination = isset($popular_honey_dest[0]->destination) ? $popular_honey_dest[0]->destination : [];
?>
    <legend>Popular Destinations for Honeymoon</legend>
    <div class="row mg_bt_20">
        <div class="col-md-3">
            <label>Select Display Status</label>
            <select class="form-control" style="width:100%" name="display_status" id="display_status" title="Display Status" data-toggle="tooltip">
                <?php if ($query['popular_honey_dest_flag'] != '') { ?>
                    <option value="<?= $query['popular_honey_dest_flag'] ?>"><?= $query['popular_honey_dest_flag'] ?></option>
                <?php } ?>
                <?php if ($query['popular_honey_dest_flag'] != 'Hide') { ?>
                    <option value="Hide">Hide</option>
                <?php } ?>
                <?php if ($query['popular_honey_dest_flag'] != 'Show') { ?>
                    <option value="Show">Show</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-9 col-sm-4">
            <label>Description</label>
            <textarea id="description" placeholder="*Enter Description" title="Enter Description" data-toggle="tooltip" class="form-control" onchange="validate_char_size(this.id,150);" rows="1"><?php echo $popular_honey_dest[0]->description; ?></textarea>
        </div>
    </div>
    <h5 class="no-pad">Select Destination(Max 3 Destination you can add!)</h5>
    <?php
    if (sizeof($destination) == 0) {
        for ($i = 0; $i < 3; $i++) {
    ?>
            <div class="row mg_bt_20">
                <div class="col-md-3">
                    <select name="dest_name<?= ($i + 1) ?>" id="dest_name<?= ($i + 1) ?>" title="Select Destination" style="width:100%" class="form-control app_select2" required>
                        <option value="">*Select Destination</option>
                        <?php
                        $sq_query = mysqlQuery("select * from destination_master where status != 'Inactive'");
                        while ($row_dest = mysqli_fetch_assoc($sq_query)) { ?>
                            <option value="<?php echo $row_dest['dest_id']; ?>"><?php echo $row_dest['dest_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input class="btn btn-sm btn-danger" style="padding-left: 10px !important;" type="button" value="Select Image" id="select_image-<?= ($i + 1) ?>" onclick="get_dest_images('dest_name<?= ($i + 1) ?>','imagel<?= ($i + 1) ?>');" />
                    <input type="hidden" value="" id="imagel<?= ($i + 1) ?>" />
                </div>
            </div>
            <script>
                $('#dest_name<?= ($i + 1) ?>').select2();
            </script>
        <?php }
    } else {
        for ($i = 0; $i < 3; $i++) {
            $dest_id = $destination[$i]->dest_id;
            $sq_dest = mysqli_fetch_assoc(mysqlQuery("select dest_id,dest_name from destination_master where dest_id='$dest_id'"));

            $url = $destination[$i]->image_url;
            $pos = strstr($url, 'uploads');
            if ($pos != false) {
                $newUrl1 = preg_replace('/(\/+)/', '/', $destination[$i]->image_url);
                $newUrl = BASE_URL . str_replace('../', '', $newUrl1);
            } else {
                $newUrl =  $destination[$i]->image_url;
            }
            $button_text = isset($newUrl) ? 'Image Uploaded' : 'Select Image';
            $button_class = isset($newUrl) ? 'btn-warning' : 'btn-danger';
        ?>
            <div class="row mg_bt_20">
                <div class="col-md-3">
                    <select name="dest_name<?= ($i + 1) ?>" id="dest_name<?= ($i + 1) ?>" title="Select Destination" style="width:100%" class="form-control app_select2">
                        <?php if ($sq_dest['dest_name'] != '') { ?>
                            <option value="<?= $sq_dest['dest_id'] ?>"><?= $sq_dest['dest_name'] ?></option>
                        <?php } ?>
                        <option value="">*Select Destination</option>
                        <?php
                        $sq_query = mysqlQuery("select * from destination_master where status != 'Inactive'");
                        while ($row_dest = mysqli_fetch_assoc($sq_query)) { ?>
                            <option value="<?php echo $row_dest['dest_id']; ?>"><?php echo $row_dest['dest_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input class="btn btn-sm <?= $button_class ?>" style="padding-left: 10px !important;" type="button" value="<?= $button_text ?>" id="select_image-<?= ($i + 1) ?>" onclick="get_dest_images('dest_name<?= ($i + 1) ?>','imagel<?= ($i + 1) ?>');" />
                    <input type="hidden" value="<?= $newUrl ?>" id="imagel<?= ($i + 1) ?>" />
                </div>
            </div>
            <script>
                $('#dest_name<?= ($i + 1) ?>').select2();
            </script>
    <?php }
    } ?>
    <div id="image_modal"></div>
<?php } elseif ($section_name == '10') {
    $col1 = isset($query1['col1']) ? json_decode($query1['col1']) : [];
    $col2 = isset($query1['col2']) ? json_decode($query1['col2']) : [];
    $col3 = isset($query1['col3']) ? json_decode($query1['col3']) : [];
?>
    <legend>Footer</legend>
    <div class="row modal-body profile_box_padding">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs footer_tab" role="tablist">
            <li role="presentation" class="active"><a href="#hotels" aria-controls="home" role="tab" data-toggle="tab" class="tab_name">Best Selling Hotels</a></li>
            <li role="presentation"><a href="#activities" aria-controls="hotels" role="tab" data-toggle="tab" class="tab_name">Best Selling Activities</a></li>
            <li role="presentation"><a href="#tours" aria-controls="activities" role="tab" data-toggle="tab" class="tab_name">Best Selling Tours</a></li>
            <li role="presentation"><a href="#terms" aria-controls="profile" role="tab" data-toggle="tab" class="tab_name"> Terms & Policies</a></li>
        </ul>
        <div class="panel panel-default panel-body fieldset profile_background">
            <!-- Tab panes1 -->
            <div class="tab-content">
                <!-- *****TAb1 start -->
                <div role="tabpanel" class="tab-pane active" id="hotels">
                    <?php include "inc/footer/tab1.php"; ?>
                </div>
                <!-- ********Tab1 End******** -->
                <!-- ***Tab2 Start*** -->
                <div role="tabpanel" class="tab-pane" id="activities">
                    <?php include "inc/footer/tab2.php"; ?>
                </div>
                <!-- ***Tab2 End*** -->
                <!-- ***Tab3 Start*** -->
                <div role="tabpanel" class="tab-pane" id="tours">
                    <?php include "inc/footer/tab3.php"; ?>
                </div>
                <!-- ***Tab3 End*** -->
                <!-- ***Tab4 Start*** -->
                <div role="tabpanel" class="tab-pane" id="terms">
                    <?php include "inc/footer/tab4.php"; ?>
                </div>
                <!-- ***Tab4 End*** -->
            </div>
        </div>
    </div>
<?php } ?>
<div id="images_list"></div>
<div class="row mg_tp_20">
    <div class="col-xs-12 text-center">
        <button class="btn btn-sm btn-success" id="btn_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
    </div>
</div>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script src="<?= BASE_URL ?>js/ajaxupload.3.5.js"></script>
<script src="cms/index.js"></script>

<script type="text/javascript">
    $(function() {
        $("[data-toggle='tooltip']").tooltip({
            placement: 'bottom'
        });
    });
    if (<?php echo $section_name; ?> == '1') {
        load_images();
    }
    if (<?php echo $section_name; ?> == '2') {
        load_why_choose_section();
    }
    if (<?php echo $section_name; ?> == '3') {
        ideas_data_reflect();
    }
    if (<?php echo $section_name; ?> == '5') {
        $('.city_id_cms').select2({
            minimumInputLength: 1
        });
    }
    if (<?= $section_name ?> == '7') {
        upload_call_image();

        function upload_call_image() {
            var btnUpload = $('#id_upload_btn');
            $(btnUpload).find('span').text('Image');

            new AjaxUpload(btnUpload, {
                action: 'cms/inc/call_to_action/upload_call_img.php',
                name: 'uploadfile',
                onSubmit: function(file, ext) {
                    if (!(ext && /^(png|jpeg)$/.test(ext))) {
                        error_msg_alert('Only JPEG,PNG files are allowed');
                        return false;
                    }
                    $(btnUpload).find('span').text('Uploading...');
                },
                onComplete: function(file, response) {
                    var response1 = response.split('--')
                    if (response1[0] == "error") {
                        error_msg_alert(response1[1]);
                        $(btnUpload).find('span').text('Image');
                    } else {
                        $(btnUpload).find('span').text('Uploaded');
                        $("#image_upload_url").val(response);
                    }
                }
            });
        }
    }
</script>