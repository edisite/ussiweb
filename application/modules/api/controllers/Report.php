<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of report
 *
 * @author edisite
 */
class Report extends API_Controller {
    //put your code here
    private $mn_cash_awal   = 0;
    private $mn_cash_akhir  = 0;
    private $mn_cash_adm    = 0;
    private $mn_debt_awal   = 0;
    private $mn_debt_akhir  = 0;
    private $mn_debt_adm    = 0;
    
    private $biaya_adm      = 0;
    
    private $biaya_adm_indo = 0;
    private $biaya_adm_bmt  = 0;
    private $biaya_adm_agen = 0;
    
    private $total_harga_bmt  = 0;
    private $total_harga_jual  = 0;
    private $total_harga_profit, $total_total_komisi  = 0;


    protected $vdownlineid =  array();
    protected $vharga =  array();
    private $com_vhargabmt, $com_vharga_jual, $com_vprofit,$com_vntotal  =  0;
    private $pay_vhargabmt, $pay_vharga_jual, $pay_vprofit,$pay_vntotal  =  0;

    public function __construct() {
        parent::__construct();
        $this->load->model('Mdownline');
    }
    
    public function Com_get() {
        $in_dtm_from        = $this->input->get('dtm_from') ?: '2017-05-16';
        $in_dtm_to          = $this->input->get('dtm_to') ?: '2017-06-16';
        
        $res_inv    = $this->Com_model->Inv_by_dtm($in_dtm_from,$in_dtm_to);        
        $this->response($res_inv);
    }
    
    // laporan downline mobile apps, bmt commerce
    public function GetDownline_total_perday_post() {
        $in_parentid        = $this->input->post('agentid') ?: '';        
        $in_dateday         = $this->input->post('tgl') ?: date('Y-m-d'); 
        $arrsubres[] = array('downline_id' => '','downline_name' => '','total' => '');
        if(empty($in_parentid)){            
            $this->arr_res = array(
                'status'    => FALSE,
                'errorcode' => '601',
                'message'   => 'agentid isempty',
                'data'      => $arrsubres,
                'grand_total'     => "0"
            );
            $this->response($this->arr_res);             
        }
        if(strlen($in_parentid) < 2 || strlen($in_parentid) > 6 || !is_numeric($in_parentid)){
            $this->arr_res = array(
                'status'    => FALSE,
                'errorcode' => '601',
                'message'   => 'agentid isempty',
                'data'      => $arrsubres,
                'grand_total'     => "0"
            );
            $this->response($this->arr_res);
        }      
        
        $subtotal = 0;
        $resdown = $this->Mdownline->Main($in_parentid,$in_dateday);
        if($resdown){
            //$this->response($resdown);
            
            foreach ($resdown as $v) {
                $subtotal += $v['total'];
            }
            
            $this->arr_res = array(
                'status'    => TRUE,
                'errorcode' => '600',
                'message'   => 'sucess',
                'data_list'      => $resdown,
                'grand_total'     => strval($subtotal)
            );
        }else{
            $this->arr_res = array(
                'status'    => FALSE,
                'errorcode' => '602',
                'message'   => 'Tidak ada downline',
                'data'      => $arrsubres,
                'grand_total'     => "0"
            );
        }
        
        $this->response($this->arr_res);          
    }
    public function GetDownline_report_post() {
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        $in_downlineid     = $this->input->post('downlineid') ?: '';        
        $in_dtm_fr         = $this->input->post('date_fr') ?: ''; 
        $in_dtm_to         = $this->input->post('date_to') ?: ''; 
        
        if(empty($in_downlineid)){         
            $arr_res   = array('status' => FALSE,'error_code' => '601','message' => 'agentid isempty','data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => '','komisi' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => '','total_komisi' => ''));       
            
            $this->response($arr_res);             
        }
        if(strlen($in_downlineid) < 2 || strlen($in_downlineid) > 6 || !is_numeric($in_downlineid)){
            $arr_res   = array('status' => FALSE,'error_code' => '601','message' => 'agentid isempty','data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => '','komisi' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => '','total_komisi' => ''));       
            
            $this->response($arr_res);
        } 
        if(empty($in_dtm_fr)){
            $arr_res   = array('status' => FALSE,'error_code' => '602','message' => 'tanggal from isempty','data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => '','komisi' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => '','total_komisi' => ''));       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        if(empty($in_dtm_to)){
            $arr_res   = array('status' => FALSE,'error_code' => '603','message' => 'tanggal to isempty','data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => '','komisi' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => '','total_komisi' => ''));       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        $res_countday = $this->Count_day($in_dtm_to, $in_dtm_fr);   
        $this->logAction('info', $trace_id, array(), 'periode hari : '.$res_countday); 
        if(!$res_countday){
            return;
        }
        if($res_countday <= 0 || $res_countday > 30){            
            $arr_res   = array('status' => FALSE,'error_code' => '606','message' => 'batas maksimal 7 hari','data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => '','komisi'=>''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => '','total_komisi'));       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        //
        
        $get_date_transaksi = $this->Com_model->Comtrans_get_model(array($in_downlineid),$in_dtm_fr,$in_dtm_to);
        $this->logAction('info', $trace_id, array(), 'Com_model->Comtrans_get_model('.json_encode($in_downlineid).','.$in_dtm_fr.','.$in_dtm_to.')');
        $this->logAction('info', $trace_id, array(), json_encode($get_date_transaksi));
        
        if(!$get_date_transaksi):
            $arr_res   = array('status' => FALSE,'error_code' => '607','message' => 'error internal',
                'data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => ''));       

            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        endif;
        
        foreach ($get_date_transaksi as $v) {
            $trx_tgl_trans = $v->TGL_TRANS ?: '';
            $trx_model = $v->MODEL ?: '';
            
            $this->com_vhargabmt        = 0;
            $this->pay_vhargabmt        = 0;
            $this->com_vharga_jual      = 0;
            $this->pay_vharga_jual      = 0;                    
            $this->com_vprofit          = 0;
            $this->pay_vprofit          = 0;
            $this->com_vntotal          = 0;
            $this->pay_vntotal          = 0;
            
            $this->logAction('info', $trace_id, array(), 'TGL = '.$trx_tgl_trans.'- MODEL = '.$trx_model);
            if(strtoupper($trx_model) == "COM"){
                $get_trx_downline = $this->Com_model->Getalltransaksi_downline(array($in_downlineid),$trx_tgl_trans,$trx_tgl_trans);
                if($get_trx_downline){
                    foreach ($get_trx_downline as $v) {
                        //$this->com_vtanggal       =  $v->TGL_TRANS ?: '';
                        $this->com_vhargabmt      =  $v->harga_bmt ?: 0;
                        $this->com_vharga_jual    =  $v->harga_jual ?: 0;
                        $this->com_vprofit        =  $v->profit ?: 0;
                        $this->com_vntotal        =  $v->ntotal ?: ''; 
                    }
                }
                //$this->logAction('info', $trace_id, array(), 'BMT = '.$this->com_vhargabmt.'- JUAL = '.$this->com_vharga_jual.' - PROFIT = '.$this->com_vprofit);
            }
            $this->logAction('info', $trace_id, array(), 'COM - BMT = '.$this->com_vhargabmt.'- JUAL = '.$this->com_vharga_jual.' - PROFIT = '.$this->com_vprofit);
           
            
            //payment
            if(strtoupper($trx_model) == "PAY"){
                $get_trx_downlinep = $this->Com_model->Tgh_mutasi_pay2(array($in_downlineid),$trx_tgl_trans,$trx_tgl_trans);
                if($get_trx_downlinep){
                   foreach ($get_trx_downlinep as $v) {
                        //$this->pay_vtanggal       =  $v->dtm_transaksi ?: '';
                        $this->pay_vhargabmt      =  $v->price_bmt ?: 0;
                        $this->pay_vharga_jual    =  $v->price_akhir ?: 0;
                        $this->pay_vprofit        =  $v->price_agent ?: 0;  
                        $this->pay_vntotal        =  $v->ntotal ?: ''; 
                    }
                }
                
//                var_dump($get_trx_downlinep);
//                return;
            }
            $this->logAction('info', $trace_id, array(), 'PAY - BMT = '.$this->pay_vharga_jual.'- JUAL = '.$this->pay_vhargabmt.' - PROFIT = '.$this->pay_vprofit);
                
            // perhitungan total pay + com
                    $subtot_harga_bmt       = $this->com_vhargabmt + $this->pay_vhargabmt;
                    $subtot_harga_jual      = $this->com_vharga_jual + $this->pay_vharga_jual;
                    $subtot_harga_profit    = $this->com_vprofit + $this->pay_vprofit;
                    $subtot_vntotal         = $this->com_vntotal + $this->pay_vntotal;
                    
                    $this->logAction('info', $trace_id, array(),'TGL'.$trx_tgl_trans.' // SUB TOTAL BMT = '.$subtot_harga_bmt.'- JUAL = '.$subtot_harga_jual.' - PROFIT = '.$subtot_harga_profit);
                    //set manual komisi 100 rupiah
                    $this->vharga[]   = array('tanggal' => $trx_tgl_trans,'harga_bmt' => $this->Rp($subtot_harga_bmt),'harga_jual' => $this->Rp($subtot_harga_jual),'profit' => $this->Rp($subtot_harga_profit),'komisi' => $this->Rp($subtot_vntotal * 100));
                    
                    $this->total_harga_bmt      = $this->total_harga_bmt + $subtot_harga_bmt;
                    $this->total_harga_jual     = $this->total_harga_jual + $subtot_harga_jual;
                    $this->total_harga_profit   = $this->total_harga_profit + $subtot_harga_profit;
                    $this->total_total_komisi   = $this->total_total_komisi + $subtot_vntotal;
                    
                    unset($this->com_vhargabmt);
                    unset($this->pay_vhargabmt);
                    unset($this->com_vharga_jual);
                    unset($this->pay_vharga_jual);                    
                    unset($this->com_vprofit);
                    unset($this->pay_vprofit);        
                    unset($this->com_vntotal);
                    unset($this->pay_vntotal);
        }
        
        $arr_res   = array('status' => TRUE,'error_code' => '600','message' => 'success','data_list' =>$this->vharga,
            'data_total' => array('total_harga_bmt' => $this->Rp($this->total_harga_bmt),'total_harga_jual' => $this->Rp($this->total_harga_jual),'total_profit' => $this->Rp($this->total_harga_profit),'total_komisi' => $this->Rp($this->total_total_komisi * 100)));       

        $this->response($arr_res); 
        //----------------------------
        //--------------------
        /*
        $get_trx_downline = $this->Com_model->Getalltransaksi_downline(array($in_downlineid),$in_dtm_fr,$in_dtm_to);
        if(!$get_trx_downline){
            $arr_res   = array('status' => FALSE,'error_code' => '607','message' => 'error internal',
                'data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => '','komisi' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => '','total_komisi' => ''));       

            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        $this->logAction('info', $trace_id, $get_trx_downline, '');
        foreach ($get_trx_downline as $v) {
            $vtanggal       =  $v->TGL_TRANS ?: '';
            $vhargabmt      =  $v->harga_bmt ?: '';
            $vharga_jual    =  $v->harga_jual ?: '';
            $vprofit        =  $v->profit ?: '';            
            $vntotal        =  $v->ntotal ?: '';            
            
            $this->vharga[]   = array('tanggal' => $vtanggal,'harga_bmt' => $this->Rp($vhargabmt),'harga_jual' => $this->Rp($vharga_jual),'profit' => $this->Rp($vprofit),'komisi' => $this->Rp($vntotal * 100));
            $this->total_harga_bmt      = $this->total_harga_bmt + $vhargabmt;
            $this->total_harga_jual     = $this->total_harga_jual + $vharga_jual;
            $this->total_harga_profit   = $this->total_harga_profit + $vprofit;
            $this->total_total_komisi   = $this->total_total_komisi + $vntotal;
        }
        
        $arr_res   = array('status' => TRUE,'error_code' => '600','message' => 'success','data_list' =>$this->vharga,
            'data_total' => array('total_harga_bmt' => $this->Rp($this->total_harga_bmt),'total_harga_jual' => $this->Rp($this->total_harga_jual),'total_profit' => $this->Rp($this->total_harga_profit),'total_komisi' => $this->Rp($this->total_total_komisi * 100)));       

        $this->response($arr_res);  */
        
        //$this->response($this->arr_res);          
    }
    // menampilkan transaksi yg dilakukan oleh upline dan downlinenya
    public function Transaksi_upline_post() {
        //mutasi data transaksi commerce
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        
        $in_agentid     = $this->input->post('agentid') ?: '';
        $in_dtm_fr      = $this->input->post('date_fr') ?: '';
        $in_dtm_to      = $this->input->post('date_to') ?: '';
        
        //
        if(empty($in_agentid)){            
            $arr_res   = array('status' => FALSE,'error_code' => '601','message' => 'agentid isempty','data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => ''));       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        if(empty($in_dtm_fr)){
            $arr_res   = array('status' => FALSE,'error_code' => '602','message' => 'tanggal from isempty','data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => ''));       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        if(empty($in_dtm_to)){
            $arr_res   = array('status' => FALSE,'error_code' => '603','message' => 'tanggal to isempty','data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => ''));       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        
        $res_countday = $this->Count_day($in_dtm_to, $in_dtm_fr);   
        $this->logAction('info', $trace_id, array(), 'periode hari : '.$res_countday); 
        if(!$res_countday){
            return;
        }
        if($res_countday <= 0 || $res_countday > 30){            
            $arr_res   = array('status' => FALSE,'error_code' => '606','message' => 'batas maksimal 7 hari','data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => ''));       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        
        $res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agentid);
        $this->logAction('info', $trace_id, array(), 'Admin_user2_model->User_by_username ['.$in_agentid.']');
        if($res_call_user_agent){
        }else{
            $arr_res   = array('status' => FALSE,'error_code' => '601','message' => 'agentid isempty',
                'data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => ''));       

            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        //
        
        
        //get list downline
        $get_downline = $this->Mdownline->Upline_by_parentid($in_agentid);
        //$this->response($get_downline);
        
        if($get_downline){
            foreach ($get_downline as $v) {
                $vparentid      =  $v->parent_id ?: '';                                
                $this->vdownlineid[] = $v->downline_id ?: '';
            }
        }else{
            $this->vdownlineid = array($in_agentid);
        }
        $this->logAction('info', $trace_id, $this->vdownlineid, 'Mdownline->Upline_by_parentid('.$in_agentid.')');
        $get_date_transaksi = $this->Com_model->Comtrans_get_model($this->vdownlineid,$in_dtm_fr,$in_dtm_to);
        $this->logAction('info', $trace_id, array(), 'Com_model->Comtrans_get_model('.json_encode($this->vdownlineid).','.$in_dtm_fr.','.$in_dtm_to.')');
        $this->logAction('info', $trace_id, array(), json_encode($get_date_transaksi));
        
        if(!$get_date_transaksi):
            $arr_res   = array('status' => FALSE,'error_code' => '607','message' => 'error internal',
                'data_list' =>array('tanggal' => '','harga_bmt' => '','harga_jual' => '','profit' => ''),
                'data_total' => array('total_harga_bmt' => '','total_harga_jual' => '','total_profit' => ''));       

            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        endif;
//        $this->response($get_date_transaksi);
//        return;
        
        foreach ($get_date_transaksi as $v) {
            $trx_tgl_trans = $v->TGL_TRANS ?: '';
            $trx_model = $v->MODEL ?: '';
            
            $this->com_vhargabmt        = 0;
            $this->pay_vhargabmt        = 0;
            $this->com_vharga_jual      = 0;
            $this->pay_vharga_jual      = 0;                    
            $this->com_vprofit          = 0;
            $this->pay_vprofit          = 0;
            
            $this->logAction('info', $trace_id, array(), 'TGL = '.$trx_tgl_trans.'- MODEL = '.$trx_model);
            if(strtoupper($trx_model) == "COM"){
                $get_trx_downline = $this->Com_model->Getalltransaksi_downline($this->vdownlineid,$trx_tgl_trans,$trx_tgl_trans);
                if($get_trx_downline){
                    foreach ($get_trx_downline as $v) {
                        //$this->com_vtanggal       =  $v->TGL_TRANS ?: '';
                        $this->com_vhargabmt      =  $v->harga_bmt ?: 0;
                        $this->com_vharga_jual    =  $v->harga_jual ?: 0;
                        $this->com_vprofit        =  $v->profit ?: 0;           
                    }
                }
                //$this->logAction('info', $trace_id, array(), 'BMT = '.$this->com_vhargabmt.'- JUAL = '.$this->com_vharga_jual.' - PROFIT = '.$this->com_vprofit);
            }
            $this->logAction('info', $trace_id, array(), 'COM - BMT = '.$this->com_vhargabmt.'- JUAL = '.$this->com_vharga_jual.' - PROFIT = '.$this->com_vprofit);
           
            
            //payment
            if(strtoupper($trx_model) == "PAY"){
                $get_trx_downlinep = $this->Com_model->Tgh_mutasi_pay2($this->vdownlineid,$trx_tgl_trans,$trx_tgl_trans);
                if($get_trx_downlinep){
                   foreach ($get_trx_downlinep as $v) {
                        //$this->pay_vtanggal       =  $v->dtm_transaksi ?: '';
                        $this->pay_vhargabmt      =  $v->price_bmt ?: 0;
                        $this->pay_vharga_jual    =  $v->price_akhir ?: 0;
                        $this->pay_vprofit        =  $v->price_agent ?: 0;            
                    }
                }
                
//                var_dump($get_trx_downlinep);
//                return;
            }
            $this->logAction('info', $trace_id, array(), 'PAY - BMT = '.$this->pay_vharga_jual.'- JUAL = '.$this->pay_vhargabmt.' - PROFIT = '.$this->pay_vprofit);
                
            // perhitungan total pay + com
                    $subtot_harga_bmt       = $this->com_vhargabmt + $this->pay_vhargabmt;
                    $subtot_harga_jual      = $this->com_vharga_jual + $this->pay_vharga_jual;
                    $subtot_harga_profit    = $this->com_vprofit + $this->pay_vprofit;
                    
                    $this->logAction('info', $trace_id, array(),'TGL'.$trx_tgl_trans.' // SUB TOTAL BMT = '.$subtot_harga_bmt.'- JUAL = '.$subtot_harga_jual.' - PROFIT = '.$subtot_harga_profit);
                
                    $this->vharga[]   = array('tanggal' => $trx_tgl_trans,'harga_bmt' => $this->Rp($subtot_harga_bmt),'harga_jual' => $this->Rp($subtot_harga_jual),'profit' => $this->Rp($subtot_harga_profit));
                    $this->total_harga_bmt      = $this->total_harga_bmt + $subtot_harga_bmt;
                    $this->total_harga_jual     = $this->total_harga_jual + $subtot_harga_jual;
                    $this->total_harga_profit   = $this->total_harga_profit + $subtot_harga_profit;
                    
                    unset($this->com_vhargabmt);
                    unset($this->pay_vhargabmt);
                    unset($this->com_vharga_jual);
                    unset($this->pay_vharga_jual);                    
                    unset($this->com_vprofit);
                    unset($this->pay_vprofit);
        }
        
        $this->logAction('info', $trace_id, array(),'TGL'.$trx_tgl_trans.' // TOTAL BMT = '.$this->total_harga_bmt.'- JUAL = '.$this->total_harga_jual.' - PROFIT = '.$this->total_harga_profit);
                
        $arr_res   = array('status' => TRUE,'error_code' => '600','message' => 'success','data_list' =>$this->vharga,
            'data_total' => array('total_harga_bmt' => $this->Rp($this->total_harga_bmt),'total_harga_jual' => $this->Rp($this->total_harga_jual),'total_profit' => $this->Rp($this->total_harga_profit)));       
        $this->logAction('response', $trace_id, $arr_res, 'OK');
        
        $this->response($arr_res);        
    }
        function Rp($value = null)    {
        return number_format($value,0,",",".");
    }
    public function Downline_list_post($in_agentid = '') {
        $trace_id = $this->logid();        
        $this->logheader($trace_id);
        $arr_err = array();
        $arr_err[] = array('downline_id'=> '','downline_name' => '');
        $in_agentid = $this->input->post('agentid') ?: '';
        if(empty($in_agentid)){            
            $arr_res   = array('status' => FALSE,'error_code' => '601','message' => 'agentid is empty','data_list' =>$arr_err); 
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->Logend($trace_id);
            $this->response($arr_res);
        }
        $this->logAction('info', $trace_id, array(), 'Mdownline->Main_by_parent('.$in_agentid.')');
        $get_downline = $this->Mdownline->Main_by_parent($in_agentid);
        if($get_downline):
            $this->logAction('info', $trace_id, $get_downline, 'respon ok :');
           $arr_res   = array('status' => TRUE,'error_code' => '600','message' => 'success','data_list' =>$get_downline);       
        else:
            $this->logAction('info', $trace_id, array(), 'error :'.$get_downline);
           $arr_res   = array('status' => FALSE,'error_code' => '602','message' => 'Downline kosong','data_list' =>$arr_err);       
        endif;
        $this->logAction('response', $trace_id, $arr_res, '');       
        $this->Logend($trace_id);
        $this->response($arr_res);  
    }

}
