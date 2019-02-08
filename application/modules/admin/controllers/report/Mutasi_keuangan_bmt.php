<?php
define("DEFAULT_NILAI", "0.00");
define("DEFAULT_FALSE", "FALSE");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mutasi_keuangan_bmt
 *
 * @author edisite
 */
class Mutasi_keuangan_bmt extends Admin_Controller{
    protected $in_kodekantor = 0;
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('admin_user2_model');
        $this->load->model('kode_perk');
        $this->load->model('Trans');
        $this->load->model('admin_user2_model');
        $this->load->model('Sys_daftar_user_model');
    }
    public function Preview() {
          
        $target = $this->admin_user2_model->Uname();
        $kdperk = $this->kode_perk->Kas();
        $this->mViewData['tgl']         = "";
        $this->mViewData['datauser']    = $target; 
        $this->mViewData['kodeperk']    = $kdperk;
        
        $this->mTitle                   = "[8541]Teller laporan mutasi keuangan BMT";
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('usrid','User ID','required');        
        $this->form_validation->set_rules('kdperkiraan','Kode Perkiraan','required');
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_mutasi_keuangan_bmt');
            
        }else{            
                //post data
                $in_usrid   = $this->input->post('usrid');
                $in_kodeperk    = $this->input->post('kdperkiraan');
                //model
                $this->load->model('Sys_daftar_user_model');
                $datakantor         = $this->Sys_daftar_user_model->Unitkerja($in_usrid);                
                if($datakantor){
                    foreach($datakantor as $subdatakantor){
                        $in_kodekantor      = $subdatakantor->UNIT_KERJA;
                    }
                }
//                if($in_kodekantor == ""){
//                    session_start();
//                    $this->session->set_flashdata('msg', 'Unit Kerja dari Userid ini = '.$in_usrid.' Tidak tersedia');
//                    redirect('admin/report/mutasi_keuangan_bmt/preview1');
//                }  
                
                $mtrans             =  $this->Trans->master2($in_usrid,$in_kodeperk,$in_kodekantor);
                $tab_trans          =  $this->Trans->tabtrans2($in_usrid);
                $deb_trans          =  $this->Trans->debtrans2($in_usrid);
                $kre_trans          =  $this->Trans->kretrans2($in_usrid);
                //print_r($mtrans);
                
                if(!floatval($mtrans)){
                   return DEFAULT_FALSE;
                }
                if(!floatval($tab_trans)){
                   return DEFAULT_FALSE;
                }
                if(!floatval($deb_trans)){
                   return DEFAULT_FALSE;
                }
                if(!floatval($kre_trans)){
                   return DEFAULT_FALSE;
                }

                foreach ($mtrans as $sutrans) {
                   $debit        = $sutrans->debet;
                   $kredit      = $sutrans->kredit;             
                   if(!$debit)   {   
                       $debit = DEFAULT_NILAI;}
                   if(!$kredit) {   
                       $kredit = DEFAULT_NILAI;}  
                }
                foreach ($tab_trans as $subtab) {
                   $SETORAN        = $subtab->SETORAN;
                   $PENARIKAN      = $subtab->PENARIKAN;             
                   if(!$SETORAN)   {   
                       $SETORAN = DEFAULT_NILAI;}
                   if(!$PENARIKAN) {   
                       $PENARIKAN = DEFAULT_NILAI;}  
                }
                foreach ($deb_trans as $subdeb){
                   $POKOK_SETOR    = $subdeb->POKOK_SETOR;
                   $TITIPAN_SETOR  = $subdeb->TITIPAN_SETOR;
                   $BUNGA_SETOR    = $subdeb->BUNGA_SETOR;
                   $PAJAK_SETOR    = $subdeb->PAJAK_SETOR;

                   $POKOK_AMBIL    = $subdeb->POKOK_AMBIL;
                   $TITIPAN_AMBIL  = $subdeb->TITIPAN_AMBIL;
                   $BUNGA_AMBIL    = $subdeb->BUNGA_AMBIL;
                   $PAJAK_AMBIL    = $subdeb->PAJAK_AMBIL;
                   if(!$POKOK_SETOR){  
                       $POKOK_SETOR        = DEFAULT_NILAI; }
                   if(!$TITIPAN_SETOR){  
                       $TITIPAN_SETOR      = DEFAULT_NILAI; }
                   if(!$BUNGA_SETOR){  
                       $BUNGA_SETOR        = DEFAULT_NILAI;} 
                   if(!$PAJAK_SETOR){  
                       $PAJAK_SETOR        = DEFAULT_NILAI;} 

                   if(!$POKOK_AMBIL){  
                       $POKOK_AMBIL        = DEFAULT_NILAI;}
                   if(!$TITIPAN_AMBIL){  
                       $TITIPAN_AMBIL      = DEFAULT_NILAI; }
                   if(!$BUNGA_AMBIL){  
                       $BUNGA_AMBIL        = DEFAULT_NILAI; }
                   if(!$PAJAK_AMBIL){  
                       $PAJAK_AMBIL        = DEFAULT_NILAI; }
                }
                foreach ($kre_trans as $subkre) {
                   $REALISASI      = $subkre->REALISASI;
                   $ANGSURAN       = $subkre->ANGSURAN;

                   if(!$REALISASI){
                       $REALISASI  = DEFAULT_NILAI;}
                   if(!$ANGSURAN){
                       $ANGSURAN = DEFAULT_NILAI;}         
                } 
                $DEBSETOR       = $POKOK_SETOR + $TITIPAN_SETOR + $BUNGA_SETOR + $PAJAK_SETOR;
                $DEBAMBIL       = $POKOK_AMBIL + $TITIPAN_AMBIL + $BUNGA_AMBIL + $PAJAK_AMBIL;
                $dropkas        = $debit   - $kredit;
                $penerimaan     = $SETORAN + $DEBSETOR + $ANGSURAN;
                $pengeluaran    = $DEBAMBIL + $PENARIKAN + $REALISASI;
                $saldokas       = $penerimaan - $pengeluaran;


                $target_user         = $this->admin_user2_model->Id($in_usrid);
                foreach ($target_user as $subuser) {
                   $namadpn        = $subuser->first_name;
                   $namablk        = $subuser->last_name;            
                }
                $namauser       = $namadpn." ".$namablk;

                $this->mViewData['terima']      = $this->Rupiah($penerimaan);
                $this->mViewData['keluar']      = $this->Rupiah($pengeluaran);
                $this->mViewData['saldo']       = $this->Rupiah($saldokas);
                $this->mViewData['dropkas']     = $this->Rupiah($dropkas);        
                $this->mViewData['pengguna']    = $namauser;
                $this->mViewData['tgl']         = $this->Tglambildata();

                $this->render('report/lap_mutasi_keuagan_bmt_hasil'); 
            
            
        }
    }
    function Rupiah($rupiah) {
        return number_format ($rupiah, 2, ',', '.');
    }
    function Tglambildata() {         
        $now = date('d-m-Y');
        return $now;        
    }
}
