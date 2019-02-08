<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Xmlserver
 *
 * @author edisite
 */
class Xmlserver extends MX_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index()
        {
                $this->load->library('xmlrpc');
                $this->load->library('xmlrpcs');

                $config['functions']['Greetings'] = array('function' => 'xmlserver.process');

                $this->xmlrpcs->initialize($config);
                $this->xmlrpcs->serve();
        }


        public function process($request)
        {
                $parameters = $request->output_parameters();

                $response = array(
                        array(
                                'you_said'  => $parameters[0],
                                'i_respond' => 'Not bad at all.'
                        ),
                        'struct'
                );

                return $this->xmlrpc->send_response($response);
        }
}
