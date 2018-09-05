<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title"><?php echo __('Companies Listing'); ?></h4>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?= SITE_URL_ADMIN . '/Users/dashboard' ?>">Home</a>
                        </li>
                        <li class="active">
                            Companies
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <h4 class="m-t-0 header-title"><b><?php echo __('Manage Companies'); ?></b></h4>

                        <div class="form-inline m-b-20">
							<div class="row">
								<div class="col-sm-6 text-xs-center text-left">
									<div class="form-group">
											<select id="demo-foo-filter-status" class="form-control input-sm searchBox">
													<option value="all" selected >Show all</option>
													<option value="compname">Company Name</option>
											</select>
									</div>
									<div class="form-group">
											<input id="demo-foo-search searchField" placeholder="Search" class="form-control input-sm searchField" required autocomplete="on" type="text">
											<button class="btn btn-primary waves-effect waves-light searchCompany" type="button">Go</button>
									</div>
								</div>
                                <div class="col-sm-6 text-xs-center text-right">
                                    <div class="form-group">
                                        <?php echo $this->Html->link(__('Add New Company'), ['action' => 'add'], ['class' => 'btn btn-primary']); ?>
                                    </div>
                                </div>
                            </div>
                            
                        </div> 

                        <table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0 default footable-loaded footable companyTableData" data-page-size="7">
                            <thead>
                                <tr>
                                    <th data-toggle="true" class="footable-visible footable-first-column footable-sortable">Company Name<!--<span class="footable-sort-indicator"></span>--></th>
                                    <th class="footable-visible footable-sortable">Address<!--<span class="footable-sort-indicator"></span>--></th>
                                    <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable">Created<!--<span class="footable-sort-indicator"></span>--></th>
                                    <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Action'); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if (isset($companies) && !empty($companies)) {
                                    $count = 0;
                                    foreach ($companies as $data) {
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
                                                if (isset($data['Company']['name']) && !empty($data['Company']['name'])) {
                                                    echo $data['Company']['name'];
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <td class="footable-visible">
                                                <?php
                                                $address = '';
                                                if (isset($data['Company']['street']) && $data['Company']['street'] != '') {
                                                    $address .= $data['Company']['street'] . ", ";
                                                }
                                                if (isset($data['Company']['town']) && $data['Company']['town'] != '') {
                                                    $address .= $data['Company']['town'] . ", ";
                                                }
                                                if (isset($data['Company']['country']) && $data['Company']['country'] != '') {
                                                    $address .= $data['Company']['country'] . ", ";
                                                }

                                                if (isset($data['Company']['postcode']) && $data['Company']['postcode'] != '') {
                                                    $address .= $data['Company']['postcode'] . " ";
                                                }

                                                if (trim($address) != '') {
                                                    echo trim($address);
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
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
                                                <a href="<?= SITE_URL_ADMIN . '/Companies/edit/' . base64_encode($data['Company']['id']) ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    <?= $this->Form->postLink('<i class="fa fa-remove"></i>', ['action' => 'delete', base64_encode($data['Company']['id'])], ['escape' => false, 'confirm' => __('Are you sure you want to delete?')]) ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                }
                                ?>
                            </tbody>
<!--                            <tfoot>
                                <tr>
                                    <td colspan="5" class="footable-visible">
                                        <div class="text-right">
                                            <ul class="pagination pagination-split m-t-30 m-b-0">
                                                <li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li>
                                                <li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li>
                                                <li class="footable-page active"><a data-page="0" href="#">1</a></li>
                                                <li class="footable-page"><a data-page="1" href="#">2</a></li>
                                                <li class="footable-page"><a data-page="2" href="#">3</a></li>
                                                <li class="footable-page"><a data-page="3" href="#">4</a></li>
                                                <li class="footable-page"><a data-page="4" href="#">5</a></li>
                                                <li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li>
                                                <li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>-->
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
