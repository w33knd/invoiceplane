<div class="table-responsive">
    <table id="item_table" class="items table table-condensed table-bordered no-margin">
        <thead style="display: none">
        <tr>
            <th></th>
            <th><?php _trans('item'); ?></th>
            <!--<th><?php _trans('description'); ?></th>-->
            <th><?php _trans('quantity'); ?></th>
            <th><?php _trans('price'); ?></th>
            <th><?php _trans('tax_rate'); ?></th>
            <th><?php _trans('subtotal'); ?></th>
            <th><?php _trans('tax'); ?></th>
            <th><?php _trans('total'); ?></th>
            <th></th>
        </tr>
        </thead>

        <tbody id="new_row" style="display: none;">
        <tr>
            <td rowspan="2" class="td-icon"><i class="fa fa-arrows cursor-move"></i></td>
            <td class="td-text">
                <input type="hidden" name="quote_id" value="<?php echo $quote_id; ?>">
                <input type="hidden" name="item_id" value="">
                <input type="hidden" name="item_product_id" value="">

                <div class="input-group">
                    <span class="input-group-addon"><?php _trans('item'); ?></span>
                    <!--<input type="text" name="item_name" class="input-sm form-control" value="" list="productlistcustom" id="item_name_id">-->
                    <input type="text" name="item_name" class="input-sm form-control" value="" list="productlistcustom" id="item_name_id" oninput="item_change_function()">
                    <datalist id="productlistcustom">
                    <?php    
                            $servername = "localhost";
                            $username = "u614119281_invoiceplane";
                            $password = "Tr@151101";
                            $dbname = "u614119281_mahalaxmi";
                            
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            // Check connection
                            if ($conn->connect_error) {
                              die("Connection failed: " . $conn->connect_error);
                            }
                            
                            $sql = "SELECT * FROM ip_products";
                            $result = $conn->query($sql);
                            $datalistoptions="";
                            $datalistraw="{";

                            if ($result->num_rows > 0) {
                              // output data of each row
                              while($row = $result->fetch_assoc()) {
                                $datalistraw=$datalistraw."\"".strval($row["product_name"])."\":\"" . strval($row["product_id"])."\"";
                                $datalistraw.=",";
                                $datalistoptions =$datalistoptions. "<option class='productlist-class' id='productlist-item-".strval($row["product_id"])."' >" . strval($row["product_name"]). "</option>";
                              }
                              $datalistraw[strlen($datalistraw)-1]="}";
                            } else {
                              echo "0 results";
                            }
                            echo $datalistoptions;
                            echo "<script> var datalistraw=JSON.parse('".$datalistraw."');</script>";
                            $conn->close();
                    ?>
                    </datalist>
                    <!-- custom select tag -->
                    <!-- <div class="form-group has-feedback">
                        <div class="input-group">
                            <select class="custom-select-tag" name="state" onchange="get_product_values()">
                            </select>
                            </span>
                        </div>  
                    </div> -->

                </div>
            </td>
            <td class="td">
                <div class="input-group">
                    <span class="input-group-addon">Width</span>
                    <input type="text" class="input-sm form-control td-measure-for-description" id="description-width" value="" oninput="dimensionchange()">
                </div>
            </td>
            <td class="td">
                <div class="input-group">
                    <span class="input-group-addon">Length</span>
                    <input type="text" class="input-sm form-control td-measure-for-description" id="description-length" value="" oninput="dimensionchange()">
                </div>
            </td>
            <td class="td">
                <div class="input-group">
                    <span class="input-group-addon">Pieces</span>
                    <input type="text" class="input-sm form-control td-measure-for-description" id="description-pieces" value="" oninput="dimensionchange()">
                </div>
            </td>
            <td class="td-amount td-quantity">
                <div class="input-group">
                    <span class="input-group-addon"><?php _trans('quantity'); ?></span>
                    <input type="text" name="item_quantity" class="input-sm form-control amount" value="" id="item_quantity_id" >
                </div>
            </td>
            <td class="td-amount">
                <div class="input-group">
                    <span class="input-group-addon"><?php _trans('price'); ?></span>
                    <input type="text" name="item_price" class="input-sm form-control amount" value="">
                </div>
            </td>
            <td class="td-amount ">
                <div class="input-group">
                    <span class="input-group-addon"><?php _trans('item_discount'); ?></span>
                    <input type="text" name="item_discount_amount" class="input-sm form-control amount"
                           data-toggle="tooltip" data-placement="bottom"
                           title="<?php echo get_setting('currency_symbol') . ' ' . trans('per_item'); ?>">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-addon"><?php _trans('tax_rate'); ?></span>
                    <select name="item_tax_rate_id" class="form-control input-sm">
                        <option value="0"><?php _trans('none'); ?></option>
                        <?php foreach ($tax_rates as $tax_rate) { ?>
                            <option value="<?php echo $tax_rate->tax_rate_id; ?>">
                                <?php echo format_amount($tax_rate->tax_rate_percent) . '% - ' . $tax_rate->tax_rate_name; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </td>
            <td class="td-icon text-right td-vert-middle">
                <button type="button" class="btn_delete_item btn btn-link btn-sm" title="<?php _trans('delete'); ?>">
                    <i class="fa fa-trash-o text-danger"></i>
                </button>
            </td>
        </tr>
        <tr>
            <td class="td-textarea">
                <div class="input-group">
                    <span class="input-group-addon"><?php _trans('description'); ?></span>
                    <input id="item_description_id" name="item_description" class="input-sm form-control" readonly></input>
                </div>
            </td>
            <td class="td-amount">
                <div class="input-group">
                    <span class="input-group-addon"><?php _trans('product_unit'); ?></span>
                    <select name="item_product_unit_id"
                            class="form-control input-sm">
                        <option value="0"><?php _trans('none'); ?></option>
                        <?php foreach ($units as $unit) { ?>
                            <option value="<?php echo $unit->unit_id; ?>">
                                <?php echo htmlsc($unit->unit_name) . "/" . htmlsc($unit->unit_name_plrl); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </td>
            <td>
                <div class="btn-group">
                    <a href="#" class="btn_add_row btn btn-sm btn-default" id="add_new_row_caller" onclick="$('#add_new_row_button_listener').click()">
                        <i class="fa fa-plus"></i>
                        <?php _trans('add_new_row'); ?>
                    </a>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-addon">Running Foot</span>
                    <input id="running_foot_id" class="input-sm form-control td-measure-for-description" readonly>
                    </input>
                </div>
            </td>
            <td class="td-amount td-vert-middle">
                <span><?php _trans('subtotal'); ?></span><br/>
                <span name="subtotal" class="amount"></span>
            </td>
            <td class="td-amount td-vert-middle">
                <span><?php _trans('discount'); ?></span><br/>
                <span name="item_discount_total" class="amount"></span>
            </td>
            <td class="td-amount td-vert-middle">
                <span><?php _trans('tax'); ?></span><br/>
                <span name="item_tax_total" class="amount"></span>
            </td>
            <td class="td-amount td-vert-middle">
                <span><?php _trans('total'); ?></span><br/>
                <span name="item_total" class="amount"></span>
            </td>
        </tr>
        </tbody>

        <?php foreach ($items as $item) { ?>
            <tbody class="item">
            <tr>
                <td rowspan="2" class="td-icon"><i class="fa fa-arrows cursor-move"></i></td>
                <td class="td-text">
                    <input type="hidden" name="quote_id" value="<?php echo $quote_id; ?>">
                    <input type="hidden" name="item_id" value="<?php echo $item->item_id; ?>">
                    <input type="hidden" name="item_product_id" value="<?php echo $item->item_product_id; ?>">

                    
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('item'); ?></span>
                        <input type="text" name="item_name" list="productlistcustom" class="input-sm form-control item_name_class" id="item_name_id" oninput="item_change_function()"   
                               value="<?php _htmlsc($item->item_name); ?>">
                        <datalist id="productlistcustom">
                        <?php    
                            echo $datalistoptions;
                            ?>
                        </datalist>
                    </div>
                </td>
                <?php
                    $item_description=$item->item_description;
                    $foundWidth="";
                    $foundLength="";
                    $foundPieces="";
                    $item_break=explode(" ",$item->item_description);
                    if($item->item_description){
                        if(preg_match("/in/",$item_description)){
                            $foundWidth=$item_break[0];
                            $foundLength=$item_break[2];
                            $foundPieces=$item_break[4];

                        }
                        else{
                            $foundPieces=$item_break[0];
                        }
                    }
                    
                ?>
                <td class="td">
                <div class="input-group">
                    <span class="input-group-addon">Width</span>
                    <input type="text" class="input-sm form-control td-measure-for-description" id="description-width" value="<?php echo $foundWidth;?>" oninput="dimensionchange()">
                </div>
                </td>
                <td class="td">
                    <div class="input-group">
                        <span class="input-group-addon">Length</span>
                        <input type="text" class="input-sm form-control td-measure-for-description" id="description-length" value="<?php echo $foundLength;?>" oninput="dimensionchange()">
                    </div>
                </td>
                <td class="td">
                    <div class="input-group">
                        <span class="input-group-addon">Pieces</span>
                        <input type="text" class="input-sm form-control td-measure-for-description" id="description-pieces" value="<?php echo $foundPieces;?>" oninput="dimensionchange()">
                    </div>
                </td>
                <td class="td-amount td-quantity">
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('quantity'); ?></span>
                        <input type="text" name="item_quantity" class="input-sm form-control amount" id="item_quantity_id" 
                               value="<?php echo format_amount($item->item_quantity); ?>">
                    </div>
                </td>
                <td class="td-amount">
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('price'); ?></span>
                        <input type="text" name="item_price" class="input-sm form-control amount"
                               value="<?php echo format_amount($item->item_price); ?>">
                    </div>
                </td>
                <td class="td-amount ">
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('item_discount'); ?></span>
                        <input type="text" name="item_discount_amount" class="input-sm form-control amount"
                               value="<?php echo format_amount($item->item_discount_amount); ?>"
                               data-toggle="tooltip" data-placement="bottom"
                               title="<?php echo get_setting('currency_symbol') . ' ' . trans('per_item'); ?>">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('tax_rate'); ?></span>
                        <select name="item_tax_rate_id" class="form-control input-sm">
                            <option value="0"><?php _trans('none'); ?></option>
                            <?php foreach ($tax_rates as $tax_rate) { ?>
                                <option value="<?php echo $tax_rate->tax_rate_id; ?>"
                                        <?php if ($item->item_tax_rate_id == $tax_rate->tax_rate_id) { ?>selected="selected"<?php } ?>>
                                    <?php echo format_amount($tax_rate->tax_rate_percent) . '% - ' . htmlsc($tax_rate->tax_rate_name); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </td>
                <td class="td-icon text-right td-vert-middle">
                    <button type="button" class="btn_delete_item btn btn-link btn-sm" title="<?php _trans('delete'); ?>"
                            data-item-id="<?php echo $item->item_id; ?>">
                        <i class="fa fa-trash-o text-danger"></i>
                    </button>
                </td>
            </tr>
            <tr>
                <td class="td-textarea">
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('description'); ?></span>
                        <input id="item_description_id" name="item_description" class="input-sm form-control" readonly value="<?php echo htmlsc($item->item_description); ?>">
                            
                        </input>
                    </div>
                </td>
                <td class="td-amount">
                    <div class="input-group">
                        <span class="input-group-addon"><?php _trans('product_unit'); ?></span>
                        <select name="item_product_unit_id"
                                class="form-control input-sm">
                            <option value="0"><?php _trans('none'); ?></option>
                            <?php foreach ($units as $unit) { ?>
                                <option value="<?php echo $unit->unit_id; ?>"
                                    <?php check_select($item->item_product_unit_id, $unit->unit_id); ?>>
                                    <?php echo htmlsc($unit->unit_name) . "/" . htmlsc($unit->unit_name_plrl); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="btn-group"> 
                        <a href="#" class="btn_add_row btn btn-sm btn-default" id="add_new_row_caller" >
                            <i class="fa fa-plus"></i>
                            <?php _trans('add_new_row'); ?>
                        </a>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon">Running Foot</span>
                        <input id="running_foot_id" class="input-sm form-control td-measure-for-description" readonly value="<?php 
                            // $rfoot=(int)$foundPieces*2*((int)$foundWidth+(int)$foundLength);   
                            $iwidth=0;
                            $ilength=0;
                            if((int)$foundWidth%3!==0){
                                $iwidth=  $foundWidth+ 3-($foundWidth%3);
                            } else{
                                $iwidth=$foundWidth;
                            }
                            if((int)$foundLength%6!==0){
                                $ilength=  $foundLength+ 6-($foundLength%6);
                            } else{
                                $ilength=$foundLength;
                            } 
                            $rfoot=2*(((int)$iwidth/12)+((int)$ilength/12))*(int)$foundPieces;
                            if($rfoot){echo $rfoot; } 
                        ?>">
                        </input>
                    </div>
                </td>
                <td class="td-amount td-vert-middle">
                    <span><?php _trans('subtotal'); ?></span><br/>
                    <span name="subtotal" class="amount">
                        <?php echo format_currency($item->item_subtotal); ?>
                    </span>
                </td>
                <td class="td-amount td-vert-middle">
                    <span><?php _trans('discount'); ?></span><br/>
                    <span name="item_discount_total" class="amount">
                        <?php echo format_currency($item->item_discount); ?>
                    </span>
                </td>
                <td class="td-amount td-vert-middle">
                    <span><?php _trans('tax'); ?></span><br/>
                    <span name="item_tax_total" class="amount">
                        <?php echo format_currency($item->item_tax_total); ?>
                    </span>
                </td>
                <td class="td-amount td-vert-middle">
                    <span><?php _trans('total'); ?></span><br/>
                    <span name="item_total" class="amount">
                        <?php echo format_currency($item->item_total); ?>
                    </span>
                </td>
            </tr>
            </tbody>
        <?php } ?>

    </table>
</div>

<br>

<div class="row">
    <div class="col-xs-12 col-md-4">
        <div class="btn-group">
            <a href="#" class="btn_add_row btn btn-sm btn-default" id="add_new_row_button_listener">
                <i class="fa fa-plus"></i>
                <?php _trans('add_new_row'); ?>
            </a>
            <a href="#" class="btn_add_product btn btn-sm btn-default">
                <i class="fa fa-database"></i>
                <?php _trans('add_product'); ?>
            </a>
        </div>
    </div>

    <div class="col-xs-12 visible-xs visible-sm"><br></div>

    <div class="col-xs-12 col-md-6 col-md-offset-2 col-lg-4 col-lg-offset-4">
        <table class="table table-bordered text-right">
            <tr>
                <td style="width: 40%;"><?php _trans('subtotal'); ?></td>
                <td style="width: 60%;" class="amount"><?php echo format_currency($quote->quote_item_subtotal); ?></td>
            </tr>
            <tr>
                <td><?php _trans('item_tax'); ?></td>
                <td class="amount"><?php echo format_currency($quote->quote_item_tax_total); ?></td>
            </tr>
            <tr>
                <td><?php _trans('quote_tax'); ?></td>
                <td>
                    <?php if ($quote_tax_rates) {
                        foreach ($quote_tax_rates as $index => $quote_tax_rate) { ?>
                            <form method="POST" class="form-inline"
                                  action="<?php echo site_url('quotes/delete_quote_tax/' . $quote->quote_id . '/' . $quote_tax_rate->quote_tax_rate_id) ?>">
                                <?php _csrf_field(); ?>
                                <button type="submit" class="btn btn-xs btn-link"
                                        onclick="return confirm('<?php _trans('delete_tax_warning'); ?>');">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                                <span class="text-muted">
                                    <?php echo htmlsc($quote_tax_rate->quote_tax_rate_name) . ' ' . format_amount($quote_tax_rate->quote_tax_rate_percent) . '%' ?>
                                </span>
                                <span class="amount">
                                    <?php echo format_currency($quote_tax_rate->quote_tax_rate_amount); ?>
                                </span>
                            </form>
                        <?php }
                    } else {
                        echo format_currency('0');
                    } ?>
                </td>
            </tr>
            <tr>
                <td class="td-vert-middle"><?php _trans('discount'); ?></td>
                <td class="clearfix">
                    <div class="discount-field">
                        <div class="input-group input-group-sm">
                            <input id="quote_discount_amount" name="quote_discount_amount"
                                   class="discount-option form-control input-sm amount"
                                   value="<?php echo format_amount($quote->quote_discount_amount != 0 ? $quote->quote_discount_amount : ''); ?>">

                            <div
                                class="input-group-addon"><?php echo get_setting('currency_symbol'); ?></div>
                        </div>
                    </div>
                    <div class="discount-field">
                        <div class="input-group input-group-sm">
                            <input id="quote_discount_percent" name="quote_discount_percent"
                                   value="<?php echo format_amount($quote->quote_discount_percent != 0 ? $quote->quote_discount_percent : ''); ?>"
                                   class="discount-option form-control input-sm amount">
                            <div class="input-group-addon">&percnt;</div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><b><?php _trans('total'); ?></b></td>
                <td class="amount"><b><?php echo format_currency($quote->quote_total); ?></b></td>
            </tr>
        </table>
    </div>

</div>
<script src="<?php echo site_url('../assets/core/js/custom.js?version=1'); ?>"></script>
