<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable" data-paging="true" data-page-size="7">
	<thead>
		<tr>
			<th data-toggle="true" class="footable-visible footable-first-column footable-sortable"><?php echo __('Model, Type');?><!--<span class="footable-sort-indicator"></span>--></th>
			<th class="footable-visible footable-sortable"><?php echo __('Body Type');?><!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone" class="footable-visible footable-sortable"><?php echo __('Doors');?><!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('HP');?><!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Fuel');?><!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Gear');?><!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Factory Price');?><!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Created');?><!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Action'); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php
		if (isset($vehicles) && !empty($vehicles)) {
			$count = 0;
			foreach ($vehicles as $data) {
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
					<td class="footable-visible footable-last-column">
						<a href="<?php /*echo SITE_URL_ADMIN . '/Vehicle/edit/' . base64_encode($data['Vehicle']['id'])*/ ?>javascript:void(0);" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
					</td>
				</tr>
				<?php
				$count++;
			}
		}else{
		?>
		<tr>
			<td colspan="9">
				No Recoard Found
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
