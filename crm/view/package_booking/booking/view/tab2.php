<!-- Train Info -->
<?php $sq_t_count = mysqli_num_rows(mysqlQuery("select * from package_train_master where booking_id='$booking_id'")); 
    if($sq_t_count != '0'){ ?> 
<div class="row">

	<div class="col-xs-12 mg_bt_20">

		<div class="profile_box main_block">

        	 	<h3 class="editor_title">Train Details</h3>

				<div class="table-responsive">

                    <table class="table table-bordered no-marg">

	                    <thead>

	                       	<tr class="table-heading-row">

		                       	<th>S_No.</th>

		                       	<th>Departure_D/T</th>

		                       	<th>Location_From</th>

		                       	<th>Location_To</th>

		                       	<th>Train_Name_&_No.</th>

		                       	<th>Seats</th>

		                       	<th>Amount</th>

		                       	<th>Class</th>

		                       	<th>Priority</th>



	                       </tr>

	                    </thead>

	                    <tbody>

	                       <?php 

	                       		$count = 0;

	                       		$sq_entry = mysqlQuery("select * from package_train_master where booking_id='$booking_id'");

	                       		while($row_entry = mysqli_fetch_assoc($sq_entry)){

	                       			$count++;

	                       	?>

							<tr class="<?php echo $bg; ?>">

							    <td><?php echo $count; ?></td>

							    <td><?php echo date("d-m-Y H:i", strtotime($row_entry['date'])); ?></td>

							    <td><?php echo $row_entry['from_location'] ?></td>

								<td><?php echo $row_entry['to_location']; ?></td>

							    <td><?php echo $row_entry['train_no']; ?></td>

							    <td><?php echo $row_entry['seats']; ?></td>

							    <td><?php echo $row_entry['amount']; ?> </td>

							    <td><?php echo $row_entry['train_class']; ?> </td>

							    <td><?php echo $row_entry['train_priority']; ?></td>

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
<?php } ?>

<!-- Flight Info -->
<?php $sq_f_count = mysqli_num_rows(mysqlQuery("select * from package_plane_master where booking_id='$booking_id'")); 
    if($sq_f_count != '0'){?> 

<div class="row mg_bt_20">

	<div class="col-xs-12">

		<div class="profile_box main_block">

        	 	<h3 class="editor_title">Flight Details</h3>

				<div class="table-responsive">

                    <table class="table table-bordered no-marg">

	                    <thead>

	                       	<tr class="table-heading-row">

		                       	<th>S_No.</th>
		                       	<th>Departure_D/T</th>

		                       	<th>Arrival_D/T</th>

		                       	<th>From_City</th>
								<th>Sector_From</th>
								<th>To_City</th>

		                       	<th>Sector_To</th>

		                       	<th>Airline</th>
		                       	<th>Class</th>

		                       	<th>Seats</th>

		                       	<th>Amount</th>


	                       </tr>

	                    </thead>

	                    <tbody>

	                       <?php 

	                       		$count = 0;

	                       		$sq_entry = mysqlQuery("select * from package_plane_master where booking_id='$booking_id'");
	                       		while($row_entry = mysqli_fetch_assoc($sq_entry)){

	                       			$count++;
	                       			$sq_airline = mysqli_fetch_assoc(mysqlQuery("select * from airline_master where airline_id='$row_entry[company]'"));
	                       			$sq_city = mysqli_fetch_assoc(mysqlQuery("select city_name from city_master where city_id='$row_entry[from_city]'"));
		                            $sq_city1 = mysqli_fetch_assoc(mysqlQuery("select city_name from city_master where city_id='$row_entry[to_city]'"));
	                       	?>

							<tr class="<?php echo $bg; ?>">

							    <td><?php echo $count; ?></td>
							    <td><?php echo date("d-m-Y H:i", strtotime($row_entry['date'])) ?></td>

							    <td><?php echo  date("d-m-Y H:i", strtotime($row_entry['arraval_time'])); ?> </td>

							    <td><?php echo $sq_city['city_name']; ?></td>
								<td><?php echo $row_entry['from_location']; ?></td>
								<td><?php echo $sq_city1['city_name']; ?></td>

								<td><?php echo $row_entry['to_location']; ?></td>

							    <td><?php echo $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')'; ?></td>

							    <td><?php echo $row_entry['class']; ?></td>
							    <td><?php echo $row_entry['seats']; ?></td>

							    <td><?php echo $row_entry['amount']; ?> </td>


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
<?php } ?>

<!-- Cruise Info -->
<?php $sq_c_count = mysqli_num_rows(mysqlQuery("select * from package_cruise_master where booking_id='$booking_id'")); 
    if($sq_c_count != '0'){?>
	<div class="row">

	<div class="col-xs-12 mg_bt_20">

		<div class="profile_box main_block">

        	 	<h3 class="editor_title">Cruise Details</h3>
				<div class="table-responsive">
                    <table class="table table-bordered no-marg">
	                    <thead>
	                       	<tr class="table-heading-row">
		                       	<th>S_No.</th>
		                       	<th>Departure_D/T</th>
		                       	<th>Arrival_D/T</th>
		                       	<th>Route</th>
		                       	<th>Cabin</th>
		                       	<th>Sharing</th>
		                       	<th>Seats</th>
		                       	<th>Amount</th>
	                       </tr>
	                    </thead>
	                    <tbody>
	                       <?php 
	                       		$count = 0;
	                       		$sq_entry = mysqlQuery("select * from package_cruise_master where booking_id='$booking_id'");
	                       		while($row_entry = mysqli_fetch_assoc($sq_entry)){
	                       			$count++;
	                       	?>
							<tr class="<?php echo $bg; ?>">
							    <td><?php echo $count; ?></td>
							    <td><?php echo date("d-m-Y H:i", strtotime($row_entry['dept_datetime'])); ?></td>
							    <td><?php echo date("d-m-Y H:i", strtotime($row_entry['arrival_datetime'])); ?></td>
								<td><?php echo $row_entry['route']; ?></td>
							    <td><?php echo $row_entry['cabin']; ?></td>
							    <td><?php echo $row_entry['sharing']; ?></td>
							    <td><?php echo $row_entry['seats']; ?> </td>
							    <td><?php echo $row_entry['amount']; ?> </td>
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
<?php } ?>