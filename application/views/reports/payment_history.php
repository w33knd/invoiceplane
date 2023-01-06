<!DOCTYPE html>
<html lang="<?php echo trans('cldr'); ?>">
<head>
    <title><?php echo trans('payment_history'); ?></title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/<?php echo get_setting('system_theme', 'invoiceplane'); ?>/css/reports.css" type="text/css">
</head>
<body>

<h3 class="report_title">
    <?php echo trans('payment_history'); ?><br/>
    <small><?php echo $from_date . ' - ' . $to_date ?></small>
</h3>
<?php 
$this->load->model('custom_fields/mdl_payment_custom' );
$this->load->model('custom_values/mdl_custom_values');
$this->load->model('payment_methods/mdl_payment_methods');
?>
<table>
    <tr>
        <th><?php echo trans('date'); ?></th>
        <th><?php echo trans('invoice'); ?></th>
        <th><?php echo trans('client'); ?></th>
        <th><?php echo trans('payment_method'); ?></th>
        <th><?php echo trans('note'); ?></th>
        <th class="amount"><?php echo trans('amount'); ?></th>
    </tr>
    <?php
    $sum = 0;
    $defaulted_sum=0;

    foreach ($results as $result) {
        
        ?>
        <?php
                $entry=$this->mdl_payment_custom->get_by_payid($result->payment_id);
                $value=0;
                if(!empty($entry)){
                    $value=$entry[0]->payment_custom_fieldvalue;
                }
            ?>
        <tr <?php if($value) {echo "bgcolor='#ff7f7f'";}?>>
            <td><?php echo date_from_mysql($result->payment_date, true); ?></td>
            <td><?php echo $result->invoice_number; ?></td>
            <td><?php echo format_client($result); ?></td>
            <td><?php _htmlsc($result->payment_method_name); ?></td>
            <td><?php echo nl2br(htmlsc($result->payment_note)); ?></td>
            <td class="amount"><?php echo format_currency($result->payment_amount);
                if($value){
                    $defaulted_sum+= $result->payment_amount;
                }else{
                    $sum = $sum + $result->payment_amount;
                }
                 ?></td>
        </tr>
        <?php
    }

    if (!empty($results)) {
        ?>
        <tr>
            <td colspan=5><?php echo "Subtotal"; ?></td>
            <td class="amount"><?php echo format_currency($sum); ?></td>
        </tr>
        <tr>
            <td colspan=5><?php echo "Defaulted"; ?></td>
            <td class="amount"><?php echo format_currency($defaulted_sum); ?></td>
        </tr>
        <tr>
            <td colspan=5><?php echo trans('total'); ?></td>
            <td class="amount"><?php echo format_currency($sum-$defaulted_sum); ?></td>
        </tr>
        
    <?php } ?>
</table>

</body>
</html>
