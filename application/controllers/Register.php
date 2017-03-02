<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

class Register extends MY_Controller
{
    private $_db;

    public function __construct()
    {
    	parent::__construct();

         // Load loginModel
        if($this->load->model("RegisterModel", true))
            $this->_db = new RegisterModel;
    }

    public function index() 
    {
        // Register new user on success
        if($_SERVER["REQUEST_METHOD"] === "POST" && $this->_validate()) 
            $this->_register_new_user();    
  
        return view_page("register"); 
    }

    public function show_message($msg)
    {
        // Use a super global to return a message
        $_POST["show_message"] = $msg;
    }

    private function _user_input()
    {
        $options = array(
            'cost' => 11,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        );
        
        return (object) array(
            "avatar"    => (object) $_FILES["avatar"],
            "username"  => $this->input->post("username"),
            "email"     => $this->input->post("email"),
            "password"  => $this->input->post("password"),
            "cpassword" => $this->input->post("cpassword"),
            "hash"      => password_hash($this->input->post("password"), PASSWORD_BCRYPT, $options),
        );
    }

    private function _validate()
    {

        if($this->input->post("submit_register")) {
    
            //Set rules for validation
            $this->form_validation->set_rules("avatar", "Avatar", "trim|callback_verify_avatar");
            $this->form_validation->set_rules("username", "Username", "trim|required|alpha_numeric|min_length[4]|is_unique[users.username]", 
                                        array("is_unique" => "This username already exists. Please choose another one."));
            $this->form_validation->set_rules("email", "Email", "trim|required|valid_email|is_unique[users.email]");
            $this->form_validation->set_rules("password", "Password", "trim|required|min_length[5]");
            $this->form_validation->set_rules("cpassword", "Confirm Password", "trim|required|min_length[5]|matches[password]");

        }
        
        if ($this->form_validation->run() !== false) 
            return true;

        return false;
    }

    public function verify_avatar()
    {
        if(!empty($_FILES["avatar"]) && $this->_user_input()->avatar->tmp_name) {
            
            $tmp_name   = $this->_user_input()->avatar->tmp_name;
            $name       = $this->_user_input()->avatar->name;
            $type       = $this->_user_input()->avatar->type;
            $size       = $this->_user_input()->avatar->size;
            $path       = "./assets/images/thumbs/";

            $supported  = ["image/jpeg", "image/jpg", "image/png"];
            $size_limit = 650000;

            try {

                if(!in_array($type, $supported)){

                    throw new Exception("File must be JPEG or PNG");
                }elseif ($size > $size_limit) {

                    throw new Exception("The avatar size limit is " . $size_limit . "kb");
                }else {       

                    // Upload avatar (take the basename and convert it to png to be sure that the image is not containt).
                    $uploadfile = $path . basename(md5($this->_user_input()->email)) . ".png";
                    move_uploaded_file($tmp_name, $uploadfile);
                    return true;  
                }

            }catch(Exception $e){

                // Equivalent of the form_validation->set_message
                $this->show_message($e->getMessage());
            }     
        } 
    }

    private function _register_new_user()
    {
        $username   = $this->_user_input()->username;
        $email      = $this->_user_input()->email;
        $hash       = $this->_user_input()->hash;

        try {
            
            //query the database
            $result = $this->_db->create_new_user($username, $email, $hash);

            if($result !== false){
 
                redirect("login");
                exit; 
            }else{
                throw new Exception("Oops... something went wrong. please try again");
            }
         
        } catch (Exception $e) {

            $this->form_validation->set_message("required", $e->getMessage());
        }
    }
}
