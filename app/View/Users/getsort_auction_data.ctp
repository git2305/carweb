<style>
#img_11{position:relative !important;}
</style>
<?php
if (isset($auction_data) && !empty($auction_data)) {
    foreach ($auction_data as $data) {
        ?>
        <div class="thumbnail vehicleSell_<?php echo $data['Vehicle']['id']; ?>">
            <div class="col-car-1"> 
                <h5><?php echo __('Time'); ?> <span id="expireTime_<?php echo $data['Vehicle']['id']; ?>" data-countdown="<?php echo $data['Vehicle']['auction_ovr_tym']; ?>" data-vehicleid="<?php echo $data['Vehicle']['id']; ?>" class="expireTime">00:01</span></h5>
            </div>
            <div class="col-car-2">
                <!-- <img src="<?php echo BASE_URL; ?>/img/front/car.png"> -->
                <?php $vehicle_img = $this->requestAction('/Users/findVehicleImage/' . base64_encode($data['Vehicle']['id'])); ?>
                <img class="imgzoom" id="img_11" src="<?php echo BASE_URL . '/img/vehicle/thumb/' . $vehicle_img; ?>" data-zoom-image="<?php echo BASE_URL . '/img/vehicle/orignal/' . $vehicle_img; ?>">
                <h6><?php echo $data['Vehicle']['brand'] . " - " . $data['Vehicle']['model']; ?></h6>
            </div>
            <div class="col-car-3">
                <h4><?php echo $data['Vehicle']['brand'] . " - " . $data['Vehicle']['model']; ?></h4>
                <p><span><?php echo __('Power'); ?> <?php echo $data['Vehicle']['hp'] . 'HP'; ?></span> <span><?php echo $data['Vehicle']['kw'] . 'KW'; ?></span> <span><?php echo $data['Vehicle']['other_condition']; ?> </span> <span><?php echo "Exterior Color: " . $data['Vehicle']['exterior_color']; ?> </span></p>
                <div class="icon-like">
					<?php 
					foreach($data['AuctionBid'] as $k => $v){
						if($v['user_id'] == AuthComponent::User('id')){
							if(in_array($v['biding_amount'],$bidArr)){ ?>
							<a href="#" class="like active"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a>
						<?php } else { ?>	
							<a href="#" class="dislike"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a>
						<?php } ?>
					<?php } } ?>
				</div>
			</div>
            <div class="col-car-4">
                <a href="#" class="btn-Preis"><?php echo __('Price'); ?> $<?php echo $data['Vehicle']['min_auction_price']; ?></a>&nbsp;  &nbsp;
                <a href="<?php echo BASE_URL. '/Vehicles/vehicleDetail/' . base64_encode($data['Vehicle']['id']); ?>" class="btn-Preis"><?php echo __('Offer'); ?></a>
            </div>
        </div>
    <?php }
} else { ?>
	<div  class="thumbnail text-center">
		<h4><?php echo __('No Record Found');?></h4>
	</div>
<?php } ?>
<script>
$(".imgzoom").elevateZoom({gallery:'gallery_01', cursor: 'pointer', galleryActiveClass: 'active', imageCrossfade: true, loadingIcon: ''}); 
</script>
