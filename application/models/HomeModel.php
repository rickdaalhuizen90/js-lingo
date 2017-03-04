<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class HomeModel extends CI_Model
{
    public function get_random_word($game_mode)
    {
    	//Get a random word that matches the value of "game_mode" 4x4, 5x5 or 6x6.
        $query = $this->db->query("SELECT `words` FROM `wordlist` WHERE LENGTH(`words`) = {$game_mode} ORDER BY RAND() LIMIT 1");

        if($query->num_rows() > 0){
            return $query->result();
            exit;
        } 
        
        return false;
    }
}
