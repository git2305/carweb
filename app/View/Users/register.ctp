<!-- ================MID section=====================-->
<section class="mid-section">
    <div class="container">
        
            <h1 class="main-title"><?php echo __("Registration"); ?></h1>
            <div class="registra-form">
                <form method="POST" id="reg_form">
                    <div class="main-h3"><h3><?= __("Company Data") ?></h3></div>
                    <div class="form-group row">
                        <div class="col-md-3 label-radio-wrap">
                            <label class="control-label" for="CompanyName"><?= __("Commercial Register") ?></label>
                        </div>
                        <div class="col-md-9">
                            <?php
                            echo $this->Form->input('Company.commercial_register', array('type' => 'radio', 'options' => array(1 => __('Yes'), 0 => __('No')), 'div' => false, 'label' => true, 'class' => 'comercial_vehicle', 'legend' => false, 'default' => 0,
                                'before' => '<div class="radio-wrap">',
                                'after' => '</div>',
                                'separator' => '</div><div class="radio-wrap">')
                            );
                            ?>
                        </div>
                    </div>

                    <div class="form-group row vat-no-container" style="display: none;">
                        <div class="col-md-3"> <label for="CompanyName"><?= __("VAT-Nr.") ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('Company.vat_no', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("VAT-Nr."))); ?>
                        </div>
                    </div>
                    <div class="form-group row vat-no-container" style="display: none;">
                        <div class="col-md-3"> <label for="CompanyName"><?= __("UID Nr.") ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('Company.uid_no', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("UID Nr."))); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3"> <label for="CompanyName"><?php echo __("Company"); ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('Company.name', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Company"))); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3"> <label for="CompanyStreet"><?php echo __("Street/no."); ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('Company.street', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Address line 1"))); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?php echo __("PO box"); ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('Company.pob', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Address line 2"))); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?php echo __("Postcode/town *"); ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('Company.postcode', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control col-form-2', 'placeholder' => __("Postcode"))); ?>
                            <?php echo $this->Form->input('Company.town', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control col-form-10', 'placeholder' => __("Town"))); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?php echo __("Country*"); ?></label></div>
                        <div class="col-md-9">
                            <select class="validate[required] form-control" name="data[Company][country]">
                                <option>Switzerland</option>
                                <option value="ch.carauktion.entity.customer.Country:20">Austria</option>
                                <option value="ch.carauktion.entity.customer.Country:1">Belgium</option>
                                <option value="ch.carauktion.entity.customer.Country:2">Bulgaria</option>
                                <option value="ch.carauktion.entity.customer.Country:11">Croatia</option>
                                <option value="ch.carauktion.entity.customer.Country:3">Czech Republic</option>
                                <option value="ch.carauktion.entity.customer.Country:4">Denmark</option>
                                <option value="ch.carauktion.entity.customer.Country:6">Estonia</option>
                                <option value="ch.carauktion.entity.customer.Country:26">Finland</option>
                                <option value="ch.carauktion.entity.customer.Country:10">France</option>
                                <option value="ch.carauktion.entity.customer.Country:5">Germany</option>
                                <option value="ch.carauktion.entity.customer.Country:29">Great Britain</option>
                                <option value="ch.carauktion.entity.customer.Country:8">Greece</option>
                                <option value="ch.carauktion.entity.customer.Country:17">Hungary</option>
                                <option value="ch.carauktion.entity.customer.Country:7">Ireland</option>
                                <option value="ch.carauktion.entity.customer.Country:12">Italy</option>
                                <option value="ch.carauktion.entity.customer.Country:14">Latvia</option>
                                <option value="ch.carauktion.entity.customer.Country:30">Liechtenstein</option>
                                <option value="ch.carauktion.entity.customer.Country:15">Lithuania</option>
                                <option value="ch.carauktion.entity.customer.Country:16">Luxembourg</option>
                                <option value="ch.carauktion.entity.customer.Country:31">Monaco</option>
                                <option value="ch.carauktion.entity.customer.Country:19">Netherlands</option>
                                <option value="ch.carauktion.entity.customer.Country:21">Poland</option>
                                <option value="ch.carauktion.entity.customer.Country:22">Portugal</option>
                                <option value="ch.carauktion.entity.customer.Country:23">Romania</option>
                                <option value="ch.carauktion.entity.customer.Country:33">Serbia</option>
                                <option value="ch.carauktion.entity.customer.Country:25">Slovakia</option>
                                <option value="ch.carauktion.entity.customer.Country:24">Slovenia</option>
                                <option value="ch.carauktion.entity.customer.Country:9">Spain</option>
                                <option value="ch.carauktion.entity.customer.Country:27">Sweden</option>
                                <option value="ch.carauktion.entity.customer.Country:28" selected="selected">Switzerland</option>
                                <option value="ch.carauktion.entity.customer.Country:32">Ukraine</option>
                            </select></div>
                    </div>

                    <div class="main-h3"><h3><?= __("Administrator") ?></h3></div>

                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("First name*") ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('User.fname', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("Insert first name"))); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Surname*") ?></label></div>
                        <div class="col-lg-9">
                            <?php echo $this->Form->input('User.lname', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("Insert last name"))); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Email*") ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('User.email', array('div' => false, 'label' => false, 'class' => 'validate[required,custom[email]] form-control', 'placeholder' => __("Insert email address"))); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Telephone *") ?></label></div>
                        <div class="col-md-3 col-sm-5">
                            <div class="input-group ">
                                <div class="input-group-addon">+41</div>
                                <?php echo $this->Form->input('User.phone', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __('Telephone'))); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Mobile") ?></label></div>
                        <div class="col-md-3 col-sm-5">
                            <div class="input-group ">
                                <div class="input-group-addon">+41</div>
                                <?php echo $this->Form->input('User.mobile', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __('Mobile'))); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Preferred language") ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('User.language', array('div' => false, 'label' => false, 'class' => 'form-control', 'empty' => __('Please select'), 'type' => 'select', 'options' => array('German' => 'German', 'French' => 'French', 'Italian' => 'Italian', 'English' => 'English'))); ?>
                        </div>
                    </div>
                    <div class="main-h3"><h3><?= __("Login details") ?><p style="font-weight:normal;  margin-top:10px"><?= __("Please note that your username will be displayed if you place a bid") ?></p></h3></div>

                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Username*") ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('User.username', array('div' => false, 'label' => false, 'placeholder' => __("Insert username"), 'class' => 'validate[required] form-control', 'style' => 'margin:0')); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Password*") ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('User.password', array('div' => false, 'label' => false, 'placeholder' => __("Insert password"), 'class' => 'validate[required,minSize[8]] form-control', 'style' => 'margin:0', 'id' => 'pass')); ?>
                            <p><?= __("At least 8 characters, uppercase/lowercase letters and numbers") ?></p></div>
                    </div>
                    <div class="form-group row" style="margin-bottom:25px;">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Re-enter password*") ?></label></div>
                        <div class="col-md-9">
                            <?php echo $this->Form->input('User.repassword', array('div' => false, 'type' => 'password', 'label' => false, 'placeholder' => __("Confirm your password"), 'class' => 'validate[required,equals[pass]] form-control', 'style' => 'margin:0')); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1">&nbsp;</label></div>
                        <div class="col-md-9">
                            <div class="checkbox-wrap">
                                <input id="terms" type="checkbox" name="data[User][terms]" value="1">
                                <label class="full-width" for="terms"><?= __("I hereby confirm that I have read and understood the General Terms and Conditions and that I agree to them*") ?></label>                                            
                            </div>
                            <div class="checkbox-wrap">
                                <input id="carauktion_ag" type="checkbox" name="data[User][carauktion_ag]" value="1">
                                <label class="full-width" for="carauktion_ag"><?= __("Eintauschfahrzeuge24.ch GmbH is hereby authorised to obtain external information about the company/person to be registered*") ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Security Check") ?></label></div>
                        <div class="col-md-9">
                            <div class="boost">
                                <input type="checkbox" id="robot">
                                <label for="robot"><?= __("I'm not a robot") ?></label> 
                                <img src="<?php echo BASE_URL; ?>/img/front/boost.png" style="float:right">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-9 col-md-offset-3">
                            <a href="javascript:void(0);" class="btn btn-default" onclick="$('#reg_form').submit();"><?= __("Registration") ?></a>
                        </div>
                    </div>

                </form>
            </div>


        
    </div>
</section>

<script type = 'text/javascript'>
    $(document).ready(function () {
        $('#reg_form').validationEngine(
            'attach', {
                promptPosition : 'bottomLeft:0,0',
                scroll: false,
                autoHidePrompt: true,
                autoHideDelay: 2500,
                fadeDuration: 0.3,
                focusFirstField : true,
                maxErrorsPerField: 1
            }
        );
    });
</script><!-- Finished Middle Content -->