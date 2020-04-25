<section class="mid-section">
    <div class="container vehicle-listing">
        <div class="title-wrap">
            <div class="right-title-block">
                <div class="select-box">
                    <label>
                        <select class="sortbyopt">
                            <option selected value=""><?php echo __('Sort by'); ?> </option>
                            <option value="YLH"><?php echo __('YEAR OLD-NEW'); ?></option>
                            <option value="YHL"><?php echo __('YEAR NEW-OLD'); ?></option>
                            <option value="PLH"><?php echo __('PRICE LOW-HIGH'); ?></option>
                            <option value="PHL"><?php echo __('PRICE HIGH-LOW'); ?></option>
                            <option value="KLH"><?php echo __('ODOMETER LOW-HIGH'); ?></option>
                            <option value="KHL"><?php echo __('ODOMETER HIGH-LOW'); ?></option>
                        </select>
                    </label>
                </div>
                <?php if ($this->Session->read('Auth.User.id')) { ?>
                    <div class="checkbox-page"> 
                        <input id="checkbox1" type="checkbox" name="checkbox" value="1"><label for="checkbox1"><?php echo __('Only show shortlisted'); ?> </label>
                        <input id="chkFavourites" type="checkbox" name="checkbox" value="1"><label for="chkFavourites"><?php echo __('Only show favourites'); ?></label>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?php if ($this->request->params['action'] == 'sellVehicles' && $this->request->params['controller'] == 'Vehicles') { ?>
            <div class="form-collapsible">
                <form>
                    <div class="form-group form-group-col3 form-group-make">
                        <select id="vehicleMake" class="form-control">
                            <option value=""><?php echo __('Select Make'); ?></option>
                        </select>
                    </div>
                    <div class="form-group form-group-col3 form-group-model">
                        <select id="vehicleModel" class="form-control">
                            <option value=""><?php echo __('Select Model'); ?></option>
                        </select>
                    </div>
                    <div class="form-group form-group-col3 form-group-region">
                        <?php echo $this->Form->input('vehicle_regions', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control', 'empty' => __('Select Region'), 'type' => 'select', 'options' => $region_data)); ?>
                    </div>
                    <div class="form-group form-group-col2 form-group-minyear">
                        
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
                    <div class="form-group form-group-col2 form-group-maxyear">
                        <?php echo $this->Form->input('max_year', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control', 'empty' => __('Max Year'), 'type' => 'select', 'options' => $maxArr)); ?>
                    </div>
                    <div class="form-buttonset"> <button type="button" class="btn btn-default btnQuickSearch"><?php echo __("Search"); ?></button></div>
                </form>
            </div>
        <?php } ?>
        <div class="product-listing-wrap">
            <div id="auctionData"></div>
            <div id="clockdiv"></div>
        </div>
    </div>
</section>