<!DOCTYPE html>
<html lang="<?php _trans('cldr'); ?>">
<head>
    <meta charset="utf-8">
    <title><?php _trans('invoice'); ?></title>
    <link rel="stylesheet"
          href="<?php echo base_url(); ?>assets/<?php echo get_setting('system_theme', 'invoiceplane'); ?>/css/templates.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/core/css/custom-pdf.css">
</head>
<body>
<div id="content">
        <h2 style="text-align:center;">INVOICE</h2>
<header class="clearfix">

    <div id="logo">
        <?php echo invoice_logo_pdf(); ?>
    </div>
    <div id="company">
        <div><b>From:   <?php _htmlsc($invoice->user_company); ?></b></div>
        <?php 

        // if ($invoice->user_vat_id) {
        //     echo '<div>' . trans('vat_id_short') . ': ' . $invoice->user_vat_id . '</div>';
        // }
        // if ($invoice->user_tax_code) {
        //     echo '<div>' . trans('tax_code_short') . ': ' . $invoice->user_tax_code . '</div>';
        // }
        if ($invoice->user_address_1) {
            echo '<div>' . htmlsc($invoice->user_address_1) . '</div>';
        }
        if ($invoice->user_address_2) {
            echo '<div>' . htmlsc($invoice->user_address_2) . '</div>';
        }
        if ($invoice->user_city || $invoice->user_state || $invoice->user_zip) {
            echo '<div>';
            if ($invoice->user_city) {
                echo htmlsc($invoice->user_city) . ' ';
            }
            if ($invoice->user_state) {
                echo htmlsc($invoice->user_state) . ' ';
            }
            if ($invoice->user_zip) {
                echo htmlsc($invoice->user_zip);
            }
            echo '</div>';
        }
        if ($invoice->user_country) {
            echo '<div>' . get_country_name(trans('cldr'), $invoice->user_country) . '</div>';
        }

        echo '<br/>';

        if ($invoice->user_phone) {
            echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($invoice->user_phone) . '</div>';
        }
        if ($invoice->user_fax) {
            echo '<div>' . trans('fax_abbr') . ': ' . htmlsc($invoice->user_fax) . '</div>';
        }
        ?>
    </div>


    
    <table>
        <tr>
            <td style="padding-right:30px;">
                <div>
                <b>To :     <?php _htmlsc(format_client($invoice)); ?></b>
                </div>
<?php $ibills = $this->db->query('SELECT * FROM `invoice_shipping` where invoice_id ='.$invoice->invoice_id.' and ibill = 1 limit 1'); 
 foreach ($ibills->result() as $ibill)
        {
            $saddress = $this->db->query('SELECT * FROM ip_shipping_address where id = '.$ibill->shipping_id);
                foreach ($saddress->result() as $row) {
                    echo $row->address."<br>";
                    if ($row->gst_no != "") {
                        echo "<b>GSTIN :</b>".$row->gst_no."<br>";
                    }
                    if ($row->sac_code != "") {
                        echo "<b>SAC Code :</b>".$row->sac_code;
                    }
                }
        }
?>
            </td>
<?php  $iships = $this->db->query('SELECT * FROM `invoice_shipping` where invoice_id ='.$invoice->invoice_id.' and ibill = 0 limit 1'); 
if ($iships) {
?>
            <td style="padding-right:30px; width:30%;">
                <div>
                <b>Ship To :     <?php _htmlsc(format_client($invoice)); ?></b>
                </div>
<?php 
 foreach ($iships->result() as $iship)
        {
            $saddress = $this->db->query('SELECT * FROM ip_shipping_address where id = '.$iship->shipping_id);
                foreach ($saddress->result() as $row) {
                    echo $row->address."<br>";
                    if ($row->gst_no != "") {
                        echo "<b>GSTIN :</b>".$row->gst_no."<br>";
                    }
                    if ($row->sac_code != "") {
                        echo "<b>SAC Code :</b>".$row->sac_code;
                    }
                }
        }
?>
            
            </td>
<?php
}
    $user_field = $this->db->query('SELECT * FROM `ip_user_custom` where user_id ='.$invoice->user_id);

    foreach ($user_field->result() as $row)
        {
            if ($row->user_custom_fieldid == 6) {
                $upan = $row->user_custom_fieldvalue;
            }
            if ($row->user_custom_fieldid == 7) {
                $ugst = $row->user_custom_fieldvalue;
            }
            if ($row->user_custom_fieldid == 8) {
                $usac = $row->user_custom_fieldvalue;
            }
        }
    ?>
            <td id="invoice-details-style">                        
                <div class="invoice-details clearfix" >
                    <table>
                        <tr>
                            <td><?php echo trans('Invoice_number') . ':'; ?></td>
                            <td><?php echo trans($invoice->invoice_number); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo trans('Invoice_date') . ':'; ?></td>
                            <td><?php echo date_from_mysql($invoice->invoice_date_created, true); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo trans('Due_date') . ': '; ?></td>
                            <td><?php echo date_from_mysql($invoice->invoice_date_due, true); ?></td>
                        </tr>
                        <?php if ($payment_method): ?>
                            <tr>
                                <td><?php echo trans('Purchase Order No') . ': '; ?></td>
                                <td><?php echo $spo ?></td>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <td><?php echo trans('Purchase Order Date') . ':'; ?></td>
                            <td><?php echo $spod; ?></td>
                        </tr>
                        <?php if ($payment_method): ?>
                            <tr>
                                <td><?php echo trans('Payment Terms') . ': '; ?></td>
                                <td><?php _htmlsc($payment_method->payment_method_name); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td><?php echo trans('PAN No') . ': '; ?></td>
                            <td><?php echo $upan; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo trans('GSTIN No') . ': '; ?></td>
                            <td><?php echo $ugst; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo trans('SAC No') . ': '; ?></td>
                            <td><?php echo $usac; ?></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
        

    <div id="client_sa" class="caddress">

    </div>
</header>

<main>
<div style="text-align:center">Kind Attention <strong><?php echo $invoice->client_surname; ?></strong></div>
    <table class="item-table">
        <thead>
        <tr>
            <th class="item-name"><?php _trans('Sl.#'); ?></th>
            <th class="item-desc"><?php _trans('description'); ?></th>
            <th class="item-amount text-right"><?php _trans('qty'); ?></th>
            <th class="item-price text-right"><?php _trans('price'); ?></th>
            <?php if ($show_item_discounts) : ?>
                <th class="item-discount text-right"><?php _trans('discount'); ?></th>
            <?php endif; ?>
            <th class="item-total text-right"><?php _trans('total'); ?></th>
        </tr>
        </thead>
        <tbody>

        <?php
        $i = 1;
        foreach ($items as $item) { ?>
            <tr class= "items-list">
                <td><?php echo $i; $i++; ?></td>
                <td><b><?php _htmlsc($item->item_name); ?></b><br>
                <?php echo nl2br(htmlsc($item->item_description)); ?></td>
                <td class="text-right">
                    <?php echo format_amount($item->item_quantity); ?>
                    <?php if ($item->item_product_unit) : ?>
                        <br>
                        <small><?php _htmlsc($item->item_product_unit); ?></small>
                    <?php endif; ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($item->item_price); ?>
                </td>
                <?php if ($show_item_discounts) : ?>
                    <td class="text-right">
                        <?php echo format_currency($item->item_discount); ?>
                    </td>
                <?php endif; ?>
                <td class="text-right">
                    <?php echo format_currency($item->item_total); ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
        <tbody class="invoice-sums">

        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                <?php _trans('subtotal'); ?>
            </td>
            <td class="text-right"><?php echo format_currency($invoice->invoice_item_subtotal); ?></td>
        </tr>

        <?php if ($invoice->invoice_item_tax_total > 0) { ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php _trans('item_tax'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice->invoice_item_tax_total); ?>
                </td>
            </tr>
        <?php } ?>

        <?php foreach ($invoice_tax_rates as $invoice_tax_rate) : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php echo htmlsc($invoice_tax_rate->invoice_tax_rate_name) . ' (' . format_amount($invoice_tax_rate->invoice_tax_rate_percent) . '%)'; ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice_tax_rate->invoice_tax_rate_amount); ?>
                </td>
            </tr>
        <?php endforeach ?>

        <?php if ($invoice->invoice_discount_percent != '0.00') : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php _trans('discount'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_amount($invoice->invoice_discount_percent); ?>%
                </td>
            </tr>
        <?php endif; ?>
        <?php if ($invoice->invoice_discount_amount != '0.00') : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php _trans('discount'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice->invoice_discount_amount); ?>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                <b><?php _trans('TOTAL AMOUNT'); ?></b>
            </td>
            <td class="text-right">
                <b><?php echo format_currency($invoice->invoice_total); ?></b>
            </td>
        </tr>
        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                <?php _trans('paid'); ?>
            </td>
            <td class="text-right">
                <?php echo format_currency($invoice->invoice_paid); ?>
            </td>
        </tr>
        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                <b><?php _trans('balance'); ?></b>
            </td>
            <td class="text-right">
                <b><?php echo format_currency($invoice->invoice_balance); ?></b>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="text-left footer" style="border-right:1px solid #003366; border-top:1px solid #003366;">
                <b>Remarks :</b> 
All disputes subject to Bangalore Jurisdiction.
            </td>
            <td colspan="2" class="text-left footer" style="padding:5px 2px 10px; border-top:1px solid #003366;">
                <b>for JoulestoWatts Business Solutions Private Limited</b>
            </td>
        </tr>


        <tr>
             <td colspan="2" class="text-right footer">
                        <b>Bank A/c Name</b>
              </td>
              <td class="footer" style="border-right:1px solid #003366;">
                  JoulestoWatts Business Solutions Private Limited
              </td>
              <td class="footer" colspan="2"></td>
        </tr>
        <tr>
             <td colspan="2" class="text-right footer">
                        <b>Bank Name</b>
              </td>
              <td class="footer" style="border-right:1px solid #003366;">
                  SBI Bank
              </td>
              <td class="footer" colspan="2"></td>
        </tr>
        <tr>
             <td colspan="2" class="text-right footer">
                        <b>Bank A/c No.</b>
              </td>
              <td class="footer" style="border-right:1px solid #003366;">
                  35509831439
              </td>
              <td class="footer" colspan="2"></td>
        </tr>
        <tr>
             <td colspan="2" class="text-right footer">
                        <b>Branch</b>
              </td>
              <td class="footer" style="border-right:1px solid #003366;">
                  SME Mahadevapura (03028)
              </td>
              <td class="footer" colspan="2"></td>
        </tr>
        <tr>
             <td colspan="2" class="text-right footer">
                        <b>IFSC Code</b>
              </td>
              <td class="footer" style="border-right:1px solid #003366;">
                  SBIN0003028
              </td>
              <td colspan="2" class="footer"></td>
        </tr>
        <tr>
             <td colspan="2" class="text-right footer">
                        <b>MICR Code</b>
              </td>
              <td class="footer" style="border-right:1px solid #003366;">
                  560002019
              </td>
              <td colspan="2" class="footer" style="text-align:center;"> <b>AUTHORISED SIGNATORY</b> </td>
        </tr>
        </tbody>
    </table>

</main>

<footer>        
</footer>
</div>
</body>
</html>
