<section class="mid-section">
    <div class="container">
        <?php echo $this->Form->hidden('Vehicle.id'); ?>
        <div class="tab-bar-all-pages">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><?php echo $this->Html->link(__('Vehicle Data'), ['controller'=> 'Vehicles', 'action' => 'step1' ]); ?></li>
                <li role="presentation"><?php echo $this->Html->link( __('Documents & Pictures'), ['controller'=> 'Vehicles', 'action' => 'step2' ]); ?></li>
                <li role="presentation" class="active"><a href="javascript:void(0);" aria-controls="settings" role="tab" data-toggle="tab"><?= __('Auction') ?></a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                    <h2 class="tabs-title"><span> 3. </span><?= __('Auction') ?></h2>
                    <p><?= __('Fields marked with asterisk (*) are mandatory') ?></p>

                    <div class="row tabs-content-wrap">
                        <form method="POST" id="step3-form">
                            <?php echo $this->Form->hidden('Vehicle.id'); ?>
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-md-4"> <label for="exampleInputEmail1"><?= __('Minimum Price *') ?></label></div>
                                    <div class="col-md-8">
                                        <?php echo $this->Form->input('Vehicle.min_auction_price', array('div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'placeholder' => __('CHF'), 'class' => 'validate[required] form-control')); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-md-4"> <label for="exampleInputEmail1"><?= __('Auction Duration *') ?></label></div>
                                    <div class="col-md-8">
                                        <?php echo $this->Form->input('Vehicle.auction_duration', array('div' => FALSE, 'label' => FALSE, 'class' => 'validate[required] form-control', 'empty' => __('--Please Select--'), 'type' => 'select', 'options' => array('1' => '24 hours', '2' => '48 hours', '3' => '72 hours'))); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-md-4"> <label for="exampleInputEmail1"><?= __('Buy now Price *') ?></label></div>
                                    <div class="col-md-8">
                                        <?php echo $this->Form->input('Vehicle.buy_price', array('div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'placeholder' => __('CHF'), 'class' => 'validate[required] form-control')); ?>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-md-4"> <label for="exampleInputEmail1"><?= __('To be transported by the *') ?></label></div>
                                    <div class="col-md-8">
                                        <?php echo $this->Form->input('Vehicle.transport_medium', array('div' => FALSE, 'label' => FALSE, 'class' => 'validate[required] form-control', 'empty' => __('--Please Select--'), 'type' => 'select', 'options' => array('Local Transporters' => 'Local Transporters'))); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-md-4"> <label for="exampleInputEmail1"><?= __('Increase with') ?></label></div>
                                    <div class="col-md-8">
                                        <?php echo $this->Form->input('Vehicle.increase_with', array('div' => FALSE, 'label' => FALSE, 'class' => 'validate[required] form-control', 'type' => 'select', 'options' => array('50' => '50', '100' => '100', '150' => '150', '200' => '200' ))); ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </div>

        <div class="row padin-row">
            <div class="col-xs-6 text-left">
                <a href="#" class="btn btn-default"><?= __('Previous') ?></a>
            </div>
            <div class="col-xs-6" style="text-align:right"> <a href="javascript:void(0);" onclick="$('#step3-form').submit();" class="btn btn-default"><?= __('Done') ?></a></div>
        </div>


    </div>
</section>

<script type = 'text/javascript'>

    $(document).ready(function () {
        $('#step3-form').validationEngine();

    });
</script>
