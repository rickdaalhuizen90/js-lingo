<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

if(!function_exists("password_match"))
{
    function password_match($password, $cpassword)
	{
		if(strcmp($password, $cpassword)){
            $this->form_validation->set_message("password_match", "password and confirmation password doesn\"t match");
            return false;
        } else {
            return true;
        }
	} 
}