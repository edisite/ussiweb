<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Trans
 *
 * @author edisite
 */
class Trans extends MY_Model {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Master($in_uid,$in_kd_perk) {
        if($in_uid == ""){
            return;
        }
        if($in_kd_perk == ""){
            return;
        }
        $sql = "select sum(debet) as penerimaan, "
                . "sum(kredit) as pengeluaran "
                . "from transaksi_master, transaksi_detail "
                . "where transaksi_master.trans_id=transaksi_detail.master_id "
                . "and modul_id_source='GL' "
                . "and transaksi_master.userid='".$in_uid."' "
                . "and tgl_trans=DATE_ADD(CURDATE(), INTERVAL - 1 DAY) "
                . "and kode_perk='".$in_kd_perk."'";           
        $query = $this->db->query($sql)->result();
        return $query;
    
    }
    public function Master2($in_uid,$in_kd_perk,$kode_office) {
        if($in_uid == ""){
            return;
        }
        if($in_kd_perk == ""){
            return;
        }
        if($kode_office == ""){
            return;
        }
        $sql = "select sum(debet) as debet,"
                . "sum(kredit) as kredit,"
                . "d_or_k,"
                . "transaksi_detail.kode_perk "
                . "from transaksi_detail,perkiraan "
                . "where transaksi_detail.kode_perk=perkiraan.kode_perk "
                . "and transaksi_detail.kode_perk='".$in_kd_perk."' "
                . "and master_id in"
                        . "(select trans_id "
                        . "from transaksi_master "
                        . "where tgl_trans<= DATE_ADD(CURDATE(), INTERVAL - 1 DAY)"
                        . "and kode_kantor='".$kode_office."') "
                . "group by kode_perk";        
        $query = $this->db->query($sql)->result();
        return $query;        
                
    }
    public function Tabtrans($in_uid) {
        $sql = "SELECT SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0)) AS SETORAN, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0)) AS PENARIKAN "
                . "FROM TABTRANS "
                . "WHERE "
                . "TGL_TRANS=DATE_ADD(CURDATE(), INTERVAL - 1 DAY)"
                . "AND USERID='".$in_uid."' "
                . "AND TOB='T'";           
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Tabtrans2($in_uid) {
        $sql = "SELECT SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0)) AS SETORAN, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0)) AS PENARIKAN "
                . "FROM TABTRANS "
                . "WHERE TGL_TRANS=DATE_FORMAT(NOW(	),'%Y-%m-%d')"
                . "AND USERID='".$in_uid."' "
                . "AND TOB='T'";           
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Debtrans($in_uid) {
        $sql = "SELECT "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK_TRANS,0)) AS POKOK_SETOR, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK_TRANS,0)) AS POKOK_AMBIL, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,TITIPAN_TRANS,0)) AS TITIPAN_SETOR, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=4,TITIPAN_TRANS,0)) AS TITIPAN_AMBIL, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=5,BUNGA_TRANS,0)) AS BUNGA_SETOR, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=6,BUNGA_TRANS,0)) AS BUNGA_AMBIL, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=7,PAJAK_TRANS,0)) AS PAJAK_SETOR, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=8,PAJAK_TRANS,0)) AS PAJAK_AMBIL "
                . "FROM DEPTRANS "
                . "WHERE "        
                . "TGL_TRANS=DATE_ADD(CURDATE(), INTERVAL - 1 DAY) "              
                . "AND USERID='".$in_uid."' "
                . "AND TOB='T'";           
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Debtrans2($in_uid) {
        $sql = "SELECT SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK_TRANS,0)) AS POKOK_SETOR, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK_TRANS,0)) AS POKOK_AMBIL, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,TITIPAN_TRANS,0)) AS TITIPAN_SETOR, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=4,TITIPAN_TRANS,0)) AS TITIPAN_AMBIL, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=5,BUNGA_TRANS,0)) AS BUNGA_SETOR, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=6,BUNGA_TRANS,0)) AS BUNGA_AMBIL, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=7,PAJAK_TRANS,0)) AS PAJAK_SETOR, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=8,PAJAK_TRANS,0)) AS PAJAK_AMBIL "
                . "FROM DEPTRANS "
                . "WHERE TGL_TRANS=DATE_FORMAT(NOW(	),'%Y-%m-%d')"
                . "AND USERID='".$in_uid."' "
                . "AND TOB='T'";          
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Kretrans($in_uid) {
        $sql = "SELECT SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0)) AS REALISASI, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,POKOK,0)) AS ANGSURAN "
                . "FROM KRETRANS "
                . "WHERE "
                . "TGL_TRANS=DATE_ADD(CURDATE(), INTERVAL - 1 DAY) "
                . "AND USERID='".$in_uid."' "
                . "AND TOB<>'O'";           
        $query = $this->db->query($sql)->result();
        return $query;
    }
     public function Kretrans2($in_uid) {
        $sql = "SELECT SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0)) AS REALISASI, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,POKOK,0)) AS ANGSURAN "
                . "FROM KRETRANS "
                . "WHERE TGL_TRANS=DATE_FORMAT(NOW(	),'%Y-%m-%d')"
                . "AND USERID='".$in_uid."' "
                . "AND TOB<>'O'";           
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Tab_nas_tabtrans($in_paytype,$in_id,$in_tgl_fr,$in_tgl_to) {
        
         $sql = "select tgl_trans,tabtrans.keterangan,kuitansi,tabtrans.userid, "
                 . "if((floor(my_kode_trans/100)=1),REPLACE(REPLACE(REPLACE(FORMAT(pokok, 2), '.', '|'), ',', '.'), '|', ',') ,0) as setoran, "
                 . "if((floor(my_kode_trans/100)=2),REPLACE(REPLACE(REPLACE(FORMAT(pokok, 2), '.', '|'), ',', '.'), '|', ','),0) as penarikan "
                 . "from nasabah, tabung, tabtrans "
                 . "where nasabah.nasabah_id=tabung.nasabah_id "
                 . "and tabung.no_rekening=tabtrans.no_rekening "
                 . "and tabtrans.tob='".$in_paytype."' ";
                 if($in_id == "alluser"){
                     
                 }else{
                    $sql .= "and tabtrans.userid='".$in_id."' ";                         
                 }
                 $sql .= "and DATE_FORMAT(tgl_trans,'%m/%d/%Y') >= '".$in_tgl_fr."'  "
                 . "and DATE_FORMAT(tgl_trans,'%m/%d/%Y') <= '".$in_tgl_to."' "
                 . "having setoran>0 "
                 . "or  penarikan>0 "
                 . "order by tgl_trans, tabtrans_id";           
        $query = $this->db->query($sql)->result();
        return $query;
   
    }
    public function Del_trans_master_by_transid_source($in_tabtrans = array()) {
        if($this->session->userdata('user_id') == "" || $this->session->userdata('username') == "")
        {
            redirect();
        }
        $query = $this->db->delete('TRANSAKSI_MASTER', $in_tabtrans); 
        return $query;
    }
    public function Deb_trans_by_rek($in_rek) {
        if($this->session->userdata('user_id') == "" || $this->session->userdata('username') == "")
        {
            redirect();
        }
        if(empty($in_rek)){
            return FALSE;
        }
        $sql = "SELECT 
		SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK_TRANS,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK_TRANS,0)) AS SALDO_AKHIR_POKOK,
                SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,BUNGA_TRANS,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,BUNGA_TRANS,0)) AS SALDO_AKHIR_BUNGA,
                SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,PAJAK_TRANS,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,PAJAK_TRANS,0)) AS SALDO_AKHIR_PAJAK,
                SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,TITIPAN_TRANS,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,TITIPAN_TRANS,0)) AS SALDO_AKHIR_TITIPAN,
                SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,ZAKAT_TRANS,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,ZAKAT_TRANS,0)) AS SALDO_AKHIR_ZAKAT,
                SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,CADANGAN_TRANS,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,CADANGAN_TRANS,0)) AS SALDO_AKHIR_CADANGAN
		FROM DEPTRANS 
	WHERE NO_REKENING='".$in_rek."' limit 1";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Del($in_tbl,$in_data = array()) {
        if($this->session->userdata('user_id') == "" || $this->session->userdata('username') == "")
        {
            return FALSE;
        }
        $query = $this->db->delete($in_tbl, $in_data); 
        return $query;
    }
    public function Tabtrans_tab_nas_by_rek($in_norek,$periode) {
        if(empty($in_norek)){
            return false;
        }        
        if($periode == '1bulan'){
            $period = ' 1 MONTH ';
        }elseif($periode == '1minggu'){
            $period = ' 1 WEEK ';
        }else{
            $period = ' 1 DAY ';
        }
        $sql = "select tabtrans.tabtrans_id,tabtrans.tgl_trans,tabtrans.no_rekening,nasabah.nama_nasabah,tabtrans.kode_trans, tabtrans.keterangan, "
                . "REPLACE(REPLACE(REPLACE(FORMAT(if(my_kode_trans=100,pokok,0), 0), '.', '|'), ',', '.'), '|', ',') as setoran, "
                . "REPLACE(REPLACE(REPLACE(FORMAT(if(my_kode_trans=200,pokok,0), 0), '.', '|'), ',', '.'), '|', ',') as penarikan, "
                . " tabung.Saldo_akhir,"
                . "REPLACE(REPLACE(REPLACE(FORMAT(tabtrans.adm, 0), '.', '|'), ',', '.'), '|', ',') as adm,tabtrans.kode_kolektor "
                . "from tabtrans,tabung,nasabah  "
                . "where tabtrans.no_rekening=tabung.no_rekening "
                . "and tabung.nasabah_id=nasabah.nasabah_id "
                . "and (KODE_KOLEKTOR LIKE '%%' or KODE_KOLEKTOR IS NULL) "
                . "and (TABTRANS.NO_REKENING LIKE '%".$in_norek."%' "
                . "OR NAMA_NASABAH LIKE '%".$in_norek."%' ) "
                . "AND TGL_TRANS >= CURDATE() - INTERVAL ".$period." "
                . "and (FLOOR(MY_KODE_TRANS/100)<3) "
                . "ORDER BY TGL_TRANS,TABTRANS_ID";
        $query = $this->db->query($sql)->result();
        
        return $query;
    }
    public function Kretrans_tab_nas_by_rek($in_norek,$periode) {
        if(empty($in_norek)){
            return 'NO REK not found';
        }        
        if($periode == '1bulan'){
            $period = ' 1 MONTH ';
        }elseif($periode == '1minggu'){
            $period = ' 1 WEEK ';
        }else{
            $period = ' 1 DAY ';
        }
        $sql = "select tabtrans.tabtrans_id,tabtrans.tgl_trans,tabtrans.no_rekening,nasabah.nama_nasabah,tabtrans.kode_trans, tabtrans.keterangan, "
                . "REPLACE(REPLACE(REPLACE(FORMAT(if(my_kode_trans=100,pokok,0), 0), '.', '|'), ',', '.'), '|', ',') as setoran, "
                . "REPLACE(REPLACE(REPLACE(FORMAT(if(my_kode_trans=200,pokok,0), 0), '.', '|'), ',', '.'), '|', ',') as penarikan, "
                . " tabung.Saldo_akhir,"
                . "REPLACE(REPLACE(REPLACE(FORMAT(tabtrans.adm, 0), '.', '|'), ',', '.'), '|', ',') as adm,tabtrans.kode_kolektor "
                . "from kretrans,tabung,nasabah  "
                . "where tabtrans.no_rekening=tabung.no_rekening "
                . "and tabung.nasabah_id=nasabah.nasabah_id "
                . "and (KODE_KOLEKTOR LIKE '%%' or KODE_KOLEKTOR IS NULL) "
                . "and (TABTRANS.NO_REKENING LIKE '%".$in_norek."%' "
                . "OR NAMA_NASABAH LIKE '%".$in_norek."%' ) "
                . "AND TGL_TRANS >= CURDATE() - INTERVAL ".$period." "
                . "and (FLOOR(MY_KODE_TRANS/100)<3) "
                . "ORDER BY TGL_TRANS,TABTRANS_ID";
        $query = $this->db->query($sql)->result();
        
        return $query;
    }
    public function Jurnal($jenis_trx = '',$kdkantor = '',$tgl_fr = null,$tgl_to=null) 
    {
        $sql = "SELECT TRANSAKSI_MASTER.TRANS_ID,TGL_TRANS,KODE_JURNAL,URAIAN, NO_BUKTI,PERKIRAAN.KODE_PERK,KODE_ALTERNATIF,NAMA_PERK,TRANSAKSI_DETAIL.DEBET,KREDIT "
                . "FROM TRANSAKSI_MASTER,TRANSAKSI_DETAIL,PERKIRAAN "
                . "WHERE TRANSAKSI_MASTER.TRANS_ID=TRANSAKSI_DETAIL.MASTER_ID "
                . "AND TRANSAKSI_DETAIL.KODE_PERK=PERKIRAAN.KODE_PERK "
                . "AND PERKIRAAN.TYPE_PERK<>'ADMINISTRATIF' "
                . "AND TGL_TRANS>='".$tgl_fr."' AND TGL_TRANS<='".$tgl_to."' ";
                if($kdkantor == "all"){
                $sql .= "AND '_35_35.1' like concat(concat('%',KODE_KANTOR),'%')   ";
                }else{
                    $sql .= "AND '".$kdkantor."' like concat(concat('%',KODE_KANTOR),'%')   ";                    
                }
                if($jenis_trx == "all"){}
                else{
                    $sql .= "and TRANSAKSI_MASTER.IS_BANK='".$jenis_trx."' ";
                }
                $sql .= "ORDER BY TGL_TRANS,KODE_JURNAL,TRANSAKSI_DETAIL.TRANS_ID";
        
        $query = $this->db->query($sql)->result();        
        return $query;
    }
    public function Run_transaction($tbl1 = '',$data1 = '',$tbl2 = '',$data2 = '') {
        $this->db->trans_begin();
        //first
        $this->db->insert($tbl1, $data1); // insert trx master
        $this->db->insert_batch($tbl2, $data2); // insert trx detail
        
        $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }
    public function Create_temp_tbl($tablename = 'myteller_e_') {
        $idn_temp    = date('dmyhis');
        $tbl_temp    = $tablename.$idn_temp;
        
        $sql = "CREATE TABLE IF NOT EXISTS mytteller201216131941 (
                kode_perk char(20) NOT NULL,
                kode_induk char(20) NOT NULL,
                type_perk char(15) NOT NULL,
                level_perk int(11) DEFAULT '0',
                id_perk int(11) NOT NULL,
                id_induk int(11) NOT NULL,
                g_or_d char(1) DEFAULT NULL,
                saldo_akhir1 decimal(18,2) NOT NULL DEFAULT '0.00',
                saldo_akhir2 decimal(18,2) NOT NULL DEFAULT '0.00',
                saldo_akhir3 decimal(18,2) NOT NULL DEFAULT '0.00',
                saldo_akhir4 decimal(18,2) NOT NULL DEFAULT '0.00',
                saldo_akhir5 decimal(18,2) NOT NULL DEFAULT '0.00',
                saldo_akhir6 decimal(18,2) NOT NULL DEFAULT '0.00',
                saldo_akhir7 decimal(18,2) NOT NULL DEFAULT '0.00',
                saldo_akhir8 decimal(18,2) NOT NULL DEFAULT '0.00',
                saldo_akhir9 decimal(18,2) NOT NULL DEFAULT '0.00',
                saldo_akhir10 decimal(18,2) NOT NULL DEFAULT '0.00',
                PRIMARY KEY (`kode_perk`),
                UNIQUE KEY kode (`kode_perk`)
              ) ENGINE=MEMORY DEFAULT CHARSET=latin1";
        $query = $this->db->query($sql)->result();        
        return $query;
    }
    public function Neraca_temp($tgl = '') {
        $tgl = $tgl ?: date('Y-m-d');
        $sql = "SELECT perkiraan.kode_perk,nama_perk,saldo_akhir1 AS saldo_awal, saldo_akhir2 AS mut_debet,"
                . "saldo_akhir3 AS mut_kredit, saldo_akhir4 AS saldo_akhir,tanggal_temp1,rep_neraca_harian_temp.ID_INDUK,rep_neraca_harian_temp.ID_PERK tanggal_temp2, "
                . "perkiraan.kode_induk,perkiraan.level_perk,perkiraan.type_perk,perkiraan.g_or_d, "
                . "IF(perkiraan.type_perk='HARTA','AKTIVA','PASIVA') AS group_neraca "
                . "FROM perkiraan,".TBL_NERACA_TEMP." "
                . "WHERE perkiraan.kode_perk=".TBL_NERACA_TEMP.".kode_perk "
                . "AND ".TBL_NERACA_TEMP.".dtm = '".$tgl."' "
//                . "AND ((perkiraan.type_perk='HARTA') "
//                . "OR (perkiraan.type_perk='KEWAJIBAN') "
//                . "OR (perkiraan.type_perk='MODAL') "
//                . "OR (perkiraan.type_perk='LABA RUGI')) "
                . "AND perkiraan.LEVEL_PERK>=0 "
                . "AND perkiraan.LEVEL_PERK<=9 "
                . "ORDER BY perkiraan.KODE_PERK";
        $query = $this->db->query($sql);        
        return $query->result();  
    }
    public function Neraca_temp1() {
        $sql = "SELECT perkiraan.kode_perk,nama_perk,saldo_akhir1 AS saldo_awal, saldo_akhir2 AS mut_debet,saldo_akhir3 AS mut_kredit, saldo_akhir4 AS saldo_akhir,tanggal_temp1, tanggal_temp2, perkiraan.kode_induk,perkiraan.level_perk,perkiraan.type_perk,perkiraan.g_or_d, IF(perkiraan.type_perk='HARTA','AKTIVA','PASIVA') AS group_neraca FROM perkiraan,MyTTELLER201216131949 WHERE perkiraan.kode_perk=MyTTELLER201216131949.kode_perk AND ((perkiraan.type_perk='HARTA') OR (perkiraan.type_perk='KEWAJIBAN') OR (perkiraan.type_perk='MODAL') OR (perkiraan.type_perk='LABA RUGI')) AND perkiraan.LEVEL_PERK>=0 AND perkiraan.LEVEL_PERK<=9 ORDER BY perkiraan.KODE_PERK";
        $query = $this->db->query($sql)->result();        
        return $query;  
      
    }

    
}
define("TBL_NERACA_TEMP", "rep_neraca_harian_temp");