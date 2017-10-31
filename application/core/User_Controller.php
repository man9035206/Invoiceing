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
 * Class User_Controller
 */
class User_Controller extends Base_Controller
{
    /**
     * User_Controller constructor.
     * @param string $required_key
     * @param integer $required_val
     */
    public function __construct($required_key, $required_val)
    {
        parent::__construct();
        $r = $this->session->userdata($required_key);
        if ($r <> $required_val && $r <> 2 && $r <> 3 && $r <> 4 && $r <>5 && $r <> 6) {
            redirect('sessions/login');
        }
    }
}
