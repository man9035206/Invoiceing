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
        <h2 style="text-align:center;">TAX INVOICE</h2>
<header class="clearfix">

    <div id="logo">
        <?php echo invoice_logo_pdf(); ?>
    </div>

<table>
  <tr>
    <td valign="top"><b>From:</b></td>
    <td colspan="2" valign="top">
            <div id="company">
                <b>JoulestoWatts Business Solutions Private Ltd</b><br>
                SJRI Park, Plot No 13,14,15 EPIP<br>
                Bengaluru Karnataka 560066<br>
                India<br>
            </div>

    </td>
    <td rowspan="2" id="invoice-details-style">                        
        <div class="invoice-details clearfix" >
            <table>
                <tr><th colspan="2" style="border-bottom:1px solid #173366;"> Original for Recipient</th></tr>
                <tr>
                    <td><b><?php echo 'Invoice number' . ':'; ?></b></td>
                    <td><?php echo trans($invoice->invoice_number); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo 'Invoice date' . ':'; ?></b></td>
                    <td><?php echo date_from_mysql($invoice->invoice_date_created, true); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo 'Due_date' . ': '; ?></b></td>
                    <td><?php echo date_from_mysql($invoice->invoice_date_due, true); ?></td>
                </tr>
                <?php if ($payment_method): ?>
                    <tr>
                        <td><b><?php echo 'Purchase Order No' . ': '; ?></b></td>
                        <td><?php echo $items[0]->product_no; ?></td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td><b><?php echo 'Purchase Order Date' . ':'; ?></b></td>
                    <td><?php echo date_from_mysql($items[0]->product_start)." to ".date_from_mysql($items[0]->product_end); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo 'Payment Terms' . ': '; ?></b></td>
                    <td>
                    <?php
                    if ($custom_fields['client']['Payment Terms (In days)']) {
                        echo $custom_fields['client']['Payment Terms (In days)']." days"; 
                    } else {
                        echo get_setting('invoices_due_after')." days";
                    }
                     ?>                                 
                     </td>
                </tr>
                <?php if ($payment_method): ?>
                    <tr>
                        <td><b><?php echo 'Mode of Payment' . ': '; ?></b></td>
                        <td><?php _htmlsc($payment_method->payment_method_name); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td><b><?php echo 'PAN No' . ': '; ?></b></td>
                    <td>
                        <?php   $this->db->select("setting_value");
                                $this->db->from("ip_settings");
                                $this->db->or_where('setting_key', 'j2w_PAN');
                                $query = $this->db->get(); 
                                foreach ($query->result() as $row) {
                                    echo $row->setting_value;
                                } 
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?php echo 'GSTIN No' . ': '; ?></b></td>
                    <td><?php $this->db->select("setting_value");
                              $this->db->from("ip_settings");
                              $this->db->or_where('setting_key', 'j2w_GSTIN');
                              $query = $this->db->get(); 
                              foreach ($query->result() as $row) {
                                echo $row->setting_value;
                            } 
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?php echo 'HSN/SAC CODE' . ': '; ?></b></td>
                    <td><?php $this->db->select("setting_value");
                              $this->db->from("ip_settings");
                              $this->db->or_where('setting_key', 'j2w_SAC_Code');
                              $query = $this->db->get(); 
                              foreach ($query->result() as $row) {
                                echo $row->setting_value;
                            } ?>
                    </td>
                </tr>
                <?php if ($items[0]->po_pos): ?>
                    <tr>
                        <td><b><?php echo 'Place of Supply' . ': '; ?></b></td>
                        <td><?php _htmlsc($items[0]->po_pos); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($items[0]->po_state_code): ?>
                    <tr>
                        <td><b><?php echo 'State Code' . ': '; ?></b></td>
                        <td><?php _htmlsc($items[0]->po_state_code); ?></td>
                    </tr>
                <?php endif; ?>

                <?php if ($invoice->invoice_item_tax_total == 0) { ?>
                        <tr>
                            <td><b><?php echo 'LUT No' . ': '; ?></b></td>
                            <td><?php $this->db->select("setting_value");
                                      $this->db->from("ip_settings");
                                      $this->db->or_where('setting_key', 'j2w_LUT_number');
                                      $query = $this->db->get(); 
                                      foreach ($query->result() as $row) {
                                        echo $row->setting_value;
                                    } ?>
                            </td>
                        </tr>
                <?php } ?>
                <?php if ($items[0]->po_reverse_charge): ?>
                    <tr>
                        <td><b><?php echo 'Reverse Charge' . ': '; ?></b></td>
                        <td><?php _htmlsc($items[0]->po_reverse_charge); ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </td>
  </tr>
  <tr>
    <td valign="top"><b style="font-size:8px;">To :</b> </td>
    <td valign="top" style="padding-right:10px; width:210px;">
        <?php   if($items[0]->po_billing_address) { ?>
                <div>
                <b style="font-size:8px;">    <?php echo $invoice->client_name; ?></b>
                </div>
        <?php  $saddress = $this->db->query('SELECT * FROM ip_shipping_address where id = '.$items[0]->po_billing_address);
                foreach ($saddress->result() as $row) {
                    echo $row->address."<br>";
                    if ($row->gst_no != "") {
                        echo "<b>GSTIN :</b>".$row->gst_no."<br>";
                    }
                    if ($row->sac_code != "") {
                        echo "<b>HSN/SAC Code :</b>".$row->sac_code;
                    }
                }
            }
        ?>
    </td>
    <td valign="top" style="padding-right:10px; width:210px;">
        <?php   if($items[0]->po_shipping_address) { ?>
                <div>
                <b style="font-size:8px;">Ship To : <?php echo $invoice->client_name;  ?></b>
                </div>
        <?php   $saddress = $this->db->query('SELECT * FROM ip_shipping_address where id = '.$items[0]->po_shipping_address);
                        foreach ($saddress->result() as $row) {
                            echo $row->address."<br>";
                            if ($row->gst_no != "") {
                                echo "<b>GSTIN :</b>".$row->gst_no."<br>";
                            }
                            if ($row->sac_code != "") {
                                echo "<b>HSN/SAC Code :</b>".$row->sac_code;
                            }
                        }
                    }
        ?>
    </td>
  </tr>
</table> 

    <table>
        <tr>


            <td style="padding-right:30px;width:210px;">

            </td>



            <td style="padding-right:30px; width:250px;">

            
            </td>



            
        </tr>
    </table>
        

    <div id="client_sa" class="caddress">

    </div>
</header>

<main>
    <table class="item-table">
        <thead>
        <tr>
            <th class="item-name"><?php _trans('Employee Id'); ?></th>
            <th colspan="2" class="item-desc"><?php _trans('description'); ?></th>
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
                <td><?php echo $item->empid; ?></td>
                <td colspan="2" style="width: 300px;">

                <b><u>Consultant Name :</u> <?php _htmlsc($item->item_name); ?></b><br>

                <?php 
                if ($item->invoice_end) {
                    $yrdata= strtotime($item->invoice_end);
                    echo "<br>".date('F Y', $yrdata)." (".date_from_mysql($item->invoice_start)." to ".date_from_mysql($item->invoice_end).")";
                }
                if($item->total_days) {
                    echo "<br>Applicable Working Days – ".$item->total_days;
                }
                if($item->worked_days) {
                    echo "<br>Charged Working Days  – ".$item->worked_days;                    
                }
                echo "<br><br>".nl2br(htmlsc(po_desc($item->item_description))); 
                ?>
                <br><br>
                </td>
                <td class="text-right">
                    <?php echo number_format($item->item_quantity,2); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($item->item_price); ?>
                </td>
                <td class="text-right">
                    <?php  echo format_currency($item->item_quantity * $item->item_price); ?>
                    <?php // echo format_currency($item->item_total); ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
        <tbody class="invoice-sums">

        <tr>
            <td class="empty-cell"></td>
            <td colspan="2" class="empty-cell"></td>
            <td class="empty-cell"></td>
            <td class="text-right empty-cell">
                
            </td>
            <td class="text-right"></td>
        </tr>

        <tr>
            <td class="empty-cell"></td>
            <td colspan="2" class="empty-cell"></td>
            <td class="empty-cell"></td>
            <td class="text-right empty-cell">
            </td>
            <td class="text-right"></td>
        </tr>
        <tr>
            <td class="empty-cell"></td>
            <td colspan="2" class="empty-cell"></td>
            <td class="empty-cell"></td>
            <td class="text-right empty-cell">
                
            </td>
            <td class="text-right"></td>
        </tr>

        <tr>
            <td class="empty-cell"></td>
            <td colspan="2" class="empty-cell"></td>
            <td class="empty-cell"></td>
            <td class="text-right empty-cell">
            </td>
            <td class="text-right"></td>
        </tr>   
        <tr>
            <td class="empty-cell"></td>
            <td colspan="2" class="empty-cell" align="right">
                <?php _trans('subtotal'); ?>
            </td>
            <td class="empty-cell"></td>
            <td class="text-right empty-cell"></td>
            <td class="text-right"><?php echo format_currency($invoice->invoice_item_subtotal); ?></td>
        </tr>

        <?php if ($invoice->invoice_item_tax_total) { ?>
            <?php if($item->tax_rate_id == 5) { ?>

            <tr>
            <td class="empty-cell"></td>
            <td colspan="2" class="empty-cell" align="right">CGST @ 9%</td>
            <td class="empty-cell"></td>
            <td class="text-right empty-cell"></td>
            <td class="text-right">
                <?php echo format_currency(($invoice->invoice_item_tax_total)/2); ?>
            </td>
            </tr>
            <tr>
            <td class="empty-cell"></td>
            <td colspan="2" class="empty-cell" align="right">SGST @ 9%</td>
            <td class="empty-cell"></td>
            <td class="text-right empty-cell"></td>
            <td class="text-right">
                <?php echo format_currency(($invoice->invoice_item_tax_total)/2); ?>
            </td>
            </tr>

            <?php } else { ?>
            <tr>
            <td class="empty-cell"></td>
            <td colspan="2" class="empty-cell" align="right"><?php echo $item->item_tax_rate_name; ?></td>
            <td class="empty-cell"></td>
            <td class="text-right empty-cell"></td>
                <td class="text-right">
                    <?php echo format_currency($invoice->invoice_item_tax_total); ?>
                </td>
            </tr>
        <?php }
        } ?>

        <tr>
            <td colspan="5" class="text-right ta-cell">
                <b><?php _trans('TOTAL AMOUNT'); ?></b>
            </td>
            <td class="text-right ta-cell">
                <b><?php echo format_currency($invoice->invoice_total); ?></b>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="text-right">
                <?php _trans('paid'); ?>
            </td>
            <td class="text-right">
                <?php echo format_currency($invoice->invoice_paid); ?>
            </td>
        </tr>
        <tr>
            <td><b>TOTAL (IN WORDS)</b></td>
            <td colspan="3"><?php echo numTowords($invoice->invoice_total) ; ?> ONLY.</td>
            <td class="text-right">
                <b><?php _trans('balance'); ?></b>
            </td>
            <td class="text-right">
                <b><?php echo format_currency($invoice->invoice_balance); ?></b>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="text-left footer" style="border-right:1px solid #003366; border-top:1px solid #003366;">
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
              <td colspan="2" class="footer" style="border-right:1px solid #003366;">
                  JoulestoWatts Business Solutions Private Limited
              </td>
              <td class="footer" colspan="2"></td>
        </tr>
        <tr>
             <td colspan="2" class="text-right footer">
                        <b>Bank Name</b>
              </td>
              <td colspan="2" class="footer" style="border-right:1px solid #003366;">
              <?php $this->db->select("setting_value");
                        $this->db->from("ip_settings");
                        $this->db->or_where('setting_key', 'Bank_Name');
                        $query = $this->db->get(); 
                        foreach ($query->result() as $row) {
                            echo $row->setting_value;
                        } ?>
              </td>
              <td class="footer" colspan="2"></td>
        </tr>
        <tr>
             <td colspan="2" class="text-right footer">
                        <b>Bank A/c No.</b>
              </td>
              <td colspan="2" class="footer" style="border-right:1px solid #003366;">
              <?php $this->db->select("setting_value");
                        $this->db->from("ip_settings");
                        $this->db->or_where('setting_key', 'Bank_A/c_No');
                        $query = $this->db->get(); 
                        foreach ($query->result() as $row) {
                            echo $row->setting_value;
                        } ?>
              </td>
              <td class="footer" colspan="2"></td>
        </tr>
        <tr>
             <td colspan="2" class="text-right footer">
                        <b>Branch</b>
              </td>
              <td colspan="2" class="footer" style="border-right:1px solid #003366;">
              <?php $this->db->select("setting_value");
                        $this->db->from("ip_settings");
                        $this->db->or_where('setting_key', 'Bank_Branch');
                        $query = $this->db->get(); 
                        foreach ($query->result() as $row) {
                            echo $row->setting_value;
                        } ?>
              </td>
              <td class="footer" colspan="2"></td>
        </tr>
        <tr>
             <td colspan="2" class="text-right footer">
                        <b>IFSC Code</b>
              </td>
              <td colspan="2" class="footer" style="border-right:1px solid #003366;">
              <?php $this->db->select("setting_value");
                        $this->db->from("ip_settings");
                        $this->db->or_where('setting_key', 'IFSC_Code');
                        $query = $this->db->get(); 
                        foreach ($query->result() as $row) {
                            echo $row->setting_value;
                        } ?>
              </td>
              <td colspan="2" class="footer"></td>
        </tr>
        <tr>
             <td colspan="2" class="text-right footer">
                        <b>MICR Code</b>
              </td>
              <td colspan="2" class="footer" style="border-right:1px solid #003366;">
              <?php $this->db->select("setting_value");
                        $this->db->from("ip_settings");
                        $this->db->or_where('setting_key', 'MICR_Code');
                        $query = $this->db->get(); 
                        foreach ($query->result() as $row) {
                            echo $row->setting_value;
                        } ?>
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
