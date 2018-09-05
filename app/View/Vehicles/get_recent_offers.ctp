<div class="col-md-12">

    <h1 class="main-title"><span><?php echo __('Recent'); ?></span> <?php echo __('Offers'); ?></h1>

    <p><?php echo (isset($vehicle['AuctionBid']) && !empty($vehicle['AuctionBid']) ? count($vehicle['AuctionBid']) : __('No') ) . ' ' . __('offer(s) found.'); ?></p>
    <table class="table">
        <thead>
            <tr>
                <th><?php echo __('Name'); ?></th>
                <th><?php echo __('Bidding Price'); ?></th>
                <th><?php echo __('Created'); ?></th>
            </tr>
        </thead>
        <tbody>
                        <?php
                        if (!empty($vehicle['AuctionBid'])) {
                            foreach ($vehicle['AuctionBid'] as $key => $val) {
                                ?>
            <tr>
                <td><?php echo $vehicle['buyerArr'][$val['user_id']]; ?></td>
                <td><?php echo __('CHF') . ' ' . number_format($val['biding_amount'], 2); ?></td>
                <td><?php echo date('m/d/Y', strtotime($val['created'])); ?></td>
            </tr>
                                <?php
                            }
                        } else {
                            ?>
            <tr>
                <td colspan="3" align="center"><?php echo __('No offer(s) found'); ?></td>
            </tr>
                            <?php
                        }
                        ?>
        </tbody>
    </table>
</div>