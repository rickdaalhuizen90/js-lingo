<?php if (!defined("BASEPATH")) exit("No direct script access allowed"); 

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Load libraries and helpers
        $this->load->library("form_validation");
        $this->load->helper("form");
	}
}