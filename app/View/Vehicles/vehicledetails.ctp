<section class="mid-section">
    <div class="container">
        <input type="hidden" name="auction_ovr_tym" id="auction_ovr_tym" value="<?php echo $vehicle['Vehicle']['auction_ovr_tym']; ?>"/>
        <div class="main-sidebar">
            <h1 class="main-title main-title-left"><?php echo $vehicle['Vehicle']['brand']; ?> <span><?php echo $vehicle['Vehicle']['model'] . ' ' . $vehicle['Vehicle']['type'] . ' ' . $vehicle['Vehicle']['body_type'] . ', ' . $vehicle['Vehicle']['hp'] . ' HP'; ?></span></h1>

            <div class="product-main-gallery">
                <div id="carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner product-img-box">
                        <?php
                        if (!empty($vehicle['VehicleDoc'])) {
                            $i = 0;
                            foreach ($vehicle['VehicleDoc'] as $key => $val) {
                                if ($val['file_type'] == '2') {
                                    ?>
                                    <div class="item <?php echo $i == 0 ? 'active' : ''; ?>">
                                        <a class="fancybox product-img-main" href="<?php echo BASE_URL . '/img/vehicle/orignal/' . $val['file_name']; ?>" data-zoom-image="<?php echo BASE_URL . '/img/vehicle/orignal/' . $val['file_name']; ?>"  data-fancybox-group="gallery" title="<?php echo $vehicle['Vehicle']['brand'] . " " . $vehicle['Vehicle']['model']; ?>">
                                            <img class="product-img-box-image" src="<?php echo BASE_URL . '/img/vehicle/orignal/' . $val['file_name']; ?>" alt="<?php echo $vehicle['Vehicle']['brand'] . " " . $vehicle['Vehicle']['model']; ?>" >
                                        </a>
                                    </div>
                                    <?php
                                    $i++;
                                }
                            }
                        }
                        ?>
                    </div>
                </div> 

                <div id="thumbcarousel" class="carousel slide thumbcarousel" data-interval="false">
                    <div class="carousel-inner product-img-carousel">
                        <div class="item active">
                            <?php
                            if (!empty($vehicle['VehicleDoc'])) {
                                $thumbKey = 0;
                                foreach ($vehicle['VehicleDoc'] as $key => $val) {
                                    if ($val['file_type'] == '2') {
                                        ?>
                                        <?php if ($thumbKey > 0 && $thumbKey % 4 === 0): ?>
                                        </div><div class="item">
                                        <?php endif; ?>
                                        <div data-target="#carousel" data-slide-to="<?php echo $key; ?>" class="thumb">
                                            <div class="thumb-wrap"><img class="product-img-box-image" src="<?php echo BASE_URL . '/img/vehicle/thumb/' . $val['file_name']; ?>" alt="<?php echo $vehicle['Vehicle']['brand'] . " " . $vehicle['Vehicle']['model']; ?>" ></div>
                                        </div>
                                        <?php
                                        $thumbKey++;
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div><!-- /carousel-inner -->
                    <a class="left carousel-control" href="#thumbcarousel" role="button" data-slide="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                    <a class="right carousel-control" href="#thumbcarousel" role="button" data-slide="next"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                </div> <!-- /thumbcarousel -->
            </div>


            <div class="sidebar-block mar-top-25">
                <h5><?php echo __('Vehicle description'); ?></h5>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td width="40%"><b><?php echo __('Displacement'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['displacement']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Exterior Color'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['exterior_color']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Interior Color'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['interior_color']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Body Type'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['body_type']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Type'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['type']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Repairs'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['repairs']; ?></td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Accident vehicle'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['gen_condition']; ?></td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Vehicle inspection'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['inspection']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Service record'); ?></b></td>
                                    <td width="60%">
                                        <?php
                                            if( $vehicle['Vehicle']['service_record'] == 1 ){
                                                echo __('Available');
                                            } else if ( $vehicle['Vehicle']['service_record'] == 2 ) {
                                                echo __('Incomplete');
                                            } else if ( $vehicle['Vehicle']['service_record'] == 2 ) {
                                                echo __('Missing');
                                            } else {
                                                echo '-';
                                            }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Vehicle registration document'); ?></b></td>
                                    <td>
                                        <?php
                                            if( $vehicle['Vehicle']['reg_document'] == 1 ){
                                                echo __('Available');
                                            } else if ( $vehicle['Vehicle']['reg_document'] == 2 ) {
                                                echo __('Will be supplied later');
                                            } else {
                                                echo '-';
                                            }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Number of keys'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['no_of_keys']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Model Number'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['vehicle_no']; ?></td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Frame Number'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['frame_no']; ?></td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Register Number'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['reg_no']; ?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo __('Options / Additional information'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['additional_info']; ?></td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Swiss Car'); ?></b></td>
                                    <td>
                                        <?php
                                            if( $vehicle['Vehicle']['swiss_car'] == '1' ){
                                                echo __('Yes');
                                            } else {
                                                echo __('No');
                                            }
                                            
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Vehicle Region'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['vehicle_regions']; ?></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="sidebar-block">
                <h5><?php echo __('Mechanics'); ?></h5>
                <div class="row">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td width="40%"><b><?php echo __('Mechanics'); ?></b></td>
                                <td width="60%"><?php echo $vehicle['Vehicle']['mechanics_'.Configure::read('Config.language')]; ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo __('Car finish'); ?></b></td>
                                <td><?php echo $vehicle['Vehicle']['car_finish']; ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo __('Body'); ?></b></td>
                                <td><?php echo $vehicle['Vehicle']['body_'.Configure::read('Config.language')]; ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo __('Other'); ?></b></td>
                                <td>
                                    <span class="white-base-wrap"><?php echo $vehicle['Vehicle']['other_condition_'. Configure::read('Config.language')]; ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="sidebar-right">
            
            <div class="sidebar-block">
                <ul class="details-list">
                    <li><i class="fa fa-calendar" aria-hidden="true"></i>
                        <?php
                        if ($vehicle['Vehicle']['first_reg'] != '') {
                            $firstRegDate = new DateTime('01/' . $vehicle['Vehicle']['first_reg']);
                            echo $firstRegDate->format('d/Y');
                        } else {
                            echo '-';
                        }
                        ?> Occasion</li>
                    <li><i class="fa fa-bullseye" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['hp']; ?> PS/<?php echo $vehicle['Vehicle']['kw']; ?> KW</li>
                    <li><i class="fa fa-tachometer " aria-hidden="true"></i><?php echo intval($vehicle['Vehicle']['kilometers']); ?> km</li>
                    <li><i class="fa fa-gear" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['gear']; ?></li>
                    <li><i class="fa fa-fire" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['fuel'] . " " . $vehicle['Vehicle']['type']; ?></li>
                    <li><i class="fa fa-futbol-o" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['wheel_drive'] . ' ' . __('wheel drive'); ?></li>

                    <li><i class="fa fa-building" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['doors'] . ' ' . __('doors'); ?></li>
                    <li><i class="fa fa-key" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['no_of_keys'] . ' ' . __('key(s)'); ?></li>
                    <li><i class="fa fa-wheelchair" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['no_of_seats'] . ' ' . __('seats'); ?></li>
                    <li><i class="fa fa-registered" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['reg_no']; ?></li>
                </ul>
                <!-- <div class="pull-right">
                    <a class="show-more-button less" href="javascript:void(0);"><?php //echo __('more...'); ?></a>
                </div> -->
            </div>



            <?php if (!empty($vehicle['Vehicle']['VehicleDamage'])) { ?>
                <div class="sidebar-block">
                    <h5><?php echo __('Damage Car'); ?></h5>
                    <div class="cor-damage">
                        <?php foreach ($vehicle['Vehicle']['VehicleDamage'] as $k => $v) {
                            ?>

                            <?php if ($v['left_file_name'] != '') { ?>
                                <a class="fancybox damage-car-imgs" href="<?php echo BASE_URL . '/img/vehicledamage/orignal/' . $v['left_file_name']; ?>"><img src="<?php echo BASE_URL . '/img/vehicledamage/thumb/' . $v['left_file_name']; ?>"></a>
                            <?php } ?>

                            <?php if ($v['right_file_name'] != '') { ?>
                                <a class="fancybox damage-car-imgs" href="<?php echo BASE_URL . '/img/vehicledamage/orignal/' . $v['right_file_name']; ?>"><img src="<?php echo BASE_URL . '/img/vehicledamage/thumb/' . $v['right_file_name']; ?>"></a>
                            <?php } ?>

                            <?php if ($v['top_file_name'] != '') { ?>
                                <a class="fancybox damage-car-imgs" href="<?php echo BASE_URL . '/img/vehicledamage/orignal/' . $v['top_file_name']; ?>"><img src="<?php echo BASE_URL . '/img/vehicledamage/thumb/' . $v['top_file_name']; ?>"></a>
                            <?php } ?>

                            <?php if ($v['bottom_file_name'] != '') { ?>
                                <a class="fancybox damage-car-imgs" href="<?php echo BASE_URL . '/img/vehicledamage/orignal/' . $v['bottom_file_name']; ?>"><img src="<?php echo BASE_URL . '/img/vehicledamage/thumb/' . $v['bottom_file_name']; ?>"></a>
                            <?php } ?>

                            <?php if ($v['back_file_name'] != '') { ?>
                                <a class="fancybox damage-car-imgs" href="<?php echo BASE_URL . '/img/vehicledamage/orignal/' . $v['back_file_name']; ?>"><img src="<?php echo BASE_URL . '/img/vehicledamage/thumb/' . $v['back_file_name']; ?>"></a>
                            <?php } ?>

                        <?php }
                        ?>
                    </div>
                </div>


            <?php } ?>
            
            <?php if (!empty($vehicle['Vehicle']['VehicleDoc'])) { ?>
                <div class="sidebar-block">
                    <h5><?php echo __('Vehicle Documents'); ?></h5>
                    <ul class="details-list">
                        <?php $i = 1;
                            foreach ($vehicle['Vehicle']['VehicleDoc'] as $k => $v) { ?>
                            <?php if (file_exists(WWW_ROOT . '/files/doc/' . $v['file_name'])) { ?>
                                <li>
                                    <span class="document-label"><?php echo __('Documents'). ' '. ($i); ?></span>
                                    <a download class="vehicle-documents btn btn-sm btn-primary" href="<?php echo BASE_URL . '/files/doc/' . $v['file_name']; ?>"><span class="glyphicon glyphicon-download"></span> Download</a>
                                </li>
                            <?php $i++; } ?>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>


        </div>        

        <div class="products-accordion">
            <div id="accordion">

                <div>

                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-md-12">

                <h1 class="main-title"><span><?php echo __('Recent'); ?></span> <?php echo __('Offers'); ?></h1>

                <p><?php echo (isset($vehicle['Vehicle']['AuctionBid']) && !empty($vehicle['Vehicle']['AuctionBid']) ? count($vehicle['Vehicle']['AuctionBid']) : __('No') ) . ' ' . __('offer(s) found.'); ?></p>
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo __('Name'); ?></th>
                            <th><?php echo __('Bidding Price'); ?></th>
                            <th><?php echo __('Created'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($vehicle['Vehicle']['AuctionBid'])) {
                            foreach ($vehicle['Vehicle']['AuctionBid'] as $key => $val) {
                                ?>
                                <tr>
                                    <td><?php echo $vehicle['buyerArr'][$val['user_id']]; ?></td>
                                    <td><?php echo __('CHF') . ' ' . number_format($val['biding_amount'], 2); ?></td>
                                    <td><?php echo date('m/d/Y', strtotime($val['created'])); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="3" align="center"><?php echo __('No offer(s) found'); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
   
</section>
<script>

    $(document).ready(function () {
       
        $('.fancybox').fancybox();
    });

</script>
