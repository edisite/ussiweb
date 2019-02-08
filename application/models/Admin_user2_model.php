<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin_user2_model
 *
 * @author edisite
 */
class Admin_user2_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
        
    }
    public function Uname() {
        $this->db->select('id,username,first_name,last_name');
        $query = $this->db->get('admin_users');
        return $query->result();
    }
     public function Id($id) {
        $this->id = $id;
        $this->db->select('id,username,first_name,last_name');
        $this->db->where('id',  $this->id);
        $this->db->or_where('username',  $this->id);
        $this->db->limit('1');
        $query = $this->db->get('admin_users');
        return $query->result();
    }
    public function Usrjoin() {
        
        $this->db->select('admin_users.id,admin_users.username,sys_daftar_user.user_id,sys_daftar_user.user_name,sys_daftar_user.nama_lengkap, sys_daftar_user.kode_perk_kas');
        $this->db->where('sys_daftar_user.user_name<>"ADMIN"');
        $this->db->join('admin_users','admin_users.username=sys_daftar_user.user_name');
        $query = $this->db->get('sys_daftar_user');
        return $query->result();        
    }
    public function Usergroup() {        
        $sql = "SELECT a.id, lower(a.username) as username FROM `admin_users` a, admin_users_groups b  WHERE a.id=b.user_id and b.group_id >1";
        $query = $this->db->query($sql);
        return $query->result();        
    }
    public function Agent_group() {        
        $sql = "SELECT 	admin_users.id AS uid,admin_users.username,CONCAT(admin_users.first_name,' ',admin_users.last_name) AS nama,admin_groups.description,
admin_groups.id	AS gid 	FROM sejahtera.admin_groups,sejahtera.admin_users,sejahtera.admin_users_groups 	
	WHERE admin_users_groups.group_id = admin_groups.id AND admin_users_groups.user_id= admin_users.id  AND admin_groups.id > 2	
	ORDER BY admin_groups.id ASC";
        $query = $this->db->query($sql);
        return $query->result();        
    }
    public function Agent_by_group_tabung($username = '') {        
        $sql = "SELECT admin_users.NO_REKENING,admin_users.NASABAH_ID,SALDO_AKHIR,CONCAT(admin_users.first_name,' ',admin_users.last_name) AS nama,username,id AS userid,active,IF(active = 1,'ACTIVE','INACTIVE') AS STATUS FROM tabung, admin_users WHERE tabung.NASABAH_ID = admin_users.nasabah_id AND tabung.NO_REKENING = admin_users.no_rekening AND admin_users.id='".$username."'";
        $query = $this->db->query($sql);
        return $query->result();        
    }
    public function User_by_username($in_username) {
        $this->id = $in_username;
        $this->db->select('id,username,first_name,last_name,nasabah_id,no_rekening,active');
        $this->db->where('username',  $this->id);
        $this->db->or_where('id',  $this->id);
        //$this->db->where('active',  '1');
        $this->db->limit('1');
        $query = $this->db->get('admin_users');
        return $query->result();
    }
    public function User_by_msisdn_pin($msisdn = '',$pin = '0000') {

        $this->db->select('id,username,first_name,last_name,nasabah_id,no_rekening,active');
        $this->db->where('pin_payment',  $pin);
        $this->db->where('msisdn_payment',  $msisdn);
        //$this->db->where('active',  '1');
        $this->db->limit('1');
        $query = $this->db->get('admin_users');
        return $query->result();
    }
    public function User_by_id($in_userid) {
        if(empty($in_userid)){
            return FALSE;
        }
        if($this->Id($in_userid)){
            return TRUE;
        }
        return FALSE;
    }
    public function Pin_pay($in_agent_id = '',$in_kode_pin = '') {
        $this->id = $in_agent_id;
        $this->db->select('pin_payment');
        $this->db->where('id',  $this->id);
        $this->db->where('pin_payment',  $in_kode_pin);
        $this->db->limit('1');
        $query = $this->db->get('admin_users')->result();
        if($query)  {         return true;        }
        else    {           return FALSE;          }
    }
    public function Model_user($in_msisdn = '') {
        if(empty($in_msisdn)){
            return false;
        }
        $this->db->select('model_agent');
        $this->db->where('msisdn_payment',  $in_msisdn);
        $this->db->where('active',  '1');
        $this->db->limit('1');
        $query = $this->db->get('admin_users')->result();
        return $query;
    }
     public function User_by_username_nasabah($in_username) {
        $this->id = $in_username;
        $this->db->select('id,username,first_name,last_name,nasabah_id,active,pin_payment');
        $this->db->where('username', $this->id);
        $this->db->or_where('id', $this->id);
        //$this->db->where('active',  '1');
        $this->db->limit(1);
        $query = $this->db->get('users')->result();
        return $query;
    }
    public function Pin_pay_nasabah($in_agent_id = '',$in_kode_pin = '') {
        $this->id = $in_agent_id;
        $this->db->select('pin_payment');
        $this->db->where('id',  $this->id);
        $this->db->where('pin_payment',  $in_kode_pin);
        $this->db->limit('1');
        $query = $this->db->get('users')->result();
        if($query)  {         return true;        }
        else    {           return FALSE;          }
    }
    public function GetName($id) {
        $getdata = $this->Id($id);
        if($getdata){
            foreach ($getdata as $v) {
                return $v->first_name.' '.$v->last_name;
            }
        }
        return 'NONAME';
    }
     public function User_by_nasabahid($in_nasabahid = '',$in_norekening = '') {
        $this->nid = $in_nasabahid;
        $this->rek = $in_norekening;
        $this->db->select('username');
        $this->db->where('nasabah_id',  $this->nid);
        $this->db->where('no_rekening',  $this->rek);
        $this->db->limit('2');
        $query = $this->db->get('admin_users')->result();
        return $query;
    }
    
}
