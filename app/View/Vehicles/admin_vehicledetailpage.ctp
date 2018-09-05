<div class="content-page">
    <!-- Start content -->
    <div class="content">

        <div class="wraper container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Vehicle Detail Page</h4>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?= SITE_URL_ADMIN . 'Users/dashboard' ?>">Home</a>
                        </li>
                        <li class="active">
                            Vehicle Detail Page
                        </li>
                    </ol>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">

                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b>Vehicle Detail Page</b></h4>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-20">
									<h3><?php echo __('Seller Information'); ?></h3>
                                    <table class="table table-striped">
										<tbody>
											<tr>
												<td width="30%"><?php echo __('Name'); ?></td>
												<td width="70%"><?php
													if (isset($vehicle['User']['fname']) && !empty($vehicle['User']['fname'])){
														echo trim($vehicle['User']['fname'] . " " . $vehicle['User']['lname']);
													} else {
														echo "N/A";
													}
													?></td>
											</tr>
											<tr>
												<td width="30%"><?php echo __('Email'); ?></td>
												<td width="70%"><?php
													if (isset($vehicle['User']['email']) && !empty($vehicle['User']['email'])){
														echo trim($vehicle['User']['email']);
													} else {
														echo "N/A";
													}
													?></td>
											</tr>
											<tr>
												<td width="30%"><?php echo __('Phone'); ?></td>
												<td width="70%"><?php
													if (isset($vehicle['User']['phone']) && !empty($vehicle['User']['phone'])){
														echo trim($vehicle['User']['phone']);
													} else {
														echo "N/A";
													}
													?></td>
											</tr>
											<tr>
												<td width="30%"><?php echo __('Mobile'); ?></td>
												<td width="70%"><?php
													if (isset($vehicle['User']['mobile']) && !empty($vehicle['User']['mobile'])){
														echo trim($vehicle['User']['mobile']);
													} else {
														echo "N/A";
													}
													?></td>
											</tr>
										</tbody>
									</table>
                                </div>
                                <div class="p-20">
									<h3><?php echo __('Special equipment'); ?></h3>
                                    <table class="table table-striped">
										<tbody>
											<tr>
												<td width="30%"><?php echo __('Special equipment'); ?></td>
												<td width="70%"><?php echo $vehicle['Vehicle']['special_equipment']; ?></td>
											</tr>
										</tbody>
									</table>
                                </div>
                                <div class="p-20">
									<h3><?php echo __('Serial equipment'); ?></h3>
                                    <table class="table table-striped">
										<tbody>
											<tr>
												<td width="30%"><?php echo __('Serial equipment'); ?></td>
												<td width="70%"><?php echo $vehicle['Vehicle']['serial_equipment']; ?></td>
											</tr>
										</tbody>
									</table>
                                </div>
                                <div class="p-20">
									<h3><?php echo __('Vehicle description'); ?></h3>
                                    <table class="table table-striped ">
										<tbody>
											
											<tr>
												<td width="40%"><?php echo __('Vehicle Brand'); ?></td>
												<td width="60%"><?php echo $vehicle['Vehicle']['brand'] ?></td>
											</tr>
											
											<tr>
												<td width="40%"><?php echo __('Vehicle Model'); ?></td>
												<td width="60%"><?php echo $vehicle['Vehicle']['model'] ?></td>
											</tr>
											
											<tr>
												<td width="40%"><?php echo __('Vehicle Type'); ?></td>
												<td width="60%"><?php echo $vehicle['Vehicle']['type'] ?></td>
											</tr>
											
											<tr>
												<td width="40%"><?php echo __('Auction Price'); ?></td>
												<td width="60%"><?php echo $vehicle['Vehicle']['min_auction_price'].' CHF'; ?></td>
											</tr>
											
											<tr>
												<td width="40%"><?php echo __('Repairs'); ?></td>
												<td width="60%"><?php echo $vehicle['Vehicle']['repairs']; ?></td>
											</tr>

											<tr>
												<td><?php echo __('Accident vehicle'); ?></td>
												<td><?php echo $vehicle['Vehicle']['gen_condition']; ?></td>
											</tr>

											<tr>
												<td><?php echo __('Vehicle inspection'); ?></td>
												<td><?php echo $vehicle['Vehicle']['inspection']; ?></td>
											</tr>
										</tbody>
									</table>
                                </div>
                                <div class="p-20">
									<table class="table">
										<tbody>
											<tr>
												<td><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo date('m.Y', strtotime($vehicle['Vehicle']['first_reg'])); ?> Occasion </td>
												<td><i class="fa fa-bullseye" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['hp']; ?> PS/<?php echo $vehicle['Vehicle']['kw']; ?> KW</td>
											</tr>
											<tr class="">
												<td><i class="fa fa-road" aria-hidden="true"></i><?php echo intval($vehicle['Vehicle']['kilometers']); ?> km</td>
												<td><i class="fa fa-taxi" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['gear']; ?></td>
											</tr>
											<tr>
												<td><i class="fa fa-calendar" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['fuel'] . " " . $vehicle['Vehicle']['type']; ?> </td>
												<td><i class="fa fa-gear" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['wheel_drive'] . ' ' . __('wheel drive'); ?></td>
											</tr>
										</tbody>
									</table>
                                </div>
                                <div class="p-20">
									<table class="table table-striped ">
										<tbody>

											<tr>
												<td width="40%"><?php echo __('Service record'); ?></td>
												<td width="60%"><?php echo __('Available'); ?></td>
											</tr>

											<tr>
												<td><?php echo __('Vehicle registration document'); ?></td>
												<td><?php echo __('Will be supplied later'); ?></td>
											</tr>

											<tr>
												<td><?php echo __('Number of keys'); ?></td>
												<td><?php echo $vehicle['Vehicle']['no_of_keys']; ?></td>
											</tr>
										</tbody>
									</table>
                                </div>
                                <div class="p-20">
									<table class="table table-striped ">
										<tbody>

											<tr>
												<td width="40%"><?php echo __('Model Number'); ?></td>
												<td width="60%"><?php echo $vehicle['Vehicle']['vehicle_no']; ?></td>
											</tr>

											<tr>
												<td><?php echo __('Frame Number'); ?></td>
												<td><?php echo $vehicle['Vehicle']['frame_no']; ?></td>
											</tr>

											<tr>
												<td><?php echo __('Register Number'); ?></td>
												<td><?php echo $vehicle['Vehicle']['reg_no']; ?></td>
											</tr>
										</tbody>
									</table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container -->
    </div> <!-- content -->
    <!-- Start Footer -->
    <?php echo $this->element('admin/footer'); ?>
    <!-- End Footer -->
</div>			
