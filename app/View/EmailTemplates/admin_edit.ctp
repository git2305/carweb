<script type="text/javascript" src="<?php echo SITE_URL; ?>/plugins/ckeditor/ckeditor.js"></script>
<div class="content-page">
    <!-- Start content -->
    <div class="content">

        <div class="wraper container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title"><?php echo __('Edit Email Templates'); ?></h4>
                    <ol class="breadcrumb">
                        <li><a href="<?= SITE_URL_ADMIN . 'Users/dashboard' ?>"><?php echo __('Home'); ?></a></li>
                        <li class="active"><?php echo __('Edit Email Templates'); ?></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo __('Edit Email Templates'); ?></b></h4>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-20">
                                    <form method="POST" class="form-horizontal" id="emailtemplate_update">
                                        <?php echo $this->Form->hidden('EmailTemplate.id'); ?>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="UserPrefixName"><?= __("From Name") ?></label>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('EmailTemplate.from_name', array('div' => false, 'label' => false, "placeholder" => "Enter from name", "class" => "validate[required] form-control", "required")); ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="UserPrefixName"><?= __("From Email") ?></label>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('EmailTemplate.from_email', array('div' => false, 'label' => false, "placeholder" => "Enter from email", "class" => "validate[required] form-control", "required")); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="UserPrefixName"><?= __("To") ?></label>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('EmailTemplate.to', array('div' => false, 'label' => false, "placeholder" => "Enter to", "class" => "validate[required] form-control", "required")); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="UserPrefixName"><?= __("CC") ?></label>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('EmailTemplate.cc', array('div' => false, 'label' => false, "placeholder" => "Enter CC", "class" => "form-control")); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="UserPrefixName"><?= __("BCC") ?></label>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('EmailTemplate.bcc', array('div' => false, 'label' => false, "placeholder" => "Enter BCC", "class" => "form-control")); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="UserPrefixName"><?= __("Subject") ?></label>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('EmailTemplate.subject', array('div' => false, 'label' => false, "placeholder" => "Enter subject", "class" => "validate[required] form-control", "required")); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="UserPrefixName"><?= __("Email Content") ?></label>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('EmailTemplate.description', array('type'=> 'textarea','div' => false, 'label' => false, "class" => "validate[required] form-control ckeditor", "required")); ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="UserStatus"><?= __("Status") ?></label>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('EmailTemplate.status', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => __('Please select'), 'type' => 'select', 'options' => array(0 => 'Inactive', 1 => 'Active'))); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"></label>
                                            <div class="col-lg-9">
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
        $('#emailtemplate_update').validationEngine();
    });
</script>	

<?php /*
<div class="emailTemplates form">
    <?php echo $this->Form->create('EmailTemplate'); ?>
    <fieldset>
        <legend><?php echo __('Admin Edit Email Template'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('from_name');
        echo $this->Form->input('from_email');
        echo $this->Form->input('to');
        echo $this->Form->input('cc');
        echo $this->Form->input('bcc');
        echo $this->Form->input('subject');
        echo $this->Form->input('alias');
        echo $this->Form->input('description');
        echo $this->Form->input('status');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('EmailTemplate.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('EmailTemplate.id')))); ?></li>
        <li><?php echo $this->Html->link(__('List Email Templates'), array('action' => 'index')); ?></li>
    </ul>
</div>
*/ ?>