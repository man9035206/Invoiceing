<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * InvoicePlane
 *
 * @author		InvoicePlane Developers & Contributors
 * @copyright	Copyright (c) 2012 - 2017 InvoicePlane.com
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

    public function non_invoiced($page = 0)
    {
        $this->load->model('invoices/mdl_items');
        $query = "SELECT item_product_id from ip_invoice_items as it 
        LEFT JOIN ip_invoices as i ON it.invoice_id = i.invoice_id
        WHERE MONTH(invoice_date_created) = ".date('m');
        $items = $this->db->query($query)->result();

        $invoiced_pids= array();
        foreach($items as $row){
           $invoiced_pids[] = $row->item_product_id;
        }

        $invoiced_pids = implode(",",$invoiced_pids);
        $invoiced_pids = explode(",", $invoiced_pids);

        $this->mdl_products->paginate(site_url('products/index'), $page);
        $products = $this->mdl_products->where_not_in('product_id', $invoiced_pids)->get()->result();
        

        $this->layout->set('products', $products);
        $this->layout->buffer('content', 'products/non_invoiced_po');
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

        if ($this->input->post('po_billing_address') == "new") {
            $this->db->insert('ip_shipping_address',array('address' => $this->input->post('billing_address_a'),'client_id' => $this->input->post('po_client_id'),'gst_no' => $this->input->post('billing_address_gst'), 'sac_code' => $this->input->post('billing_address_sac'), 'billing_address' => 1 )); 
            $db_array["po_billing_address"] = $this->db->insert_id();
        } 
        if ($this->input->post('po_shipping_address') == "new") {
            $this->db->insert('ip_shipping_address',array('address' => $this->input->post('shipping_address_a'),'client_id' => $this->input->post('po_client_id'),'gst_no' => $this->input->post('shipping_address_gst'), 'sac_code' => $this->input->post('shipping_address_sac') )); 
            $db_array["po_shipping_address"] = $this->db->insert_id();
        } 
            $this->mdl_products->save($id, $db_array);            
            if ($id) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->db->select_max('product_id');
                $result = $this->db->get('ip_products');  
                redirect("/products/form/".$result->row()->product_id);
            }
        }

        if ($id and !$this->input->post('btn_submit')) {
            if (!$this->mdl_products->prep_form($id)) {
                show_404();
            }
        }

        $this->load->model('clients/mdl_clients');
        $this->load->model('families/mdl_families');
        $this->load->model('units/mdl_units');
        $this->load->model('tax_rates/mdl_tax_rates');

        $this->layout->set(
            array(
                'families' => $this->mdl_families->get()->result(),
                'units' => $this->mdl_units->get()->result(),
                'tax_rates' => $this->mdl_tax_rates->get()->result(),
                'clients' => $this->mdl_clients->get()->result(),
                'po_desc' => $this->mdl_products->po_desc(),
            )
        );

        $this->layout->buffer('content', 'products/form');
        $this->layout->render();
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
