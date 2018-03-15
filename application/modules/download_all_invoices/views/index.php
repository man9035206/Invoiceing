<script>
    $(function () {
        // Display the create invoice modal
        $('#create-invoice').modal('show');

        // Enable select2 for all selects
        $('.simple-select').select2();

        <?php $this->layout->load_view('clients/script_select2_client_id.js'); ?>

        // Toggle on/off permissive search on clients names
        $('span#toggle_permissive_search_clients').click(function () {
            if ($('input#input_permissive_search_clients').val() == ('1')) {
                $.get("<?php echo site_url('clients/ajax/save_preference_permissive_search_clients'); ?>", {
                    permissive_search_clients: '0'
                });
                $('input#input_permissive_search_clients').val('0');
                $('span#toggle_permissive_search_clients i').removeClass('fa-toggle-on');
                $('span#toggle_permissive_search_clients i').addClass('fa-toggle-off');
            } else {
                $.get("<?php echo site_url('clients/ajax/save_preference_permissive_search_clients'); ?>", {
                    permissive_search_clients: '1'
                });
                $('input#input_permissive_search_clients').val('1');
                $('span#toggle_permissive_search_clients i').removeClass('fa-toggle-off');
                $('span#toggle_permissive_search_clients i').addClass('fa-toggle-on');
            }
        });
    });

</script>
<div id="headerbar">
    <h1 class="headerbar-title">Download all PDF Files</h1>
</div>

<div id="content">

    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3">

            <?php $this->layout->load_view('layout/alerts'); ?>

            <div id="report_options" class="panel panel-default">

                <div class="panel-heading">
                    <i class="fa fa-print"></i>
                    <?php _trans('report_options'); ?>
                </div>

                <div class="panel-body">
                    <form method="Post" action="/j2w-invoicing/index.php/download_all_invoices/downloadInvoices/downloadFiles"
                        <?php echo get_setting('reports_in_new_tab', false) ? 'target="_blank"' : ''; ?>>


                        <div class="form-group has-feedback">
                            <label for="create_invoice_client_id"><?php _trans('client'); ?></label>
                            <div class="input-group">
                                <select name="client_id" id="create_invoice_client_id" class="client-id-select form-control"
                                        autofocus="autofocus">
                                        <option name="client_id" value="all" Required="Required"> All Clients </option>
                                    <?php if (!empty($client)) : ?>
                                        <option value="<?php echo $client->client_id; ?>"><?php _htmlsc(format_client($client)); ?></option>
                                    <?php endif; ?>
                                </select>
                                <span id="toggle_permissive_search_clients" class="input-group-addon"
                                      title="<?php _trans('enable_permissive_search_clients'); ?>" style="cursor:pointer;">
                                    <i class="fa fa-toggle-<?php echo get_setting('enable_permissive_search_clients') ? 'on' : 'off' ?> fa-fw"></i>
                                </span>
                            </div>
                        </div>

                        <input type="hidden" name="<?php echo $this->config->item('csrf_token_name'); ?>"
                               value="<?php echo $this->security->get_csrf_hash() ?>">

                        <input type="submit" class="btn btn-success"
                               name="btn_submit" value="<?php _trans('run_report'); ?>">

                    </form>
                </div>

            </div>

        </div>
    </div>

</div>
