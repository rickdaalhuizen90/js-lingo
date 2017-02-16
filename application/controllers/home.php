<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        if(isset($_COOKIE['Authentication'])) {
            
            // View is being loaded within the custom page_helper, view_page method. 
            return view_page('lingo');
        } else {          
 
            //If no session, redirect to login page
            redirect('login');
            exit;
        }   
        
    }

    public function logout()
    {
    	// Destroy cookie by setting the expire time to -10.
        setcookie("Authentication", $_COOKIE['Authentication'], time() -10);

        // Redirect
        redirect('login');
        exit;
    }
}
