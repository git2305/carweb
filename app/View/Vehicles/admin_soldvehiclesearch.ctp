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
					
					<td class="footable-visible footable-last-column">
						<?php
						if (isset($data['Vehicle']['created']) && !empty($data['Vehicle']['created'])) {
							echo date('d-M-Y', strtotime($data['Vehicle']['created']));
						} else {
							echo "N/A";
						}
						?>
					</td>
					<td class="footable-visible footable-last-column"><a href="<?php echo BASE_URL. '/admin/Vehicles/vehicledetailpage/' . base64_encode($data['Vehicle']['id']) .'/'.base64_encode('sold_vehicles'); ?>" class="btn-Preis"><?php echo __('Visit Details Page'); ?></a></td>
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
