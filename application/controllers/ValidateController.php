<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Validate user input
 */
class ValidateController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('LoginModel', true);
        $this->load->library('form_validation', 'session');
        $this->load->helper('form');
    }

    public function index()
    {
        if($this->validate() !== false)
            redirect('lingo'); //Go to private area

        return view_page('login');
    }

    private function validate()
    {
        if($this->input->post('submit_login'))
        {
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_verify_login'); // Callback to verify_login

        } elseif($this->input->post('submit_register')) {

            //register validation
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('cpassword', 'Confirm password', 'trim|required');

        }
        
        if ($this->form_validation->run() !== false)
            return true;

        return false;
    }

    public function verify_login($password)
    {
        $db = new LoginModel;
        //Field validation succeeded.  Validate against database
        $username = $this->input->post('username');

        //query the database
        $result = $db->getLoginCredentials($username, $password);
        
        if ($result) {

            // Create an user session
            $sess_array = array();
            foreach ($result as $row) {
                $session_data = array(
                    'id'        => $row->id,
                    'username'  => $row->username,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata('logged_in', $session_data);
            }
            return true;
        } else {
            $this->form_validation->set_message('verify_login', 'Invalid username or password');
            return false;
        }
    }
}
