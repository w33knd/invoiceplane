<div class="table-responsive">
    <table class="table">

        <thead>
        <tr>
            <th><?php _trans('payment_date'); ?></th>
            <th><?php _trans('invoice_date'); ?></th>
            <th><?php _trans('invoice'); ?></th>
            <th><?php _trans('client'); ?></th>
            <th><?php _trans('amount'); ?></th>
            <th><?php _trans('payment_method'); ?></th>
            <!--<th><?php _trans('note'); ?></th>-->
            <th>Receiver</th>
            <th><?php _trans('options'); ?></th>
        </tr>
        </thead>

        <tbody>
<?php 
$this->load->model('custom_fields/mdl_payment_custom' );
$this->load->model('custom_values/mdl_custom_values');
$this->load->model('payment_methods/mdl_payment_methods');
?>

        <?php foreach ($payments as $payment) { ?>
            <?php
                $entry=$this->mdl_payment_custom->get_by_payid($payment->payment_id);
                $value=0;
                if(!empty($entry)){
                    $value=$entry[0]->payment_custom_fieldvalue;
                }
            ?>
            <tr <?php if($value) {echo "bgcolor='#ffcccb'";}?>>

            <!--<tr>-->
                <td><?php echo date_from_mysql($payment->payment_date); ?></td>
                <td><?php echo date_from_mysql($payment->invoice_date_created); ?></td>
                <td><?php echo anchor('invoices/view/' . $payment->invoice_id, $payment->invoice_number); ?></td>
                <td>
                    <a href="<?php echo site_url('clients/view/' . $payment->client_id); ?>"
                       title="<?php _trans('view_client'); ?>">
                        <?php _htmlsc(format_client($payment)); ?>
                    </a>
                </td>
                <td class="amount"><?php echo format_currency($payment->payment_amount); ?></td>
                <td><?php _htmlsc($payment->payment_method_name); ?></td>
                <td><?php _htmlsc($payment->payment_note); ?></td>
                <td>
                    <div class="options btn-group">
                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-cog"></i> <?php _trans('options'); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo site_url('payments/form/' . $payment->payment_id); ?>">
                                    <i class="fa fa-edit fa-margin"></i>
                                    <?php _trans('edit'); ?>
                                </a>
                            </li>
                            <li>
                                <form action="<?php echo site_url('payments/delete/' . $payment->payment_id); ?>"
                                      method="POST">
                                    <?php _csrf_field(); ?>
                                    <button type="submit" class="dropdown-button"
                                            onclick="return confirm('<?php _trans('delete_record_warning'); ?>');">
                                        <i class="fa fa-trash-o fa-margin"></i> <?php _trans('delete'); ?>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>
</div>
