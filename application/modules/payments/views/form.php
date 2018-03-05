<script>
    $(function () {
        var $invoice_id = $('#invoice_id');
        $invoice_id.focus();

        amounts = JSON.parse('<?php echo $amounts; ?>');
        invoice_payment_methods = JSON.parse('<?php echo $invoice_payment_methods; ?>');
        $invoice_id.change(function () {
            var invoice_identifier = "invoice" + $('#invoice_id').val();
            $('#payment_amount').val(amounts[invoice_identifier].replace("&nbsp;", " "));
            $('#payment_method_id').find('option[value="' + invoice_payment_methods[invoice_identifier] + '"]').prop('selected', true);

            if (invoice_payment_methods[invoice_identifier] != 0) {
                $('.payment-method-wrapper').append("<input type='hidden' name='payment_method_id' id='payment-method-id-hidden' class='hidden' value='" + invoice_payment_methods[invoice_identifier] + "'>");
                $('#payment_method_id').prop('disabled', true);
            } else {
                $('#payment-method-id-hidden').remove();
                $('#payment_method_id').prop('disabled', false);
            }
        });

    });

    $(document).ready(function(){
        $("#payment_tds").change(function(){
            var amount_tds = $('#payment_tds').val();
            var invoice_id = $('#invoice_id').val();

           $.ajax({
                url: "payments/getInvoiceAmount",
                method: "post",
                dataType: 'json',
                data: {"amount_tds": amount_tds, "invoice_id":invoice_id},
                success: function(response) {
                //Do Something
                // alert(amount_tds);
                // alert(response[0].invoice_item_subtotal);
                // alert(response[0].invoice_paid);

                 var tds_amount = Math.round(((amount_tds / 100) * response[0].invoice_item_subtotal)*100)/100;
                 // alert(tds_amount);
                 var invoice_total = response[0].invoice_total;
                 var invoice_balance = response[0].invoice_balance;
                 var net_payment = Math.round((invoice_balance - tds_amount)*100)/100;
                 var invoice_paid = response[0].invoice_paid;
                 
                 if(invoice_paid != 0.00)
                 {
                    $('#payment_tds').val('0');
                    $('#payment_tds_amount').val('0');
                    $('#payment_amount').val(invoice_balance);
                 } 
                    else if (invoice_paid == 0.00)
                 {
                    $('#payment_tds_amount').val(tds_amount);
                    $('#payment_amount').val(net_payment);
                 }                     
                },
                error: function(xhr) {
                    //Do Something to handle error
                }
            });
        });
    });
</script>

<form method="post" class="form-horizontal">

    <input type="hidden" name="<?php echo $this->config->item('csrf_token_name'); ?>"
           value="<?php echo $this->security->get_csrf_hash() ?>">

    <?php if ($payment_id) { ?>
        <input type="hidden" name="payment_id" value="<?php echo $payment_id; ?>">
    <?php } ?>

    <div id="headerbar">
        <h1 class="headerbar-title"><?php _trans('payment_form'); ?></h1>
        <?php $this->layout->load_view('layout/header_buttons'); ?>
    </div>

    <div id="content">

        <?php $this->layout->load_view('layout/alerts'); ?>

        
        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_method_id" class="control-label">
                    <?php _trans('payment_method'); ?>
                </label>
            </div>
            <div class="col-xs-12 col-sm-6 payment-method-wrapper">

                <?php
                // Add a hidden input field if a payment method was set to pass the disabled attribute
                if ($this->mdl_payments->form_value('payment_method_id')) { ?>
                    <input type="hidden" name="payment_method_id" class="hidden"
                           value="<?php echo $this->mdl_payments->form_value('payment_method_id'); ?>">
                <?php } ?>

                <select id="payment_method_id" name="payment_method_id" class="form-control simple-select"
                    <?php echo($this->mdl_payments->form_value('payment_method_id') ? 'disabled="disabled"' : ''); ?>>

                    <?php foreach ($payment_methods as $payment_method) { ?>
                        <option value="<?php echo $payment_method->payment_method_id; ?>"
                                <?php if ($this->mdl_payments->form_value('payment_method_id') == $payment_method->payment_method_id) { ?>selected="selected"<?php } ?>>
                            <?php echo $payment_method->payment_method_name; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="invoice_id" class="control-label"><?php _trans('invoice'); ?></label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <select name="invoice_id" id="invoice_id" class="form-control simple-select">
                    <?php if (!$payment_id) { ?>                         
                            <option value="">Select Client</option> 
                        <?php foreach ($open_invoices as $invoice) { ?>
                            <option value="<?php echo $invoice->invoice_id; ?>"
                                <?php check_select($this->mdl_payments->form_value('invoice_id'), $invoice->invoice_id); ?>>
                                <?php echo $invoice->invoice_number . ' - ' . format_client($invoice) . ' - ' . format_currency($invoice->invoice_balance); ?>
                            </option>
                        <?php } ?>
                    <?php } else { ?>
                        <option value="<?php echo $payment->invoice_id; ?>">
                            <?php echo $payment->invoice_number . ' - ' . format_client($payment) . ' - ' . format_currency($payment->invoice_balance); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group has-feedback">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_date" class="control-label"><?php _trans('date'); ?></label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="input-group">
                    <input name="payment_date" id="payment_date"
                           class="form-control datepicker"
                           value="<?php echo date_from_mysql($this->mdl_payments->form_value('payment_date')); ?>">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar fa-fw"></i>
                    </span>
                </div>
            </div>
        </div>

       <!--  <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_amount" class="control-label"><?php echo "Net Payment"; ?></label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <input type="number" name="payment_amount" id="payment_amount" class="form-control"
                       value="<?php echo format_amount($this->mdl_payments->form_value('payment_amount')); ?>">
            </div>
        </div> -->

        

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_note" class="control-label"><?php _trans('note'); ?></label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <textarea name="payment_note"
                          class="form-control"><?php echo $this->mdl_payments->form_value('payment_note', true); ?></textarea>
            </div>
        </div>

        
        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_tds" class="control-label">
                    TDS
                </label>
            </div>

            <div class="col-xs-12 col-sm-6">
               <select id="payment_tds" name="payment_tds" class="form-control simple-select">
                    <option value="">Select</option>
                    <option value="0">0</option>
                    <option value="0.5">0.5</option>
                    <option value="1">1</option>
                    <option value="1.5">1.5</option>
                    <option value="2">2</option>
                    <option value="2.5">2.5</option>
                    <option value="3">3</option>
                    <option value="3.5">3.5</option>
                    <option value="4">4</option>
                    <option value="4.5">4.5</option>
                    <option value="5">5</option>

                    <option value="5.5">5.5</option>
                    <option value="6">6</option>
                    <option value="6.5">6.5</option>
                    <option value="7">7</option>
                    <option value="7.5">7.5</option>
                    <option value="8">8</option>
                    <option value="8.5">8.5</option>
                    <option value="9">9</option>
                    <option value="9.5">9.5</option>
                    <option value="10">10</option>
               </select>
            </div>
        </div>


        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_tds_amount" class="control-label">
                    TDS Amount
                </label>
            </div>

            <div class="col-xs-12 col-sm-6">
               <input type="number" name="payment_tds_amount" id="payment_tds_amount" class="form-control" step=".0001"
                       value="<?php echo format_amount($this->mdl_payments->form_value('payment_tds_amount')); ?>"> 
            </div>
        </div>

         <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_amount" class="control-label"><?php echo "Net Payment"; ?></label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <input type="number" name="payment_amount" id="payment_amount" class="form-control"
                       value="<?php echo format_amount($this->mdl_payments->form_value('payment_amount')); ?>" step=".0001">
            </div>
        </div>


        <?php
        $cv = $this->controller->view_data["custom_values"];
        foreach ($custom_fields as $custom_field) {
            print_field($this->mdl_payments, $custom_field, $cv, "col-xs-12 col-sm-2 text-right text-left-xs", "col-xs-12 col-sm-6");
        } ?>

    </div>

</form>
