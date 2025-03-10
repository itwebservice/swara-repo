<div class="row mg_bt_20" style="margin-top: 5px"> 
    <div class="col-xs-6 mg_bt_20_sm_xs">
        <button type="button" class="btn btn-excel btn-sm" title="Add Activity" onclick="activity_save_modal()"><i class="fa fa-plus"></i></button>
    </div>
    <div class="col-xs-6 text-right mg_bt_20_sm_xs">
    <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_exc_infomration');city_lzloading('select[name=city_name-1]')"><i class="fa fa-plus"></i></button>
    <button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_package_exc_infomration')"><i class="fa fa-trash"></i></button>
</div> </div>
<div class="row main_block">
    <div class="col-xs-12"> 
        <div class="table-responsive">
            <table id="tbl_package_exc_infomration" class="table table-bordered table-hover pd_bt_51 table-striped no-marg" style="width: 1111px;">
                <tr>
                    <td><input id="check-btn-exc" type="checkbox" ></td>
                    <td><input maxlength="15" type="text" name="username"  value="1" placeholder="Sr. No." disabled/></td>
                    <td><input type="text" id="exc_date-1" name="exc_date-1" placeholder="Activity Date & Time" title="Activity Date & Time" class="app_datetimepicker" value="<?= date('d-m-Y H:i') ?>" style="width:200px"></td>
                    <td><select id="city_name-1" class="form-control" name="city_name-1" title="City Name" style="width:200px" onchange="get_excursion_list(this.id);">
                        </select>
                    </td>
                    <td><select id="excursion-1" class="form-control" title="Activity Name" name="excursion-1" style="width:200px">
                        <option value="">*Activity Name</option>
                    <td><select name="transfer_option-1" id="transfer_option-1" data-toggle="tooltip" class="form-contrl app_select2" title="Transfer Option" style="width:200px">
                        <option value="Private Transfer">Private Transfer</option>
                        <option value="Without Transfer">Without Transfer</option>
                        <option value="Sharing Transfer">Sharing Transfer</option>
                        <option value="SIC">SIC</option>
                        </select></td>
                    </select></td>
                    <td><input type="number" id="adult-1" name="adult-1" placeholder="Adult(s)" title="Adult(s)" style="width:100px" onchange="validate_balance(this.id);"></td>
                    <td><input type="number" id="child-1" name="child-1" placeholder="Child With-Bed" title="Child With-Bed" style="width:150px" onchange="validate_balance(this.id);" ></td>
                    <td><input type="number" id="childwo-1" name="childwo-1" placeholder="Child Without-Bed" title="Child Without-Bed" style="width:150px" onchange="validate_balance(this.id);" ></td>
                    <td><input type="number" id="infant-1" name="infant-1" placeholder="Infant(s)" title="Infant(s)" style="width:100px" onchange="validate_balance(this.id);" ></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script>
    city_lzloading('select[name="city_name-1"]');
</script>