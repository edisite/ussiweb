<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pay_model
 *
 * @author edisite
 */
class Pay_model extends MY_Model {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function List_product($by_code = '') {
        $this->db->select('*');
        //$this->db->limit(2);
        $this->db->where('code_provider',$by_code);
        $this->db->where('status','OPEN');
        $this->db->from('com_pulsa');
        $query = $this->db->get()->result();
        return $query;
    }
     public function List_op($by_type) {
        $this->db->select('code_provider,provider');
        $this->db->distinct();
        $this->db->where('type',$by_type);
        //$this->db->where('status','OPEN');
        $this->db->from('com_pulsa');
        $query = $this->db->get()->result();
        return $query;
    }
    public function List_op_product_lainya($by_type) {
        $categories = array('121', '122','124');
        $this->db->select('code_provider,provider');
        $this->db->distinct();
        $this->db->where('type',$by_type);
        $this->db->where_in('code_provider',$categories);
        $this->db->from('com_pulsa');
        $query = $this->db->get()->result();
        return $query;
    }
    public function Pulsa_by_product($in_kode_produk = '',$in_type = '') {
        if(empty($in_kode_produk)){
            return FALSE;
        }
        $this->db->select('*');
        $this->db->where('product_id',$in_kode_produk);
        $this->db->or_where('id_pulsa',$in_kode_produk);
        $this->db->where('status','OPEN');
        $this->db->where('type',$in_type);
        $this->db->limit(1);
        $this->db->from('com_pulsa');
        $query = $this->db->get()->result();
        return $query;
    }
    public function Pln_ses_by_code($in_kode_produk = '', $meterid = '') {
        if(empty($in_kode_produk)){            return FALSE;        }
        if(empty($meterid)){           return FALSE;        }
        $this->db->select('*');
        $this->db->where('log_trace',$in_kode_produk);
        $this->db->where('cust_id',$meterid);
        $this->db->where('rc','00');
        $this->db->where('status','OK');
        $this->db->limit(1);
        $this->db->from('com_pln_ses');
        $query = $this->db->get()->result();
        return $query;
    }
    public function Bpjs_ses_by_code($in_kode_produk = '', $meterid = '') {
        if(empty($in_kode_produk)){            return FALSE;        }
        if(empty($meterid)){           return FALSE;        }
        $this->db->select('*');
        $this->db->where('log_trace',$in_kode_produk);
        $this->db->where('cust_id',$meterid);
        $this->db->where('rc','00');
        $this->db->where('status','OK');
        $this->db->limit(1);
        $this->db->from('com_bpjs_ses');
        $query = $this->db->get();
        return $query->result();
    }
    public function Mfinance_ses_by_code($in_kode_produk = '', $meterid = '') {
        if(empty($in_kode_produk)){            return FALSE;        }
        if(empty($meterid)){           return FALSE;        }
        $this->db->select('*');
        $this->db->where('log_trace',$in_kode_produk);
        $this->db->or_where('trx_id',$in_kode_produk);
        $this->db->where('cust_id',$meterid);
        $this->db->where('rc','00');
        $this->db->where('status','OK');
        $this->db->limit(1);
        $this->db->from('com_mfinance_ses');
        $query = $this->db->get()->result();
        return $query;
    }
    public function Upd_logpulsa($traceid = '', $in_array = array()) {
        $this->db->where('log_trace', $traceid);
        $query  = $this->db->update('com_pulsa_log', $in_array);
        return $query;
    }
    public function Upd_logpayment($traceid = '', $in_array = array()) {
        if(empty($traceid)){
            return false;
        }
        if(empty($in_array)){
            return false;
        }
        $this->db->where('log_trace', $traceid);
        $query  = $this->db->update('com_payment_log', $in_array);
        return $query;
    }
    public function Logpulsa($traceid = '') {
        $sql = "SELECT b.type,a.msisdn,b.product_alias,a.product,b.provider,a.userid,a.res_sn,a.dtm FROM com_pulsa_log a, com_pulsa b WHERE a.product = b.product_id AND b.type ='PULSA' AND a.res_code = '00' AND (a.log_trace = '".$traceid."' OR a.res_sn = '".$traceid."')";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Log_last_transaction($userid = '',$tanggal = '') {
        if(empty($tanggal)){
            $tanggal = date('Y-m-d');
        }
        $sql = "SELECT a.dtm,b.type,a.msisdn AS cust_id,CONCAT('[',a.product,']',b.product_alias) AS product,"
                . "a.res_sn,res_code FROM com_pulsa_log a, com_pulsa b WHERE "
                . "a.product = b.product_id AND AND a.userid = '".$userid."' DATE(a.dtm) = '".$tanggal."' ORDER BY id DESC LIMIT 1";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Logpln($traceid = '') {
        $sql = "SELECT b.type,a.msisdn,b.product_alias,a.product,b.provider,a.userid,a.res_sn,a.dtm FROM com_pulsa_log a, com_pulsa b WHERE a.product = b.product_id AND b.type ='PLN' AND a.res_code = '00' AND (a.log_trace = '".$traceid."' OR a.res_sn = '".$traceid."')";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Logpln_postpaid($traceid = '') {
        $sql = "SELECT a.dtm,a.destid,a.res_sn,b.tagihan,b.biaya_adm,b.total,b.periode1,b.cust_name,b.tariff,b.daya FROM com_payment_log a, com_pln_ses b WHERE a.log_trace = b.log_trace AND a.log_trace = '".$traceid."' AND a.res_code = '00'";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Logfinance($traceid = '') {
        //$sql = "SELECT b.type,a.msisdn,b.product_alias,a.product,b.provider,a.userid,a.res_sn,a.dtm FROM com_pulsa_log a, com_pulsa b WHERE a.product = b.product_id AND b.type ='FINANCE' AND a.res_code = '00' AND (a.log_trace = '".$traceid."' OR a.res_sn = '".$traceid."')";
        $sql = "SELECT a.dtm,a.res_code,a.userid,a.res_sn,a.res_transid,b.cust_name,b.cust_id,b.amount,b.admin_fee,b.total,b.no_installment,c.product_alias AS leasing,b.due_date FROM com_pulsa_log a, com_mfinance_ses b, com_pulsa c WHERE a.res_transid = b.trx_id  AND a.product = c.product_id AND product ='6000' AND a.res_code ='00' AND (a.log_trace = '".$traceid."' OR a.res_sn = '".$traceid."')";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Upd_logpln($transid = '00000', $in_array = array()) {
        $this->db->where('trx_id', $transid);
        $this->db->order_by('id', "desc");
        $this->db->limit(1);
        $query  = $this->db->update('com_pln_ses', $in_array);
        return $query;
    }
    public function Upd_logbpjs($transid = '00000', $in_array = array()) {
        $this->db->where('trx_id', $transid);
        $this->db->order_by('id', "desc");
        $this->db->limit(1);
        $query  = $this->db->update('com_bpjs_ses', $in_array);
        return $query;
    }
    public function Upd_logmfinance($transid = '00000', $in_array = array()) {
        $this->db->where('trx_id', $transid);
        $this->db->order_by('id', "desc");
        $this->db->limit(1);
        $query  = $this->db->update('com_mfinance_ses', $in_array);
        return $query;
    }
    public function Lasttrx($in_productid = '',$in_custid = '') {
        $sql = "SELECT MAX(dtm) AS dtm,res_status,res_code,"
                . "res_sn FROM com_pulsa_log WHERE msisdn = '".$in_custid."' "
                . "AND product = '".$in_productid."'";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Upd_pln_ses_by_code($in_kode_produk = '', $meterid = '',$in_array = array()) {
        if(empty($in_kode_produk)){            return FALSE;        }
        if(empty($meterid)){           return FALSE;        }
        
        $this->db->where('log_trace', $in_kode_produk);
        $this->db->where('cust_id', $meterid);
        $this->db->where('rc','00');
        $this->db->where('status','OK');
        $query  = $this->db->update('com_pln_ses', $in_array);
        return $query;               
    }
    public function Upd_bpjs_ses_by_code($in_kode_produk = '', $meterid = '',$in_array = array()) {
        if(empty($in_kode_produk)){            return FALSE;        }
        if(empty($meterid)){           return FALSE;        }
        
        $this->db->where('log_trace', $in_kode_produk);
        $this->db->where('cust_id', $meterid);
        $this->db->where('rc','00');
        $this->db->where('status','OK');
        $query  = $this->db->update('com_bpjs_ses', $in_array);
        return $query;               
    }
    public function Ref($transid = '', $destid = '',$reffid = '') {
        $sql = "SELECT a.tagihan,b.biaya_adm,b.cust_name,a.destid,a.res_sn,b.periode1,b.daya,b.total FROM com_payment_log a, com_pln_ses b "
                . "WHERE a.log_trace =b.log_trace "
                . "AND a.log_trace='".$transid."' "
                . "AND a.res_sn = '".$reffid."' "
                . "AND a.destid = '".$destid."'";
        $query = $this->db->query($sql)->result();
        return $query;
    }
}
