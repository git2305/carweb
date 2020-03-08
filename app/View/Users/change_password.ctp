<section class="mid-section">
    <div class="container">
        <h1 class="main-title"><?php echo __('Change');?> <span><?php echo __('Password'); ?></span></h1>
        <div class="row">
            <div class="col-lg-12">
                <div class="registra-form login-form">
                    <form method="POST" id="changepass_form">
                        <div class="form-group row">
                            <div class="col-md-3 col-sm-3"> <label for="UserOldPassword"><?php echo __('Current Password'); ?></label></div>
                            <div class="col-md-9 col-sm-9"><?php echo $this->Form->input('User.current_password', array('type'=> 'password', 'div' => false, 'label' => false, "placeholder" => __('Current Password'), "class" => "validate[required] form-control")); ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 col-sm-3"> <label for="UserPassword"><?php echo __('New Password'); ?></label></div>
                            <div class="col-md-9 col-sm-9"><?php echo $this->Form->input('User.password', array( 'type'=> 'password' ,'div' => false, 'label' => false, "placeholder" => __('New Password'), "class" => "validate[required] form-control")); ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 col-sm-3"> <label for="UserConfirmPassword"><?php echo __('Confirm Password'); ?></label></div>
                            <div class="col-md-9 col-sm-9"><?php echo $this->Form->input('User.confirm_password', array('type'=> 'password', 'div' => false, 'label' => false, "placeholder" => __('Confirm Password'), "class" => "validate[required] form-control")); ?></div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-9 col-sm-offset-3">
                                <a href="javascript:void(0);" class="btn btn-primary" onclick="$('#changepass_form').submit();"><?= __("Change Password") ?></a>
                                <a href="javascript:void(0);" class="btn btn-default"><?= __("Cancel") ?></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script type = 'text/javascript'>
    $(document).ready(function () {
        $("#changepass_form").validationEngine();
    });
</script>