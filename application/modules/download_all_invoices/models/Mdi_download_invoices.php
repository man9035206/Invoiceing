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
 * Class Mdl_Client_Notes
 */
class Mdi_download_invoices extends Response_Model
{
	public function download_pdf_files($id)
	{
		$this->db->select('*');
		$this->db->from('ip_clients');
		$this->db->join('ip_invoices', 'ip_invoices.client_id = ip_clients.client_id');
		$this->db->join('ip_invoice_amounts','ip_invoice_amounts.invoice_id=ip_invoices.invoice_id');
		// $this->db->join('ip_products','ip_products.po_client_id = ip_clients.client_id');
		$this->db->where(array('ip_invoices.client_id' => $id));
		$query = $this->db->get();
		if($query > 0)
		{
			return $query->result();
		}
  	    
	}
}
