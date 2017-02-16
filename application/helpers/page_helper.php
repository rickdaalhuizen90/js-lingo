<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('view_page'))
{
    function view_page($page = 'login')
	{
		$CI = &get_instance();

        if(isset($_COOKIE['Authentication'])) {
            $jwt = JWT::decode($_COOKIE['Authentication'], 'DS68N%ISwW*1^Z0qWH^ezjkE7atZde0Pf1');

            /*
            eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJyaWNrQGdtYWlsLmNvbSIsImlzcyI6InJpY2siLCJzY29wZXMiOlsiYWRtaW4iLCJ1c2VyIl0sImV4cCI6MTQ4NzM1MTY2NH0.JyKccNJxYw19hMuM-stedf6q6QaSRz_x7fOmEybWtxKA9iuzmUdgUiRSrsqVNlWAmKN6ZmJ40KkOiJQV0yO0fw
            */

            $data['email'] = $jwt->sub;
            $data['username'] = $jwt->iss;
        }
		
        if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php')) 
            show_404();

        $data['title'] = ucfirst($page); // Capitalize the first letter
        
        $CI->load->view('templates/header', $data);
        $CI->load->view('pages/'.$page, $data);
        $CI->load->view('templates/footer', $data);
	} 
}