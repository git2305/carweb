<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable" data-page-size="7">
	<thead>
		<tr>
				<th data-toggle="true" class="footable-visible footable-first-column footable-sortable">Full Name<!--<span class="footable-sort-indicator"></span>--></th>
				<th class="footable-visible footable-sortable">Email<!--<span class="footable-sort-indicator"></span>--></th>
				<th data-hide="phone" class="footable-visible footable-sortable">Mobile<!--<span class="footable-sort-indicator"></span>--></th>
				<th data-hide="phone, tablet" class="footable-visible footable-sortable">Status<!--<span class="footable-sort-indicator"></span>--></th>
				<th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable">Created<!--<span class="footable-sort-indicator"></span>--></th>
				<th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Action');?></th>
		</tr>
	</thead>

	<tbody>
		<?php
		if (isset($user_data) && !empty($user_data)) {
			$count = 0;
			foreach ($user_data as $data) {
				?>
				<tr style="display: table-row;" class="<?php if ($count % 2 == 0) {
					echo 'footable-even';
				} else {
					echo 'footable-even';
				} ?>">
					<td class="footable-visible footable-first-column">
						<?php
						if (isset($data['User']['fname']) && !empty($data['User']['fname'])) {
							echo $data['User']['prefix_name'] . " " . $data['User']['fname'] . " " . $data['User']['lname'];
						} else {
							echo "N/A";
						}
						?>
					</td>
					<td class="footable-visible">
						<?php
						if (isset($data['User']['email']) && !empty($data['User']['email'])) {
							echo $data['User']['email'];
						} else {
							echo "N/A";
						}
						?>
					</td>
					<td class="footable-visible">
							<?php
							if (isset($data['User']['mobile']) && !empty($data['User']['mobile'])) {
								$mob = explode('+', $data['User']['mobile']);
								echo "+" . $mob[0] . " " . $mob[1];
							} else {
								echo "N/A";
							}
							?>
					</td>
					<td class="footable-visible">
						<a href="javascript:void(0);" id="status_<?= $data['User']['id']; ?>" onclick='changestatus(<?= $data['User']['id']; ?>, "User");'>
							<?php if ($data['User']['status'] == 1) { ?>
								<span class="label label-table label-success">Active</span>
							<?php } else { ?>
								<span class="label label-table label-danger">Inactive</span>
				<?php } ?>
						</a>
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
						<a href="<?= SITE_URL_ADMIN.'/Users/edit/'.base64_encode($data['User']['id']) ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
						<?= $this->Form->postLink('<i class="fa fa-remove"></i>', ['action' => 'delete', base64_encode($data['User']['id'])], ['escape' => false, 'confirm' => __('Are you sure you want to delete?')]) ?>
					</td>
				</tr>
				<?php
				$count++;
			}
		}else{
		?>
		<tr>
			<td colspan="6">
				No Recoard Found
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

    
