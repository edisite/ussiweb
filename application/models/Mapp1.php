<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mapp
 *
 * @author edisite
 */
class Mapp extends CI_Model {
    //put your code here
    function __construct() {
        parent::__construct();
    }
    public function get_cpid($idcp = 'default') {
        $this->db->select('cpid, cpname, dbhost, dbuser, dbpass, dbname, dbconn');
        $this->db->from('handle_apps');
        $this->db->where('cpid',$idcp);
        $this->db->limit(1);
        $qry = $this->db->get();
        return $qry;
    }
    
}
