<div class="content-page">
                <!-- Start content -->
                <div class="content">
                    
                    <div class="wraper container-fluid">
                        <!-- Page-Title -->
						<div class="row">
							<div class="col-sm-12">
								<h4 class="page-title">Vehicles Category</h4>
								<ol class="breadcrumb">
									<li>
										<a href="<?= SITE_URL_ADMIN.'Users/dashboard' ?>">Home</a>
									</li>
									<li class="active">
										Update Category
									</li>
								</ol>
							</div>
						</div>


                        <div class="row">
                            <div class="col-lg-12">
                             
							 <div class="card-box">
									<h4 class="m-t-0 header-title"><b>Manage Category Content</b></h4>
									
									<div class="row">
										<div class="col-lg-9">
											
			                                <div class="p-20">
												<form class="form-horizontal" method="POST" id = 'vehcl_category'>
												<?php echo $this->Form->hidden('VehicleCategory.id'); ?>
                                                    <!--<div class="form-group">
                                                        <label class="col-lg-4 control-label">With all options</label>
                                                        <div class="col-lg-8">
                                                            <div id="reportrange" class="pull-right form-control">
                                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                                <span>September 7, 2016 - October 6, 2016</span>
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                    <div class="form-group">
                                                        <label class="col-lg-4 control-label">Name</label>
                                                        <div class="col-lg-8">
															<?php echo $this->Form->input('VehicleCategory.name', array('div'=>FALSE, 'label'=>FALSE, 'class'=>'validate[required] form-control')); ?>
                                                        </div>
                                                    </div>
                                                   
													
													<div class="form-group">
                                                        <label class="col-lg-4 control-label"></label>
                                                        <div class="col-lg-8">
															<?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-default waves-effect waves-light m-l-5')); ?>
                                                            <?php echo $this->Form->button('Submit', array('type'=>'submit', 'class'=>'btn btn-primary waves-effect waves-light')); ?>
                                                        </div>
                                                    </div>
													
                                                </form>
												
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


            <!-- Right Chatbar -->
            <?php //echo $this->element('admin/chatbar'); ?>
            <!-- /Chatbar -->

<script type = 'text/javascript'>

	$(document).ready(function () {
			$('#vehcl_category').validationEngine();
		
	});
</script>		