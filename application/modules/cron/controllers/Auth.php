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
                $this->logAction('response', $traceid, $res, 'Failed');
                $this->response($res);
                return FALSE;
        }
        /*$this->load->model('ion_auth_model');
        $resdata = $this->ion_auth_model->login_api($username,$uniqcode,false);
        if($resdata){
                $res = array(
                    'status' => 'OK',
                    'msg'   => 'successful[username='.$username.' passwd='.$uniqcode,
                );
        }  else {
            $res = array(
                    'status' => 'NOK',
                    'msg'   => 'unsuccessful',
                );
        }*/
        
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
            $res = array(
                    'status'        => 'OK',
                    'msg'           => 'succesfully',
                    'agentid'       => $ou_agentid,
                    'username'      => $ou_username,
                    'name'          => $ou_first.' '.$ou_last,
                    'nasabahid'     => $ou_nasabahid,
                    'key_session'   => $g_key,
                );
            $this->logAction('response', $traceid, $res, 'OK');
        }else{
            $res = array(
                    'status' => 'NOK',
                    'msg'   => 'unsuccessful',
                );
            $this->logAction('response', $traceid, $res, 'Failed');
        }
        $this->response($res);
    }
    protected function _cret_key($in_agentid) {
        $gen_rand_id = $this->_rand_id();
        $arr_inst   = array(
            'id'             => $gen_rand_id,
            'agent_id'       => $in_agentid,
            'ip_remote'      => $this->input->ip_address(),
        );
        
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
}

