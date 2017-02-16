<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
    private $_db;

    public function __construct()
    {
        parent::__construct();

        // Load libraries and helpers
        $this->load->library('form_validation');
        $this->load->helper('form');

        // Load loginModel
        if($this->load->model('loginModel', true))
            $this->_db = new LoginModel;
    }

    public function index()
    {
        if(isset($_COOKIE['Authentication'])) {
            
            return view_page('lingo');
            exit;
        } else {
            
            $this->_validate();
            return view_page('login');
            exit;
        }
    }

    private function _user_input()
    {
        return (object) array(
            "username" => $this->input->post('username'),
            "password" => $this->input->post('password')
        );
    }

    private function _validate()
    {
        if($this->input->post('submit_login')) {

            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_verify_login');
        }

        if ($this->form_validation->run() !== false)
            return true;

        return false;
    }

    public function verify_login()
    {
         
        // init user input
        $username = $this->_user_input()->username;
        $password = $this->_user_input()->password;
        
        try {

             //query the database
            $result = $this->_db->get_login_credentials($username);

            // Return null if result is empty.
            $result !== false ? $hash = $result[0]->password : $hash = null;
            
            if($result !== false && $hash !== null) {

                // create jwt if password hash matches.
                if($this->bcrypt->check_password($password, $hash))
                    $this->_create_jwt($result);
                
            } else {
                throw new Exception("Invalid username or password");
            }
   
        } catch (Exception $e) {

            $this->form_validation->set_message('required', $e->getMessage());
        }
    }

    private function _create_jwt($result)
    {
        if($result) {

            $username   = $result[0]->username;
            $email      = $result[0]->email;
            $exp_date   = date('d-m-y G:i:s', strtotime('+1 days'));
            $algorithm  = 'HS512'; // HS256, HS385
            $key        = 'DS68N%ISwW*1^Z0qWH^ezjkE7atZde0Pf1';

            /*
            * -------------------------------------------------
            *                      CLAIMS
            * -------------------------------------------------
            *  Sub: Who this person is (sub, short for subject)
            *  Iss: Who issued the token (iss, short for issuer)
            *  Scopes: What this person can access with this token (scope)
            *  Exp: When the token expires (exp)
            */
            $payload = array (
                'sub' => $email,
                'iss' => $username,
                'scopes' => ["admin", "user"],
                'exp' => strtotime($exp_date), // Expire date
            );

            // Jason Webtoken
            // Header typ: JWT & default algorithm: HS256
            if($token = JWT::encode($payload, $key, $algorithm)) {

                // Set cookie
                // https://stormpath.com/blog/where-to-store-your-jwts-cookies-vs-html5-web-storage
                setcookie('Authentication', $token, time() + (86400 * 30), "/");

                // Redirect to admin area
                redirect('lingo'); 
                exit;
            }
                    
        }
        
    }

    /*
    IF YOU WANT TO USE SESSION!
    private function _create_user_session($result)
    {
        if($result) {

            // Create an user session
            $sess_array = array();
            foreach ($result as $row) {
                $session_data = array(
                    'id'        => $row->id,
                    'username'  => $row->username,
                    'logged_in' => true
                );
                $this->session->set_userdata($session_data);
            }
            
            redirect('lingo'); //Go to private area
            exit;
        } 
    }
    */
}
