<?php
include "../../../../../model/model.php";
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id = $_SESSION['emp_id'];
$role = $_SESSION['role'];
$q = "select * from branch_assign where link='booking/index.php'";
$sq_count = mysqli_num_rows(mysqlQuery($q));
$sq = mysqli_fetch_assoc(mysqlQuery($q));
$branch_status = ($sq_count >0 && $sq['branch_status'] !== NULL && isset($sq['branch_status'])) ? $sq['branch_status'] : 'no';
?>
<input type="hidden" id="branch_status" name="branch_status" value="<?= $branch_status ?>" >
<div class="app_panel_content Filter-panel mg_bt_10">
    <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
      <select id="tour_id_filter" name="tour_id_filter" onchange="tour_group_dynamic_reflect1();" style="width:100%" title="Tour Name"> 
        <?php get_customer_dropdown($role,$branch_admin_id,$branch_status);?>
      </select>
    </div>      
</div>
<div id="div_list" class="main_block mg_tp_20">
<div class="row"> <div class="col-md-12 no-pad"> <div class="table-responsive">
<table id="package_tour_report" class="table table-hover" style="margin: 20px 0 !important;">         
</table>
</div></div></div>
</div>
<div id="travelr_details_popup"></div>
<script>
  $('#from_date, #to_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
  $('#tour_id_filter').select2();

</script>
<script type="text/javascript">
  var column = [
	{ title: "S_No." },
	{ title: "Customer_name" },
	{ title: "birth_date" },
	{ title: "gender" },
	{ title: "repeated_count_group" ,className:"text-center"},
	{ title: "Actions"}
];
function tour_group_dynamic_reflect1()
{
  var traveler_id = $('#tour_id_filter').val();
    var branch_status = $('#branch_status').val();
    $.post('reports_content/group_tour/repeater_tourist_report/repeater_tourist_report_filter.php', { traveler_id:traveler_id,branch_status:branch_status}, function(data){
      pagination_load(data, column, true, false, 20, 'package_tour_report',true);
    });
}
function travelers_details(id)
  {
	  $('#'+id).prop('disabled',true);
    var base_url = $('#base_url').val();
    var branch_status = $('#branch_status').val();
    var traveler_group_id = $("#"+id).val();
	  $('#'+id).button('loading');
    $.get('reports_content/group_tour/repeater_tourist_report/repeater_tourist_report_filter_popup.php', { traveler_group_id : traveler_group_id,branch_status:branch_status }, function(data){
        $('#travelr_details_popup').html(data);
        $('#'+id).prop('disabled',false);
        $('#'+id).button('reset');
    });
  } 
tour_group_dynamic_reflect1();
</script>