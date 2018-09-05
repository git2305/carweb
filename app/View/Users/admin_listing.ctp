<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Users Listing</h4>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?= SITE_URL_ADMIN . '/Users/dashboard' ?>">Home</a>
                        </li>
                        <li class="active">
                            Users
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <h4 class="m-t-0 header-title"><b>Manage Users</b></h4>
                        <!--<p class="text-muted m-b-30 font-13">
                                include filtering in your FooTable.
                        </p>-->

                        <div class="form-inline m-b-20">
                            <div class="row">
                                <div class="col-sm-6 text-xs-center">

                                </div>

                                <div class="col-sm-6 text-xs-center text-right">
                                    <div class="form-group">
                                        <select id="demo-foo-filter-status" class="form-control input-sm searchBox">
                                            <option value="all" selected >Show all</option>
                                            <option value="email">Email</option>
                                            <option value="name">Name</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input id="demo-foo-search searchField" placeholder="Search" class="form-control input-sm searchField" required autocomplete="on" type="text">
                                        <button class="btn btn-primary waves-effect waves-light searchAdmin" type="button">Go</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable userTableData" data-page-size="7">
                            <thead>
                                <tr>
                                    <th data-toggle="true" class="footable-visible footable-first-column footable-sortable">Full Name<!--<span class="footable-sort-indicator"></span>--></th>
                                    <th class="footable-visible footable-sortable">Email<!--<span class="footable-sort-indicator"></span>--></th>
                                    <th data-hide="phone" class="footable-visible footable-sortable">Mobile<!--<span class="footable-sort-indicator"></span>--></th>
                                    <th data-hide="phone, tablet" class="footable-visible footable-sortable">Status<!--<span class="footable-sort-indicator"></span>--></th>
                                    <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable">Created<!--<span class="footable-sort-indicator"></span>--></th>
                                    <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Action');?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if (isset($user_data) && !empty($user_data)) {
                                    $count = 0;
                                    foreach ($user_data as $data) {
                                        ?>
                                <tr style="display: table-row;" class="<?php if ($count % 2 == 0) {
                                            echo 'footable-even';
                                        } else {
                                            echo 'footable-even';
                                        } ?>">
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
                                                        if( $data['User']['mobile'] != '' ){
                                                            $mob = explode('+', $data['User']['mobile']);

                                                            if(isset($mob[0]) && isset($mob[1])){
                                                                echo "+" . $mob[0] . " " . $mob[1];
                                                            } else {
                                                                echo "N/A";
                                                            }
                                                            
                                                        }else {
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
                                        <a href="<?php echo SITE_URL_ADMIN.'/Users/edit/'.base64_encode($data['User']['id']) ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
<!--                                        <a title="Add Sub User" href="<?php //echo SITE_URL_ADMIN.'/Users/add_sub_user/'.base64_encode($data['User']['id']) ?>" class="on-default edit-row"><i class="fa fa-user"></i></a>-->
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
						<?php echo 'Page '.$this->Paginator->counter(); ?>
                    </div>
                    <div class="pagination pagination-right">
                        <ul>
                            <?php 
                                    echo $this->Paginator->prev( '<<', array( 'class' => 'myclass', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
                                    echo $this->Paginator->numbers( array( 'tag' => 'li', 'separator' => '', 'class' => 'active myclass', 'currentTag' => 'a' ) );
                                    echo $this->Paginator->next( '>>', array( 'class' => 'myclass', 'tag' => 'li' ), null, array( 'class' => 'disabled myclass', 'tag' => 'li' ) );
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div> <!-- container -->

    </div> <!-- content -->

    <!-- Start Footer -->
	<?php echo $this->element('admin/footer'); ?>
    <!-- End Footer -->

</div>
