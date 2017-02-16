<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LoginModel extends CI_Model
{
    public function get_login_credentials($username = null)
    {
        $query = $this->db->get_where('users', ['username'=>$username]);

        if ($query->num_rows() == 1) {
            return $query->result();
            exit;
        } 
        
        return false;
    }
}
