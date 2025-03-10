<?php
include "../../../../../../model/model.php";

$customer_id = $_SESSION['customer_id'];
$ticket_id = $_POST['ticket_id'];

$query = "select * from train_ticket_master where 1 and delete_status='0' ";
$query .=" and customer_id='$customer_id'";
if($ticket_id!=""){
	$query .=" and train_ticket_id='$ticket_id'";
}
?>
<div class="row mg_tp_20"> <div class="col-md-12"> <div class="table-responsive">
	
<table class="table table-bordered cust_table" id="tbl_ticket_report" style="margin:20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>Sr.No</th>
			<th>Booking_ID</th>
			<th>From_Location</th>
			<th>To_Location</th>
			<th>Train_Name</th>
			<th>Train_No</th>
			<th>Class</th>
			<th>Ticket</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = 0;
		$sq_ticket = mysqlQuery($query);
		while($row_ticket =mysqli_fetch_assoc($sq_ticket)){
			$date = $row_ticket['created_at'];
			$yr = explode("-", $date);
			$year =$yr[0];
			$sq_tickets = mysqli_fetch_assoc(mysqlQuery("select * from train_ticket_master_upload_entries where train_ticket_id='$row_ticket[train_ticket_id]'"));
			if(isset($sq_tickets['train_ticket_url'])){
				$url = $sq_tickets['train_ticket_url'];
				$url = explode('uploads/', $url);
				$url = BASE_URL.'uploads/'.$url[1];
			}else{
				$url = '';
			}
			
			$pass_count = mysqli_num_rows(mysqlQuery("select * from  train_ticket_master_entries where train_ticket_id='$row_ticket[train_ticket_id]'"));
			$cancel_count = mysqli_num_rows(mysqlQuery("select * from  train_ticket_master_entries where train_ticket_id='$row_ticket[train_ticket_id]' and status='Cancel'"));
			
			$sq_customer_info = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$row_ticket[customer_id]'"));
			$sq_entry1 = mysqlQuery("select * from train_ticket_master_trip_entries where train_ticket_id='$row_ticket[train_ticket_id]'");
			$bg = ($pass_count==$cancel_count) ? 'danger' : '';
			while($row_entery1 = mysqli_fetch_assoc($sq_entry1)){
				?>
				<tr class="<?= $bg ?>">
					<td><?= ++$count ?></td>
					<td><?= get_train_ticket_booking_id($row_ticket['train_ticket_id'],$year) ?></td>
					<td><?= $row_entery1['travel_from'] ?></td>
					<td><?= $row_entery1['travel_to'] ?></td>
					<td><?= $row_entery1['train_name'] ?></td>
					<td><?= $row_entery1['train_no'] ?></td>
					<td><?= $row_entery1['class'] ?></td>
					<td>
					<?php if(isset($sq_tickets['train_ticket_url'])){  ?>
						<a href="<?= $url ?>" download class="btn btn-info btn-sm"><i class="fa fa-download"></i></a>
					<?php }else{
						echo 'NA';
					}
			} ?>
						</td>
				</tr>
				<?php
			}
		?>
	</tbody>
</table>
</div> </div> </div>
<script type="text/javascript">
$('#tbl_ticket_report').dataTable({
	"pagingType": "full_numbers"
});
</script>
