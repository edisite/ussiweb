<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Perk_model
 *
 * @author edisite
 */
class Perk_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function perk_by_kodeperk($in_kode = null) {
        $this->db->select('NAMA_PERK, G_OR_D');
        $this->db->where('KODE_PERK',$in_kode);
        $query = $this->db->get('PERKIRAAN')->result();
        return $query;
    }
    public function perk_by_kodeperk_gord($in_kode = null,$in_gord = null) {
        $this->db->select('count(*) as total');
        $this->db->where('KODE_PERK',$in_kode);
        $this->db->where('G_OR_D',$in_gord);
        $query = $this->db->get('PERKIRAAN')->result();
        return $query;
    }
    public function perk() {
        $this->db->select('KODE_PERK,NAMA_PERK, G_OR_D, KODE_INDUK, LEVEL_PERK, TYPE_PERK,SALDO_AKHIR');
        $this->db->order_by('KODE_PERK','ASC');
        //$this->db->order_by('KODE_PERK');
        $query = $this->db->get('PERKIRAAN')->result();
        return $query;
    }
}
