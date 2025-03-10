<?php
include "../../../../../../model/model.php";

$ticket_id = $_POST['ticket_id'];
$customer_id = $_SESSION['customer_id'];

$query = "select * from ticket_refund_master where 1 ";
if($ticket_id!=""){
	$query .=" and ticket_id='$ticket_id'";
}
$query .=" and ticket_id in ( select ticket_id from ticket_master where customer_id='$customer_id' )";

?>
<div class="row mg_tp_20"> <div class="col-md-12"> <div class="table-responsive">
	
<table class="table table-bordered table-hover bg_white cust_table" id="tbl_refund" style="margin:20px 0 !important;">
	<thead>
		<tr class="table-heading-row">
			<th>S_No.</th>
			<th>Booking ID</th>
			<th>Refund_Date</th>
			<th>Mode</th>
			<th>Bank Name</th>
			<th>Cheque_No/ID</th>
			<th class="success">Amount</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$total_refund = 0;
		$count = 0;
		$bg;
		$sq_pending_amount=0;
		$total_refund_amt=0;
		$sq_paid_amount=0;
		$canceled_refund=0;
		$sq_refund = mysqlQuery($query);
		while($row_refund = mysqli_fetch_assoc($sq_refund)){

			$date = $row_refund['refund_date'];
			$yr = explode("-", $date);
			$year =$yr[0];
			$traveler_name = "";
			$sq_refund_entries = mysqlQuery("select * from ticket_refund_entries where refund_id='$row_refund[refund_id]'");
			while($row_refund_entry = mysqli_fetch_assoc($sq_refund_entries)){

				$sq_entry_info = mysqli_fetch_assoc(mysqlQuery("select * from ticket_master_entries where entry_id='$row_refund_entry[entry_id]'"));
				$traveler_name .= $sq_entry_info['first_name'].' '.$sq_entry_info['last_name'].', ';
			}
			$traveler_name = trim($traveler_name, ", ");

			$total_refund = $total_refund+$row_refund['refund_amount'];			

			if($row_refund['clearance_status']=="Pending"){ $bg='warning';
				$sq_pending_amount = $sq_pending_amount + $row_refund['refund_amount'];
			}
			if($row_refund['clearance_status']=="Cleared"){ $bg='';
				$sq_paid_amount = $sq_paid_amount + $row_refund['refund_amount'];
			}

			if($row_refund['clearance_status']=="Cancelled"){ 
				$bg = "danger"; 
				$canceled_refund = $canceled_refund + $row_refund['refund_amount'];
			}

			if($row_refund['clearance_status']==""){ $bg='';
				$sq_paid_amount = $sq_paid_amount + $row_refund['refund_amount'];
			}

			$total_refund_amt += $row_refund['refund_amount'];
			?>
			<tr class="<?= $bg?>">
				<td><?= ++$count ?></td>
				<td><?= get_ticket_booking_id($row_refund['ticket_id'],$year); ?></td>
				<td><?= date('d-m-Y', strtotime($row_refund['refund_date'])) ?></td>
				<td><?= $row_refund['refund_mode'] ?></td>
				<td><?= $row_refund['bank_name'] ?></td>
				<td><?= $row_refund['transaction_id'] ?></td>
				<td class="success"><?= $row_refund['refund_amount'] ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
	<tfoot>
		<tr class="active">
			<th colspan="1" class="text-right info">Refund: <?= ($total_refund_amt=='')?number_format(0,2): number_format($total_refund_amt,2); ?></th>
			<th colspan="2" class="text-right warning">Pending : <?= ($sq_pending_amount=='')?number_format(0,2): number_format($sq_pending_amount,2);?></th>
			<th colspan="2" class="text-right danger">Cencelled: <?= ($canceled_refund=='')?number_format(0,2): number_format($canceled_refund,2); ?></th>
			<th colspan="2" class="text-right success">Total Refund: <?= number_format(($total_refund_amt-$sq_pending_amount- $canceled_refund),2);?></th>
		</tr>
	</tfoot>
</table>

</div> </div> </div>
<script>
$('#tbl_refund').dataTable({
	"pagingType": "full_numbers"
});
</script>