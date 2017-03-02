<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

if(!function_exists("view_page")) {
    function view_page($page = "login")
	{
		$CI = &get_instance();

        if(isset($_COOKIE["Player"])) {
            $jwt = JWT::decode($_COOKIE["Player"], JWT::get_key());
           
            $data["email"]      = $jwt->email;
            $data["username"]   = $jwt->name;
            $data["scopes"]     = $jwt->scopes;    
        } 

        if(isset($_COOKIE["Guest"])) {
            $jwt = JWT::decode($_COOKIE["Guest"], JWT::get_key());

            $data["email"]      = "";
            $data["username"]   = "Guest";
            $data["scopes"]     = $jwt->scopes;
        }

		
        if ( ! file_exists(APPPATH."views/pages/{$page}.php")) 
            show_404();

        $data["title"]      = ucfirst($page); 
        $data["message"]    = isset($_POST["show_message"]) ? "<p>{$_POST['show_message']}</p>" : "";
        
        $CI->load->view("templates/header", $data);
        $CI->load->view("pages/{$page}", $data);
        $CI->load->view("templates/footer", $data);
	} 
}