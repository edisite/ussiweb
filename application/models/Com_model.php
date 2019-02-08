<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Com
 *
 * @author edisite
 */

$arrcode['COM_PULSA_CODE_AGENT']      = '101';
$arrcode['COM_PULSA_CODE_NASABAH']    = '102';
$arrcode['COM_PULSA_MYCODE']          = '100';


class Com_model extends MY_Model {
    //put your code here
    protected $res_call_kode_perk_kas;    
    private $in_kode_perk_kas,$in_nama_perk,$in_gord,$in_kode_perk_com_in_integrasi,$in_kode_perk_kas_in_integrasi;           

        //public $arrres = array();
    
    public function __construct() {
        parent::__construct();
        
    }
    public function Kodetrans_by_kodetrans($in_kodetrans = NULL) {
        $sql = "select kode_trans as kode,"
                . "deskripsi_trans as deskripsi, "
                . "TOB "
                . "from COM_KODE_TRANS "
                . "where "
                . "kode_trans='".$in_kodetrans."' "
                . "order by kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
        
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
    public function Integrasi_by_kd_int($nokode) {
        $sql = "SELECT KODE_PERK_KAS,KODE_PERK_COM from COM_INTEGRASI WHERE KODE_INTEGRASI='".$nokode."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function Perk($in_agenid = '', $in_kode_integrasi = '', $kodeperkas_default = '406') {
        if(empty($in_agenid) || empty($in_kode_integrasi)){
            return false;
        }
        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agenid);
        if($res_call_kode_perk_kas){
            foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
            }
        }else{
            $in_kode_perk_kas = $kodeperkas_default;
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

                $in_kode_perk_com_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_COM;                                                   
                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;                                                   
            }
        }else{
            $in_kode_perk_com_in_integrasi      = '';
            $in_kode_perk_kas_in_integrasi      = '';
        }
        $this->load->model('Sysmysysid_model');
        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_COMMERCE');
        if($res_call_tab_keyvalue){
            foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
                $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE;
            }
        }else{
            $res_kode_jurnal = "COM";
        }
        
        $arr_res = array();
        $arr_res['kodeperk_kas']    = $in_kode_perk_kas ?: ''; 
        $arr_res['namaperk']        = $in_nama_perk ?: '';
        $arr_res['gord']            = $in_gord ?: '';
        $arr_res['kodeperk_com']    = $in_kode_perk_com_in_integrasi ?: '';  
        $arr_res['kode_jurnal']     = $res_kode_jurnal ?: '';   
        return array($arr_res);    
    }
    public function Render_json($data)
    {
//        $this->output
//                ->set_status_header($code)
//                ->set_content_type('application/json')
//                ->set_output(json_encode($data));
//        global $OUT;
//        $OUT->_display();
        //exit;
        echo json_encode($data);
    }
    public function Kwitansi() {
        $sql = "SELECT MAX(KUITANSI) as KUITANSI FROM COMTRANS WHERE TGL_TRANS>='".$this->Star_date()."' AND TGL_TRANS<='".$this->last_date()."'";
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
    public function Kodetrans_by_desc_kind($message = NULL) {
        $sql = "select kode_trans as kode,"
                . "deskripsi_trans as deskripsi, "
                . "tob "
                . "from COM_KODE_TRANS "
                . "where "
                . "deskripsi_kind='".strtoupper($message)."' "
                . "order by kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
        
    }
    public function Kodetrans() {
        $sql = "select kode_trans as kode,"
                . "deskripsi_trans as deskripsi "
                . "from com_kode_trans "
                . "where '100_101_102_200' like concat(concat('%',kode_trans),'%') "
                . "order by kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
        
    }
    public function Integrasi() {
        $sql = "select kode_integrasi as kode,deskripsi_integrasi as deskripsi from com_integrasi order by kode_integrasi";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Produk() {
        $sql = "select kode_produk as kode,deskripsi_produk as deskripsi from com_pulsa order by kode_produk";
        $query = $this->db->query($sql);
        return $query->result();  
    }
     public function Lap_mutasi($tgl_fr = '' ,$tgl_to = '',$kdtransaksi = '',$kdintegrasi = '' ,
             $kdkantor = '',$kdkolek = '') {
         
         //$tgl_fr,$tgl_to,$in_kode_trans,$in_kode_integrasi,$kdkantor,$in_kode_agent
        if(empty($tgl_fr) || empty($tgl_to) ){
            return FALSE;
        }                                
        $sql = "SELECT COMTRANS_ID, TGL_TRANS,NOCUSTOMER,COMTYPE,KODE_TRANS,MY_KODE_TRANS,POKOK, ADM, NO_REKENING, KUITANSI, USERID, KODE_KANTOR FROM COMTRANS WHERE TGL_TRANS>='".$tgl_fr."' AND TGL_TRANS<='".$tgl_to."' ";
                if(strtolower($kdtransaksi) == "all"){
                    $sql .= "AND '100_101_102_200' like concat(concat('%',COMTRANS.KODE_TRANS),'%')";
                }else{                    
                    $sql .= "AND '".$kdtransaksi."' LIKE CONCAT(CONCAT('%',COMTRANS.KODE_TRANS),'%') ";                }
                if(strtolower($kdkantor) == "all"){}else{
                    $sql .= "AND COMTRANS.KODE_KANTOR='".$kdkantor."' ";                }
                if(strtolower($kdkolek) == "all"){}else{
                    $sql .= "AND COMTRANS.USERID='".$kdkolek."' ";                }
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Peragent($agentid = '',$dtm_tday = '0000-00-00') {
        if(empty($dtm_tday)){
            return false;
        }if(empty($agentid)){
            return false;
        }
        $sql = "SELECT `comtrans`.`comtype`,`comtrans`.KODE_TRANS,`com_kode_trans`.DESKRIPSI_TRANS,"
                . "SUM(IF((`comtrans`.`pokok` IS NOT NULL),`comtrans`.`POKOK`,0)) AS `subjumlah`,"
                . "COUNT(*) AS total FROM (`comtrans` JOIN `com_kode_trans`) "
                . "WHERE ((`comtrans`.`KODE_TRANS` = `com_kode_trans`.`KODE_TRANS`) "
                . "AND `comtrans`.TGL_TRANS = '".$dtm_tday."' "
                . "AND `comtrans`.USERID = '".$agentid."' "
                . "AND ('100_101_102' LIKE CONCAT(CONCAT('%',`comtrans`.`KODE_TRANS`),'%'))) "
                . "GROUP BY comtrans.COMTYPE,comtrans.KODE_TRANS ORDER BY comtrans.COMTYPE ASC";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Inv_by_dtm($dtm_from = '0000-00-00',$dtm_to = '0000-00-00') {
        if(empty($dtm_from)){
            return false;
        }if(empty($dtm_to)){
            return false;
        }
        $sql = "SELECT a.TGL_TRANS as dtm, c.provider,
	a.COMTYPE as comtype,c.product,c.price_fr_mitra AS price_from_mitra,c.price_original AS price_indosis,c.price_sell AS price_3 FROM comtrans a, transaksi_master b, com_pulsa_log c WHERE
	a.COMTRANS_ID = b.TRANS_ID_SOURCE AND b.TRANS_ID = c.master_id AND a.TGL_TRANS >= '".$dtm_from."' AND a.TGL_TRANS <= '".$dtm_to."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_per_month_sum($dtm_from = '0000-00-00',$dtm_to = '0000-00-00') {
        if(empty($dtm_from)){
            return false;
        }if(empty($dtm_to)){
            return false;
        }
        $sql = "SELECT kode_trans,(SELECT DESKRIPSI_TRANS FROM com_kode_trans WHERE KODE_TRANS = `comtrans`.`KODE_TRANS`) AS `desc_kode_trans`,
SUM(IF(pokok IS NULL,0,pokok)) AS nominal_pokok,SUM(IF(adm IS NULL,0,adm)) AS nominal_adm,
COUNT(*) AS total FROM COMTRANS WHERE TGL_TRANS >= '".$dtm_from."' AND TGL_TRANS <= '".$dtm_to."' GROUP BY kode_trans ORDER BY kode_trans";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Trx_pending_data() {
        $sql = "SELECT date(dtm) as dtm,msisdn,product,master_id,userid FROM com_pulsa_log WHERE res_code ='68' AND DATE(dtm) = date(now()) LIMIT 10";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Upd_com_log($in_product = '',$in_msisdn = '',$dtm = '', $in_array = array()) {
        if(empty($in_product)){
            return false;
        }
        if(empty($in_msisdn)){
            return false;
        }
        if(empty($dtm)){
            return false;
        }
        
        $this->db->where('msisdn', $in_msisdn);
        $this->db->where('product', $in_product);
        $this->db->where('date(dtm)', $dtm);
        $this->db->where('res_code', '68');
        $query  = $this->db->update('com_pulsa_log', $in_array);
        return $query;
    }
    public function Upd_com_log_byid($in_idtrans, $in_array = array()) {
        if(empty($in_idtrans)){
            return false;
        }
        
        $this->db->where('id', $in_idtrans);
        $this->db->where('res_code', '68');
        $query  = $this->db->update('com_pulsa_log', $in_array);
        return $query;
    }
    public function Upd_pay_log_byid($in_idtrans, $in_array = array()) {
        if(empty($in_idtrans)){
            return false;
        }
        
        $this->db->where('id', $in_idtrans);
        $this->db->where('res_code', '68');
        $query  = $this->db->update('com_payment_log', $in_array);
        return $query;
    }
    public function Del_Comtrans_byid($in_transid,$guserid) {
        $this->db->limit(1);
        $query = $this->db->delete('COMTRANS', array('COMTRANS_ID' => $in_transid, 'USERID' => $guserid)); 
        return $query; 
    }
    public function Hist_per_day($userid = '') {
        if(empty($userid)){
            return false;
        }
        $this->tgl = date("Y-m-d");
        $sql = "SELECT dtm,msisdn,product,nominal,res_status,res_code,price_original,price_stok,price_selling,userid,res_sn,master_id,log_trace	FROM com_pulsa_log WHERE DATE(dtm) <='".$this->tgl."' and userid = '".$userid."' AND ('00_68' LIKE CONCAT(CONCAT('%',res_code),'%')) GROUP BY id";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function Hist_per_periode($userid = '',$in_tgl_fr = '',$in_tgl_to = '') {
        if(empty($userid)){
            return false;
        }if(empty($in_tgl_fr)){
            return false;
        }if(empty($in_tgl_to)){
            return false;
        }
        
        $this->tgl_fr = $in_tgl_fr ?: '';
        $this->tgl_to = $in_tgl_to ?: '';
        
        $sql = "SELECT dtm,msisdn,product,nominal,res_status,res_code,price_original,price_stok,price_selling,userid,res_sn,master_id,log_trace	FROM com_pulsa_log WHERE DATE(dtm) >= '".$this->tgl_fr."' AND DATE(dtm) <='".$this->tgl_to."' AND userid = '".$userid."' AND ('00_68' LIKE CONCAT(CONCAT('%',res_code),'%')) GROUP BY id";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Logtrx_byid($kode_log = '') {
        if(empty($kode_log)){
            return false;
        }
        $sql = "SELECT com_pulsa_log.*, admin_users.username FROM `com_pulsa_log` "
                . "JOIN `admin_users` ON `admin_users`.`id` = `com_pulsa_log`.`userid` "
                . "AND com_pulsa_log.id = '".$kode_log."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Logtrxpayment_byid($kode_log = '') {
        if(empty($kode_log)){
            return false;
        }
        $sql = "SELECT `com_payment_log`.*, `admin_users`.`username` FROM `com_payment_log` "
                . "JOIN `admin_users` ON `admin_users`.`id` = `com_payment_log`.`userid` "
                . "AND `com_payment_log`.`id` = '".$kode_log."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Tgh($userid,$tgl_fr,$tgl_to) {
        if(empty($userid)){           return false;       }
        $sql = "SELECT b.KODE_TRANS,b.DESKRIPSI_TRANS,SUM(IF((c.`price_original` IS NOT NULL),c.`price_original`,0)) AS price_indosis,SUM(IF((c.`price_stok` IS NOT NULL),c.`price_stok`,0)) AS price_bmt,SUM(IF((c.`price_selling` IS NOT NULL),c.`price_selling`,0)) AS price_agent,SUM(IF((a.`POKOK` IS NOT NULL),a.`POKOK`,0)) AS total,SUM(IF((a.`ADM` IS NOT NULL),a.`ADM`,0)) AS adm FROM comtrans a, com_kode_trans b, com_pulsa_log c WHERE a.COMTRANS_ID = c.master_id AND a.USERID = c.userid AND a.USERID = '".$userid."' AND date(dtm) >= '".$tgl_fr."' AND date(dtm) <= '".$tgl_to."'  AND a.KODE_TRANS=b.KODE_TRANS  AND ('100_101_102' LIKE CONCAT(CONCAT('%',b.`KODE_TRANS`),'%'))GROUP BY a.KODE_TRANS";
        $query = $this->db->query($sql);
        return $query->result();   
    }   
    public function Tgh_mutasi($userid,$tgl_fr,$tgl_to) {//pulsa
        if(empty($userid)){           return false;       }
        $sql = "SELECT DATE_FORMAT(c.dtm,'%Y/%m/%d %H:%i') AS dtm_transaksi,"
                . "b.KODE_TRANS,b.DESKRIPSI_TRANS,c.nominal,c.product,a.nocustomer,"
                . "IF((c.`price_stok` != 0),c.`price_stok`,c.price_selling) AS price_bmt,"
                . "IF((c.`price_selling` != 0),c.`price_selling`,0) AS price_agent,"
                . "IF((a.`POKOK` != 0),a.`POKOK`,0) AS total,IF((a.`ADM` != 0),a.`ADM`,0) AS adm ,"
                . "(IF((c.`price_selling` != 0),c.`price_selling`,0) - IF((c.`price_stok`!=0),"
                . "c.`price_stok`,c.price_selling)) AS profit_agent "
                . "FROM comtrans a, com_kode_trans b, com_pulsa_log c "
                . "WHERE a.COMTRANS_ID = c.master_id AND a.USERID = c.userid AND a.USERID = '".$userid."' "
                . "AND DATE(dtm) >= '".$tgl_fr."' AND DATE(dtm) <= '".$tgl_to."' "
                . "AND a.KODE_TRANS=b.KODE_TRANS "
                . "AND ('100_101_102' LIKE CONCAT(CONCAT('%',b.`KODE_TRANS`),'%')) ORDER BY c.id ASC";
        $query = $this->db->query($sql);
        return $query->result();   
    } 
    public function Tgh_mutasi_pay($userid,$tgl_fr = '0000-00-00',$tgl_to = '0000-00-00') {//payment
        if(empty($userid)){           return false;       }
        $sql = "SELECT DATE_FORMAT(c.dtm,'%Y/%m/%d %H:%i') AS dtm_transaksi,b.KODE_TRANS,b.DESKRIPSI_TRANS,c.nominal,c.product,a.NOCUSTOMER,
                IF((c.`adm_bmt` != 0),c.`adm_bmt`,c.adm_bmt) AS price_bmt,
                IF((c.`adm_agen` != 0),c.`adm_agen`,0) AS price_agent,
                IF((a.`POKOK` != 0),a.`POKOK`,0) AS tagihan,
                IF((a.`ADM` != 0),a.`ADM`,0) AS total_adm 
                FROM comtrans a, com_kode_trans b, com_payment_log c 
                WHERE a.COMTRANS_ID = c.master_id AND a.USERID = c.userid AND a.USERID = '".$userid."' AND DATE(dtm) >= '".$tgl_fr."' AND DATE(dtm) <= '".$tgl_to."' AND a.KODE_TRANS=b.KODE_TRANS 
                AND ('100_101_102' LIKE CONCAT(CONCAT('%',b.`KODE_TRANS`),'%')) ORDER BY c.id ASC";
        $query = $this->db->query($sql);
        return $query->result();   
    } 
    public function Tgh_mutasi_pay_all($tgl_fr = '0000-00-00',$tgl_to = '0000-00-00', $produklist = '') {//payment
   
        $sql = "SELECT DATE_FORMAT(c.dtm,'%Y/%m/%d %H:%i') AS dtm_transaksi,b.KODE_TRANS,b.DESKRIPSI_TRANS,c.nominal,c.product,a.NOCUSTOMER,
                IF((c.`adm_bmt` != 0),c.`adm_bmt`,c.adm_bmt) AS price_bmt,
                IF((c.`adm_agen` != 0),c.`adm_agen`,0) AS price_agent,
                IF((c.`adm_provider` != 0),c.`adm_provider`,0) AS price_provider,
                IF((c.`adm_indosis` != 0),c.`adm_indosis`,0) AS price_indosis,
                IF((a.`POKOK` != 0),a.`POKOK`,0) AS tagihan,
                IF((a.`ADM` != 0),a.`ADM`,0) AS total_adm,
                a.USERID AS userid
                FROM comtrans a, com_kode_trans b, com_payment_log c 
                WHERE a.COMTRANS_ID = c.master_id  AND DATE(dtm) >= '".$tgl_fr."' AND DATE(dtm) <= '".$tgl_to."' 
                AND a.KODE_TRANS = b.KODE_TRANS";
        if(empty($produklist)){            
        }else{
            $sql  .= " AND c.product = '".$produklist."'";    
        }
        $sql  .= " AND ('100_101_102' LIKE CONCAT(CONCAT('%',b.`KODE_TRANS`),'%')) ORDER BY c.dtm ASC";
        $query = $this->db->query($sql);
        return $query->result();   
    } 
    public function Tgh_mutasi_bfor($userid,$tgl_fr = '0000-00-00') {
        if(empty($userid)){           return false;       }
        $sql = "SELECT DATE_FORMAT(c.dtm,'%Y/%m/%d %H:%s') AS dtm_transaksi,"
                . "b.KODE_TRANS,b.DESKRIPSI_TRANS,c.nominal,c.product,a.nocustomer,"
                . "IF((c.`price_stok` != 0),c.`price_stok`,c.price_selling) AS price_bmt,"
                . "IF((c.`price_selling` != 0),c.`price_selling`,0) AS price_agent,"
                . "IF((a.`POKOK` != 0),a.`POKOK`,0) AS total,IF((a.`ADM` != 0),a.`ADM`,0) AS adm ,"
                . "(IF((c.`price_selling` != 0),c.`price_selling`,0) - IF((c.`price_stok`!=0),"
                . "c.`price_stok`,c.price_selling)) AS profit_agent "
                . "FROM comtrans a, com_kode_trans b, com_pulsa_log c "
                . "WHERE a.COMTRANS_ID = c.master_id AND a.USERID = c.userid AND a.USERID = '".$userid."' "
                . "AND DATE(dtm) <= '".$tgl_fr."' "
                . "AND a.KODE_TRANS=b.KODE_TRANS "
                . "AND ('100_101_102' LIKE CONCAT(CONCAT('%',b.`KODE_TRANS`),'%'))";
        $query = $this->db->query($sql);
        return $query->result();   
    }
    public function Tgh_mutasi_pay_bfor($userid = '',$tgl_fr ='0000-00-00') {//payment
        if(empty($userid)){           return false;       }
        $sql = "SELECT DATE_FORMAT(c.dtm,'%Y/%m/%d %H:%s') AS dtm_transaksi,b.KODE_TRANS,b.DESKRIPSI_TRANS,c.nominal,c.product,a.NOCUSTOMER,
                IF((c.`adm_bmt` != 0),c.`adm_bmt`,c.adm_bmt) AS price_bmt,
                IF((c.`adm_agen` != 0),c.`adm_agen`,0) AS price_agent,
                IF((a.`POKOK` != 0),a.`POKOK`,0) AS tagihan,
                IF((a.`ADM` != 0),a.`ADM`,0) AS total_adm 
                FROM comtrans a, com_kode_trans b, com_payment_log c 
                WHERE a.COMTRANS_ID = c.master_id AND a.USERID = c.userid 
                AND a.USERID = '".$userid."' 
                AND DATE(dtm) <= '".$tgl_fr."' 
                AND a.KODE_TRANS=b.KODE_TRANS 
                AND ('100_101_102' LIKE CONCAT(CONCAT('%',b.`KODE_TRANS`),'%'))";
        $query = $this->db->query($sql);
        return $query->result();   
    } 
    public function Tgh_before_by_dtm($userid = '',$tgl_fr = '0000-00-00') {
        if(empty($userid)){
            return false;           
        }
        if(empty($tgl_fr)){
            return false;
        }
        $sql = "SELECT SUM(IF((POKOK != 0),POKOK,0)) AS SETORAN,SUM(IF((ADM != 0),ADM,0)) AS ADM FROM com_setoran WHERE USERID = '".$userid."' AND VERIFIKASI = '1' AND TGL_TRANS < '".$tgl_fr."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Tgh_before_by_now($userid = '',$tgl_fr = '',$tgl_to = '') {
        if(empty($userid)){
            return false;           
        }
        if(empty($tgl_fr)){
            return false;
        }
        if(empty($tgl_to)){
            return false;
        }
        $sql = "SELECT IF((POKOK != 0),POKOK,0) AS SETORAN,IF((ADM != 0),ADM,0) AS BASIL,JAM FROM com_setoran WHERE USERID = '".$userid."' AND VERIFIKASI = '1' AND TGL_TRANS >= '".$tgl_fr."' AND TGL_TRANS <= '".$tgl_to."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    //1. untuk mendapatkan data history pembayaran 1 bulan terakhir untuk di menu tagihan
    public function Tgh_history_last_month($userid = '') {
        if(empty($userid)){
            return false;           
        }
        
        $sql = "SELECT COMSETOR_ID,IF((POKOK != 0),POKOK,0) AS SETORAN,IF((ADM != 0),ADM,0) AS BASIL,JAM,KETERANGAN FROM com_setoran WHERE USERID = '".$userid."' AND TGL_TRANS >= DATE_ADD(NOW( ), INTERVAL -1 MONTH ) AND VERIFIKASI = '1' ORDER BY COMSETOR_ID";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function GetDataNoMasterID() {
        $sql = "SELECT 	id, dtm, request, ip_request, provider, msisdn, product, nominal,urlhit, response,res_status, 
            res_code,res_message,res_saldo,trx_status, price_original,price_stok, 
            price_selling, price_fr_mitra, userid, res_sn, res_transid,master_id, cp,log_trace, desc_trx	 
            FROM com_pulsa_log 
            WHERE (res_code = '00' OR res_code = '68') AND (master_id = '' OR master_id IS NULL) limit 20";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function GetDataPayNoMasterID() {                       
        $sql = "SELECT 	id, dtm, 
	request, 
	ip_request, 
	provider, 
	destid, 
	product, 
	nominal, 
	urlhit, 
	response, 
	res_status, 
	res_code, 
	res_message, 
	res_saldo, 
	trx_status, 
	adm_indosis, 
	adm_bmt, 
	adm_agen, 
	adm_fr_mitra, 
	userid, 
	res_sn, 
	res_transid, 
	master_id, 
	cp, 
	log_trace, 
	desc_trx, 
	tagihan, 
	adm_provider	 
            FROM com_payment_log 
            WHERE (res_code = '00' OR res_code = '68') AND (master_id = '0' OR master_id IS NULL)  limit 20";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Get_total_transaksi($userid = '',$periode = '') {
        
        if(empty($userid)){
            return 0;
        }
        if(empty($periode)){
            return 0;
        }
        $sql = "SELECT COUNT(*) AS total FROM comtrans WHERE comtrans.USERID ='".$userid."' AND comtrans.TGL_TRANS = DATE('".$periode."')";
        $query = $this->db->query($sql)->result();
        
        if($query){
            foreach ($query as $v){
                return $v->total ?: 0;
            }
        }
        return 0;
    }
    public function Prefix_get_provider($msisdn = '',$type = '') {
        if(empty($msisdn)){
            return 0;
        }
        $sql = "SELECT code_prov_pulsa,code_prov_data FROM sejahtera.com_msisdn_prefix WHERE nomor_prefix = '".$msisdn."'";
        $query = $this->db->query($sql)->result();
        if($query){
            foreach ($query as $v){
                if ($type == "pulsa"):
                    return $v->code_prov_pulsa ?: 0;
                elseif ($type == "data") :
                    return $v->code_prov_data ?: 0;
                else:
                    return 0;
                endif;
            }
            
        }
        return 0;
    }
    //get downline mode commerce
    public function Getalltransaksi_downline($in_agentid = array(),$tgl_from = '',$tgl_to = '') {        
        $this->db->select('comtrans.TGL_TRANS');
        //$this->db->select_sum('price_stok','harga_bmt');
        $this->db->select_sum('IF((price_stok != 0),price_stok,0)','harga_bmt');
        $this->db->select_sum('IF((price_selling != 0),price_selling,0)','harga_jual');
        $this->db->select_sum('IF((price_selling != 0),price_selling,0) - IF((price_stok != 0),price_stok,0)','profit'); 
        $this->db->select('count(*) AS ntotal');
        $this->db->from('com_pulsa_log');
        $this->db->join('comtrans','com_pulsa_log.userid=comtrans.USERID');
        $this->db->where('comtrans.COMTRANS_ID = com_pulsa_log.master_id');
        $this->db->where('comtrans.TGL_TRANS >= "'.$tgl_from.'"');
        $this->db->where('comtrans.TGL_TRANS <= "'.$tgl_to.'"');
        $this->db->where('("100_101_102" LIKE CONCAT(CONCAT("%",comtrans.`KODE_TRANS`),"%"))');
        $this->db->where_in('comtrans.USERID',$in_agentid);        
        $this->db->group_by('TGL_TRANS');
        //$this->db->limit(20);
        return $this->db->get()->result();                
    }
    
    public function Comtrans_get_model($userid = array(),$tgl_from = '',$tgl_to = '') {
        $sql = "SELECT TGL_TRANS, MODEL FROM comtrans WHERE USERID IN (".implode(',', $userid).") AND MODEL IN ('COM','PAY') AND DATE(tgl_trans) >= '".$tgl_from."' AND DATE(tgl_trans) <= '".$tgl_to."' GROUP BY tgl_trans,model";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    //get downline mode payment
    public function Tgh_mutasi_pay2($userid = array(),$tgl_fr = '0000-00-00',$tgl_to = '0000-00-00') {//payment
        if(empty($userid)){           return false;       }
        $sql = "SELECT DATE_FORMAT(c.dtm,'%Y/%m/%d %H:%s') AS dtm_transaksi,b.KODE_TRANS,b.DESKRIPSI_TRANS,c.nominal,c.product,a.NOCUSTOMER,
                sum(IF((c.`adm_bmt` != 0),c.`adm_bmt`,c.adm_bmt)) AS price_bmt,
                sum(IF((c.`adm_agen` != 0),c.`adm_agen`,0)) AS price_agent,
                sum(IF((a.`POKOK` != 0),a.`POKOK`,0)) AS tagihan,
                sum(IF((a.`ADM` != 0),a.`ADM`,0)) AS total_adm,
                sum(IF((a.`POKOK` != 0),a.`POKOK`,0) +  IF((a.`ADM` != 0),a.`ADM`,0)) AS price_akhir,
                sum(IF((a.`POKOK` != 0),a.`POKOK`,0) +  IF((c.`adm_bmt` != 0),c.`adm_bmt`,c.adm_bmt) + IF((c.`adm_indosis` != 0),c.`adm_indosis`,0) + IF((c.`adm_provider` != 0),c.`adm_provider`,0)) AS price_bmt,
                count(*) as ntotal
                FROM comtrans a, com_kode_trans b, com_payment_log c 
                WHERE a.COMTRANS_ID = c.master_id AND a.USERID = c.userid AND a.USERID IN (".implode(',', $userid).") AND DATE(dtm) >= '".$tgl_fr."' AND DATE(dtm) <= '".$tgl_to."' AND a.KODE_TRANS=b.KODE_TRANS 
                AND ('100_101_102' LIKE CONCAT(CONCAT('%',b.`KODE_TRANS`),'%')) GROUP BY a.TGL_TRANS";
        $query = $this->db->query($sql);
        return $query->result();   
    } 
    //1. save pdf bpjs
    public function Getdata_bpjs_by_logtrace($code) {
        $sql = "SELECT a.destid,a.dtm,c.POKOK,c.ADM,b.cust_id,b.cust_name,a.res_sn,b.periode,b.total_tagihan,b.log_trace	 
	FROM com_payment_log a,com_bpjs_ses b, comtrans c WHERE a.log_trace=b.log_trace AND a.master_id = c.COMTRANS_ID	 AND a.log_trace= '".$code."' limit 1";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Commerce_per_day($userid = '') {
        if(empty($userid)){
            return false;
        }
        $sql = "SELECT dtm,msisdn as nomorid,product,nominal as kategori,price_selling as price,res_sn as sn,log_trace as code FROM com_pulsa_log WHERE userid = '".$userid."' AND ('00_68' LIKE CONCAT(CONCAT('%',res_code),'%')) AND (DATE(dtm) >=  NOW() - INTERVAL 1 MONTH) ORDER BY id DESC
	LIMIT 50";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Payment_pln_per_day($userid = '') {
        if(empty($userid)){
            return false;
        }
        $sql = "SELECT a.dtm,b.cust_id,b.cust_name,b.periode1,b.periode2,b.periode3,b.periode4,c.POKOK,c.ADM,b.total,a.res_sn FROM 
                com_payment_log a, com_pln_ses b, comtrans c WHERE a.log_trace = b.log_trace AND a.master_id = c.COMTRANS_ID  
                AND c.userid = '".$userid."' AND ('00_68' LIKE CONCAT(CONCAT('%',a.res_code),'%')) 
                AND (DATE(a.dtm) >=  NOW() - INTERVAL 1 MONTH)
                ORDER BY a.id DESC LIMIT 200";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Payment_bpjs_per_day($userid = '') {
        if(empty($userid)){
            return false;
        }
        $sql = "SELECT a.dtm,b.cust_id,b.cust_name,b.periode,c.POKOK,c.ADM,b.total_amount,a.res_sn,b.total_person,b.no_reff FROM 
                com_payment_log a, com_bpjs_ses b, comtrans c WHERE a.log_trace = b.log_trace AND a.master_id = c.COMTRANS_ID  
                AND c.userid = '".$userid."' AND ('00_68' LIKE CONCAT(CONCAT('%',a.res_code),'%')) 
                AND (DATE(a.dtm) >=  NOW() - INTERVAL 1 MONTH)
                ORDER BY a.id DESC LIMIT 200";
        $query = $this->db->query($sql);
        return $query->result();
    }
    function Payment_cetak($id = '') {
        if(empty($id)){
            return false;
        }
        $sql ="SELECT c.dtm AS dtm_transaksi,c.nominal,c.product,a.NOCUSTOMER,
                IF((c.`adm_bmt` != 0),c.`adm_bmt`,c.adm_bmt) AS price_bmt,
                IF((c.`adm_agen` != 0),c.`adm_agen`,0) AS price_agent,
                IF((c.`adm_provider` != 0),c.`adm_provider`,0) AS price_provider,
                IF((c.`adm_indosis` != 0),c.`adm_indosis`,0) AS price_indosis,
                IF((a.`POKOK` != 0),a.`POKOK`,0) AS tagihan,
                IF((a.`ADM` != 0),a.`ADM`,0) AS total_adm,
                a.USERID AS userid,
                a.COMTRANS_ID,
                c.log_trace,
                c.res_sn
                FROM comtrans a, com_payment_log c  WHERE a.COMTRANS_ID = c.master_id AND c.id = '".$id."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    function BPJSSESByLogTrace($log = '') {
        $this->db->where('log_trace',$log);
        return $this->db->get('com_bpjs_ses')->result();
    }
    
    
}

   