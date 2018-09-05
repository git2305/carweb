<section class="mid-section">
    <div class="container">
        <h1 class="main-title"><?php echo __('Email');?> <span><?php echo __('Preference'); ?></span></h1>
        <div class="row">
            <div class="col-lg-12">
                <div class="registra-form">
                    <form method="POST" id="changepass_form">
                        <div class="form-group">
                            <div class="form-group a-center">
                                <?php echo $this->Form->input('User.email_preference', array( 'value'=> $this->data['User']['email_preference']  ,'type'=> 'checkbox','div' => false, 'label' => false, "placeholder" => "Current Password", "class" => "")); ?>
                                <label for="UserEmailPreference"><?php echo __('Send Email Notifications ?'); ?></label>
                            </div>
                        </div>
                        <div class="form-group a-center">
                            <a href="javascript:void(0);" class="btn btn-primary" onclick="$('#changepass_form').submit();"><?php echo __("Save"); ?></a>
                            <?php echo $this->Html->link(__("Cancel"), array('controller' => 'Users', 'action' => 'index'), array('tabindex' => -1, 'class' => 'btn btn-default')); ?>
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