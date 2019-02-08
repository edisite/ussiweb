<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of App_kode_kantor_model
 *
 * @author edisite
 */
class App_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    //put your code here
    function Kode_kantor() {
        $this->db->select('KODE_KANTOR,NAMA_KANTOR');
        $this->db->order_by('kode_kantor');
        $query = $this->db->get('app_kode_kantor');
        return $query->result(); 
    }
    public function Generate_id() {
       $sql = "SELECT get_next_id() as ID";           
        $query = $this->db->query($sql)->result();
        return $query; 
    }
    public function Gen_id() {
        $resid = $this->Generate_id();
        foreach($resid as $subid){
            $data = $subid->ID;
        }        
        return $data;
    }
    public function Gen_id_model() {
        $resid = $this->Generate_id();
        foreach($resid as $subid){
            $data = $subid->ID;
        }        
        return $data;
    }
    public function Device_del() {
        $sql = "DELETE FROM devices WHERE dtm + INTERVAL 3 HOUR < NOW()";
        $query = $this->db->query($sql);
        return $query;
    }
    public function TrfErrMsg($traceid = '', $errlog = '', $errmsg = '', $task = '', $api = '') {
        $data = array(
            'dtm' => date('Y-m-d H:i:s'),
            'traceid' => $traceid,
            'errorlog' => $errmsg,
            'msg' => $errmsg,
            'task' => $task,
            'api' => $api
        );
        return $this->db->insert('transfer_errorlog', $data);
    }
    
}
