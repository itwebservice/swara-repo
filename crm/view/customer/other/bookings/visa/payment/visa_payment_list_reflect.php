<?php
include "../../../../../../model/model.php";
$visa_id = $_POST['visa_id'];
$customer_id = $_SESSION['customer_id'];
?>
<div class="row mg_tp_20"> <div class="col-md-12"> <div class="table-responsive">
<table class="table table-bordered bg_white cust_table" id="visa_payment_list" style="margin:20px 0 !important">
	<thead>
	 <tr class="table-heading-row">
		<th>S_No.</th>
		<th>Visa_ID</th>
		<th>Payment_Date</th>
		<th>Mode</th>
		<th>Bank_Name</th>
		<th>Cheque_No/ID</th>
		<th class="success">Amount</th>
		<th>Receipt </th>
	</tr>
	</thead>
	<tbody>
		<?php 
		$query = "SELECT * from visa_payment_master where 1 and payment_amount!='0' ";	
		if($visa_id!=""){
			$query .= " and visa_id='$visa_id'";
		}
		if($customer_id!=""){
			$query .= " and visa_id in (select visa_id from visa_master where customer_id='$customer_id')";
		}

		$count = 0;
		$total_paid_amt=0;

		$sq_pending_amount=0;
		$sq_cancel_amount=0;
		$sq_paid_amount=0;
		$total_payment=0;
		$footer_paid_total = 0; $footer_pending_total = 0; $footer_cancel_total = 0;
		$sq_visa_payment = mysqlQuery($query);

		$sq_cancel_pay=mysqli_fetch_assoc(mysqlQuery("select sum(payment_amount) as sum from visa_payment_master where clearance_status='Cleared'"));
	
		$sq_pend_pay=mysqli_fetch_assoc(mysqlQuery("select sum(payment_amount) as sum from visa_payment_master where clearance_status='Pending'"));

		while($row_visa_payment = mysqli_fetch_assoc($sq_visa_payment)){

			$sq_cancel_amount = 0;
			$sq_pending_amount = 0;
			$count++;
			$sq_visa_info = mysqli_fetch_assoc(mysqlQuery("select * from visa_master where visa_id='$row_visa_payment[visa_id]'"));
			$total_sale = $sq_visa_info['visa_total_cost'];
			$sq_pay = mysqli_fetch_assoc(mysqlQuery("select sum(payment_amount) as sum from visa_payment_master where clearance_status!='Cancelled' and visa_id='$row_visa_payment[visa_id]'"));
			$total_pay_amt = $sq_pay['sum'];
			
			$date = $sq_visa_info['created_at'];
			$yr = explode("-", $date);
			$year =$yr[0];
			$outstanding =  $total_sale - $total_pay_amt;

			$sq_customer_info = mysqli_fetch_assoc(mysqlQuery("select * from customer_master where customer_id='$sq_visa_info[customer_id]'"));

			$bg='';

			if($row_visa_payment['clearance_status']=="Pending"){ $bg='warning';
				$sq_pending_amount = $row_visa_payment['payment_amount']+ $row_visa_payment['credit_charges'];
			}
			else if($row_visa_payment['clearance_status']=="Cancelled"){ $bg='danger';
				$sq_cancel_amount = $row_visa_payment['payment_amount']+ $row_visa_payment['credit_charges'];
			}	
			else if($row_visa_payment['clearance_status']=="Cancelled"){ $bg='success';
			}else{
				$bg='';
			}
			$paid_amount = currency_conversion($currency,$sq_visa_info['currency_code'],$row_visa_payment['payment_amount']+$row_visa_payment['credit_charges']);
			$sq_paid_amount_string = explode(' ',$paid_amount);
			$footer_paid_total += str_replace(',', '', $sq_paid_amount_string[1]);
			
			$pending_amount = currency_conversion($currency,$sq_visa_info['currency_code'],$sq_pending_amount);
			$sq_pending_amount_string = explode(' ',$pending_amount);
			$footer_pending_total += str_replace(',', '', $sq_pending_amount_string[1]);
			$cancel_amounts = currency_conversion($currency,$sq_visa_info['currency_code'],$sq_cancel_amount);
			
			$sq_cancel_amount_string = explode(' ',$cancel_amounts);
			$footer_cancel_total += str_replace(',', '', $sq_cancel_amount_string[1]);
			$sq_paid_amount = $sq_paid_amount + $row_visa_payment['payment_amount']+ $row_visa_payment['credit_charges'];
			$date = $row_visa_payment['payment_date'];
			$yr = explode("-", $date);
			$year1 =$yr[0];

			$payment_id_name = "Visa Payment ID";
			$payment_id = get_visa_booking_payment_id($row_visa_payment['payment_id'],$year1);
			$receipt_date = date('d-m-Y');
			$booking_id = get_visa_booking_id($row_visa_payment['visa_id'],$year);
			$customer_id = $sq_visa_info['customer_id'];
			$booking_name = "Visa Booking";
			$travel_date = 'NA';
			$payment_amount = $row_visa_payment['payment_amount']+ $row_visa_payment['credit_charges'];
			$payment_mode1 = $row_visa_payment['payment_mode'];
			$transaction_id = $row_visa_payment['transaction_id'];
			$payment_date = date('d-m-Y',strtotime($row_visa_payment['payment_date']));
			$bank_name = $row_visa_payment['bank_name'];
			$receipt_type = "Visa Receipt";			

			$url1 = BASE_URL."model/app_settings/print_html/receipt_html/receipt_body_html.php?payment_id_name=$payment_id_name&payment_id=$payment_id&receipt_date=$receipt_date&booking_id=$booking_id&customer_id=$customer_id&booking_name=$booking_name&travel_date=$travel_date&payment_amount=$payment_amount&transaction_id=$transaction_id&payment_date=$payment_date&bank_name=$bank_name&confirm_by=&receipt_type=$receipt_type&payment_mode=$payment_mode1&outstanding=$outstanding&branch_status=yes&table_name=visa_payment_master&customer_field=visa_id&in_customer_id=$row_visa_payment[visa_id]&currency_code=$sq_visa_info[currency_code]&status=$row_visa_payment[status]&branch_admin_id=$sq_visa_info[branch_admin_id]";
			?>
			<tr class="<?= $bg?>">
				<td><?= $count ?></td>
				<td><?=  get_visa_booking_id($row_visa_payment['visa_id'],$year); ?></td>
				<td><?= date('d-m-Y', strtotime($row_visa_payment['payment_date'])) ?></td>
				<td><?= $row_visa_payment['payment_mode'] ?></td>
				<td><?= $row_visa_payment['bank_name']; ?></td>
				<td><?= $row_visa_payment['transaction_id']; ?></td>
				<td class="success"><?= $paid_amount ?></td>
				<td>
					<a onclick="loadOtherPage('<?= $url1 ?>')" class="btn btn-info btn-sm" title="Download Receipt"><i class="fa fa-print"></i></a>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
	<tfoot>
		<tr class="active">
			<th colspan="2" class="text-right">Paid Amount : <?= number_format($footer_paid_total,2) ?></th>
			<th colspan="2" class="warning text-right">Pending Clearance : <?= number_format($footer_pending_total,2) ?></th>
			<th colspan="2" class="danger text-right">Cancelled : <?= number_format($footer_cancel_total,2) ?></th>
			<th colspan="1" class="success text-right">Total Payment : <?= number_format(($footer_paid_total - $footer_pending_total - $footer_cancel_total),2) ?></th>
			<th class="active"></th>
		</tr>
	</tfoot>	
</table>
</div> </div> </div>
<script>
	$('#visa_payment_list').dataTable({
		"pagingType": "full_numbers"
	});
</script>