<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<section class="mid-section">
    <div class="container">
        <form method="POST" id="step1-form" enctype="multipart/form-data">
            <?php echo $this->Form->hidden('Vehicle.id'); ?>
            <div class="tab-bar-all-pages">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="javascript:void(0);" aria-controls="settings" role="tab" data-toggle="tab"><?= __('Vehicle Data') ?></a></li>
                    <li role="presentation"><?php echo $this->Html->link( __('Documents & Pictures'), ['controller'=> 'Vehicles', 'action' => 'step2' ]); ?></li>
                    <li role="presentation"><?php echo $this->Html->link(__('Auction'), ['controller'=> 'Vehicles', 'action' => 'step3' ]); ?></a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">
                        <h2 class="tabs-title"><span> 1. </span><?= __('Vehicle Data') ?></h2>
                        <p><?= __('Fields marked with asterisk (*) are mandatory') ?></p>

                        <div class="row tabs-content-wrap">
                            <div class="col-md-6">

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Brand *') ?></label></div>
                                    <div class="col-md-9 ">
                                        <?php echo $this->Form->input('Vehicle.brand', array('value' => isset($vehicleData[$ltnId]['Brand']) ? $vehicleData[$ltnId]['Brand'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'placeholder' => __('Brand Name'), 'class' => 'validate[required] form-control')); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Model *') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.model', array('value' => isset($vehicleData[$ltnId]['Model']) ? $vehicleData[$ltnId]['Model'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'placeholder' => __('Model'), 'class' => 'validate[required] form-control')); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Type') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.type', array('value' => isset($vehicleData[$ltnId]['Type']) ? $vehicleData[$ltnId]['Type'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'placeholder' => __('Type'), 'class' => 'form-control')); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Body Type *') ?></label></div>
                                    <div class="col-md-9">
                                        <?php
                                        $bodyTypeOptions = array(
                                            'Limousine' => 'Limousine',
                                            'Kombi' => 'Kombi',
                                            'Cabrio' => 'Cabrio',
                                            'Coupe' => 'Coupe',
                                            'Bus' => 'Bus',
                                            'SUV / Offroader' => 'SUV',
                                            'Minivan' => 'Minivan',
                                            'Kleinwagen' => 'Kleinwagen',
                                            'Pickup' => 'Pickup'
                                        );
                                        echo $this->Form->input('Vehicle.body_type', array('value' => isset($vehicleData[$ltnId]['BodyTypeEx']) ? $vehicleData[$ltnId]['BodyTypeEx'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'empty' => __('Select Body Type'), 'class' => 'validate[required] form-control', 'type' => 'select', 'options' => $bodyTypeOptions));
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Doors *') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.doors', array('value' => isset($vehicleData[$ltnId]['DoorsNumber']) ? $vehicleData[$ltnId]['DoorsNumber'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'empty' => __('Please select'), 'class' => 'validate[required] form-control', 'type' => 'select', 'options' => array(2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7))); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Displacement') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.displacement', array('value' => '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'placeholder' => __('Displacement'), 'class' => 'form-control')); ?>
                                    </div>
                                </div>
                            </div>



                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Wheel Drive *') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.wheel_drive', array('value' => isset($vehicleData[$ltnId]['DoorsNumber']) ? $vehicleData[$ltnId]['DoorsNumber'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'empty' => __('Please select'), 'class' => 'validate[required] form-control', 'type' => 'select', 'options' => array('Front' => 'Front', 'Heck' => 'Heck', '4x4' => '4x4'))); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Gear *') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.gear', array('value' => isset($vehicleData[$ltnId]['GearBox']) ? $vehicleData[$ltnId]['GearBox'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'empty' => __('Please select'), 'class' => 'validate[required] form-control', 'type' => 'select', 'options' => array('Manuell' => 'Manuell', 'Automat' => 'Automat', 'A-sequent.' => 'A-sequent.', 'Halbautomat' => 'Halbautomat'))); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Fuel *') ?></label></div>
                                    <div class="col-md-9">
                                        <?php
                                        $fuelOptions = array(
                                            'Benzin' => 'Benzin',
                                            'Diesel' => 'Diesel',
                                            'Elektro' => 'Elektro',
                                            'Gas (CNG)' => 'Gas (CNG)',
                                            'Hybrid' => 'Hybrid',
                                            'Gas (LPG)' => 'Gas (LPG)',
                                            'Hybrid/Benzin' => 'Hybrid/Benzin',
                                            'Hybrid/Diesel' => 'Hybrid/Diesel',
                                        );

                                        echo $this->Form->input('Vehicle.fuel', array('value' => isset($vehicleData[$ltnId]['Fuel']) ? $vehicleData[$ltnId]['Fuel'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'empty' => __('Please select'), 'class' => 'validate[required] form-control', 'type' => 'select', 'options' => $fuelOptions));
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Performance *') ?></label></div>
                                    <div class="col-md-9">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <?php echo $this->Form->input('Vehicle.hp', array('value' => isset($vehicleData[$ltnId]['PowerHp']) ? $vehicleData[$ltnId]['PowerHp'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'placeholder' => __('HP'), 'class' => 'validate[required] form-control')); ?>
                                                    <span class="input-group-addon" id="basic-addon1"><?php echo __('PS'); ?></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <?php echo $this->Form->input('Vehicle.kw', array('value' => isset($vehicleData[$ltnId]['PowerKw']) ? $vehicleData[$ltnId]['PowerKw'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'placeholder' => __('KW'), 'class' => 'validate[required] form-control')); ?>
                                                    <span class="input-group-addon" id="basic-addon1"><?php echo __('KW'); ?></span>
                                                </div>
                                            </div>
                                        </div>



                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Number of Seats ') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.no_of_seats', array('value' => isset($vehicleData[$ltnId]['SeatsNumber']) ? $vehicleData[$ltnId]['SeatsNumber'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'empty' => __('Bitte wählen'), 'class' => 'form-control', 'type' => 'select', 'options' => array('' => __('Bitte wählen'), 1 => 1, 2 => 2, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9))); ?>
                                    </div>
                                </div>

                                <!--<div class="form-group">
                                 <div class="col-lg-3"> <label>Company</label></div>
                                  <div class="col-lg-9 ">  <textarea placeholder="Email" id="exampleInputEmail1" class="form-control" type="email"></textarea><p>(Min. 6, max. 8 characters)</p></div>
                                </div>-->

                            </div>
                        </div>

                        <h3><?php echo __('Further Information'); ?></h3>
                        <div class="row tabs-content-wrap">
                            <div class="col-lg-6">

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('1st Reg. *') ?></label></div>
                                    <div class="col-md-9 ">
                                        <?php echo $this->Form->input('Vehicle.first_reg', array('value' => isset($vehicleData[$ltnId]['FirstRegistration']) ? $vehicleData[$ltnId]['FirstRegistration'] : '','div' => FALSE, 'label' => FALSE, 'class' => 'validate[required] form-control', 'placeholder' => __('1st Registration'), 'type' => 'text', 'required' => true)); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Kilometers *') ?></label></div>
                                    <div class="col-md-9 ">
                                        <?php echo $this->Form->input('Vehicle.kilometers', array('div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'placeholder' => __('Kilometers'), 'class' => 'validate[required] form-control')); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Exterior Color *') ?></label></div>
                                    <div class="col-md-9">
                                        <?php
                                        $vehicleColors = ['Anthracite' => __('Anthracite'), 'Beige' => __('Beige'), 'Black' => __('Black'), 'Blue' => __('Blue'), 'Bordeaux' => __('Bordeaux'), 'Brown' => __('Brown'), 'Gold' => __('Gold'), 'Green' => __('Green'), 'Grey' => __('Grey'), 'Orange' => __('Orange'), 'Other' => __('Other'), 'Pink' => __('Pink'), 'Red' => __('Red'), 'Silver' => __('Silver'), 'Turquoise' => __('Turquoise'), 'Violet' => __('Violet'), 'White' => __('White'), 'Yellow' => __('Yellow')];
                                        echo $this->Form->input('Vehicle.exterior_color', array('div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => 'validate[required] form-control', 'type' => 'select', 'options' => $vehicleColors, 'empty' => __('Please select')));
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Car Finish') ?></label></div>
                                    <div class="col-md-9">
                                        <?php
                                        $carFinishOptions = ['Bubble effect' => __('Bubble effect'), 'Individual paint' => __('Individual paint'), 'Matt paint' => __('Matt paint'), 'Mettalic' => __('Mettalic'), 'Solid paint' => __('Solid paint')];
                                        echo $this->Form->input('Vehicle.car_finish', array('div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'empty' => __('Please select'), 'class' => 'form-control', 'type' => 'select', 'options' => $carFinishOptions));
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Interior Color *') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.interior_color', array('div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'empty' => __('Please select'), 'class' => 'validate[required] form-control', 'type' => 'select', 'options' => $vehicleColors, 'empty' => __('Please select'))); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Options / Additional information') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.additional_info', array('div' => FALSE, 'label' => FALSE, 'type'=>'textarea', 'class' => 'form-control', 'rows' => 2)); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Gen. Condition') ?></label></div>
                                    <div class="col-md-9 form-label">
                                        <input type="hidden" name="data[Vehicle][gen_condition]">
                                        <div class="radio">
                                            <input id="genCondition1" value="accident" type="checkbox" name="data[Vehicle][gen_condition][]">
                                            <label for="genCondition1"><?= __('Vehicle involved in accident') ?></label>
                                        </div>
                                        <div class="radio">
                                            <input id="genCondition2" value="dog_owner" type="checkbox" name="data[Vehicle][gen_condition][]">
                                            <label for="genCondition2"><?= __('Dog owner') ?></label>
                                        </div>
                                        <div class="radio">
                                            <input id="genCondition3" value="smoker_vehicle" type="checkbox" name="data[Vehicle][gen_condition][]">
                                            <label for="genCondition3"><?= __("Smoker's vehicle") ?></label>
                                        </div>
                                        <div class="radio">
                                            <input id="genCondition4" value="hail_damage" type="checkbox" name="data[Vehicle][gen_condition][]">
                                            <label for="genCondition4"><?= __('Hail damage') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Vehicle inspection Date') ?></label></div>
                                    <div class="col-md-9 ">
                                        <?php echo $this->Form->input("Vehicle.inspection", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0')); ?>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other') ?></label></div>
                                    <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_". Configure::read('Config.language'), array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?><p><a href="javascript:void(0);" data-toggle="modal" data-target="#others" ><u><?= __('Multilingual') ?></u></a></p></div>
                                </div>

                                <div id="others" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Other Condition</h4>
                                    </div>
                                    <div class="modal-body">

                                    <?php if(Configure::read('Config.language') == 'eng'){  ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - German') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_deu", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - Italy') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_it", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - France') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_fr", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } else if(Configure::read('Config.language') == 'deu'){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - English') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_eng", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - Italy') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_it", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - France') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_fr", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } else if(Configure::read('Config.language') == 'it'){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - English') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_eng", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - German') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_deu", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - France') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_fr", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } else if(Configure::read('Config.language') == 'fr'){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - English') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_eng", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - German') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_deu", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Other - Italy') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.other_condition_it", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } ?>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-6">

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Frame No.') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.frame_no', array('div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => 'form-control')); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Model No.') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.model_no', array('value' => isset($vehicleData[$ltnId]['ModelNumber']) ? $vehicleData[$ltnId]['ModelNumber'] : '', 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => 'form-control')); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Register No.') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.reg_no', array('div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => 'form-control')); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label><?= __('Vehicle No.') ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.vehicle_no', array('div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => 'form-control')); ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3 label-radio-wrap">
                                        <label class="control-label" for="CompanyName"><?= __("Swiss Car") ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        echo $this->Form->input('Company.swiss_car', array('type' => 'radio', 'options' => array(1 => __('Yes'), 0 => __('No')), 'div' => false, 'label' => true, 'class' => 'comercial_vehicle', 'legend' => false, 'default' => 1,
                                            'before' => '<div class="radio-wrap">',
                                            'after' => '</div>',
                                            'separator' => '</div><div class="radio-wrap">')
                                        );
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label for="vehicle_region"><?= __("Vehicle Region*") ?></label></div>
                                    <div class="col-md-9">
                                        <?php echo $this->Form->input('Vehicle.vehicle_regions', array('div' => FALSE, 'label' => FALSE, 'class' => 'validate[required] form-control', 'empty' => __('Select Region'), 'type' => 'select', 'options' => @$region_data)); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-md-3 label-radio-wrap"> <label for="exampleInputEmail1"><?= __('Vehicle registration document') ?></label></div>
                                    <div class="col-md-9">
                                        
                                        <?php
                                            echo $this->Form->input('Vehicle.reg_document', array('type' => 'radio', 
                                                'options' => array('1' => __('Available'), '2' => __('Will be supplied later')), 'div' => false, 'label' => true, 'class' => '', 'legend' => false, 'default' => '1',
                                                'before' => '<div class="radio">',
                                                'after' => '</div>',
                                                'separator' => '</div><div class="radio">')
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3 label-radio-wrap"><label for="exampleInputEmail1"><?= __('Service record') ?></label></div>
                                    <div class="col-md-9 ">
                                        <?php
                                            echo $this->Form->input('Vehicle.service_record', array('type' => 'radio', 
                                                'options' => array('1' => __('Available'), '2' => __('Incomplete'), '3' => __('Missing') ), 'div' => false, 'label' => true, 'class' => '', 'legend' => false, 'default' => '1',
                                                'before' => '<div class="radio">',
                                                'after' => '</div>',
                                                'separator' => '</div><div class="radio">')
                                            );
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Number of keys*') ?></label></div>
                                    <div class="col-md-9 "><?php echo $this->Form->input('Vehicle.no_of_keys', array('div' => FALSE, 'label' => FALSE, 'class' => 'validate[required] form-control', 'empty' => __('Please select'), 'type' => 'select', 'options' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4))); ?></div>
                                </div>

                                
                            </div>
                        </div>
                        
                        <h2 class="tabs-title"><span> 2. </span><?= __('Vehicle Damage Information') ?></h2>
                        <div class="row tabs-content-wrap">
                            <div class="col-md-6">

                                <div class="form-group row">
                                    <div class="col-md-3 label-radio-wrap">
                                        <label class="control-label" for="CompanyName"><?= __("Vehicle Damage") ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        echo $this->Form->input('Company.is_damage', array('type' => 'radio', 'options' => array(1 => __('Yes'), 0 => __('No')), 'div' => false, 'label' => true, 'class' => 'vehicleDamage', 'legend' => false, 'default' => 0,
                                            'before' => '<div class="radio-wrap">',
                                            'after' => '</div>',
                                            'separator' => '</div><div class="radio-wrap">')
                                        );
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="form-group row vehicleContent">
                                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body') ?></label></div>
                                    <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_". Configure::read('Config.language'), array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?><p><a href="javascript:void(0);" data-toggle="modal" data-target="#body" ><u><?= __('Multilingual') ?></u></a></p></div>
                                </div>

                                <div id="body" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Body</h4>
                                    </div>
                                    <div class="modal-body">

                                    <?php if(Configure::read('Config.language') == 'eng'){  ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - German') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_deu", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - Italy') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_it", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - France') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_fr", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } else if(Configure::read('Config.language') == 'deu'){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - English') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_eng", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - Italy') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_it", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - France') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_fr", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } else if(Configure::read('Config.language') == 'it'){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - English') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_eng", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - German') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_deu", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - France') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_fr", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } else if(Configure::read('Config.language') == 'fr'){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - English') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_eng", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - German') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_deu", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Body - Italy') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.body_it", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } ?>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group row vehicleContent">
                                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Repairs*') ?></label></div>
                                    <div class="col-md-9 ">
                                        <?php echo $this->Form->input("Vehicle.repairs", array('div' => false, 'label' => false, 'class' => 'validate[required] form-control', 'style' => 'margin:0', 'placeholder' => __('CHF'))); ?>
                                    </div>
                                </div>


                                <div class="form-group row vehicleContent">
                                    <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics') ?></label></div>
                                    <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_". Configure::read('Config.language'), array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?><p><a href="javascript:void(0);" data-toggle="modal" data-target="#mechanics" ><u><?= __('Multilingual') ?></u></a></p></div>
                                </div>

                                <div id="mechanics" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Mechanics</h4>
                                    </div>
                                    <div class="modal-body">

                                    <?php if(Configure::read('Config.language') == 'eng'){  ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - German') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_deu", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - Italy') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_it", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - France') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_fr", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } else if(Configure::read('Config.language') == 'deu'){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - English') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_eng", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - Italy') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_it", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - France') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_fr", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } else if(Configure::read('Config.language') == 'it'){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - English') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_eng", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - German') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_deu", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - France') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_fr", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } else if(Configure::read('Config.language') == 'fr'){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - English') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_eng", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - German') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_deu", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"> <label for="exampleInputEmail1"><?= __('Mechanics - Italy') ?></label></div>
                                            <div class="col-md-9 "><?php echo $this->Form->input("Vehicle.mechanics_it", array('div' => false, 'label' => false, 'class' => 'form-control', 'style' => 'margin:0', 'rows' => 2 )); ?></div>
                                        </div>
                                    <?php } ?>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <h3 class="vehicleContent"><?php echo __('Vehicle Damage Images'); ?></h3>
                        <div class="row tabs-content-wrap vehicleContent">
                            <div class="col-md-6">
                                <div class="damage-car-container">
                                    <div data-attr="front_view" class="car-views front_view"></div>
                                    <div data-attr="left_view" class="car-views left_view"></div>
                                    <div data-attr="top_view" class="car-views top_view"></div>
                                    <div data-attr="right_view" class="car-views right_view"></div>
                                    <div data-attr="back_view" class="car-views back_view"></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group front_view_container" style="display: none;" >
                                    <div class="col-md-5"> <label for="VehicleDamageBottomside"><?= __('Front view') ?></label></div>
                                    <div class="col-md-7 "><?php echo $this->Form->input('VehicleDamage.bottomside.', array('type' => 'file', 'multiple' => TRUE, 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => '')); ?></u></p></div>
                                </div>

                                <div class="form-group left_view_container" style="display: none;" >
                                    <div class="col-md-5"> <label for="VehicleDamageLeftside"><?= __('Left view') ?></label></div>
                                    <div class="col-md-7 "><?php echo $this->Form->input('VehicleDamage.leftside.', array('type' => 'file', 'multiple' => TRUE, 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => '')); ?></u></p></div>
                                </div>
                                <div class="form-group top_view_container" style="display: none;" >
                                    <div class="col-md-5"> <label for="VehicleDamageTopside"><?= __('Top view') ?></label></div>
                                    <div class="col-md-7 "><?php echo $this->Form->input('VehicleDamage.topside.', array('type' => 'file', 'multiple' => TRUE, 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => '')); ?></u></p></div>
                                </div>


                                <div class="form-group right_view_container" style="display: none;" >
                                    <div class="col-md-5"><label for="VehicleDamageRightside"><?php echo __('Right view'); ?></label></div>
                                    <div class="col-md-7 "><?php echo $this->Form->input('VehicleDamage.rightside.', array('type' => 'file', 'multiple' => TRUE, 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => '')); ?></u></p></div>
                                </div>
                                <div class="form-group back_view_container" style="display: none;" >
                                    <div class="col-md-5"> <label for="VehicleDamageBackside"><?= __('Back view') ?></label></div>
                                    <div class="col-md-7 "><?php echo $this->Form->input('VehicleDamage.backside.', array('type' => 'file', 'multiple' => TRUE, 'div' => FALSE, 'label' => FALSE, 'style' => 'margin:0', 'class' => '')); ?></u></p></div>
                                </div>
                            </div>

                        </div>
                        
                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile">...</div>
                    <div role="tabpanel" class="tab-pane" id="messages">...</div>
                    <div role="tabpanel" class="tab-pane" id="settings">...</div>
                </div>

            </div>

            <div class="row padin-row">
                <div class="col-lg-6 text-left">
                    <a href="#" class="btn btn-default"><?= __('Previous') ?></a>
                </div>
                <div class="col-lg-6" style="text-align:right"> <a href="javascript:void(0);" onclick="$('#step1-form').submit();" class="btn btn-default"><?= __('Next') ?></a></div>
            </div>

        </form>

    </div>
</section>

<script type = 'text/javascript'>

    $(document).ready(function () {
        $('#step1-form').validationEngine();
        var placeholder = 'Fahrzeugausstattung \n Z.b. \n - Leder- Navi \n- Klima \n- Schiebedach \n - Tempomat \n - Sitzheizung \n - Leichtmetallräder \n - Anhängevorrichtung mit abnehmbaremKugelkopf/Haken';
        $('#VehicleOptions').val(placeholder);

        $('#VehicleOptions').focus(function(){
            if($(this).val() === placeholder){
                $(this).val('');
            }
        });

        $('#VehicleOptions').blur(function(){
            if($(this).val() ===''){
                $(this).val(placeholder);
            }    
        });
        
        $('#VehicleFirstReg').datepicker({
            format: "mm/yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
        });

        $('#VehicleInspection').datepicker({
            format: "mm/yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
        });
        
        if( $('.vehicleDamage:checked').val() == 0 ){
            $('.vehicleContent').hide();
        } else {
            $('.vehicleContent').show();
        }
        
        $('.vehicleDamage').change(function(){
            if( $(this).val() == 0 ){
                $('.vehicleContent').hide();
            } else {
                $('.vehicleContent').show();
            }
        });
        
        $('.car-views').click(function () {
            if ($(this).hasClass('selected')) {
                $('.' + $(this).attr('data-attr') + '_container').hide();
                $(this).removeClass('selected');
            } else {
                $('.' + $(this).attr('data-attr') + '_container').show();
                $(this).addClass('selected');
            }
        });

        // $('#selectOtherCondition').click(function(){
        //     $('#VehicleOtherCondition').val( $("[name='data[Vehicle][other_condition_<?php echo Configure::read('Config.language');  ?>]']").val()  );
        // });

    });
</script>
