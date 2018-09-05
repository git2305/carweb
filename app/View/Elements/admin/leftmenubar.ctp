<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

                <li class="text-muted menu-title">Navigation</li>

                <li class="has_sub">
                    <a href="<?= BASE_URL . '/admin/Users/dashboard' ?>" class="waves-effect waves-light active"><i class="md md-home"></i> <span><?php echo __('Dashboard'); ?></span> </a>
                </li>

                <li class="text-muted menu-title">Extra</li>

                <li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-account-box"></i><span><?php echo __('Manage Users');?></span></a>
                    <ul class="list-unstyled">
                        <li>
                            <?php echo $this->Html->link('All Users', array('controller' => 'Users', 'action' => 'listing')); ?>
                        </li>
                    </ul>
                </li>

                <!--<li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-palette"></i> <span> UI Kit </span> </a>
                    <ul class="list-unstyled">
                        <li><a href="ui-buttons.html">Buttons</a></li>
                        <li><a href="ui-panels.html">Panels</a></li>
                    </ul>
                </li>-->

                <!--<li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-invert-colors-on"></i><span class="label label-primary pull-right">8</span><span> Components </span> </a>
                    <ul class="list-unstyled">
                        <li><a href="components-grid.html">Grid</a></li>
                        <li><a href="components-widgets.html">Widgets</a></li>
                    </ul>
                </li>-->

                <li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-polymer"></i><span><?php echo __('Manage Vehicles'); ?></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="#"><?php echo __('Add New Car');?></a></li>
                        <li><?= $this->Html->link('Manage Vehicles', array('controller' => 'Vehicles', 'action' => 'listing')); ?></li>
<!--                        <li><?php //echo $this->Html->link('Manage Category', array('controller' => 'Vehicles', 'action' => 'vehicleCategory')); ?></li>-->
                        <li><?= $this->Html->link('Sold Vehicles', array('controller' => 'Vehicles', 'action' => 'soldvehicles')); ?></li>
                        <li><?= $this->Html->link('Purchased Vehicles', array('controller' => 'Vehicles', 'action' => 'purchasedvehicles')); ?></li>
                        <li><?= $this->Html->link('Auction Vehicles', array('controller' => 'Vehicles', 'action' => 'auctionvehicles')); ?></li>

                    </ul>
                </li>
                
                <li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-menu"></i><span><?php echo __('Manage Companies'); ?></span> </a>
                    <ul class="list-unstyled">
                        <li><?= $this->Html->link('Add Company', array('controller' => 'Companies', 'action' => 'add')); ?></a></li>
                        <li><?= $this->Html->link('Manage Companies', array('controller' => 'Companies', 'action' => 'listing')); ?></a></li>
                    </ul>
                </li>
                
                <li class="has_sub">
                    <a href="<?php echo BASE_URL . '/admin/EmailTemplates/index'; ?>" class="waves-effect waves-light"><i class="md md-mail"></i><span><?php echo __('Email Management'); ?></span> </a>
                </li>
                
                

                <!--<li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-now-widgets"></i><span> Forms </span></a>
                    <ul class="list-unstyled">
                        <li><a href="form-elements.html">General Elements</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-view-list"></i><span>Tables </span></a>
                    <ul class="list-unstyled">
                        <li><a href="tables-tablesaw.html">Tablesaw</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-poll"></i><span class="label label-default pull-right">11</span><span> Charts </span></a>
                    <ul class="list-unstyled">
                        <li><a href="chart-sparkline.html">Sparkline charts</a></li>
                        <li><a href="chart-radial.html">Radial charts</a></li>
                        <li><a href="chart-other.html">Other Chart</a></li>
                        <li><a href="chart-ricksaw.html">Ricksaw Chart</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-place"></i><span> Maps </span></a>
                    <ul class="list-unstyled">
                        <li><a href="map-google.html"> Google Map</a></li>
                        <li><a href="map-vector.html"> Vector Map</a></li>
                    </ul>
                </li>-->

                <li class="text-muted menu-title"><?php echo __('More');?></li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect waves-light"><i class="md md-pages"></i><span><?php echo __('CMS Pages');?></span></a>
                    <ul class="list-unstyled">
                        <li><?= $this->Html->link('Manage Pages', array('controller' => 'CmsPages', 'action' => 'allPages')); ?></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-redeem"></i><span><?php echo __('Extras');?></span></a>
                    <ul class="list-unstyled">
                        <li><a href="#">Profile</a></li>
                        <li><a href="#">Timeline</a></li>
                    </ul>
                </li>

                <!--<li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-apps"></i><span class="label label-pink pull-right">3</span><span> Apps </span></a>
                    <ul class="list-unstyled">
                        <li><a href="apps-inbox.html"> Email</a></li>
                        <li><a href="apps-calendar.html"> Calendar</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-view-quilt"></i><span> Layouts </span></a>
                    <ul class="list-unstyled">
                        <li><a href="layout-header_2.html"> Header style</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect waves-light"><i class="md md-share"></i><span>Multi Level </span></a>
                    <ul>
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><span>Menu Level 1.1</span> </a>
                            <ul style="">
                                <li><a href="javascript:void(0);"><span>Menu Level 2.1</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><span>Menu Level 1.2</span></a>
                        </li>
                    </ul>
                </li>-->

                <!--<li class="text-muted menu-title">Extra</li>

                <li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-account-box"></i><span> Crm </span></a>
                    <ul class="list-unstyled">
                        <li><a href="crm-dashboard.html"> Dashboard </a></li>
                        <li><a href="crm-customers.html"> Customers </a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect waves-light"><i class="md md-add-shopping-cart"></i><span> eCommerce </span></a>
                    <ul class="list-unstyled">
                        <li><a href="ecommerce-dashboard.html"> Dashboard</a></li>
                        <li><a href="ecommerce-sellers.html"> Sellers</a></li>
                    </ul>
                </li> -->

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
