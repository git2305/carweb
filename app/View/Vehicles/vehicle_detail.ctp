<section class="mid-section">
    <div class="container">
        <input type="hidden" name="auction_ovr_tym" id="auction_ovr_tym" value="<?php echo $vehicle['Vehicle']['Vehicle']['auction_ovr_tym']; ?>"/>
        <div class="main-sidebar">
            <h1 class="main-title main-title-left"><?php echo $vehicle['Vehicle']['Vehicle']['brand']; ?> <span><?php echo $vehicle['Vehicle']['Vehicle']['model'] . ' ' . $vehicle['Vehicle']['Vehicle']['type'] . ' ' . $vehicle['Vehicle']['Vehicle']['body_type'] . ', ' . $vehicle['Vehicle']['Vehicle']['hp'] . ' HP'; ?></span></h1>

            <div class="product-main-gallery">
                <div id="carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner product-img-box">
                        <?php
                        if (!empty($vehicle['Vehicle']['VehicleDoc'])) {
                            $i = 0;
                            foreach ($vehicle['Vehicle']['VehicleDoc'] as $key => $val) {
                                if ($val['file_type'] == '2') {
                                    ?>
                                    <div class="item <?php echo $i == 0 ? 'active' : ''; ?>">
                                        <a class="fancybox product-img-main" href="<?php echo BASE_URL . '/img/vehicle/orignal/' . $val['file_name']; ?>" data-zoom-image="<?php echo BASE_URL . '/img/vehicle/orignal/' . $val['file_name']; ?>"  data-fancybox-group="gallery" title="<?php echo $vehicle['Vehicle']['Vehicle']['brand'] . " " . $vehicle['Vehicle']['Vehicle']['model']; ?>">
                                            <img class="product-img-box-image" src="<?php echo BASE_URL . '/img/vehicle/orignal/' . $val['file_name']; ?>" alt="<?php echo $vehicle['Vehicle']['Vehicle']['brand'] . " " . $vehicle['Vehicle']['Vehicle']['model']; ?>" >
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
                            if (!empty($vehicle['Vehicle']['VehicleDoc'])) {
                                $thumbKey = 0;
                                foreach ($vehicle['Vehicle']['VehicleDoc'] as $key => $val) {
                                    if ($val['file_type'] == '2') {
                                        ?>
                                        <?php if ($thumbKey > 0 && $thumbKey % 4 === 0): ?>
                                        </div><div class="item">
                                        <?php endif; ?>
                                        <div data-target="#carousel" data-slide-to="<?php echo $key; ?>" class="thumb">
                                            <div class="thumb-wrap"><img class="product-img-box-image" src="<?php echo BASE_URL . '/img/vehicle/thumb/' . $val['file_name']; ?>" alt="<?php echo $vehicle['Vehicle']['Vehicle']['brand'] . " " . $vehicle['Vehicle']['Vehicle']['model']; ?>" ></div>
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

            <div class="row-bottome">
                <div class="product-timer">
                    <div class="time top-box-priz">
                        <div class="time-out-aut" id="counterAuction"></div>
                    </div>
                </div>
                <ul class="product-social">
                    <li class="facebook">
                        <a href="javascript:void(0);" class="addtoFav"><i aria-hidden="true" class="fa <?php echo (isset($vehicle['Vehicle']['VehicleFavourite'][0]['is_favourite']) && $vehicle['Vehicle']['VehicleFavourite'][0]['is_favourite'] == '1') ? 'fa-heart' : 'fa-heart-o' ?>" aria-hidden="true"></i></a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-block mar-top-25">
                <h5><?php echo __('Vehicle description'); ?></h5>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td width="40%"><b><?php echo __('Displacement'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['Vehicle']['displacement']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Exterior Color'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['Vehicle']['exterior_color']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Interior Color'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['Vehicle']['interior_color']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Body Type'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['Vehicle']['body_type']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Type'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['Vehicle']['type']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Repairs'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['Vehicle']['repairs']; ?></td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Accident vehicle'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['gen_condition']; ?></td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Vehicle inspection'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['inspection']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Service record'); ?></b></td>
                                    <td width="60%">
                                        <?php
                                            if( $vehicle['Vehicle']['Vehicle']['service_record'] == 1 ){
                                                echo __('Available');
                                            } else if ( $vehicle['Vehicle']['Vehicle']['service_record'] == 2 ) {
                                                echo __('Incomplete');
                                            } else if ( $vehicle['Vehicle']['Vehicle']['service_record'] == 2 ) {
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
                                            if( $vehicle['Vehicle']['Vehicle']['reg_document'] == 1 ){
                                                echo __('Available');
                                            } else if ( $vehicle['Vehicle']['Vehicle']['reg_document'] == 2 ) {
                                                echo __('Will be supplied later');
                                            } else {
                                                echo '-';
                                            }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Number of keys'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['no_of_keys']; ?></td>
                                </tr>

                                <tr>
                                    <td width="40%"><b><?php echo __('Model Number'); ?></b></td>
                                    <td width="60%"><?php echo $vehicle['Vehicle']['Vehicle']['vehicle_no']; ?></td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Frame Number'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['frame_no']; ?></td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Register Number'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['reg_no']; ?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo __('Options / Additional information'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['additional_info']; ?></td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Swiss Car'); ?></b></td>
                                    <td>
                                        <?php
                                            if( $vehicle['Vehicle']['Vehicle']['swiss_car'] == '1' ){
                                                echo __('Yes');
                                            } else {
                                                echo __('No');
                                            }
                                            
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b><?php echo __('Vehicle Region'); ?></b></td>
                                    <td><?php echo $vehicle['Vehicle']['Vehicle']['vehicle_regions']; ?></td>
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
                                <td width="60%"><?php echo $vehicle['Vehicle']['Vehicle']['mechanics_'.Configure::read('Config.language')]; ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo __('Car finish'); ?></b></td>
                                <td><?php echo $vehicle['Vehicle']['Vehicle']['car_finish']; ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo __('Body'); ?></b></td>
                                <td><?php echo $vehicle['Vehicle']['Vehicle']['body_'.Configure::read('Config.language')]; ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo __('Other'); ?></b></td>
                                <td>
                                    <span class="white-base-wrap"><?php echo $vehicle['Vehicle']['Vehicle']['other_condition_'. Configure::read('Config.language')]; ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div  class="sidebar-right">
            
            <?php if( $vehicle['Vehicle']['Vehicle']['is_sell'] == 0 ){ ?>
            
            <div id="pricebox" class="sidebar-block">
                <div class="top-box-price">
                    <div class="box">
                        <p><?php echo __('Auction Price'); ?></p>
                        <h4>
                            <?php
                            if ($vehicle['minAuctionPrice'] != '') {
                                echo $vehicle['minAuctionPrice'];
                            } else {
                                echo $vehicle['Vehicle']['Vehicle']['min_auction_price'];
                            }
                            ?>
                        </h4>
                    </div>
                    <div class="box">
                        <p><?php echo __('Increase with'); ?></p>
                        <?php echo $this->Form->input('auctionBid', ['type' => 'select', 'label' => false, 'class' => 'auctionBid', 'options' => ['25' => 25, '50' => 50]  ]); //$vehicle['bidDropDown'] ?>                        
                    </div>
                    
                    <div class="btn-set"><a href="javascript:void(0);" class="btn btnBidWithIncrease" data-max-bid="<?php echo $vehicle['minAuctionPrice']; ?>" data-increase="<?php echo $vehicle['Vehicle']['Vehicle']['increase_with']; ?>" data-buy-price="<?php echo $vehicle['Vehicle']['Vehicle']['buy_price']; ?>" data-id="<?php echo $vehicle['Vehicle']['Vehicle']['id']; ?>">Bid with increase</a></div>
                        
                        <p class="text-center"><?php echo __('OR');?></p>
                        <?php echo $this->Form->input('custom_auction_bid', array('div' => FALSE, 'label' => FALSE, 'placeholder' => __('Enter custom bid price') ,'class' => 'validate[condRequired[auctionBid]] form-control custom-increase-with')); ?>
                        <div class="btn-set"><a href="javascript:void(0);" class="btn btnBid" data-max-bid="<?php echo $vehicle['minAuctionPrice']; ?>" data-increase="<?php echo $vehicle['Vehicle']['Vehicle']['increase_with']; ?>" data-buy-price="<?php echo $vehicle['Vehicle']['Vehicle']['buy_price']; ?>" data-id="<?php echo $vehicle['Vehicle']['Vehicle']['id']; ?>">Bid Now</a></div>
                    
                    <div class="clearfix"></div>
                    
                    <hr/>
                    <p class="text-center">OR</p>
                    <p><?php echo __('Buy Price'); ?> : <span class="buy-now-price">CHF <?php echo $vehicle['Vehicle']['Vehicle']['buy_price']; ?></span> </p>
                    <div class="btn-set"><a class="btn buyNow" href="javascript:void(0);" data-buy-price="<?php echo $vehicle['Vehicle']['Vehicle']['buy_price']; ?>" data-id="<?php echo $vehicle['Vehicle']['Vehicle']['id']; ?>"><?php echo __('Buy Now'); ?></a></div>
                </div>
            </div>
            
            <?php } ?>
            <div class="sidebar-block">
                <ul class="details-list">
                    <li><i class="fa fa-calendar" aria-hidden="true"></i>
                        <?php
                        if ($vehicle['Vehicle']['Vehicle']['first_reg'] != '') {
                            $firstRegDate = new DateTime('01/' . $vehicle['Vehicle']['Vehicle']['first_reg']);
                            echo $firstRegDate->format('d/Y');
                        } else {
                            echo '-';
                        }
                        ?> Occasion</li>
                    <li><i class="fa fa-bullseye" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['hp']; ?> PS/<?php echo $vehicle['Vehicle']['Vehicle']['kw']; ?> KW</li>
                    <li><i class="fa fa-tachometer " aria-hidden="true"></i><?php echo intval($vehicle['Vehicle']['Vehicle']['kilometers']); ?> km</li>
                    <li><i class="fa fa-gear" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['gear']; ?></li>
                    <li><i class="fa fa-fire" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['fuel'] . " " . $vehicle['Vehicle']['Vehicle']['type']; ?></li>
                    <li><i class="fa fa-futbol-o" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['wheel_drive'] . ' ' . __('wheel drive'); ?></li>

                    <li><i class="fa fa-building" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['doors'] . ' ' . __('doors'); ?></li>
                    <li><i class="fa fa-key" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['no_of_keys'] . ' ' . __('key(s)'); ?></li>
                    <li><i class="fa fa-wheelchair" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['no_of_seats'] . ' ' . __('seats'); ?></li>
                    <li><i class="fa fa-registered" aria-hidden="true"></i><?php echo $vehicle['Vehicle']['Vehicle']['reg_no']; ?></li>
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


        <div id="recent_offers" class="row">
            
        </div>
        
            
    </div>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo AuthComponent::User('id'); ?>" />
    <input type="hidden" name="vehicle_id" id="vehicle_id" value="<?php echo $vehicle['Vehicle']['Vehicle']['id']; ?>" />
    <input type="hidden" name="is_favourite" id="is_favourite" value="<?php echo (isset($vehicle['Vehicle']['VehicleFavourite'][0]['is_favourite']) && $vehicle['Vehicle']['VehicleFavourite'][0]['is_favourite'] != '') ? $vehicle['Vehicle']['VehicleFavourite'][0]['is_favourite'] : '' ?>"/>
</section>
<script>
    //$("#img_01").elevateZoom({gallery: 'gallery_01', cursor: 'pointer', galleryActiveClass: 'active', imageCrossfade: true, loadingIcon: ''});

    $(document).ready(function () {
        
//        $('#auctionBid').change(function(){
//           if( $(this).val() == 'custom' ){
//               $('.custom-increase-with').show();
//           } else {
//               $('.custom-increase-with').hide();
//               $('.custom-increase-with').val('');
//           }
//        });

        $('.buyNow').click(function () {
            $.ajax({
                url: siteUrl + "/Users/sellCarToUser",
                method: 'post',
                data: { vehicle_id: $(this).attr('data-id'), purchase_type: 'buy_now'},
                success: function (res) {
                   
                    $.ajax({
                        url: siteUrl + "/Vehicles/getRecentOffers",
                        method: 'get',
                        data: { vehicle_id: $('#vehicle_id').val()},
                        success: function (result) {
                            $("#recent_offers").html(result);
                        }
                    });
                    
                    if (res == 'emailsuccess') {
                        $('#pricebox').remove();
                        alertify.notify('Car has been purchased successfully.', 'success');
                        return false;
                    } else if (res == 'success') {
                        $('#pricebox').remove();
                        alertify.notify('Car has been purchased successfully.', 'success');
                        return false;
                    } else if (res == 'fail') {
                        alertify.notify('We are facing issue to execute your request. Please try again.', 'fail');
                        return false;
                    } else {
                        return false;
                    }
                    
                }
            });
        });
        
        $.ajax({
            url: siteUrl + "/Vehicles/getRecentOffers",
            method: 'get',
            data: { vehicle_id: $('#vehicle_id').val()},
            success: function (result) {
                $("#recent_offers").html(result);
            }
        });

        $('.fancybox').fancybox();

        /*$(".show-more-button").click(function () {
            if ($(this).hasClass("less")) {
                $(this).html('less...');
                $(this).removeClass("less").addClass('more');
                $('.less-view').show();
            } else {
                $(this).removeClass('more').addClass("less");
                $(this).html('more...');
                $('.less-view').hide();
            }

            return false;
        });*/

//        $(".show-more-button").toggle(function(){
//            $(this).text("less..");
//            $(".less-view").show();    
//        }, function(){
//            $(this).text("more..");
//            $(".less-view").hide();    
//        });
    });

    //pass the images to Fancybox
//    $("#img_01").bind("click", function (e) {
//        var ez = $('#img_01').data('elevateZoom');
//        $.fancybox(ez.getGalleryList());
//        return false;
//    });



//    $('#gallery_01').slimscroll({
//        size: '5px',
//        height: '170px',
//    });

    $('#counterAuction').countdown(Date.parse($('#auction_ovr_tym').val()), {elapse: true}).on('update.countdown', function (event) {
        var $this = $(this);
        if (event.elapsed) {
            location.href = siteUrl;
        } else {
            $this.html(event.strftime('<div class="Hour"><h6 id="auctionHours">%I<span>:</span></h6><p><?php echo __('Hours'); ?></p></div><div class="Minutes"><h6 id="auctionMins">%M <span>:</span></h6><p><?php echo __('Minutes'); ?></p></div><div class="Seconds"><h6 id="auctionSecs">%S </h6><p><?php echo __('Seconds'); ?></p></div>'));
        }
    });

</script>
