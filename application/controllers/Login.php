<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

class Login extends MY_Controller
{
    private $_db;

    public function __construct()
    {
        parent::__construct();

        // Load loginModel
        if($this->load->model("loginModel", true))
            $this->_db = new LoginModel;
    }

    public function index()
    {
        if(isset($_COOKIE["Player"]) || isset($_COOKIE["Guest"])) {         
            
            return view_page("lingo");
            exit;            
        } else {

            // Check if it's an user login or guest login.
            if($_SERVER["REQUEST_METHOD"] === "POST"){
                if($this->input->post("submit_login_guest")){

                    // No need to validate on a guest account.
                    $this->_create_jwt();
                }else{
                    $this->_validate();
                }
            }    
            
            return view_page("login");
            exit;
        }
    }

    private function _user_input()
    {
        return (object) array(
            "username"  => $this->input->post("username"),
            "password"  => $this->input->post("password")
        );
    }

    private function _validate()
    {
        if($this->input->post("submit_login")) {
            $this->form_validation->set_rules("username", "Username", "trim|required");
            $this->form_validation->set_rules("password", "Password", "trim|required|callback_verify_login");
        }

        if ($this->form_validation->run() !== false)
            return true;

        return false;
    }

    public function verify_login()
    {
         
        // initialize user input
        $username = $this->_user_input()->username;
        $password = $this->_user_input()->password;
        
        try {

            $result = $this->_db->get_username($username);
            $result !== false ? $hash = $result[0]->password : $hash = null;

            if($result !== false && $hash !== null) {

                // create jwt if password hash matches.  
                if(password_verify($password, $hash)) 
                    $this->_create_jwt($result);
                
            } else {
                throw new Exception("Invalid username or password");
            }
   
        } catch (Exception $e) {

            $this->form_validation->set_message("required", $e->getMessage());
        }
    }

    private function _create_jwt($result = null)
    {
        // Create jwt if credentials are valid.
        if($result) {
            $user_id    = $result[0]->id;
            $username   = $result[0]->username;
            $email      = $result[0]->email;
        }

         // Get avatar   
        $path = "./assets/images/thumbs/";
        if(!file_exists($path . md5($email). ".png")) { 
            // Return default avatar 
            $avatar = "{$path}avatar.png";
        } else {           
            $avatar = $path . md5($email).".png";
        }

        $exp_date   = date("d-m-y H:i:s", strtotime("+1 days"));
        $algorithm  = "HS512"; // HS256, HS385
        $key        = JWT::get_key();

        /*
        * -------------------------------------------------
        *                      CLAIMS
        * -------------------------------------------------
        *  Sub: Who this person is (sub, short for subject)
        *  Iss: Who issued the token (iss, short for issuer)
        *  Scopes: What this person can access with this token (scope)
        *  Exp: When the token expires (exp)
        */
       
        if(isset($_POST["submit_login"])) {

            // Payload for player
            $cookie_name = "Player";
            $payload = array (
                // Registered claims
                "sub"       => "user",
                "iss"       => $_SERVER["SERVER_NAME"],
                "exp"       => strtotime($exp_date), // Expire date
                // Public claims
                "scopes"    => ["user", "player"],
                "id"        => $user_id,
                "name"      => $username,
                "email"     => $email,
                "img"       => $avatar,
            );

        } elseif (isset($_POST["submit_login_guest"])) {

            // Payload for guest
            $cookie_name = "Guest";
            $payload = array (
                // Registered claims
                "sub"       => "user",
                "iss"       => $_SERVER["SERVER_NAME"],
                "exp"       => strtotime($exp_date), // Expire date
                // Public claims
                "scopes"    => ["user", "guest"],
                "id"        => mt_rand(1, 100000),
                "name"      => "Guest",
                "email"     => "guest@email.com",
                "img"       => $avatar,
            );
        }
        
        // Jason Webtoken
        // Header typ: JWT & default algorithm: HS256
        try {

            if($token = JWT::encode($payload, $key, $algorithm)) {

                // Set cookie
                // https://stormpath.com/blog/where-to-store-your-jwts-cookies-vs-html5-web-storage
                setcookie($cookie_name, $token, time() + (86400 * 7), "/");

                // Redirect to admin area
                redirect("lingo"); 
                exit;
            } else {
                throw new Exception("Oops... Something went wrong, please try again.");
            }

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
