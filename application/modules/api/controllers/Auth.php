<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Users
 *
 * @author edisite
 */
class Auth extends API_Controller{
    //put your code here
    
    protected $areslogin_nasabah =[
                    'm_userid'      => '',
                    'username'      => '',
                    'name'          => '',
                    'nasabahid'     => '',
                    'key_session'   => '',
                    'accesspin'     => '',
                    'no_rekening'   => ''
    ];
    public function __construct() {
        parent::__construct();         
    }
    public function Cek_post() {
        $traceid    = $this->logid();
        $this->logheader($traceid);
	$username      = $this->input->post('identity');
        $uniqcode      = $this->input->post('password');             

        if (empty($username) || empty($uniqcode))
        {
                $res = array(
                    'status' => 'NOK',
                    'msg'   => 'unsuccessful',
                );
                //$this->logAction('response',$res);
                $this->logAction('response', $traceid, array(), 'Failed, username & password (empty)');
                $this->logAction('response', $traceid, $res, '');
                $this->response($res);
        }
        
        $this->load->model('Api_ion_auth');
        $resdata = $this->Api_ion_auth->login_api($username,$uniqcode,false);
        if($resdata){  
            $this->logAction('response', $traceid, array(), 'OK >>username & password (match)');
        }  else {
            $res = array(
                    'status' => 'NOK',
                    'msg'   => 'unsuccessful'
                );
            $this->logAction('result', $traceid, array(), 'Failed >> username & password (not match)');
            $this->logAction('response', $traceid, $res, '');
            $this->response($res);
        }
        
        $res_admin  = $this->Admin_user2_model->User_by_username($username);
        if($res_admin){
            foreach ($res_admin as $val) {
                $ou_agentid     = $val->id;
                $ou_username    = $val->username;
                $ou_first       = $val->first_name;
                $ou_last        = $val->last_name;
                $ou_nasabahid   = $val->nasabah_id;
                $ou_rekid       = $val->no_rekening;
                $ou_status      = $val->active;
            }
        }else{
            $res = array(
                    'status' => 'NOK',
                    'msg'   => 'user not registered',
                );
            $this->logAction('response', $traceid, $res, 'Failed');
            $this->response($res);
        }
        $res_key = $this->_cret_key($ou_agentid);
        if($res_key){
            $parsing_json = json_decode($res_key);
            $g_status   = $parsing_json->status;
            $g_message  = $parsing_json->message;
            $g_key      = $parsing_json->key;            
        }
        else{
            $res = array(
                    'status' => 'NOK',
                    'msg'   => 'Problem internal,[session keyid]',
                );
                $this->logAction('response', $traceid, $res, 'Failed');
                $this->response($res);
        }
        if($g_status == "NOK"){
            $res = array(
                    'status' => 'NOK',
                    'msg'   => 'Problem internal,[session keyid]',
                );
                $this->logAction('response', $traceid, $res, 'Failed');
                $this->response($res);
        }  
        
        if(trim($ou_status) == 0){
            $res = array(
                    'status' => 'NOK',
                    'msg'   => 'user inactive',
                );
        }
        if(trim($ou_status) == 1){
            $this->load->model('Sys_daftar_user_menu_model');
            $datamenu = $this->Sys_daftar_user_menu_model->menu_prompt_mobile($ou_username);
            $arr_priv = array();
            if($datamenu){
                foreach ($datamenu as $val) {
                    $opt_menuid = $val->menu_id;
                    $opt_msg    = $val->menu_prompt;
                    $arr_priv[]   = array(
                        'menu_id'    => $opt_menuid,
                        'menu_desc'  => $opt_msg
                    );
                }                
            }
            $res = array(
                    'status'        => 'OK',
                    'msg'           => 'succesfully',
                    'agentid'       => $ou_agentid,
                    'username'      => $ou_username,
                    'name'          => $ou_first.' '.$ou_last,
                    'nasabahid'     => $ou_nasabahid,
                    'rekening'      => $ou_rekid,
                    'key_session'   => $g_key,
                    'priv_menu'     => $arr_priv
                );
            $this->logAction('response', $traceid, $res, 'OK');
        }else{
            $res = array(
                    'status' => 'NOK',
                    'msg'   => 'unsuccessful',
                );
            $this->logAction('response', $traceid, $res, 'Failed');
        }
        $this->load->config('tambahan');
        $is_inserted = array(                
                'uri' => $this->uri->uri_string(),
                'method' => $this->request->method,
                'params' => $this->_args ? ($this->config->item('rest_logs_json_params') === TRUE ? json_encode($this->_args) : serialize($this->_args)) : NULL,
                'api_key' => isset($this->rest->key) ? $this->rest->key : '',
                'ip_address' => $this->input->ip_address(),
                'time' => date('YmdHis'),                   
                'maps_lat' => $this->input->post('lat') ?: '',                   
                'maps_lng' => $this->input->post('lng') ?: '',                   
                'userid' => $ou_agentid  ?: '',                   
                'uname' => $ou_username ?: '',                                 
            );
        $this->logAction('info', $traceid, $is_inserted, 'maps');
        $this->Tab_model->Ins_tbl($this->config->item('t_maps'),$is_inserted);
        $this->response($res);
    }
    protected function _cret_key($in_agentid) {
        $gen_rand_id = $this->_rand_id();
        $arr_inst   = array(
            'id'             => $gen_rand_id,
            'agent_id'       => $in_agentid,
            'ip_remote'      => $this->input->ip_address(),
        );
        $this->Kunci_model->Del_by_agent($in_agentid ?: '');
        if($this->Tab_model->Ins_Tbl('api_kunci',$arr_inst)){
            $arrayName = array('status' =>'OK' ,
                        'message'=>'Success',
                        'key'=>$gen_rand_id,
                         );
        }else{
            $arrayName = array('status' =>'NOK' ,
                        'message'=>'No succesfully',
                        'key'=>'',
                         );
        }
        return json_encode($arrayName);       
    }
    protected function _rand_id() {       
        $in_random = random_string('alnum', 20); 
        if(empty($in_random)){
            $in_random = random_string('alnum', 20);
        }
        return $in_random;
    }
    public function Last_status_get() {
        if((APISESSION == TRUE)){                                    
                    
                $key_session    = $this->input->get_request_header('key_ses');
                $ip_addrs       = $this->input->ip_address();
                $find_key = $this->Kunci_model->Find_key($ip_addrs,$key_session);                        
                if(!$find_key){                             
                    $res = array(
                        'status' => 'NOK'                                
                    );
                    $this->response($res);
                }
                foreach($find_key as $val){
                    $waktu = $val->waktu;
                }

                if($waktu >= APIINTERVAL){
                    $this->Kunci_model->Del_key($ip_addrs,$key_session);                             
                    $res = array(
                        'status' => 'NOK'                                
                    );
                    $this->response($res);
                }
                $res = array(
                    'status' => 'OK'                                
                );
                $this->response($res);
        }
    }
    public function Apptypemodel_post() {        
        //cek model user untuk donwload jenis mobile apps
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_msisdn = $this->input->post('msisdn') ? $this->input->post('msisdn') : '';  // post data
        if(empty($in_msisdn)){
            $arr = array('status' => 'NOK','typeuser' => '','message' => 'missing parameter');
            //echo json_encode($arr);
            $this->response($arr);
            return;
        }
        $get_res = $this->Admin_user2_model->Model_user($in_msisdn);
        if(!$get_res){
            $arr = array('status' => 'NOK','typeuser' => '','message' => 'missing msisdn');
            $this->response($arr);
            return;
        }
        $modeluser = '';
        foreach ($get_res as $v) {
            $modeluser = $v->model_agent ?: '';
        }
        if(empty($modeluser)){
            $arr = array('status' => 'NOK','typeuser' => '','message' => 'missing msisdn');
            $this->response($arr);
            return;
        }
        $arr = array('status' => 'OK','typeuser' => $modeluser,'message' => '');
        $this->response($arr);
    }
    public function Login_nasabah_post() {
        $traceid    = $this->logid();
        $this->logheader($traceid);
	$username      = $this->input->post('identity_nasabah') ?: '';
        $uniqcode      = $this->input->post('passcode') ?: '';  
        
        if(empty($username) || strlen($username) >= 20){
            $res = array(
                    'status' => '101',
                    'msg'   => 'username failed',
                );
            $res = array_merge($res, $this->areslogin_nasabah);
            $this->logAction('response', $traceid, array(), 'Failed, username & password (empty)');
            $this->logAction('response', $traceid, $res, '');
            $this->response($res);
        }
        if(empty($uniqcode) || strlen($uniqcode) >= 20){
            $res = array(
                    'status' => '102',
                    'msg'   => 'password failed',
                );
            $res = array_merge($res, $this->areslogin_nasabah);
            $this->logAction('response', $traceid, array(), 'Failed, username & password (empty)');
            $this->logAction('response', $traceid, $res, '');
            $this->response($res);
        }
        
        $this->load->model('Api_ion_auth_nasabah');
        $resdata = $this->Api_ion_auth_nasabah->login_api($username,$uniqcode,false);        
        
        if($resdata){  
            $this->logAction('response', $traceid, array(), 'OK >>username & password (match)');
        }  else {
            $res = array(
                    'status' => '103',
                    'msg'   => 'failed, username and password not matching'
                );
            $res = array_merge($res, $this->areslogin_nasabah);
            $this->logAction('result', $traceid, array(), 'Failed >> username & password (not match)');
            $this->logAction('response', $traceid, $res, '');
            $this->response($res);
        }
        
        $res_admin  = $this->Admin_user2_model->User_by_username_nasabah($username);
        if($res_admin){
            foreach ($res_admin as $val) {
                $ou_agentid     = $val->id;
                $ou_username    = $val->username;
                $ou_first       = $val->first_name;
                $ou_last        = $val->last_name;
                $ou_nasabahid   = $val->nasabah_id;
                //$ou_rekid       = $val->no_rekening;
                $ou_status      = $val->active;
                $ou_pin      = $val->pin_payment;
            }
        }else{
            $res = array(
                    'status' => '104',
                    'msg'   => 'username not registered',
                );
            $res = array_merge($res, $this->areslogin_nasabah);
            $this->logAction('response', $traceid, $res, 'Failed');
            $this->response($res);
        }
         
        $res_key = $this->_cret_key($ou_agentid);
        if($res_key){
            $parsing_json = json_decode($res_key);
            $g_status   = $parsing_json->status;
            $g_message  = $parsing_json->message;
            $g_key      = $parsing_json->key;            
        }
        else{
            $res = array(
                    'status' => '105',
                    'msg'   => 'Error internal',
                );
            $res = array_merge($res, $this->areslogin_nasabah);
            $this->logAction('response', $traceid, $res, 'Failed');
            $this->response($res);
        }
        if($g_status == "NOK"){
            $res = array(
                    'status' => '105',
                    'msg'   => 'Error internal',
                );
            $res = array_merge($res, $this->areslogin_nasabah);
            $this->logAction('response', $traceid, $res, 'Failed');
            $this->response($res);
        }        
        
        if(trim($ou_status) == 0){
            $res = array(
                    'status' => '106',
                    'msg'   => 'user inactive',
                );
            $res = array_merge($res, $this->areslogin_nasabah);
        }
        $res_data_rekening = $this->Tab_model->Rek_by_nasabahid($ou_nasabahid);
        if($res_data_rekening){            
            foreach ($res_data_rekening as $vrek) {
                $rrekening = $vrek->NO_REKENING; 
            }
        if(trim($ou_status) == 1){
            
            $res = array(
                    'status'        => '100',
                    'msg'           => 'succesfully',
                    'm_userid'      => $ou_agentid,
                    'username'      => $ou_username,
                    'name'          => $ou_first.' '.$ou_last,
                    'nasabahid'     => $ou_nasabahid,
                    'key_session'   => $g_key,
                    'accesspin'     => $ou_pin,
                    'no_rekening'   => $rrekening
                    //'priv_menu'     => $arr_priv
                );
            $this->logAction('response', $traceid, $res, 'OK');           
            
        }else{
            $res = array(
                    'status' => '106',
                    'msg'   => 'user inactive',
                );
            $res = array_merge($res, $this->areslogin_nasabah);
            $this->logAction('response', $traceid, $res, 'Failed');
        }
        $this->load->config('tambahan');
        $is_inserted = array(                
                'uri'           => $this->uri->uri_string(),
                'method'        => $this->request->method,
                'params'        => $this->_args ? ($this->config->item('rest_logs_json_params') === TRUE ? json_encode($this->_args) : serialize($this->_args)) : NULL,
                'api_key'       => isset($this->rest->key) ? $this->rest->key : '',
                'ip_address'    => $this->input->ip_address(),
                'time'          => date('YmdHis'),                   
                'maps_lat'      => $this->input->post('lat') ?: '',                   
                'maps_lng'      => $this->input->post('lng') ?: '',                   
                'userid'        => $ou_agentid  ?: '',                   
                'uname'         => $ou_username ?: '',                  
                
            );
            $this->logAction('info', $traceid, $is_inserted, 'maps');
            $this->Tab_model->Ins_tbl($this->config->item('t_maps'),$is_inserted);
        $this->response($res);
    }
    }

}

