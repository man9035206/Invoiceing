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
 * Class Migrate
 */
class Migrate extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		if ($this->input->is_cli_request() == FALSE) {
			show_404();
		}
		$this->load->library('migration');
		$this->load->dbforge();
	}


	public function index()
	  {
	    if(!$this->migration->latest()) 
	    {
	      show_error($this->migration->error_string());
	    }
	  }

	public function latest() {
		$this->migration->latest();
		echo $this->migration->error_string() .PHP_EOL;
	}

	public function reset() {
		$this->migration->version(0);
		echo $this->migration->error_string() .PHP_EOL;
	}

	public function version($version = 0) {
		$version = (int) $version;
		if ($version == 0) {
			die("Version should be greater than zero").PHP_EOL;
		}
		$this->migration->version($version);
		echo $this->migration->error_string() .PHP_EOL;
	}

}
