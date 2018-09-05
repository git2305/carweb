<!-- ================MID section=====================-->
<section class="mid-section">
                <div class="registra-form login-form">
    <div class="container">
        <div class="row">
            <div class="col-lg-11">
                <h1 class="main-title"><?php echo __("Forgot Password"); ?></h1>
                <div class="registra-form login-form">
                    <?php echo $this->Form->create('User', array('url'=> ['action' => 'forgotPassword'], 'id' => 'signin_form')); ?>
                        <div class="form-group">
                            <?php echo $this->Form->input('User.email', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("Enter your email"))); ?>
                        </div>
                    
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <input name="forgot-submit"  id="forgot-submit" class="form-control btn-login" value="<?php echo __("Submit"); ?>" type="submit">
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="text-center">
                                        <?php echo $this->Html->link(__("Login"), array('controller' => 'Users', 'action' => 'login'), ['class' => 'pwd forgot-password'] ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php echo $this->Form->end();?>
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