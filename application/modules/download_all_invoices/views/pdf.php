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
        <div class="invoice-details clearfix">
            <table>
                <tr><th colspan="2" style="border-bottom:1px solid #173366;"> Original for Recipient</th></tr>
                <tr>
                    <td><b><?php echo 'Invoice number' . ':'; ?></b></td>
                    <td><?php echo trans($invoice_number); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo 'Invoice date' . ':'; ?></b></td>
                    <td><?php echo date_from_mysql($invoice_date_created, true); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo 'Due_date' . ': '; ?></b></td>
                    <td><?php echo date_from_mysql($invoice_date_due, true); ?></td>
                </tr>
                <?php if ($payment_method): ?>
                    <tr>
                        <td><b><?php echo 'Purchase Order No' . ': '; ?></b></td>
                          <td><?php echo $product_no; ?></td>
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
    
            </table>
        </div>
        </td>    
</tr>
</table>
    </div>
<footer>        
</footer>
</div>
</body>
</html>
