<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tab_produk_model
 *
 * @author edisite
 */
class Tab_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function Produk() {
        $sql = "select kode_produk as kode,deskripsi_produk as deskripsi from tab_produk order by kode_produk";
        $query = $this->db->query($sql);
        return $query->result();  
    }
    public function Kodegroup1() {
        $sql = "select kode_group1 as kode,deskripsi_group1 as deskripsi from tab_kode_group1 order by kode_group1";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Kodegroup2() {
        $sql = "select kode_group2 as kode,deskripsi_group2 as deskripsi from tab_kode_group2 order by kode_group2";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Kodegroup3() {
        $sql = "select kode_group3 as kode,deskripsi_group3 as deskripsi from tab_kode_group3 order by kode_group3";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Kodegroup4() {
        $sql = "select kode_produk as kode,deskripsi_produk as deskripsi from tab_produk order by kode_produk";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Integrasi() {
        $sql = "select kode_integrasi as kode,deskripsi_integrasi as deskripsi from tab_integrasi order by kode_integrasi";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Integrasi_by_kd_int($nokode) {
        $sql = "SELECT KODE_PERK_KAS,KODE_PERK_HUTANG_POKOK,KODE_PERK_PEND_ADM,KODE_PERK_TRANSFER from TAB_INTEGRASI WHERE KODE_INTEGRASI='".$nokode."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Kodepemilik() {
        $this->db->select('*');
        $query = $this->db->get('tab_kode_pemilik');
        return $query->result();
    }
    public function Kodemetodebasil() {
        $this->db->select('*');
        $query = $this->db->get('tab_kode_metode_basil');
        return $query->result();
    }
    public function Kodehubunganbank() {
        $this->db->select('*');
        $query = $this->db->get('tab_kode_hubungan_bank');
        return $query->result();
    }
    public function Kodetrans() {
        $sql = "select kode_trans as kode,"
                . "deskripsi_trans as deskripsi "
                . "from tab_kode_trans "
                . "where '100_101_102_103_104_105_110_111_112_120_121_122_125_126_200_201_202_203_204_205_206_210_211_220_221_222_225_226_227_228_229_230_231_232_233_234_235' like concat(concat('%',kode_trans),'%') "
                . "order by kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
        
    }
    public function Kodetrans_filter() {
        $sql = "select kode_trans as kode,"
                . "deskripsi_trans as deskripsi "
                . "from TAB_KODE_TRANS "
                . "where "
                . "kode_trans='100' "
                . "or Kode_trans='200' "
                . "or kode_trans='102' "
                . "or Kode_trans='202' "
                . "or kode_trans='104' "
                . "or Kode_trans='204' "
                . "or kode_trans='205' "
                . "order by kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
        
    }
     public function Kodetrans_by_kodetrans($in_kodetrans = NULL) {
        $sql = "select kode_trans as kode,"
                . "deskripsi_trans as deskripsi, "
                . "TOB "
                . "from TAB_KODE_TRANS "
                . "where "
                . "kode_trans='".$in_kodetrans."' "
                . "order by kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
        
    }
    public function Kolektor() {
        $sql = "select kode_kolektor as kode,nama_kolektor as deskripsi from tab_kode_kolektor order by kode_kolektor";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Tab_pro_nas_join($in_rek = '') {
        if(empty($in_rek)){
            return FALSE;                   
        }else{
        $sql = "select tabung.*, nasabah.nasabah_id,nasabah.nama_nasabah, nasabah.alamat,tab_produk.DESKRIPSI_PRODUK "
                . "from tabung, tab_produk, nasabah "
                . "where tabung.kode_produk=tab_produk.kode_produk "
                . "and tabung.nasabah_id=nasabah.nasabah_id "
                . "and no_rekening='".$in_rek."'";
        }
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Sandi_trans() {
        $sql = "SELECT sandi_kode,sandi_deskripsi FROM tab_sandi_trans ORDER BY sandi_kode";
        $query = $this->db->query($sql);
        return $query->result();        
    }
    public function Tab_nas($in_norek) {
        if(empty($in_norek)){
            return FALSE;                   
        }else{
            $sql = "select nasabah.nasabah_id,no_rekening, nama_nasabah,alamat, saldo_akhir "
                    . "from tabung,nasabah "
                    . "where tabung.nasabah_id=nasabah.nasabah_id "
                    . "and tabung.no_rekening='".$in_norek."' limit 1";
            $query = $this->db->query($sql);
            return $query->result();  
        }
    }  
    public function Tab_nas_all() {
            $sql = "select nasabah.nasabah_id,no_rekening, nama_nasabah,alamat, saldo_akhir "
                    . "from tabung,nasabah "
                    . "where tabung.nasabah_id=nasabah.nasabah_id";
            $query = $this->db->query($sql);
            return $query->result();  
    }  
    public function Tab_by_nasabah($in_nasabahid) {
        if(empty($in_nasabahid)){
            return FALSE;
        }
        $sql = "select nasabah_id,no_rekening from tabung where tabung.nasabah_id='".$in_nasabahid."' limit 1";
        $query = $this->db->query($sql);
        return $query->result();         
    }
    public function Tab_trans($in_genid) {
        if(empty($in_genid)){
            return FALSE;                   
        }else{
            $sql = "select tabtrans.*, tabung.kode_integrasi from tabtrans,tabung where tabtrans_id='".$in_genid."' and tabtrans.no_rekening=tabung.no_rekening";
            $query = $this->db->query($sql);
            return $query->result();  
        }
    }
    public function Tab_by_kodetrans($in_genid) {
        if(empty($in_genid)){
            return FALSE;                   
        }else{
            $sql = "SELECT tabtrans.tabtrans_id,tabtrans.tgl_trans,tabtrans.no_rekening,tabtrans.pokok,tabtrans.adm,tabtrans.KODE_TRANS,tabtrans.keterangan, tabung.SALDO_AKHIR,nasabah.nama_nasabah,nasabah.ALAMAT FROM tabtrans,tabung,nasabah WHERE tabtrans_id='".$in_genid."' AND tabtrans.no_rekening=tabung.no_rekening AND nasabah.NASABAH_ID=tabung.NASABAH_ID";
            $query = $this->db->query($sql);
            return $query->result();  
        }
    }
    public function Temp_tabtrans($in_tgl,$in_norek,$in_kwitansi,$in_saldo,$in_jsetoran,$in_jpenarikan,$in_sandi,$in_kolektor,$in_namap) {

        $sql = "INSERT INTO tabtrans_temp_e "        
                . "(TGL_TRANS, NO_REKENING, KUITANSI, POKOK, SETORAN, PENARIKAN, KODE_KOLEKTOR,KODE_KANTOR, NAMA_NASABAH,SANDI_TRANS,USERID,USERNAME) "
                . "VALUES "
                . "('".$in_tgl."','".$in_norek."','".$in_kwitansi."','".$in_saldo."','".$in_jsetoran."','".$in_jpenarikan."','".$in_kolektor."','','".$in_namap."','".$in_sandi."','".$this->session->userdata('user_id')."','".$this->session->userdata('username')."')";
    
         $query = $this->db->query($sql);
         return $query; 
    }
    public function List_temp_tabtrans($in_act_edit,$in_act_del) {
        
         $sql = "SELECT TABTRANS_ID,NO_REKENING, NAMA_NASABAH,POKOK, SETORAN,PENARIKAN "
                    . "from tabtrans_temp_e "
                    . "where USERNAME = '".$this->session->userdata('username')."'";
            $query = $this->db->query($sql)->result();
           // return $query->result();  
            $result = "";
            $jmlsetoran = 0;
            $jmlpenarikan  = 0;
            $no = 1;
            if($query){                
                foreach($query as $subq){
                    $tid            =    $subq->TABTRANS_ID;
                    $no_rek         =    $subq->NO_REKENING;
                    $nama_nasabah   =    $subq->NAMA_NASABAH;
                    $saldo          =    $subq->POKOK;
                    $setoran        =    $subq->SETORAN;
                    $penarikan      =    $subq->PENARIKAN;
                    
                    $result     .= "<tr>
                                    <td>".$no."</td>
                                    <td>".$no_rek."</td>
                                    <td>".$nama_nasabah."</td>
                                    <td align ='right'>".  $this->Rp($saldo)."</td>
                                    <td align ='right'>".  $this->Rp($setoran)."</td>
                                    <td align ='right'>".  $this->Rp($penarikan)."</td>                                        
                                    <td align='center'><a href='".$in_act_edit."/".$tid."/".$no_rek."' class='btn btn-default btn-xs' role='button'>Ubah</a> <a href='".$in_act_del."/".$tid."' class='btn btn-default btn-xs' role='button'>Hapus</a></td>
                                  </tr>";
                    
                    $jmlsetoran += $setoran;
                    $jmlpenarikan += $penarikan;
                    $no = $no + 1;
                    
                }
            }
            else{
                $result     = "<tr>
                                    <td colspan='7' align='center'>No record</td>                                    
                                  </tr>";
            }
            $resjml         = "<tr>
                                    <td colspan='4' align ='center'>Jumlah</td>
                                    <td align ='left' ><b>Rp. ".  $this->Rp($jmlsetoran)."</b></td>
                                    <td align ='left' ><b>Rp. ".  $this->Rp($jmlpenarikan)."</b></td>
                                        <td></td>
                                  </tr>"; 
            return $result.$resjml;
    }
    
    public function Del_temp_tabtrans_byid($in_nid) {        
        if($this->session->userdata('user_id') == "" || $this->session->userdata('username') == "")
        {
            redirect('tsimpanan/simpanan/kolektif');
        }
        if ( $this->ion_auth->in_group(array('webmaster', 'admin')) ){
            $query = $this->db->delete('tabtrans_temp_e', array('TABTRANS_ID' => $in_nid)); 
            return $query;
            
        }else{
            $query = $this->db->delete('tabtrans_temp_e', array('TABTRANS_ID' => $in_nid,'USERNAME' => $this->session->userdata('username'))); 
            return $query;
        }
        
    }
    public function Del_temp_tabtrans_cancel() {
        if($this->session->userdata('user_id') == "" || $this->session->userdata('username') == "")
        {
            redirect('tsimpanan/simpanan/kolektif');
        }
        $query = $this->db->delete('tabtrans_temp_e', array('USERNAME' => $this->session->userdata('username'))); 
        return $query;
        
    }
    public function Upd_temp_tabtrans($in_tgl,$in_norek,$in_kwitansi,$in_saldo,$in_jsetoran,$in_jpenarikan,$in_sandi,$in_kolektor,$in_namap,$in_tid) {
        if ($this->ion_auth->in_group(array('webmaster', 'admin')) ){ 
        $data = array(
            'KUITANSI'      => $in_kwitansi,
            'POKOK'         => $in_saldo,
            'SETORAN'       => $in_jsetoran,
            'PENARIKAN'     => $in_jpenarikan,
            'KODE_KOLEKTOR' => $in_kolektor,
            'NAMA_NASABAH'  => $in_namap,
            'SANDI_TRANS'   => $in_sandi
            );
            $this->db->set($data);
            $this->db->where('TABTRANS_ID',$in_tid);
            $query = $this->db->update('tabtrans_temp_e'); 
        }else{
            $data = array(
            'KUITANSI'      => $in_kwitansi,
            'POKOK'         => $in_saldo,
            'SETORAN'       => $in_jsetoran,
            'PENARIKAN'     => $in_jpenarikan,
            'KODE_KOLEKTOR' => $in_kolektor,
            'NAMA_NASABAH'  => $in_namap,
            'SANDI_TRANS'   => $in_sandi
                );
            $this->db->set($data);
            $this->db->where('TABTRANS_ID',$in_tid);
            $this->db->where('USERID',$this->session->userdata('user_id'));
            $this->db->where('USERNAME',$this->session->userdata('username'));
            $query = $this->db->update('tabtrans_temp_e'); 
        }  
         return $query; 
    }
    public function List_temp_tabtrans_byid($in_nid) {
        
         $sql = "SELECT TABTRANS_ID,NO_REKENING, NAMA_NASABAH,POKOK, SETORAN,PENARIKAN,SANDI_TRANS,KODE_KOLEKTOR "
                    . "from tabtrans_temp_e "
                    . "where TABTRANS_ID = '".$in_nid."' limit 1";
            $query = $this->db->query($sql);
            return $query->result();
    }
    public function List_temp_tabtrans_byuser() {
        if($this->session->userdata('username') || $this->session->userdata('user_id')){
            $sql = "SELECT TABTRANS_ID,NO_REKENING, NAMA_NASABAH,POKOK, SETORAN,PENARIKAN,SANDI_TRANS,KODE_KOLEKTOR,KUITANSI,USERID,TGL_TRANS "
                       . "from tabtrans_temp_e "
                       . "where USERNAME = '".$this->session->userdata('username')."' AND USERID = '".$this->session->userdata('user_id')."'";
               $query = $this->db->query($sql);
               return $query->result();
        }
    }
    
    public function Ins_Tbl($table,$data = array()) {        
        $query = $this->db->insert($table, $data);
        return $query;        
    }
    public function Ins_batch($table,$data = array()) {
        return $this->db->insert_batch($table, $data);
    }
    protected function Rp($value)
    {
        return number_format($value,2,",",".");
    }
    protected function Rp1($value)
    {
        return "Rp ".number_format($value,2,",",".");
    }
    public function Sum_1_2_taptrans($no_rek) {
        $this->db->select('SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0)) AS SETORAN, SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0)) AS PENARIKAN,'
                . 'SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,POKOK,0)) AS SETORAN_BUNGA, SUM(IF(FLOOR(MY_KODE_TRANS/100)=4,POKOK,0)) AS PENARIKAN_BUNGA');
        $this->db->where('NO_REKENING',$no_rek);
        $query = $this->db->get('tabtrans')->result();
        return $query;
    }
    public function Sum_3_4_taptrans($no_rek) {
        $this->db->select('SUM(IF(FLOOR(MY_KODE_TRANS/100)=3,POKOK,0)) AS SETORAN, SUM(IF(FLOOR(MY_KODE_TRANS/100)=4,POKOK,0)) AS PENARIKAN');
        $this->db->where('NO_REKENING',$no_rek);
        $this->db->from('tabtrans');
        $query = $this->db->get()->result();
        return $query;
    }
    public function Upd_tabung($norek, $in_array = array()) {
        $this->db->where('NO_REKENING', $norek);
        $query  = $this->db->update('tabung', $in_array);
        return $query;
    }
    public function Upd_transfer($incode, $in_array = array()) {
        $this->db->where('code_transfer', $incode);
        $query  = $this->db->update('transfer_ses_e', $in_array);
        return $query;
    }
    public function Upd_user($in_user, $in_array = array()) {
        $this->db->where('id', $in_user);
        $query  = $this->db->update('admin_users', $in_array);
        return $query;
    }
    public function Tab_byrek($in_rek) {
        $this->db->select('KODE_INTEGRASI');
        $this->db->where('NO_REKENING',$in_rek);
        $query = $this->db->get('TABUNG')->result();
        return $query;       
    }
    public function Kwitansi() {
        $sql = "SELECT MAX(KUITANSI) as KUITANSI FROM TABTRANS WHERE TGL_TRANS>='".$this->Star_date()."' AND TGL_TRANS<='".$this->last_date()."' AND KUITANSI<='Tab.[9999]' AND KUITANSI LIKE 'Tab.%%'";
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
    public function Tab_nas_group() {        
        $sql = "select nasabah.nama_nasabah,tabung.setoran_per_bln,tabung.no_rekening,tabung.saldo_akhir "
                . "from tabung,nasabah "
                . "where tabung.nasabah_id=nasabah.nasabah_id "
                . "order by tabung.no_rekening";
        $query = $this->db->query($sql)->result();
        $result = "";
        $jmlsaldo = 0;
        $jmlsetoran  = 0;
        $no = 1;
        if($query){                
            foreach($query as $subq){
                $no_rekening            =    $subq->no_rekening;
                $nama_nasabah           =    $subq->nama_nasabah;
                $saldo                  =    $subq->saldo_akhir;
                $setoranperbln          =    $subq->setoran_per_bln;


                $result     .= "<tr>
                                <td>".$no."</td>
                                <td>".$no_rekening."</td>
                                <td>".$nama_nasabah."</td>
                                <td align ='right'>".  $this->Rp($saldo)."</td>
                                <td align ='right'>".  $this->Rp($setoranperbln)."</td>                                        
                                </tr>";

                $jmlsaldo += $saldo;
                $jmlsetoran += $setoranperbln;
                $no = $no + 1;

            }
        }
        else{
            $result     = "<tr>
                                <td colspan='5' align='center'>No record</td>                                    
                              </tr>";
        }
        $resjml         = "<tr>
                                    <td colspan='3' align ='center'>Jumlah</td>
                                    <td align ='left' ><b>Rp. ".  $this->Rp($jmlsaldo)."</b></td>
                                    <td align ='left' ><b>Rp. ".  $this->Rp($jmlsetoran)."</b></td>
                                  </tr>";
        return $result.$resjml;
        //return $query->result();          
    }
    public function Gen_new_rek_id($kode_produk = null,$kode_kantor = null) {       
        if(empty($kode_kantor) || empty($kode_produk)){
            return FALSE;
        }
        $gen = $this->Exkdkantor($kode_kantor).".".$kode_produk;
        $this->db->select('MAX(NO_REKENING) as NO_REKENING');
        $this->db->where('NO_REKENING<="'.$gen.'.999999"');
        $this->db->where('NO_REKENING LIKE "'.$gen.'.%%"');
        $this->db->limit(1);
        $query = $this->db->get('TABUNG')->result();
        
        foreach ($query as $sub_query) {
            $no_rek_old =  $sub_query->NO_REKENING;
        }
        
        $ex     = explode(".", $no_rek_old);
                $angka1 =  $ex[0];
                $angka2 =  $ex[1];
                $angka3 =  $ex[2];
                
                if($angka3){
                    $angka3 = $angka3 + 1;
                    if($angka3  >=  999999){
                            return FALSE;
                    }
                    $jmlangka3      = strlen($angka3);
                    if($jmlangka3 <= 6){
                        $hasil  = 6 - $jmlangka3;
                        
                        for($i = 1;$i <= $hasil;$i++){
                            $angka3 =       "0".$angka3;
                        }
                    }
                    return $angka1.".".$angka2.".".$angka3;
                }
        return FALSE;
    }
    protected function Exkdkantor($kode) {
        	if(empty($kode)){
                    return "35";
                }
                $key = explode(".", $kode);	
                return $key[0];
    }
    public function Produk_by_kode_produk($in_kode) {
        $this->db->select('*');
        $this->db->where('KODE_PRODUK',$in_kode);
        $query = $this->db->get('tab_produk');
        return $query->result();
    }
    public function Tab_by_common($in_tabtrans) {
        if(!$in_tabtrans){
            return 0;
        }
        $this->db->select('COMMON_ID,NO_REKENING,MODUL_ID_SOURCE,TRANS_ID_SOURCE');
        $this->db->where('TABTRANS_ID',$in_tabtrans);
        $query = $this->db->get('TABTRANS')->result();
        return $query;
    }
    public function Tab_by_jml_common($in_common) {
        if(!$in_common){
            return 0;
        }
        $this->db->select('count(tabtrans_id) as jml');
        $this->db->where('common_id',$in_common);
        $query = $this->db->get('TABTRANS')->result();
        foreach ($query as $subqry) {
            $res_row = $subqry->jml;
        }        
        if (isset($res_row)){
            return $res_row;
        }else{
            return 0;
        }
    }
    public function Del_tabtrans($in_tabtransid) {
        if($this->session->userdata('user_id') == "" || $this->session->userdata('username') == "")
        {
            redirect('admin/bo_simpanan/browse_tabtrans/');
        }        
        $query = $this->db->delete('TABTRANS', array('TABTRANS_ID' => $in_tabtransid)); 
        return $query;                    
    }
    public function Tab_by_modul_source($in_tabtrans) {
        if(!$in_tabtrans){
            return 0;
        }
        $this->db->select('TABTRANS_ID');
        $this->db->where('MODUL_ID_SOURCE','TAB');
        $this->db->where('TRANS_ID_SOURCE',$in_tabtrans);
        $query = $this->db->get('TABTRANS')->result();
        foreach ($query as $subqry) {
            $res_row = $subqry->TABTRANS_ID;
        }        
        if (isset($res_row)){
            return $res_row;
        }else{
            return 0;
        }
    }
    public function Tiad_trf_ses($agentid,$codetransfer) {
        if(empty($agentid) || empty($codetransfer)){
            return FALSE;
        }
        $this->db->select('agent_id,dtm,rekening_sender,rekening_receiver,nominal,status,code_transfer,cost_adm,total,nama_receiver,kode_bank_receiver,id_transfer,d_acc_name,d_transaction_id,cost_adm_bmt,kode_bank_sender,nama_sender');
        $this->db->where('code_transfer',$codetransfer);
        $this->db->where('status','1');
        $this->db->where('agent_id',$agentid);
        $this->db->limit(1);
        $query = $this->db->get('transfer_ses_e');
        return $query->result();
    }
    public function Bank() {
        $this->db->select('kode_bank,nama_bank,biaya_adm');
        $this->db->where('status','1');
        $this->db->order_by('seq','asc');
        $query = $this->db->get('transfer_daftar_bank')->result();
        return $query;
    }
    public function Tabtrans_print($in_nomor_rekening) {
        if(empty($in_nomor_rekening)){
            return FALSE;
        }
        $sql = "SELECT TABTRANS_ID, TGL_TRANS AS Tanggal,"
                . "IF(FLOOR(MY_KODE_TRANS/100)=2,'D','K') AS DK,"
                . "IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0) AS Debet,"
                . "IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0) AS Kredit,"
                . "SANDI_TRANS as Sandi,"
                //. "REPLACE(REPLACE(REPLACE(FORMAT((@csum := IF(FLOOR(MY_KODE_TRANS/100)=2,@csum - POKOK,@csum + POKOK)), 0), '.', '|'), ',', '.'), '|', ',') AS SALDO,"
                . "(@csum := IF(FLOOR(MY_KODE_TRANS/100)=2,@csum - POKOK,@csum + POKOK)) AS SALDO, "
                . "MY_KODE_TRANS,print_buku FROM tabtrans,(SELECT @csum := 0) AS csums "
                . "WHERE no_rekening='".$in_nomor_rekening."' "
                //. "AND (print_buku IS NULL OR print_buku='T') "
                . "AND (FLOOR(MY_KODE_TRANS/100)<3) ORDER BY tgl_trans, tabtrans_id";  
                $query = $this->db->query($sql);
        return $query->result();
    }
    public function Tabtrans_print_id($in_nomor_rekening) {
        $sql = "SELECT MIN(TABTRANS_ID) as min,MAX(TABTRANS_ID) as max "
                . "FROM TABTRANS "
                . "WHERE NO_REKENING='".$in_nomor_rekening."' "
                . "AND (print_buku IS NULL OR print_buku='T') "
                . "AND (FLOOR(MY_KODE_TRANS/100)<3)";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Tabtrans_print_limit($in_nomor_rekening) {
        $sql = "SELECT MIN(id) AS MIN,MAX(id) AS MAX FROM (SELECT TABTRANS_ID AS id FROM TABTRANS WHERE NO_REKENING='".$in_nomor_rekening."' AND (print_buku IS NULL OR print_buku='T') AND (FLOOR(MY_KODE_TRANS/100)<3) ORDER BY TABTRANS_ID DESC LIMIT 5) AS id";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Tabtrans_print_last($in_nomor_rekening,$min,$max,$csum = 0) {
        if(empty($in_nomor_rekening)){
            return FALSE;
        }
        $sql = "SELECT TABTRANS_ID, TGL_TRANS AS Tanggal,"
                . "IF(FLOOR(MY_KODE_TRANS/100)=2,'D','K') AS DK,"
                . "IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0) AS Debet,"
                . "IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0) AS Kredit,"
                . "SANDI_TRANS as Sandi,"
                //. "REPLACE(REPLACE(REPLACE(FORMAT((@csum := IF(FLOOR(MY_KODE_TRANS/100)=2,@csum - POKOK,@csum + POKOK)), 0), '.', '|'), ',', '.'), '|', ',') AS SALDO,"
                . "(@csum := IF(FLOOR(MY_KODE_TRANS/100)=2,@csum - POKOK,@csum + POKOK)) AS SALDO, "
                . "MY_KODE_TRANS,print_buku FROM tabtrans,(SELECT @csum := ".$csum.") AS csums "
                . "WHERE no_rekening='".$in_nomor_rekening."' "
                . "AND TABTRANS_ID >= '".$min."' "
                . "AND TABTRANS_ID <= '".$max."' "
                //. "AND (print_buku IS NULL OR print_buku='T') "
                . "AND (FLOOR(MY_KODE_TRANS/100)<3) ORDER BY tgl_trans, tabtrans_id"; 
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Tabtrans_print_last_cutoff($in_nomor_rekening,$min,$max, $csum = 0) {
        if(empty($in_nomor_rekening)){
            return FALSE;
        }
        $sql = "SELECT TABTRANS_ID, TGL_TRANS AS Tanggal,"
                . "IF(FLOOR(MY_KODE_TRANS/100)=2,'D','K') AS DK,"
                . "IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0) AS Debet,"
                . "IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0) AS Kredit,"
                . "SANDI_TRANS as Sandi,"
                //. "REPLACE(REPLACE(REPLACE(FORMAT((@csum := IF(FLOOR(MY_KODE_TRANS/100)=2,@csum - POKOK,@csum + POKOK)), 0), '.', '|'), ',', '.'), '|', ',') AS SALDO,"
                . "(@csum := IF(FLOOR(MY_KODE_TRANS/100)=2,@csum - POKOK,@csum + POKOK)) AS SALDO, "
                . "MY_KODE_TRANS,print_buku FROM tabtrans,(SELECT @csum := ".$csum.") AS csums "
                . "WHERE no_rekening='".$in_nomor_rekening."' "
                . "AND TABTRANS_ID >= '".$min."' "
                . "AND TABTRANS_ID < '".$max."' "
                //. "AND (print_buku IS NULL OR print_buku='T') "
                . "AND (FLOOR(MY_KODE_TRANS/100)<3) ORDER BY tgl_trans, tabtrans_id"; 
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Tabtrans_print_saldo_awal($in_nomor_rekening = '', $gmin = '') {
        if(empty($in_nomor_rekening)):
            return false;
        endif;
        $sql = "SELECT SUM(IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0)) - SUM(IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0)) AS total_saldo FROM tabtrans 
                WHERE no_rekening='".$in_nomor_rekening."' AND (FLOOR(MY_KODE_TRANS/100)<3) and TABTRANS_ID < '".$gmin."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Bukutab_by_rek($in_nomor_rekening) {
        $this->db->select('BARIS_BUKU');
        $this->db->where('NO_REKENING',$in_nomor_rekening);
        $this->db->limit('1');
        $query = $this->db->get('TABUNG');
        return $query->result();
        //SELECT BARIS_BUKU FROM TABUNG WHERE NO_REKENING='35.01.000002'
    }

    public function Bukutab_upd($dta = null) {
        $resp = $this->db->update_batch('tabtrans',$dta,'TABTRANS_ID');
        return $resp;
    }
    public function Lastprint($in_param,$norek) {
        if(empty($in_param) || empty($norek)){
            return FALSE;
        }
        $this->db->select('*');
        $this->db->where('rek',$norek);
        $data = $this->db->get('tab_print')->result();
        if($data){
            $this->db->where('rek', $norek);
            $res = $this->db->update('tab_print', $in_param);           
        }else{
            $res = $this->db->insert('tab_print', $in_param);
        }
        return $res;
    }
    public function Print_total_noprint($in_rekening = '') {
        if(empty($in_rekening)){
            return FALSE;
        }
        $sql    = "SELECT COUNT(*) AS total FROM TABTRANS WHERE NO_REKENING='".$in_rekening."' AND (print_buku IS NULL OR print_buku='T') AND (FLOOR(MY_KODE_TRANS/100)<3)";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Ceklastprint($in_norek) {
        $this->db->where('rek',$in_norek);
        $data = $this->db->get('tab_print')->result();
        return $data;
    }
    public function Lap_saldo_awal($tgl) {
        if(empty($tgl)){
            return FALSE;
        }
        $sql = "select (sum(if((floor(my_kode_trans/100)=1),pokok,0)) - "
                . "sum(if((floor(my_kode_trans/100)=2),pokok,0))) as saldo_awal "
                . "from tabtrans where tgl_trans <'".$tgl."'"; 
        $query = $this->db->query($sql);
        return $query->result();        
    }
    public function Lap_mutasi_periode($in_no_rekening = '',$periode = '',$saldo_awal = '') {
        
        if($saldo_awal == ''){
            $saldo_awal = 0;
        }
        if($periode == '1bulan'){
            $period = ' 1 MONTH ';
        }elseif($periode == '1minggu'){
            $period = ' 1 WEEK ';
        }else{
            $period = ' 1 DAY ';
        }
        $sql = "SELECT KODE_TRANS, TGL_TRANS AS Tanggal, "
                . "IF(FLOOR(MY_KODE_TRANS/100)=2,'D','K') AS DK, "
                . "IF(FLOOR(MY_KODE_TRANS/100)=1,POKOK,0) AS Debit,"
                . "IF(FLOOR(MY_KODE_TRANS/100)=2,POKOK,0) AS Kredit,SANDI_TRANS AS Sandi, "
                . "(@csum := IF(FLOOR(MY_KODE_TRANS/100)=2,@csum - POKOK,@csum + POKOK)) AS SALDO, "
                . "MY_KODE_TRANS,KETERANGAN,kode_kolektor "
                . "FROM tabtrans,(SELECT @csum := ".$saldo_awal.") AS csums "
                . "WHERE no_rekening='".$in_no_rekening."' "
                . "AND TGL_TRANS >= CURDATE()- INTERVAL ".$period." "
                . "AND (FLOOR(MY_KODE_TRANS/100)<3) ORDER BY tgl_trans, tabtrans_id"; 
        $query = $this->db->query($sql);
        return $query->result();        
    }
    public function Lap_mutasi_periode_saldo_awal($in_no_rekening = '',$periode = '') {
        if(empty($in_no_rekening)){
            return FALSE;
        }
        if($periode == '1bulan'){
            $period = ' 1 MONTH ';
        }elseif($periode == '1minggu'){
            $period = ' 1 WEEK ';
        }else{
            $period = ' 1 DAY ';
        }
        $sql = " SELECT (SUM(IF((FLOOR(my_kode_trans/100)=1),pokok,0)) - SUM(IF((FLOOR(my_kode_trans/100)=2),pokok,0))) AS saldo_awal "
                . "FROM tabtrans WHERE no_rekening='".$in_no_rekening."' AND TGL_TRANS < CURDATE() - INTERVAL ".$period." 
 AND (FLOOR(MY_KODE_TRANS/100)<3)"; 
        $query = $this->db->query($sql);
        return $query->result();        
    }
    public function Lap_mutasi($tgl_fr = '' ,$tgl_to = '',$kdtransaksi = '',$kdintegrasi = '' ,$kdproduk = '',$ao = '',$wiayah = '',$profesi = '',$kdkantor = '',$kdkolek = '') {
        if(empty($tgl_fr) || empty($tgl_to) ){
            return FALSE;
        }
        $sql = "SELECT nama_nasabah, tabtrans_id,tabung.no_rekening, tabung.kode_produk, tab_produk.kode_produk,
            deskripsi_produk, tob,tabtrans.tgl_trans, tabtrans.kode_trans, tabtrans.kuitansi, 
            IF(FLOOR(my_kode_trans/100)=1,pokok,0) AS setoran, IF(FLOOR(my_kode_trans/100)=2,pokok,0) AS penarikan, 
            IF(FLOOR(MY_KODE_TRANS/100)=2,'D','K') AS DK
            FROM nasabah, tab_produk,tabung,tabtrans WHERE tabung.no_rekening=tabtrans.no_rekening 
            AND nasabah.nasabah_id=tabung.nasabah_id AND tabung.kode_produk=tab_produk.kode_produk 
            AND tgl_trans>='".$tgl_fr."' AND tgl_trans<='".$tgl_to."'";
                if(strtolower($kdtransaksi) == "all"){}else{                    
                    $sql .= "AND '".$kdtransaksi."' LIKE CONCAT(CONCAT('%',tabtrans.kode_trans),'%') ";                }
                if(strtolower($kdproduk) == "all"){}else{
                    $sql .= "AND tabung.kode_produk='".$kdproduk."' ";                }
                if(strtolower($kdintegrasi) == "all"){}else{
                    $sql .= "AND tabung.kode_integrasi='".$kdintegrasi."' ";                }
                if(strtolower($ao) == "all"){}else{
                    $sql .= "AND tabung.kode_group1='".$ao."' ";                }
                if(strtolower($kdkantor) == "all"){}else{
                    $sql .= "AND tabung.kode_kantor='".$kdkantor."' ";                }
                if(strtolower($kdkolek) == "all"){}else{
                    $sql .= "AND tabtrans.kode_kolektor='".$kdkolek."' ";                }
                if(strtolower($wiayah) == "all"){}else{
                    $sql .= "AND tabung.kode_group2='".$wiayah."' ";                }
                if(strtolower($profesi) == "all"){}else{
                    $sql .= "AND tabung.kode_group3='".$profesi."' ";               }
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Lap_mutasi_per_rek($tgl_fr = '' ,$tgl_to = '',$kdtransaksi = '',$kdintegrasi = '' ,$kdproduk = '',$ao = '',$wiayah = '',$profesi = '',$kdkantor = '',$kdkolek = '') {
        if(empty($tgl_fr) || empty($tgl_to) ){
            return FALSE;
        }                                
        $sql = "select nama_nasabah, tabtrans_id,tabung.no_rekening, tabung.kode_produk, tab_produk.kode_produk, deskripsi_produk, tob,
			 tabtrans.tgl_trans, tabtrans.kode_trans, tabtrans.kuitansi, if(floor(my_kode_trans/100)=1,pokok,0) as setoran, 
			 if(floor(my_kode_trans/100)=2,pokok,0) as penarikan
            FROM nasabah, tab_produk,tabung,tabtrans WHERE tabung.no_rekening=tabtrans.no_rekening 
            AND nasabah.nasabah_id=tabung.nasabah_id AND tabung.kode_produk=tab_produk.kode_produk 
            AND tgl_trans>='".$tgl_fr."' AND tgl_trans<='".$tgl_to."'";
                if(strtolower($kdtransaksi) == "all"){
                    $sql .= "and '100_101_102_103_104_110_111_112_120_121_200_201_202_203_204_205_210_211_220_221_222' like concat(concat('%',tabtrans.kode_trans),'%')";
                }else{                    
                    $sql .= "AND '".$kdtransaksi."' LIKE CONCAT(CONCAT('%',tabtrans.kode_trans),'%') ";                }
                if(strtolower($kdproduk) == "all"){}else{
                    $sql .= "AND tabung.kode_produk='".$kdproduk."' ";                }
                if(strtolower($kdintegrasi) == "all"){}else{
                    $sql .= "AND tabung.kode_integrasi='".$kdintegrasi."' ";                }
                if(strtolower($ao) == "all"){}else{
                    $sql .= "AND tabung.kode_group1='".$ao."' ";                }
                if(strtolower($kdkantor) == "all"){}else{
                    $sql .= "AND tabung.kode_kantor='".$kdkantor."' ";                }
                if(strtolower($kdkolek) == "all"){}else{
                    $sql .= "AND tabtrans.kode_kolektor='".$kdkolek."' ";                }
                if(strtolower($wiayah) == "all"){}else{
                    $sql .= "AND tabung.kode_group2='".$wiayah."' ";                }
                if(strtolower($profesi) == "all"){}else{
                    $sql .= "AND tabung.kode_group3='".$profesi."' ";               }
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Lap_anggaran_basil($in_produk = '',$in_kantor = '') {
        $sql = "select nama_nasabah, alamat, no_rekening, kode_produk, tabung.kode_kantor,saldo_efektif_bln_ini,"
                . "bunga_bln_ini, pajak_bln_ini,adm_bln_ini "
                . "from nasabah, tabung "
                . "where nasabah.nasabah_id=tabung.nasabah_id ";
                if(strtolower($in_kantor) == "all" || strtolower($in_kantor) == ""){}
                else{
                    $sql .= "and tabung.kode_kantor like '".$in_kantor."' ";
                }
                    $sql .= "and (bunga_bln_ini>0 or pajak_bln_ini>0 or adm_bln_ini>0) ";
                if(strtolower($in_produk) == "all" || strtolower($in_produk) == ""){}
                else{
                    $sql .= "and kode_produk like '".$in_produk."' ";
                }
                $sql .= "order by no_rekening"; 
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Lap_ob_basil_pajak_adm($in_kode_integrasi = '',$in_kantor = '',$tanggal,$kdbasil) {
        $sql = "select tabung.no_rekening,nama_nasabah,alamat, "
                . "sum(if(my_kode_trans=100 and kode_trans='101',pokok,0)) as bunga, "
                . "sum(if(my_kode_trans=200 and kode_trans='201',pokok,0)) as pajak, "
                . "sum(if(my_kode_trans=200 and kode_trans='206',pokok,0)) as admin "
                . "from tabung,tabtrans,nasabah "
                . "where tabung.no_rekening=tabtrans.no_rekening "
                . "and tabung.nasabah_id=nasabah.nasabah_id "
                . "and tgl_trans='".$tanggal."' ";
                if(strtolower($in_kode_integrasi == 'all' || strtolower($in_kode_integrasi) == '')){}
                else{
                    $sql .= "and '01' like concat(concat('%',tabung.kode_integrasi),'%')  ";
                }
                if(strtolower($in_kantor == 'all' || strtolower($in_kantor) == '')){}
                else{
                    $sql .= "and '35' like concat(concat('%',tabung.kode_kantor),'%') ";
                }
                    
                if(strtolower($kdbasil == 'all' || strtolower($kdbasil) == '')){
                    $sql .= "group by no_rekening having (bunga>0 or pajak>0 or admin>0) order by no_rekening";
                }
                else{
                    $sql .= "group by no_rekening having ".$kdbasil." order by no_rekening";
                }
        $query = $this->db->query($sql);
        return $query->result();      
        
        //select tabung.no_rekening,nama_nasabah,alamat, sum(if(my_kode_trans=100 and kode_trans="101",pokok,0)) as bunga, sum(if(my_kode_trans=200 and kode_trans="201",pokok,0)) as pajak, sum(if(my_kode_trans=200 and kode_trans="206",pokok,0)) as admin from tabung,tabtrans,nasabah where tabung.no_rekening=tabtrans.no_rekening and tabung.nasabah_id=nasabah.nasabah_id and tgl_trans='2016/10/20'  and "01" like concat(concat("%",tabung.kode_integrasi),"%")  and "35" like concat(concat("%",tabung.kode_kantor),"%") group by no_rekening having admin>0 order by no_rekening
        
    }
    public function Del_by_tabtrans($in_tabtransid = NULL) {                
        $query = $this->db->delete('TABTRANS', array('TABTRANS_ID' => $in_tabtransid)); 
        return $query;                    
    }
    public function Trx_agent_history($in_userid = '000',$in_tgl_fr = '0000-00-00',$in_tgl_to = '0000-00-00') {
        $sql = "SELECT tbl_history_r.transaksi_id, tbl_history_r.jenis_transaksi, tbl_history_r.poin, "
                . "tabtrans.MY_KODE_TRANS ,tabtrans.TABTRANS_ID,tabtrans.NO_REKENING, tabtrans.POKOK, "
                . "tbl_history_r.tanggal FROM tabtrans, tbl_history_r WHERE tbl_history_r.kode_transaksi = 'TAB' "
                . "AND tbl_history_r.agent_id='".$in_userid."' AND tbl_history_r.transaksi_id=tabtrans.TABTRANS_ID AND "
                . "tbl_history_r.tanggal >= '".$in_tgl_fr."' AND tbl_history_r.tanggal <='".$in_tgl_to."' GROUP BY "
                . "tbl_history_r.transaksi_id, tbl_history_r.jenis_transaksi,tbl_history_r.poin, "
                . "tabtrans.MY_KODE_TRANS ,tabtrans.TABTRANS_ID, tabtrans.NO_REKENING, tabtrans.POKOK,"
                . "tbl_history_r.tanggal";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Kode_trans_trx_agent() {
        $sql = "SELECT KODE_TRANS,DESKRIPSI_TRANS FROM	sejahtera.tab_kode_trans WHERE KODE_TRANS NOT IN (SELECT kode_transaksi	FROM tbl_transaksi WHERE jenis_transaksi='TAB')";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_per_day_by_agent($in_agentid = '', $in_tgl = '') {
        if(empty($in_agentid)){            return FALSE;        }
        if(empty($in_tgl)){            return FALSE;        }              
        $sql = "SELECT tgl_trans,kode_trans,no_rekening, IF(MY_KODE_TRANS=100,POKOK,0) AS setor, "
                . "IF(MY_KODE_TRANS=200,POKOK,0) AS tarik,keterangan FROM TABTRANS WHERE userid='".$in_agentid."' "
                . "AND DATE_FORMAT(TGL_TRANS,'%m/%d/%Y') = '".$in_tgl."' "
//                . "AND TGL_TRANS<='2017-02-30' "
                . "ORDER BY tabtrans_id,tgl_trans";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_per_day_by_agent_sum($in_agentid = '', $in_tgl = '') {
        if(empty($in_agentid)){            return FALSE;        }
        if(empty($in_tgl)){            return FALSE;        }              
        $sql = "SELECT (SUM(IF((FLOOR(my_kode_trans/100)=1),pokok,0)) - SUM(IF((FLOOR(my_kode_trans/100)=2),pokok,0))) AS saldo,SUM(IF((FLOOR(my_kode_trans/100)=1),pokok,0)) AS setoran, SUM(IF((FLOOR(my_kode_trans/100)=2),pokok,0)) AS tarikan "
                . "FROM TABTRANS WHERE userid='".$in_agentid."' "
                . "AND DATE_FORMAT(TGL_TRANS,'%m/%d/%Y') = '".$in_tgl."' "
//                . "AND TGL_TRANS<='2017-02-30' "
                . "ORDER BY tabtrans_id,tgl_trans";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_per_month_sum($in_tgl_from = '0000-00-00', $in_tgl_to = '0000-00-00') {
        if(empty($in_tgl_from)){            return FALSE;        }
        if(empty($in_tgl_to)){            return FALSE;        }              
        $sql = "SELECT kode_trans,(SELECT DESKRIPSI_TRANS FROM tab_kode_trans WHERE KODE_TRANS = `tabtrans`.`KODE_TRANS`) AS `desc_kode_trans`,SUM(pokok) AS nominal,COUNT(*) AS total FROM TABTRANS WHERE TGL_TRANS >= '".$in_tgl_from."' AND TGL_TRANS <= '".$in_tgl_to."' GROUP BY kode_trans ORDER BY kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Saldo_upd_by_rekening($in_nomor_rekening = '') {
        if(empty($in_nomor_rekening)){
            return false;
        }
            $sql = "call TabHitungSaldo(?)";
            $prm = array($in_nomor_rekening);
       
            $query = $this->db->query($sql,$prm);
            //$this->db->free_db_resource();
        return $query;      
    }
    public function Perkolektor($dtm_from = '',$dtm_to  = '') {
        if(empty($dtm_from)){
            return false;
        }
        if(empty($dtm_to)){
            return false;
        }
        $sql    = "SELECT `tab_kode_kolektor`.`KODE_KOLEKTOR` AS `KODE_KOLEKTOR`,"
                . "UCASE(`tab_kode_kolektor`.`NAMA_KOLEKTOR`) AS `nama_kolektor`,"
                . "SUM(IF((`tabtrans`.`MY_KODE_TRANS` = 100),`tabtrans`.`POKOK`,0)) AS `tabungan_pokok`,"
                . "SUM(IF((`tabtrans`.`MY_KODE_TRANS` = 200),`tabtrans`.`POKOK`,0)) AS `penarikan_pokok`,"
                . "(SUM(IF((`tabtrans`.`MY_KODE_TRANS` = 200),`tabtrans`.`POKOK`,0)) - SUM(IF((`tabtrans`.`MY_KODE_TRANS` = 100),`tabtrans`.`POKOK`,0))) AS `stok` "
                . "FROM (((`tabung` JOIN `tabtrans`) JOIN `nasabah`) JOIN `tab_kode_kolektor`) "
                . "WHERE ((`tabung`.`NO_REKENING` = `tabtrans`.`NO_REKENING`) "
                . "AND (`tabung`.`NASABAH_ID` = `nasabah`.`NASABAH_ID`) "
                . "AND (`tabtrans`.`KODE_KOLEKTOR` = `tab_kode_kolektor`.`KODE_KOLEKTOR`) "
                . "AND (`tabtrans`.`TGL_TRANS` >= '".$dtm_from."') AND (`tabtrans`.`TGL_TRANS` <= '".$dtm_to."') "
                . "AND ('100_101_102_103_104_105_110_111_112_120_121_122_125_126_200_201_202_203_204_205_206_210_211_220_221_222_225_226_227_228_229_230_231_232_233_234_235' LIKE CONCAT(CONCAT('%',`tabtrans`.`KODE_TRANS`),'%'))) "
                . "GROUP BY `tab_kode_kolektor`.`KODE_KOLEKTOR`";
        $query  = $this->db->query($sql);
        return $query->result();
    } 
    public function PerAgent($agentid = '',$dtm_tday = '0000-00-00') {
        if(empty($dtm_tday)){
            return false;
        }if(empty($agentid)){
            return false;
        }
        $sql    = "SELECT tabtrans.kode_trans,(SELECT DESKRIPSI_TRANS FROM tab_kode_trans WHERE KODE_TRANS = `tabtrans`.`KODE_TRANS`) AS `desc_kode_trans`,SUM(IF((`tabtrans`.`MY_KODE_TRANS` = 100),`tabtrans`.`POKOK`,0)) AS `tabungan_pokok`,"
                . "SUM(IF((`tabtrans`.`MY_KODE_TRANS` = 200),`tabtrans`.`POKOK`,0)) AS `penarikan_pokok`,"
                . "(SUM(IF((`tabtrans`.`MY_KODE_TRANS` = 100),`tabtrans`.`POKOK`,0)) - SUM(IF((`tabtrans`.`MY_KODE_TRANS` = 200),`tabtrans`.`POKOK`,0))) AS `stok`,"
                . "COUNT(*) AS total "
                . "FROM ((`tabung` JOIN `tabtrans`) JOIN `nasabah`) "
                . "WHERE ((`tabung`.`NO_REKENING` = `tabtrans`.`NO_REKENING`) "
                . "AND (`tabung`.`NASABAH_ID` = `nasabah`.`NASABAH_ID`) "
                . "AND ('100_200' LIKE CONCAT(CONCAT('%',`tabtrans`.`KODE_TRANS`),'%'))) "
                . "AND `tabtrans`.`USERID` = '".$agentid."' "
               // . "AND (`tabtrans`.`TGL_TRANS` >= '".$dtm_from."') "
                . "AND (`tabtrans`.`TGL_TRANS` = '".$dtm_tday."') GROUP BY tabtrans.KODE_TRANS";
        $query  = $this->db->query($sql);
        return $query->result();
    } 
    public function Tab_commerce($in_rkning = '',$in_customerid = '',$in_agenid = '',$topup_total = '',$code_trans = '',$mycode_trans = '',$gen_id_COMTRANS_ID = '',$kodeperkas_default = '',$status_trans = 'setor') {
        
        if(empty($in_rkning) || empty($topup_total)){
            return false;            
        }
        $in_gord                        = 'D';
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_desc_trans                  = $this->_Kodetrans_by_desc($code_trans);
        $in_tob                         = $this->_Kodetrans_by_tob($mycode_trans);
        $in_keterangan                  = $in_desc_trans." ".$in_customerid." ".  $this->Rp($topup_total);
        
        $in_KUITANSI    = $this->_Kuitansi();
        
        if(trim(strtolower($status_trans)) == "setor"){//nabung
            $in_DEBET100   = $topup_total ;
            $in_KREDIT100  = 0 ;
            $in_DEBET200   = 0;
            $in_KREDIT200  = $topup_total ;
        }elseif(trim(strtolower($status_trans)) == "tarik"){ // tarikan
            $in_DEBET100   = 0 ;
            $in_KREDIT100  = $topup_total;
            $in_DEBET200   = $topup_total ;
            $in_KREDIT200  = 0;
        }else{
            $in_DEBET100   = 0 ;
            $in_KREDIT100  = $topup_total;
            $in_DEBET200   = $topup_total ;
            $in_KREDIT200  = 0;
        }      
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>$mycode_trans, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$topup_total,
            'ADM'               =>'',
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>$code_trans,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            //$this->response($arr_data);
            
        $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
        
         if(!$res_ins){
            //$data = array('status' => FALSE,'error' => 'error db', 'data' => '');
             return false;
         }
        $this->Saldo_upd_by_rekening($in_rkning); 
        
        $res_call_tab   = $this->Tab_byrek($in_rkning);
        if($res_call_tab){
            foreach($res_call_tab as $sub_call_tab){
                $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
            }
        }else{
            $in_kode_integrasi = '';
        }

        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agenid);
        if($res_call_kode_perk_kas){
            foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS ?: '10101';
            }
        }else{
            //bikin anomali
            //20170829 debit pulsa masuk ke kas kasanah, kredit ke simara dll
            $in_kode_perk_kas = '10101';
        }
        $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            //sender
        $res_call_tab_integrasi_by_kd = $this->Integrasi_by_kd_int($in_kode_integrasi);
        if($res_call_tab_integrasi_by_kd){
            foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS ?: '';
                $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK ?: '';
                $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM ?: '';                                                      
            }
        }else{
                $in_kode_perk_kas_in_integrasi          =  '';
                $in_kode_perk_hutang_pokok_in_integrasi =  '';
                $in_kode_perk_pend_adm_in_integrasi     =  ''; 
        }
        //receiver
        
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        if($res_call_perk_kode_all){
            foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
                $count_as_total = $sub_call_perk_kode_all->total ?: 0;
            }        
        }else{
            $count_as_total = '';
        }
        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
        if($res_call_tab_keyvalue){
            foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
                $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE ?: 'TAB';
            }
        }else{
            $res_kode_jurnal = 'TAB';
        }
        $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                'USERID'            =>  $in_agenid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            );
        $ar_trans_detail = array(
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
//                'DEBET'         =>  0, 
//                'KREDIT'        =>  $topup_total   
                'DEBET'         =>  $in_DEBET200, 
                'KREDIT'        =>  $in_KREDIT200
            ),array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
//                'DEBET'         =>  $topup_total, 
//                'KREDIT'        =>  0
                'DEBET'         =>  $in_DEBET100, 
                'KREDIT'        =>  $in_KREDIT100
            )
        );      
        

        $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
        if($res_run){                  
            return true;            
        }else{            
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
            return false;
        }
        
    }
    protected function _Kodetrans_by_desc($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Kodetrans_by_kodetrans($in_kd_trans);
        if($kodetrans_desc){
            foreach($kodetrans_desc as $res_desc){
                    $out_desc          = $res_desc->deskripsi;
                }
        }else{
            $out_desc   = "";
        }
        return $out_desc;
    }
    protected function _Kodetrans_by_tob($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Kodetrans_by_kodetrans($in_kd_trans);
        if($kodetrans_desc){
            foreach($kodetrans_desc as $res_desc){
                    $out_tob          = $res_desc->TOB;
                }
        }else{
            $out_tob   = NULL;
        }
        return $out_tob;
    }
    function Acc_by_partnerid($name = NULL) {
        if(empty($name)){
            return false;
        }
        $no_rekening = "";
        $sql  = "SELECT nasabahid,no_rekening,nama_rekening FROM tab_rek_partner WHERE patnerid = '".$name."'";
        $query = $this->db->query($sql)->result();
        if($query){
            foreach($query as $res_desc){
                    $no_rekening          = $res_desc->no_rekening;
                }
        }else{
            $no_rekening   = "";
        }
        return $no_rekening;
    }
    protected function _Kuitansi() {
        $result_kwitn = $this->Kwitansi();
        
            if($result_kwitn){
                foreach($result_kwitn as $res_kwi){
                    $out_nokwi          = $res_kwi->KUITANSI;
                }
            }else{
                $out_nokwi = "";
            }
            if(empty($out_nokwi)){
                $out_nokwi = date('md')."001";
            }else{                
                $out_nokwi = increment_string($out_nokwi,'.');
            }
            return $out_nokwi;
    }
    protected function _Tgl_hari_ini(){
        return Date('Y-m-d');
    }
    public function Upd_comtrans($in_notrxid = '', $in_array = array()) {
        if(empty($in_notrxid)){
            return false;
        }
        $this->db->where('COMTRANS_ID', $in_notrxid);
        $query  = $this->db->update('COMTRANS', $in_array);
        return $query;
    }
    public function Rek_by_nasabahid($in_nasabahid = '') {
        //for nasabahid account - list rekening
        
        $sql = "SELECT NASABAH_ID,NO_REKENING FROM tabung "
                . "WHERE NASABAH_ID =".$this->db->escape($in_nasabahid)." LIMIT 1";
        $query  = $this->db->query($sql);
        return $query->result();
    }
    public function Trf_ses_upd($code_transfer) {
        $data = array(
            'status' => '2',
            'last_upd' => date('Y-m-d H:i:s'),        
        );

        $this->db->where('code_transfer', $code_transfer);
        return $this->db->update('transfer_ses_e', $data);        
    }
    
}
