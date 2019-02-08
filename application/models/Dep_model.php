<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Dep_model
 *
 * @author edisite
 */
class Dep_model extends MY_Model{
    //put your code here
    public function Produk() {
        //$this->dtpengguna = "TELLER";  
        $this->db->select('*');
        //$this->db->where('user_name', $this->dtpengguna);
        $query = $this->db->get('deb_produk');
        return $query->result();  
    }
    public function Kodepemilik() {
        $this->db->select('*');
        $query = $this->db->get('deb_kode_pemilik');
        return $query->result();
    }
    public function Kodegroup1() {
        $this->db->select('*');
        $query = $this->db->get('deb_kode_group1');
        return $query->result();
    }
    public function Kodegroup2() {
        $this->db->select('*');
        $query = $this->db->get('deb_kode_group2');
        return $query->result();
    }
    public function Kodegroup3() {
        $this->db->select('*');
        $query = $this->db->get('deb_kode_group3');
        return $query->result();
    }
    public function Integrasi() {
        $this->db->select('*');
        $query = $this->db->get('deb_integrasi');
        return $query->result();
    }
    public function Kodemetodebasil() {
        $this->db->select('*');
        $query = $this->db->get('deb_kode_metode_basil');
        return $query->result();
    }
    public function Kodehubunganbank() {
        $this->db->select('*');
        $query = $this->db->get('deb_kode_hubungan_bank');
        return $query->result();
    }
    public function Dep_by_id($in_norek) {
        if(empty($in_norek)){
            return FALSE;
        }
        $sql = "select deposito.*, nasabah.nama_nasabah, nasabah.alamat,dep_produk.DESKRIPSI_PRODUK "
                . "FROM deposito, dep_produk, nasabah "
                . "where deposito.kode_produk=dep_produk.kode_produk "
                . "and deposito.nasabah_id=nasabah.nasabah_id "
                . "and no_rekening='".$in_norek."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
   public function Dep_by_transid($in_debtranid) {
        if(empty($in_debtranid)){
            return FALSE;
        }
        $this->db->select('NO_REKENING');
        $this->db->where('DEPTRANS_ID',$in_debtranid);
        $this->db->limit(1);
        $query = $this->db->get('DEPTRANS')->result();
        if($query){
            foreach ($query as $sub_qry) {
                $res_norek =  $sub_qry->NO_REKENING;
            }
            return $res_norek;
        }
        else {
            return FALSE;
        }        
    }
    public function Upd_deposito($norek, $in_array = array()) {
        $this->db->where('NO_REKENING', $norek);
        $query  = $this->db->update('deposito', $in_array);
        return $query;
    }
    public function Trx_agent_history($in_userid = '000',$in_tgl_fr = '0000-00-00',$in_tgl_to = '0000-00-00') {
        $sql = "SELECT tbl_history_r.transaksi_id, tbl_history_r.jenis_transaksi, tbl_history_r.poin, deptrans.MY_KODE_TRANS ,deptrans.DEPTRANS_ID,deptrans.NO_REKENING, deptrans.POKOK_TRANS, tbl_history_r.tanggal FROM deptrans, tbl_history_r WHERE tbl_history_r.kode_transaksi = 'DEP' AND tbl_history_r.agent_id='".$in_userid."' AND tbl_history_r.transaksi_id=deptrans.DEPTRANS_ID AND tbl_history_r.tanggal BETWEEN '".$in_tgl_fr."' AND '".$in_tgl_to."' GROUP BY tbl_history_r.transaksi_id, tbl_history_r.jenis_transaksi,tbl_history_r.poin, deptrans.MY_KODE_TRANS ,deptrans.DEPTRANS_ID, deptrans.NO_REKENING, deptrans.POKOK_TRANS,tbl_history_r.tanggal";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Kode_trans_trx_agent() {
        $sql = "SELECT KODE_TRANS,DESKRIPSI_TRANS FROM dep_kode_trans WHERE KODE_TRANS NOT IN (SELECT kode_transaksi FROM tbl_transaksi WHERE jenis_transaksi='DEP')";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_per_day_by_agent($in_agentid = '', $in_tgl = '') {
        if(empty($in_agentid)){            return FALSE;        }
        if(empty($in_tgl)){            return FALSE;        }  
        $sql = "SELECT TGL_TRANS,NO_REKENING,KODE_TRANS,POKOK_TRANS AS POKOK,BUNGA_TRANS AS BUNGA, 
	PAJAK_TRANS AS PAJAK,ADM_TRANS AS ADM,KETERANGAN AS KET	 
	FROM sejahtera.deptrans WHERE DATE_FORMAT(TGL_TRANS,'%m/%d/%Y') = '".$in_tgl."' AND '100_300' LIKE CONCAT(CONCAT('%',KODE_TRANS),'%') ORDER BY DEPTRANS_ID,TGL_TRANS";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_per_month_sum($in_tgl_from = '0000-00-00', $in_tgl_to = '0000-00-00') {
        if(empty($in_tgl_from)){            return FALSE;        }
        if(empty($in_tgl_to)){            return FALSE;        }  
        $sql = "SELECT kode_trans,(SELECT DESKRIPSI_TRANS FROM dep_kode_trans WHERE KODE_TRANS = `deptrans`.`KODE_TRANS`) AS `desc_kode_trans`,
SUM(IF(pokok_trans IS NULL,0,pokok_trans)) AS nominal_pokok,SUM(IF(bunga_trans IS NULL,0,bunga_trans)) AS nominal_bunga,
SUM(IF(adm_trans IS NULL,0,adm_trans)) AS nominal_adm,COUNT(*) AS total FROM DEPTRANS WHERE TGL_TRANS >= '".$in_tgl_from."' AND TGL_TRANS <= '".$in_tgl_to."' GROUP BY kode_trans ORDER BY kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
    }
}
