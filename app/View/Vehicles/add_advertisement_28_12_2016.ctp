<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<section class="mid-section">
    <div class="container">
        <div class="tab-bar-all-pages">

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                    <h1><span><?= __("Add Advertisement") ?></span></h1>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="col-lg-12"> <label for="reg_number"><?= __("1st reg. *") ?></label></div>
                                <div class="col-lg-12">
                                    <?php echo $this->Form->input('reg_number', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control', 'type' => 'text', 'required' => true)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-12"> <label for="model_number"><?= __("Model No. *") ?></label></div>
                                <div class="col-lg-12">
                                    <?php echo $this->Form->input('model_number', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control', 'type' => 'text', 'required' => true)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-offset-10">
                                    <a href="javascript:void(0);" class="searchVehicle btn btn-default"><?= __("Search") ?></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p><?= __("Choose your vehicle category and region") ?></p>

                    <div class="row">
                        <form method="POST" id="cat-form">
                            <?php echo $this->Form->hidden('Vehicle.id'); ?>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-lg-12"> <label for="exampleInputEmail1"><?= __("Add Category") ?></label></div>
                                    <div class="col-lg-12">
                                        <?php
                                        $monthArray = array();

                                        $startYear = date('Y');
                                        $endYear = $startYear - 15;
                                        $yearArray = range($startYear, $endYear);

                                        $yearData = array();
                                        foreach ($yearArray as $year) {
                                            $yearData[$year] = $year;
                                        }

                                        $monthData = array();
                                        for ($m = 1; $m <= 12; $m++) {
                                            $monthData[$m] = date('F', mktime(0, 0, 0, $m));
                                        }


                                        echo $this->Form->input('Vehicle.year', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control vehicleYear', 'empty' => __('--Select Year--'), 'type' => 'select', 'options' => $yearData));
                                        echo $this->Form->input('Vehicle.month', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control vehicleMonth', 'empty' => __('--Select Month--'), 'type' => 'select', 'options' => $monthData));
                                        echo $this->Form->input('Vehicle.make', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control vehicleMake', 'empty' => __('--Select Make--'), 'type' => 'select'));
                                        echo $this->Form->input('Vehicle.model', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control vehicleModel', 'empty' => __('--Select Model--'), 'type' => 'select'));

                                        //echo $this->Form->input('Vehicle.category_id', array('div' => FALSE, 'label' => FALSE, 'class' => 'form-control', 'empty' => __('--Select Category--'), 'type' => 'select', 'options' => @$category_data));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:void(0);" onclick="$('#cat-form').submit();" class="btn btn-default btnNext" style="margin-top:113px;"><?= __("Next") ?></a>

                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<input type="hidden"  name="item_selection" autocomplete="off" value="">

<div id="myModal" class="modal fade" role="dialog"></div>


<script>
    $(document).ready(function () {

        $('#reg_number').datepicker({
            format: "mm/yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
        });

        /*$('#VehicleCategoryId').change(function(){
         if( $(this).val() != '' ) {
         $('.btnNext').show().css('display','block');
         } else {
         $('.btnNext').hide();
         }
         });*/

        $('.vehicleYear, .vehicleMonth').change(function () {
            
            if( $('#vehicleYear').val() != '' && $('#VehicleMonth').val() != ''  ){
                $.ajax({
                    url: "http://ef24.ch/euro/get_vehicle_make_list.php",
                    type: "POST",
                    //dataType: 'json',
                    data: {'productionYear': $('#VehicleYear').val(), 'productionMonth': $('#VehicleMonth').val()},
                    success: function (result) {
                        $('#VehicleMake').html(result);
                        
//                        console.log(result);
//                        var makeOptions = '';
//                        if (result.data.length) {
//                            $.each(result.data,function (key, val) {
//                                makeOptions += '<option value="' + key + '"> ' + val + ' </option>'
//                            });
//
//                            $('#VehicleMake').html(makeOptions);
//                        } else {
//                            $('#VehicleMake').html('<option value=""><?php echo __("Please Select"); ?></option>');
//                        }
                    }
                });
            }
            
        });

        $('#VehicleMake').change(function () {
            $.ajax({
                url: "http://ef24.ch/euro/get_vehicle_model_list.php",
                type: "POST",
                //dataType: 'json',
                data: {'productionYear': $('#VehicleYear').val(), 'productionMonth': $('#VehicleMonth').val()},
                success: function (result) {
                    
                    $('#VehicleModel').html(result);
//                    var makeOptions = '';
//                    if (result.data.length) {
//                        $.each(result.data, function (key, val) {
//                            makeOptions += '<option value="' + key + '"> ' + val + ' </option>'
//                        });
//
//                        $('#VehicleModel').html(makeOptions);
//                    } else {
//                        $('#VehicleModel').html('<option value=""><?php echo __("Please Select"); ?></option>');
//                    }
                }
            });
        });

        $('.searchVehicle').click(function () {
            $.ajax({
                url: "http://ef24.ch/euro/get_vehicle_info.php",
                type: "POST",
                data: {'first_registration': $('#reg_number').val(), 'model_number': $('#model_number').val()},
                success: function (result) {
                    $('#myModal').html(result);
                    $('#myModal').modal("show");

                    $('.vehicle-detail-row').click(function () {
                        window.location.href = siteUrl + '/Vehicles/step1?ltn=' + $(this).attr('data-id');
                    });
                }
            });
        });
    });
</script>
