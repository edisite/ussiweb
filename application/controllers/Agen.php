<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Agen
 *
 * @author edisite
 */
class Agen extends CI_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        redirect('agen/login');
    }
}
