<style>
    #img_11{position:relative !important;}
</style>

<section class="search-results search-results-list">
    <?php
    if (isset($auction_data) && !empty($auction_data)) {
        foreach ($auction_data as $data) {
    ?>
        <div class="vehicle-item panel panel-default vehicleSell_<?php echo $data['Vehicle']['id']; ?>">
        <header class="vehicle-type"><div class="clearfix"></div> </header>
        <div class="vehicle-data panel-body">
            <div class="vehicle-thumbnail col-md-3">
                <div class="banner-wrapper">
                    <a href="<?php echo BASE_URL . '/Vehicles/vehicleDetail/' . base64_encode($data['Vehicle']['id']); ?>" title="Used Abarth 500 Esseesse, Bentley, 2013 Abarth 500 Esseesse Hatchback.">
                        <img class="imgzoom" id="img_11" src="<?php echo BASE_URL . '/img/vehicle/thumb/' . $data['VehicleImage']['file_name']; ?>" data-zoom-image="<?php echo BASE_URL . '/img/vehicle/orignal/' . $data['VehicleImage']['file_name']; ?>" itemprop="image" class="non-lazy" error-depth="0">
                    </a>
                    <h5 class="timer-wrap"><span id="expireTime_<?php echo $data['Vehicle']['id']; ?>" data-countdown="<?php echo $data['Vehicle']['auction_ovr_tym']; ?>" data-vehicleid="<?php echo $data['Vehicle']['id']; ?>" class="expireTime">00:01</span></h5>
                    <div class="clearfix"></div>
                </div>
            </div>
            
            <div class="vehicle-info col-md-9">
                <div class="col-sm-12 col-xs-11 vehicle_title_list" style="text-align:left"> <a href="<?php echo BASE_URL . '/Vehicles/vehicleDetail/' . base64_encode($data['Vehicle']['id']); ?>" title="Used Abarth 500 Esseesse, Bentley, 2013 Abarth 500 Esseesse Hatchback." id="stock_list_veh_title">
                        <h3 class="title">
                            <?php
                                $vehicleName = $data['Vehicle']['brand'];
                                
                                if( $data['Vehicle']['model'] != '' ){
                                    $vehicleName .= ' '.$data['Vehicle']['model'];
                                }
                                
                                if( $data['Vehicle']['type'] != '' ){
                                    $vehicleName .= ' / '.$data['Vehicle']['type'];
                                }
                                
                                
                                if( isset($data['Vehicle']['first_reg']) && $data['Vehicle']['first_reg'] != '' ){
                                    $makeYearArr = explode('/', $data['Vehicle']['first_reg']);
                                    if( isset($makeYearArr[1]) ){
                                        $vehicleName .= ' / '. $makeYearArr[1];
                                    }
                                }
                                
                                if( $data['Vehicle']['kilometers'] != '' && $data['Vehicle']['kilometers'] > 0){
                                    $vehicleName .= ' / '. number_format($data['Vehicle']['kilometers'] , 2). ' KM';
                                }
                                
                                echo $vehicleName;
                            ?>
                        </h3>
                    </a>
                </div>
                <div class="vehicle-price pricing-container ">
                    <div class="price">
                        <div class="col-md-8">
                            <h2 class="price_stocklist stocklist-price omega" itemprop="price">CHF 
                                <?php
                                    $bidingPriceArray = [];
                                    if( !empty($data['AuctionBid']) ){
                                        foreach ($data['AuctionBid'] as $k => $v) {
                                            $bidingPriceArray[] = $v['biding_amount'];
                                        }
                                    }
                                
                                    echo (!empty($bidingPriceArray)) ? max($bidingPriceArray) : $data['Vehicle']['min_auction_price'];
                                ?>
                                <b class="pricing-asterix">*</b> </h2>
                            <p>
                                <i class="fa fa-map-marker"></i>
                                <?php echo isset($regions[$data['Vehicle']['vehicle_regions']]) ? $regions[$data['Vehicle']['vehicle_regions']] : '';?>
                                <span class="actions-container">
                                    <?php
                                        foreach ($data['AuctionBid'] as $k => $v) {
                                            if ($v['user_id'] == AuthComponent::User('id')) {
                                                if (in_array($v['biding_amount'], $bidArr)) {
                                                    ?>
                                                    <a href="javascript:void(0);" class="inline-block mar-left-10 dislike active"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0);" class="inline-block mar-left-10 like active"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a>
                                                <?php } ?>
                                            <?php }
                                        }
                                    ?>
                                    
                                    
                                    <?php
                                        if( $this->Session->read('Auth.User.id') ){
                                            if( isset($data['MyFavourite']['is_favourite']) && $data['MyFavourite']['is_favourite'] == 1 ){ ?>
                                            <a data-fav-id="<?php echo $data['MyFavourite']['is_favourite']; ?>" data-id="<?php echo $data['Vehicle']['id']; ?>" href="javascript:void(0);" class="inline-block mar-left-10 staricon add-favourite inline"><i class="fa fa-heart"></i></a>                    
                                        <?php } else { ?>
                                        <a data-fav-id="0" data-id="<?php echo $data['Vehicle']['id']; ?>" href="javascript:void(0);" class="inline-block mar-left-10 staricon add-favourite inline"><i class="fa fa-heart-o"></i></a>
                                    <?php } }?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4 mar-top-5">
                            <a class="btn btn-block btn-Preis <?php if($data['Vehicle']['id'] == 1){ ?> vehicle-sold-button <?php } ?>" href="<?php echo BASE_URL . '/Vehicles/vehicleDetail/' . base64_encode($data['Vehicle']['id']); ?>">
                                <span class="vehicle-sold hidden"><?php echo __('Sold');?></span>
                                <span class="vehicle-in-auction"><?php echo __('Offer'); ?></span>
                            </a>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
    <?php }
} else {
    ?>
        <div  class="thumbnail text-center">
            <h4><?php echo __('No Record Found'); ?></h4>
        </div>
<?php } ?>
    </section>
<script>
    $(".imgzoom").elevateZoom({gallery: 'gallery_01', cursor: 'pointer', galleryActiveClass: 'active', imageCrossfade: true, loadingIcon: ''});
    $('.add-favourite').click(function(){
        
		var user_id = '<?php echo $this->Session->read('Auth.User.id');  ?>';
		var vehicle_id = $(this).attr('data-id');
                var favVal = $(this).attr('data-fav-id');
                
		if( parseInt(favVal)  == '0' ){
                    var is_favourite = '1'; 
		}else{
                    var is_favourite = '0';
		}
		$.ajax({
                    url: siteUrl+'/Vehicles/addtofavourite',
                    type: 'post', // performing a POST request
                    data: {
                            user_id: user_id,
                            vehicle_id: vehicle_id,
                            is_favourite : is_favourite
                    },
                    success: function (result)
                    {
                        //alert(result);
                        if(result == 'success' || result == 'fav'){
                                $('.addtoFav i').removeClass('fa fa-heart').addClass('fa fa-heart-o');
                        }else{
                                $('.addtoFav i').removeClass('fa fa-heart-o').addClass('fa fa-heart');
                        }
                        
                        getAucionData($('.sortbyopt').val(), $('#checkbox1:checked').val(), '', $('#chkFavourites:checked').val(), $('#vehicleMake').val(),  $('#vehicleModel').val(), $('#vehicle_regions').val(), $('#min_year').val(), $('#max_year').val());
                    }
		});
	});
    
</script>
