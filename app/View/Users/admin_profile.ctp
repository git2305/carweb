<div class="content-page">
                <!-- Start content -->
                <div class="content">
                    
                    <div class="wraper container-fluid">
                        <!-- Page-Title -->
						<div class="row">
							<div class="col-sm-12">
								<h4 class="page-title">Profile</h4>
								<ol class="breadcrumb">
									<li>
										<a href="<?= SITE_URL_ADMIN.'Users/dashboard' ?>">Home</a>
									</li>
									<li class="active">
										Profile
									</li>
								</ol>
							</div>
						</div>


                        <div class="row">
                            <div class="col-sm-5 col-md-4 col-lg-3">
                                <div class="profile-detail card-box">
                                    <div>
                                        <img src="<?php echo BASE_URL; ?>/img/admin/users/avatar-2.jpg" class="img-circle" alt="profile-image">

                                        <!--<ul class="list-inline status-list m-t-20">
                                            <li>
                                                <h3 class="text-primary m-b-5">456</h3>
                                                <p class="text-muted">Followings</p>
                                            </li>

                                            <li>
                                                <h3 class="text-success m-b-5">5864</h3>
                                                <p class="text-muted">Followers</p>
                                            </li>
                                        </ul>

                                        <button type="button" class="btn btn-pink btn-custom btn-rounded waves-effect waves-light">Follow</button>-->

                                        <hr>
                                        <h4 class="text-uppercase font-600">About Me</h4>
                                        <p class="text-muted font-13 m-b-30">
                                            <?php
											if(isset($this->data['User']['biodata']) & !empty($this->data['User']['biodata'])){
												echo $this->data['User']['biodata'];
											}else{
												echo "Not available";
											}
											?>
                                        </p>

                                        <div class="text-left">
                                            <p class="text-muted font-13"><strong>Full Name :</strong>
											<span class="m-l-15">
											<?php
											if(isset($this->data['User']['fname']) && !empty($this->data['User']['fname'])){
												echo $this->data['User']['fname'].' '.@$this->data['User']['lname'];
											}else{
												echo "Not available";
											}
											?>
											</span>
											</p>

                                            <p class="text-muted font-13"><strong>Mobile :</strong>
											<span class="m-l-15">
											<?php
											if(isset($this->data['User']['mobile']) && !empty($this->data['User']['mobile'])){
												echo $this->data['User']['mobile'];
											}else{
												echo "Not available";
											}
											?>
											</span>
											</p>

                                            <p class="text-muted font-13"><strong>Email :</strong>
											<span class="m-l-15">
											<?php
											if(isset($this->data['User']['email']) && !empty($this->data['User']['email'])){
												echo $this->data['User']['email'];
											}else{
												echo "Not available";
											}
											?>
											</span>
											</p>

                                        </div>


                                        <!--<div class="button-list m-t-20">
                                            <button type="button" class="btn btn-facebook waves-effect waves-light">
                                               <i class="fa fa-facebook"></i>
                                            </button>

                                            <button type="button" class="btn btn-twitter waves-effect waves-light">
                                               <i class="fa fa-twitter"></i>
                                            </button>

                                            <button type="button" class="btn btn-linkedin waves-effect waves-light">
                                               <i class="fa fa-linkedin"></i>
                                            </button>

                                            <button type="button" class="btn btn-dribbble waves-effect waves-light">
                                               <i class="fa fa-dribbble"></i>
                                            </button>
                                        </div>-->
                                    </div>

                                </div>

                                <!--<div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Friends <span class="text-muted">(154)</span></b></h4>

                                    <div class="friend-list">
                                        <a href="#">
                                            <img src="<?php echo BASE_URL; ?>/img/admin/users/avatar-1.jpg" class="img-circle thumb-md" alt="friend">
                                        </a>

                                        <a href="#">
                                            <img src="<?php echo BASE_URL; ?>/img/admin/users/avatar-2.jpg" class="img-circle thumb-md" alt="friend">
                                        </a>

                                        <a href="#">
                                            <img src="<?php echo BASE_URL; ?>/img/admin/users/avatar-3.jpg" class="img-circle thumb-md" alt="friend">
                                        </a>

                                        <a href="#">
                                            <img src="<?php echo BASE_URL; ?>/img/admin/users/avatar-4.jpg" class="img-circle thumb-md" alt="friend">
                                        </a>

                                        <a href="#">
                                            <img src="<?php echo BASE_URL; ?>/img/admin/users/avatar-5.jpg" class="img-circle thumb-md" alt="friend">
                                        </a>

                                        <a href="#">
                                            <img src="<?php echo BASE_URL; ?>/img/admin/users/avatar-6.jpg" class="img-circle thumb-md" alt="friend">
                                        </a>

                                        <a href="#">
                                            <img src="<?php echo BASE_URL; ?>/img/admin/users/avatar-7.jpg" class="img-circle thumb-md" alt="friend">
                                        </a>

                                        <a href="#" class="text-center">
                                            <span class="extra-number">+89</span>
                                        </a>
                                    </div>
                                </div>-->
                            </div>


                            <div class="col-lg-9">
                                
                               <div class="card-box">
									<h4 class="m-t-0 header-title"><b>Manage Profile</b></h4>
									<p class="text-muted font-13 m-b-30">
	                                    You can update your profile here.
	                                </p>
		                                        
									<form method="POST">
										<?php echo $this->Form->hidden('User.id'); ?>
										<div class="form-group">
											<label for="userName">Fist Name *</label>
											<?php echo $this->Form->input('User.fname', array('div'=>false, 'label'=>false, "placeholder"=>"Enter first name", "class"=>"form-control", "required")); ?>
											<!--<input name="nick" parsley-trigger="change" required="" placeholder="Enter user name" class="form-control" id="userName" data-parsley-id="4" type="text">-->
										</div>
										<div class="form-group">
											<label for="emailAddress">Last Name *</label>
											<?php echo $this->Form->input('User.lname', array('div'=>false, 'label'=>false, "placeholder"=>"Enter last name", "class"=>"form-control", "required")); ?>
										</div>
										<div class="form-group">
											<label for="pass1">Email *</label>
											<?php echo $this->Form->input('User.email', array('div'=>false, 'label'=>false, "placeholder"=>"Enter email address", "class"=>"form-control", "required")); ?>
										</div>
										<div class="form-group">
											<label for="passWord2">Contact Number</label>
											<?php echo $this->Form->input('User.mobile', array('div'=>false, 'label'=>false, "placeholder"=>"Enter phone number", "class"=>"form-control")); ?>
										</div>
										<div class="form-group">
											<label for="passWord2">About Yourself</label>
											<?php echo $this->Form->input('User.biodata', array('div'=>false, 'label'=>false, "placeholder"=>"Write something about you..", "class"=>"form-control")); ?>
										</div>

										<div class="form-group text-right m-b-0">
											<?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-default waves-effect waves-light m-l-5')); ?>
											<?php echo $this->Form->button('Submit', array('type'=>'submit', 'class'=>'btn btn-primary waves-effect waves-light')); ?>
										</div>
										
									</form>
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