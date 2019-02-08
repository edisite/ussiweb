<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Openhttp
 *
 * @author edisite
 */
class Openhttp  extends MY_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    function Hitget($url)
    {
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        curl_close($ch);
        echo $output;
    }
    function Hitpost($url= NULL,$postText=null) {
        $ch=curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postText);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json\r\n',            
            'Content-Length: ' . strlen($postText) ,
            'XAPIKEY' => 'a1s2d3f4g5'
            )             
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);          
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $sfd = "Failed : ".curl_error($ch);
            echo $sfd;
        } else {
            echo $data;
        }
        curl_close($ch);  
        
    }
}
