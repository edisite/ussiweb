<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User2_model
 *
 * @author edisite
 */
class User2_model extends MY_Model {
    //put your code here
    function Cek_by_id($in_userid) {
        $this->id = $in_userid;
        $this->db->select('id');
        $this->db->where('id',  $this->id);
        $this->db->where('active',  '1');
        $this->db->limit('1');
        $query = $this->db->get('users');
        return $query->result();
    }
    public function Cek($in_userid) {
        $res_status_userid = $this->Cek_by_id($in_userid);
        if($res_status_userid){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
