<?php
define('setor_my_kode_trans', '100');
define('setor_kode_trans', '100');
define('tarik_my_kode_trans', '200');
define('tarik_kode_trans', '200');
define('setor_sandi', '01');
define('tarik_sandi', '02');

define('KODE_KANTOR_DEFAULT', '35');
define('ADM_DEFAULT', '0.00');
define('KODEPERKKAS_DEFAULT', '10101');
define('veririfikasi', '1');
define('kode_kolektor', '000');
define('kode_kantor_default', '35');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Simpanan
 *
 * @author edisite
 */
class Simpanan extends Admin_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('tabung');  
        //$crud->set_theme('datatables');
        $crud->set_model('Master_simpan_model');
        $crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT',
                'SALDO_AKHIR','TGL_REGISTER');
                //,'VERIFIKASI');
                //,'BUNGA_BLN_INI','PAJAK_BLN_INI','ADM_BLN_INI','ZAKAT_BLN_INI','KODE_PRODUK','KODE_INTEGRASI');       
        //$crud->where('tabung.VERIFIKASI', '1');
        $crud->fields('NO_REKENING');
        $crud->display_as('BUNGA_BLN_INI','BASIL BULAN INI');
        $crud->display_as('PAJAK_BLN_INI','PAJAK');
        $crud->display_as('ZAKAT_BLN_INI','ZAKAT');
        $crud->display_as('ADM_BLN_INI','ADM');   
        
        $crud->add_action('Setor/Tarik', 'Pilih', 'admin/tsimpanan/simpanan/simpan_form', '');
        $crud->add_action('Print Buku', 'Pilih', 'admin/tsimpanan/cetak/buku_tab', '');
        
        $crud->callback_column('SALDO_AKHIR',array($this,'_column_bonus_right_align'));
        $crud->callback_column('BUNGA_BLN_INI',array($this,'_column_bonus_right_align'));
        $crud->callback_column('PAJAK_BLN_INI',array($this,'_column_bonus_right_align'));
        $crud->callback_column('BUNGA',array($this,'_column_bonus_right_align'));
        $crud->callback_column('ADM_BLN_INI',array($this,'_column_bonus_right_align'));
            
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();
        $crud->set_subject('NASABAH');
	$this->mTitle.= '[6000] Transaksi Simpanan';
        $this->render_crud();
    }
    
    function Rp($value, $row)
    {
        return number_format($value,2,",",".");
    }
    function Rp1($value, $row)
    {
        return "Rp ".number_format($value,2,",",".");
    }
    function convert_to_number($rupiah)
    {
            return intval(preg_replace('/,.*|[^0-9]/', '', $rupiah));
    }
     function _column_bonus_right_align($value,$row)
    {
        
        return "<span style=\"width:100%;text-align:right;display:block;\">".$this->rp($value,'Rp')."</span>";
    }
    function _column_center_align($value,$row)
    {
        
        return "<span style=\"width:100%;text-align:center;display:block;\">".$value."</span>";
    }
    
    public function Simpan_form($no_rek) {
        $this->norek = $no_rek;
        $result_data_nasabah = $this->Tab_model->Tab_pro_nas_join($this->norek);
        if($result_data_nasabah){
            foreach($result_data_nasabah as $sub_res){
                $out_norek          = $sub_res->NO_REKENING;
                $out_nasabah_id     = $sub_res->NASABAH_ID;
                $out_nama_nasabah   = $sub_res->nama_nasabah;
                $out_alamat         = $sub_res->alamat;
                $out_des_produk     = $sub_res->DESKRIPSI_PRODUK;
                $out_tgl_mulai      = $sub_res->TGL_REGISTER;
                $out_tgl_jt         = $sub_res->TGL_JT;
                $out_alternatif         = $sub_res->NO_ALTERNATIF;
                $out_nominal        = $sub_res->SALDO_AKHIR;             
            }
        }else{
            redirect();
        }        
        $result_sandi = $this->Tab_model->Sandi_trans();        
        $result_kolek = $this->Tab_model->Kolektor();        
            
        $this->mViewData['SETNOREK']         = $out_norek;
        $this->mViewData['SETNSBID']         = $out_nasabah_id;
        $this->mViewData['SETNAMAP']         = $out_nama_nasabah;
        $this->mViewData['SETADDRS']         = $out_alamat;
        $this->mViewData['SETPRODK']         = $out_des_produk;
        $this->mViewData['SETTGLMU']         = $out_tgl_mulai;
        $this->mViewData['SETTGLJT']         = $out_tgl_jt;
        $this->mViewData['SETNOMIN']         = $out_nominal;
        $this->mViewData['SETALTER']         = $out_alternatif;
        $this->mViewData['SETDESCR']         = " ".$out_norek."[ ".$out_nama_nasabah." ]";
        $this->mViewData['SETSANDT']         = $result_sandi;
        $this->mViewData['SETKOLEK']         = $result_kolek;
        
        $this->mTitle = "[6000] Transaksi Simpanan";
        $this->render('simpanan/tsimpanan');
    }
     public function Simpan_post() {

// <!--         $trace_id   = $this->logid();
//         $this->logheader($trace_id); -->
        date_default_timezone_set('Asia/Jakarta');
        $now = new DateTime();
        $DateTime = $now->format('Ymd');
        //print_r($this->input->post());
        $setor_my_kode_trans = 100;
        $tarik_my_kode_trans = 200;
        $in_ver              = 1;
        $in_kd_kantor        = 35;
        $in_kd_trans            = setor_kode_trans;

        $in_KODETRANS        = $this->input->post('kode_trans') ?: '';
        $in_NO_REKENING        = $this->input->post('no_rekening') ?: '';
        $in_TGLTRANSAKSI        = $this->input->post('tgl_transaksi') ?: '';
        $in_USERID              = $this->session->userdata('user_id') ?: '';
        //var_dump($this->session->userdata());
        if($in_TGLTRANSAKSI == '')
        {
            $in_TGLTRANSAKSI  = $DateTime;
        }
        else
        {
            $in_TGLTRANSAKSI  = $this->input->post('tgl_transaksi');
        }

        $nama_nasabah          = $this->input->post('nama_nasabah');
        $in_SANDI               = $this->input->post('sandi');
        $in_NOKWITANSI          = $this->input->post('no_kwitansi');
        $in_KOLEKTOR            = $this->input->post('kolektor');
        $in_ADMTUTUP            = $this->input->post('adm_tutup');;
        $in_PAJAK               = $this->input->post('pajak');
        //$in_KETERANGAN          = $this->input->post('keterangan');


        if($in_KODETRANS == 100)
        {
            if (empty($in_ADMTUTUP))
            {
               $in_POKOK = $this->input->post('jml_setor') ?: 0;                
            }
            else
            {
               $in_POKOK = $this->input->post('total_diterima') ?: 0;              
            }            
            
            $in_mykdetrans  = $setor_my_kode_trans;

            $in_DEBET100   = $in_POKOK ;
            $in_KREDIT100  = 0 ;
            $in_DEBET200   = 0;
            $in_KREDIT200  = $in_POKOK ;
            
        }
        else if($in_KODETRANS == 200)
        {
            if (empty($in_ADMTUTUP))
            {
               $in_POKOK = $this->input->post('jml_tarik') ?: 0;                
            }
            else
            {
               $in_POKOK = $this->input->post('total_diterima') ?: 0;              
            }     
            $in_mykdetrans  = $tarik_my_kode_trans;

            $in_DEBET100   = 0 ;
            $in_KREDIT100  = $in_POKOK;
            $in_DEBET200   = $in_POKOK ;
            $in_KREDIT200  = 0;
            
        }else{
            $this->messages->add('Error kode transaksi','error');
            redirect(base_url().'admin/tsimpanan/simpanan');
        }


        $this->load->model('App_model');
        $this->load->model('Tab_model');
        $this->load->model('Perk_model');
        $this->load->model('Sysmysysid_model');
        $this->load->model('Sys_daftar_user_model');

        
        $gen_id_TABTRANS_ID         = $this->App_model->Gen_id();
        $gen_id_COMMON_ID           = $this->App_model->Gen_id();
        $gen_id_MASTER              = $this->App_model->Gen_id();
        $in_KUITANSI                = $this->_Kuitansi();
        $in_keterangan              = $this->_Kodetrans_by_desc($in_kd_trans);
        $in_tob                     = $this->_Kodetrans_by_tob($in_kd_trans);
        if($in_KODETRANS == 100)
        {
            $in_keterangan              = "Setoran Tabungan tunai : an ".$in_NO_REKENING." ".$nama_nasabah;
        }
        else if($in_KODETRANS == 200)
        {
            $in_keterangan              = "Pengambilan Tabungan tunai : an ".$in_NO_REKENING." ".$nama_nasabah;
        }
        $unitkerja                  = $in_kd_kantor;
        $arr_data = array(
            'TABTRANS_ID' =>$gen_id_TABTRANS_ID, 
            'KODE_TRANS'     =>$in_KODETRANS,
            'MY_KODE_TRANS'     =>$in_mykdetrans,
            'NO_REKENING'     =>$in_NO_REKENING, 
            'TGL_TRANS'     =>$in_TGLTRANSAKSI,      
            'SANDI_TRANS'             =>$in_SANDI,
            'VERIFIKASI'        =>$in_ver,               
            'KUITANSI'       =>$in_KUITANSI, 
            'TOB'               =>$in_tob,      
            'KODE_KOLEKTOR'          =>$in_KOLEKTOR,
            'USERID'            =>$in_USERID,          
            'POKOK'             =>$in_POKOK,             
            'ADM_PENUTUPAN'   =>$in_ADMTUTUP,              
            'ADM'             =>$in_PAJAK,             
            'KETERANGAN'        =>$in_keterangan,
            'COMMON_ID'         =>$gen_id_COMMON_ID               
            );
                
        $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
        $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
        $this->Tab_model->Saldo_upd_by_rekening($in_rkning);
        /*$res_sum12  = $this->Tab_model->Sum_1_2_taptrans($in_NO_REKENING);
        foreach ($res_sum12 as $sub12) {
                $setoran12      = $sub12->SETORAN;
                $penarikan12    = $sub12->PENARIKAN;
                $setoran_bunga12    = $sub12->SETORAN_BUNGA;
                $penarikan_bunga12    = $sub12->PENARIKAN_BUNGA;
            }                        

            $hasil      = $setoran12 - $penarikan12;
            $bunga      = $setoran_bunga12 - $penarikan_bunga12;                        
            $datatabung = array(
                        'SALDO_AKHIR' => $hasil,
                        'STATUS' => '1',
                        'SALDO_AKHIR_TITIPAN_BUNGA' => $bunga
                );                       
        $res_upd_tab = $this->Tab_model->upd_tabung($in_NO_REKENING,$datatabung);
         * */
         
        $res_call_tab_trans = $this->Tab_model->Tab_trans($gen_id_TABTRANS_ID);
        $res_call_tab   = $this->Tab_model->Tab_byrek($in_NO_REKENING);
        if($res_call_tab){
            foreach($res_call_tab as $sub_call_tab){
                    $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
                }
        }else{
            $in_kode_integrasi = "";
        }
        $this->logAction('result', '', array('kode_integrasi' => $in_kode_integrasi), 'kode integrasi');
            
        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_USERID);
            if($res_call_kode_perk_kas){
                foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                    $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
                }               
            } 
            if(empty($in_kode_perk_kas)){
                $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
            }
        $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
        if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                }
            }else{
                $in_nama_perk = "";
                $in_gord            = "";
            }
        $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
         foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
                $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
                $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;                                                      
            }
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
                    foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
                $count_as_total = $sub_call_perk_kode_all->total;
            }

        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
        foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
                $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE;
        }

             $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $DateTime, 
                'URAIAN'            =>  $in_keterangan, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                'USERID'            =>  $in_USERID, 
                'KODE_KANTOR'       =>  $unitkerja
            );

            // $ar_trans_detail = array(
            //     array(
            //         'TRANS_ID'      =>  $this->App_model->Gen_id(), 
            //         'MASTER_ID'     =>  $gen_id_MASTER, 
            //         'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
            //         'DEBET'         =>  $in_DEBET100, 
            //         'KREDIT'        =>  $in_KREDIT100                                                             
            //     ),array(
            //         'TRANS_ID'      =>  $this->App_model->Gen_id(), 
            //         'MASTER_ID'     =>  $gen_id_MASTER, 
            //         'KODE_PERK'     =>  $in_kode_perk_kas, 
            //         'DEBET'         =>  $in_DEBET200, 
            //         'KREDIT'        =>  $in_KREDIT200
            //     )
            // );

            $ar_trans_detail = array(
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                    'DEBET'         =>  $in_DEBET200, 
                    'KREDIT'        =>  $in_KREDIT200
                ),array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_kas, 
                    'DEBET'         =>  $in_DEBET100, 
                    'KREDIT'        =>  $in_KREDIT100                                                             
                )
            );           

        $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
        if($res_run){                
                $in_addpoin = array(
                    'tid'   => $gen_id_TABTRANS_ID,
                    'agent' => $in_USERID,
                    'kode'  => $in_kd_trans,
                    'jenis' => 'TAB',
                    'nilai' => $in_POKOK
                );
                $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
                $this->messeges->add('Transaksi anda berhasil di proses','info');
            }else{            
                $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);        
                $this->messeges->add('Problem Error','warning');
            }          
        redirect (base_url().'admin/tsimpanan/simpanan/');
            //var_dump($arr_data);
    }
    public function Kolektif() {
            $this->form_validation->set_rules('in_norek','Nomor Rekening','required|min_length[10]|max_length[14]');        
          
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->mViewData['SETNOREK']         = "";
            $this->mViewData['SETNAMAP']         = "";
            $this->mViewData['SETADDRS']         = "";
            $this->mViewData['SETNOMIN']         = "";
            $this->mViewData['SETNOKWI']         = "";
            $this->mViewData['SETSANDT']         = "";
            $this->mViewData['SETKOLEK']         = "";
            $this->mViewData['SETBUTON']         = "disabled";
            $this->mViewData['SETTABLE']         = $this->Kolektif_src_nsbah();
            $this->mTitle = "[6003] Transaksi Simpanan[Kolektif]";
            $this->render('simpanan/kolektif');  
        }else{
            $in_norek   = $this->input->post('in_norek');                        
            $result_nsbah = $this->Tab_model->Tab_nas($in_norek);
            if($result_nsbah){
                foreach($result_nsbah as $sub_snbah){
                    $out_norek          = $sub_snbah->no_rekening;
                    $out_nama_nasabah   = $sub_snbah->nama_nasabah;
                    $out_alamat         = $sub_snbah->alamat;
                    $out_nominal        = $sub_snbah->saldo_akhir;
                }
            }else{
                echo "empty";
                return;
            }
            $result_kwitn = $this->Kre_model->Kwitansi();
            if($result_kwitn){
                foreach($result_kwitn as $res_kwi){
                    $out_nokwi          = $res_kwi->KUITANSI;
                }
            }else{
                $out_nokwi = "";
            }
            $result_sandi = $this->Tab_model->Sandi_trans();        
            $result_kolek = $this->Tab_model->Kolektor();
            
            $this->mViewData['SETNOREK']         = $out_norek;
            $this->mViewData['SETNAMAP']         = $out_nama_nasabah;
            $this->mViewData['SETADDRS']         = $out_alamat;
            $this->mViewData['SETNOMIN']         = $out_nominal;
            
            $this->mViewData['SETSANDT']         = $result_sandi;
            $this->mViewData['SETKOLEK']         = $result_kolek;
            $this->mViewData['SETSANDT']         = $result_sandi;
            $this->mViewData['SETKOLEK']         = $result_kolek;                    
            $this->mViewData['SETNOKWI']         = $out_nokwi;
            $this->mViewData['SETBUTON']         = "";
            $this->mViewData['SETTABLE']         = $this->Kolektif_src_nsbah();
            $this->mTitle = "[6003] Transaksi Simpanan[Kolektif]";
            $this->render('simpanan/kolektif');
          
        }  
    }
    public function Kolektif_ins_table() {
          $in_tgl           = $this->input->post('ftgl');
          $in_kwitansi      = $this->input->post('fkwitansi');
          $in_sandi         = $this->input->post('fsandi');
          $in_kolektor      = $this->input->post('fkolektor');
          $in_norek         = $this->input->post('fnorek');
          $in_namap         = $this->input->post('fnama');
          $in_alamat        = $this->input->post('falamat');
          $in_saldo         = $this->convert_to_number($this->input->post('fsaldo'));
          $in_jsetoran      = $this->input->post('fjmlsetoran');
          $in_jpenarikan    = $this->input->post('fjmlpenarikan');
          
//          $getid        = $this->App_model->generate_id();
//          $dataid       = $getid->ID;
         // $dataid   = rand(10000, 99999);
          
          $data = $this->Tab_model->Temp_tabtrans($in_tgl,$in_norek,$in_kwitansi,$in_saldo,$in_jsetoran,$in_jpenarikan,$in_sandi,$in_kolektor,$in_namap);
          if($data){
              echo "ok";
          }else{
              echo "nok";
          }
          redirect('admin/tsimpanan/simpanan/kolektif');
          
                
    }
    protected function Kolektif_src_nsbah() {
          $respon = $this->Tab_model->List_temp_tabtrans(ACT_EDIT,ACT_DELETE);
          return $respon;      
    }
    protected function Group_src_nsbah() {
          $respon = $this->Tab_model->Tab_nas_group();
          return $respon;      
    }
    public function Kolektif_delete($in_nid) {
        if($in_nid){
            $this->Tab_model->Del_temp_tabtrans_byid($in_nid);           
        }
        redirect('admin/tsimpanan/simpanan/kolektif');
    }
    public function Kolektif_cancel() {
        $this->Tab_model->Del_temp_tabtrans_cancel();           
        redirect('admin/');
    }
    public function Kolektif_edit($in_nid,$in_norek) {                      
        $result_nsbah = $this->Tab_model->Tab_nas($in_norek);
        if($result_nsbah){
            foreach($result_nsbah as $sub_snbah){
                $out_norek          = $sub_snbah->no_rekening;
                $out_nama_nasabah   = $sub_snbah->nama_nasabah;
                $out_alamat         = $sub_snbah->alamat;
                $out_nominal        = $this->Rp($sub_snbah->saldo_akhir,'');
            }
        }else{
            echo "empty";
            return;
        }
        $result_kwitn = $this->Kre_model->Kwitansi();
        if($result_kwitn){
            foreach($result_kwitn as $res_kwi){
                $out_nokwi          = $res_kwi->KUITANSI;
            }
        }else{
            $out_nokwi = "";
        }
        $result_sandi = $this->Tab_model->Sandi_trans();        
        $result_kolek = $this->Tab_model->Kolektor();
        $result_temp = $this->Tab_model->List_temp_tabtrans_byid($in_nid);
        if($result_temp){
            foreach($result_temp as $sub_temp){
                $out_setoran        = $sub_temp->SETORAN;
                $out_penarikan      = $sub_temp->PENARIKAN;
                $out_sandi          = $sub_temp->SANDI_TRANS;
                $out_kolek          = $sub_temp->KODE_KOLEKTOR;                
            }
        }else{
            echo "empty";
            //return;
        }

        $this->mViewData['SETNOREK']         = $out_norek;
        $this->mViewData['SETNAMAP']         = $out_nama_nasabah;
        $this->mViewData['SETADDRS']         = $out_alamat;
        $this->mViewData['SETNOMIN']         = $out_nominal;

        $this->mViewData['SETSANDT']         = $result_sandi;
        $this->mViewData['SETKOLEK']         = $result_kolek;
        $this->mViewData['SETSANDT']         = $result_sandi;
        $this->mViewData['SETKOLEK']         = $result_kolek;                    
        $this->mViewData['SETNOKWI']         = $out_nokwi;
        $this->mViewData['SETSETOR']         = $out_setoran;
        $this->mViewData['SETTARIK']         = $out_penarikan;
        $this->mViewData['SETKOLET']         = $out_kolek;
        $this->mViewData['SETSANDI']         = $out_sandi;
        $this->mViewData['SETTRAID']         = $in_nid;
        $this->mViewData['SETBUTON']         = "";
        $this->mViewData['SETTABLE']         = $this->Kolektif_src_nsbah();
        $this->mTitle = "[6003] Transaksi Simpanan[Kolektif]";
        $this->render('simpanan/kolektif_edit');
    }
    public function Kolektif_upd() {
          $in_tgl           = $this->input->post('ftgl');
          $in_kwitansi      = $this->input->post('fkwitansi');
          $in_sandi         = $this->input->post('fsandi');
          $in_kolektor      = $this->input->post('fkolektor');
          $in_norek         = $this->input->post('fnorek');
          $in_namap         = $this->input->post('fnama');
          $in_alamat        = $this->input->post('falamat');
          $in_saldo         = $this->input->post('fsaldo');
          $in_jsetoran      = $this->input->post('fjmlsetoran');
          $in_jpenarikan    = $this->input->post('fjmlpenarikan');
          $in_tid    = $this->input->post('ftid');
          $data = $this->Tab_model->Upd_temp_tabtrans($in_tgl,$in_norek,$in_kwitansi,$in_saldo,$in_jsetoran,$in_jpenarikan,$in_sandi,$in_kolektor,$in_namap,$in_tid);
          if($data){
              echo "ok";
          }else{
              echo "nok";
          }
          
          redirect('admin/tsimpanan/simpanan/kolektif');
    }
    public function SimpKolektif_Save() {
         /*if (!$this->ion_auth->in_group(array('webmaster', 'admin', 'manager', 'staff','agent')) ){ 
             return;
         }*/
             $res_unit_kerja    = $this->Sys_daftar_user_model->Unitkerja($this->session->userdata('user_id'));
             if($res_unit_kerja){
                 foreach ($res_unit_kerja as $subunit) {
                 $unitkerja = $subunit->UNIT_KERJA;                 
                }
             }
             else{
                 $unitkerja =  "";
             }
             $res_tabtrans = $this->Tab_model->List_temp_tabtrans_byuser();
             if(!$res_tabtrans){
                 redirect('/error');
             }else{
                foreach($res_tabtrans as $subtrans){
                    $in_TABTRANS_ID     = $subtrans->TABTRANS_ID;
                    $in_NO_REKENING     = $subtrans->NO_REKENING;
                    $in_NAMA_NASABAH    = $subtrans->NAMA_NASABAH;
                    $in_POKOK           = $subtrans->POKOK;
                    $in_SETORAN         = $subtrans->SETORAN;
                    $in_PENARIKAN       = $subtrans->PENARIKAN;
                    $in_SANDI_TRANS     = $subtrans->SANDI_TRANS;
                    $in_KODE_KOLEKTOR   = $subtrans->KODE_KOLEKTOR;
                    $in_KUITANSI        = $subtrans->KUITANSI;
                    $in_USERID          = $subtrans->USERID;
                    $in_TGL_TRANS       = $subtrans->TGL_TRANS;
                    
                    if($in_SETORAN >= 0 && $in_PENARIKAN == 0 ){
                        $in_codetrans       = 100;
                    }elseif ($in_SETORAN == 0 && $in_PENARIKAN >= 0) {
                        $in_codetrans       = 200;
                    }
                    else{
                        return;
                    }
                    $gen_id     = $this->Gen_id();                    
                    $arr_data = array(
                        'TABTRANS_ID'       =>$gen_id, 
                        'TGL_TRANS'         =>$in_TGL_TRANS, 
                        'NO_REKENING'       =>$in_NO_REKENING, 
                        'MY_KODE_TRANS'     =>$in_codetrans, 
                        'KUITANSI'          =>$in_KUITANSI, 
                        'POKOK'             =>$in_POKOK,
                        'ADM'               =>0,
                        'KETERANGAN'        =>'Setoran Kolektif Tabungan', 
                        'VERIFIKASI'        =>'1', 
                        'USERID'            =>$in_USERID, 
                        'KODE_TRANS'        =>$in_codetrans,
                        'TOB'               =>'T', 
                        'SANDI_TRANS'       =>$in_SANDI_TRANS,
                        'KODE_PERK_OB'      =>'', 
                        'NO_REKENING_VS'    =>'', 
                        'KODE_KOLEKTOR'     =>$in_KODE_KOLEKTOR,
                        'KODE_KANTOR'       =>$unitkerja
                        );
                    
                        $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
//                        if(!$res_ins){
//                            return "Error =====> ".$arr_data;
//                        }
                        $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
                        $this->Tab_model->Saldo_upd_by_rekening($in_rkning);
//                        $res_sum12  = $this->Tab_model->Sum_1_2_taptrans($in_NO_REKENING);
//                        if(!$res_sum12){
//                            return "Error =====> ".$res_sum12;
//                        }
//                        foreach ($res_sum12 as $sub12) {
//                            $setoran12      = $sub12->SETORAN;
//                            $penarikan12    = $sub12->PENARIKAN;
//                            $setoran_bunga12    = $sub12->SETORAN_BUNGA;
//                            $penarikan_bunga12    = $sub12->PENARIKAN_BUNGA;
//                        }                        
//                                                
//                        $hasil      = $setoran12 - $penarikan12;
//                        $bunga      = $setoran_bunga12 - $penarikan_bunga12;                        
//                        $datatabung = array(
//                                    'SALDO_AKHIR' => $hasil,
//                                    'STATUS' => '1',
//                                    'SALDO_AKHIR_TITIPAN_BUNGA' => $bunga
//                            );                       
//                        $res_upd_tab = $this->Tab_model->upd_tabung($in_NO_REKENING,$datatabung);
//                        if(!$res_upd_tab){                            
//                            echo "Gagal update tabungan";
//                            return;
//                        }
                        $res_call_tab_trans = $this->Tab_model->Tab_trans($gen_id);
                        if(!$res_call_tab_trans){
                            echo "gagal - transid tidak ada";
                            return;
                        }
                        
                        $res_call_tab   = $this->Tab_model->Tab_byrek($in_NO_REKENING);
                        if($res_call_tab){
                            foreach($res_call_tab as $sub_call_tab){
                                $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
                            }
                        }
                        
                        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_USERID);
                        if($res_call_kode_perk_kas){
                            foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                                $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
                            }
                        }
                        
                        if(empty($in_kode_perk_kas)){
                            $in_kode_perk_kas == 0;
                        }
                        $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
                        if($res_call_perk_kode_gord){
                            foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                                $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                                $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                            }
                        }
                        $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
                        foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {
                            
                            $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
                            $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
                            $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;                                                      
                        }
                        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
                        foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
                            $count_as_total = $sub_call_perk_kode_all->total;
                        }
                        $this->load->model('Sysmysysid_model');
                        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
                        if($res_call_tab_keyvalue){
                            foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
                                $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE ?: ACC_KODE_JURNAL_TABUNGAN;
                            }
                        }else{
                            $res_kode_jurnal = ACC_KODE_JURNAL_TABUNGAN;
                        }
                        //INSERT INTO TRANSAKSI_MASTER (TRANS_ID, KODE_JURNAL, NO_BUKTI, 
                        //TGL_TRANS, URAIAN, MODUL_ID_SOURCE,
                        //TRANS_ID_SOURCE,USERID,KODE_KANTOR)  VALUES (4586391, 'TAB',
                        //'Pby.0185','2016-08-08 00:00:00', 
                        //'Setoran Kolektif Tabungan', 'TAB', 4586390, -25127, '35')
                        $arr_master = array(
                            'TRANS_ID'          =>  $this->Gen_id(), 
                            'KODE_JURNAL'       =>  $res_kode_jurnal, 
                            'NO_BUKTI'          =>  $in_KUITANSI, 
                            'TGL_TRANS'         =>  $in_TGL_TRANS, 
                            'URAIAN'            =>  'Setoran Kolektif Tabungan', 
                            'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                            'TRANS_ID_SOURCE'   =>  $gen_id, 
                            'USERID'            =>  $in_USERID, 
                            'KODE_KANTOR'       =>  $unitkerja
                        );
                        $res_trans_master    = $this->Tab_model->Ins_Tbl('TRANSAKSI_MASTER',$arr_master);
                        if(!$res_trans_master){
                            return "Error =====> ".$arr_master;
                        }
                        
                        $ar_trans_detail = array(
                            array(
                                'TRANS_ID'      =>  $this->Gen_id(), 
                                'MASTER_ID'     =>  $gen_id, 
                                'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                                'DEBET'         =>  $in_PENARIKAN, 
                                'KREDIT'        =>  $in_SETORAN                                                             
                            ),array(
                                'TRANS_ID'      =>  $this->Gen_id(), 
                                'MASTER_ID'     =>  $gen_id, 
                                'KODE_PERK'     =>  $in_kode_perk_kas, 
                                'DEBET'         =>  $in_SETORAN, 
                                'KREDIT'        =>  $in_PENARIKAN
                            )
                        );
                        $this->Tab_model->Ins_batch('TRANSAKSI_DETAIL',$ar_trans_detail);
                }
                redirect();
             }   
    }
    function Gen_id() {
        $resid = $this->App_model->Generate_id();
        foreach($resid as $subid){
            $data = $subid->ID;
        }
        return $data;
    }
    public function Pergroup() {
        
            $result_sandi = $this->Tab_model->Sandi_trans();        
            $result_kolek = $this->Tab_model->Kolektor();
            $result_kwitn = $this->Tab_model->Kwitansi();
            
            if($result_kwitn){
                foreach($result_kwitn as $res_kwi){
                    $out_nokwi          = $res_kwi->KUITANSI;
                }
            }else{
                $out_nokwi = "";
            }
            $this->mViewData['SETNOKWI']         = $out_nokwi;
            $this->mViewData['SETSANDT']         = $result_sandi;
            $this->mViewData['SETKOLEK']         = $result_kolek;   
            //$this->mViewData['SETTABNS']         = $result_tabns;   
            $this->mViewData['SETTABLE']         = $this->Group_src_nsbah();
            
            $this->mTitle = "[6004] Transaksi Simpanan[Per-Group]";
            $this->render('simpanan/pergroup');         
    }
    protected function _Kuitansi() {
        $result_kwitn = $this->Tab_model->Kwitansi();
            if($result_kwitn){
                foreach($result_kwitn as $res_kwi){
                    $out_nokwi          = $res_kwi->KUITANSI;
                }
            }else{
                $out_nokwi = "";
            }
            if(empty($out_nokwi)){
                $out_nokwi = "Tab.0001";
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
    protected function _Kodetrans_by_desc($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Tab_model->Kodetrans_by_kodetrans($in_kd_trans);
        if($kodetrans_desc){
            foreach($kodetrans_desc as $res_desc){
                    $out_desc          = $res_desc->deskripsi;
                }
        }else{
            $out_desc   = "";
        }
        return $out_desc;
    }
    protected function _Kodetrans_by_tob($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Tab_model->Kodetrans_by_kodetrans($in_kd_trans);
        if($kodetrans_desc){
            foreach($kodetrans_desc as $res_desc){
                    $out_tob          = $res_desc->TOB;
                }
                return $out_tob;
        }else{
            if(setor_kode_trans == 100 || setor_kode_trans == 200){
                return 'T';
            }else{
                return 'O';
            }
        }
        return $out_tob;
    }
}
define('ACT_EDIT', 'tsimpanan/simpanan/kolektif_edit');
define('ACT_DELETE', 'tsimpanan/simpanan/kolektif_delete');
define('ACC_KODE_JURNAL_TABUNGAN', 'TAB');

