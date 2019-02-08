<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Lkm
 *
 * @author edisite
 */
class Lkm_model extends MY_Model {
    //put your code here
    public function Simpanan_grouping() {
        $this->db->select('*');
        $this->db->order_by('ID');
        $query = $this->db->get('lkm_simpanan_dikelompokan');
        return $query->result();  
        
    }
}
