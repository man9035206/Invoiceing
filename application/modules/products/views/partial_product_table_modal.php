<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <tr>
            <th>&nbsp;</th>
            <th><?php _trans('product_no'); ?></th>
            <th><?php _trans('family_name'); ?></th>
            <th><?php _trans('client_name'); ?></th>
            <th><?php _trans('product_name'); ?></th>
            <th class="text-right"><?php _trans('product_price'); ?></th>
        </tr>
        <?php foreach ($products as $product) { ?>
            <tr class="product">
                <td class="text-left">
                    <input type="checkbox" class="<?php echo $product->product_no; ?>" name="product_ids[]"
                           value="<?php echo $product->product_id; ?>">
                </td>
                <td nowrap class="text-left">
                    <b><?php _htmlsc($product->product_no); ?></b>
                </td>
                <td>
                    <b><?php _htmlsc($product->family_name); ?></b>
                </td>
                <td>
                    <b><?php 
$client = $this->mdl_clients->where('client_id',$product->po_client_id)->get()->result();
echo $client[0]->client_name;
                    ?></b>
                </td>
                <td>
                    <b><?php _htmlsc($product->product_name); ?></b>
                </td>
                <td class="text-right">
                    <?php echo format_currency($product->product_price); ?>
                </td>
            </tr>
        <?php } ?>

    </table>
</div>

<script type="text/javascript">
    $(document).ready(function(){
      $("[name='product_ids[]']").click(function(){
        if($("[name='product_ids[]']:checked").length != 0) {
            $("[name='product_ids[]']").prop('disabled', true);
            $("."+$(this).attr('class')).prop('disabled', false);            
        } else {
            $("[name='product_ids[]']").prop('disabled', false);            
        }
      });
    });
</script>
