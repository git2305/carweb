<section class="mid-section">
    <div class="container">
        <h1 class="main-title"><?php echo __("Sub"); ?> <span><?php echo __("Users"); ?></span></h1>


        <div id="auctionData1">

            <table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable vehicleTableData" data-paging="true" data-page-size="7">
                <thead>
                    <tr>
                        <th data-toggle="true" class="footable-visible footable-first-column footable-sortable">Full Name<!--<span class="footable-sort-indicator"></span>--></th>
                        <th class="footable-visible footable-sortable"><?php echo __('Email');?></th>
                        <th data-hide="phone" class="footable-visible footable-sortable"><?php echo __('Mobile');?></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Status');?></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Created');?></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Action'); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if (isset($user_data) && !empty($user_data)) {
                        $count = 0;
                        foreach ($user_data as $data) {
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
                                    if (isset($data['User']['fname']) && !empty($data['User']['fname'])) {
                                        echo $data['User']['prefix_name'] . " " . $data['User']['fname'] . " " . $data['User']['lname'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['User']['email']) && !empty($data['User']['email'])) {
                                        echo $data['User']['email'];
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <?php
                                    if (isset($data['User']['mobile']) && !empty($data['User']['mobile'])) {
                                        if ($data['User']['mobile'] != '') {
                                            $mob = explode('+', $data['User']['mobile']);

                                            if (isset($mob[0]) && isset($mob[1])) {
                                                echo "+" . $mob[0] . " " . $mob[1];
                                            } else {
                                                echo "N/A";
                                            }
                                        } else {
                                            echo "N/A";
                                        }
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible">
                                    <a href="javascript:void(0);" id="status_<?= $data['User']['id']; ?>" onclick='changestatus(<?= $data['User']['id']; ?>, "User");'>
                                        <?php if ($data['User']['status'] == 1) { ?>
                                            <span class="label label-table label-success">Active</span>
                                        <?php } else { ?>
                                            <span class="label label-table label-danger">Inactive</span>
                                        <?php } ?>
                                    </a>
                                </td>
                                <td class="footable-visible footable-last-column">
                                    <?php
                                    if (isset($data['User']['created']) && !empty($data['User']['created'])) {
                                        echo date('d-M-Y', strtotime($data['User']['created']));
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td class="footable-visible footable-last-column">
                                    <a title="<?php echo __('Edit'); ?>" href="<?php echo SITE_URL . '/Users/editSubUser/' . base64_encode($data['User']['id']) ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                    <a title="<?php echo __('Added Vehicle'); ?>" href="<?php echo SITE_URL . '/Users/subUserVehicles/' . base64_encode($data['User']['id']) ?>" class="on-default edit-row"><i class="fa fa-car"></i></a>
                                    <?php echo $this->Form->postLink('<i class="fa fa-remove"></i>', ['action' => 'delete', base64_encode($data['User']['id'])], ['escape' => false, 'confirm' => __('Are you sure you want to delete?')]) ?>
                                </td>
                            </tr>
                            <?php
                            $count++;
                        }
                    }
                    ?>
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