<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class HomeController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            
           	// View is being loaded within the custom page_helper, view_page method. 
        	return view_page('lingo');
        } else {          
 
            //If no session, redirect to login page
            redirect('login');
        }
    }

    public function logout()
    {
    	// Destroy user session on logout
        $this->session->unset_userdata('logged_in');
        $this->session->sess_destroy();
        redirect('login');
        exit;
    }
}
