<?php
include_once ('../../model/model.php');
$tourwise_id = $_POST['cmb_tourwise_traveler_id'];
?>
<h3 class="editor_title">Refund History</h3>
<div class="row text-center"><div class="col-xs-12">
<div class="table-responsive">
<table class="table table-bordered table-hover no-marg">
    <thead>
    <tr class="table-heading-row">
        <th>S_No.</th>
        <th>passenger_Name</th>
        <th>Amount</th>
        <th>Refund_Date</th>
        <th>Mode</th>
        <th>Bank_Name</th>
        <th>Cheque_NO/ID</th>
        <th>Voucher</th>
    </tr>
    </thead>
    <tbody>
    <?php    
    $count = 0;
    $bg;
    $pending=0;
    $cancel=0;
    $refunded=0;
    $sq_refund = mysqlQuery("select * from refund_traveler_cancelation where tourwise_traveler_id='$tourwise_id' and total_refund!=0 ");
    while($row_refund = mysqli_fetch_assoc($sq_refund))
    {        
        $count++;
    
        $refunded= $refunded + $row_refund['total_refund'];

        if($row_refund['clearance_status']=="Pending"){ 
            $bg = "warning";
            $pending = $pending + $row_refund['total_refund'];
        }
        else if($row_refund['clearance_status']=="Cancelled"){ 
            $bg = "danger"; 
            $cancel = $cancel + $row_refund['total_refund'];    
        }
        else if($row_refund['clearance_status']=="Cleared"){ 
            $bg = "success"; 
        }
        else{
            $bg = ""; 
        }
        $date = $row_refund['created_at'];
        $yr = explode("-", $date);
        $year = $yr[0];

    ?>
    <tr class="<?= $bg?>">
        <td class="text-left"><?= $count; ?></td>
        <td class="text-left">
        <?php 
        $sq_refund_entry = mysqlQuery("select * from refund_traveler_cancalation_entries where refund_id='$row_refund[refund_id]'");
        while($row_refund_entry = mysqli_fetch_assoc($sq_refund_entry)){
            $sq_traveler = mysqli_fetch_assoc(mysqlQuery( "select m_honorific, first_name, last_name from travelers_details where traveler_id='$row_refund_entry[traveler_id]'" ));
            echo $sq_traveler['m_honorific'].' '.$sq_traveler['first_name'].' '.$sq_traveler['last_name']."<br>";
        }
        ?>
        </td>
        <?php 
            $sq_refund_id = mysqli_fetch_assoc(mysqlQuery("select * from refund_traveler_cancalation_entries where refund_id='$row_refund[refund_id]'"));
            $sq_traveler_name = mysqli_fetch_assoc( mysqlQuery( "select * from travelers_details where traveler_id='$sq_refund_id[traveler_id]'" ) );

            $sq_cust = mysqli_fetch_assoc(mysqlQuery("select * from tourwise_traveler_details where id='$tourwise_id' and delete_status='0'"));

            $v_voucher_no = get_group_booking_traveler_refund_id($row_refund['refund_id'],$year);
            $v_refund_date = $row_refund['refund_date'];
            $v_refund_to = $sq_traveler_name['first_name']." ".$sq_traveler_name['last_name'];
            $v_service_name = "Group Booking";
            $v_refund_amount = $row_refund['total_refund'];
            $v_payment_mode = $row_refund['refund_mode'];
            $customer_id = $sq_cust['customer_id'];
            $refund_id = $row_refund['refund_id'];
            $url = BASE_URL."model/app_settings/generic_refund_voucher_pdf.php?v_voucher_no=$v_voucher_no&v_refund_date=$v_refund_date&v_refund_to=$v_refund_to&v_service_name=$v_service_name&v_refund_amount=$v_refund_amount&v_payment_mode=$v_payment_mode&customer_id=$customer_id&refund_id=$refund_id&booking_id=".get_group_booking_id($tourwise_id,$year)."&currency_code=$sq_cust[currency_code]";
        ?>
        <td class="text-right"><?php echo number_format($row_refund['total_refund'],2) ?></td>
        <td><?php echo date("d-m-Y", strtotime($row_refund['refund_date'])) ?></td>
        <td><?php echo $row_refund['refund_mode'] ?></td>
        <td><?php echo $row_refund['bank_name'] ?></td>
        <td><?php echo $row_refund['transaction_id'] ?></td>        
        <td><a href="<?= $url ?>" class="btn btn-danger btn-sm" target="_blank" title="Voucher"><i class="fa fa-file-pdf-o"></i></a></td>  
    </tr>
    <?php    
    }    
    ?>
    </tbody>
    <tfoot>
        <tr class="active">
            <th colspan="2" class="info">Refund : <?= ($refunded=="") ? number_format(0,2) : number_format($refunded,2) ?></th>
            
            <th colspan="2" class="warning">Pending : <?= ($pending=="") ? number_format(0,2) : number_format($pending,2) ?></th>
            <th colspan="2" class="danger">Cancelled : <?= ($cancel=="") ? number_format(0,2) : number_format($cancel,2) ?></th>
            <th colspan="2" class="success">Total_Refund : <?= number_format(($refunded - $pending - $cancel),2) ?></th>
        </tr>
    </tfoot>
</table>
<input type="hidden" id="ref_amt" value="<?= ($refunded=="") ? 0 : $refunded ?>">
</div></div></div>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script> 