<?php
/**
* 
*/
class Migrate extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		if ($this->input->is_cli_request() == FALSE) {
			show_404();
		}
	}

	public function index() {
		echo "Hello";
	}
}