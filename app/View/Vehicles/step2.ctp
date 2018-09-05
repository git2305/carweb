<section class="mid-section">
    <div class="container">
        <form method="POST" id="step2-form" enctype="multipart/form-data">
            <?php echo $this->Form->hidden('VehicleDoc.vehicle_id', array('value' => @$this->data['Vehicle']['id'])); ?>
            <div class="tab-bar-all-pages">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation"><?php echo $this->Html->link(__('Vehicle Data'), ['controller'=> 'Vehicles', 'action' => 'step1' ]); ?></li>
                    <li role="presentation" class="active"><a href="javascript:void(0);" aria-controls="messages" role="tab" data-toggle="tab"><?= __('Documents & Pictures') ?></a></li>
                    <li role="presentation"><?php echo $this->Html->link(__('Auction'), ['controller'=> 'Vehicles', 'action' => 'step3' ]); ?></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">
                        <h3><?= __('Pictures of vehicles') ?></h3>
                        <p><?= __("You can upload a maximum of 20 vehicle images.Maximum file size 5 MB,minimum image resolution: 370X490 pixels.Only images in landscape orientation can be uploaded.") ?></p>
                        
                        
<!--                        <h3><?php echo __('Vehicle Images'); ?></h3>-->
                        <div class="row">
                            
                            <div class="col-md-12 sketchable">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/1.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/2.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/3.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/4.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/5.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/6.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/7.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/8.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/9.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/10.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/11.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email"><img src="<?php echo $this->webroot;?>img/cars/12.png" /></label>
                                    <div class="col-sm-10">
                                      <?php echo $this->Form->input('VehicleDoc.image.', array('type' => 'file', 'multiple' => FALSE, 'div' => FALSE, 'label' => FALSE, 'class' => 'validate[required]')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                        <h2 class="tabs-title"><span> 3. </span><?= __('Documents & pictures') ?></h2>
                        <h3><?= __('Documents of vehicles') ?></h3>
                        <p><?= __("You can upload vehicle documents such as expert's report.Maximum file size 18 MB,minimum image size: 370X490 pixels.") ?></p>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class=""> </div>
                                    <?php echo $this->Form->input('VehicleDoc.doc.', array('type' => 'file', 'multiple' => TRUE, 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => 'validate[required]')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row padin-row">
                    <div class="col-xs-6 text-left">
                        <a href="#" class="btn btn-default"><?= __('Previous') ?></a>
                    </div>
                    <div class="col-xs-6" style="text-align:right"> <a href="javascript:void(0);" onclick="$('#step2-form').submit();" class="btn btn-default"><?= __('Next') ?></a></div>
                </div>
            </div>
        </form>
    </div>
</section>

<script type = 'text/javascript'>
    $(document).ready(function () {
        $('#step2-form').validationEngine();
    });

</script>
