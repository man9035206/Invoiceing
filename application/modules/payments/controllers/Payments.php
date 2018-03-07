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
 * Class Payments
 */
class Payments extends Admin_Controller
{
    /**
     * Payments constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('mdl_payments');
    }

    /**
     * @param int $page
     */
    public function index($page = 0)
    {
        $this->mdl_payments->paginate(site_url('payments/index'), $page);
        $payments = $this->mdl_payments->result();

        $this->layout->set(
            array(
                'payments' => $payments,
                'filter_display' => true,
                'filter_placeholder' => trans('filter_payments'),
                'filter_method' => 'filter_payments'
            )
        );

        $this->layout->buffer('content', 'payments/index');
        $this->layout->render();
    }

    public function getInvoiceAmount() 
    {

        $invoice_id = $this->input->post('invoice_id');
        $amount_tds = $this->input->post('amount_tds');

        $this->load->model('invoices/mdl_invoices');
        $this->load->model('invoices/mdl_invoice_amounts');

        $this->db->from('ip_invoice_amounts');
        $this->db->where('invoice_id',$invoice_id);
        $this->db->where('invoice_balance >', 0);
        $invoice_amount = $this->db->get()->result();
                
        $this->db->from('ip_payments');
        $this->db->where('invoice_id',$invoice_id);
        $payment_exist = $this->db->get()->result();

        echo json_encode($invoice_amount);
    }
    /**
     * @param null $id
     */
    public function form($id = null)
    {
        $this->load->model('invoices/mdl_invoices');
        if ($this->input->post('btn_cancel')) {
            redirect('payments');
        }

        $invoice_id = $this->input->post('invoice_id');
        $payment_amount = $this->input->post('payment_amount');

        $invoice_details = $this->db->from('ip_invoice_amounts')->where('invoice_id', $invoice_id)->get()->result();

        if($invoice_details[0]->invoice_total < $payment_amount)
        {
            $credit_notes_amount = $payment_amount - $invoice_details[0]->invoice_total;
            $payment_amount = $invoice_details[0]->invoice_total;
        } else {
            $credit_notes_amount = 0;
            $payment_amount = $payment_amount;
        }

              
        if ($this->mdl_payments->run_validation()) {

            $db_array = array (
                'invoice_id' => $this->input->post('invoice_id'),
                'payment_date' => date_to_mysql($this->input->post('payment_date')),
                // 'payment_amount' => $this->input->post('payment_amount'),
                'payment_amount' => $payment_amount,
                'payment_method_id' => $this->input->post('payment_method_id'),
                'payment_note' => $this->input->post('payment_note'),
                'payment_tds' => $this->input->post('payment_tds'),
                'payment_tds_amount' => $this->input->post('payment_tds_amount'),
                'credit_notes_amount' => $credit_notes_amount
           );
            
            // $this->mdl_payments->insert_data($db_array, $id);
            
            $id = $this->mdl_payments->save($id, $db_array);

            $this->load->model('custom_fields/mdl_payment_custom');

            $this->mdl_payment_custom->save_custom($id, $this->input->post('custom'));

            redirect('payments');
        }

        if (!$this->input->post('btn_submit')) {
            $prep_form = $this->mdl_payments->prep_form($id);

            if ($id and !$prep_form) {
                show_404();
            }

            $this->load->model('custom_fields/mdl_payment_custom');
            $this->load->model('custom_values/mdl_custom_values');

            $payment_custom = $this->mdl_payment_custom->where('payment_id', $id)->get();

            if ($payment_custom->num_rows()) {
                $payment_custom = $payment_custom->row();

                unset($payment_custom->payment_id, $payment_custom->payment_custom_id);

                foreach ($payment_custom as $key => $val) {
                    $this->mdl_payments->set_form_value('custom[' . $key . ']', $val);
                }
            }
        } else {
            if ($this->input->post('custom')) {
                foreach ($this->input->post('custom') as $key => $val) {
                    $this->mdl_payments->set_form_value('custom[' . $key . ']', $val);
                }
            }
        }

        $this->load->helper('custom_values');
        $this->load->model('invoices/mdl_invoices');
        $this->load->model('payment_methods/mdl_payment_methods');
        $this->load->model('custom_fields/mdl_custom_fields');
        $this->load->model('custom_values/mdl_custom_values');

        $this->load->model('invoices/mdl_invoice_amounts');
        $this->load->model('user_clients/mdl_user_clients');
        $this->load->model('clients/mdl_clients');

        
        $user_id = $this->session->userdata('user_id');
        $user_type = $this->session->userdata('user_type');

        if($user_type == 1) {

            $open_invoices = $this->mdl_invoices
            ->where('invoice_status_id !=', 4)
            ->where('ip_invoice_amounts.invoice_balance >', 0)
            ->or_where('ip_invoice_amounts.invoice_balance <', 0)
            ->get()->result();
        
        } else {

            $this->db->from('ip_invoices');
            $this->db->join('ip_invoice_amounts','ip_invoices.invoice_id = ip_invoice_amounts.invoice_id');
            $this->db->join('ip_clients', 'ip_clients.client_id = ip_invoices.client_id');
            $this->db->join('ip_user_clients','ip_clients.client_id = ip_user_clients.client_id');
            $this->db->where('ip_user_clients.user_id', $user_id);   
            $this->db->where('ip_invoices.invoice_status_id !=', 4);
            $this->db->where('ip_invoice_amounts.invoice_balance >', 0);
            $this->db->or_where('ip_invoice_amounts.invoice_balance <', 0);
            $open_invoices = $this->db->get()->result(); 
        }

        $custom_fields = $this->mdl_custom_fields->by_table('ip_payment_custom')->get()->result();
        $custom_values = [];

        foreach ($custom_fields as $custom_field) {
            if (in_array($custom_field->custom_field_type, $this->mdl_custom_values->custom_value_fields())) {
                $values = $this->mdl_custom_values->get_by_fid($custom_field->custom_field_id)->result();
                $custom_values[$custom_field->custom_field_id] = $values;
            }
        }


        foreach ($custom_fields as $cfield) {
            foreach ($fields as $fvalue) {
                if ($fvalue->payment_custom_fieldid == $cfield->custom_field_id) {
                    // TODO: Hackish, may need a better optimization
                    $this->mdl_payments->set_form_value(
                        'custom[' . $cfield->custom_field_id . ']',
                        $fvalue->payment_custom_fieldvalue
                    );
                    break;
                }
            }
        }

        $amounts = array();
        $invoice_payment_methods = array();
        foreach ($open_invoices as $open_invoice) {
            $amounts['invoice' . $open_invoice->invoice_id] = format_amount($open_invoice->invoice_balance);
            $invoice_payment_methods['invoice' . $open_invoice->invoice_id] = $open_invoice->payment_method;
        }


        $this->layout->set(
            array(
                'payment_id' => $id,
                'payment_methods' => $this->mdl_payment_methods->get()->result(),
                'open_invoices' => $open_invoices,
                'custom_fields' => $custom_fields,
                'custom_values' => $custom_values,
                'amounts' => json_encode($amounts),
                'invoice_payment_methods' => json_encode($invoice_payment_methods)
            )
        );

        if ($id) {
            $this->layout->set('payment', $this->mdl_payments->where('ip_payments.payment_id', $id)->get()->row());
        }

        $this->layout->buffer('content', 'payments/form');
        $this->layout->render();
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $this->mdl_payments->delete($id);
        redirect('payments');
    }

}
