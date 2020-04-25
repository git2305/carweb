<div class="content-page">
    <!-- Start content -->
    <div class="content">

        <div class="wraper container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title"><?php echo __('Edit User'); ?></h4>
                    <ol class="breadcrumb">
                        <li><a href="<?= SITE_URL_ADMIN . 'Users/dashboard' ?>">Home</a></li>
                        <li class="active"><?php echo __('Edit User'); ?></li>
                    </ol>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo __('Edit User'); ?></b></h4>

                        <div class="row">
                            <div class="col-lg-9">
                                <div class="p-20">
                                    <form method="POST" class="form-horizontal" id="cmspage_update">
                                        <?php echo $this->Form->hidden('User.id'); ?>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label" for="UserPrefixName"><?= __("Username") ?></label>
                                            <div class="col-lg-8">
                                                <p class="form-control-static mb-0"><?php echo h($this->request->data['User']['username']); ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label" for="UserPrefixName"><?= __("Form of address*") ?></label>
                                            <div class="col-lg-8">
                                                <?php echo $this->Form->input('User.prefix_name', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => 'Please select', 'options' => array('Mrs' => 'Mrs', 'Mr' => 'Mr'))); ?>
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
                                                <?php echo $this->Form->input('User.language', array('div' => false, 'label' => false, 'class' => 'form-control', 'empty' => 'Please select', 'type' => 'select', 'options' => array('German' => 'German', 'French' => 'French', 'Italian' => 'Italian', 'English' => 'English'))); ?>
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
                                                <?php echo $this->Form->input('User.status', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => __('Please select'), 'type' => 'select', 'options' => array(0 => 'Inactive', 1 => 'Active'))); ?>
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

        </div> <!-- container -->

    </div> <!-- content -->

    <!-- Start Footer -->
    <?php echo $this->element('admin/footer'); ?>
    <!-- End Footer -->

</div>

<script type = 'text/javascript'>
    $(document).ready(function () {
        $('#cmspage_update').validationEngine();
    });
</script>	