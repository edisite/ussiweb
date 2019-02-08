<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transfer
 *
 * @author edisite
 */
class Trf_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Trf_ses($in_kode_trf) {
        $this->db->select('id_transfer,agent_id,dtm,kode_transfer,rekening_sender,kode_bank_sender');
        $this->db->where('id_transfer',$in_kode_trf);
        $query = $this->db->get('transfer_ses_e')->result();
        return $query;
    }
    public function Bank_default() {
        $this->db->select('*');
        $this->db->where('status','1');
        $this->db->where('sett_default','YES');
        $this->db->limit('1');
        $query = $this->db->get('transfer_daftar_bank')->result();
        return $query;
    }
    public function Cek_bank_default() {
        $res_cek_bank_default = $this->Bank_default();
        if($res_cek_bank_default){
            foreach ($res_cek_bank_default as $sub_cek_bank) {
                return $sub_cek_bank->kode_bank;
            }
        }
        return FALSE;
    }
    public function Cekbank($in_kode_bank) {
        $this->db->select('kode_bank,nama_bank,biaya_adm');
        $this->db->where('status','1');
        $this->db->where('kode_bank',$in_kode_bank);
        $query = $this->db->get('transfer_daftar_bank')->result();
        return $query;
    }
    public function GetBankName($in_kode_bank = '') {
        $getdata = $this->Cekbank($in_kode_bank);
        foreach ($getdata as $vb) {
            return $vb->nama_bank  ?: '';
        }
    }
    public function Getres_json($in_code = NULL) {
        if(empty($in_code)){
            return false;
        }
        $this->db->select('respon_json');
        $this->db->where('status','2');
        $this->db->where('code_transfer',$in_code);
        $this->db->limit(1);
        $query = $this->db->get('transfer_ses_e')->result();
        return $query;
    }
    public function Tarifcode($in_code) {
        $this->db->select('tarif_code');
        $this->db->where('tarif_code',$in_code);
        $this->db->where('status','1');
        $query = $this->db->get('transfer_ses_e')->result();
        return $query;
    }
    public function Model_transfer($in_kode_transfer = "") {
        $this->db->select('set_adm_default,adm_default,set_limit,min_trf,max_trf,adm_bmt');
        $this->db->where('kode_trf',$in_kode_transfer);
        $query = $this->db->get('transfer_jenis')->result();
        return $query;
    }
    public function Bkt_trf($in_kode_transfer = NULL) { 
        $sql = "SELECT concat(d.first_name,' ', d.last_name) as pegawe,b.nama_bank as bank_pengirim, "
                . "a.rekening_sender,a.nama_sender,c.nama_bank as bank_penerima, a.rekening_receiver,"
                . "a.nama_receiver,a.nominal,a.cost_adm,a.code_transfer,a.dtm "
                . "FROM transfer_ses_e a,transfer_daftar_bank b, transfer_daftar_bank c, admin_users d "
                . "WHERE a.agent_id = d.id and  a.kode_bank_sender=b.kode_bank "
                . "and a.kode_bank_receiver=c.kode_bank "
                . "and a.code_transfer='".$in_kode_transfer."' and a.status='2'";           
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Trf_bank($in_id = NULL) { 
        $sql = "SELECT concat(d.first_name,' ', d.last_name) as pegawe,a.agent_id,a.total,concat('[',a.kode_bank_sender,']',b.nama_bank) as bank_pengirim, "
                . "a.rekening_sender,a.nama_sender,concat('[',a.kode_bank_receiver,']',c.nama_bank) as bank_penerima, a.rekening_receiver,"
                . "a.nama_receiver,a.nominal,a.cost_adm,a.code_transfer,a.dtm "
                . "FROM transfer_ses_e a,transfer_daftar_bank b, transfer_daftar_bank c, admin_users d "
                . "WHERE a.agent_id = d.id and  a.kode_bank_sender=b.kode_bank "
                . "and a.kode_bank_receiver=c.kode_bank "
                . "and a.id_transfer='".$in_id."' and a.status='1' ";           
                //. "and a.kode_transfer='".$kode_trf."'";           
        $query = $this->db->query($sql)->result();
        return $query;
        
    }
    public function Bkt_trf_antar_bank($in_kode_transfer = NULL) { 
        $sql = "SELECT concat(d.first_name,' ', d.last_name) as pegawe,b.nama_bank as bank_pengirim, "
                . "a.rekening_sender,a.nama_sender,c.nama_bank as bank_penerima, a.rekening_receiver,"
                . "a.nama_receiver,a.nominal,a.cost_adm,a.code_transfer,a.dtm, "
                . "a.t_tgl,a.t_jam,a.t_berita,a.t_noref,a.t_status,a.t_admin_id "
                . "FROM transfer_ses_e a,transfer_daftar_bank b, transfer_daftar_bank c, admin_users d "
                . "WHERE a.agent_id = d.id and  a.kode_bank_sender=b.kode_bank "
                . "and a.kode_bank_receiver=c.kode_bank "
                . "and a.code_transfer='".$in_kode_transfer."' and a.status='2'";           
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function List_perday($in_kode_transfer = NULL, $dtm = '0000-00-00') { 
        $sql = "SELECT b.nama_bank AS bank_pengirim,rekening_sender,a.nama_sender,c.nama_bank AS bank_penerima, "
                . "a.rekening_receiver,a.nama_receiver,a.nominal,a.cost_adm,a.code_transfer,a.dtm,a.status,"
                . "a.kode_transfer FROM transfer_ses_e a,transfer_daftar_bank b, transfer_daftar_bank c, admin_users d "
                . "WHERE a.agent_id = d.id AND  a.kode_bank_sender=b.kode_bank AND a.kode_bank_receiver=c.kode_bank "
                . "AND a.status >='1' AND DATE(a.dtm) = '".$dtm."' AND a.kode_transfer ='".$in_kode_transfer."'";           
        $query = $this->db->query($sql)->result();
        return $query;
    } 
    public function Trf_disburment_mutasi($userid = '') {
        $this->db->where('user_id',$userid);
        $this->db->where('date(dtm) >= CURDATE() - INTERVAL 90 DAY AND CURDATE()');
        $this->db->limit('200');
        return $this->db->get('transfer_log_bmt_kebanklain')->result();                       
    }
}
