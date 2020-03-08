<section class="mid-section">
    <div class="container">
        <h1><span>Edit-Profile </span></h1>
        <div class="row">
            <div class="col-lg-3">
                <div class="profile-img">
                    <img src="img/funny-profile-pictures.jpg">
                </div>
                <form method="POST" id = 'reg_form' enctype = 'multipart/form-data'>
                    <label class="btn btn-send btn-file">
                        <?php echo $this->Form->input('User.image', array('div' => false, 'label' => false, "class" => "validate[required]", 'type' => 'file')); ?>
                    </label>


            </div>
            <div class="col-lg-9">



                <div class="registra-form">


                    <?php
                    echo $this->Form->hidden('User.id');
                    echo $this->Form->hidden('Company.user_id', array('value' => AuthComponent::User('id')));
                    ?>

                    <div class="main-h3"><h3><?php echo __("Company Data") ?></h3></div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1">Company</label></div>
                        <div class="col-lg-9"><?php echo $this->Form->input('Company.name', array('div' => false, 'label' => false, "placeholder" => "Company", "class" => "validate[required] form-control")); ?></div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1">Addition</label></div>
                        <div class="col-lg-9"><?php echo $this->Form->input('Company.addition', array('div' => false, 'label' => false, "placeholder" => "Addition", "class" => "validate[required] form-control")); ?></div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1">Street/no.</label></div>
                        <div class="col-lg-9"><?php echo $this->Form->input('Company.street', array('div' => false, 'label' => false, "placeholder" => "Street", "class" => "validate[required] form-control")); ?></div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1">PO box</label></div>
                        <div class="col-lg-9"><?php echo $this->Form->input('Company.pob', array('div' => false, 'label' => false, "placeholder" => "PO Box", "class" => "validate[required] form-control")); ?></div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1">Postcode/town *</label></div>
                        <div class="col-lg-9">
                            <?php echo $this->Form->input('Company.postcode', array('div' => false, 'label' => false, "placeholder" => "Postcode", "class" => "validate[required] form-control")); ?> 
                            <?php echo $this->Form->input('Company.town', array('div' => false, 'label' => false, "placeholder" => "Town", "class" => "validate[required] form-control")); ?> </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1">Country*</label></div>
                        <div class="col-lg-9"><select class="form-control">
                                <option>Switzerland</option>
                                <option value="ch.carauktion.entity.customer.Country:20">Austria</option>
                                <option value="ch.carauktion.entity.customer.Country:1">Belgium</option>
                                <option value="ch.carauktion.entity.customer.Country:2">Bulgaria</option>
                                <option value="ch.carauktion.entity.customer.Country:11">Croatia</option>
                                <option value="ch.carauktion.entity.customer.Country:3">Czech Republic</option>
                                <option value="ch.carauktion.entity.customer.Country:4">Denmark</option>
                                <option value="ch.carauktion.entity.customer.Country:6">Estonia</option>
                                <option value="ch.carauktion.entity.customer.Country:26">Finland</option>
                            </select></div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1">Car dealership</label></div>
                        <div class="col-lg-9"> <?php echo $this->Form->input('Company.car_dealership', array('div' => false, 'label' => false, "placeholder" => "Car Dealership", "class" => "validate[required] form-control")); ?> </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1">Preferred language</label></div>
                        <div class="col-lg-9"><select class="form-control">
                                <option>Please select</option>
                                <option>German</option>
                                <option>French</option>
                                <option>Italian</option>
                                <option>English</option>
                            </select></div>
                    </div>
                    <div class="main-h3"><h3><?= __("Personal details") ?></h3></div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Form of address*") ?></label></div>
                        <div class="col-lg-9">
                            <?php echo $this->Form->input('User.prefix_name', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => 'Please Select', 'options' => array('Mrs' => 'Mrs', 'Mr' => 'Mr'))); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("First name*") ?></label></div>
                        <div class="col-lg-9">
                            <?php echo $this->Form->input('User.fname', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("Insert first name"))); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Surname*") ?></label></div>
                        <div class="col-lg-9">
                            <?php echo $this->Form->input('User.lname', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("Insert last name"))); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Email*") ?></label></div>
                        <div class="col-lg-9">
                            <?php echo $this->Form->input('User.email', array('div' => false, 'label' => false, 'class' => 'form-control', 'readonly' => true)); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Telephone *") ?></label></div>
                        <div class="col-lg-2">
                            <div class="input-group ">
                                <div class="input-group-addon">+</div>
                                <?php echo $this->Form->input('User.phone_code', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => '50', 'style' => 'margin:0')); ?>
                            </div> </div>

                        <div class="col-lg-7">
                            <?php echo $this->Form->input('User.phone', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __('Area code without the "0", Number plus extension'))); ?>
                        </div></div>
                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Mobile") ?></label></div>
                        <div class="col-lg-2">
                            <div class="input-group ">
                                <div class="input-group-addon">+</div>
                                <?php echo $this->Form->input('User.mobile_code', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => '41', 'style' => 'margin:0')); ?>
                            </div> </div>

                        <div class="col-lg-7">
                            <?php echo $this->Form->input('User.mobile', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __('Area code without the "0", Number plus extension'))); ?>
                        </div></div>

                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Preferred language") ?></label></div>
                        <div class="col-lg-9">
                            <?php echo $this->Form->input('User.language', array('div' => false, 'label' => false, 'class' => 'form-control', 'empty' => 'Please Select', 'type' => 'select', 'options' => array('German' => 'German', 'French' => 'French', 'Italian' => 'Italian', 'English' => 'English'))); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Username*") ?></label></div>
                        <div class="col-lg-9">
                            <?php echo $this->Form->input('User.username', array('div' => false, 'label' => false, 'placeholder' => __("Insert username"), 'class' => 'form-control', 'style' => 'margin:0', 'readonly' => true
                            ));
                            ?>
                            <p><?= __("(Min. 6, max. 8 characters)") ?></p>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("How did you find out about us?*") ?></label></div>
                        <div class="col-lg-9"><select class="validate[required] form-control" style="margin:0" name="data[User][site_reference]">
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



                    <div class="col-lg-9 col-xs-offset-3"><a href="javascript:void(0);" class="btn btn-default" onclick="$('#reg_form').subm
          it();"><?= __("Registration") ?></a></div>

                    </form>
                </div>

            </div>


        </div>


    </div>
</section>

<script type = 'text/javascript'>
	
        $(document).ready(functi on (){
		
                $("#reg_form").validationEngine();
    
        });

</script>