<div id="headerbar">
    <!-- <h1 class="headerbar-title"><?php _trans('products'); ?></h1> -->
    <form class="navbar-form" action="<?php echo site_url('products/index'); ?>" method="get">
    <div class="col-xs-10 col-md-4">
        <div class="headerbar-item">
            <input type="text" name="product_search" class="form-control" placeholder="Search by PO Number or Product name"/>
            <button class="btn btn-primary">Submit</button>
        </div>  
    </div>
    </form>
    <form class="navbar-form" action="<?php echo site_url('products/index'); ?>" method="get">
    <div class="col-xs-10 col-md-4">
        <div class="headerbar-item">
            <input type="text" name="client_search" class="form-control" placeholder="Search by Client"/>
            <button class="btn btn-primary">Submit</button>
        </div>  
    </div>
    </form> 

    <div class="headerbar-item pull-right">
        <a class="btn btn-sm btn-primary" href="<?php echo site_url('products/form'); ?>">
            <i class="fa fa-plus"></i> <?php _trans('new'); ?>
        </a>
    </div>

    <div class="headerbar-item pull-right">
        <?php echo pager(site_url('products/index'), 'mdl_products'); ?>
    </div>

</div>

<div id="content" class="table-content">

    <?php $this->layout->load_view('layout/alerts'); ?>

    <div class="table-responsive">
        <table class="table table-striped">

            <thead>
            <tr>
                <th>Slno</th>
                <th><?php _trans('family'); ?></th>
                <th><?php _trans('product_no'); ?></th>
                <th><?php _trans('product_name'); ?></th>
                <th><?php _trans('client'); ?></th>
                <th><?php _trans('product_price'); ?></th>
                <th><?php _trans('product_unit'); ?></th>
                <th><?php _trans('tax_rate'); ?></th>
                <?php if (get_setting('sumex')) : ?>
                    <th><?php _trans('product_tariff'); ?></th>
                <?php endif; ?>
                <th><?php _trans('options'); ?></th>
            </tr>
            </thead>

            <tbody>
            <?php $i = 1; ?>
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td><?php echo $i; $i++; ?></td>
                    <td><?php _htmlsc($product->family_name); ?></td>
                    <td><?php _htmlsc($product->product_no); ?></td>
                    <td><?php _htmlsc($product->product_name); ?></td>
                    <td><?php 
$client = $this->mdl_clients->where('client_id',$product->po_client_id)->get()->result();
echo $client[0]->client_name;
                    ?></td>
                    <td class="amount"><?php echo format_currency($product->product_price); ?></td>
                    <td><?php _htmlsc($product->unit_name); ?></td>
                    <td><?php echo ($product->tax_rate_id) ? htmlsc($product->tax_rate_name) : trans('none'); ?></td>
                    <?php if (get_setting('sumex')) : ?>
                        <td><?php _htmlsc($product->product_tariff); ?></td>
                    <?php endif; ?>
                    <td>
                        <div class="options btn-group">
                            <a class="btn btn-default btn-sm dropdown-toggle"
                               data-toggle="dropdown" href="#">
                                <i class="fa fa-cog"></i> <?php _trans('options'); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url('products/form/' . $product->product_id); ?>">
                                        <i class="fa fa-edit fa-margin"></i> <?php _trans('edit'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('products/form/' . $product->product_id .'?copy=true'); ?>">
                                        <i class="fa fa-plus-square-o fa-margin"></i>Add Employee For PO
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('products/delete/' . $product->product_id); ?>"
                                       onclick="return confirm('<?php _trans('delete_record_warning'); ?>');">
                                        <i class="fa fa-trash-o fa-margin"></i> <?php _trans('delete'); ?>
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
