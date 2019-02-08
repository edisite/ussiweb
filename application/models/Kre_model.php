<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kre_model
 *
 * @author edisite
 */
class Kre_model extends MY_Model {
    //put your code here   
    public function Kodetype() {
        $this->db->select('*');
        $query = $this->db->get('kre_kode_type');
        return $query->result();
    }
    public function Kodesatuanwaktu() {
        $this->db->select('*');
        $query = $this->db->get('kre_kode_satuan_waktu');
        return $query->result();
    }
     public function Produk() {
        //$this->dtpengguna = "TELLER";  
        $this->db->select('*');
        $this->db->order_by('kode_produk');
        //$this->db->where('user_name', $this->dtpengguna);
        $query = $this->db->get('kre_produk');
        return $query->result();  
    }    
    public function Kwitansi() {             
        $sql = "SELECT MAX(KUITANSI) as KUITANSI FROM KRETRANS WHERE TGL_TRANS>='".$this->Star_date()."' AND TGL_TRANS<='".$this->last_date()."' AND KUITANSI<='Pby.[9999]' AND KUITANSI LIKE 'Pby.%%'";
        $query = $this->db->query($sql);
        return $query->result();  
    }
    protected function Star_date() {
        $hari_ini = date("Y-m-d");
        $tgl_pertama = date('Y-m-01', strtotime($hari_ini));
        return $tgl_pertama;
    }
    protected function Last_date() {
        $hari_ini = date("Y-m-d");
        $tgl_terakhir = date('Y-m-t', strtotime($hari_ini));
        return $tgl_terakhir;
    }
    public function Kre_nas_join_by_rek($in_norek = '') {
         if(empty($in_norek)){
            return FALSE;
        }
        $sql = "select kredit.*, nasabah.nama_nasabah, kre_integrasi.DESKRIPSI_INTEGRASI AS DESKRIPSI_PRODUK,kre_kode_type.* from kredit, kre_integrasi, nasabah,kre_kode_type where kredit.kode_integrasi=kre_integrasi.kode_integrasi and kredit.nasabah_id=nasabah.nasabah_id and kredit.type_kredit=kre_kode_type.kode_type_kredit and no_rekening='".$in_norek."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Jadwal_angsuran($in_norek = '') {
         if(empty($in_norek)){
            return FALSE;
        }
        $sql = "SELECT kretrans_id, tgl_trans, no_rekening, my_kode_trans, angsuran_ke, pokok, bunga, denda,keterangan, verifikasi, userid,pokok+bunga AS pokokbunga FROM kretrans WHERE no_rekening='".$in_norek."' AND FLOOR(my_kode_trans/100)=2 ORDER BY tgl_trans, angsuran_ke";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Tag_tag_ang_detail($in_norek ='') {
         if(empty($in_norek)){
            return FALSE;
        }
        $sql = "SELECT SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0)) AS TAG_POKOK, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,POKOK,0)) AS ANG_POKOK, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,BUNGA,0)) AS TAG_BUNGA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,BUNGA,0)) AS ANG_BUNGA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,DENDA,0)) AS TAG_DENDA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,DENDA,0)) AS ANG_DENDA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,ADM_LAINNYA,0)) AS TAG_ADM_LAINNYA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,ADM_LAINNYA,0)) AS ANG_ADM_LAINNYA "
                . "FROM KRETRANS WHERE NO_REKENING='".$in_norek."' "
                . "AND TGL_TRANS<='".$this->Last_datetm()."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Tag_tag_bln($in_norek ='') {
         if(empty($in_norek)){
            return FALSE;
        }
        $sql = "SELECT SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0)) AS TAG_POKOK, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,BUNGA,0)) AS TAG_BUNGA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,DENDA,0)) AS TAG_DENDA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,ADM_LAINNYA,0)) AS TAG_ADM_LAINNYA "
                . "FROM KRETRANS WHERE NO_REKENING='".$in_norek."' AND TGL_TRANS>='".$this->Star_datetm()."' AND TGL_TRANS<='".$this->Last_datetm()."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    protected function Star_datetm() {
        $hari_ini = date("Y-m-d");
        $tgl_pertama = date('Y-m-01 00:00:00', strtotime($hari_ini));
        return $tgl_pertama;
    }
    protected function Last_datetm() {
        $hari_ini = date("Y-m-d");
        $tgl_terakhir = date('Y-m-t 00:00:00', strtotime($hari_ini));
        return $tgl_terakhir;
    }
    public function Ins_Tbl($table,$data = array()) {        
        $query = $this->db->insert($table, $data);
        return $query;        
    }
    public function Ins_batch($table,$data = array()) {
        return $this->db->insert_batch($table, $data);
    }
    public function Count_trans_by_kodetrans($in_norekening = NULL,$in_my_kodetrans =  NULL,$in_tgl_trans = NULL) {
        $sql = "SELECT COUNT(*) as counttrans "
                . "FROM KRETRANS "
                . "WHERE NO_REKENING='".$in_norekening."' "
                . "AND TGL_TRANS='".$in_tgl_trans."' "
                . "AND MY_KODE_TRANS='".$in_my_kodetrans."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Kode_integrasi_by_rek($in_rek = NULL) {        
        $this->db->select('kode_integrasi');
        $this->db->where('NO_REKENING',$in_rek);
        return $this->db->get('KREDIT')->result();
    }
    public function Integrasi_by_kd_int($nokode) {
        $sql = "SELECT KODE_PERK_KAS,KODE_PERK_KREDIT,KODE_PERK_BUNGA FROM KRE_INTEGRASI WHERE KODE_INTEGRASI='".$nokode."' LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Kodetrans_by_kodetrans($in_kodetrans = NULL) {
        $sql = "select kode_trans as kode,"
                . "deskripsi_trans as deskripsi, "
                . "TOB "
                . "from KRE_KODE_TRANS "
                . "where "
                . "kode_trans='".$in_kodetrans."' "
                . "order by kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
        
    }
    public function Kretrans_count_by_rek($in_norek =  NULL) {
        if(empty($in_norek)){
            return FALSE;
        }
        $sql = "SELECT "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0)) AS REALISASI_POKOK, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,POKOK,0)) AS ANGSURAN_POKOK, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,BUNGA,0)) AS REALISASI_BUNGA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,BUNGA,0)) AS ANGSURAN_BUNGA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,DISCOUNT,0)) AS ANGSURAN_DISCOUNT, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=4,BUNGA,0)) AS ANGGARAN_BUNGA_YAD, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=5,BUNGA,0)) AS ANGSURAN_BUNGA_YAD, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,PROVISI,0)) AS PROVISI, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=8,POKOK,0)) AS TRANSAKSI_DEBIUS, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=9,POKOK,0)) AS ANGSURAN_DEBIUS "
                . "FROM KRETRANS "
                . "WHERE NO_REKENING='".$in_norek."' LIMIT 10";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Del_kretrans($in_idkretrans) {
        $query = $this->db->delete('KRETRANS', array('KRETRANS_ID' => $in_idkretrans)); 
        return $query;
    }
    public function Upd_kredit($norek, $in_array = array()) {
        $this->db->where('NO_REKENING', $norek);
        $query  = $this->db->update('KREDIT', $in_array);
        return $query;
    }
    public function Kredit_by_rek($in_norek) {
        $this->db->select('KODE_PRODUK,KODE_INTEGRASI');
        $this->db->where('NO_REKENING',$in_norek);
        $this->db->limit('1');
        $query = $this->db->get('KREDIT');
        return $query->result();
    }
    public function Kredit_int_by_kode($in_kodeintegrasi) {
        $this->db->select('KODE_PERK_KREDIT,KODE_PERK_KAS,KODE_PERK_BUNGA,KODE_PERK_ADM_LAINNYA,KODE_PERK_DENDA');
        $this->db->where('KODE_INTEGRASI',$in_kodeintegrasi);
        $this->db->limit('1');
        $query = $this->db->get('KRE_INTEGRASI');
        return $query->result();
    }
    public function Integrasi() {
        $this->db->select('kode_integrasi,deskripsi_integrasi');
        $this->db->order_by('kode_integrasi');
        $query = $this->db->get('KRE_INTEGRASI');
        return $query->result();
    }
    public function Kre_tunggakan_by_rek($in_norekening = '',$tgl_tagihan = '') {
        if(empty($in_norekening)){
            return FALSE;
        }
        if(empty($tgl_tagihan)){
            return FALSE;
        }
        $sql = "SELECT SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,POKOK,0)) AS TUNGGAKAN_POKOK, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,BUNGA,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,BUNGA,0)) AS TUNGGAKAN_BUNGA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,POKOK,0)) AS SALDO_POKOK, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,BUNGA,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,BUNGA,0)) AS SALDO_BUNGA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=4,BUNGA,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=5,BUNGA,0)) AS SALDO_BUNGA_RRA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=6,DENDA,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,DENDA,0)) AS SALDO_DENDA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,DENDA,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,DENDA,0)) AS DENDA, "
                . "SUM(IF(FLOOR(KRETRANS.MY_KODE_TRANS/100)=2,KRETRANS.ADM_LAINNYA,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,KRETRANS.ADM_LAINNYA,0)) AS ADM_LAINNYA, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,BUNGA,0)) AS MARGIN_JADWAL, "
                . "SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,BUNGA,0)) AS MARGIN_ANGSURAN "
                . "FROM KREDIT LEFT JOIN KRETRANS ON KREDIT.NO_REKENING=KRETRANS.NO_REKENING "
                . "WHERE TGL_TRANS<='".$tgl_tagihan."' AND KREDIT.NO_REKENING='".$in_norekening."'";
               
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Kre_angsuran_ke_by_rek($in_norekening = '') {
        if(empty($in_norekening)){
            return 0;
        }
        $sql = "SELECT MAX(ANGSURAN_KE) AS ANGSURAN_KE FROM KRETRANS "
                . "WHERE FLOOR(MY_KODE_TRANS/100)=2 "
                . "AND NO_REKENING='".$in_norekening."' "
                . "AND TGL_TRANS<='".date("Y-m-d")." 00:00:00'";              
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Kre_angsuran_ses($norek = '',$code = '', $agentid = '') {
        $sql = "SELECT userid, code_trx, no_rekening, nama, total_setoran, total_tunggakan, tunggakan_pokok, "
                . "tunggakan_bunga, tunggakan_adm, tunggakan_denda, total_tagihan, tagihan_pokok, "
                . "tagihan_bunga, tagihan_adm, tagihan_denda, angsuran_ke, jml_angsuran, "
                . "tunggakan_pokok + tagihan_pokok AS t_pokok,"
                . "tunggakan_bunga + tagihan_bunga AS t_bunga,"
                . "tunggakan_adm + tagihan_adm AS t_adm,"
                . "tunggakan_denda + tagihan_denda AS t_denda "
                . "FROM kre_angsuran_ses_e "
                . "WHERE no_rekening = '".$norek."' AND code_trx = '".$code."' AND userid = '".$agentid."' AND status = '0'";              
        $query = $this->db->query($sql);
        return $query->result();
                
    }
    public function Upd_kre_angsuran_ses($idtrx = '', $in_array = array()) {
        if(empty($idtrx)){
            return FALSE;
        }
        $this->db->where('code_trx', $idtrx);
        $query  = $this->db->update('kre_angsuran_ses_e', $in_array);
        return $query;
    }
    public function Kre_periode($in_norek = '',$periode = '') {        
        if(empty($in_norek)){
            return false;
        }        
        if($periode == '6bulan'){
            $period = ' 6 MONTH ';
        }elseif($periode == '12bulan'){
            $period = ' 12 MONTH ';
        }elseif($periode == '24bulan'){
            $period = ' 24 MONTH ';
        }else{
            $period = ' 1 MONTH ';
        }
        
        $sql = "SELECT tgl_trans,kode_trans as sandi, keterangan,  IF(POKOK >0,POKOK,0) as pokok, IF(BUNGA >0,BUNGA,0) AS basil "
                . "FROM kretrans "
                . "WHERE no_rekening='".$in_norek."' "
                . "AND '100_300' like concat(concat('%',kode_trans),'%') "
                . "AND TGL_TRANS >= CURDATE() - INTERVAL ".$period." "
                . "ORDER BY tgl_trans, my_kode_trans";              
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Kre_angsuran_bukti($code_angsuran = '') {
        if(empty($code_angsuran)){
            return FALSE;
        }
        $sql = "SELECT DATE_FORMAT(a.TGL_TRANS,'%d %M %Y') AS TGL_TRANS,a.NO_REKENING,b.USERID,c.nama,KUITANSI,a.ANGSURAN_KE,POKOK,BUNGA,DENDA,ADM_LAINNYA,a.KODE_TRANS, IF(POKOK >0,POKOK,0) + IF(BUNGA >0,BUNGA,0) + IF(DENDA > 0,DENDA,0) AS nominal,a.KETERANGAN from kretrans a,transaksi_master b,kre_angsuran_ses_e c WHERE  b.TRANS_ID_SOURCE=a.KRETRANS_ID AND b.trans_id = c.master_id AND c.status = '1' AND c.code_trx='".$code_angsuran."'";              
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_agent_history($in_userid = '000',$in_tgl_fr = '0000-00-00',$in_tgl_to = '0000-00-00') {
        $sql = "SELECT tbl_history_r.transaksi_id, tbl_history_r.jenis_transaksi, tbl_history_r.poin, "
                . "kretrans.MY_KODE_TRANS ,kretrans.KRETRANS_ID,kretrans.NO_REKENING, "
                . "kretrans.POKOK, tbl_history_r.tanggal FROM kretrans, tbl_history_r "
                . "WHERE tbl_history_r.kode_transaksi = 'DEP' AND tbl_history_r.agent_id='".$in_userid."' "
                . "AND tbl_history_r.transaksi_id=kretrans.KRETRANS_ID AND tbl_history_r.tanggal "
                . "BETWEEN '".$in_tgl_fr."' AND '".$in_tgl_to."' GROUP BY tbl_history_r.transaksi_id, "
                . "tbl_history_r.jenis_transaksi,tbl_history_r.poin, kretrans.MY_KODE_TRANS ,"
                . "kretrans.KRETRANS_ID, kretrans.NO_REKENING, kretrans.POKOK_TRANS,tbl_history_r.tanggal";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Kode_trans_trx_agent() {
        $sql = "SELECT KODE_TRANS,DESKRIPSI_TRANS FROM kre_kode_trans WHERE KODE_TRANS NOT IN (SELECT kode_transaksi FROM tbl_transaksi WHERE jenis_transaksi='KRE')";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_per_day_by_agent($in_agentid = '', $in_tgl = '') {
        if(empty($in_agentid)){            return FALSE;        }
        if(empty($in_tgl)){            return FALSE;        }  
        $sql = "SELECT KRETRANS_ID,TGL_TRANS,NO_REKENING,MY_KODE_TRANS AS KODETRANS,ANGSURAN_KE,"
                . "POKOK,BUNGA,DENDA,ADM_LAINNYA AS ADM,KETERANGAN AS KET "
                . "FROM sejahtera.kretrans "
                . "WHERE "
//                . "TGL_TRANS>='2016-06-30' "
                . "DATE_FORMAT(TGL_TRANS,'%m/%d/%Y') = '".$in_tgl."' "
                . "AND '100_300' LIKE CONCAT(CONCAT('%',MY_KODE_TRANS),'%') ORDER BY KRETRANS_ID,TGL_TRANS";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_per_day_by_agent_sum($in_agentid = '', $in_tgl = '') {
        if(empty($in_agentid)){            return FALSE;        }
        if(empty($in_tgl)){            return FALSE;        }  
        $sql = "SELECT SUM(IF((FLOOR(my_kode_trans/100)=3),pokok,0)) AS apokok,
SUM(IF((FLOOR(my_kode_trans/100)=3),bunga,0)) AS abunga,
SUM(IF((FLOOR(my_kode_trans/100)=3),denda,0)) AS adenda,
SUM(IF((FLOOR(my_kode_trans/100)=3),adm_lainnya,0)) AS aadm,
SUM(IF((FLOOR(my_kode_trans/100)=1),pokok,0)) AS rpokok,
SUM(IF((FLOOR(my_kode_trans/100)=1),bunga,0)) AS rbunga,
SUM(IF((FLOOR(my_kode_trans/100)=1),denda,0)) AS rdenda,
SUM(IF((FLOOR(my_kode_trans/100)=1),adm_lainnya,0)) AS radm,

SUM(IF((FLOOR(my_kode_trans/100)=3),pokok,0)) +
SUM(IF((FLOOR(my_kode_trans/100)=3),bunga,0)) +
SUM(IF((FLOOR(my_kode_trans/100)=3),denda,0)) +
SUM(IF((FLOOR(my_kode_trans/100)=3),adm_lainnya,0)) AS total_angsuran,
SUM(IF((FLOOR(my_kode_trans/100)=1),pokok,0))+
SUM(IF((FLOOR(my_kode_trans/100)=1),bunga,0))+
SUM(IF((FLOOR(my_kode_trans/100)=1),denda,0))+
SUM(IF((FLOOR(my_kode_trans/100)=1),adm_lainnya,0)) AS total_realisasi "
                . "FROM kretrans "
                . "WHERE "
               // . "USERID ='".$in_agentid."' "
                . "DATE_FORMAT(TGL_TRANS,'%m/%d/%Y') = '".$in_tgl."' "
                . "AND '100_300' LIKE CONCAT(CONCAT('%',MY_KODE_TRANS),'%') ORDER BY KRETRANS_ID,TGL_TRANS";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_per_month_sum($in_tgl_from = '0000-00-00', $in_tgl_to = '0000-00-00') {
      if(empty($in_tgl_from)){            return FALSE;        }
        if(empty($in_tgl_to)){            return FALSE;        }  
        $sql = "SELECT kode_trans,(SELECT DESKRIPSI_TRANS FROM kre_kode_trans WHERE KODE_TRANS = `kretrans`.`KODE_TRANS`) AS `desc_kode_trans`,
SUM(IF(pokok IS NULL,0,pokok)) AS nominal_pokok,SUM(IF(bunga IS NULL,0,bunga)) AS nominal_bunga,
COUNT(*) AS total FROM KRETRANS WHERE TGL_TRANS >= '".$in_tgl_from."' AND TGL_TRANS <= '".$in_tgl_to."' GROUP BY kode_trans ORDER BY kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Peragent($in_agentid = '', $in_tgl = '0000-00-00') {
        if(empty($in_agentid)){            return FALSE;        }
        if(empty($in_tgl)){            return FALSE;        }  
        $sql = "SELECT 
SUM(IF((FLOOR(my_kode_trans/100)=3),pokok,0)) AS ang_pokok, 
SUM(IF((FLOOR(my_kode_trans/100)=3),bunga,0)) AS ang_bunga, 
SUM(IF((FLOOR(my_kode_trans/100)=3),denda,0)) AS ang_denda, 
SUM(IF((FLOOR(my_kode_trans/100)=3),adm_lainnya,0)) AS ang_adm,
SUM(IF((FLOOR(my_kode_trans/100)=1),pokok,0)) AS rel_pokok,
SUM(IF((FLOOR(my_kode_trans/100)=1),bunga,0)) AS rel_bunga,
SUM(IF((FLOOR(my_kode_trans/100)=1),denda,0)) AS rel_denda,
SUM(IF((FLOOR(my_kode_trans/100)=1),adm_lainnya,0)) AS rel_adm "
                . "FROM kretrans "
                . "WHERE "
                . "USERID ='".$in_agentid."' "
                . "AND TGL_TRANS = '".$in_tgl."' "
                . "AND '100_300' LIKE CONCAT(CONCAT('%',MY_KODE_TRANS),'%') ORDER BY KRETRANS_ID,TGL_TRANS";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Perkolektor() {
            $sql = "SELECT b.kode_kolektor,COUNT(a.NO_REKENING) AS jml_peminjam,
    SUM(IF((a.JML_PINJAMAN)>0,a.JML_PINJAMAN,0)) AS realisasi  
    FROM kredit a 
    RIGHT JOIN kre_kode_kolektor b ON a.kode_kolektor = b.kode_kolektor 
    WHERE 1 GROUP BY b.kode_kolektor";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
}
