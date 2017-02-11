<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('view_page'))
{
    function view_page($page = 'login')
	{
		$CI = &get_instance();

        // Get Session username when Session is setted.
        if ($CI->session->userdata('logged_in')) {
            $session = $CI->session->userdata('logged_in');
            $data['username'] = $session['username'];
        } else {
            $session = null;
        }
		
        if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php')) 
            show_404();

        $data['title'] = ucfirst($page); // Capitalize the first letter
        
        $CI->load->view('templates/header', $data);
        $CI->load->view('pages/'.$page, $data);
        $CI->load->view('templates/footer', $data);
	} 
}