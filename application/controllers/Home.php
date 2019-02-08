<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home page
 */
class Home extends MX_Controller {

	public function index()
	{
		$this->view('home');
               //redirect(base_url().'admin/');
	}
}
