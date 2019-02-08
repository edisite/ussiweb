<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Api_model
 *
 * @author edisite
 */
class Kunci_model extends CI_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Find_key($in_ipaddr,$in_keyid) {
        //$sql = "SELECT TIMESTAMPDIFF(MINUTE,last,NOW()) as waktu,agent_id FROM api_kunci WHERE ip_remote='".$in_ipaddr."' AND id='".$in_keyid."'";
        $sql = "SELECT TIMESTAMPDIFF(MINUTE,last,NOW()) as waktu,agent_id FROM api_kunci WHERE id=".  $this->db->escape($in_keyid);
        $query = $this->db->query($sql)->result();
        return $query;
        
    }
    public function Upd_key($keyid) {
//        $SQL_Insert_Key = "UPDATE api_kunci SET last = NOW() WHERE id='".$keyid."'";
//        $query = $this->db->query($SQL_Insert_Key)->result();
//        
        //$this->db->set('last',date('Y-m-d H:i:s'));
        $this->db->set('last','now()',false);
        $this->db->where('id',$keyid);
        $query = $this->db->update('api_kunci');
        return $query;
    }
    public function Del_key($ipremote,$keyid) {
        $query = $this->db->delete('api_kunci', array('id' => $keyid,'ip_remote' => $ipremote)); 
        return $query;
    }
    public function Del_by_agent($agent = '') {
        if(empty($agent)){
            return false;
        }
        $query = $this->db->delete('api_kunci', array('agent_id' => $agent)); 
        return $query;
    }
    
    
}
