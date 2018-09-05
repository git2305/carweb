<style>
    /*set a border on the images to prevent shifting*/
    #gallery_01 img{border:2px solid white;}

    /*Change the colour*/
    .active img{border:2px solid #333 !important;}
    #img_01{position:relative !important;}
    .img_01{heitht:420px !important;width:750px !important;}
</style>

<section class="mid-section">
    <div class="container">
        <input type="hidden" name="auction_ovr_tym" id="auction_ovr_tym" value="<?php echo $vehicle['Vehicle']['Vehicle']['auction_ovr_tym']; ?>"/>
        <div class="row row-margentop">
            <div class="col-lg-8">
                <div class="box-top">
                    <h1><span><?php echo $vehicle['Vehicle']['Vehicle']['brand']; ?> </span><?php echo $vehicle['Vehicle']['Vehicle']['model'] . ' ' . $vehicle['Vehicle']['Vehicle']['type'] . ' ' . $vehicle['Vehicle']['Vehicle']['body_type'] . ', ' . $vehicle['Vehicle']['Vehicle']['hp'] . ' HP'; ?></h1>

                    <div class="time top-box-priz">
                        <div class="time-out-aut" id="counterAuction"></div>
                    </div>

                </div>

                <?php $vehicleImg = $this->requestAction('/Users/findVehicleImage/' . base64_encode($vehicle['Vehicle']['Vehicle']['id'])); ?>
                <div class="mid-car-onclick"> <img class="img_01" id="img_01" src="<?php echo BASE_URL . '/img/vehicle/orignal/' . $vehicleImg; ?>" data-zoom-image="<?php echo BASE_URL . '/img/vehicle/orignal/' . $vehicleImg; ?>"/>
                    <div class="clearfix"></div>
                    <div id="gallery_01" style="margin-top: 20px;">
                        <?php if (!empty($vehicle['Vehicle']['VehicleDoc'])) {
                            foreach ($vehicle['Vehicle']['VehicleDoc'] as $key => $val) {
                                if ($val['file_type'] == '2') {
                                    ?>
                                    <div class="sm-img-car"><a href="#" class="fancybox" data-image="<?php echo BASE_URL . '/img/vehicle/orignal/' . $val['file_name']; ?>" data-zoom-image="<?php echo BASE_URL . '/img/vehicle/orignal/' . $val['file_name']; ?>"><img src="<?php echo BASE_URL . '/img/vehicle/thumb/' . $val['file_name']; ?>"></a></div>

                                    <?php
                                }
                            }
                        }
                        ?> 
                    </div>
                </div>
                <div class="star">
                    <!--                    <a href="#">RoccoPro</a> 
                                        <a href="#" class="staricon"><img src="<?php echo BASE_URL; ?>/img/front/star-128.png"></a>
                                        <a href="#" class="staricon"><img src="<?php echo BASE_URL; ?>/img/front/star-128-h.png"></a>
                                        <a href="#" class="staricon"><img src="<?php echo BASE_URL; ?>/img/front/star-128-h.png"></a>
                                        <a href="#">1450 Sainte-Croix Schweiz</a>-->

                    <div class="right"><a href="javascript:void(0);" class="addtoFav"><i class="fa <?php echo (isset($vehicle['Vehicle']['VehicleFavourite'][0]['is_favourite']) && $vehicle['Vehicle']['VehicleFavourite'][0]['is_favourite'] == '1') ? 'fa-heart' : 'fa-heart-o' ?>" aria-hidden="true"></i></a></div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12">
                <h1><?php echo __('Auction Starts in'); ?></h1>

                <div class="top-box-priz">
                    <div class="box"> <p><?php echo __('Auction Price'); ?></p>
                        <h4><?php echo $vehicle['Vehicle']['Vehicle']['min_auction_price']; ?></h4></div>
                    <div class="box"> <p><?php echo __('Your bid'); ?></p>
                        <input placeholder="<?php echo $vehicle['Vehicle']['Vehicle']['min_auction_price']; ?>" data-auction-amt = "<?php echo $vehicle['Vehicle']['Vehicle']['min_auction_price']; ?>" type="text" class="auctionBid"></div>



                    <div class="box box-full-width"> <a href="javascript:void(0);" class="btn btnBid" data-id="<?php echo $vehicle['Vehicle']['Vehicle']['id']; ?>">Bieten</a></div>

                </div>

                <div class="table-box">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo date('m.Y', strtotime($vehicle['Vehicle']['Vehicle']['first_reg'])); ?> Occasion </td>
                                <td><i class="fa fa-bullseye" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['hp']; ?> PS/<?php echo $vehicle['Vehicle']['Vehicle']['kw']; ?> KW</td>
                            </tr>
                            <tr class="">
                                <td><i class="fa fa-road" aria-hidden="true"></i><?php echo intval($vehicle['Vehicle']['Vehicle']['kilometers']); ?> km</td>
                                <td><i class="fa fa-taxi" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['gear']; ?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-calendar" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['fuel'] . " " . $vehicle['Vehicle']['Vehicle']['type']; ?> </td>
                                <td><i class="fa fa-gear" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['wheel_drive'] . ' ' . __('wheel drive'); ?></td>
                            </tr>
                            <tr><td colspan="2" style="text-align:right"> <a href="#"><?php echo __('View all details'); ?></a></td></tr>



                        </tbody>
                    </table>

                </div>
                <div class="cor-damage">
                    <h3>Damage Car</h3>
<?php if (!empty($vehicle['Vehicle']['VehicleDamage'])) {
    foreach ($vehicle['Vehicle']['VehicleDamage'] as $k => $v) {
        ?>
        <?php if ($v['left_file_name'] != '' || $v['right_file_name'] != '' || $v['top_file_name'] != '' || $v['bottom_file_name'] != '' || $v['back_file_name'] != '') { ?>
                                <img src="<?php echo BASE_URL . '/img/vehicledamage/thumb/' . $v['left_file_name']; ?>">
                                <img src="<?php echo BASE_URL . '/img/vehicledamage/thumb/' . $v['right_file_name']; ?>">
                                <img src="<?php echo BASE_URL . '/img/vehicledamage/thumb/' . $v['top_file_name']; ?>">
                                <img src="<?php echo BASE_URL . '/img/vehicledamage/thumb/' . $v['bottom_file_name']; ?>">
                                <img src="<?php echo BASE_URL . '/img/vehicledamage/thumb/' . $v['back_file_name']; ?>">
        <?php } else { ?>
                                <h3>No Damage Car Images</h3>
                                    <?php } ?>

                            <div class="line-box">
                                <div class="left">Defekte:</div>
                                <div class="right"> 
        <?php $dmArr = explode(',', $v['vehicle_damage']) ?>
                                    <label class=""><input type="checkbox"  <?php echo (in_array('motor', $dmArr)) ? 'checked' : '' ?> disabled> Motor</label>
                                    <label class=""> <input type="checkbox" <?php echo (in_array('getriebe', $dmArr)) ? 'checked' : '' ?> disabled>Getriebe</label>
                                    <label class=""> <input type="checkbox" <?php echo (in_array('vorderachse', $dmArr)) ? 'checked' : '' ?> disabled> Vorderachse</label>
                                    <label class=""> <input type="checkbox" <?php echo (in_array('hinterachse', $dmArr)) ? 'checked' : '' ?> disabled>Hinterachse</label>
                                </div>
                            </div>
    <?php }
} else { ?>
                        <h4 style="padding:5px;">No Vehicle Damage</h4>
<?php } ?>

                    <div class="line-box">
                        <div class="left">Airbags:</div>
                        <div class="right"><label class=""> Airbags ausgelost:0</label></div>
                    </div>
                    <div class="line-box">
                        <div class="left">Altfahrzeug:</div>
                        <div class="right"><label class=""> Airbags ausgelost:0</label></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row-bottome">
<!--                    <span><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $vehicle['User']['email']; ?></span>
                    <span><i class="fa fa-phone" aria-hidden="true"></i> <?php echo $vehicle['User']['phone']; ?></span>-->
                    <a href="#"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-vimeo-square" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <!--<div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><?php echo __('Seller Information'); ?></a>
                                <div class="pull-right">
                                    <span class="glyphicon glyphicon-plus-sign cl-pl" aria-hidden="true"></span>
                                    <span class="glyphicon glyphicon-minus-sign pl-cl" aria-hidden="true"></span></div>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%"><?php echo __('Name'); ?></td>
                                            <td width="70%"><?php
                    if (isset($vehicle['Vehicle']['User']['fname']) && !empty($vehicle['Vehicle']['User']['fname'])) {
                        echo trim($vehicle['Vehicle']['User']['fname'] . " " . $vehicle['Vehicle']['User']['lname']);
                    } else {
                        echo "N/A";
                    }
                    ?></td>
                                        </tr>
                                        <tr>
                                            <td width="30%"><?php echo __('Email'); ?></td>
                                            <td width="70%"><?php
                    if (isset($vehicle['Vehicle']['User']['email']) && !empty($vehicle['Vehicle']['User']['email'])) {
                        echo trim($vehicle['Vehicle']['User']['email']);
                    } else {
                        echo "N/A";
                    }
                    ?></td>
                                        </tr>
                                        <tr>
                                            <td width="30%"><?php echo __('Phone'); ?></td>
                                            <td width="70%"><?php
                    if (isset($vehicle['Vehicle']['User']['phone']) && !empty($vehicle['Vehicle']['User']['phone'])) {
                        echo trim($vehicle['Vehicle']['User']['phone']);
                    } else {
                        echo "N/A";
                    }
                    ?></td>
                                        </tr>
                                        <tr>
                                            <td width="30%"><?php echo __('Mobile'); ?></td>
                                            <td width="70%"><?php
                    if (isset($vehicle['Vehicle']['User']['mobile']) && !empty($vehicle['Vehicle']['User']['mobile'])) {
                        echo trim($vehicle['Vehicle']['User']['mobile']);
                    } else {
                        echo "N/A";
                    }
                    ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>-->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"><?php echo __('Special equipment'); ?></a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
<?php echo $vehicle['Vehicle']['Vehicle']['special_equipment']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> <?php echo __('Serial equipment'); ?> </a> </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="panel-body">
<?php echo $vehicle['Vehicle']['Vehicle']['serial_equipment']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingFour">
                            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour"><?php echo __('Vehicle description'); ?></a> </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4 padding-right-10">
                                        <table class="table table-striped ">
                                            <tbody>

                                                <tr>
                                                    <td width="40%"><?php echo __('Repairs'); ?></td>
                                                    <td width="60%"><?php echo $vehicle['Vehicle']['Vehicle']['repairs']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Accident vehicle'); ?></td>
                                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['gen_condition']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Vehicle inspection'); ?></td>
                                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['inspection']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-4 padding-right-10">
                                        <table class="table table-striped ">
                                            <tbody>

                                                <tr>
                                                    <td width="40%"><?php echo __('Service record'); ?></td>
                                                    <td width="60%"><?php echo __('Available'); ?></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Vehicle registration document'); ?></td>
                                                    <td><?php echo __('Will be supplied later'); ?></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Number of keys'); ?></td>
                                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['no_of_keys']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-4 padding-right-10">
                                        <table class="table table-striped ">
                                            <tbody>

                                                <tr>
                                                    <td width="40%"><?php echo __('Model Number'); ?></td>
                                                    <td width="60%"><?php echo $vehicle['Vehicle']['Vehicle']['vehicle_no']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Frame Number'); ?></td>
                                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['frame_no']; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Register Number'); ?></td>
                                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['reg_no']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingFive">
                            <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive"><?php echo __('Condition'); ?></a> </h4>
                        </div>
                        <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td width="30%"><?php echo __('Mechanics'); ?></td>
                                            <td width="70%"><?php echo $vehicle['Vehicle']['Vehicle']['mechanics']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Car finish'); ?></td>
                                            <td><?php echo $vehicle['Vehicle']['Vehicle']['car_finish']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Body'); ?></td>
                                            <td><?php echo $vehicle['Vehicle']['Vehicle']['body']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo __('Other'); ?></td>
                                            <td>
                                                <span class="white-base-wrap">
<?php echo $vehicle['Vehicle']['Vehicle']['other_condition']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <h1><span><?php echo __('Recent'); ?></span> <?php echo __('Offers'); ?></h1>

                <p><?php echo (isset($vehicle['AuctionBid']) && !empty($vehicle['AuctionBid']) ? count($vehicle['AuctionBid']) : __('No') ) . ' ' . __('offer(s) found.'); ?></p>
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
if (!empty($vehicle['AuctionBid'])) {
    foreach ($vehicle['AuctionBid'] as $key => $val) {
        ?>
                                <tr>
                                    <td><?php echo $val['User']['username']; ?></td>
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

    <input type="hidden" name="user_id" id="user_id" value="<?php echo AuthComponent::User('id'); ?>" />
    <input type="hidden" name="vehicle_id" id="vehicle_id" value="<?php echo $vehicle['Vehicle']['Vehicle']['id']; ?>" />
    <input type="hidden" name="is_favourite" id="is_favourite" value="<?php echo (isset($vehicle['Vehicle']['VehicleFavourite'][0]['is_favourite']) && $vehicle['Vehicle']['VehicleFavourite'][0]['is_favourite'] != '') ? $vehicle['Vehicle']['VehicleFavourite'][0]['is_favourite'] : '' ?>"/>
</section>
<script>
    $("#img_01").elevateZoom({gallery: 'gallery_01', cursor: 'pointer', galleryActiveClass: 'active', imageCrossfade: true, loadingIcon: ''});

    //pass the images to Fancybox
    $("#img_01").bind("click", function (e) {
        var ez = $('#img_01').data('elevateZoom');
        $.fancybox(ez.getGalleryList());
        return false;
    });

    $('#gallery_01').slimscroll({
        size: '5px',
        height: '170px',
    });

    $('#counterAuction').countdown(Date.parse($('#auction_ovr_tym').val()), {elapse: true}).on('update.countdown', function (event) {
        var $this = $(this);
        if (event.elapsed) {
            location.href = siteUrl;
        } else {
            $this.html(event.strftime('<div class="Hour"><h6 id="auctionHours">%H<span>:</span></h6><p><?php echo __('Hours'); ?></p></div><div class="Minutes"><h6 id="auctionMins">%M <span>:</span></h6><p><?php echo __('Minutes'); ?></p></div><div class="Seconds"><h6 id="auctionSecs">%S </h6><p><?php echo __('Seconds'); ?></p></div>'));
        }
    });

</script>
