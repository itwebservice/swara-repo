<?php
include "../../../../model/model.php";
$package_id = $_POST['package_id'];
$sq_pckg = mysqli_fetch_assoc(mysqlQuery("select * from custom_package_master where package_id = '$package_id'"));
?>

<div class="modal fade profile_box_modal" id="bus_view_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog modal-lg" role="document" style="width: 70%">
<div class="modal-content">
	<div class="modal-body profile_box_padding">
		<div>
			<!-- Nav tabs -->
			<ul class="nav nav-tabs responsive" role="tablist">
				<li role="presentation" class="active"><a href="#basic_information" aria-controls="home" role="tab" data-toggle="tab" class="tab_name">Package Information</a></li>
			    <li role="presentation"><a href="#hotel_images" aria-controls="profile" role="tab" data-toggle="tab" class="tab_name">Gallery Image</a></li>

				<li role="presentation" class="pull-right"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></li>
			</ul>
			<!-- Tab panes1 -->
			<div class="tab-content responsive">
			<!-- *****TAb1 start -->
			<div role="tabpanel" class="tab-pane active no-pad-sm" id="basic_information">
				<?php include "tab1.php"; ?>
			</div>
			<!-- ********Tab1 End******** --> 
			<!-- ***Tab2 Start*** -->
			<div role="tabpanel" class="tab-pane no-pad-sm" id="hotel_images">
				<div class="panel panel-default panel-body fieldset profile_background no-pad-sm">
				<?php include "tab2.php"; ?>
				</div>
			</div>
			<!-- ***Tab2 End*** -->
			</div>
		</div>
	</div>
	</div>

</div>
</div>

<script>
$('#bus_view_modal').modal('show');
$('#bus_view_modal').on('shown.bs.modal', function () {
	fakewaffle.responsiveTabs(['xs', 'sm']);
});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>