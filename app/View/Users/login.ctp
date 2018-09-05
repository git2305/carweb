<!-- ================MID section=====================-->
<section class="mid-section">
                <div class="registra-form login-form">
    <div class="container">
        <div class="row">
            <div class="col-lg-11">
                <h1 class="main-title"><?php echo __("Sign In"); ?></h1>
                <div class="registra-form login-form">
                    <form method="POST" id="signin_form">
                        <div class="form-group">
                            <?php echo $this->Form->input('User.username', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("Username").'/'.__("Email") )); ?>
                        </div>
                        <div class="form-group">                            
                                <?php echo $this->Form->input('User.password', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("Password"))); ?>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <input name="login-submit"  id="login-submit" tabindex="4" class="form-control btn-login" value="<?php echo __("Sign In"); ?>" type="submit">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="text-center">
                                        <?php echo $this->Html->link(__("Forgot Password?"), array('controller' => 'Users', 'action' => 'forgotPassword'), ['class' => 'pwd forgot-password'] ); ?>
                                    </div>
                                </div>
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
        $('#signin_form').validationEngine();

    });

</script>