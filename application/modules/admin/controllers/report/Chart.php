<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Chart extends Admin_Controller {
    public function __construct() {
        parent::__construct();
    }
    public function index()
    {
            $tanggal1 = $this->input->get('_dtm') ?: date('m/d/Y');
            $tanggal = date('Y-m-d', strtotime($tanggal1));
        
            $this->mViewData['tanggal']                 = $tanggal1;
            $this->render('_chart/v_chart');             
    }
    public function Detail() {
        $newdata = array(
			'startdate'  	=> @$this->input->post("start") ? $this->input->post("start") : date("Y").'-01-01',
			'enddate'  		=> @$this->input->post("end") ? $this->input->post("end") : date("Y").'-12-31',
			'label'  		=> @$this->input->post("label") ? $this->input->post("label") : 'Tahun Ini',
		);
        $this->session->set_userdata($newdata);
        $this->load->view('_chart/v_chart1');
    }
    
    
}