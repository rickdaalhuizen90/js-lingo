<?php if (!defined("BASEPATH")) exit("No direct script access allowed"); 

class Home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if(isset($_COOKIE["Player"]) || isset($_COOKIE["Guest"])){

            // Submit popup form.
            if($_SERVER["REQUEST_METHOD"] === "POST") 
                $this->_popup_verify();
                    
            return view_page("lingo");
        } else {          
 
            //If no session, redirect to login page
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

        // Redirect
        redirect("login");
        exit;
    }

    private function _popup_user_input()
    {
        // letter_count 
        return (object) array(
            "letter_count" => (int)$this->input->post('set_letter_count')
        );
    }

    private function _popup_validate()
    {
        // check letter_count
        if($this->input->post("submit_popup")) {
            $this->form_validation->set_rules(
                "set_letter_count", 
                "letter_count", 
                "trim|max_length[1]|integer|required"
            );
        }

        if ($this->form_validation->run() !== false)
            return true;

        return false;
    }

    private function _popup_verify()
    {
        $letter_count = $this->_popup_user_input()->letter_count;

        // Give default value if empty
        if(empty($letter_count)) $letter_count = 5;

        try {

            //Save cookie
            $game_config = json_encode(array(
                "letter_count"  => $letter_count,
                "room_name"     => $this->_random_room_name(),
                "player_two"    => $this->_random_bot_name(),
                "type"          => "single_player"
            ), JSON_FORCE_OBJECT);

            // name, value, expire, path, domain
            $cookie = setcookie('game_config', $game_config, 0, "/home", "lingo.io");

            if($cookie){
                // After save result to db return a random word.
                $this->_lingo_random_word($letter_count);
                redirect("home");
                exit;
            }else{
                throw new Exception("Oops... something went wrong. please try again");
            }

        } catch(Exception $e) {
            $this->form_validation->set_message("required", $e->getMessage());
        }
    }

    private function _get_result_from_db()
    {
        //get result from db;
    }

    private function _lingo_random_word($letter_count = 5)
    {
        //exit('random_word');
        // get random word from db
        // return to game
        //return "randomword";
    }

    private function _random_room_name()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $room_name = '';

        // Generate a random string.
        for ($i = 0; $i < 6; $i++) {
            $room_name .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $room_name;
    }

    private function _random_bot_name()
    {
        $names = array(
            "Darth Vader",
            "Luke Skywalker",
            "Kylo Ren",
            "Yoda",
            "Han Solo",
            "Obi-Wan",
            "Boba Fett",
            "R2-D2",
            "Chewbacca",
            "Darth Maul",
            "C-3PO"
        );

        // Return a random name.
        $random_index = array_rand($names, 1);
        return $names[$random_index];
    }
}