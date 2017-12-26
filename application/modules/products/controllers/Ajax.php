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
 * Class Ajax
 */
class Ajax extends Admin_Controller
{
    public $ajax_controller = true;

    public function modal_product_lookups()
    {
        $filter_product = $this->input->get('filter_product');
        $filter_family = $this->input->get('filter_family');
        $reset_table = $this->input->get('reset_table');
        $po_id = $this->input->get('po_id');

        $this->load->model('mdl_products');
        $this->load->model('families/mdl_families');

        if (!empty($filter_family)) {
            $this->mdl_products->by_family($filter_family);
        }

        if (!empty($filter_product)) {
            $this->mdl_products->by_product($filter_product);
        }

        if ($po_id == 0) {
            $products = $this->mdl_products->get()->result();
        } else {
            $item_po = $this->mdl_products->where('product_id',$po_id)->get()->result();
            $products = $this->mdl_products
            ->where('po_shipping_address',$item_po[0]->po_shipping_address)
            ->where('po_billing_address',$item_po[0]->po_billing_address)
            ->where('product_id<>',$po_id)
            ->get()->result();
        }
        $families = $this->mdl_families->get()->result();

        $data = array(
            'products' => $products,
            'families' => $families,
            'filter_product' => $filter_product,
            'filter_family' => $filter_family,
        );

        if ($filter_product || $filter_family || $reset_table) {
            $this->layout->load_view('products/partial_product_table_modal', $data);
        } else {
            print_r($products);
            $this->layout->load_view('products/modal_product_lookups', $data);
        }
    }

    public function process_product_selections()
    {
        $this->load->model('mdl_products');

        $products = $this->mdl_products->where_in('product_id', $this->input->post('product_ids'))->get()->result();

        foreach ($products as $product) {
            $product->product_price = format_amount($product->product_price);
        }

        echo json_encode($products);
    }

    public function client_address() {
        $data = array(
            'c_id' => $this->input->get('c_id'),
            's_id' => $this->input->get('s_id'),
            'b_id' => $this->input->get('b_id'),
            'p_id' => $this->input->get('p_id'), 
            'po_no' => $this->input->get('po_no') 
        );
        $this->layout->load_view('products/client_address', $data);
    }

}
