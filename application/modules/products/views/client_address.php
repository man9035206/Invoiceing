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

                                        echo "<div class='col-xs-11'><p>".$row->address."</p><p><b>GST No :</b>".$row->gst_no." <b>SAC code :</b>".$row->sac_code."</p></div></div>";
                                    }
                                ?>
                    <?php
                        }
                    ?>

                    <?php 
                    if($shipping_address) { 
                        ?>

                            <label for="po_shipping_address">
                                Shipping Address
                            </label>
                                <?php
                                foreach ($shipping_address as $row)
                                    {
                                     
                                        if($s_id == $row->id){                         
                                            echo "<div class='po_billing_address'><div class='col-xs-1'>
                                            <input type='radio' name='po_shipping_address' value=".$row->id." checked></div>";

                                        } else {                            
                                            echo "<div class='po_billing_address'><div class='col-xs-1'>
                                            <input type='radio' name='po_shipping_address' value=".$row->id."></div>";

                                        }   
                                           
                                        echo "<div class='col-xs-11'><p>".$row->address."</p><p><b>GST No :</b>".$row->gst_no.", <b>SAC code :</b>".$row->sac_code."</p></div></div>";
                                    }
                                ?>
                    <?php
                        }
                    ?>