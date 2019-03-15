<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Merchant_model
 *
 * @author edisite
 */
class Merchant_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    function ListOrder($userid = '', $norekening = '') {
        $this->db->where('m_userid', $userid);
        $this->db->where('m_norekening', $norekening);
        return $this->db->get('merchant_transaction_ses')->result();
    }
    function MerchantStatusCheck($id, $mc_dtm_expired) {
        $curDateTime = date("YmdHis");
        $myDate = date("YmdHis", strtotime($mc_dtm_expired));
        if($myDate > $curDateTime){
            return true;
        }else{
            $data =  array('status' => '2','dtm_update' => date("Y-m-d H:i:s"),'keterangan' => 'expired, waktu konfirmasi oleh user telah habis');
            $this->db->update('merchant_transaction_ses', $data, array('id' => $id));
            return false;
        }
    }
    function FindOrder($userid = '', $norekening = '',$transid = '') {
        $this->db->where('m_userid', $userid);
        $this->db->where('m_norekening', $norekening);
        $this->db->where('transid', $transid);
        return $this->db->get('merchant_transaction_ses')->result();
    }
}
