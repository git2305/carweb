<!-- Fetch Footer -->
<footer>
    <div class="container">
        <div class="row">

            <div class="col-md-4">
                <h5><?php echo __("Eintauschfahrzeuge24.ch GmbH"); ?></h5>
                <ul class="contact-info">
                    <li class="address"><?php echo __("Oberzelgstrasse 3"); ?> <br/><?php echo __("8618 Oetwil am See ZH"); ?></li>
                    <li class="phone"><a href="tel:+0-55-553-40-00"><?php echo __("+0 55 553 40 00"); ?></a></li>
                    <li class="email"><a href="mailto:info@ef24.ch"><?php echo __("info@ef24.ch"); ?></a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h5><?php echo __("Useful Links"); ?> </h5>
                <ul class="menu-link">
                    <li><?php echo $this->Html->link(__("Jobs"),array( 'controller' => '', 'action' => '/jobs' )); ?></li>
                    <li><?php echo $this->Html->link(__("Imprint"),array( 'controller' => '', 'action' => '/imprint' )); ?></li>
                    <li><?php echo $this->Html->link(__("Prices"),array( 'controller' => '', 'action' => '/prices' )); ?></li>
                    <li><?php echo $this->Html->link(__("AGB"),array( 'controller' => '', 'action' => '/agb' )); ?></li>
                </ul>
            </div>

            <div class="col-md-2">
                <h5><?php echo __("Our Company"); ?></h5>
                <ul class="menu-link">
                    <li><?php echo $this->Html->link(__("About Us"),array( 'controller' => '', 'action' => '/about-us')); ?></li>
                    <li><?php echo $this->Html->link(__("FAQ"), array( 'controller' => '', 'action' => '/faq' )); ?></li>
                    <li><?php echo $this->Html->link(__("Instructions"),array( 'controller' => '', 'action' => '/instruction')); ?></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5><?php echo __("Our Partners"); ?> </h5>
                <ul class="footer-sicher">
                    <li><a href="#"><img src="<?php echo BASE_URL; ?>/img/front/l.jpg"></a></li>
                    <li><a href="#"><img src="<?php echo BASE_URL; ?>/img/front/la.jpg"></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer><!-- Finished Footer -->