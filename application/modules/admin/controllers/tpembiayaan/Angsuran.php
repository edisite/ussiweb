<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Angsuran
 *
 * @author edisite
 */
class Angsuran extends Admin_Controller {
    
    var $in_kode_integrasi = '';
    var $in_kode_perk_kas = '';
    var $in_nama_perk = '';
    var $in_gord = '';
    var $in_nama_perk_kredit = '';
    var $in_kode_perk_kredit_in_integrasi = '';
    var $in_kode_perk_bunga_in_integrasi = '';
    var $in_gord_kredit = '';
    var $in_nama_perk_bunga = '';
    var $in_gord_bunga = '';
    var $status_lunas = '';

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_user2_model');
    }
    public function index() {
        $crud = $this->generate_crud('kredit');
        $crud->set_model('Kre_nas_join_model');
        $crud->columns('NO_REKENING', 'NO_ALTERNATIF', 'NAMA_NASABAH','ALAMAT', 'JML_PINJAMAN', 'TGL_REALISASI', 'POKOK_SALDO_AKHIR'
                ,'VERIFIKASI','SALDO_BUNGA_YAD','SALDO_AKHIR_DEBIUS');        
        $crud->unset_add();
        $crud->unset_print();
        $crud->unset_export();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->add_action('Bayar', 'Bayar Angsuran', 'admin/tpembiayaan/angsuran/payment', '');        
        $crud->add_action('Riwayat', 'Riwayat Angsuran', 'admin/tpembiayaan/angsuran/riwayat', '');        
        $crud->callback_column('JML_PINJAMAN',array($this,'_column_bonus_right_align'));
        $crud->callback_column('POKOK_SALDO_AKHIR',array($this,'_column_bonus_right_align'));
        $crud->callback_column('SALDO_BUNGA_YAD',array($this,'_column_bonus_right_align'));
        $crud->callback_column('SALDO_AKHIR_DEBIUS',array($this,'_column_bonus_right_align'));
        $crud->callback_column('TGL_REALISASI',array($this,'Dtm_format'));
        $crud->set_subject('Realiasi Peminjaman');
	$this->mTitle.= '[6201] Transaksi Pembiayaan[ Angsuran ]';
        $this->render_crud(); 
        
    }
    function Rp($value, $row)    {
        return number_format($value,2,",",".");
    }
    function Rp1($value, $row = '')    {
        return "Rp ".number_format($value,2,",",".");
    }
    function convert_to_number($rupiah)    {
            return intval(preg_replace('/,.*|[^0-9]/', '', $rupiah));
    }
    function _column_bonus_right_align($value,$row)    {
        
        return "<span style=\"width:100%;text-align:right;display:block;\">".$this->rp($value,'Rp')."</span>";
    }
    function _column_center_align($value,$row)    {
        
        return "<span style=\"width:100%;text-align:center;display:block;\">".$value."</span>";
    }
    function Dtm_format($tgl) {
        $newDate = date("d-m-Y", strtotime($tgl));
        return $newDate;
    }    
    public function Payment($in_norek = '')    { //opik 16022017
        $this->norek = $in_norek;
        $result_data_nasabah = $this->Kre_model->Kre_nas_join_by_rek($this->norek);
        if($result_data_nasabah)
        {
            foreach($result_data_nasabah as $sub_res)
            {
                $out_norek          = $sub_res->NO_REKENING;
                $out_des_produk     = $sub_res->DESKRIPSI_PRODUK;
                $out_nasabah_id     = $sub_res->NASABAH_ID;
                $out_nama_nasabah   = $sub_res->nama_nasabah;
                $out_jml_pinjaman   = $sub_res->JML_PINJAMAN;
                $out_keterangan     = $sub_res->DESKRIPSI_TYPE_KREDIT;
                $out_tgl_realisasi  = $sub_res->TGL_REALISASI;
                $out_tgl_tempo      = $sub_res->TGL_JATUH_TEMPO;
                $out_nisbah         = $sub_res->SUKU_BUNGA_PER_TAHUN;
                $out_bunga_yad      = $sub_res->saldo_bunga_yad;
                $out_sisa           = $sub_res->saldo_akhir_debius;
                $tgl_tagihan        = $sub_res->TGL_TAGIHAN;
                $jml_angsuran       = $sub_res->JML_ANGSURAN;
            }
        }
        else
        {
            $this->messages->add('Nomor rekening tidak terdaftar', 'warning');             
            redirect('admin/tpembiayaan/angsuran');
        }
        $result_count_trans = $this->Kre_model->Count_trans_by_kodetrans($this->norek);

        if($result_count_trans){
            foreach($result_count_trans as $sub_res_count){
                $this->count_trans        = $sub_res_count->counttrans;                                          
            }
        }
        if($this->count_trans > 0){          
            $this->messages->add('Double transaksi anggsuran', 'message');             
            redirect('admin/tpembiayaan/angsuran');
        }

        $result_angsuranke = $this->Kre_model->Kre_angsuran_ke_by_rek($this->norek);
    
        if($result_angsuranke){
            foreach($result_angsuranke as $sub_ang){
                $angsuran_ke = $sub_ang->ANGSURAN_KE;
            }
        }
        if($angsuran_ke == ''){
            $angsuran_ke = 0;
        }

        if($tgl_tagihan == ''){
            $tgl_tagihan = '01';
        }

        //$arrtunggakan = array();        
        $result_data_tunggakan = $this->Kre_model->Kre_tunggakan_by_rek($this->norek,  $this->_Tgl_hari_ini());
        $result_data_tagihan = $this->Kre_model->Tag_tag_bln($this->norek);
        $in_keterangan              = $this->_Kodetrans_by_desc(DEFAULT_KODE_TRANS);
        $in_keterangan              = $in_keterangan." ".$angsuran_ke.", No Rek: ".$this->norek.", ".$out_nama_nasabah;
        $in_KUITANSI                = $this->_Kuitansi();
        
        if($result_data_tunggakan){
            foreach($result_data_tunggakan as $status)
            {
                $saldo_bunga  = $status->TUNGGAKAN_BUNGA;

                if($saldo_bunga > 0)
                {
                    $status_lunas = "TAGIHAN BELUM LUNAS";
                }
                else
                {
                    $status_lunas = "TAGIHAN SUDAH LUNAS";
                }
            }
        }else{
            $status_lunas = "TAGIHAN SUDAH LUNAS";
        }
            

        $this->mViewData['SETNOREK']         = $out_norek;
        $this->mViewData['SETPRODK']         = $out_des_produk;
        $this->mViewData['SETNSBID']         = $out_nasabah_id;
        $this->mViewData['SETNAMAP']         = $out_nama_nasabah;
        $this->mViewData['SETJMLPIN']        = $out_jml_pinjaman;
        $this->mViewData['SETKET']           = $out_keterangan;
        $this->mViewData['SETTGLREAL']       = $out_tgl_realisasi;
        $this->mViewData['SETTGTEMP']        = $out_tgl_tempo;
        $this->mViewData['SETNISBAH']        = $out_nisbah;
        $this->mViewData['SETBUYAD']         = $out_bunga_yad;
        $this->mViewData['SETSISA']          = $out_sisa;
        $this->mViewData['SETTAGIHAN']       = $result_data_tagihan;
        $this->mViewData['SETTUNGGAKAN']     = $result_data_tunggakan;
        $this->mViewData['SETMESSAGE']       = "Tunggakan s.d ".date('j F, Y', strtotime('last day of previous month'));
        $this->mViewData['SETMESSAGE2']      = "Tagihan ke : [".$angsuran_ke."] [".$this->Star_date()." - ".$this->Last_date($tgl_tagihan)."]";
        $this->mViewData['SETMESSAGE3']      = $in_keterangan;
        $this->mViewData['SETANGS']          = $result_angsuranke;
        $this->mViewData['SETKWITANSI']      = $in_KUITANSI;
        $this->mViewData['SETSTATUS']        = $status_lunas;


        $this->mTitle = "[6201] Transaksi Pembiayaan [Angsuran]";
        $this->render('kredit/angsuran');
    }
    function Payment_proces() { //opik 16022017
        $in_agentid             = $this->session->userdata('user_id') ?: '';
        $in_code_trx            = $this->input->post('kode_trans') ?: '';
        $in_nominal_pokok       = $this->input->post('pokok_transaksi') ?:'';
        $in_nominal_bunga       = $this->input->post('basil_transaksi') ?: 0;
        $in_no_rekening         = $this->input->post('no_rekening') ?: '';   
        $in_due_date            = $this->input->post('due_date') ?: '';   
          
        if(empty($in_no_rekening)){
            $this->messages->add('Nomor rekening kosong', 'warning');             
            redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
            return;
        }
        if(empty($in_agentid)){
            $this->messages->add('Userid tidak kosong', 'warning');             
            redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
        }
        if($this->Admin_user2_model->User_by_id($in_agentid)){            
        }else{
            $this->messages->add('user anda tidak valid', 'warning');             
            redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
        }
        if(empty($in_code_trx)){
            $this->messages->add('Kode transaksi kosong, periksa lagi transaksi anda', 'warning');             
            redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
        }
         if($this->Duwet_no_limit($in_nominal_pokok) == FALSE){
            $this->messages->add('Invalid Nominal bunga ['.$in_nominal_pokok.'],  periksa kembali', 'warning');             
            redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
         }
         if($this->Duwet_no_limit($in_nominal_bunga) == FALSE){
            $this->messages->add('Invalid Nominal bunga ['.$in_nominal_pokok.'],  periksa kembali', 'warning');             
            redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
         }
        if($in_nominal_pokok == 0 && $in_nominal_bunga == 0){
            $this->messages->add('Jumlah setoran anda kosong, periksa kembali', 'warning');             
            redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
        }
        
        $result_angsuranke = $this->Kre_model->Kre_angsuran_ke_by_rek($in_no_rekening);
    
        if($result_angsuranke){
            foreach($result_angsuranke as $sub_ang){
                $angsuran_ke = $sub_ang->ANGSURAN_KE;
            }
        }
        if($angsuran_ke == ''){
            $angsuran_ke = 0;
        }          
        $t_pokok            = $in_nominal_pokok ?: 0;                
        $t_bunga            = $in_nominal_bunga ?: 0;                
        $t_denda            = 0;                
        $t_adm              = 0; 

        $get_integrasi = $this->Kre_model->Kode_integrasi_by_rek($in_no_rekening);
        if($get_integrasi)      {         }
        else        {
            $this->messages->add('KODE INTEGRASI kosong / belum disetting, trasaksi otomatis tidak diproses', 'error');             
            redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);

        }
        foreach ($get_integrasi as $sub_integrasi)        {
            $in_kode_integrasi          = $sub_integrasi->kode_integrasi;
        }
       
        $res_call_kode_perk_kas         = $this->Sys_daftar_user_model->Perk_kas($in_agentid);

            if($res_call_kode_perk_kas)
            {
                foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas)
                {
                    $in_kode_perk_kas   = $sub_call_kode_perk_kas->KODE_PERK_KAS;
                }
               
            }
            if(empty($in_kode_perk_kas))
            {
                $in_kode_perk_kas = DEFAULT_KODE_PERK_KAS;
            }
            
            $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
            if($res_call_perk_kode_gord)
            {
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk   = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord        = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            $res_call_tab_integrasi_by_kd = $this->Kre_model->Integrasi_by_kd_int($in_kode_integrasi);
            foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd)
            {

                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
                $in_kode_perk_kredit_in_integrasi       =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KREDIT;
                $in_kode_perk_bunga_in_integrasi        =  $sub_call_tab_integrasi_by_kd->KODE_PERK_BUNGA;                                                      
            }                 
            $res_call_perk_kode_kredit = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kredit_in_integrasi);
            if($res_call_perk_kode_kredit)
            {
                foreach($res_call_perk_kode_kredit as $sub_call_perk_kode_kredit)
                {
                    $in_nama_perk_kredit = $sub_call_perk_kode_kredit->NAMA_PERK;
                    $in_gord_kredit = $sub_call_perk_kode_kredit->G_OR_D;
                }
            }
            $res_call_perk_kode_bunga = $this->Perk_model->perk_by_kodeperk($in_kode_perk_bunga_in_integrasi);
            if($res_call_perk_kode_bunga)
            {
                foreach($res_call_perk_kode_bunga as $sub_call_perk_kode_bunga)
                {
                    $in_nama_perk_bunga         = $sub_call_perk_kode_bunga->NAMA_PERK;
                    $in_gord_bunga              = $sub_call_perk_kode_bunga->G_OR_D;
                }
            }
            
        $gen_id_KRETRANS_ID         = $this->App_model->Gen_id();
        $in_KUITANSI                = $this->_Kuitansi();
        $in_keterangan              = $this->_Kodetrans_by_desc(DEFAULT_KODE_TRANS);
        $in_tob                     = $this->_Kodetrans_by_tob(DEFAULT_KODE_TRANS);

        $res_cek_rek                = $this->Kre_model->Kre_nas_join_by_rek($in_no_rekening);

        if(!$res_cek_rek)
        {            
            $this->messages->add('Data nasabah kosong, periksa kembali', 'warning');             
            redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
        }
        foreach ($res_cek_rek as $sub_cek_rek)
        {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
        }
        $in_keterangan              = $in_keterangan." ".$angsuran_ke.", No Rek: ".$in_no_rekening.", ".$nama_nasabah;
        $unitkerja                  = DEFAULT_KANTOR;        
        $arr_KRETRANS = array(
            'KRETRANS_ID'           => $gen_id_KRETRANS_ID,
            'TGL_TRANS'             => $this->_Tgl_hari_ini(),
            'NO_REKENING'           => $in_no_rekening,
            'MY_KODE_TRANS'         => DEFAULT_KODE_TRANS,
            'KUITANSI'              => $in_KUITANSI,
            'ANGSURAN_KE'           => $angsuran_ke,
            'POKOK'                 => $t_pokok,
            'BUNGA'                 => $t_bunga,
            'DENDA'                 => $t_denda,
            'ADM_LAINNYA'           => $t_adm,
            'KETERANGAN'            => $in_keterangan,
            'VERIFIKASI'            => '1',
            'USERID'                => $in_agentid,
            'KODE_TRANS'            => DEFAULT_KODE_TRANS,
            'NO_REKENING_TABUNGAN'  => NULL,
            'TOB'                   => $in_tob,
            'ACCRUAL'               => '',
            'Kolek'                 => NULL,
            'OTORISASI'             => NULL,
            'FLAG'                  => NULL,
            'KODE_KANTOR'           => $unitkerja,
            'KODE_PERK_OB'          => NULL,
            'discount'              => NULL
            );


            $res_ins_kretrans    = $this->Kre_model->Ins_Tbl('KRETRANS',$arr_KRETRANS);            
            if(!$res_ins_kretrans)            {               
                $this->messages->add('Proses insert data ke dalam database kretrans Gagal, trasaksi otomatis tidak diproses', 'error');             
                redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
            }

            $res_call_count_kretrans  = $this->Kre_model->Kretrans_count_by_rek($in_no_rekening);
            if(!$res_call_count_kretrans)
            {                
                $this->Kre_model->Del_kretrans($gen_id_KRETRANS_ID);                
                $this->messages->add('Proses insert data ke dalam database kretrans Gagal, trasaksi otomatis tidak diproses', 'error');             
                redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
            } 
            foreach ($res_call_count_kretrans as $sub_call_count_kretrans)
            {
                $co_REALISASI_POKOK        = $sub_call_count_kretrans->REALISASI_POKOK;
                $co_ANGSURAN_POKOK         = $sub_call_count_kretrans->ANGSURAN_POKOK;
                $co_REALISASI_BUNGA        = $sub_call_count_kretrans->REALISASI_BUNGA;
                $co_ANGSURAN_BUNGA         = $sub_call_count_kretrans->ANGSURAN_BUNGA;
                $co_ANGSURAN_DISCOUNT      = $sub_call_count_kretrans->ANGSURAN_DISCOUNT;
                $co_ANGGARAN_BUNGA_YAD     = $sub_call_count_kretrans->ANGGARAN_BUNGA_YAD;
                $co_ANGSURAN_BUNGA_YAD     = $sub_call_count_kretrans->ANGSURAN_BUNGA_YAD;
                $co_PROVISI                = $sub_call_count_kretrans->PROVISI;
                $co_TRANSAKSI_DEBIUS       = $sub_call_count_kretrans->TRANSAKSI_DEBIUS;
                $co_ANGSURAN_DEBIUS        = $sub_call_count_kretrans->ANGSURAN_DEBIUS;                
            }            
            $arr_kredit = array(
                    'POKOK_SALDO_AKHIR'             => $co_ANGSURAN_POKOK,
                    'BUNGA_SALDO_AKHIR'             => $co_ANGSURAN_BUNGA,
                    'STATUS'                        => '1',
                    'SALDO_BUNGA_YAD'               => $co_ANGSURAN_BUNGA_YAD,
                    'SALDO_AKHIR_PROVISI'           => $co_PROVISI,
                    'SALDO_AKHIR_DEBIUS'            => $co_ANGSURAN_DEBIUS
                    );
            $res_upd_tab = $this->Kre_model->upd_kredit($in_no_rekening,$arr_kredit);
            if(!$res_upd_tab)
            {                            
                $this->Kre_model->Del_kretrans($gen_id_KRETRANS_ID);                        
                $this->messages->add('Proses update data ke dalam database kredit Gagal, trasaksi otomatis tidak diproses', 'error');             
                redirect('admin/tpembiayaan/angsuran/payment/'.$in_no_rekening);
                return;
            }
            $res_call_kredit = $this->Kre_model->Kredit_by_rek($in_no_rekening);
            foreach ($res_call_kredit as $sub_call_kredit)
            {
                $kre_kd_produk          = $sub_call_kredit->KODE_PRODUK;                
                $kre_kd_integrasi       = $sub_call_kredit->KODE_INTEGRASI;                
            }
            $res_call_kre_integrasi     = $this->Kre_model->Kredit_int_by_kode($kre_kd_integrasi);
            foreach ($res_call_kre_integrasi as $sub_call_kre_integrasi)
            {
                $kre_int_kode_perk_kredit           = $sub_call_kre_integrasi->KODE_PERK_KREDIT;
                $kre_int_kode_perk_kas              = $sub_call_kre_integrasi->KODE_PERK_KAS;
                $kre_int_kode_perk_bunga            = $sub_call_kre_integrasi->KODE_PERK_BUNGA;
                $kre_int_kode_perk_adm_lainya       = $sub_call_kre_integrasi->KODE_PERK_ADM_LAINNYA;
                $kre_int_kode_perk_denda            = $sub_call_kre_integrasi->KODE_PERK_DENDA;
            }
            
            $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($kre_int_kode_perk_kredit,'D');
            foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all)
            {
                $count_as_total = $sub_call_perk_kode_all->total;
            }
            $gen_id_MASTER              = $this->App_model->Gen_id();  
            $arr_master = array(
                    'TRANS_ID'              => $gen_id_MASTER,
                    'KODE_JURNAL'           => 'KRE',
                    'NO_BUKTI'              => $in_KUITANSI,
                    'TGL_TRANS'             => $this->_Tgl_hari_ini(),
                    'URAIAN'                => $in_keterangan,
                    'MODUL_ID_SOURCE'       => 'KRE',
                    'TRANS_ID_SOURCE'       => $gen_id_KRETRANS_ID,
                    'USERID'                => $in_agentid,
                    'KODE_KANTOR'           => $unitkerja
                    );
            $gen_id_t_detail            = $this->App_model->Gen_id();  
            $total_debet                = $t_pokok + $t_bunga + $t_adm + $t_denda;

            $ar_trans_detail = array();
            if(round($t_pokok) == 0)            {            }
            else            {
            $ar_trans_detail[] = array( // kredit
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_kredit, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_pokok);
            }
            if(round($t_bunga) == 0)            {            }
            else            {
            $ar_trans_detail[] = array( // bunga
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_bunga, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_bunga);
            }
            if(round($t_adm) == 0)            {            }
            else            {
            $ar_trans_detail[] = array( // adm
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_adm_lainya, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_adm);
            }
            if(round($t_denda) == 0)           {            }
            else            {
            $ar_trans_detail[] = array( // denda
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_denda, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_denda);
            }
            $ar_trans_detail[] = array( // angsuran
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $in_kode_perk_kas, 
                            'DEBET'         =>  $total_debet, 
                            'KREDIT'        =>  '0');
            $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
            if($res_run)           {                       }
            else            {            
                $this->Kre_model->Del_kretrans($gen_id_KRETRANS_ID);            
            }   
        $in_addpoin = array(
            'tid' => $gen_id_KRETRANS_ID,
            'agent' => $in_agentid,
            'kode' => DEFAULT_KODE_TRANS,
            'jenis' => 'KRE',
            'nilai' => $total_debet,
            );

        $arr_upd_ses = array(
                'status' => '1',
                'master_id' => $gen_id_MASTER,
                'keterangan' => 'Sudah diproses',
                'lastupd' => date('Y-m-d H:i:s')
            );
        $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);        
        $this->getid_session = $this->_rand_id();
        $this->mViewData['note_name_nsb'] = $nama_nasabah;
        $this->mViewData['note_rekening'] = $in_no_rekening;
        $this->mViewData['note_due_date'] = $in_due_date;
        $this->mViewData['note_angsuran'] = $angsuran_ke;
        $this->mViewData['note_serial_n'] = $gen_id_KRETRANS_ID;
        $this->mViewData['note_pokok']    = $this->Rp1($t_pokok);
        $this->mViewData['note_basil']    = $this->Rp1($t_bunga);
        $this->mViewData['note_total']    = $this->Rp1($total_debet);
        $this->mViewData['note_code']    = $this->getid_session;
        
        
        $setanu = array(
            'note_name_nsb'     => $nama_nasabah,
            'note_rekening'     => $in_no_rekening,
            'note_due_date'     => $in_due_date,
            'note_angsuran'     => $angsuran_ke,
            'note_serial_n'     => $gen_id_KRETRANS_ID,
            'note_pokok'        => $this->Rp1($t_pokok),
            'note_basil'        => $this->Rp1($t_bunga),
            'note_total'        => $this->Rp1($total_debet),
            'note_code'         => $this->getid_session
        );
        $this->session->unset_userdata($this->getid_session);
        $this->session->set_userdata($this->getid_session, json_encode($setanu));
        $this->mTitle = "[6201] Transaksi Pembiayaan [Angsuran]";
        $this->render('kredit/angsuran_notice');
    }
    
    function Payment_print($in_data =  '') {
        
        //var_dump($this->session->userdata());
        if(empty($in_data)){
            $this->messages->add('cannot access', 'info');             
            redirect('admin/tpembiayaan/angsuran');
            return;
        }
        if($this->session->userdata($in_data)){
            $get_data = $this->session->userdata($in_data);
            //var_dump($get_data);
            $cek = json_decode($get_data);
            var_dump($cek);
            $data = array();
//            foreach ($cek as $val) {
//                $data['note_name_nsb'] = $val->note_name_nsb ?: '';
//                $data['note_rekening'] = $val->note_rekening ?: '';
//                $data['note_due_date'] = $val->note_due_date ?: '';
//                $data['note_angsuran'] = $val->note_angsuran ?: '';
//                $data['note_serial_n'] = $val->note_serial_n ?: '';
//                $data['note_pokok']    = $this->Rp1($val->note_pokok ?: 0);
//                $data['note_basil']    = $this->Rp1($val->note_basil ?: 0);
//                $data['note_total']    = $this->Rp1($val->note_total ?: 0);
//            }
            //echo "<br>";
            //var_dump($data);
           // $this->load->view('kredit/angsuran_print',$data);
        }else{
            $this->messages->add('expired', 'info');             
            redirect('admin/tpembiayaan/angsuran');
            return;
        }
        
    }
    protected function _Tgl_hari_ini(){
        return Date('Y-m-d');
    }
    protected function Star_date() {
        $hari_ini = date("Y-m-d");
        $tgl_pertama = date('01 F Y', strtotime($hari_ini));
        return $tgl_pertama;
    }
    protected function Last_date($tgl) {
        $hari_ini = $tgl." ".date("F Y");
        return $hari_ini;
    }
    protected function _Kodetrans_by_desc($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Kre_model->Kodetrans_by_kodetrans($in_kd_trans);
        if($kodetrans_desc){
            foreach($kodetrans_desc as $res_desc){
                    $out_desc          = $res_desc->deskripsi;
                }
        }else{
            $out_desc   = "";
        }
        return $out_desc;
    }
    protected function _Kuitansi($out_nokwi = '') {
        $result_kwitn = $this->Kre_model->Kwitansi();
            if($result_kwitn){
                foreach($result_kwitn as $res_kwi){
                    $out_nokwi          = $res_kwi->KUITANSI;
                }
                
            }
            if(empty($out_nokwi)){
                $out_nokwi = "Pby.0001";
            }else{
                $skey = explode(".", $out_nokwi);
                $vkey0 = $skey[0];
                $vkey1 = $skey[1];
                if(strlen($vkey1) < 5){
                        $c = strlen($vkey1);
                        $c = 5 - $c;
                        $vv = '';
                        for($v=1;$v < $c;$v++){
                            $vv .= '0';
                        }
                }
                $out_nokwi = $vkey0.'.'.$vv.($vkey1 + 1);
            }
            return $out_nokwi;
    }
    protected function _Kodetrans_by_tob($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Kre_model->Kodetrans_by_kodetrans($in_kd_trans);
        if($kodetrans_desc){
            foreach($kodetrans_desc as $res_desc){
                    $out_tob          = $res_desc->TOB;
                }
        }else{
            $out_tob   = NULL;
        }
        return $out_tob;
    }
    protected function _rand_id() {       
        $in_random = random_string('alnum', 20); 
        if(empty($in_random)){
            $in_random = random_string('alnum', 20);
        }
        return date('His').$in_random;
    }

}

define('DEFAULT_KODE_PERK_KAS', '10101');
define('DEFAULT_KODE_TRANS', '300');
define('DEFAULT_KANTOR', '35');