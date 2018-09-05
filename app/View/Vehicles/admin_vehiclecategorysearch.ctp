<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable" data-page-size="7">
	<thead>
		<tr>
			<th data-toggle="true" class="footable-visible footable-first-column footable-sortable">Sr.No.<!--<span class="footable-sort-indicator"></span>--></th>
			<th class="footable-visible footable-sortable">Name<!--<span class="footable-sort-indicator"></span>--></th>
			<th data-hide="phone" class="footable-visible footable-sortable">Created<!--<span class="footable-sort-indicator"></span>--></th>
			<!--<th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable">Action<!--<span class="footable-sort-indicator"></span></th>-->
		</tr>
	</thead>
	
	<tbody>
	<?php if(isset($vcategory) && !empty($vcategory)){
		$count = 1;
		foreach($vcategory as $data){ ?>
			<tr style="display: table-row;" class="<?php if($count % 2 == 0){ echo 'footable-even'; }else{ echo 'footable-even'; } ?>">
				<td class="footable-visible footable-first-column">
					<?= $count.'.' ?>
				</td>
				<td class="footable-visible">
					<?php if(isset($data['VehicleCategory']['name']) && !empty($data['VehicleCategory']['name'])){
						echo $data['VehicleCategory']['name'];
					}else{
						echo "N/A";
					} ?>
				</td>
				
				<td class="footable-visible">
					<?php if(isset($data['VehicleCategory']['created']) && !empty($data['VehicleCategory']['created'])){
						echo date('d-M-Y', strtotime($data['VehicleCategory']['created']));
					}else{
						echo "N/A";
					} ?>
				</td>
				
			<!--	<td class="footable-visible footable-last-column">
					<a href="<?= SITE_URL_ADMIN.'/Vehicles/updateCategory/'.base64_encode($data['VehicleCategory']['id']) ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
				</td>
			<td class="actions">
					<a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
					<a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>    
					<a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
				</td> -->
			</tr>
	<?php $count++;
		}
	}else{ ?>
	<tr>
		<td colspan="3">
			No Recoard Found
		</td>
	</tr>
	<?php } ?>
	</tbody>
	<!--<tfoot>
		<tr>
			<td colspan="5" class="footable-visible">
				<div class="text-right">
					<ul class="pagination pagination-split m-t-30 m-b-0">
					<li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li>
					<li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li>
					<li class="footable-page active"><a data-page="0" href="#">1</a></li>
					<li class="footable-page"><a data-page="1" href="#">2</a></li>
					<li class="footable-page"><a data-page="2" href="#">3</a></li>
					<li class="footable-page"><a data-page="3" href="#">4</a></li>
					<li class="footable-page"><a data-page="4" href="#">5</a></li>
					<li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li>
					<li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li>
					</ul>
				</div>
			</td>
		</tr>
	</tfoot>-->
</table>
