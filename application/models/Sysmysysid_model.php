<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sysmysysid_model
 *
 * @author edisite
 */
class Sysmysysid_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Pim1() {
        $sql = "select KEYVALUE from sys_mysysid where KeyName='NAMA_PIMPINAN1'";
        $query = $this->db->query($sql);
        return $query->result();  
    }
    public function Key_by_keyname($name) {
        $sql = "select KEYVALUE from sys_mysysid where KeyName='".$name."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
}
