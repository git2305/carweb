<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable" data-page-size="7">
	<thead>
		<tr>
			<th data-toggle="true" class="footable-visible footable-first-column footable-sortable">Company Name<!--<span class="footable-sort-indicator"></span>--></th>
			<th class="footable-visible footable-sortable">Address<!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable">Created<!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Action'); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php
		if (isset($companies) && !empty($companies)) {
			$count = 0;
			foreach ($companies as $data) {
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
						if (isset($data['Company']['name']) && !empty($data['Company']['name'])) {
							echo $data['Company']['name'];
						} else {
							echo "N/A";
						}
						?>
					</td>
					<td class="footable-visible">
						<?php
						$address = '';
						if (isset($data['Company']['street']) && $data['Company']['street'] != '') {
							$address .= $data['Company']['street'] . ", ";
						}
						if (isset($data['Company']['town']) && $data['Company']['town'] != '') {
							$address .= $data['Company']['town'] . ", ";
						}
						if (isset($data['Company']['country']) && $data['Company']['country'] != '') {
							$address .= $data['Company']['country'] . ", ";
						}

						if (isset($data['Company']['postcode']) && $data['Company']['postcode'] != '') {
							$address .= $data['Company']['postcode'] . " ";
						}

						if (trim($address) != '') {
							echo trim($address);
						} else {
							echo "N/A";
						}
						?>
					</td>
					<td class="footable-visible footable-last-column">
						<?php
						if (isset($data['User']['created']) && !empty($data['User']['created'])) {
							echo date('d-M-Y', strtotime($data['User']['created']));
						} else {
							echo "N/A";
						}
						?>
					</td>
					<td class="footable-visible footable-last-column">
						<a href="<?= SITE_URL_ADMIN . '/Companies/edit/' . base64_encode($data['Company']['id']) ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
							<?= $this->Form->postLink('<i class="fa fa-remove"></i>', ['action' => 'delete', base64_encode($data['Company']['id'])], ['escape' => false, 'confirm' => __('Are you sure you want to delete?')]) ?>
					</td>
				</tr>
				<?php
				$count++;
			}
		}else{ ?>
		<tr>
			<td colspan="4">
				No Recoard Found
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
