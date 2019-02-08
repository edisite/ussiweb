<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cp
 *
 * @author edisite
 */
class Cp extends Admin_Controller {
    //put your code here
    public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
		$this->mTitle = 'CP ';
	}
        public function index($cpname = "")
	{		
		//$this->render('crud',$cpname);
                $this->render('errors/error_404');
	}
        public function Id($grpid = 'default') {
            //$this->mTitle   .= 'Mobilink';
               
//            $data = $this->gcpid($grpid);
//            $this->render('apps/homegroup'); 
            
                $this->load->model('handle_apps_model', 'handle_apps');
		$target = $this->handle_apps->get_by('cpid',$grpid);
                if($target){
                    //echo $target->id;
                    //echo $target->cpid;
                    $this->mTitle .=  $target->cpname;
                }else{
                    $this->mTitle = "MEnu";
                    $this->render('errors/error_404');
                }
                $this->render('apps/homegroup'); 
        }
        protected function Gcpid($cpid){
            //$this->load->database();
            $this->load->model('Mapp');
            $data = $this->Mapp->get_cpid($cpid);           
            return $data;
           
        }
        
}
