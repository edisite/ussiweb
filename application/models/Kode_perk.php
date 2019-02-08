<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kode_perk
 *
 * @author edisite
 */
class Kode_perk extends MY_Model {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Kas() {
//         $this->db->select('select kode_perk, nama_perk from perkiraan WHERE nama_perk like "%kas%" and left(kode_perk,1)="1" order by kode_perk');
//        $query = $this->db->get();
//        return $query->result();
        
         $sql = "select kode_perk, nama_perk "
                 . "from perkiraan "
                 . "WHERE nama_perk like '%kas%' "
                 . "and left(kode_perk,1)='1' "
                 . "order by kode_perk";           
        $query = $this->db->query($sql)->result();
        return $query; 
    }
    public function Item_perk() {        
        $this->db->select('NAMA_PERK,G_OR_D');
        $this->db->where('KODE_PERK','10101');
        $query = $this->db->get('perkiraaan');
        return $query->result();        
    }
}
