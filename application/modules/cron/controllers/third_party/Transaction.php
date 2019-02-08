<?php
define('API_LOCATION', 'http://202.43.173.11:801/API_bmt/');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transaction
 *
 * @author edisite
 */
class Transaction extends MY_Controller {
    //put your code here
    public function __construct() {
        parent::MY_Controler();
    }
    public function index() {
       $in_param = array(
            'tid' => '9242842',
            'nasabahid' => '35200003',
            'jenis_transaksi' => 'TAB',
            'model_transaksi' => '01',
            'tipe_transaksi' => '204',
            'nilai' => 'Value',
        );
        if(empty($in_param)){
            return FALSE;
        }
       
       
        if (!$this->is_JSON($in_param)){
            $in_param = json_encode($in_param);
        }
        
        $val_json       = json_decode($in_param);
        $val_trx_id     = $val_json->tid;
        $val_jenis      = $val_json->jenis_transaksi;
        $val_model      = $val_json->model_transaksi;
        $val_tipe       = $val_json->tipe_transaksi;
        $val_nilai      = $val_json->nilai;
        $val_nasabahid  = $val_json->nasabahid;
//        if(empty($val_nasabahid) || empty($val_trx_id) || empty($val_jenis) || empty($val_model) || empty($val_tipe) || empty($val_nilai)){
//            return FALSE;
//        }
        $sentdata = "?transaksi_id=".$val_trx_id
                . "&nasabah_id=".$val_nasabahid
                . "&jenis_transaksi=".$val_jenis
                . "&model_transaksi=".$val_model
                . "&tipe_transaksi=".$val_tipe
                . "&nilai=".$val_nilai;
        $res = Modules::load('api/third_party/transaction/openhttp/hitget',  API_LOCATION.'find_nasabah.php'.$sentdata);
        //echo API_LOCATION.'find_nasabah.php'.$sentdata;
        echo $res;

    }
    function is_JSON($data) {
        $this->post_data = json_decode( stripslashes( $data ) );
        if( $this->post_data === NULL )
        {
            return false;
        }
        return true;
    }
}
