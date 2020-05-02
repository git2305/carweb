<!-- ================MID section=====================-->
<section class="mid-section">
                <div class="registra-form login-form">
    <div class="container">
        <div class="row">
            <div class="col-lg-11">
                <h1 class="main-title"><?php echo __("Reset Password"); ?></h1>
                <div class="registra-form login-form">
                    <?php echo $this->Form->create('User', array('id' => 'signin_form')); ?>
                        <div class="form-group row">
                            <div class="col-md-3 col-sm-3"> <label for="UserEmail"><?php echo __("Password"); ?><em>*</em></label></div>
                            <div class="col-md-9 col-sm-9">
                                <?php echo $this->Form->input('User.password', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("Password"))); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 col-sm-3"> <label for="UserEmail"><?php echo __("Confirm Password"); ?><em>*</em></label></div>
                            <div class="col-md-9 col-sm-9">
                                <?php echo $this->Form->input('User.repassword', array('type'=> 'password' ,'div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("Confirm Password"))); ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-9 col-xs-offset-3">
                                <a href="javascript:void(0);" class="btn btn-default" onclick="$('#signin_form').submit();"><?php echo __("Submit"); ?></a>
                            </div>
                        </div>
                    <?php echo $this->Form->end();?>
                </div>

            </div>
<!--            <a href="#"><?php //echo __('Login'); ?></a>-->
        </div>
    </div>
</section>

<script type = 'text/javascript'>
    $(document).ready(function () {
        $('#signin_form').validationEngine();

    });

</script>