<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

session_start();

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {

            $session_data     = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];
            
            //helper view
            $this->load->view('home_view', $data);

        } else {
            
            //If no session, redirect to login page
            redirect('login', 'refresh');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect('home', 'refresh');
    }
}
