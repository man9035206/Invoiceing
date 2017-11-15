<div id="headerbar">
    <h1 class="headerbar-title"> Non Invoiced POs</h1>

    <div class="headerbar-item pull-right">
        <?php echo pager(site_url('products/non_invoiced'), 'mdl_products'); ?>
    </div>
</div>

<div id="content" class="table-content">

    <?php $this->layout->load_view('layout/alerts'); ?>

    <div class="table-responsive">
        <table class="table table-striped">

            <thead>
            <tr>
                <th><?php _trans('client_name'); ?></th>
                <th><?php _trans('product_name'); ?></th>
                <th><?php _trans('product_description'); ?></th>
                <th><?php _trans('product_price'); ?></th>
                <th>Last Invoiced</th>
                <th>Last Invoice status</th>
                <th>Purchase order date</th>
                <th><?php _trans('options'); ?></th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td><?php _htmlsc(client_name($product->po_client_id)); ?></td>
                    <td><?php _htmlsc($product->product_name); ?></td>
                    <td><?php echo nl2br(htmlsc(po_desc($product->product_description))); ?></td>
                    <td><?php echo format_currency($product->product_price); ?></td>
                    <td><?php echo last_invoiced($product->product_id) ?></td>
                    <td><?php echo last_invoiced_status($product->product_id) ?></td>
                    <td><?php echo date_from_mysql($product->product_start)." to ".date_from_mysql($product->product_end); ?></td>
                    <td>
                        <div class="options btn-group">
                            <a class="btn btn-default btn-sm dropdown-toggle"
                               data-toggle="dropdown" href="#">
                                <i class="fa fa-cog"></i> <?php _trans('options'); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" class="create-invoice">
                                        <i class="fa fa-edit fa-margin"></i> Create Invoice
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>

</div>
