<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable" data-page-size="7">
	<thead>
		<tr>
			<th data-toggle="true" class="footable-visible footable-first-column footable-sortable">Sr.No.<!--<span class="footable-sort-indicator"></span>--></th>
			<th class="footable-visible footable-sortable">Title<!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone" class="footable-visible footable-sortable">Created<!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable">Action<!--<span class="footable-sort-indicator"></span>--></th>
		</tr>
	</thead>

	<tbody>
	<?php if(isset($pages_data) && !empty($pages_data)){
		$count = 1;
		foreach($pages_data as $data){ ?>
			<tr style="display: table-row;" class="<?php if($count % 2 == 0){ echo 'footable-even'; }else{ echo 'footable-even'; } ?>">
				<td class="footable-visible footable-first-column">
					<?= $count.'.' ?>
				</td>
				<td class="footable-visible">
					<?php if(isset($data['CmsPage']['title']) && !empty($data['CmsPage']['title'])){
						echo $data['CmsPage']['title'];
					}else{
						echo "N/A";
					} ?>
				</td>
				<td class="footable-visible">
					<?php if(isset($data['CmsPage']['created']) && !empty($data['CmsPage']['created'])){
						echo date('d-M-Y', strtotime($data['CmsPage']['created']));
					}else{
						echo "N/A";
					} ?>
				</td>
				<td class="footable-visible footable-last-column">
					<a href="<?= SITE_URL_ADMIN.'/CmsPages/update/'.base64_encode($data['CmsPage']['id']) ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
				</td>
			<!--<td class="actions">
					<a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
					<a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>    
					<a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
				</td> -->
			</tr>
	<?php $count++;
		}
	} ?>
	</tbody>
	
	</table>
