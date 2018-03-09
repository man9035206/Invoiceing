<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * InvoicePlane
 *
 * @author      InvoicePlane Developers & Contributors
 * @copyright   Copyright (c) 2012 - 2017 InvoicePlane.com
 * @license     https://invoiceplane.com/license.txt
 * @link        https://invoiceplane.com
 */

/**
 * Class Reports
 */
class Reports extends Admin_Controller
{
    /**
     * Reports constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('mdl_reports');
    }

    public function sales_by_client()
    {
        if ($this->input->post('btn_submit')) {
            $data = array(
                'results' => $this->mdl_reports->sales_by_client($this->input->post('from_date'), $this->input->post('to_date')),
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
            );

            $html = $this->load->view('reports/sales_by_client', $data, true);

            $this->load->helper('mpdf');

            pdf_create($html, trans('sales_by_client'), true);
        }

        $this->layout->buffer('content', 'reports/sales_by_client_index')->render();
    }

    public function payment_history()
    {
        if ($this->input->post('btn_submit')) {
            $data = array(
                'results' => $this->mdl_reports->payment_history($this->input->post('from_date'), $this->input->post('to_date')),
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
            );

            $html = $this->load->view('reports/payment_history', $data, true);

            $this->load->helper('mpdf');

            pdf_create($html, trans('payment_history'), true);
        }

        $this->layout->buffer('content', 'reports/payment_history_index')->render();
    }

    public function invoice_aging()
    {
        if ($this->input->post('btn_submit')) {
            $data = array(
                'results' => $this->mdl_reports->invoice_aging()
            );

            $html = $this->load->view('reports/invoice_aging', $data, true);

            $this->load->helper('mpdf');

            pdf_create($html, trans('invoice_aging'), true);
        }

        $this->layout->buffer('content', 'reports/invoice_aging_index')->render();
    }

    public function sales_by_year()
    {

        if ($this->input->post('btn_submit')) {
            $data = array(
                'results' => $this->mdl_reports->sales_by_year($this->input->post('from_date'), $this->input->post('to_date'), $this->input->post('minQuantity'), $this->input->post('maxQuantity'), $this->input->post('checkboxTax')),
                'from_date' => $this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
            );

            $html = $this->load->view('reports/sales_by_year', $data, true);

            $this->load->helper('mpdf');

            pdf_create($html, trans('sales_by_date'), true);
        }

        $this->layout->buffer('content', 'reports/sales_by_year_index')->render();
    }

    public function all_invoice() {


        if ($this->input->get('btn_submit')) {

            $cids = array();
            $uc = $this->db->from('ip_user_clients')
            ->where('user_id',$this->session->userdata('user_id'))
            ->get();

            $this->db->select('items.* , client_name, invoices.*, products.*, amounts.*, payments.*');
            $this->db->from('ip_invoice_items AS items');// I use aliasing make joins easier
            $this->db->join('ip_invoices AS invoices', 'items.invoice_id = invoices.invoice_id', 'LEFT');
            $this->db->join('ip_clients AS clients', 'invoices.client_id = clients.client_id', 'LEFT');
            $this->db->join('ip_products AS products', 'products.product_id = items.item_product_id', 'LEFT');
            $this->db->join('ip_invoice_amounts AS amounts', 'items.invoice_id = amounts.invoice_id', 'LEFT');
            $this->db->join('ip_payments AS payments', 'items.invoice_id = payments.invoice_id', 'LEFT');

            if ($this->input->get('client_id')) {
                if ($this->input->get('client_id') != "all") {
                    $cid = $this->input->get('client_id'); 
                    $this->db->where_in('invoices.client_id',$cid);        
                } else {
                    if ($this->session->userdata('user_type') != 1) { 
                        if ($uc->num_rows() != 0) {
                            foreach ($uc->result() as $c) {
                                array_push($cids, $c->client_id);
                            } 
                        }
                            $this->db->where_in('invoices.client_id',$cids); 
                    }
                }
                

            } 
            $invoices = $this->db->get();
            if ($invoices->num_rows() != 0) {
                $invoices = $invoices->result();

                $this->load->library("excel");
                $phpExcel = new PHPExcel();
                $phpExcel->setActiveSheetIndex(0);
                $table_columns = array("INV NO ", "INV DATE", "DUE DATE", "CLIENT", "FROM DATE", "TO DATE", "MONTH", "EMP ID", "EMP NAME", "INV DESC", "PO Type", "PO Number", "PO Start Date", "PO End Date", "PO Rate as per client", "PO monthly Rate(Billable Amount)", "Billable Days/Hours/Months", "INV AMT", "CGST@9%", "SGST@9%", "IGST@18%", "NET AMT", "Invoice Status","TDS %","TDS","Net Amount", "Payment Date");
                $column = 0;

                foreach ($table_columns as $field) {
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                    $column ++;
                }

                foreach(range('A','Z') as $columnID) {
                    $phpExcel->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
                }

                $phpExcel->getActiveSheet()->getStyle("A1:AA1")->getFont()->setBold(true)
                                    ->setName('Verdana')
                                    ->setSize(12)
                                    ->getColor()->setRGB('ffffff');
                                    
                $phpExcel->getActiveSheet()->getStyle("A1:AA1")->getFill()->applyFromArray(array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                         'rgb' => "003366"
                    )
                ));



                $excel_row = 2;

                $this->load->model("products/mdl_products");
                $this->load->model("units/mdl_units");
                $this->load->model("invoices/mdl_invoices");
                $po_desc = $this->mdl_products->po_desc();
                $stat = $this->mdl_invoices->statuses();
                $phpExcel->getActiveSheet()->getRowDimension("1")->setRowHeight(25);

                foreach ($invoices as $row) {
                    $phpExcel->getActiveSheet()->getRowDimension($excel_row)->setRowHeight(25);

                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->invoice_number);

                    $invoice_date_created= strtotime($row->invoice_date_created);
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, date('m/d/Y', $invoice_date_created));

                    $invoice_date_due= strtotime($row->invoice_date_due);
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, date('m/d/Y', $invoice_date_due));

                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->client_name);

                    $invoice_start= strtotime($row->invoice_start);
                    if ($invoice_start == null) {
                        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, '-');
                    } else {
                        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, date('m/d/Y', $invoice_start));
                    }

                    $invoice_end= strtotime($row->invoice_end);
                    if ($invoice_end == null) {
                        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, '-');
                        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, '-');
                    } else {
                        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, date('m/d/Y', $invoice_end));
                        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, date('M-y', $invoice_end));
                    }


                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->empid);

                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->product_name);

                    if ($row->item_inv_desc == null) {
                        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $po_desc[$row->product_description]);
                    } else {
                        $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $po_desc[$row->item_inv_desc]);

                    }

                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $this->mdl_units->get_name($row->unit_id,1));

                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->product_no);

                    $product_start= strtotime($row->product_start);
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, date('m/d/Y', $product_start));

                    $product_end= strtotime($row->product_end);
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, date('m/d/Y', $product_end));

                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->product_price);

                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row->item_price);

                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, number_format($row->item_quantity,2));

                    $totalExm = $row->item_price * $row->item_quantity;
                    // $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->invoice_item_subtotal);
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row,$totalExm);

                            $igst = 0;
                            $cgst = 0;
                            $sgst = 0;

                    if($row->item_tax_rate_id == "1"){
                            $igst = ($totalExm * 18)/100;

                    } elseif ($row->item_tax_rate_id == "2") {
                        # code...
                    } elseif ($row->item_tax_rate_id == "3") {
                            $cgst = ($totalExm * 9)/100;
                    }  elseif ($row->item_tax_rate_id == "4") {
                            $sgst = ($totalExm * 9)/100;
                    }  elseif ($row->item_tax_rate_id == "5") {
                            $cgst = ($totalExm * 9)/100;
                            $sgst = ($totalExm * 9)/100;
                    } 

                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, $cgst);
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $sgst);
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $igst);



                    // $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, $row->invoice_total);
                    
                    $invoice_total = $totalExm + $igst + $cgst + $sgst;
                    
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, $invoice_total);
                    
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $stat[$row->invoice_status_id]["label"]);

                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->payment_tds);
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, $row->payment_tds_amount);
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->payment_amount);
                    $phpExcel->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, $row->payment_date);
                    $excel_row ++;
                    
                }
                $phpExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setIndent(1);
                $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);



                $object_writer = PHPExcel_IOFactory::createWriter($phpExcel,'Excel5');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="AllInvoice_'. date("dMy") .'.xls"');
                $object_writer->save('php://output');
            } else {
                $this->session->set_flashdata('alert_error', "No clients assigned to you");
            }
        }

        $this->layout->set('invoices', $invoices);
        $this->layout->buffer('content', 'reports/all_invoice_index')->render();

    }


}
