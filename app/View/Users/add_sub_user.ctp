<section class="mid-section">
    <div class="container">
        <h1 class="main-title"><?php echo __('Add'); ?> <span><?php echo __('Sub User'); ?></span></h1>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="p-20">
                                <form method="POST" class="form-horizontal" id="cmspage_update">
                                    
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="UserPrefixName"><?= __("Parent User") ?></label>
                                        <div class="col-lg-8">
                                            <p class="form-control-static mb-0"><?php echo $parentUserData['User']['fname'] . ' ' . $parentUserData['User']['lname']; ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="UserPrefixName"><?= __("Username") ?></label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->input('User.username', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'validate[required] form-control')); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-4 control-label"> <label for="exampleInputEmail1"><?= __("Password*") ?></label></div>
                                        <div class="col-md-8">
                                            <?php echo $this->Form->input('User.password', array('div' => false, 'label' => false, 'placeholder' => __("Insert password"), 'class' => 'validate[required,minSize[8]] form-control', 'style' => 'margin:0', 'id' => 'pass')); ?>
                                            <p><?= __("At least 8 characters, uppercase/lowercase letters and numbers") ?></p></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4 control-label"> <label for="exampleInputEmail1"><?= __("Re-enter password*") ?></label></div>
                                        <div class="col-md-8">
                                            <?php echo $this->Form->input('User.repassword', array('div' => false, 'type' => 'password', 'label' => false, 'placeholder' => __("Confirm your password"), 'class' => 'validate[required,equals[pass]] form-control', 'style' => 'margin:0')); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="UserPrefixName"><?= __("Form of address*") ?></label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->input('User.prefix_name', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => 'Please Select', 'options' => array('Mrs' => 'Mrs', 'Mr' => 'Mr'))); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="userName">Fist Name *</label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->input('User.fname', array('div' => false, 'label' => false, "placeholder" => "Enter first name", "class" => "validate[required] form-control", "required")); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="emailAddress">Last Name *</label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->input('User.lname', array('div' => false, 'label' => false, "placeholder" => "Enter last name", "class" => "validate[required] form-control", "required")); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="pass1">Email *</label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->input('User.email', array('div' => false, 'label' => false, "placeholder" => "Enter email address", "class" => "validate[required] form-control", "required")); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="passWord2">Mobile Number</label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->input('User.mobile', array('div' => false, 'label' => false, "placeholder" => "Enter mobile number", "class" => "form-control")); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="passWord2">Contact Number</label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->input('User.phone', array('div' => false, 'label' => false, "placeholder" => "Enter phone number", "class" => "form-control")); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="UserLanguage"><?= __("Preferred language") ?></label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->input('User.language', array('div' => false, 'label' => false, 'class' => 'form-control', 'empty' => 'Please Select', 'type' => 'select', 'options' => array('German' => 'German', 'French' => 'French', 'Italian' => 'Italian', 'English' => 'English'))); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="passWord2">About Yourself</label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->input('User.biodata', array('div' => false, 'label' => false, "placeholder" => "Write something about you..", "class" => "form-control")); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="form-validation-field-0"><?= __("How did you find out about us?*") ?></label>
                                        <div class="col-lg-8">
                                            <select class="validate[required] form-control" style="margin:0" name="data[User][site_reference]">
                                                <option value="FLEET">About Fleet</option>
                                                <option value="AD">Advertisement</option>
                                                <option value="EVENT">Event</option>
                                                <option value="GARAGE">Le Garage</option>
                                                <option value="OTHER">Other</option>
                                                <option value="RECOMMENDATION">Personal recommendation</option>
                                                <option value="SEARCHENGINE">Search engine</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="UserStatus"><?= __("Status") ?></label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->input('User.status', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => __('Please Select'), 'type' => 'select', 'options' => array(0 => 'Inactive', 1 => 'Active'))); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label"></label>
                                        <div class="col-lg-8">
                                            <?php echo $this->Form->button('Reset', array('type' => 'reset', 'class' => 'btn btn-default waves-effect waves-light m-l-5')); ?>
                                            <?php echo $this->Form->button('Submit', array('type' => 'submit', 'class' => 'btn btn-primary waves-effect waves-light')); ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</section> <!-- content -->

<script type = 'text/javascript'>
    $(document).ready(function () {
        $('#cmspage_update').validationEngine();
    });
</script>	