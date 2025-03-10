<?php 
include "../../../../../model/model.php";
$booking_id = $_POST['booking_id'];
$sql_booking_date = mysqli_fetch_assoc(mysqlQuery("select * from bus_booking_master where booking_id = '$booking_id' and delete_status='0'")) ;
$booking_date = $sql_booking_date['created_at'];
$yr = explode("-", $booking_date);
$year =$yr[0];
?>
<div class="modal fade profile_box_modal" id="visa_display_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Booking Information(<?= get_bus_booking_id($booking_id,$year) ?>)</h4>
      </div>
      <div class="modal-body profile_box_padding">
	     <div class="row mg_bt_20">    
		  	<div class="col-xs-12">
		  		<div class="profile_box">
		           	<h3 class="editor_title">Bus Information</h3>
		            <div class="table-responsive">
                    <table  class="table table-bordered no-marg">
	                    <thead>
                       <tr class="table-heading-row">
                       	<th>S_No</th>
                        <th>Bus_Operator</th>
                        <th>Seat_Type</th>
                        <th>Bus_Type</th>
                        <th>PNR_No.</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Journey_Date&Time</th>
                        <th>Reporting_Time</th>
                        <th>Boarding_Point</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php
                      $count = 0;
                   		 $sq_entry = mysqlQuery("select * from bus_booking_entries where booking_id='$booking_id'");
                        while($row_entry = mysqli_fetch_assoc($sq_entry)){
                          if($row_entry['status']=='Cancel'){
                            $bg="danger";
                          }
                          else{
                            $bg="";
                          }
                          $count++;
                          ?>
                          <tr class="<?= $bg ?>">
                              <td><?= $count ?></td>
                              <td><?= $row_entry['company_name'] ?></td>
                              <td><?= $row_entry['seat_type'] ?></td>
                              <td><?= $row_entry['bus_type'] ?></td>
                              <td> <?= $row_entry['pnr_no'] ?></td>
                              <td><?= $row_entry['origin'] ?></td>
                              <td><?= $row_entry['destination'] ?></td>
                              <td><?= get_datetime_user($row_entry['date_of_journey']) ?></td>
                              <td><?= $row_entry['reporting_time'] ?></td>
                              <td><?= $row_entry['boarding_point_access'] ?></td>
                          </tr>   
                   		 	<?php
                      		}
                        ?>
                     </tbody>
		                </table>
		            </div>
		            </div>
		        </div>  
		    </div>
		</div>	

</div>

</div>
</div>
</div>
  
</div>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
$('#visa_display_modal').modal('show');
</script>