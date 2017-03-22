<?php if (!defined("BASEPATH")) exit("No direct script access allowed"); 

class Home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load model
        if($this->load->model("HomeModel", true))
            $this->_db = new HomeModel;
    }

    public function index()
    {
        // View home page on success.
        if(isset($_COOKIE["Player"]) || isset($_COOKIE["Guest"])){

            // Submit popup form.
            if($_SERVER["REQUEST_METHOD"] === "POST") 
                $this->_popup_verify();
                    
            return view_page("home");
        } else {          
 
            redirect("login");
            exit;
        }   
        
    }

    public function logout()
    {
    	// Destroy cookie by setting the expire time to -10
        if(isset($_COOKIE["Guest"])){
            setcookie("Guest", $_COOKIE["Guest"], time() -10);
        }elseif(isset($_COOKIE["Player"])){
            setcookie("Player", $_COOKIE["Player"], time() -10);
        }

        redirect("login");
        exit;
    }

    private function _popup_user_input()
    {
        //Don't trust user input (convert value to an integer)
        $val    = str_split($this->input->post("set_game_mode"));
        $count  = (int)$val[0];

        // Check if game mode is 4x4, 5x5 or 6x6.
        if(is_int($count) && $count > 3 && $count < 7) {
            // Return the validated user input.
            return (object) array(
                "game_mode" => $count
            );
        }

        return false;
    }

    private function _popup_validate()
    {
        // Check if user input is set and trim the user input from special characters.
        if($this->input->post("submit_popup")) 
            $this->form_validation->set_rules("set_game_mode", "set_game_mode", "trim|required");  

        // Return true on success.
        if ($this->form_validation->run() !== false)
            return true;

        return false;
    }

    private function _game_config($game_mode = false)
    {
        // Configure the game with a game mode, room_name and game_type.
        if($game_mode){
            return json_encode(array(
                "game_mode" => $game_mode,
                "room_name" => $this->_random_room_name(),
                "game_type" => "single_player"
            ), JSON_FORCE_OBJECT);
        }

        return false;
    }

    private function _game_current($lives = false, $round = false, $score = false, $word = false)
    {
        // Configure the current game session with lives, rounds and a random word.
        if(isset($lives) && isset($round) && isset($score) && isset($word)){
            return json_encode(array(
                "lives" => $lives,
                "round" => $round,
                "score" => $score,
                "word"  => strtoupper($word)
            ), JSON_FORCE_OBJECT);
        }

        return false;
    }

    private function _random_room_name()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $room_name = '';

        // Generate a random string with a length of 5.
        for ($i = 0; $i < 6; $i++) {
            $room_name .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $room_name;
    }

    private function _random_word($game_mode)
    {
        try{
            // Get a random word from the database.
            $result = $this->_db->get_random_word($game_mode);

            if($result && $result != null){
                // Return a random word. 
                return $result[0]->words;
            } else {
                throw new Exception("Oops... something went wrong. please try again");
            }

        }catch(Exception $e){
            $this->form_validation->set_message("required", $e->getMessage());
        }
    }

    private function _popup_verify()
    {
        try {
            // Set game_mode, game_config & game_current (return false on failure).
            $game_mode      = $this->_popup_user_input()->game_mode;
            $game_config    = $this->_game_config($game_mode);
            $game_current   = $this->_game_current(3, 1, 0, $this->_random_word($game_mode));

            // Check if game_mode, game_config and game_current are all set.
            if($game_mode && $game_config && $game_current) {

                // Set cookies (name, value, expire, path, domain)
                $cookies = array(
                    setcookie('game_config', $game_config, 0, "/home", "lingo.io"),
                    setcookie('game_current', $game_current, 0, "/home", "lingo.io"),
                );

                // Redirect to home if cookies are set.
                if($cookies) redirect("home");
                exit;
            }else{
                throw new Exception("Oops... something went wrong. please try again");
            }

        } catch(Exception $e) {
            $this->form_validation->set_message("required", $e->getMessage());
        }
    }
}