<?php
//define('API_LOCATION', 'http://202.43.173.13/insert_poin/insert_his_reward.php?');
//define('API_LOCATION', 'http://10.1.1.62/insert_poin/insert_his_reward.php?');
//reivis jadi di reto kata opik
define('API_LOCATION', 'http://localhost/insert_poin/insert_his_reward.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Poin_model
 *
 * @author edisite
 */
class Poin_model extends CI_Model{
    //put your code here
    public function Ins_his_reward($in_param) {       
        if(empty($in_param)){
            return FALSE;
        }       
       
        if (!$this->is_JSON($in_param)){
            $in_param = json_encode($in_param);
        }
        
        $val_json       = json_decode($in_param);
        $val_trx_id     = $val_json->tid;
        $val_agent      = $val_json->agent;
        $val_kode       = $val_json->kode;
        $val_jenis      = $val_json->jenis;
        $val_nilai      = $val_json->nilai;
//        if(empty($val_nasabahid) || empty($val_trx_id) || empty($val_jenis) || empty($val_model) || empty($val_tipe) || empty($val_nilai)){
//            return FALSE;
//        }
        $sentdata = "?transaksi_id=".$val_trx_id //trxid  // tabtrans
                . "&agent_id=".$val_agent 
                . "&jenis_transaksi=".$val_jenis  //dep
                . "&kode_transaksi=".$val_kode //kodetrans
                . "&value=".$val_nilai; //duweet                                             //jenis_transaksi=DEP&kode_transaksi=502&agent_id=35201878&value=1050000000&transaksi_id=2846274629462
        
        //var_dump($sentdata);
        //$res = $this->Hitget(API_LOCATION.'insert_his_reward.php'.$sentdata);
        $res = file_get_contents(API_LOCATION.$sentdata);
        //echo API_LOCATION.'find_nasabah.php'.$sentdata;
        //return $res;
       // $getstatus = "";
        /*if($this->is_JSON($res)){
            $status = json_decode(stripslashes( $res ));
            $getstatus = $status->status;
        }
        //echo $res;
        if(strtoupper($getstatus) == "OK"){
            return TRUE;
        }else{
            return FALSE;
        }*/
        return $res;
    }
    function is_JSON($data) {
        $this->post_data = json_decode( stripslashes( $data ) );
        if( $this->post_data === NULL )
        {
            return false;
        }
        return true;
    }
    function Hitget($url)
    {
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,false);
        $output=curl_exec($ch);
        curl_close($ch);
        echo $output;
    }
}
