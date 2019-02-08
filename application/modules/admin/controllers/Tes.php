<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tes
 *
 * @author edisite
 */
class Tes extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Tesuser() {
        
        $this->mTitle .='Saldo Kas';
        $this->render('form');
       // $this->load->view('form');
        
    }
    public function Trx() {
        $data = array(
            'tid' => '9242842',
            'nasabahid' => '35200003',
            'jenis_transaksi' => 'TAB',
            'model_transaksi' => '01',
            'tipe_transaksi' => '204',
            'nilai' => 'Value',
        );
        //transaksi_id=10004&nasabah_id=35200003&jenis_transaksi=TAB
                //&model_transaksi=01&tipe_transaksi=204&nilai=Value
        $res = Modules::run('api/third_party/transaction/index/',  json_encode($data));
        print_r($res);
    }
}
