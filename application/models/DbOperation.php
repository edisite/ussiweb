<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbOperation
 *
 * @author edisite
 */
class DbOperation extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function RegisterDevice($email,$token){
        if(empty($email) || empty($token)){
            return false;
        }
        $cek_device = $this->Check_devices($email);
        if($cek_device){
            return 2;
        }
        $arr = array(
            'email' => $email,
            'token' => $token
            );        
        $get_res = $this->Ins_Tbl('devices', $arr);
        if($get_res){
            return 0;
        }else{
            return 1;
        }
    }
    private function Ins_Tbl($table,$data = array()) {        
        $query = $this->db->insert($table, $data);
        return $query;        
    }
  
    public function Check_devices($email = '') {
        $this->db->select('*');
        $this->db->where('email',$email);
        $query = $this->db->get('devices');
        return $query->result();
    }
    public function RemoveDevice($email = '') {
        if(empty($email)){
            return false;
        }
        $cek_device = $this->Check_devices($email);
        if($cek_device){            
            $res_device = $this->Del_device($email);
            if($res_device){
                ////return 0 means success
                return 0;
            }else{
                return 1;
            }
        }else{
            return 2;
        }        
    }
    function Del_device($email = '') {                
        $query = $this->db->delete('devices', array('email' => $email)); 
        return $query;                    
    }

    
}
