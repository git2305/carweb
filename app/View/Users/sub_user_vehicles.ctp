<section class="mid-section">
    <div class="container">
        <h1 class="main-title"><?php echo __("Subuser"); ?> <span><?php echo __("Vehicles"); ?></span></h1>

        <div id="auctionData1">

            <table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable vehicleTableData" data-paging="true" data-page-size="7">
                <thead>
                    <tr>
                        
                        <th data-toggle="true" class="footable-visible footable-first-column footable-sortable"><?php echo __('Model, Type'); ?></th>
                        <th class="footable-visible footable-sortable"><?php echo __('Body Type'); ?></th>
                        <th data-hide="phone" class="footable-visible footable-sortable"><?php echo __('Doors'); ?></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('HP'); ?></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Fuel'); ?></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Gear'); ?></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Factory Price'); ?></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Created'); ?></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Action'); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if (isset($subUserVehicles) && !empty($subUserVehicles)) {
                        $count = 0;
                        foreach ($subUserVehicles as $data) {
                            ?>
                            <tr style="display: table-row;" class="<?php
                            if ($count % 2 == 0) {
                                echo 'footable-even';
                            } else {
                                echo 'footable-even';
                            }
                            ?>">
                                <td class="footable-visible footable-first-column">
                                    <?php
                                    if (isset($data['Vehicle']['brand']) && !empty($data['Vehicle']['brand'])) {
                                        echo trim($data['Vehicle']['brand'] . " " . $data['Vehicle']['model'] . " " . $data['Vehicle']['type']);
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['Vehicle']['body_type']) && !empty($data['Vehicle']['body_type'])) {
                                        echo $data['Vehicle']['body_type'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['Vehicle']['doors']) && !empty($data['Vehicle']['doors'])) {
                                        echo $data['Vehicle']['doors'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['Vehicle']['hp']) && !empty($data['Vehicle']['hp'])) {
                                        echo $data['Vehicle']['hp'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['Vehicle']['fuel']) && !empty($data['Vehicle']['fuel'])) {
                                        echo $data['Vehicle']['fuel'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['Vehicle']['gear']) && !empty($data['Vehicle']['gear'])) {
                                        echo $data['Vehicle']['gear'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['Vehicle']['factory_price']) && !empty($data['Vehicle']['factory_price'])) {
                                        echo $data['Vehicle']['factory_price'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>

                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['Vehicle']['created']) && !empty($data['Vehicle']['created'])) {
                                        echo date('d-M-Y', strtotime($data['Vehicle']['created']));
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible footable-last-column actions"><a href="<?php echo BASE_URL . '/Vehicles/vehicledetails/' . base64_encode($data['Vehicle']['id']); ?>" class="btn-Preis"><i class="fa fa-eye"></i></a></td>
                            </tr>
                            <?php
                            $count++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="11">No Record Found</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="pgCounter">
            <?php echo 'Page ' . $this->Paginator->counter(); ?>
        </div>
        <div class="pagination pagination-right">
            <ul>
                <?php
                echo $this->Paginator->prev('<<', array('class' => 'myclass', 'tag' => 'li'), null, array('class' => 'disabled myclass', 'tag' => 'li'));
                echo $this->Paginator->numbers(array('tag' => 'li', 'separator' => '', 'class' => 'active myclass', 'currentTag' => 'a'));
                echo $this->Paginator->next('>>', array('class' => 'myclass', 'tag' => 'li'), null, array('class' => 'disabled myclass', 'tag' => 'li'));
                ?>
            </ul>
        </div>
    </div>
</section>
<script>

</script>


<script type = 'text/javascript'>
    $(document).ready(function () {

        $(".imgzoom").elevateZoom({gallery: 'gallery_01', cursor: 'pointer', galleryActiveClass: 'active', imageCrossfade: true, loadingIcon: ''});

        $('#auctionData').slimscroll({
            size: '5px',
            height: '520px',
        });
    });
</script>
