<!-- banner -->
<section class="banner">
    <div class="container">
        <div class="slider-wrap">
            <div class="main-banner">
                <div class="col-md-12">
                <?php echo $this->element('front/auction_listing'); ?>
                    </div>
            </div>
            <div class="banner-right"> 
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#UsedCars" aria-controls="UsedCars" role="tab" data-toggle="tab"><?php echo __("Quick Search"); ?></a></li>
                    <li role="presentation"><a href="#NewCars" aria-controls="NewCars" role="tab" data-toggle="tab"><?php echo __("Search by body type"); ?></a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="UsedCars">
                        <form>
                            <div class="form-group">
                                <label><?= __("Make") ?></label>
                                <select id="vehicleMake" class="form-control">
                                    <option value=""><?php echo __('Select make'); ?></option>
                                </select>
                            </div>
                            <div class="form-group left">
                                <label for="exampleInputEmail1">Model</label>
                                <select id="vehicleModel" class="form-control">
                                    <option value=""><?php echo __('Select model'); ?></option>
                                </select>
                            </div>
                            <div class="form-group right">
                                <label for="exampleInputEmail1"><?= __("Region") ?></label>
                                <?php echo $this->Form->input('vehicle_regions', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control', 'empty' => __('Select Region'), 'type' => 'select', 'options' => $region_data)); ?>
                            </div>
                            <div class="form-group left">
                                <label for="exampleInputEmail1"><?= __("Min Year") ?></label>
                                <?php
                                    $minArr = array();
                                    $maxArr = array();
                                    for ($i = 2000; $i <= 2020; $i++) {
                                        $minArr[$i] = $i;
                                        $maxArr[$i] = $i;
                                    }
                                    echo $this->Form->input('min_year', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control', 'empty' => __('Min Year'), 'type' => 'select', 'options' => $minArr));
                                ?>
                            </div>
                            <div class="form-group right">
                                <label for="exampleInputEmail1"><?= __("Max Year") ?></label>
                                <?php echo $this->Form->input('max_year', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control', 'empty' => __('Max Year'), 'type' => 'select', 'options' => $maxArr)); ?>
                            </div>
                            <div class="text-right"> <button type="button" class="btn btn-default btnQuickSearch"><?php echo __("Search"); ?></button></div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="NewCars">
                        <div role="tabpanel" class="tab-pane active" id="body-type">
                            <div class="tab-category-wrap">
                                <div class="square category">
                                        <a class="btn-default btn-block btn-white" id="sedan" href="<?php echo SITE_URL; ?>/vehicles/sellVehicles?bt=<?php echo base64_encode('Limousine'); ?>"><?php echo __('Limousine'); ?></a>
                                    </div>
                                    <div class="square pad-r0 pad-l0 category">
                                        <a class="btn-default btn-block btn-white" id="wagon" href="<?php echo SITE_URL; ?>/vehicles/sellVehicles?bt=<?php echo base64_encode('Kombi'); ?>"><?php echo __('Kombi'); ?></a>
                                    </div>
                                    <div class="square category">
                                        <a class="btn-default btn-block btn-white" id="mini-car" href="<?php echo SITE_URL; ?>/vehicles/sellVehicles?bt=<?php echo base64_encode('Kleinwagen'); ?>"><?php echo __('Kleinwagen'); ?></a>
                                    </div>

                                    <div class="square category">
                                        <a class="btn-default btn-block btn-white" id="suvs" href="<?php echo SITE_URL; ?>/vehicles/sellVehicles?bt=<?php echo base64_encode('SUV'); ?>"><?php echo __('SUV'); ?></a>
                                    </div>
                                    <div class="square category">
                                        <a class="btn-default btn-block btn-white" id="cabriolet" href="<?php echo SITE_URL; ?>/vehicles/sellVehicles?bt=<?php echo base64_encode('Cabrio'); ?>"><?php echo __('Cabriolet'); ?></a>
                                    </div>
                                    <div class="square category">
                                        <a class="btn-default btn-block btn-white" id="coupe" href="<?php echo SITE_URL; ?>/vehicles/sellVehicles?bt=<?php echo base64_encode('Coupe'); ?>"><?php echo __('Coupe'); ?></a>
                                    </div>

                                    <div class="square category">
                                        <a class="btn-default btn-block btn-white" id="hatch" href="<?php echo SITE_URL; ?>/vehicles/sellVehicles?bt=<?php echo base64_encode('Minivan'); ?>"><?php echo __('Minivan'); ?></a>
                                    </div>
                                    <div class="square category">
                                        <a class="btn-default btn-block btn-white" id="utes" href="<?php echo SITE_URL; ?>/vehicles/sellVehicles?bt=<?php echo base64_encode('Pickup'); ?>"><?php echo __('Pickup'); ?></a>
                                    </div>
                                    <div class="square category">
                                        <a class="btn-default btn-block btn-white" id="bus" href="<?php echo SITE_URL; ?>/vehicles/sellVehicles?bt=<?php echo base64_encode('Bus'); ?>"><?php echo __('Bus'); ?></a>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>



<script type = 'text/javascript'>

    ///var l = window.location;
    var BASE_URL = '<?php echo BASE_URL; ?>';  //l.protocol + "//" + l.host + "/carlisting/" + l.pathname.split('/')[1] + "/";

    /*var lastX = 0;
     var currentX = 0;
     var page = 1;
     $(window).scroll(function () {
     currentX = $(window).scrollTop();
     if (currentX - lastX > 200 * page) {
     lastX = currentX;
     page++;
     $.get(BASE_URL+'Users/getAuctionData/page:2', function(data) {
     $('#auctionData').append(data);
     });
     }
     });*/

</script>
