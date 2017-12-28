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
 * Class Users
 */
class Users extends Admin_Controller
{
    /**
     * Users constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_users');
    }

    /**
     * @param int $page
     */
    public function update()
    {
          $c_name = $this->input->post('c_name');
          $c_company = $this->input->post('c_company');
          $c_email = $this->input->post('c_email');
          $c_address = $this->input->post('c_address');

          $c_gstin = $this->input->post('c_gstin');
          $c_pan = $this->input->post('c_pan');
          $c_sac = $this->input->post('c_sac');
          $c_lut = $this->input->post('c_lut');

          $data = array(
               'setting_value'=>$c_name
          );
         $this->db->where('setting_key', 'j2w_contact_name');
         $this->db->update('ip_settings',$data);

         $data1 = array(
               'setting_value'=>$c_company
          );
         $this->db->where('setting_key', 'j2w_company_name');
         $this->db->update('ip_settings',$data1);

         $data2 = array(
               'setting_value'=>$c_email
          );
         $this->db->where('setting_key', 'j2w_email');
         $this->db->update('ip_settings',$data2);

         $data3 = array(
               'setting_value'=>$c_address
          );
         $this->db->where('setting_key', 'j2w_address');
         $this->db->update('ip_settings',$data3);

         $data4 = array(
               'setting_value'=>$c_gstin
          );
         $this->db->where('setting_key', 'j2w_GSTIN');
         $this->db->update('ip_settings',$data4);

         $data5 = array(
               'setting_value'=>$c_pan
          );
         $this->db->where('setting_key', 'j2w_PAN');
         $this->db->update('ip_settings',$data5);

         $data6 = array(
               'setting_value'=>$c_sac
          );
         $this->db->where('setting_key', 'j2w_SAC_Code');
         $this->db->update('ip_settings',$data6);

         $data7 = array(
               'setting_value'=>$c_lut
          );
         $this->db->where('setting_key', 'j2w_LUT_number');
         $this->db->update('ip_settings',$data7);


         redirect('/users/j2winfo');
    }

    public function index($page = 0)
    {
        $this->mdl_users->paginate(site_url('users/index'), $page);
        $users = $this->mdl_users->result();

        $this->layout->set('users', $users);
        $this->layout->set('user_types', $this->mdl_users->user_types());
        $this->layout->buffer('content', 'users/index');
        $this->layout->render();
    }

    public function j2winfo()
    {
        if ($this->input->post('btn_cancel')) {
            redirect('users');
        }

        $this->layout->buffer('content', 'users/j2winfo');
        $this->layout->render();        
    }

    /**
     * @param null $id
     */
    public function form($id = null)
    {
        if ($this->input->post('btn_cancel')) {
            redirect('users');
        }

        if ($this->mdl_users->run_validation(($id) ? 'validation_rules_existing' : 'validation_rules')) {
            $id = $this->mdl_users->save($id);

            $this->load->model('custom_fields/mdl_user_custom');
            $this->mdl_user_custom->save_custom($id, $this->input->post('custom'));

            // Update the session details if the logged in user edited his account
            if ($this->session->userdata('user_id') == $id) {
                $new_details = $this->mdl_users->get_by_id($id);

                $session_data = array(
                    'user_type' => $new_details->user_type,
                    'user_id' => $new_details->user_id,
                    'user_name' => $new_details->user_name,
                    'user_email' => $new_details->user_email,
                    'user_company' => $new_details->user_company,
                    'user_language' => isset($new_details->user_language) ? $new_details->user_language : 'system',
                );

                $this->session->set_userdata($session_data);
            }

            $this->session->unset_userdata('user_clients');

            redirect('users');
        }

        if ($id && !$this->input->post('btn_submit')) {
            if (!$this->mdl_users->prep_form($id)) {
                show_404();
            }

            $this->load->model('custom_fields/mdl_user_custom');

            $user_custom = $this->mdl_user_custom->where('user_id', $id)->get();

            if ($user_custom->num_rows()) {
                $user_custom = $user_custom->row();

                unset($user_custom->user_id, $user_custom->user_custom_id);

                foreach ($user_custom as $key => $val) {
                    $this->mdl_users->set_form_value('custom[' . $key . ']', $val);
                }
            }
        } elseif ($this->input->post('btn_submit')) {
            if ($this->input->post('custom')) {
                foreach ($this->input->post('custom') as $key => $val) {
                    $this->mdl_users->set_form_value('custom[' . $key . ']', $val);
                }
            }
        }

        $this->load->helper('custom_values');
        $this->load->model('user_clients/mdl_user_clients');
        $this->load->model('clients/mdl_clients');
        $this->load->model('custom_fields/mdl_custom_fields');
        $this->load->model('custom_fields/mdl_user_custom');
        $this->load->model('custom_values/mdl_custom_values');
        $this->load->helper('country');

        $custom_fields = $this->mdl_custom_fields->by_table('ip_user_custom')->get()->result();
        $custom_values = [];
        foreach ($custom_fields as $custom_field) {
            if (in_array($custom_field->custom_field_type, $this->mdl_custom_values->custom_value_fields())) {
                $values = $this->mdl_custom_values->get_by_fid($custom_field->custom_field_id)->result();
                $custom_values[$custom_field->custom_field_id] = $values;
            }
        }

        $fields = $this->mdl_user_custom->get_by_useid($id);

        foreach ($custom_fields as $cfield) {
            foreach ($fields as $fvalue) {
                if ($fvalue->user_custom_fieldid == $cfield->custom_field_id) {
                    // TODO: Hackish, may need a better optimization
                    $this->mdl_users->set_form_value(
                        'custom[' . $cfield->custom_field_id . ']',
                        $fvalue->user_custom_fieldvalue
                    );
                    break;
                }
            }
        }

        $this->layout->set(
            array(
                'id' => $id,
                'user_types' => $this->mdl_users->user_types(),
                'user_clients' => $this->mdl_user_clients->where('ip_user_clients.user_id', $id)->get()->result(),
                'custom_fields' => $custom_fields,
                'custom_values' => $custom_values,
                'countries' => get_country_list(trans('cldr')),
                'selected_country' => $this->mdl_users->form_value('user_country') ?: get_setting('default_country'),
                'clients' => $this->mdl_clients->where('client_active', 1)->get()->result(),
                'languages' => get_available_languages(),
            )
        );

        $this->layout->buffer('content', 'users/form');
        $this->layout->render();
    }

    /**
     * @param $user_id
     */
    public function change_password($user_id)
    {
        if ($this->input->post('btn_cancel')) {
            redirect('users');
        }

        if ($this->mdl_users->run_validation('validation_rules_change_password')) {
            $this->mdl_users->save_change_password($user_id, $this->input->post('user_password'));
            redirect('users/form/' . $user_id);
        }

        $this->layout->buffer('content', 'users/form_change_password');
        $this->layout->render();
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        if ($id <> 1) {
            $this->mdl_users->delete($id);
        }
        redirect('users');
    }

    /**
     * @param $user_id
     * @param $user_client_id
     */
    public function delete_user_client($user_id, $user_client_id)
    {
        $this->load->model('mdl_user_clients');

        $this->mdl_user_clients->delete($user_client_id);

        redirect('users/form/' . $user_id);
    }

}
