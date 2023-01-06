<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * InvoicePlane
 *
 * @author		InvoicePlane Developers & Contributors
 * @copyright	Copyright (c) 2012 - 2018 InvoicePlane.com
 * @license		https://invoiceplane.com/license.txt
 * @link		https://invoiceplane.com
 */

/**
 * Class Products
 */
class Products extends Admin_Controller
{
    /**
     * Products constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('mdl_products');
    }

    /**
     * @param int $page
     */
    public function index($page = 0)
    {
        $this->mdl_products->paginate(site_url('products/index'), $page);
        $products = $this->mdl_products->result();

        $this->layout->set('products', $products);
        $this->layout->buffer('content', 'products/index');
        $this->layout->render();
    }

    /**
     * @param null $id
     */
    public function form($id = null)
    {
        if ($this->input->post('btn_cancel')) {
            redirect('products');
        }

        if ($this->mdl_products->run_validation()) {
            // Get the db array
            $db_array = $this->mdl_products->db_array();
            $this->mdl_products->save($id, $db_array);
            redirect('products');
        }

        if ($id and !$this->input->post('btn_submit')) {
            if (!$this->mdl_products->prep_form($id)) {
                show_404();
            }
        }

        $this->load->model('families/mdl_families');
        $this->load->model('units/mdl_units');
        $this->load->model('tax_rates/mdl_tax_rates');

        $this->layout->set(
            array(
                'families' => $this->mdl_families->get()->result(),
                'units' => $this->mdl_units->get()->result(),
                'tax_rates' => $this->mdl_tax_rates->get()->result(),
            )
        );

        $this->layout->buffer('content', 'products/form');
        $this->layout->render();
    }
    
    public function product_report(){
        $this->load->helper('mpdf');
        ob_start();
        $this->mdl_products->paginate(site_url('products/product_report'),0);
        
        $products = $this->mdl_products->result();
        $this->mdl_products->paginate(site_url('products/product_report'),50);
        foreach($this->mdl_products->result() as $product){
            array_push($products,$product);
        }

        ?>
        <!DOCTYPE html>
        
        <html> 
        
        <head>
            <?php
            // Get the page head content
            $this->layout->load_view('layout/includes/head');
            ?>
        </head>
        <body>

            <div id="headerbar">
            <h1  style="color:#6266B7;">Mahalaxmi Glass Products</h1> Date: <?php echo date("d-m-Y"); ?>
            </div>
    
            <div id="content" class="table-content"> 
            
                <?php //$this->layout->load_view('layout/alerts'); ?>
            
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
            
                        <thead>
                        <tr>
                            <th><?php _trans('family'); ?></th>
                            <!-- <th><?php //_trans('product_sku'); ?></th> -->
                            <th><?php _trans('product_name'); ?></th>
                            <!--<th><?php //_trans('product_description'); ?></th>-->
                            <th><?php _trans('product_price'); ?></th>
                            <th>Unit</th>
                        </tr>
                        </thead>
            <br><br>
                        <tbody>
                        <?php foreach ($products as $product) { ?>
                            <tr>
                                <td><?php _htmlsc($product->family_name); ?></td>
                                <!-- <td><?php _htmlsc($product->product_sku); ?></td> -->
                                <td><?php _htmlsc($product->product_name); ?></td>
                                <!--<td><?php //echo nl2br(htmlsc($product->product_description)); ?></td>-->
                                <td class="amount" align="center"><?php echo format_currency($product->product_price); ?></td>
                                <td><?php _htmlsc($product->unit_name); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
            
                    </table>
                </div>
            
            </div>
            <?php echo $this->layout->load_view('layout/includes/fullpage-loader'); ?>
            
            <script defer src="<?php echo base_url(); ?>assets/core/js/scripts.min.js"></script>
            <?php if (trans('cldr') != 'en') { ?>
                <script src="<?php echo base_url(); ?>assets/core/js/locales/bootstrap-datepicker.<?php _trans('cldr'); ?>.js"></script>
            <?php } ?>
            
            </body>
            </html>

    <?php
    
        //product_report.php

        $html= ob_get_clean();
        pdf_create($html, trans('sales_by_date'), true);
    }



    /**
     * @param $id
     */
    public function delete($id)
    {
        $this->mdl_products->delete($id);
        redirect('products');
    }

}
?>
