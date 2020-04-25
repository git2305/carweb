<!-- ================MID section=====================-->
<section class="mid-section">
    <div class="container">
        <div class="row">

            <h1 class="main-title"><?php echo __("Edit"); ?><span><?php echo __('Sub User');?></span></h1>
            <div class="registra-form">
                <form method="POST" id="reg_form">
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="UserPrefixName"><?= __("Username") ?></label>
                        <div class="col-lg-8">
                            <p class="form-control-static mb-0"><?php echo h($this->request->data['User']['username']); ?></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="UserPrefixName"><?= __("Form of address*") ?></label>
                        <div class="col-lg-8">
                            <?php echo $this->Form->input('User.prefix_name', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => __('Please select'), 'options' => array('Mrs' => 'Mrs', 'Mr' => 'Mr'))); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="fname"><?php echo __('First Name');?> *</label>
                        <div class="col-lg-8">
                            <?php echo $this->Form->input('User.fname', array('div' => false, 'label' => false, "placeholder" => __("Enter first name"), "class" => "validate[required] form-control", "required")); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="lname"><?php echo __('Last Name');?> *</label>
                        <div class="col-lg-8">
                            <?php echo $this->Form->input('User.lname', array('div' => false, 'label' => false, "placeholder" => __("Enter last name"), "class" => "validate[required] form-control", "required")); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="email"><?php echo __('Email'); ?> *</label>
                        <div class="col-lg-8">
                            <?php echo $this->Form->input('User.email', array('div' => false, 'label' => false, "placeholder" => __("Enter email address"), "class" => "validate[required] form-control", "required")); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="passWord2"><?php echo __('Mobile Number'); ?></label>
                        <div class="col-lg-8">
                            <?php echo $this->Form->input('User.mobile', array('div' => false, 'label' => false, "placeholder" => __('Enter mobile number'), "class" => "form-control")); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="passWord2"><?php echo __('Contact Number');?></label>
                        <div class="col-lg-8">
                            <?php echo $this->Form->input('User.phone', array('div' => false, 'label' => false, "placeholder" => __('Enter phone number'), "class" => "form-control")); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="UserLanguage"><?= __("Preferred language") ?></label>
                        <div class="col-lg-8">
                            <?php echo $this->Form->input('User.language', array('div' => false, 'label' => false, 'class' => 'form-control', 'empty' => __('Please select'), 'type' => 'select', 'options' => array('German' => 'German', 'French' => 'French', 'Italian' => 'Italian', 'English' => 'English'))); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="passWord2"><?php echo __('About Yourself');?></label>
                        <div class="col-lg-8">
                            <?php echo $this->Form->input('User.biodata', array('div' => false, 'label' => false, "placeholder" => __('Write something about you..'), "class" => "form-control")); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="form-validation-field-0"><?= __("How did you find out about us?*") ?></label>
                        <div class="col-lg-8">
                            <select class="validate[required] form-control" style="margin:0" name="data[User][site_reference]">
                                <option value="FLEET"><?php echo __('About Fleet');?></option>
                                <option value="AD"><?php echo __('Advertisement');?></option>
                                <option value="EVENT"><?php echo __('Event');?></option>
                                <option value="GARAGE"><?php echo __('Le Garage');?></option>
                                <option value="OTHER"><?php echo __('Other');?></option>
                                <option value="RECOMMENDATION"><?php echo __('Personal recommendation');?></option>
                                <option value="SEARCHENGINE"><?php echo __('Search engine');?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label" for="UserStatus"><?= __("Status") ?></label>
                        <div class="col-lg-8">
                            <?php echo $this->Form->input('User.status', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => __('Please select'), 'type' => 'select', 'options' => array(0 => __('Inactive'), 1 => __('Active')))); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-9 col-md-offset-3">
                            <button class="btn btn-default" type="submit"><?= __("Save") ?></button>
                            <?php echo $this->Html->link(__("Cancel"), array('controller' => 'Users', 'action' => 'index'), array('tabindex' => -1, 'class' => 'btn btn-default')); ?>
                        </div>
                    </div>

                </form>
            </div>


        </div>
    </div>
</section>