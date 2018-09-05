<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title"><?php echo __('Vehicle Listing');?></h4>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?= SITE_URL_ADMIN . '/Users/dashboard' ?>">Home</a>
                        </li>
                        <li class="active">
                            <?php echo __('Users'); ?>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <h4 class="m-t-0 header-title"><b><?php echo __('Purchased Vehicles');?></b></h4>
						<div class="form-inline m-b-20">
							<div class="row">
								<div class="col-sm-6 text-xs-center">
										<!--<div class="form-group">
												<label class="control-label m-r-5">Status</label>
												<select id="demo-foo-filter-status" class="form-control input-sm">
														<option value="">Show all</option>
														<option value="active">Active</option>
														<option value="disabled">Inactive</option>
												</select>
										</div>-->
								</div>
								
								<div class="col-sm-6 text-xs-center text-right">
										<div class="form-group">
												<select id="demo-foo-filter-status" class="form-control input-sm searchBox">
														<option value="all" selected >Show all</option>
														<option value="model">Model</option>
														<option value="body">Body Type</option>
												</select>
										</div>
										<div class="form-group">
												<input id="demo-foo-search searchField" placeholder="Search" class="form-control input-sm searchField" required autocomplete="on" type="text">
												<button class="btn btn-primary waves-effect waves-light searchpurVehicle" type="button">Go</button>
										</div>
								</div>
							</div>
						</div>
                        <table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable vehicleTableData" data-paging="true" data-page-size="7">
							<thead>
								<tr>
									<th class="footable-visible footable-sortable"><?php echo __('Buyer Name');?><!--<span class="footable-sort-indicator"></span>--></th>
									<th class="footable-visible footable-sortable"><?php echo __('Seller Name');?><!--<span class="footable-sort-indicator"></span>--></th>
									<th data-toggle="true" class="footable-visible footable-first-column footable-sortable"><?php echo __('Model, Type');?><!--<span class="footable-sort-indicator"></span>--></th>
									<th class="footable-visible footable-sortable"><?php echo __('Body Type');?><!--<span class="footable-sort-indicator"></span>--></th>
									<th data-hide="phone" class="footable-visible footable-sortable"><?php echo __('Doors');?><!--<span class="footable-sort-indicator"></span>--></th>
									<th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('HP');?><!--<span class="footable-sort-indicator"></span>--></th>
									<th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Fuel');?><!--<span class="footable-sort-indicator"></span>--></th>
									<th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Gear');?><!--<span class="footable-sort-indicator"></span>--></th>
									<th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Factory Price');?><!--<span class="footable-sort-indicator"></span>--></th>
									<th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Created');?><!--<span class="footable-sort-indicator"></span>--></th>
									<th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('View Detail Page');?><!--<span class="footable-sort-indicator"></span>--></th>
								</tr>
							</thead>

							<tbody>
								<?php
								if (isset($auction_data) && !empty($auction_data)) {
									$count = 0;
									foreach ($auction_data['SoldVehicles'] as $data) {
										?>
										<tr style="display: table-row;" class="<?php
										if ($count % 2 == 0) {
											echo 'footable-even';
										} else {
											echo 'footable-even';
										}
										?>">
											<td class="footable-visible footable-first-column">
												<?php
												if (isset($data['User']['id']) && !empty($data['User']['id'])){
													echo (!empty($auction_data['buyerArr']))?trim($auction_data['buyerArr'][$data['Vehicle']['buyer_id']]):'N/A';
												} else {
													echo "N/A";
												}
												?>
											</td>
											
											<td class="footable-visible footable-first-column">
												<?php
												if (isset($data['User']['fname']) && !empty($data['User']['fname'])){
													echo trim($data['User']['fname'] . " " . $data['User']['lname']);
												} else {
													echo "N/A";
												}
												?>
											</td>
											
											<td class="footable-visible footable-first-column">
												<?php
												if (isset($data['Vehicle']['brand']) && !empty($data['Vehicle']['brand'])) {
													echo trim($data['Vehicle']['brand'] . " " . $data['Vehicle']['model'] . " " . $data['Vehicle']['type']);
												} else {
													echo "N/A";
												}
												?>
											</td>
											<td class="footable-visible">
												<?php
												if (isset($data['Vehicle']['body_type']) && !empty($data['Vehicle']['body_type'])) {
													echo $data['Vehicle']['body_type'];
												} else {
													echo "N/A";
												}
												?>
											</td>
											<td class="footable-visible">
												<?php
												if (isset($data['Vehicle']['doors']) && !empty($data['Vehicle']['doors'])) {
													echo $data['Vehicle']['doors'];
												} else {
													echo "N/A";
												}
												?>
											</td>
											<td class="footable-visible">
												<?php
												if (isset($data['Vehicle']['hp']) && !empty($data['Vehicle']['hp'])) {
													echo $data['Vehicle']['hp'];
												} else {
													echo "N/A";
												}
												?>
											</td>
											<td class="footable-visible">
												<?php
												if (isset($data['Vehicle']['fuel']) && !empty($data['Vehicle']['fuel'])) {
													echo $data['Vehicle']['fuel'];
												} else {
													echo "N/A";
												}
												?>
											</td>
											<td class="footable-visible">
												<?php
												if (isset($data['Vehicle']['gear']) && !empty($data['Vehicle']['gear'])) {
													echo $data['Vehicle']['gear'];
												} else {
													echo "N/A";
												}
												?>
											</td>
											<td class="footable-visible">
												<?php
												if (isset($data['Vehicle']['factory_price']) && !empty($data['Vehicle']['factory_price'])) {
													echo $data['Vehicle']['factory_price'];
												} else {
													echo "N/A";
												}
												?>
											</td>
											
											<td class="footable-visible">
												<?php
												if (isset($data['Vehicle']['created']) && !empty($data['Vehicle']['created'])) {
													echo date('d-M-Y', strtotime($data['Vehicle']['created']));
												} else {
													echo "N/A";
												}
												?>
											</td>
											<td class="footable-visible footable-last-column"><a href="<?php echo BASE_URL. '/admin/Vehicles/vehicledetailpage/' . base64_encode($data['Vehicle']['id']) .'/'.base64_encode('purchased_vehicles'); ?>" class="btn-Preis"><?php echo __('Visit Details Page'); ?></a></td>
										</tr>
										<?php
										$count++;
									}
								} else { ?>
								<tr>
									<td colspan="11">No Record Found</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
                    </div>
                    <div class="pgCounter">
						<?php echo 'Page '.$this->Paginator->counter(); ?>
                    </div>
					<div class="pagination pagination-right">
						<ul>
							<?php 
								echo $this->Paginator->prev( '<<', array( 'class' => 'myclass', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
								echo $this->Paginator->numbers( array( 'tag' => 'li', 'separator' => '', 'class' => 'active myclass', 'currentTag' => 'a' ) );
								echo $this->Paginator->next( '>>', array( 'class' => 'myclass', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
							?>
						</ul>
					</div>
                </div>
            </div>

        </div> <!-- container -->

    </div> <!-- content -->

    <!-- Start Footer -->
<?php echo $this->element('admin/footer'); ?>
    <!-- End Footer -->

</div>
