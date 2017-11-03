<?php
$cv = $this->controller->view_data['custom_values'];
?>

<script type="text/javascript">
    $(function () {
        $("#client_country").select2({
            placeholder: "<?php _trans('country'); ?>",
            allowClear: true
        });
    });
</script>

<form method="post">

    <input type="hidden" name="<?php echo $this->config->item('csrf_token_name'); ?>"
           value="<?php echo $this->security->get_csrf_hash() ?>">

    <div id="headerbar">
        <h1 class="headerbar-title"><?php _trans('client_form'); ?></h1>
        <?php $this->layout->load_view('layout/header_buttons'); ?>
    </div>

    <div id="content">

        <?php $this->layout->load_view('layout/alerts'); ?>

        <input class="hidden" name="is_update" type="hidden"
            <?php if ($this->mdl_clients->form_value('is_update')) {
                echo 'value="1"';
            } else {
                echo 'value="0"';
            } ?>
        >

        <div class="row">
            <div class="col-xs-12 col-sm-6">

                <div class="panel panel-default">
                    <div class="panel-heading form-inline clearfix">
                        <?php _trans('personal_information'); ?>

                        <div class="pull-right">
                            <label for="client_active" class="control-label">
                                <?php _trans('active_client'); ?>
                                <input id="client_active" name="client_active" type="checkbox" value="1"
                                    <?php if ($this->mdl_clients->form_value('client_active') == 1
                                        || !is_numeric($this->mdl_clients->form_value('client_active'))
                                    ) {
                                        echo 'checked="checked"';
                                    } ?>>
                            </label>
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="form-group">
                            <label for="client_name">
                                <?php _trans('client_name'); ?>
                            </label>
                            <input id="client_name" name="client_name" type="text" class="form-control" required
                                   autofocus
                                   value="<?php echo $this->mdl_clients->form_value('client_name', true); ?>">
                        </div>

                        <div class="form-group">
                            <label for="client_surname">
                                Contact Person Name
                            </label>
                            <input id="client_surname" name="client_surname" type="text" class="form-control"
                                   value="<?php echo $this->mdl_clients->form_value('client_surname', true); ?>">
                        </div>

                        <div class="form-group">
                            <label for="client_vat_id"><?php _trans('vat_id'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_vat_id" id="client_vat_id" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_vat_id', true); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="client_tax_code"><?php _trans('tax_code'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_tax_code" id="client_tax_code" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_tax_code', true); ?>">
                            </div>
                        </div>

                        <!-- Custom fields -->
                        <?php foreach ($custom_fields as $custom_field): ?>
                            <?php if ($custom_field->custom_field_location != 4) {
                                continue;
                            } ?>
                            <?php print_field($this->mdl_clients, $custom_field, $cv); ?>
                        <?php endforeach; ?>

                        <div class="form-group no-margin hidden">
                            <label for="client_language">
                                <?php _trans('language'); ?>
                            </label>
                            <select name="client_language" id="client_language" class="form-control simple-select">
                                <option value="system">
                                    <?php _trans('use_system_language') ?>
                                </option>
                                <?php foreach ($languages as $language) {
                                    $client_lang = $this->mdl_clients->form_value('client_language');
                                    ?>
                                    <option value="<?php echo $language; ?>"
                                        <?php check_select($client_lang, $language) ?>>
                                        <?php echo ucfirst($language); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group hidden">
                            <label for="client_gender"><?php _trans('gender'); ?></label>

                            <div class="controls">
                                <select name="client_gender" id="client_gender" class="form-control simple-select">
                                    <?php
                                    $genders = array(
                                        trans('gender_male'),
                                        trans('gender_female'),
                                        trans('gender_other'),
                                    );
                                    foreach ($genders as $key => $val) { ?>
                                        <option value=" <?php echo $key; ?>" <?php check_select($key, $this->mdl_clients->form_value('client_gender')) ?>>
                                            <?php echo $val; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group has-feedback hidden">
                            <label for="client_birthdate"><?php _trans('birthdate'); ?></label>
                            <?php
                            $bdate = $this->mdl_clients->form_value('client_birthdate');
                            if ($bdate && $bdate != "0000-00-00") {
                                $bdate = date_from_mysql($bdate);
                            } else {
                                $bdate = '';
                            }
                            ?>
                            <div class="input-group">
                                <input type="text" name="client_birthdate" id="client_birthdate"
                                       class="form-control datepicker"
                                       value="<?php _htmlsc($bdate); ?>">
                                <span class="input-group-addon">
                                <i class="fa fa-calendar fa-fw"></i>
                            </span>
                            </div>
                        </div>

                        <?php if ($this->mdl_settings->setting('sumex') == '1'): ?>

                            <div class="form-group">
                                <label for="client_avs"><?php _trans('sumex_ssn'); ?></label>
                                <?php $avs = $this->mdl_clients->form_value('client_avs'); ?>
                                <div class="controls">
                                    <input type="text" name="client_avs" id="client_avs" class="form-control"
                                           value="<?php echo htmlspecialchars(format_avs($avs)); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="client_insurednumber"><?php _trans('sumex_insurednumber'); ?></label>
                                <?php $insuredNumber = $this->mdl_clients->form_value('client_insurednumber'); ?>
                                <div class="controls">
                                    <input type="text" name="client_insurednumber" id="client_insurednumber"
                                           class="form-control"
                                           value="<?php echo htmlentities($insuredNumber); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="client_veka"><?php _trans('sumex_veka'); ?></label>
                                <?php $veka = $this->mdl_clients->form_value('client_veka'); ?>
                                <div class="controls">
                                    <input type="text" name="client_veka" id="client_veka" class="form-control"
                                           value="<?php echo htmlentities($veka); ?>">
                                </div>
                            </div>

                        <?php endif; ?>

                        <!-- Custom fields -->
                        <?php foreach ($custom_fields as $custom_field): ?>
                            <?php if ($custom_field->custom_field_location != 3) {
                                continue;
                            } ?>
                            <?php print_field($this->mdl_clients, $custom_field, $cv); ?>
                        <?php endforeach; ?>

                    </div>
                </div>

            </div>
            <?php if ($custom_fields): ?>
                <div class="col-xs-12 col-md-6">

                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <?php _trans('custom_fields'); ?>
                        </div>

                        <div class="panel-body">
                            <?php foreach ($custom_fields as $custom_field): ?>
                                <?php if ($custom_field->custom_field_location != 0) {
                                    continue;
                                }
                                print_field($this->mdl_clients, $custom_field, $cv);
                                ?>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
        <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <?php _trans('address'); ?>
                    </div>

                    <div class="panel-body">
                        <div class="form-group hidden">
                            <label for="client_address_1"><?php _trans('street_address'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_address_1" id="client_address_1" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_address_1', true); ?>">
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label for="client_address_2"><?php _trans('street_address_2'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_address_2" id="client_address_2" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_address_2', true); ?>">
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label for="client_city"><?php _trans('city'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_city" id="client_city" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_city', true); ?>">
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label for="client_state"><?php _trans('state'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_state" id="client_state" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_state', true); ?>">
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label for="client_zip"><?php _trans('zip_code'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_zip" id="client_zip" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_zip', true); ?>">
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label for="client_country"><?php _trans('country'); ?></label>

                            <div class="controls">
                                <select name="client_country" id="client_country" class="form-control">
                                    <option value=""><?php _trans('none'); ?></option>
                                    <?php foreach ($countries as $cldr => $country) { ?>
                                        <option value="<?php echo $cldr; ?>"
                                            <?php check_select($selected_country, $cldr); ?>
                                        ><?php echo $country ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <style type="text/css">
                            fieldset
                                {
                                    border: solid 1px #000;
                                    display:block;
                                    clear:both;
                                    margin:5px 0px;
                                    padding: 0px;
                                }
                                legend
                                {
                                    padding:0px 10px;
                                    color:#000;
                                    border:1px solid;
                                }
                                .fieldname{
                                    min-width: 350px;
                                    margin-right: 5px;
                                }

                                fieldset div:nth-child(2n-1) {
                                    background-color: #eee;
                                }
                                .fieldwrapper { padding: 10px; border-width: 1px 0px; }                                
                                .fieldwrapper label { min-width: 75px; }
                                .remove { float: right; }
                        </style>

<?php 
        $shipping_address = $this->db->get_where('ip_shipping_address', array('billing_address' => 0,
                'client_id' => $this->mdl_clients->form_value('client_id', true)
            ))->result();
?>
                        <fieldset id="buildyourform">
                            <legend>Shipping address</legend>
                                <center><input type="button" value="Add Shipping address" class="add" id="add" /> </center><hr>
<?php if($shipping_address) { 
    $i = 1;
foreach ($shipping_address as $row)
        {
           
    ?>             
          
                            <div class="fieldwrapper" id="field<?php echo $i; ?>">
                                <input value="<?php echo $row->id; ?>" type="hidden" name="shipping_address[<?php echo $i-1; ?>][]">
                                <label>Address:</label><textarea class="fieldname" name="shipping_address[<?php echo $i-1; ?>][]"> <?php echo $row->address;?></textarea><br>
                                <label>GST:</label><input value="<?php echo $row->gst_no;?>" type="text" name="shipping_address[<?php echo $i-1; ?>][]"><br>
                                <label>SAC Code:</label><input value="<?php echo $row->sac_code;?>" type="text" name="shipping_address[<?php echo $i-1; ?>][]">
                            </div>
<?php  $i++; }
    }
 ?>

                        </fieldset>

                        <script type="text/javascript">
                            $(document).ready(function() {
                                $("#add").click(function() {
                                    var intId = $("#buildyourform div").length + 1;
                                    var fieldWrapper = $("<div class=\"fieldwrapper\" id=\"field" + intId + "\"/>");
                                    var fid = $("<input type=\"hidden\" name=\"shipping_address["+(intId-1)+"][]\" value=\"\" />");
                                    var fName = $("<label>Address:</label><textarea class=\"fieldname\" name=\"shipping_address["+(intId-1)+"][]\" /></textarea><br>");
                                    var fgst = $("<label>GST:</label><input type=\"text\" name=\"shipping_address["+(intId-1)+"][]\" value=\"\" /><br>");
                                    var fsac = $("<label>SAC Code:</label><input type=\"text\" name=\"shipping_address["+(intId-1)+"][]\" value=\"\" />");
                                    var removeButton = $("<input type=\"button\" class=\"remove\" value=\"remove\" />");
                                    removeButton.click(function() {
                                        $(this).parent().remove();
                                    });
                                    fieldWrapper.append(fid);
                                    fieldWrapper.append(fName);
                                    fieldWrapper.append(fgst);
                                    fieldWrapper.append(fsac);
                                    fieldWrapper.append(removeButton);
                                    $("#buildyourform").append(fieldWrapper);
                                });
                            });
                        </script>


<?php 
        $billing_address = $this->db->get_where('ip_shipping_address', array('billing_address' => 1,
                'client_id' => $this->mdl_clients->form_value('client_id', true)
            ))->result();
?>
                        <fieldset id="billingform">
                            <legend>Billing address</legend>
                                <center><input type="button" value="Add Billing address" class="addb" id="addb" /> </center> <hr>
<?php if($billing_address) { 
    $i = 1;
foreach ($billing_address as $row)
        {
           
    ?>             
          
                        
          
                            <div class="fieldwrapper" id="field<?php echo $i; ?>">
                                <input value="<?php echo $row->id; ?>" type="hidden" name="billing_address[<?php echo $i-1; ?>][]">
                                <label>Address:</label><textarea class="fieldname" name="billing_address[<?php echo $i-1; ?>][]"> <?php echo $row->address;?></textarea><br>
                                <label>GST:</label><input value="<?php echo $row->gst_no;?>" type="text" name="billing_address[<?php echo $i-1; ?>][]"><br>
                                <label>SAC Code:</label><input value="<?php echo $row->sac_code;?>" type="text" name="billing_address[<?php echo $i-1; ?>][]">
                            </div>
<?php  $i++; }
    }
 ?>

                        </fieldset>

                        <script type="text/javascript">
                            $(document).ready(function() {
                                $("#addb").click(function() {
                                    var intId = $("#billingform div").length + 1;
                                    var fieldWrapper = $("<div class=\"fieldwrapper\" id=\"field" + intId + "\"/>");
                                    var fid = $("<input type=\"hidden\" name=\"billing_address["+(intId-1)+"][]\" value=\"\" />");
                                    var fName = $("<label>Address:</label><textarea class=\"fieldname\" name=\"billing_address["+(intId-1)+"][]\" /></textarea><br>");
                                    var fgst = $("<label>GST:</label><input type=\"text\" name=\"billing_address["+(intId-1)+"][]\" value=\"\" /><br>");
                                    var fsac = $("<label>SAC Code:</label><input type=\"text\" name=\"billing_address["+(intId-1)+"][]\" value=\"\" />");
                                    var removeButton = $("<input type=\"button\" class=\"remove\" value=\"remove\" />");
                                    removeButton.click(function() {
                                        $(this).parent().remove();
                                    });
                                    fieldWrapper.append(fid);
                                    fieldWrapper.append(fName);
                                    fieldWrapper.append(fgst);
                                    fieldWrapper.append(fsac);
                                    fieldWrapper.append(removeButton);
                                    $("#billingform").append(fieldWrapper);
                                });
                                $(".remove").click(function(){
                                    $(this).parent().remove();
                                });
                            });
                        </script>

                        <!-- Custom Fields -->
                        <?php foreach ($custom_fields as $custom_field): ?>
                            <?php if ($custom_field->custom_field_location != 1) {
                                continue;
                            } ?>
                            <?php print_field($this->mdl_clients, $custom_field, $cv); ?>
                        <?php endforeach; ?>
                    </div>

                </div>

            </div>
            <div class="col-xs-12 col-sm-6">

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <?php _trans('contact_information'); ?>
                    </div>

                    <div class="panel-body">
                        <div class="form-group">
                            <label for="client_phone"><?php _trans('phone_number'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_phone" id="client_phone" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_phone', true); ?>">
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label for="client_fax"><?php _trans('fax_number'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_fax" id="client_fax" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_fax', true); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="client_mobile"><?php _trans('mobile_number'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_mobile" id="client_mobile" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_mobile', true); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="client_email"><?php _trans('email_address'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_email" id="client_email" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_email', true); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="client_web"><?php _trans('web_address'); ?></label>

                            <div class="controls">
                                <input type="text" name="client_web" id="client_web" class="form-control"
                                       value="<?php echo $this->mdl_clients->form_value('client_web', true); ?>">
                            </div>
                        </div>

                        <!-- Custom fields -->
                        <?php foreach ($custom_fields as $custom_field): ?>
                            <?php if ($custom_field->custom_field_location != 2) {
                                continue;
                            } ?>
                            <?php print_field($this->mdl_clients, $custom_field, $cv); ?>
                        <?php endforeach; ?>
                    </div>

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php _trans('tax_information'); ?>
                    </div>

                    <div class="panel-body">
                        
                    </div>

                </div>

            </div>
            <div class="col-xs-12 col-sm-6">
            </div>
        </div>
        
    </div>
</form>
