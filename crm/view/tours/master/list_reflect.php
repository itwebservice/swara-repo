<?php
include "../../../model/model.php";
$tour_type = $_GET['tour_type'];
$tour_id = $_GET['tour_id'];
$status = $_GET['status'];
?>
<div class="row">
    <div class="col-md-12 no-pad">
        <div class="table-responsive">
            <table class="table" id="tbl_tour_list" style="margin: 20px 0 !important;">
                <thead>
                    <tr class="table-heading-row">
                        <th>S_No.</th>
                        <th>Tour_Type</th>
                        <th>Tour_Name</th>
                        <th>Adult_Cost</th>
                        <th>CWB_Cost</th>
                        <th>CWOB_Cost</th>
                        <th>Infant_Cost </th>
                        <th>Extrabed_Cost </th>
                        <th>Single_person_Cost </th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
					$count = 0;
					if ($status != '') {
						$query = "select * from tour_master where 1 and active_flag='$status'";
					} else {

						$query = "select * from tour_master where 1 and active_flag='Active'";
					}
					if ($tour_type != "") {
						$query .= " and tour_type = '$tour_type'";
					}
					if ($tour_id  != "") {
						$query .= " and tour_id = '$tour_id'";
					}
					$sq_tours = mysqlQuery($query);
					while ($row_tour = mysqli_fetch_assoc($sq_tours)) {
						$bg = ($row_tour['active_flag'] == "Inactive") ? "danger" : "";
					?>
                    <tr class="<?= $bg ?>">
                        <td><?= ++$count ?></td>
                        <td><?= $row_tour['tour_type'] ?></td>
                        <td><?= $row_tour['tour_name'] ?></td>
                        <td><?= $row_tour['adult_cost']; ?></td>
                        <td><?= $row_tour['child_with_cost']; ?></td>
                        <td><?= $row_tour['child_without_cost']; ?></td>
                        <td><?= $row_tour['infant_cost']; ?></td>
                        <td><?= $row_tour['with_bed_cost']; ?></td>
                        <td><?= $row_tour['single_person_cost']; ?></td>
                        <td style="display:flex;">
                            <?php echo '
                            <form style="display:inline-block" action="update/update_group_tour.php" class="no-marg" method="POST">
                                <input type="hidden" id="tour_id" style="display:inline-block" name="tour_id" value="' . $row_tour['tour_id'] . '">
                                <button class="btn btn-info btn-sm form-control" id="update_btn' . $row_tour['tour_id'] . '" title="Update Details"><i class="fa fa-pencil-square-o"></i></button>
                            </form>'; ?>
                            <button class="btn btn-info btn-sm" onclick="display_modal(<?= $row_tour['tour_id'] ?>)" title="View Details" id="view_btn-<?= $row_tour['tour_id'] ?>"><i class="fa fa-eye"></i></button>
                        </td>

                    </tr>
                    <?php
					}
					?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$('#tbl_tour_list').dataTable({
    "pagingType": "full_numbers"
});
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>