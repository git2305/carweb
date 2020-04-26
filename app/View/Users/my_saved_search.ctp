<section class="mid-section">
    <div class="container">
        <h1 class="main-title"><?php echo __("My Saved"); ?> <span><?php echo __("Search"); ?></span></h1>

        <div id="savedSearchData">
            <table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable vehicleTableData" data-paging="true" data-page-size="7">
                <thead>
                    <tr>

                        <th data-toggle="true" class="footable-visible footable-first-column footable-sortable"><?php echo __('Make'); ?></th>
                        <th class="footable-visible footable-sortable"><?php echo __('Model'); ?></th>
                        <th data-hide="phone" class="footable-visible footable-sortable"><?php echo __('Min year'); ?></th>
                        <th data-hide="phone" class="footable-visible footable-sortable"><?php echo __('Max year'); ?></th>
                        <th data-hide="phone" class="footable-visible footable-sortable"><?php echo __('Vehicle Region'); ?></th>
                        <th data-hide="phone" class="footable-visible footable-sortable"><?php echo __('Status'); ?></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Created'); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if (isset($savedSearch) && !empty($savedSearch)) {
                        $count = 0;
                        foreach ($savedSearch as $data) {
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
                                    if (isset($data['SavedSearch']['make']) && !empty($data['SavedSearch']['make'])) {
                                        echo trim($data['SavedSearch']['make']);
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['SavedSearch']['model']) && !empty($data['SavedSearch']['model'])) {
                                        echo $data['SavedSearch']['model'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['SavedSearch']['min_year']) && !empty($data['SavedSearch']['min_year'])) {
                                        echo $data['SavedSearch']['min_year'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>

                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['SavedSearch']['max_year']) && !empty($data['SavedSearch']['max_year'])) {
                                        echo $data['SavedSearch']['max_year'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['SavedSearch']['vehicle_regions']) && !empty($data['SavedSearch']['vehicle_regions'])) {
                                        echo $data['SavedSearch']['vehicle_regions'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                
                                <td class="footable-visible">
                                    <a href="javascript:void(0);" id="status_<?php echo $data['SavedSearch']['id']; ?>" onclick='changestatus(<?php echo $data['SavedSearch']['id']; ?>, "SavedSearch");'>
                                        <?php if ($data['SavedSearch']['status'] == 1) { ?>
                                            <span class="label label-table label-success"><?php echo __('Active'); ?></span>
                                        <?php } else { ?>
                                            <span class="label label-table label-danger"><?php echo __('Inactive'); ?></span>
                                        <?php } ?>
                                    </a>
                                </td>

                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['SavedSearch']['created']) && !empty($data['SavedSearch']['created'])) {
                                        echo date('d-M-Y', strtotime($data['SavedSearch']['created']));
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            $count++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7"><?php echo __('No Record Found');?></td>
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
