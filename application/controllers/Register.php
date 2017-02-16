<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller
{
    private $_db;

    public function __construct()
    {
    	parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form', 'validate');

         // Load loginModel
        if($this->load->model('RegisterModel', true))
            $this->_db = new RegisterModel;
    }

    public function index() 
    {
        // Register new user on success
    	$this->_validate() !== false ? $this->_register_new_user() : $this->_validate();
        
        return view_page('register');
    }

    private function _user_input()
    {
        return (object) array(
            "password" => $this->input->post('password'),
            "cpassword" => $this->input->post('cpassword'),
            "hash" => $this->bcrypt->hash_password($this->input->post('password')),
            "username" => $this->input->post('username'),
            "email" => $this->input->post('email'),
        );
    }

    private function _validate()
    {
        if($this->input->post('submit_register')) {
    
            //Set rules for validation
            $this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[users.username]', 
                                        array('is_unique' => 'This username already exists. Please choose another one.'));
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|min_length[5]|matches[password]');
        }
        
        if ($this->form_validation->run() !== false)
            return true;

        return false;
    }

    private function _register_new_user()
    {
        $username = $this->_user_input()->username;
        $email = $this->_user_input()->email;
        $hash = $this->_user_input()->hash;

        try {

            //query the database
            $result = $this->_db->create_new_user($username, $email, $hash);

            if($result !== false) {
                redirect('login');
                exit;
            } else {
                throw new Exception("Oops... something went wrong.");
            }
   
        } catch (Exception $e) {

            $this->form_validation->set_message('required', $e->getMessage());
        }
    }
}
