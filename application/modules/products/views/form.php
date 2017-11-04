<form method="post">

    <input type="hidden" name="<?php echo $this->config->item('csrf_token_name'); ?>"
           value="<?php echo $this->security->get_csrf_hash() ?>">

    <div id="headerbar">
        <h1 class="headerbar-title"><?php _trans('products_form'); ?></h1>
        <?php $this->layout->load_view('layout/header_buttons'); ?>
    </div>

    <div id="content">

        <div class="row">
            <div class="col-xs-12 col-md-6">

                <?php $this->layout->load_view('layout/alerts'); ?>

                <div class="panel panel-default">
                    <div class="panel-heading">

                        <?php if ($this->mdl_products->form_value('product_id')) : ?>
                            #<?php echo $this->mdl_products->form_value('product_id'); ?>&nbsp;
                            <?php echo $this->mdl_products->form_value('product_name', true); ?>
                        <?php else : ?>
                            <?php _trans('new_product'); ?>
                        <?php endif; ?>

                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="family_id">
                                <?php _trans('Category'); ?>
                            </label>

                            <select name="family_id" id="family_id" class="form-control simple-select">
                                <option value="0"><?php _trans('Select Category'); ?></option>
                                <?php foreach ($families as $family) { ?>
                                    <option value="<?php echo $family->family_id; ?>"
                                        <?php check_select($this->mdl_products->form_value('family_id'), $family->family_id) ?>
                                    ><?php echo $family->family_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="po_client_id">
                                <?php _trans('Client'); 

                                ?>
                            </label>

                            <select name="po_client_id" id="po_client_id" class="form-control simple-select">
                                <option value="0"><?php _trans('Select Client'); ?></option>
                                <?php foreach ($clients as $client) { ?>
                                    <option value="<?php echo $client->client_id; ?>"
                                    ><?php echo $client->client_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="empid">
                                <?php _trans('Employee Id'); ?>
                            </label>

                            <input type="text" name="empid" id="empid" class="form-control"
                                   value="<?php echo $this->mdl_products->form_value('empid', true); ?>">
                        </div>

                        <div class="form-group">
                            <label for="product_no">
                                <?php _trans('product_no'); ?>
                            </label>

                            <input type="text" name="product_no" id="product_no" class="form-control"
                                   value="<?php echo $this->mdl_products->form_value('product_no', true); ?>">
                        </div>
<?php 
$ps = $this->mdl_products->form_value('product_start', true);
$pe = $this->mdl_products->form_value('product_end', true);
if ($ps != "") {
    $ps = date_from_mysql($ps);
}
if ($pe != "") {
    $pe = date_from_mysql($pe);
}
?>
                        <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label><?php _trans('product_start'); ?></label>

                                <div class="input-group">
                                    <input name="product_start" id="product_start"
                                           class="form-control input-sm datepicker"
                                           value="<?php echo $ps; ?>">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar fa-fw"></i>
                                    </span>
                                </div>                                
                            </div>
                            <div class="col-md-6">
                                <label><?php _trans('product_end'); ?></label>

                                <div class="input-group">
                                    <input name="product_end" id="product_end"
                                           class="form-control input-sm datepicker"
                                           value="<?php echo $pe; ?>">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar fa-fw"></i>
                                    </span>
                                </div>                                
                            </div>
                            
                        </div>
                        </div>

                        <div class="form-group">
                            <label for="product_sku">
                                <?php _trans('product_sku'); ?>
                            </label>

                            <input type="text" name="product_sku" id="product_sku" class="form-control"
                                   value="<?php echo $this->mdl_products->form_value('product_sku', true); ?>">
                        </div>

                        <div class="form-group">
                            <label for="product_name">
                                <?php _trans('product_name'); ?>
                            </label>

                            <input type="text" name="product_name" id="product_name" class="form-control" required
                                   value="<?php echo $this->mdl_products->form_value('product_name', true); ?>">
                        </div>

                        <div class="form-group">
                            <label for="product_description">
                                <?php _trans('product_description'); ?>
                            </label>

                            <textarea name="product_description" id="product_description" class="form-control"
                                      rows="3"><?php echo $this->mdl_products->form_value('product_description', true); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="product_price">
                                <?php _trans('product_price'); ?>
                            </label>

                            <div class="input-group has-feedback">
                                <input type="text" name="product_price" id="product_price" class="form-control"
                                       value="<?php echo format_amount($this->mdl_products->form_value('product_price')); ?>">
                                <span class="input-group-addon"><?php echo get_setting('currency_symbol'); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="unit_id">
                                <?php _trans('product_unit'); ?>
                            </label>

                            <select name="unit_id" id="unit_id" class="form-control simple-select">
                                <option value="0"><?php _trans('select_unit'); ?></option>
                                <?php foreach ($units as $unit) { ?>
                                    <option value="<?php echo $unit->unit_id; ?>"
                                        <?php check_select($this->mdl_products->form_value('unit_id'), $unit->unit_id); ?>
                                    ><?php echo $unit->unit_name . '/' . $unit->unit_name_plrl; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tax_rate_id">
                                <?php _trans('tax_rate'); ?>
                            </label>

                            <select name="tax_rate_id" id="tax_rate_id" class="form-control simple-select">
                                <option value="0"><?php _trans('none'); ?></option>
                                <?php foreach ($tax_rates as $tax_rate) { ?>
                                    <option value="<?php echo $tax_rate->tax_rate_id; ?>"
                                        <?php check_select($this->mdl_products->form_value('tax_rate_id'), $tax_rate->tax_rate_id); ?>>
                                        <?php echo $tax_rate->tax_rate_name
                                            . ' (' . format_amount($tax_rate->tax_rate_percent) . '%)'; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-6">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php _trans('extra_information'); ?>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="provider_name">
                                <?php _trans('provider_name'); ?>
                            </label>

                            <input type="text" name="provider_name" id="provider_name" class="form-control"
                                   value="<?php echo $this->mdl_products->form_value('provider_name', true); ?>">
                        </div>

                        <div class="form-group">
                            <label for="purchase_price">
                                <?php _trans('purchase_price'); ?>
                            </label>

                            <div class="input-group has-feedback">
                                <input type="text" name="purchase_price" id="purchase_price" class="form-control"
                                       value="<?php echo format_amount($this->mdl_products->form_value('purchase_price')); ?>">
                                <span class="input-group-addon"><?php echo get_setting('currency_symbol'); ?></span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php _trans('invoice_sumex'); ?>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="product_tariff">
                                <?php _trans('product_tariff'); ?>
                            </label>

                            <input type="text" name="product_tariff" id="product_tariff" class="form-control"
                                   value="<?php echo $this->mdl_products->form_value('product_tariff', true); ?>">
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

</form>
