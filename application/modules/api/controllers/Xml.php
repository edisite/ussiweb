<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Xml
 *
 * @author edisite
 */
class Xml extends MX_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
        {
                $this->load->helper('url');
                $server_url = 'http://localhost/ci/direct3/xml/Xmlserver';
                $this->load->library('xmlrpc');
                $this->xmlrpc->server($server_url, 80);
                $this->xmlrpc->method('Greetings');

                $request = array('How is it going?');
                $this->xmlrpc->request($request);
                print_r($server_url);
                if ( ! $this->xmlrpc->send_request())
                {
                        echo $this->xmlrpc->display_error();
                }
                else
                {
                        echo '<pre>';
                        print_r($this->xmlrpc->display_response());
                        echo '</pre>';
                }
        }
        
}
