<!-- ================MID section=====================-->
<!--<script src='https://maps.googleapis.com/maps/api/js?v=3.exp'></script><script type='text/javascript'>function init_map(){var myOptions = {zoom:10,center:new google.maps.LatLng(47.32917387666735,8.705989571874957),mapTypeId: google.maps.MapTypeId.ROADMAP};map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(47.32917387666735,8.705989571874957)});infowindow = new google.maps.InfoWindow({content:'<strong>Eintauschfahrzeuge24.ch GmbH</strong><br>Oberzelgstrasse 3, 8618 Oetwil am See ZH<br>'});google.maps.event.addListener(marker, 'click', function(){infowindow.open(map,marker);});infowindow.open(map,marker);}google.maps.event.addDomListener(window, 'load', init_map);</script>-->
<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: new google.maps.LatLng(47.32917387666735, 8.705989571874957)
        });
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(47.32917387666735, 8.705989571874957),
            map: map
        });

        infowindow = new google.maps.InfoWindow({content: '<strong>Eintauschfahrzeuge24.ch GmbH</strong><br>Oberzelgstrasse 3, 8618 Oetwil am See ZH<br>'});
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
        });
        infowindow.open(map, marker);

    }


</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCo-LdUdCD70EBY0kHK-JMQkXAtbHEDqUY&callback=initMap">
</script>

<section class="mid-section">

    <div class="container">
        <h1 class="main-title"><?php echo __("Contact"); ?> <span><?php echo __("Us"); ?></span></h1>
    </div>
    <div class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 address-info">
                    <div class="address-inner">
                        <ul class="address-list">
                            <li class="contact-add"><b>Eintauschfahrzeuge24.ch GmbH </b>Oberzelgstrasse 3<br/> 8618 Oetwil am See ZH</li>
                            <li class="contact-phone"><a href="tel:+0 55 553 40 00">+0 55 553 40 00</a></li>
                            <li class="contact-fax">+0 55 553 40 00</li>
                            <li class="contact-email"><a href="mailto:info@ef24.ch">info@ef24.ch</a></li>
                            <li class="contact-link"><a target="_blank" href="<?php echo SITE_URL; ?>"><?php echo SITE_URL; ?></a></li>
                        </ul>
                        <a href="http://maps.google.de/?daddr=Oberzelgstrasse 3, 8618 Oetwil am See ZH" target="_blank" class="btn btn-primary"><?php echo __('Calculate route'); ?></a>
                    </div>
                </div>

                <div class="col-md-6 map-wrap">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="registra-form">
            <form method="POST" id="reg_form">
                <div class="form-group row">
                    <div class="col-md-3 label-radio-wrap"> <label><?= __("I am") ?></label></div>
                    <div class="col-md-9">
                        <?php
                            echo $this->Form->input('user_type', array('type' => 'radio', 'options' => array('private_user' => __('Private User'), 'car_dealer' => __('Car Dealer')), 'div' => false, 'label' => true, 'class' => '', 'legend' => false, 'default' => 'private_user',
                                'before' => '<div class="radio-wrap">',
                                'after' => '</div>',
                                'separator' => '</div><div class="radio-wrap">')
                            );
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Marketplace") ?></label></div>
                    <div class="col-md-9">
                        <?php
                        $marketPlaceOptions = array(
                            'Used car market' => __('Used car market'),
                            'New cars' => __('New cars'),
                            'Parts & Accessory' => __('Parts & Accessory'),
                            'Motobikes' => __('Motobikes'),
                            'Commercial vehicles' => __('Commercial vehicles'),
                        );
                        echo $this->Form->input('marketplace', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => __('Please Select'), 'options' => $marketPlaceOptions));
                        ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("I have a") ?></label></div>
                    <div class="col-md-9">
                        <?php
                        $typeOptions = array(
                            'Question' => __('Question'),
                            'Commendation' => __('Commendation'),
                            'Complaint' => __('Complaint'),
                            'Improvement' => __('Improvement'),
                        );
                        echo $this->Form->input('type', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => __('Please Select'), 'options' => $typeOptions));
                        ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Topic") ?></label></div>
                    <div class="col-md-9">
                        <?php
                        $topicOptions = array(
                            'New car' => __('New car'),
                            'Register' => __('Register'),
                            'Add Picture' => __('Add Picture'),
                            'Change car offer' => __('Change car offer'),
                            'Login' => __('Login'),
                            'Search' => __('Search'),
                            'Payed services' => __('Payed services'),
                            'Check car offer' => __('Check car offer'),
                            'Misc' => __('Misc'),
                        );
                        echo $this->Form->input('topic', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => __('Please Select'), 'options' => $topicOptions));
                        ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("My Request") ?></label></div>
                    <div class="col-md-9">
                        <?php echo $this->Form->input('my_request', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Form of address*") ?></label></div>
                    <div class="col-md-9">
                        <?php echo $this->Form->input('prefix_name', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'empty' => __('Please Select'), 'options' => array('Mrs' => 'Mrs', 'Mr' => 'Mr'))); ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("First name*") ?></label></div>
                    <div class="col-md-9">
                        <?php echo $this->Form->input('first_name', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("First Name"))); ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Surname*") ?></label></div>
                    <div class="col-md-9">
                        <?php echo $this->Form->input('last_name', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'placeholder' => __("Last Name"))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __("Email*") ?></label></div>
                    <div class="col-md-9">
                        <?php echo $this->Form->input('email', array('div' => false, 'label' => false, 'class' => 'validate[required,custom[email]] form-control', 'placeholder' => __("Email"))); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-9 col-md-offset-3">
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="$('#reg_form').submit();"><?= __("Send Request") ?></a>
                    </div>
                </div>
                
            </form>
        </div>


    </div>

</section>



<script type = 'text/javascript'>

    $(document).ready(function () {

        $("#reg_form").validationEngine();

    });

</script>