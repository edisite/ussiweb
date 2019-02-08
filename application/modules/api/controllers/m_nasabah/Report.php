<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Report
 *
 * @author edisite
 */
class Report extends API_Controller {
    //put your code here
    private $arr_res = array();
    public function __construct() {
        parent::__construct();
        $this->load->model('Mdownline');
    }    
    public function GetDownline_post() {
        $in_parentid        = $this->input->post('agentid') ?: '';        
        $in_dateday         = $this->input->post('tgl') ?: date('Y-m-d'); 
        
        if(empty($in_parentid)){            
            $this->arr_res = array(
                'status'    => FALSE,
                'errorcode' => '601',
                'message'   => 'Parameter agentid is empty',
                'data'      => array('downline_id' => '','downline_name' => '','total' => '')
            );
            $this->response($this->arr_res);             
        }
        if(strlen($in_parentid) < 2 || strlen($in_parentid) > 6 || !is_numeric($in_parentid)){
            $this->arr_res = array(
                'status'    => FALSE,
                'errorcode' => '601',
                'message'   => 'Parameter agentid is empty',
                'data'      => array('downline_id' => '','downline_name' => '','total' => '')
            );
            $this->response($this->arr_res);
        }      
        
        $resdown = $this->Mdownline->Main($in_parentid,$in_dateday);
        if($resdown){
            $this->arr_res = array(
                'status'    => TRUE,
                'errorcode' => '600',
                'message'   => 'sucess',
                'data'      => $resdown
            );
        }else{
            $this->arr_res = array(
                'status'    => FALSE,
                'errorcode' => '602',
                'message'   => 'Tidak ada downline',
                'data'      => array('downline_id' => '','downline_name' => '','total' => '')
            );
        }
        
        $this->response($this->arr_res);          
    }
}
