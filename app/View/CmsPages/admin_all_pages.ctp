<div class="content-page">
				<!-- Start content -->
				<div class="content">
					<div class="container">

						<!-- Page-Title -->
						<div class="row">
							<div class="col-sm-12">
								<h4 class="page-title">Pages Listing</h4>
								<ol class="breadcrumb">
									<li>
										<a href="<?= SITE_URL_ADMIN.'/Users/dashboard' ?>">Home</a>
									</li>
									<li class="active">
										CmsPages
									</li>
								</ol>
							</div>
						</div>
						
						
						<div class="row">
							<div class="col-sm-12">
								<div class="card-box">
									<h4 class="m-t-0 header-title"><b>Manage Pages Content</b></h4>
									<!--<p class="text-muted m-b-30 font-13">
										include filtering in your FooTable.
									</p>-->
									
										<div class="form-inline m-b-20">
											<div class="row">
												<div class="col-sm-12 text-xs-center text-right">
													<div class="form-group">
														<label class="control-label m-r-5">Change Language</label>
														<select id="demo-foo-filter-status" class="form-control input-sm changeLanguage">
															<option value="en" selected>English</option>
															<option value="de">German</option>
															<option value="fr">France</option>
															<option value="it">Italy</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										
										<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable dataByLang" data-page-size="7">
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
		
