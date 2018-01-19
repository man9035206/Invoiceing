
<?php 
                                $cid = $c_id;
                                if ($po_no) {
?>
                        <div class="form-group">
                            <label for="product_no">
                                <?php _trans('product_no'); ?>
                            </label>

                                <input type="text" name="product_no" id="product_no" class="form-control"
                                   value="<?php echo $po_no; ?>">
                        </div>
<?php

                                }elseif ($cid) {
?>

                        <div class="form-group">
                            <label for="product_no">
                                <?php _trans('product_no'); ?>
                            </label>
<?php

                                $c = $this->db->query('select * from ip_client_custom where client_id = '.$cid.' and client_custom_fieldid = 12')->result();
                                    if ($c[0]->client_custom_fieldvalue == '2') {
?>

                                <input type="text" name="product_no" id="product_no" class="form-control"
                                   value="">
<?php
                                    } else {
                                        $po_no = "J2W-".$p_id;
?>
                                <input type="text" name="product_no" id="product_no" class="form-control"
                                   value="<?php echo $po_no; ?>">
<?php
                                    }
                                    echo "</div>";
                                } 
?>                    

<?php
/** For Client Address **/
$billing_address = $this->db->get_where( 
                'ip_shipping_address', array(
                'client_id' => $c_id,
                'billing_address' => 1
            ))->result();

$shipping_address = $this->db->get_where(
                'ip_shipping_address', array(
                'client_id' => $c_id,
                'billing_address' => 0
            ))->result();

             if($billing_address) { 
                    ?>
                            <label for="po_billing_address">
                                Billing Address
                            </label>
                                <?php
                                foreach ($billing_address as $row)
                                    {                          
                                      
                                        if($b_id == $row->id){                         
                                            echo "<div class='po_billing_address'><div class='col-xs-1'>
                                            <input type='radio' name='po_billing_address' value=".$row->id." checked></div>";

                                        } else {                            
                                            echo "<div class='po_billing_address'><div class='col-xs-1'>
                                            <input type='radio' name='po_billing_address' value=".$row->id."></div>";

                                        }

                                        echo "<div class='col-xs-11'><p>".$row->address."</p><p><b>GST No :</b>".$row->gst_no." <b>HSN/SAC Code :</b>".$row->sac_code."</p></div></div>";
                                    }
                                ?>              
                    <?php
                        }
                    ?>



                 <div class="shipping_address"> 
                    <?php 
                    if($shipping_address) {
                        ?>


                            <label for="po_shipping_address">
                                Shipping Address
                            </label>
                                <?php

                            echo "<div class='po_shipping_address'><div class='col-xs-1'>
                               <input type='radio' name='po_shipping_address' value='' checked></div>";
                            echo "<div class='col-xs-11'><p>No Shipping address</p></div></div>";

                                foreach ($shipping_address as $row)
                                    {
                                     
                                        if($s_id == $row->id){                         
                                            echo "<div class='po_shipping_address'><div class='col-xs-1'>
                                            <input type='radio' name='po_shipping_address' value=".$row->id." checked></div>";

                                        } else {                            
                                            echo "<div class='po_shipping_address'><div class='col-xs-1'>
                                            <input type='radio' name='po_shipping_address' value=".$row->id."></div>";

                                        }   
                                           
                                        echo "<div class='col-xs-11'><p>".$row->address."</p><p><b>GST No :</b>".$row->gst_no.", <b>HSN/SAC Code :</b>".$row->sac_code."</p></div></div>";
                                    }

                                ?>
                    <?php
                        }
                    ?>
                </div>    


                <?php if($c_id) { ?>
                    <div class="col-md-6">
                            <strong>
                                <input type='radio' name='po_billing_address' value="new">
                                New Billing Address
                            </strong>
                            <div class="fieldwrapper" id="field<?php echo $i; ?>">
                                <label>Address:</label><br>
                                <textarea class="fieldname" name="billing_address_a"></textarea><br>
                                <label>GST:</label><br><input value="" type="text" name="billing_address_gst"><br>
                                <label>HSN/SAC Code:</label><br><input value="" type="text" name="billing_address_sac">
                            </div>                        
                    </div>
                    <div class="col-md-6 shipping_address">
                            <strong> 
                                <input type='radio' name='po_shipping_address' value="new">
                                New Shipping Address
                            </strong>
                            <div class="fieldwrapper" id="field<?php echo $i; ?>">
                                <label>Address:</label><br>
                                <textarea class="fieldname" name="shipping_address_a"></textarea><br>
                                <label>GST:</label><br><input value="" type="text" name="shipping_address_gst"><br>
                                <label>HSN/SAC Code:</label><br><input value="" type="text" name="shipping_address_sac">
                            </div>                
                    </div>
                <?php } ?>