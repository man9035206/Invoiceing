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
                                    <input type="hidden" class="po_id" value="<?php echo $product->product_id; ?>">
                                    <a href="#" class="create-po-invoice">
                                        <i class="fa fa-edit fa-margin"></i> Create Invoice
                                    </a>
                                    <input type="hidden" class="client_id" value="<?php echo $product->po_client_id; ?>">
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

<script>
$(document).ready(function(){
    $(".create-po-invoice").click(function (e) { 
        e.preventDefault();
        var po_id = $(this).prev('.po_id').val();
        var client_id = $(this).next('.client_id').val();
        $.post("<?php echo site_url('invoices/ajax/create_po_invoice'); ?>", {
            client_id: client_id, 
            po_id: po_id,
            invoice_date_created: '<?php echo date('d-M-Y') ?>',
            invoice_group_id: 3,
            invoice_time_created: '<?php echo date('H:i:s') ?>',
            invoice_password: '',
            user_id: '<?php echo $this->session->userdata('user_id'); ?>',
            payment_method: 4
        },
        function (data) {
            <?php   echo(IP_DEBUG ? 'console.log(data);' : ''); ?>
            var response = JSON.parse(data);
            if (response.success === 1) {
                // The validation was successful and invoice was created
                window.location = "<?php echo site_url('invoices/view'); ?>/" + response.invoice_id;
            }
            else {
                // The validation was not successful
                $('.control-group').removeClass('has-error');
                for (var key in response.validation_errors) {
                    $('#' + key).parent().parent().addClass('has-error');
                }
            }
        });      
    });
});
</script>
