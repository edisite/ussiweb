<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Notfound
 *
 * @author edisite
 */
class Notfound extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Error_page()
	{
		$this->mTitle.= ' ';
		$this->render('errors/error_404');
	}

}
