<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title"><?php echo __('Email Templates'); ?></h4>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?php echo SITE_URL_ADMIN . '/Users/dashboard'; ?>"><?php echo __('Home'); ?></a>
                        </li>
                        <li class="active">
                            <?php echo __('Email Templates'); ?>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <h4 class="m-t-0 header-title"><b><?php echo __('Manage Email Templates'); ?></b></h4>
                        
                        <div class="form-inline m-b-20">
                            <div class="row">
                                <div class="col-sm-6 text-xs-center">
                                    <!--<div class="form-group">
                                                    <label class="control-label m-r-5">Status</label>
                                                    <select id="demo-foo-filter-status" class="form-control input-sm">
                                                                    <option value="">Show all</option>
                                                                    <option value="active">Active</option>
                                                                    <option value="disabled">Inactive</option>
                                                    </select>
                                    </div>-->
                                </div>

                                <div class="col-sm-6 text-xs-center text-right">
                                    <div class="form-group">
                                        <select id="demo-foo-filter-status" class="form-control input-sm searchBox">
                                            <option value="all" selected ><?php echo __('Show all'); ?></option>
                                            <option value="email"><?php echo __('Email'); ?></option>
                                            <option value="name"><?php echo __('Name'); ?></option>
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
                                        <th data-toggle="true" class="footable-visible footable-first-column footable-sortable"><?php echo __('From Name');?><!--<span class="footable-sort-indicator"></span>--></th>
                                        <th class="footable-visible footable-sortable"><?php echo __('From Email');?><!--<span class="footable-sort-indicator"></span>--></th>
                                        <th class="footable-visible footable-sortable"><?php echo __('To');?><!--<span class="footable-sort-indicator"></span>--></th>
                                        <th data-hide="phone" class="footable-visible footable-sortable"><?php echo __('Subject');?><!--<span class="footable-sort-indicator"></span>--></th>
                                        <th data-hide="phone, tablet" class="footable-visible footable-sortable"><?php echo __('Status');?><!--<span class="footable-sort-indicator"></span>--></th>
                                        <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Created');?><!--<span class="footable-sort-indicator"></span>--></th>
                                    <th data-hide="phone, tablet" class="footable-visible footable-last-column footable-sortable"><?php echo __('Action'); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if (isset($emailTemplates) && !empty($emailTemplates)) {
                                    $count = 0;
                                    foreach ($emailTemplates as $data) {
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
                                                if (isset($data['EmailTemplate']['from_name']) && !empty($data['EmailTemplate']['from_name'])) {
                                                    echo $data['EmailTemplate']['from_name'];
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <td class="footable-visible">
                                                <?php
                                                if (isset($data['EmailTemplate']['from_email']) && !empty($data['EmailTemplate']['from_email'])) {
                                                    echo $data['EmailTemplate']['from_email'];
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <td class="footable-visible">
                                                <?php
                                                if (isset($data['EmailTemplate']['to']) && !empty($data['EmailTemplate']['to'])) {
                                                    echo $data['EmailTemplate']['to'];
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <td class="footable-visible">
                                                <?php
                                                if (isset($data['EmailTemplate']['subject']) && !empty($data['EmailTemplate']['subject'])) {
                                                    echo $data['EmailTemplate']['subject'];
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <td class="footable-visible">
                                                <a href="javascript:void(0);" id="status_<?= $data['EmailTemplate']['id']; ?>" onclick='changestatus(<?= $data['EmailTemplate']['id']; ?>, "EmailTemplate");'>
                                                    <?php if ($data['EmailTemplate']['status'] == 1) { ?>
                                                        <span class="label label-table label-success"><?php echo __('Active');?></span>
                                                    <?php } else { ?>
                                                        <span class="label label-table label-danger"><?php echo __('Inactive');?></span>
                                                    <?php } ?>
                                                </a>
                                            </td>
                                            <td class="footable-visible footable-last-column">
                                                <?php
                                                if (isset($data['EmailTemplate']['created']) && !empty($data['EmailTemplate']['created'])) {
                                                    echo date('d-M-Y', strtotime($data['EmailTemplate']['created']));
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <td class="footable-visible footable-last-column">
                                                <a href="<?= SITE_URL_ADMIN . '/EmailTemplates/edit/' . base64_encode($data['EmailTemplate']['id']) ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                <?= $this->Form->postLink('<i class="fa fa-remove"></i>', ['action' => 'delete', base64_encode($data['EmailTemplate']['id'])], ['escape' => false, 'confirm' => __('Are you sure you want to delete?')]) ?>
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
            </div>

        </div> <!-- container -->

    </div> <!-- content -->

    <!-- Start Footer -->
    <?php echo $this->element('admin/footer'); ?>
    <!-- End Footer -->

</div>


<?php /*
<div class="emailTemplates index">
    <h2><?php echo __('Email Templates'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('id'); ?></th>
                <th><?php echo $this->Paginator->sort('from_name'); ?></th>
                <th><?php echo $this->Paginator->sort('from_email'); ?></th>
                <th><?php echo $this->Paginator->sort('to'); ?></th>
                <th><?php echo $this->Paginator->sort('cc'); ?></th>
                <th><?php echo $this->Paginator->sort('bcc'); ?></th>
                <th><?php echo $this->Paginator->sort('subject'); ?></th>
                <th><?php echo $this->Paginator->sort('alias'); ?></th>
                <th><?php echo $this->Paginator->sort('description'); ?></th>
                <th><?php echo $this->Paginator->sort('status'); ?></th>
                <th><?php echo $this->Paginator->sort('created'); ?></th>
                <th><?php echo $this->Paginator->sort('modified'); ?></th>
                <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($emailTemplates as $emailTemplate): ?>
                <tr>
                    <td><?php echo h($emailTemplate['EmailTemplate']['id']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['from_name']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['from_email']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['to']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['cc']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['bcc']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['subject']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['alias']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['description']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['status']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['created']); ?>&nbsp;</td>
                    <td><?php echo h($emailTemplate['EmailTemplate']['modified']); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View'), array('action' => 'view', $emailTemplate['EmailTemplate']['id'])); ?>
                        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $emailTemplate['EmailTemplate']['id'])); ?>
                        <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $emailTemplate['EmailTemplate']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $emailTemplate['EmailTemplate']['id']))); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?>	</p>
    <div class="paging">
        <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Email Template'), array('action' => 'add')); ?></li>
    </ul>
</div>
*/ ?>