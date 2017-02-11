<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class LoginController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
    }

    public function index()
    { 
    	// View is being loaded within the custom page_helper, view_page method. 
        return view_page('login');
    }

}
