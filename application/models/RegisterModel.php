<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RegisterModel extends CI_Model
{
    public function create_new_user($username = null, $email = null, $hash = null)
    {
        $data = array(
            'username' => $username,
            'email' => $email,
            'password' => $hash,
            'create_time' => datetime(), // Custom helper dateime()
        );

        if($this->db->insert('users', $data))
            return true;

        return false;
    }
}
