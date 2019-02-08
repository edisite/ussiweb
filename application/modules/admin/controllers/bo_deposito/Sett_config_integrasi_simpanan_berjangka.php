<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sett_config_integrasi_simpanan_berjangka
 *
 * @author edisite
 */
class Sett_config_integrasi_simpanan_berjangka extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        
        $this->mTitle = 'BO DEPOSITO';
    }
    public function Getlist() {
        $crud = $this->generate_crud('dep_integrasi');
        $crud->columns('KODE_INTEGRASI','DESKRIPSI_INTEGRASI','KODE_PERK_KAS','KODE_PERK_HUTANG_POKOK',
                'KODE_PERK_HUTANG_BUNGA','KODE_PERK_BIAYA_BUNGA','KODE_PERK_PAJAK_BUNGA','KODE_PERK_PEND_ADM'
                ,'KODE_PERK_TITIPAN_BUNGA','KODE_PERK_BDD','kode_perk_zakat','KODE_PERK_CADANGAN_BUNGA');
        $crud->display_as('KODE_INTEGRASI', 'KODE');
        $crud->display_as('DESKRIPSI_INTEGRASI', 'DESKRIPSI');
        $crud->display_as('KODE_PERK_HUTANG_POKOK', 'KODE PERK SIMP BERJANGKA');
        $crud->display_as('KODE_PERK_HUTANG_BUNGA', 'KODE PERK BASIL YMH DI BAYAR');
        $crud->display_as('KODE_PERK_BIAYA_BUNGA', 'KODE PERK BIAYA BAGI HASIL');
        $crud->display_as('KODE_PERK_PAJAK_BUNGA', 'KODE PERK PAJAK BAGI HASIL');
        $crud->display_as('kode_perk_zakat', 'KODE PERK ZAKAT');
        $crud->display_as('KODE_PERK_TITIPAN_BUNGA', 'KODE PERK TITIPAN BAGI HASIL');
        $crud->display_as('KODE_PERK_CADANGAN_BUNGA', 'KODE PERK CADANGAN');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('Parameter');
	$this->mTitle.= '[2003]Setting Konfigurasi INTEGRASI SIMPANAN';
        $this->render_crud();
    }
}
