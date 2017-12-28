
<form method="post" action="<?php echo site_url('users/update');?>">

    <input type="hidden" name="<?php echo $this->config->item('csrf_token_name'); ?>"
           value="<?php echo $this->security->get_csrf_hash() ?>">

    <div id="headerbar">
        <h1 class="headerbar-title">JoulestoWatts Info </h1>
        <?php echo $this->layout->load_view('layout/header_buttons'); ?>
    </div>

    <div id="content">
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">

                <?php echo $this->layout->load_view('layout/alerts'); ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">Contact Info</div>

                        <div class="panel-body">
                            <div class="form-group">
                                <label for="c_name">
                                   Contact <?php _trans('name'); ?>
                                </label>
                                <input type="text" name="c_name" id="c_name" class="form-control"
                                       value="<?php $this->db->select("setting_value");
                                                    $this->db->from("ip_settings");
                                                    $this->db->or_where('setting_key', 'j2w_contact_name');
                                        $query = $this->db->get(); foreach ($query->result() as $row){echo $row->setting_value;}?>">
                            </div>

                            <div class="form-group">
                                <label for="c_company">
                                    <?php _trans('company'); ?>
                                </label>
                                <input type="text" name="c_company" id="c_company" class="form-control"
                                       value="<?php $this->db->select("setting_value");
                                                    $this->db->from("ip_settings");
                                                    $this->db->or_where('setting_key', 'j2w_company_name');
                                        $query = $this->db->get(); foreach ($query->result() as $row){echo $row->setting_value;}?>">
                            </div>

                            <div class="form-group">
                                <label for="c_email">
                                    <?php _trans('email_address'); ?>
                                </label>
                                <input type="text" name="c_email" id="c_email" class="form-control"
                                       value="<?php $this->db->select("setting_value");
                                                    $this->db->from("ip_settings");
                                                    $this->db->or_where('setting_key', 'j2w_email');
                                        $query = $this->db->get(); foreach ($query->result() as $row){echo $row->setting_value;}?>">
                            </div>

                            <div class="form-group">
                                <label for="c_address">
                                    Address
                                </label>
                                <input type="text" name="c_address" id="c_address" class="form-control" value="<?php $this->db->select("setting_value");
                                                    $this->db->from("ip_settings");
                                                    $this->db->or_where('setting_key', 'j2w_address');
                                        $query = $this->db->get(); foreach ($query->result() as $row){echo $row->setting_value;}?>"/>
                            </div>
                        </div>

                    </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">

                <?php echo $this->layout->load_view('layout/alerts'); ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">Tax Info</div>

                        <div class="panel-body">
                            <div class="form-group">
                                <label for="c_gstin">
                                   GSTIN
                                </label>
                                <input type="text" name="c_gstin" id="c_gstin" class="form-control"
                                       value="<?php $this->db->select("setting_value");
                                                    $this->db->from("ip_settings");
                                                    $this->db->or_where('setting_key', 'j2w_GSTIN');
                                        $query = $this->db->get(); foreach ($query->result() as $row){echo $row->setting_value;}?>">
                            </div>

                            <div class="form-group">
                                <label for="c_pan">
                                    PAN
                                </label>
                                <input type="text" name="c_pan" id="c_pan" class="form-control"
                                       value="<?php $this->db->select("setting_value");
                                                    $this->db->from("ip_settings");
                                                    $this->db->or_where('setting_key', 'j2w_PAN');
                                        $query = $this->db->get(); foreach ($query->result() as $row){echo $row->setting_value;}?>">
                            </div>

                            <div class="form-group">
                                <label for="c_sac">
                                    SAC Code
                                </label>
                                <input type="text" name="c_sac" id="c_sac" class="form-control"
                                       value="<?php $this->db->select("setting_value");
                                                    $this->db->from("ip_settings");
                                                    $this->db->or_where('setting_key', 'j2w_SAC_Code');
                                        $query = $this->db->get(); foreach ($query->result() as $row){echo $row->setting_value;}?>">
                            </div>

                            <div class="form-group">
                                <label for="c_sac">
                                    LUT number
                                </label>
                                <input type="text" name="c_lut" id="c_lut" class="form-control"
                                       value="<?php $this->db->select("setting_value");
                                                    $this->db->from("ip_settings");
                                                    $this->db->or_where('setting_key', 'j2w_LUT_number');
                                        $query = $this->db->get(); foreach ($query->result() as $row){echo $row->setting_value;}?>">
                            </div>

                        </div>

                    </div>
            </div>
        </div>
    </div>

</form>