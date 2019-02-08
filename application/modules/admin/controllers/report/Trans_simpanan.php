<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of trans_simpanan
 *
 * @author edisite
 */
class Trans_simpanan extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        
        //$this->load->model('Tab_model');
        //$this->load->model('App_model');
        //$this->load->model('Sysmysysid_model');
        //
        //$this->load->model('Tab_model');
        //$this->load->model('App_model');
        //$this->load->model('Lkm_model');
        
        $this->mTitle = 'Laporan';
    }
     
    public function Preview() {

        $trg_kodetrans  = $this->Tab_model->kodetrans(); 
        $trg_integrasi  = $this->Tab_model->integrasi(); 
        $trg_produk     = $this->Tab_model->produk(); 
        $trg_ao         = $this->Tab_model->kodegroup1(); 
        $trg_wilayah    = $this->Tab_model->kodegroup2();         
        $trg_profesi    = $this->Tab_model->kodegroup3();
        
        $trg_kdkantr    = $this->App_model->kode_kantor();
        $trg_kdkolek    = $this->Tab_model->kolektor();
        
        $this->mViewData['kdtrans']     = $trg_kodetrans; 
        $this->mViewData['kdintgr']     = $trg_integrasi; 
        $this->mViewData['kdprodk']     = $trg_produk; 
        $this->mViewData['ao']          = $trg_ao; 
        $this->mViewData['wilayah']     = $trg_wilayah; 
        $this->mViewData['profesi']     = $trg_profesi; 
        $this->mViewData['kdkantr']     = $trg_kdkantr; 
        $this->mViewData['kdkolek']     = $trg_kdkolek; 
        
        $this->mTitle                   = "[8050]Lap. Transaksi Simpanan";
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('usrid','User ID','required');        
        $this->form_validation->set_rules('paytype','Tipe Pembayaran','required');
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_trans_simpanan');
            
        }else{
            //?tgl_fr=10%2F01%2F2016&tgl_to=10%2F02%2F2016&usrid=all&paytype=all
            //&paytype=all&paytype=all&paytype=all&paytype=all&paytype=all
            //&paytype=all&paytype=all&paytype=all&paytype=all&paytype=all
        }
    }
    public function Rekap() {

        $trg_kodetrans  = $this->Tab_model->kodetrans(); 
        $trg_integrasi  = $this->Tab_model->integrasi(); 
        $trg_produk     = $this->Tab_model->produk(); 
        $trg_ao         = $this->Tab_model->kodegroup1(); 
        $trg_wilayah    = $this->Tab_model->kodegroup2();         
        $trg_profesi    = $this->Tab_model->kodegroup3();
        
        $trg_kdkantr    = $this->App_model->kode_kantor();
        $trg_kdkolek    = $this->Tab_model->kolektor();
        
        $trg_pimsatu    = $this->Sysmysysid_model->Pim1();
        $this->mViewData['kdtrans']     = $trg_kodetrans; 
        $this->mViewData['kdintgr']     = $trg_integrasi; 
        $this->mViewData['kdprodk']     = $trg_produk; 
        $this->mViewData['ao']          = $trg_ao; 
        $this->mViewData['wilayah']     = $trg_wilayah; 
        $this->mViewData['profesi']     = $trg_profesi; 
        $this->mViewData['kdkantr']     = $trg_kdkantr; 
        $this->mViewData['kdkolek']     = $trg_kdkolek; 
        $this->mViewData['namabos']     = $trg_pimsatu; 
        
        $this->mTitle                   = "[8051]Lap. Transaksi Simpanan[Rekap]";
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('usrid','User ID','required');        
        $this->form_validation->set_rules('paytype','Tipe Pembayaran','required');
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_trans_simpanan_rekap');            
        }
        else{
            
            $this->load->dbforge();
//            $attributes = array('ENGINE' => 'InnoDB');
//            if($this->dbforge->create_table('tab_rekap_session', FALSE, $attributes))
//            {
//                echo "OK";
//            }else{
//                echo "NOK";
//            }
            $posts_fields=array(
            'id'=>array('type' => 'INT','constraint' => 5,'unsigned' => TRUE),
            'title'=>array('type' =>'VARCHAR','constraint' => 100),
            'content'=>array('type'=>'text'),
            'create_time'=>array('type'=>'INT','constraint'=>12));

            $this->dbforge->add_field($posts_fields);
            $this->dbforge->create_table('posts');
            
        }
    }
    public function Mutasi() {
        $this->load->model('Sysmysysid_model');
        $trg_kodetrans  = $this->Tab_model->kodetrans(); 
        $trg_integrasi  = $this->Tab_model->integrasi(); 
        $trg_produk     = $this->Tab_model->produk(); 
        $trg_ao         = $this->Tab_model->kodegroup1(); 
        $trg_wilayah    = $this->Tab_model->kodegroup2();         
        $trg_profesi    = $this->Tab_model->kodegroup3();        
        $trg_kdkantr    = $this->App_model->kode_kantor();
        $trg_kdkolek    = $this->Tab_model->kolektor();
        
        $trg_pimsatu    = $this->Sysmysysid_model->Pim1();
        $this->mViewData['kdtrans']     = $trg_kodetrans; 
        $this->mViewData['kdintgr']     = $trg_integrasi; 
        $this->mViewData['kdprodk']     = $trg_produk; 
        $this->mViewData['ao']          = $trg_ao; 
        $this->mViewData['wilayah']     = $trg_wilayah; 
        $this->mViewData['profesi']     = $trg_profesi; 
        $this->mViewData['kdkantr']     = $trg_kdkantr; 
        $this->mViewData['kdkolek']     = $trg_kdkolek; 
        $this->mViewData['namabos']     = $trg_pimsatu; 
        
        $this->mTitle                   = "[8054]Lap. Mutasi Simpanan";
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal awal','required');        
        $this->form_validation->set_rules('tgl_to','Tanggal Akhir','required');        
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_mutasi_simpanan');            
        }
        else{
            $tgl_fr             = $this->input->post('tgl_fr');
            $tgl_to             = $this->input->post('tgl_to');
            $kdtransaksi        = $this->input->post('kdtransaksi');
            $kdintegrasi        = $this->input->post('kdintegrasi');
            $kdproduk           = $this->input->post('kdproduk');
            $ao                 = $this->input->post('ao');
            $wiayah             = $this->input->post('wiayah');
            $profesi            = $this->input->post('profesi');
            $kdkantor           = $this->input->post('kdkantor');
            $kdkolek            = $this->input->post('kdkolek');

            $tgl_to     = date('Y-m-d', strtotime($tgl_to));
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));
            $res_saldo_awal = $this->Tab_model->Lap_saldo_awal($tgl_fr);            
            $res_mutasi     = $this->Tab_model->Lap_mutasi($tgl_fr,$tgl_to,$kdtransaksi,$kdintegrasi,$kdproduk,$ao,$wiayah,$profesi,$kdkantor,$kdkolek);           

            $this->mViewData['saldo_awal']      = $res_saldo_awal;
            $this->mViewData['mutasi']          = $res_mutasi;
            $this->render('report/lap_mutasi_simpanan_res');
        }
    }
    public function Anggaran_basil_pajak_adm() {
        $trg_produk     = $this->Tab_model->produk();
        $trg_kdkantr    = $this->App_model->kode_kantor();
        
        $this->mViewData['kdprodk']     = $trg_produk;
        $this->mViewData['kdkantr']     = $trg_kdkantr;
        $this->mTitle                   = "[8055]Lap. Anggaran Basil, Pajak, Adm Simpanan";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('kdproduk','produk','required');        
        $this->form_validation->set_rules('kdkantor','kdkantr','required');        

        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_basil_pajak_adm_simpanan');            
        }
        else{
            $kdproduk           = $this->input->post('kdproduk');
            $kdkantor           = $this->input->post('kdkantor');           
         
            $res_anggaran_basil     = $this->Tab_model->Lap_anggaran_basil($kdproduk,$kdkantor);           
            //var_dump($res_anggaran_basil);
            $this->mViewData['mutasi']          = $res_anggaran_basil;
            $this->render('report/lap_basil_pajak_adm_simpanan_res');
        }        
    }
    public function Ob_basil_pajak_adm() {
        $trg_integrasi  = $this->Kre_model->integrasi();
        $trg_kdkantr    = $this->App_model->kode_kantor();
        $this->mViewData['kdintgr']     = $trg_integrasi; 
        $this->mViewData['kdkantr']     = $trg_kdkantr; 
        $this->mTitle                   = "[8057]Lap. Transaksi OB Basil, Pajak dan Adm";
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal','required');              
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_ob_basil_pajak_adm');            
        }
        else{
            $kdintegrasi        = $this->input->post('kdintegrasi');
            $tgl_fr             = $this->input->post('tgl_fr');
            $kdkantor           = $this->input->post('kdkantor');
            $kdbasil            = $this->input->post('kdbasil');
            
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));
            $res_anggaran_basil     = $this->Tab_model->Lap_ob_basil_pajak_adm($kdintegrasi,$kdkantor,$tgl_fr,$kdbasil);
            $this->mViewData['mutasi']          = $res_anggaran_basil;
            $this->render('report/lap_ob_basil_pajak_adm_res');
        }
    }
    public function Nominatif() {
        $trg_integrasi  = $this->Kre_model->integrasi();
        $trg_kdkantr    = $this->App_model->kode_kantor();
        $this->mViewData['kdintgr']     = $trg_integrasi; 
        $this->mViewData['kdkantr']     = $trg_kdkantr; 
        $this->mTitle                   = "[8057]Lap. Transaksi OB Basil, Pajak dan Adm";
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal','required');              
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_ob_basil_pajak_adm');            
        }
        else{
            $kdintegrasi        = $this->input->post('kdintegrasi');
            $tgl_fr             = $this->input->post('tgl_fr');
            $kdkantor           = $this->input->post('kdkantor');
            $kdbasil            = $this->input->post('kdbasil');
            
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));
            $res_anggaran_basil     = $this->Tab_model->Lap_ob_basil_pajak_adm($kdintegrasi,$kdkantor,$tgl_fr,$kdbasil);
            $this->mViewData['mutasi']          = $res_anggaran_basil;
            $this->render('report/lap_ob_basil_pajak_adm_res');
        }
    }
    public function Simpanan_group_by_saldo() {
        
        $res_grouping                   = $this->Lkm_model->Simpanan_grouping();
        //var_dump($res_grouping);
        $this->mTitle                   = "[8064]Lap. LPS (Simpanan Dikelompokkan)";
        $this->mViewData['mutasi']      = $res_grouping;
       $this->render('report/lap_group_by_saldo');
    }
    public function Mutasi_simpanan_per_rekening() {
        $trg_kodetrans  = $this->Tab_model->kodetrans(); 
        $trg_integrasi  = $this->Tab_model->integrasi(); 
        $trg_produk     = $this->Tab_model->produk(); 
        $trg_ao         = $this->Tab_model->kodegroup1(); 
        $trg_wilayah    = $this->Tab_model->kodegroup2();         
        $trg_profesi    = $this->Tab_model->kodegroup3();        
        $trg_kdkantr    = $this->App_model->kode_kantor();
        $trg_kdkolek    = $this->Tab_model->kolektor();
        
        $trg_pimsatu    = $this->Sysmysysid_model->Pim1();
        $this->mViewData['kdtrans']     = $trg_kodetrans; 
        $this->mViewData['kdintgr']     = $trg_integrasi; 
        $this->mViewData['kdprodk']     = $trg_produk; 
        $this->mViewData['ao']          = $trg_ao; 
        $this->mViewData['wilayah']     = $trg_wilayah; 
        $this->mViewData['profesi']     = $trg_profesi; 
        $this->mViewData['kdkantr']     = $trg_kdkantr; 
        $this->mViewData['kdkolek']     = $trg_kdkolek; 
        $this->mViewData['namabos']     = $trg_pimsatu; 
        
        $this->mTitle                   = "[8078]Lap. Mutasi Simpanan Per-Rekening";
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal awal','required');        
        $this->form_validation->set_rules('tgl_to','Tanggal Akhir','required');        
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_mutasi_simpanan_per_rekening');            
        }
        else{
            $tgl_fr             = $this->input->post('tgl_fr') ?: '';
            $tgl_to             = $this->input->post('tgl_to');
            $kdtransaksi        = $this->input->post('kdtransaksi') ?: 'all';
            $kdintegrasi        = $this->input->post('kdintegrasi') ?: 'all';
            $kdproduk           = $this->input->post('kdproduk')    ?: 'all';
            $ao                 = $this->input->post('ao')          ?: 'all';
            $wiayah             = $this->input->post('wiayah')      ?: 'all';
            $profesi            = $this->input->post('profesi')     ?: 'all';
            $kdkantor           = $this->input->post('kdkantor')    ?: 'all';
            $kdkolek            = $this->input->post('kdkolek')     ?: 'all';

            $tgl_to     = date('Y-m-d', strtotime($tgl_to));
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));
            $res_saldo_awal = $this->Tab_model->Lap_saldo_awal($tgl_fr);            
            $res_mutasi     = $this->Tab_model->Lap_mutasi_per_rek($tgl_fr,$tgl_to,$kdtransaksi,$kdintegrasi,$kdproduk,$ao,$wiayah,$profesi,$kdkantor,$kdkolek);           

            $this->mViewData['saldo_awal']      = $res_saldo_awal;
            $this->mViewData['mutasi']          = $res_mutasi;
            $this->render('report/lap_mutasi_simpanan_per_rekening_res');
        }
        
    }
}
