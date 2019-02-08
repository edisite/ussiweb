<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Css_model
 *
 * @author edisite
 */
class Css_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Kodegroup1() {
        $sql = "select kode_group1 as kode,deskripsi_group1 as deskripsi from css_kode_group1 order by kode_group1";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Kodegroup2() {
        $sql = "select kode_group2 as kode,deskripsi_group2 as deskripsi from css_kode_group2 order by kode_group2";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Kodegroup3() {
        $sql = "select kode_group3 as kode,deskripsi_group3 as deskripsi from css_kode_group3 order by kode_group3";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Kodeagama() {
        $sql = "select * from CSS_KODE_AGAMA";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Jenisid() {
        $sql = "select * from css_kode_jenis_identitas";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Kodedati() {
        $sql = "select * from  CSS_KODE_DATI";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Kodepropvinsi() {
        $sql = "select * from  CSS_KODE_PROPVINSI";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Kodejurnal() {
        $sql = "SELECT KODE_JURNAL,DESKRIPSI_JURNAL FROM acc_kode_jurnal WHERE TYPE_JURNAL = 'SYS'";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
}
