<div class="content-page">
    <!-- Start content -->
    <div class="content">

        <div class="wraper container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title"><?php echo __('Add Company'); ?></h4>
                    <ol class="breadcrumb">
                        <li><a href="<?= SITE_URL_ADMIN . 'Users/dashboard' ?>">Home</a></li>
                        <li class="active"><?php echo __('Add Company'); ?></li>
                    </ol>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo __('Add Company'); ?></b></h4>

                        <div class="row">
                            <div class="col-lg-9">
                                <div class="p-20">
                                    <form method="POST" class="form-horizontal" id="cmspage_update">
                                        <div class="form-group">
                                            <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Company") ?></label></div>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('Company.name', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Company"))); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Addition") ?></label></div>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('Company.addition', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Addition"))); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Street/no.") ?></label></div>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('Company.street', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Address line 1"))); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("PO box") ?></label></div>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('Company.pob', array('div' => false, 'label' => false, 'class' => 'form-control', 'placeholder' => __("Address line 2"))); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Postcode/town *") ?></label></div>
                                            <div class="col-lg-9">
                                                <?php echo $this->Form->input('Company.postcode', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control col-form-2', 'placeholder' => __("Postcode"))); ?>
                                                <?php echo $this->Form->input('Company.town', array('div' => false, 'label' => false, 'class' => 'validate[required] form-control col-form-10', 'placeholder' => __("Town"))); ?>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Country*") ?></label></div>
                                                <div class="col-lg-9">
                                                    <select class="validate[required] form-control" name="data[Company][country]">
                                                        <option>Switzerland</option>
                                                        <option value="ch.carauktion.entity.customer.Country:20">Austria</option>
                                                        <option value="ch.carauktion.entity.customer.Country:1">Belgium</option>
                                                        <option value="ch.carauktion.entity.customer.Country:2">Bulgaria</option>
                                                        <option value="ch.carauktion.entity.customer.Country:11">Croatia</option>
                                                        <option value="ch.carauktion.entity.customer.Country:3">Czech Republic</option>
                                                        <option value="ch.carauktion.entity.customer.Country:4">Denmark</option>
                                                        <option value="ch.carauktion.entity.customer.Country:6">Estonia</option>
                                                        <option value="ch.carauktion.entity.customer.Country:26">Finland</option>
                                                        <option value="ch.carauktion.entity.customer.Country:10">France</option>
                                                        <option value="ch.carauktion.entity.customer.Country:5">Germany</option>
                                                        <option value="ch.carauktion.entity.customer.Country:29">Great Britain</option>
                                                        <option value="ch.carauktion.entity.customer.Country:8">Greece</option>
                                                        <option value="ch.carauktion.entity.customer.Country:17">Hungary</option>
                                                        <option value="ch.carauktion.entity.customer.Country:7">Ireland</option>
                                                        <option value="ch.carauktion.entity.customer.Country:12">Italy</option>
                                                        <option value="ch.carauktion.entity.customer.Country:14">Latvia</option>
                                                        <option value="ch.carauktion.entity.customer.Country:30">Liechtenstein</option>
                                                        <option value="ch.carauktion.entity.customer.Country:15">Lithuania</option>
                                                        <option value="ch.carauktion.entity.customer.Country:16">Luxembourg</option>
                                                        <option value="ch.carauktion.entity.customer.Country:31">Monaco</option>
                                                        <option value="ch.carauktion.entity.customer.Country:19">Netherlands</option>
                                                        <option value="ch.carauktion.entity.customer.Country:21">Poland</option>
                                                        <option value="ch.carauktion.entity.customer.Country:22">Portugal</option>
                                                        <option value="ch.carauktion.entity.customer.Country:23">Romania</option>
                                                        <option value="ch.carauktion.entity.customer.Country:33">Serbia</option>
                                                        <option value="ch.carauktion.entity.customer.Country:25">Slovakia</option>
                                                        <option value="ch.carauktion.entity.customer.Country:24">Slovenia</option>
                                                        <option value="ch.carauktion.entity.customer.Country:9">Spain</option>
                                                        <option value="ch.carauktion.entity.customer.Country:27">Sweden</option>
                                                        <option value="ch.carauktion.entity.customer.Country:28" selected="selected">Switzerland</option>
                                                        <option value="ch.carauktion.entity.customer.Country:32">Ukraine</option>
                                                    </select></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Car dealership") ?></label></div>
                                                <div class="col-lg-9">
                                                    <?php echo $this->Form->input('Company.car_dealership', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control', 'rows' => 2, 'placeholder' => '')); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Motorcycle dealership") ?></label></div>
                                                <div class="col-lg-9">
                                                    <?php echo $this->Form->input('Company.motorcycle_dealership', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control', 'rows' => 2, 'placeholder' => '')); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-3"> <label for="exampleInputEmail1"><?= __("Commercial vehicle dealership") ?></label></div>
                                                <div class="col-lg-9">
                                                    <?php echo $this->Form->input('Company.com_vehicle_dealership', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control', 'rows' => 2, 'placeholder' => '')); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-4 control-label"></label>
                                                <div class="col-lg-8">
                                                    <?php echo $this->Form->button('Reset', array('type' => 'reset', 'class' => 'btn btn-default waves-effect waves-light m-l-5')); ?>
                                                    <?php echo $this->Form->button('Submit', array('type' => 'submit', 'class' => 'btn btn-primary waves-effect waves-light')); ?>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div> <!-- container -->

    </div> <!-- content -->

    <!-- Start Footer -->
    <?php echo $this->element('admin/footer'); ?>
    <!-- End Footer -->

</div>
    
<script type = 'text/javascript'>
    $(document).ready(function () {
        $('#cmspage_update').validationEngine();
    });
</script>	