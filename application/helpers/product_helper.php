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
 * @param string $id
 * @return string
 */

function po_desc($id){

    $CI = get_instance();
    $CI->load->model('products/mdl_products', 'p');
    $desc = $CI->p->po_desc();
    return $desc[$id];
}


function last_invoiced($id){
    
        $CI = get_instance();
        $CI->load->model('items/mdl_items', 'items');
        $query = 'SELECT i.invoice_date_created FROM ip_invoice_items as it
        LEFT JOIN ip_invoices as i ON it.invoice_id = i.invoice_id
        WHERE item_product_id = '.$id
        .' ORDER BY item_id DESC LIMIT 1';
        $invoice_items = $CI->db->query($query)->result();
        if(count($invoice_items)) {
            return date_from_mysql($invoice_items[0]->invoice_date_created);
        } else {
            return " - ";
        }
}


function last_invoiced_status($id){
    
    $CI = get_instance();
    $CI->load->model('items/mdl_items', 'items');
    $query = 'SELECT i.invoice_status_id FROM ip_invoice_items as it
    LEFT JOIN ip_invoices as i ON it.invoice_id = i.invoice_id
    WHERE item_product_id = '.$id
    .' ORDER BY item_id DESC LIMIT 1';
    $invoice_items = $CI->db->query($query)->result();
    if(count($invoice_items)) {
        $CI->load->model('invoices/mdl_invoices', 'i');
        $desc = $CI->i->statuses();
        return $desc[$invoice_items[0]->invoice_status_id]["label"];
    } else {
        return " - ";
    }
}
