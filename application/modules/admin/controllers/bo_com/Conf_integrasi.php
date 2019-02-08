<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sett_konfig_integrasi_simpanan
 *
 * @author edisite
 */
class Conf_integrasi extends Admin_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
        
        $this->mTitle = 'BO COMMERCE - ';
    }
    public function index() {
        $crud = $this->generate_crud('com_integrasi');
       // $crud->columns('KODE_INTEGRASI','DESKRIPSI_INTEGRASI','KODE_PERK_KAS','KODE_PERK_HUTANG_POKOK','KODE_PERK_HUTANG_BUNGA','KODE_PERK_BIAYA_BUNGA','KODE_PERK_PAJAK_BUNGA','KODE_PERK_PEND_ADM','KODE_PERK_PEND_PENUTUPAN','kode_perk_zakat');
        $crud->display_as('KODE_INTEGRASI', 'KODE');
        $crud->display_as('DESKRIPSI_INTEGRASI', 'DESKRIPSI');
        $crud->display_as('KODE_PERK_HUTANG_POKOK', 'KODE_PERK_SIMPANAN');
        $crud->display_as('KODE_PERK_HUTANG_BUNGA', 'BASIL YMH DI BAYAR');
        $crud->display_as('KODE_PERK_BIAYA_BUNGA', 'BIAYA BASIL');
        $crud->display_as('KODE_PERK_PAJAK_BUNGA', 'ZAKAT/PAJAK BASIL');
        $crud->display_as('kode_perk_zakat', 'KODE PERK ZAKAT');
        $this->load->model('perk_model');
        $get_kodperk = $this->perk_model->perk();
        $data = array();
        if($get_kodperk){
            foreach ($get_kodperk as $v) {
                $data[$v->KODE_PERK] = '['.$v->KODE_PERK.'] - '.$v->NAMA_PERK;
            }
        }
        $this->load->model('app_model');
        $get_kodekantor= $this->app_model->Kode_kantor();
        $datakantor = array();
        if($get_kodekantor){
            foreach ($get_kodekantor as $v) {
                $datakantor[$v->KODE_KANTOR] = '['.$v->KODE_KANTOR.'] - '.$v->NAMA_KANTOR;
            }
        }
        $crud->field_type('KODE_PERK_COM','dropdown',$data);
        $crud->field_type('KODE_KANTOR','dropdown',$datakantor);

       // $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('Parameter');
        $this->mMenuID                  = "1050";
        $this->render_crud();
    }
}
