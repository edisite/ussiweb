<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Data_master_simpanan
 *
 * @author edisite
 */
class Data_master_simpanan extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->mTitle = '';
    }
    public function index() {
        
        $crud = $this->generate_crud('tabung');       
        $crud->set_model('Master_simpan_model');
        //$crud->set_theme('datatables');
        $crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT','SALDO_AKHIR','TGL_REGISTER','VERIFIKASI','BUNGA_BLN_INI','PAJAK_BLN_INI'
                ,'ADM_BLN_INI','ZAKAT_BLN_INI','KODE_PRODUK','KODE_INTEGRASI');
        $crud->where('tabung.VERIFIKASI', '1');
        $crud->callback_column('SALDO_AKHIR',array($this,'_column_bonus_right_align'));
        $crud->callback_column('BUNGA_BLN_INI',array($this,'_column_bonus_right_align'));
        $crud->callback_column('PAJAK_BLN_INI',array($this,'_column_bonus_right_align'));
        $crud->callback_column('BUNGA',array($this,'_column_bonus_right_align'));
        $crud->callback_column('ADM_BLN_INI',array($this,'_column_bonus_right_align'));
        $crud->callback_column('VERIFIKASI',array($this,'_column_center_align'));
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('NASABAH');
	$this->mTitle.= '[2000]Data master simpanan';
        $this->render_crud();       
        
    }
    function Rp($value)
    {
        return number_format($value,2,",",".");
    }
    function Rp1($value, $row)
    {
        return number_format($value,2,",",".");
    }
    
    public function Verifikasi() {
        $crud = $this->generate_crud('tabung');       
        $crud->set_model('Master_simpan_model');
        $crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT','SALDO_AKHIR','TGL_REGISTER','VERIFIKASI','BUNGA_BLN_INI','PAJAK_BLN_INI'
                ,'ADM_BLN_INI','ZAKAT_BLN_INI','KODE_PRODUK','KODE_INTEGRASI');        
        $crud->where('tabung.VERIFIKASI', '0');
        $crud->callback_column('SALDO_AKHIR',array($this,'_column_bonus_right_align'));
        $crud->callback_column('BUNGA_BLN_INI',array($this,'_column_bonus_right_align'));
        $crud->callback_column('PAJAK_BLN_INI',array($this,'_column_bonus_right_align'));
        $crud->callback_column('BUNGA',array($this,'_column_bonus_right_align'));
        $crud->callback_column('ADM_BLN_INI',array($this,'_column_bonus_right_align'));
        $crud->callback_column('VERIFIKASI',array($this,'_column_center_align'));
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_add();
        $crud->set_subject('NASABAH');
	$this->mTitle.= '[2001]Verifikasi Data Master Simpanan';
        $this->render_crud();
    }
      function _column_bonus_right_align($value)
    {       
        return "<span style=\"width:100%;text-align:right;display:block;\">".$this->rp($value)."</span>";
    }
    function _column_center_align($value,$row)
    {       
        return "<span style=\"width:100%;text-align:center;display:block;\">".$value."</span>";
    }
    
    public function Tambah() {
        $crud = $this->generate_crud('nasabah');
		$crud->columns('NASABAH_ID', 'NAMA_NASABAH', 'ALAMAT', 'VERIFIKASI','DIN');
                $crud->callback_add_field('USER_PASSWORD',array($this,'add_field_callback_1'));
                $crud->callback_edit_field('phone',array($this,'edit_field_callback_1'));
                $crud->callback_before_delete(array($this,'log_user_before_delete'));
                //$crud->set_theme('datatables');
                $crud->add_action('Pilih', 'show_button', base_url().'admin/bo_simpanan/Data_master_simpanan/add_form/');
                $crud->unset_add();
                $crud->unset_export();
                $crud->unset_print();
                $crud->unset_edit();
                $crud->unset_delete();
                $crud->set_subject('Nasabah');

		$this->mTitle.= '[2000]Data Master Simpanan';
		$this->render_crud();
       
    }
    function add_field_callback_1()
    {
            unset($post_arr['USER_PASSWORD']);                    
        return $post_arr;
              
    }
    public function Add_form($in_nasabahid = null) {
        $this->form_validation->set_rules('fnasabahid','Nasabah ID','required');     
        $this->form_validation->set_rules('fnamanasabah','Nama Nasabah','required');     
        $this->form_validation->set_rules('falamat','Alamat','required');     
        $this->form_validation->set_rules('fkdintegrasi','Kode Integrasi','required');     
        $this->form_validation->set_rules('fkdproduk','Kode Produk','required');     
        $this->form_validation->set_rules('fkdkantor','Kode Kantor','required');       
        //$this->form_validation->set_rules('fsaldo','Saldo','required|decimal');     
        $this->form_validation->set_rules('fkdpemilik','Kode Pemilik','required');     
        $this->form_validation->set_rules('fmetode','Metode basil','required');     
        $this->form_validation->set_rules('fao','AO','required');     
        $this->form_validation->set_rules('fhubbank','Hubungan Bank','required');          
        $this->form_validation->set_rules('ftglreg','Tgl Register','required');     
        
        if($this->form_validation->run()){
            $in_fnasabahid      = $this->input->post('fnasabahid') ?: '';
            $in_fnamanasabah    = $this->input->post('fnamanasabah') ?: '';
            $in_falamat         = $this->input->post('falamat') ?: '';
            $in_fkdintegrasi    = $this->input->post('fkdintegrasi') ?: '';
            $in_fkdproduk       = $this->input->post('fkdproduk') ?: '';
            $in_fsaldo          = $this->input->post('fsaldo') ?: '';
            $in_fkdpemilik      = $this->input->post('fkdpemilik') ?: '';
            $in_fmetode         = $this->input->post('fmetode') ?: '';
            $in_fao             = $this->input->post('fao') ?: '';
            $in_fhubbank        = $this->input->post('fhubbank') ?: '';
            $in_ftglreg         = $this->input->post('ftglreg') ?: '';
            $in_fkdkantor       = $this->input->post('fkdkantor') ?: '';
            $in_fwilayah        = $this->input->post('fwilayah') ?: '';
            $in_fprofesi        = $this->input->post('fprofesi') ?: '';
            $in_fnobilyet       = $this->input->post('fnobilyet') ?: '';
            
            $res_kode_produk = $this->Tab_model->Produk_by_kode_produk($in_fkdproduk);
            
            if($res_kode_produk){
                foreach ($res_kode_produk as $sub_kode_produk) {
                    $in_sukubunga           = $sub_kode_produk->SUKU_BUNGA_DEFAULT;
                    $in_pph                 = $sub_kode_produk->PPH_DEFAULT;
                    $in_saldo_min           = $sub_kode_produk->SALDO_MINIMUM_DEFAULT;
                    $in_setoran_min         = $sub_kode_produk->SETORAN_MINIMUM_DEFAULT;
                    $in_adm_perbulan        = $sub_kode_produk->ADM_PER_BULAN;                        
                }
            }else{
                $this->mTitle.= '[2000]Data Master Simpanan';
                $this->mViewData['SETHEADLINE']             = "";
                $this->mViewData['SETWARNTEXT']             = "Kesalahan";
                $this->mViewData['SETINFOTEXT']             = "Tidak bisa dilanjutkan, Produk dari ID ini '".$in_fkdproduk."' kosong ";
                $this->mViewData['SETCOLULANG']             = "bo_simpanan/Data_master_simpanan/tambah";
                $this->mViewData['SETCOLHOME']             = BASE_URL();

                $this->render('errors/error_page');    
                return;
            } 
                $res_kode_produk = $this->Tab_model->Gen_new_rek_id($in_fkdproduk,$in_fkdkantor);
                
                $this->mViewData['SET_BUNGA']           = $in_sukubunga;
                $this->mViewData['SET_PPH']             = $in_pph;
                $this->mViewData['SET_SALDOMIN']        = $in_saldo_min;
                $this->mViewData['SET_SETORAN_MIN']     = $in_setoran_min;
                $this->mViewData['SET_ADMPERBLN']       = $in_adm_perbulan;
                $this->mViewData['SET_NOREKENING']      = $res_kode_produk;
                $this->mViewData['SET_NASABAHID']       = $in_fnasabahid;
                $this->mViewData['SET_NAMANSBAH']       = $in_fnamanasabah;
                //
                $this->mViewData['SETINTEGRASI']         = $in_fkdintegrasi;
                $this->mViewData['SETKDPEMILIK']         = $in_fkdpemilik;
                $this->mViewData['SETKODGROUP1']         = $in_fao;
                $this->mViewData['SETKODGROUP2']         = $in_fwilayah;
                $this->mViewData['SETKODGROUP3']         = $in_fprofesi;
                $this->mViewData['SETKODPRODUK']         = $in_fkdproduk;
                $this->mViewData['SETKODKANTOR']         = $in_fkdkantor;
                $this->mViewData['SETKDMTDBASL']         = $in_fmetode;
                $this->mViewData['SETKDHUBBANK']         = $in_fhubbank;                
                $this->mViewData['SETALAMATNSB']         = $in_falamat;   
                $this->mViewData['SETTGLREGIST']         = $in_ftglreg;   
                $this->mViewData['SETSALDOSKRG']         = $in_fsaldo;   
                $this->mViewData['SETNO_BILYET']         = $in_fnobilyet;   

                $this->mTitle.= '[2000]Data Master Simpanan';
                $this->render('simpanan/tambah_simpanan_next');    

            return;
        }
         $this->form_validation->set_error_delimiters('<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true">', '</span><br>');  
            if(empty($in_nasabahid)){
                $this->mTitle.= '[2000]Data Master Simpanan';
                $this->mViewData['SETHEADLINE']             = "";
                $this->mViewData['SETWARNTEXT']             = "Kesalahan";
                $this->mViewData['SETINFOTEXT']             = "Tidak bisa dilanjutkan, Periksa Nasabah ID";
                $this->mViewData['SETCOLULANG']             = "bo_simpanan/Data_master_simpanan/tambah";
                $this->mViewData['SETCOLHOME']             =  BASE_URL();

                $this->render('errors/error_page');    
                return;
            }
            if (! $this->ion_auth->in_group(array('webmaster', 'admin','manager')) )
            {         
                $this->mTitle.= '[2000]Data Master Simpanan';
                $this->mViewData['SETHEADLINE']             = "";
                $this->mViewData['SETWARNTEXT']             = "Kesalahan";
                $this->mViewData['SETINFOTEXT']             = "Tidak bisa dilanjutkan, User anda tidak memiliki kemampuan";
                $this->mViewData['SETCOLULANG']             = "bo_simpanan/Data_master_simpanan/tambah";
                $this->mViewData['SETCOLHOME']             = BASE_URL();

                $this->render('errors/error_page');    
                return;

            }
            if(!$this->session->userdata('username'))
            {
                $this->mTitle.= '[2000]Data Master Simpanan';
                $this->mViewData['SETHEADLINE']             = "";
                $this->mViewData['SETWARNTEXT']             = "Kesalahan";
                $this->mViewData['SETINFOTEXT']             = "Tidak bisa dilanjutkan, Username anda tidak terdaftar";
                $this->mViewData['SETCOLULANG']             = "bo_simpanan/Data_master_simpanan/tambah";
                $this->mViewData['SETCOLHOME']             = BASE_URL();

                $this->render('errors/error_page');    
                return;
            }

            $res_call_nasbah = $this->Nas_model->nas_by_id($in_nasabahid);
            if(!$res_call_nasbah){
                $this->mTitle.= '[2000]Data Master Simpanan';
                $this->mViewData['SETHEADLINE']             = "";
                $this->mViewData['SETWARNTEXT']             = "Kesalahan";
                $this->mViewData['SETINFOTEXT']             = "ID Nasabah tidak terdaftar";
                $this->mViewData['SETCOLULANG']             = "bo_simpanan/Data_master_simpanan/tambah";
                $this->mViewData['SETCOLHOME']             = BASE_URL();

                $this->render('errors/error_page');    
                return;
            }
            foreach ($res_call_nasbah as $sub_call_nasbah) {
                $in_nasabahid   = $sub_call_nasbah->nasabah_id;
                $in_namansbah   = $sub_call_nasbah->nama_nasabah;
                $in_alamatsnb   = $sub_call_nasbah->alamat;
            }
            $in_integrasi = $this->Tab_model->Integrasi();
            $in_kdpemilik = $this->Tab_model->Kodepemilik();
            $in_kodgroup1 = $this->Tab_model->Kodegroup1();
            $in_kodgroup2 = $this->Tab_model->Kodegroup2();
            $in_kodgroup3 = $this->Tab_model->Kodegroup3();
            $in_kodproduk = $this->Tab_model->Produk();
            $in_kodkantor = $this->App_model->Kode_kantor();
            $in_kdmetbasl = $this->Tab_model->Kodemetodebasil();
            $in_kdhubbank = $this->Tab_model->Kodehubunganbank();

            $this->mViewData['SETINTEGRASI']         = $in_integrasi;
            $this->mViewData['SETKDPEMILIK']         = $in_kdpemilik;
            $this->mViewData['SETKODGROUP1']         = $in_kodgroup1;
            $this->mViewData['SETKODGROUP2']         = $in_kodgroup2;
            $this->mViewData['SETKODGROUP3']         = $in_kodgroup3;
            $this->mViewData['SETKODPRODUK']         = $in_kodproduk;
            $this->mViewData['SETKODKANTOR']         = $in_kodkantor;
            $this->mViewData['SETKDMTDBASL']         = $in_kdmetbasl;
            $this->mViewData['SETKDHUBBANK']         = $in_kdhubbank;         
            $this->mViewData['SETNASABAHID']         = $in_nasabahid;         
            $this->mViewData['SETNAMANASAB']         = $in_namansbah;         
            $this->mViewData['SETALAMATNSB']         = $in_alamatsnb;         
            $this->mTitle.= '[2000]Data Master Simpanan';
            $this->render('simpanan/tambah_simpanan');     
    }
    
    public function Add_form2() {
                $in_fnasabahid          = $this->input->post('fnasabahid');
                $in_fnorek              = $this->input->post('fnorek');
                $in_fnamanasabah        = $this->input->post('fnamanasabah');
                $in_nisbah              = $this->input->post('nisbah');
                $in_setro_min           = $this->input->post('setro_min');
                $in_admpbln             = $this->input->post('admpbln');
                $in_pph                 = $this->input->post('pph');
                $in_saldo_min           = $this->input->post('saldo_min');
                $in_zakat               = $this->input->post('zakat');
                $in_norek_penj          = $this->input->post('norek_penj');
                $in_nama_penj           = $this->input->post('nama_penj');
                $in_saldo_penj          = $this->input->post('saldo_penj');
                $in_h_integrasi         = $this->input->post('h_integrasi');
                $in_h_pemilik           = $this->input->post('h_pemilik');
                $in_h_group1            = $this->input->post('h_group1');
                $in_h_group2            = $this->input->post('h_group2');
                $in_h_group3            = $this->input->post('h_group3');
                $in_h_produk            = $this->input->post('h_produk');
                $in_h_kantor            = $this->input->post('h_kantor');
                $in_h_basil             = $this->input->post('h_basil');
                $in_h_hubbank           = $this->input->post('h_hubbank');
                $in_h_alamat            = $this->input->post('h_alamat');
                $in_h_tglreg            = $this->input->post('h_tglreg');
                $in_h_saldoskrg         = $this->input->post('h_saldoskrg');
                $in_p_target_nominal    = $this->input->post('p_target_nominal');
                $in_p_setoran           = $this->input->post('p_setoran');
                $in_p_rek_tab_umum      = $this->input->post('p_rek_tab_umum');
                $in_p_jangka_waktu      = $this->input->post('p_jangka_waktu');
                $in_p_setoran_perbln    = $this->input->post('p_setoran_perbln');
                $in_p_tgl_jt            = $this->input->post('p_tgl_jt');
                $in_h_nobilyet            = $this->input->post('h_nobilyet');
                $in_keterangan            = $this->input->post('keterangan');
             
                
        $data = array(
            'kode_produk'           => $in_h_produk,
            'kode_integrasi'        => $in_h_integrasi,
            'kode_kantor'           => $in_h_kantor,
            'tgl_register'          => $in_h_tglreg,
            'no_rekening'           => $in_fnorek, 
            'suku_bunga'            => $$in_nisbah, 
            'persen_pph'            => $in_pph, 
            'abp'                   => 0, 
            'nasabah_id'            => $in_fnasabahid, 
            'kode_bi_pemilik'       => $in_h_pemilik, 
            'kode_bi_lokasi'        => '', 
            'kode_group1'           => $in_h_group1, 
            'kode_group2'           => $in_h_group2, 
            'kode_group3'           => $in_h_group3, 
            'minimum'               => $in_saldo_min, 
            'setoran_minimum'       =>$in_setro_min,
            'saldo_akhir'           => $in_h_saldoskrg,
            'verifikasi'            => '1',
            'setoran_wajib'         => $in_p_setoran_perbln,
            'tgl_jt'                => $in_p_tgl_jt,
            'saldo_jam_kredit'      => $in_saldo_penj,
            'setoran_awal'          => $in_p_setoran,
            'keterangan'            => $in_keterangan,
            'target_nominal'        => $in_p_target_nominal,
            'jkw'                   => $in_p_jangka_waktu,
            'no_alternatif'         => $in_h_nobilyet,
            'norek_tab_program'     => $in_p_rek_tab_umum,
            'adm_per_bln'           => $in_admpbln, 
            'bi_metode_basil_dana'  => $in_h_basil,
            'bi_hubungan_bank'      => $in_h_hubbank                
        );
        
        $this->Tab_model->Ins_Tbl('TABUNG',$data);
        redirect('admin/bo_simpanan/data_master_simpanan/tambah');
    }
}
