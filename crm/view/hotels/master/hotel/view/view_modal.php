<div class="panel panel-default panel-body fieldset profile_background">
	<!-- Tab panes1 -->
	<div class="tab-content">
		<!-- *****TAb1 start -->
		<div role="tabpanel" class="tab-pane active" id="basic_information">
			<div class="row">
				<div class="col-md-12">
					<div class="profile_box main_block">
						<?php $sq_city = mysqli_fetch_assoc(mysqlQuery("select city_name from city_master where city_id='$sq_hotel[city_id]'")); ?>
						<div class="row">
							<div class="col-md-6 right_border_none_sm" style="border-right: 1px solid #ddd">

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>City Name <em>:</em></label> " . $sq_city['city_name']; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Hotel Name <em>:</em></label> " . $sq_hotel['hotel_name']; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Mobile No <em>:</em></label> " . $mobile_no; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Landline No <em>:</em></label> " . $sq_hotel['landline_no']; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Email ID <em>:</em></label> " . $email_id; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Alternative Email ID 1<em>:</em></label> " . $email_id1; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Alternative Email ID 2<em>:</em></label> " . $email_id2; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Contact Person <em>:</em></label> " . $sq_hotel['contact_person_name']; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Emergency Contact <em>:</em></label> " . $sq_hotel['immergency_contact_no']; ?>

								</span>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Hotel Category <em>:</em></label> " . $sq_hotel['rating_star']; ?>
								</span>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Hotel Type <em>:</em></label> " . $sq_hotel['hotel_type']; ?>
								</span>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Hotel Address <em>:</em></label> " . ucfirst($sq_hotel['hotel_address']); ?>
								</span>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Hotel Description <em>:</em></label> "; ?>
									<h6 style='margin: 20px;'><?= $sq_hotel['description'] ?></h6>
								</span>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Hotel Amenities <em>:</em></label> <br> ";
									if ($sq_hotel['amenities'] != '') { ?>
										<ul style='margin: 20px;'>
											<?php
											$amenties = explode(',', $sq_hotel['amenities']);
											for ($i = 0; $i < sizeof($amenties); $i++) {
											?>
												<li class="col-md-6"><?= $amenties[$i] ?></li>
											<?php } ?>
										</ul>
									<?php } ?>
								</span>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Child Without Bed From-To Age <em>:</em></label> " . $sq_hotel['cwob_from'] . '-' . $sq_hotel['cwob_to']; ?>
								</span>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Child With Bed From-To Age <em>:</em></label> " . $sq_hotel['cwb_from'] . '-' . $sq_hotel['cwb_to']; ?>
								</span>
								<span class="main_block">
									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>
									<?php echo "<label>Hotel Policies <em>:</em></label> "; ?>
									<h6 style='margin: 20px;'><?= $sq_hotel['policies'] ?></h6>
								</span>


							</div>

							<div class="col-md-6">
								<?php $sq_state = mysqli_fetch_assoc(mysqlQuery("select * from state_master where id='$sq_hotel[state_id]'"));
								?>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>State/Country <em>:</em></label>" . $sq_state['state_name'] ?>

								</span>

								<!-- <span class="main_block">

		        				<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i> 

		        				    <?php echo "<label>Country <em>:</em></label> " . ucfirst($sq_hotel['country']); ?>

		        				</span> -->
								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Website <em>:</em></label> " . $sq_hotel['website']; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Bank Name <em>:</em></label> " . $sq_hotel['bank_name']; ?>

								</span>
								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Account Type <em>:</em></label> " . $sq_hotel['account_name']; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Account No <em>:</em></label> " . $sq_hotel['account_no']; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Branch <em>:</em></label> " . $sq_hotel['branch']; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>IFSC/SWIFT CODE <em>:</em></label> " . $sq_hotel['ifsc_code']; ?>

								</span>
								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label> Personal Identification No(PIN) <em>:</em></label> " . $sq_hotel['pan_no']; ?>

								</span>

								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Tax No <em>:</em></label> " . strtoupper($sq_hotel['service_tax_no']); ?>

								</span>
								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label> Opening Balance <em>:</em></label> " . $sq_hotel['opening_balance']; ?>

								</span>
								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label> Balance Side <em>:</em></label> " . $sq_hotel['side']; ?>

								</span>
								<span class="main_block">

									<i class="fa fa-angle-double-right cost_arrow" aria-hidden="true"></i>

									<?php echo "<label>Status <em>:</em></label> " . $sq_hotel['active_flag']; ?>

								</span>
								<?php
								$sq_hotel_image = mysqli_fetch_assoc(mysqlQuery("select * from hotel_vendor_images_entries where hotel_id='$sq_hotel[hotel_id]'"));
								$newUrl = isset($sq_hotel_image['hotel_pic_url']) ? preg_replace('/(\/+)/', '/', $sq_hotel_image['hotel_pic_url']) : '';

								?>

							</div>

						</div>
						<div class="row">
							<div class="col-md-12">

							</div>
						</div>
						<div class="row">
							<div class="col-md-12">

							</div>
						</div>
						<div class="row">
							<div class="col-md-12">

							</div>
						</div>
						<div class="row">
							<div class="col-md-6">

							</div>
							<div class="col-md-6" style="border-right: 1px solid #ddd">

							</div>
						</div>
						<div class="row">
							<div class="col-md-12">

							</div>
						</div>

					</div>

				</div>



			</div>

		</div>

		<!-- ********Tab1 End******** -->

	</div>

</div>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>