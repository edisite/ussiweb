<?php
define("DEFAULT_NILAI", "0.00");
define("DEFAULT_FALSE", "FALSE");

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cek_saldo_kas
 *
 * @author edisite
 */
class Saldo_kas extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->library('form_builder');
        $this->load->model('Trans');
        $this->load->model('kode_perk');
        $this->load->model('admin_user2_model');
        $this->mTitle = '[8401]Lap.Per-Teller[Cek Saldo Kas]';
    }
    public function Cek() {                
        
        
        $target = $this->admin_user2_model->Uname();
        $this->mViewData['datauser'] = $target;
        $target2   = $this->kode_perk->kas();
        $this->mViewData['datakas'] = $target2;
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('usrid','Username','required');        
        $this->form_validation->set_rules('kode_perk','Kode Perkiraan','required');
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');    
            $this->render('report/lap_saldo_kas_teller');  
            
        }else{
               $in_usrid = $this->input->post('usrid');
               $in_kodeperk = $this->input->post('kode_perk');
               $mtrans             =  $this->Trans->master($in_usrid,$in_kodeperk);
               $tab_trans          =  $this->Trans->tabtrans($in_usrid);
               $deb_trans          =  $this->Trans->debtrans($in_usrid);
               $kre_trans          =  $this->Trans->kretrans($in_usrid);
               if(!$mtrans){
                   return FALSE;
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
                   $penerimaan        = $sutrans->penerimaan;
                   $pengeluaran      = $sutrans->pengeluaran;             
                   if(!$penerimaan)   {   
                       $penerimaan = DEFAULT_NILAI;}
                   if(!$pengeluaran) {   
                       $pengeluaran = DEFAULT_NILAI;}  
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
               $dropkas        = $penerimaan   - $pengeluaran;
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

               $this->render('report/lap_saldo_kas_hasil');               
        }
       
    }   
    public function Show() {
        $in_usrid = $this->input->post('usrid');
        $in_kodeperk = $this->input->post('kode_perk');
        $message = array();
        if($in_usrid == ""){
            redirect('admin/report/saldo_kas/cek');
        }
        if($in_kodeperk == ""){
            redirect('admin/report/saldo_kas/cek');
        }
               
        $mtrans             =  $this->Trans->master($in_usrid,$in_kodeperk);
        $tab_trans          =  $this->Trans->tabtrans($in_usrid);
        $deb_trans          =  $this->Trans->debtrans($in_usrid);
        $kre_trans          =  $this->Trans->kretrans($in_usrid);
        if(!$mtrans){
            return FALSE;
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
            $penerimaan        = $sutrans->penerimaan;
            $pengeluaran      = $sutrans->pengeluaran;             
            if(!$penerimaan)   {   
                $penerimaan = DEFAULT_NILAI;}
            if(!$pengeluaran) {   
                $pengeluaran = DEFAULT_NILAI;}  
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
        $dropkas        = $penerimaan   - $pengeluaran;
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
        
        $this->render('report/lap_saldo_kas_hasil');   
    } 
    function Rupiah($rupiah) {
        return number_format ($rupiah, 2, ',', '.');
    }
    function Tglambildata() {         
        $now = strtotime(date("d-m-Y"));
        return date('d-m-Y', strtotime('-1 day', $now));        
    }
}
