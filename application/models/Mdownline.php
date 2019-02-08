<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mdownline
 *
 * @author edisite
 */
class Mdownline extends MY_Model {
    //put your code here
    private $vparentid,$vdownlineid;
    
    private $dataarr = array();
    public function __construct() {
        parent::__construct();
    }
    function Upline_by_parentid($in_parentid = '') {
        $this->db->select('parent_id,downline_id');
        $this->db->where('parent_id',$in_parentid);
        $this->db->from('com_downline');
        $query = $this->db->get()->result();
        return $query;
    }
    public function Main($in_parentid, $periode = '') {
        $getupline = $this->Upline_by_parentid($in_parentid);
        if($getupline){
            foreach ($getupline as $v) {
                $vparentid      =  $v->parent_id ?: '';
                $vdownlineid    =  $v->downline_id ?: '';
                
                $this->dataarr[] = array('downline_id' => $vdownlineid,'downline_name' => $this->Admin_user2_model->getname($vdownlineid),'total' => strval($this->Com_model->Get_total_transaksi($vdownlineid,$periode)));
            } 
        }else{
            return false;
        }
        return $this->dataarr;
    }
    public function Main_by_parent($in_parentid) {
        $getupline = $this->Upline_by_parentid($in_parentid);
        if($getupline){
            foreach ($getupline as $v) {
                $vparentid      =  $v->parent_id ?: '';
                $vdownlineid    =  $v->downline_id ?: '';
                
                $this->dataarr[] = array('downline_id' => $vdownlineid,'downline_name' => $this->Admin_user2_model->getname($vdownlineid));
            } 
        }else{
            return false;
        }
        return $this->dataarr;
    }
    public function Master_upline() {
        $sql = "SELECT DISTINCT com_downline.parent_id, admin_users.username, LOWER(concat(admin_users.first_name,' ',admin_users.last_name)) as full_name,count(*) as total FROM `com_downline` JOIN `admin_users` ON `admin_users`.`id` = `com_downline`.`parent_id` group by com_downline.parent_id";
        $query = $this->db->query($sql);
        return $query->result();
    }
    //get data admin user yang tidak terdaftar sebagai upline downline
    //digunakan untuk daftar upline downline baru
    //web
    public function Listupline() {
        $sql = "SELECT com_downline.parent_id, admin_users.id, admin_users.username, 
LOWER(CONCAT(admin_users.first_name,' ',admin_users.last_name)) AS full_name,IF(com_downline.parent_id IS NULL,'Tidak Terdaftar','Terdaftar') AS STATUS
FROM `com_downline` RIGHT JOIN `admin_users` ON `admin_users`.`id` = `com_downline`.`downline_id`";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Delupline($uid = '') {               
        $query = $this->db->delete('com_downline', array('parent_id' => $uid)); 
        return $query;                    
    
    }
}
