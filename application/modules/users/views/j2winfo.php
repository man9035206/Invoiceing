
<form method="post">

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
                                       value="<?php echo $this->mdl_users->form_value('c_name', true); ?>">
                            </div>

                            <div class="form-group">
                                <label for="c_company">
                                    <?php _trans('company'); ?>
                                </label>
                                <input type="text" name="c_company" id="c_company" class="form-control"
                                       value="<?php echo $this->mdl_users->form_value('c_company', true); ?>">
                            </div>

                            <div class="form-group">
                                <label for="c_email">
                                    <?php _trans('email_address'); ?>
                                </label>
                                <input type="text" name="c_email" id="c_email" class="form-control"
                                       value="<?php echo $this->mdl_users->form_value('c_email', true); ?>">
                            </div>

                            <div class="form-group">
                                <label for="c_email">
                                    Address
                                </label>
                                <textarea type="text" name="c_email" id="c_email" class="form-control"
                                       value="<?php echo $this->mdl_users->form_value('c_email', true); ?>">
                                </textarea>
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
                                       value="<?php echo $this->mdl_users->form_value('c_gstin', true); ?>">
                            </div>

                            <div class="form-group">
                                <label for="c_pan">
                                    PAN
                                </label>
                                <input type="text" name="c_pan" id="c_pan" class="form-control"
                                       value="<?php echo $this->mdl_users->form_value('c_pan', true); ?>">
                            </div>

                            <div class="form-group">
                                <label for="c_sac">
                                    SAC Code
                                </label>
                                <input type="text" name="c_sac" id="c_sac" class="form-control"
                                       value="<?php echo $this->mdl_users->form_value('c_sac', true); ?>">
                            </div>

                            <div class="form-group">
                                <label for="c_sac">
                                    LUT number
                                </label>
                                <input type="text" name="c_lut" id="c_lut" class="form-control"
                                       value="<?php echo $this->mdl_users->form_value('c_lut', true); ?>">
                            </div>

                        </div>

                    </div>
            </div>
        </div>
    </div>

</form>