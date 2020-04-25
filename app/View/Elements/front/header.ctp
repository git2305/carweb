<!-- Fetch Header -->
<div class="header">
    <div class="topheader">
        <div class="container">
            <div class="left-logo"><a href="<?php echo SITE_URL; ?>"><img src="<?php echo BASE_URL; ?>/img/front/logo.png"></a></div>
            <div class="right-search-log">
                <div class="login-lang-wrap">
                    <div class="login-welcom">
                        <nav>
                            <div class="top-menu">
                                <ul class="nav navbar-nav">
                                    <?php if (AuthComponent::User('id')) { ?>
                                        <li class="dropdown">
                                            <?php echo $this->Html->link(__("My Corner") . ' <span class="caret"></span>', array(), array('class' => 'dropdown-toggle', 'role' => 'button', 'aria-haspopup' => true, 'aria-expanded' => false, 'data-toggle' => 'dropdown', 'tabindex' => -1, 'escape' => false)); ?>
                                            <ul class="dropdown-menu">
                                                <li class="menu-header-title"><?php echo __('My Vehicles'); ?></li>
                                                <li><a href="<?php echo SITE_URL; ?>/vehicles/soldVehicles"><?php echo __('Vehicles Sold'); ?></a></li>
                                                <li><a href="<?php echo SITE_URL; ?>/vehicles/purchasedVehicles"><?php echo __('Vehicles Purchased'); ?></a></li>
                                                <li><a href="<?php echo SITE_URL; ?>/vehicles/auctionVehicles"><?php echo __('Vehiclea in Auktion'); ?></a></li>
                                                <li><a href="<?php echo SITE_URL; ?>/vehicles/favouriteVehicles"><?php echo __('My Favourite Vehicles'); ?></a></li>
                                                <li role="separator" class="divider"></li>
                                                
                                                <?php if( AuthComponent::User('parent_id') == 0  ){ ?>
                                                    <li class="menu-header-title"><?php echo __('Sub Users'); ?></li>
                                                    <li><?php echo $this->Html->link(__("Add Sub User"), array('controller' => 'Users', 'action' => 'addSubUser', base64_encode(AuthComponent::User('id'))), array('tabindex' => -1)); ?></li>
                                                    <li><?php echo $this->Html->link(__("Sub Users Listing"), array('controller' => 'Users', 'action' => 'subUsers', base64_encode(AuthComponent::User('id')))); ?></li>
                                                    <li role="separator" class="divider"></li>
                                                <?php } ?>
                                                <?php if( AuthComponent::User('parent_id') < 1  ){ ?>
                                                    <li class="menu-header-title"><?php echo __('Profile'); ?></li>
                                                    <li><?php echo $this->Html->link(__("Change Profile Data"), array('controller' => 'Users', 'action' => 'profile'), array('tabindex' => -1)); ?></li>
                                                    <li><?php echo $this->Html->link(__("Change Password"), array('controller' => 'Users', 'action' => 'changePassword')); ?></li>
                                                    <li><?= $this->Form->postLink(__('Delete Account'), ['action' => 'delete', base64_encode(AuthComponent::User('id'))], ['escape' => false, 'confirm' => __('Are you sure you want to delete account?')]) ?></li>
                                                    <li role="separator" class="divider"></li>
                                                <?php } ?>
                                                
                                                
                                                <li class="menu-header-title"><?php echo __('Settings'); ?></li>
                                                <li><?php echo $this->Html->link(__("Email Preference"), array('controller' => 'Users', 'action' => 'emailPreference')); ?></li>
                                                <li role="separator" class="divider"></li>
                                                <li><?php echo $this->Html->link(__("Invoices"), array('controller' => 'Vehicles', 'action' => 'invoices')); ?></li>
                                            </ul>
                                        </li>
                                        <li><?php echo $this->Html->link(__("Sign out"), array('controller' => 'Users', 'action' => 'logout')); ?></li>
                                    <?php } else { ?>
                                        <li><?php echo $this->Html->link(__("Sign up"), array('controller' => 'Users', 'action' => 'register'), array('tabindex' => -1)); ?></li>
                                        <li><?php echo $this->Html->link(__("Sign in"), array('controller' => 'Users', 'action' => 'login'), array('tabindex' => -1)); ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div class="lang-wrap">
                        <?php
                        echo $this->Form->create(null, [ 'id' => 'language_form', 'name' => 'language-form',
                            'url' => ['controller' => 'App', 'action' => 'changeLang']
                        ]);
                        ?>
                        <select id="change-language" name="language" class="form-control input-small">
                            <option <?php if ($language == 'eng') { ?> selected="selected" <?php } ?>  value="eng">English</option>
                            <option <?php if ($language == 'deu') { ?> selected="selected" <?php } ?> value="deu">German</option>
                            <option <?php if ($language == 'fra') { ?> selected="selected" <?php } ?> value="fra">French</option>
                            <option <?php if ($language == 'ita') { ?> selected="selected" <?php } ?> value="ita">Italian</option>
                        </select>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
<!--                <div class="advertisement-btn">
                    <div class="btn-right"><a href="<?= BASE_URL . '/Vehicles/addAdvertisement' ?>"><i class="car-icon-pls"></i> <?php echo __("Add advertisement"); ?> </a></div>
                </div>-->
                <div class="app-icons" align="center">
                    <a href="#"><img src="<?php echo BASE_URL; ?>/img/google-play-store.png" /></a>
                    <a href="#"><img src="<?php echo BASE_URL; ?>/img/itune-store.png" /></a>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar-wrap">
        <div class="container">
            <nav id="mainNav" class="navbar navbar-default"> 
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header page-scroll">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <span class="toggle-line"></span>
                                <span class="toggle-line"></span>
                                <span class="toggle-line"></span>
                        </button>
                        <div class="call-icon phone-show"><i class="icon"></i> +19 123-365-2525</div>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li> <?php echo $this->Html->link(__("Home"), array('controller' => 'Users', 'action' => 'index'), array('tabindex' => -1)); ?> </li>
                        <li> <?php echo $this->Html->link(__("How it works"), array('controller' => '', 'action' => '/', $cmsSlugs[4])); ?> </li>
                        <li> <?php echo $this->Html->link(__("Registration"), array('controller' => 'Users', 'action' => 'register'), array('tabindex' => -1)); ?></li>
                        <li> <a href="<?= BASE_URL . '/Vehicles/addAdvertisement' ?>"><img src="<?php echo BASE_URL; ?>/img/front/car-plus-icon.png" /> <?php echo __("Add advertisement"); ?> </a></li>
                        <li> <?php echo $this->Html->link(__("About Us"), array('controller' => '', 'action' => '/', $cmsSlugs[1])); ?> </li>
                        <li> <?php echo $this->Html->link(__("Contact Us"), array('controller' => 'Users', 'action' => 'contactUs')); ?> </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>

<div class="container">
    <?php echo $this->Session->flash(); ?>
</div>

<script>
    $(document).ready(function () {
        $('#change-language').on('change', function () {
            document.forms['language-form'].submit();
        });
    });
</script>
