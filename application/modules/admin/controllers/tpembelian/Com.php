<?php
require_once __DIR__ . '/../../../api/controllers/Commerce.php'; 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of com
 *
 * @author edisite
 */

class Com extends Admin_Controller{
    //put your code here
    var $message_status;
    private $productid = '';
    private $tot_tagihan, $total = 0;
    protected $topup_statushit = 0;
    
    
    private $biaya_adm_indo = 0;
    private $biaya_adm_bmt  = 0;
    private $biaya_adm_agen, $biaya_adm_prov = 0;
    
    private $model_com      = "COM";
    private $model_pay      = "PAY";
        
    public function __construct() {
        parent::__construct();
        $this->load->model('Pay_model');
        $this->load->model('Sysmysysid_model');
        $this->load->model('Admin_user2_model');
        $this->load->model('Poin_model');
    }
    public function ConfSrv_purchase_agent() {        
        $crud = $this->generate_crud('com_pulsa'); 
        $crud->set_theme('datatables');
        //$crud->set_model('Deposito_nasabah_model');
        $crud->columns('type','provider','product_id','product_alias','price_original','price_stok','price_selling','status','lastdtm');
        $crud->edit_fields('type','mode_trans','price_original','price_stok','price_selling','status','code_provider','lastdtm');
        $crud->where('mode_trans','purchase');
        $crud->or_where('mode_trans is null');
        $crud->display_as('dtm','Date');        
        $crud->display_as('lastdtm','Update');        
        $crud->display_as('price_original','Harga INDOSIS');        
        $crud->display_as('price_stok','Harga BMT');        
        $crud->display_as('price_selling','Harga Jual');        
        $crud->display_as('harga','Price');        
        $crud->display_as('product_alias','Nama');        
        $crud->display_as('type',' Tipe Produk');        
        //$crud->field_type('type', 'dropdown', array('PULSA','PLN','FINANCE','GAME','PAM','TV'));        
        $crud->callback_column('price_original',array($this,'rp'));
        $crud->callback_column('price_stok',array($this,'rp'));
        $crud->callback_column('price_selling',array($this,'rp'));

        $crud->unset_delete();
        //$crud->unset_view();
        $crud->set_subject('Produk');
        //$this->mTitle = "[1022] Configuration Product Commerce";
        $this->mMenuID    = '1021';
        $this->render_crud();
    }
    public function ConfSrv_purchase_users() {        
        $crud = $this->generate_crud('com_pulsa'); 
        $crud->set_theme('datatables');
        //$crud->set_model('Deposito_nasabah_model');
        $crud->columns('type','provider','product_id','product_alias','price_user_indosis','price_user_selling','status','price_user_lastupd');
        $crud->edit_fields('type','mode_trans','price_user_indosis','price_user_selling','status','code_provider','price_user_lastupd');
        $crud->where('mode_trans','purchase');
        $crud->or_where('mode_trans is null');
        $crud->display_as('dtm','Date');        
        $crud->display_as('lastdtm','Update');        
        $crud->display_as('price_user_indosis','Harga INDOSIS');                
        $crud->display_as('price_user_selling','Harga Jual BMT');        
        $crud->display_as('harga','Price');        
        $crud->display_as('product_alias','Nama');        
        $crud->display_as('type',' Tipe Produk');        
        //$crud->field_type('type', 'dropdown', array('PULSA','PLN','FINANCE','GAME','PAM','TV'));        
        $crud->callback_column('price_user_indosis',array($this,'rp'));
        $crud->callback_column('price_user_selling',array($this,'rp'));

        $crud->unset_delete();
        //$crud->unset_view();
        $crud->set_subject('Produk');
        //$this->mTitle = "[1022] Configuration Product Commerce";
        $this->mMenuID    = '1037';
        $this->render_crud();
    }
    public function ConfSrvPost_payment_agent() {
        
        $crud = $this->generate_crud('com_pulsa'); 
        $crud->set_theme('datatables');
        //$crud->set_model('Deposito_nasabah_model');
        $crud->columns('type','product_id','product_alias','adm_payment_provider','price_original','price_stok','price_selling','adm_payment_total','status');
        $crud->edit_fields('price_original','price_stok','price_selling','status');
        //$crud->fields('provider','product_id','product_alias','adm_payment_provider','price_original','price_stok','price_selling','status','adm_payment_total');
        $crud->where('mode_trans','payment');
        $crud->display_as('dtm','Date');        
        $crud->display_as('lastdtm','Update');        
        $crud->display_as('price_original','Adm Indosis');        
        $crud->display_as('price_stok','Adm BMT');        
        $crud->display_as('price_selling','Adm Agen');        
        $crud->display_as('adm_payment_total','Adm Total');        
        $crud->display_as('adm_payment_provider','Basil Provider');        
        $crud->display_as('harga','Price');        
        $crud->display_as('product_alias','Nama');        
        $crud->display_as('type',' Tipe Produk');        
        $crud->display_as('adm_payment_provider',' Basil Provider');        
        $crud->field_type('mode_trans', 'dropdown', array('PAYMENT'));        
        $crud->callback_column('price_original',array($this,'rp'));
        $crud->callback_column('price_stok',array($this,'rp'));
        $crud->callback_column('price_selling',array($this,'rp'));        
        $crud->callback_column('adm_payment_provider',array($this,'rp'));        
        $crud->callback_column('adm_payment_total',array($this,'rp'));        
        $crud->callback_before_insert(array($this,'my_sum_function'));
        $crud->callback_after_update(array($this,'get_init_total_adm'));

        $crud->unset_delete();
        $crud->set_subject('Layanan Pembayaran');
        $this->mMenuID    = '1036';
        $this->render_crud();
    }    
    public function ConfSrvPost_payment_users() {
        
        $crud = $this->generate_crud('com_pulsa'); 
        $crud->set_theme('datatables');
        //$crud->set_model('Deposito_nasabah_model');
        $crud->columns('type','product_id','product_alias','adm_payment_provider','adm_user_payment_indosis','adm_user_payment_bmt','adm_user_payment_total','adm_user_paymment_lastupd','status');
        $crud->edit_fields('adm_user_payment_indosis','adm_user_payment_bmt','status');
        //$crud->fields('provider','product_id','product_alias','adm_payment_provider','price_original','price_stok','price_selling','status','adm_payment_total');
        $crud->where('mode_trans','payment');
        $crud->display_as('dtm','Date');        
        $crud->display_as('adm_user_paymment_lastupd','Update');        
        $crud->display_as('adm_user_payment_indosis','Adm Indosis');        
        $crud->display_as('adm_user_payment_bmt','Adm BMT');                
        $crud->display_as('adm_user_payment_total','Adm Total');        
        $crud->display_as('adm_payment_provider','Basil Provider');        
        $crud->display_as('harga','Price');        
        $crud->display_as('product_alias','Nama');        
        $crud->display_as('type',' Tipe Produk');        
        $crud->display_as('adm_payment_provider',' Basil Provider');        
        $crud->field_type('mode_trans', 'dropdown', array('PAYMENT'));        
        $crud->callback_column('adm_user_payment_indosis',array($this,'rp'));
        $crud->callback_column('adm_user_payment_bmt',array($this,'rp'));   
        $crud->callback_column('adm_payment_provider',array($this,'rp'));        
        $crud->callback_column('adm_user_payment_total',array($this,'rp')); 
        
        $crud->callback_before_insert(array($this,'my_sum_functionusers'));
        $crud->callback_after_update(array($this,'get_init_total_adm_users'));

        $crud->unset_delete();
        $crud->set_subject('Layanan Pembayaran');
        $this->mMenuID    = '1038';
        $this->render_crud();
    }    
    public function Pulsa() {
        //var_dump($this->session->userdata());
        $crud = $this->generate_crud('com_pulsa'); 
        $crud->set_theme('datatables');
        //$crud->set_model('Deposito_nasabah_model');
        $crud->columns('provider','product_id','product_alias','price_selling');
        $crud->edit_fields('price_original','price_selling','status');
        $crud->where('mode_trans','purchase');
        $crud->or_where('mode_trans is null');
        $crud->display_as('dtm','Date');        
        $crud->display_as('price_selling','Harga');        
        $crud->display_as('product_id','Produk');        
        $crud->display_as('harga','Price');        
        $crud->display_as('product_alias','Name');        
        $crud->field_type('type', 'dropdown', array('PULSA','PLN')); 
        $crud->where('type', 'PULSA');
        $crud->where('status', 'OPEN');
//        $crud->callback_column('price_original',array($this,'rp'));
        $crud->callback_column('price_selling',array($this,'rp'));
//        $crud->callback_column('product_alias',array($this,'rp'));
        //$crud->add_action('Cash', 'Pilih', 'admin/tdeposito/tsimpanan_berjangka/titipan_form', '');
        $crud->add_action('Beli', 'Pilih', 'admin/tpembelian/com/pulsa_mark', '');
        $crud->unset_delete();
        $crud->unset_edit();
        $crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_view();
        $crud->set_subject('PULSA');
        //$this->mTitle = '[1023]Pembelian Pulsa';
        $this->mMenuID = '1022';
        $this->render_crud();
    }
    public function Pulsa_mark($nometer = '') {
        if(empty($nometer)){
            redirect(base_url().'admin/tpembelian/com/pulsa');
        }        
        $res_call = $this->Pay_model->Pulsa_by_product($nometer,'PULSA');
        if($res_call){
            foreach ($res_call as $subcall) {
                $g_product_id       = $subcall->product_id;
                $g_product_alias    = $subcall->product_alias;
                $g_price_selling    = $subcall->price_selling;
            }
        }else{
            return FALSE;
        }
        $this->mViewData['productid'] = $g_product_id;
        $this->mViewData['aliasname'] = $this->Rp($g_product_alias);
        $this->mViewData['pricesell'] = $this->Rp($g_price_selling);
        
        $this->load->library('form_validation');
         $this->mTitle = '[1024]Pembelian Pulsa';
        $this->form_validation->set_rules('jenis','Jenis Pembayaran','required');        
        $this->form_validation->set_rules('productid','Product ID','required');        
        $this->form_validation->set_rules('nohandset','Nomor','trim|required|min_length[8]|max_length[17]|integer');
        
        if($this->form_validation->run()===FALSE){
            //$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">','</div>'); 
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
            $this->render('pembelian/pulsa');            
        }else{
            $in_paytype          = $this->input->post('jenis') ?: '';
            $in_nohandset        = $this->input->post('nohandset') ?: '';               
            $in_productid        = $this->input->post('productid') ?: '';

            
            $this->mViewData['nohandset']   = $in_nohandset;
            $this->mViewData['paytype']     = $in_paytype;
            $this->mViewData['produk']  = $in_productid;
            $this->mViewData['alias']  = $this->Rp($g_product_alias);
            $this->mViewData['harga']   = $this->Rp($g_price_selling);
            $this->mViewData['catalogid']   = $nometer;
            $this->mTitle = '[1024]Pembelian PULSA';
            $this->render('pembelian/pulsa_confirm');
        }
    }
    function Pulsa_purchase() {
        $trace_id = $this->Logid();
        $this->logAction('info', $trace_id, array(), 'parameter :'.$this->uri->uri_string());
        $in_agenid          = $this->session->userdata('user_id') ?: ''; //**     
        $in_msisdn          = $this->input->post('msisdn') ?: '';
        $in_produk          = $this->input->post('produk') ?: '';
        $in_paytype         = $this->input->post('paytype') ?: '';
        $in_catalogid         = $this->input->post('catalogid') ?: '';
        $in_kdepin          = '9999';       //**  
        
        
        if(strtolower($in_paytype) == "nasabah"){      
            $this->logAction('info', $trace_id, array(), 'paytype nasabah ditutup');
            $this->messages->add('Jenis pembayaran dengan rekening nasabah tidak diijinkan', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pulsa_mark/'.$in_catalogid);            
        }elseif(strtolower($in_paytype) == "agent"){
            $this->logAction('info', $trace_id, array(), 'paytype agent');
            $this->load->model('Admin_user2_model');
            $res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agenid);
            $this->logAction('hit model', $trace_id, array(), 'Admin_user2_model->User_by_username('.$in_agenid.')');
                    
            if($res_call_user_agent){
                foreach ($res_call_user_agent as $sub_call_user_agent) {
                    $agn_norekening = $sub_call_user_agent->no_rekening;
                    $agn_active = $sub_call_user_agent->active;
                    $agn_username = $sub_call_user_agent->username;
                }
                if(empty($agn_norekening)){
                    $this->logAction('info', $trace_id, array(), 'norekening : Account Anda belum disetting Rekening untuk debet beban biaya pembelian<br>Transaction Close');
                    $this->messages->add('Account Anda belum disetting Rekening untuk debet beban biaya pembelian<br>Transaction Close', 'error');             
                    redirect(base_url().'admin/tpembelian/com/pulsa');   
                }
                if($agn_active == "0"){
                    $this->logAction('info', $trace_id, array(), 'Account Anda Inactive, <br>Transaction Close');                    
                    $this->messages->add('Account Anda Inactive, <br>Transaction Close', 'warning');             
                    redirect(base_url().'admin/tpembelian/com/pulsa');   
                }
                $in_rkning =  $agn_norekening;
            }else{
                $this->logAction('info', $trace_id, array(), 'Account Anda tidak ditemukan, <br>Transaction Close');
                $this->messages->add('Account Anda tidak ditemukan, <br>Transaction Close', 'warning');             
                redirect(base_url().'admin/tpembelian/com/pulsa');   
            }
        }else{
            $this->logAction('info', $trace_id, array(), 'Jenis Pembayaran invalid, <br>Transaction Close');                
            $this->messages->add('Jenis Pembayaran invalid, <br>Transaction Close', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pulsa');   
        }
        
        $res_product = $this->Pay_model->Pulsa_by_product($in_produk,'PULSA');
         $this->logAction('hit model', $trace_id, array(), 'Pay_model->Pulsa_by_product('.$in_produk.',PULSA)'); 
        if( ! $res_product){
            $this->logAction('result', $trace_id, array(), 'Product PLN invalid, <br>Transaction Close'); 
            $this->messages->add('Product PULSA invalid, <br>Transaction Close', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pulsa');     
        }
        foreach ($res_product as $sub_product) {
            $r_provider         = $sub_product->provider;
            $r_kode_produk      = $sub_product->product_id;
            $r_type             = $sub_product->type;
            $r_price_original   = $sub_product->price_original;
            $r_price_stok       = $sub_product->price_stok;
            $r_price_selling    = $sub_product->price_selling;
            $r_product_alias    = $sub_product->product_alias;
            $r_kode_integrasi   = $sub_product->kode_integrasi;
        }
        
        //**** cek nasabah
        $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
        $this->logAction('hit model', $trace_id, array(), 'Tab_model->Tab_nas('.$in_rkning.')'); 
        if(!$res_cek_rek){   
            $this->logAction('result', $trace_id, array(), 'rekening invalid, <br>Transaction Close'); 
            $this->messages->add('rekening invalid, <br>Transaction Close', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pulsa_mark/'.$in_catalogid);   
        }
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
            $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
            $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
        }

        
        //*****************************************
        //lokasi hit ke server pln
        //*****************************************
        $URL = URLPULSA;
        $URL = $URL."?no_hp=".$in_msisdn;
        $URL = $URL."&kode_produk=".rawurlencode($in_produk);
        $URL = $URL."&jumlah=".$r_product_alias;     
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        //$this->logAction('openhttp', $trace_id, array('url'=> $URL), 'masih pura puraaa');
        $res_call_hit_topup = $this->Hitget($URL);
        $res_call_hit_topup = '{"status":"OK","rc":"00","kode_produk":"SN5","trx_id":"628551000727","saldo":"4036155","no_hp":"081327222460","jumlah":"5000","message":"SN=7090916265781859300;09\/09\/17 16:26 ISI SN5 KE 081327222460, BERHASIL SISA. SAL=4.036.155,HRG=5.700,ID=61695829,SN=7090916265781859300; INFO : Perubahan harga tsel 25 utk RS silahkan cek harga...thx","sn":"7090916265781859300","provider":"Powermedia"}';
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        if(empty($res_call_hit_topup)){
            $this->logAction('info', $trace_id, array(), 'Error di pihak provider, cek kembali transaksi anda, <br>Transaction Close'); 
            $this->messages->add('Error di pihak provider, cek kembali transaksi anda, <br>Transaction Close', 'info');             
            redirect(base_url().'admin/tpembelian/com/pulsa_mark/'.$in_catalogid); 
            //return;
        }
        
        $data_topup = json_decode($res_call_hit_topup);
       
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_harga            = isset($data_topup->harga) ? $data_topup->harga : NULL;
        $topup_no_serial        = isset($data_topup->sn) ? $data_topup->sn : '';
        $topup_transid          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
        
        if(strtolower($topup_status) == 'nok'){
            $this->logAction('result', $trace_id, array(), 'response : Error di pihak provider, cek kembali transaksi anda, <br>Transaction Close');
            $this->logAction('result', $trace_id, array(), $topup_message);
            $this->messages->add('Error di pihak provider, cek kembali transaksi anda. <br> message :'.$topup_message.'.<br><br>Transaction Close', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pulsa_mark/'.$in_catalogid); 
            return;
        }        

        $com_pulsa_log = array(
            'request'       => $this->input->raw_input_stream,
            'ip_request'    => $this->input->ip_address(),
            'msisdn'        => $in_msisdn,
            'product'       => $in_produk,
            'nominal'       => $r_type,
            'urlhit'        => $URL,
            'response'      => $res_call_hit_topup,
            'res_status'    => $topup_status,
            'res_code'      => $topup_respon_code,
            'res_message'   => $topup_message,
            'res_saldo'     => $topup_saldo,
            'userid'        => $in_agenid,
            'trx_status'    => 'topup',
            'desc_trx'      => 'hit topup 3party',
            'provider'      => $r_provider,
            'price_selling' => $r_price_selling,
            'price_stok'    => $r_price_stok,
            'price_original'=> $r_price_original,
            'price_fr_mitra'=> $topup_harga,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'      => $trace_id,
            'provider'      => $topup_provider
        );
        
        $this->logAction('hit model', $trace_id, array($com_pulsa_log), 'Tab_model->Ins_Tbl(com_pulsa_log)'); 
        if(!$this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log)){   
            $this->logAction('result', $trace_id, array(), 'error insert database'); 
            $this->messages->add('Error Internal, gagal insert ke database, cek kembali transaksi anda', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pulsa_mark/'.$in_catalogid); 
            return;
        }
        if((strtolower($topup_respon_code) == '00') //sukses
            || (strtolower($topup_respon_code) == '68')  //pending
            ){ //
            $this->logAction('insert', $trace_id, array(), 'resulrt,responcode ['.$topup_respon_code.'] >> '.$topup_message);
        }else{
            if($topup_respon_code == '03'){
                $data = array('status' => FALSE,'error_code' => '622','message' => 'invalid pin','sn' =>'','code'=>''); 
                $this->message_status = "Invalid pin";
            }elseif($topup_respon_code == '04'){
                $this->message_status = "Invalid product ID";                
            }elseif($topup_respon_code == '10'){
                $this->message_status = "Invalid Nomor PLN";            
            }elseif($topup_respon_code == '88'){
                $this->message_status = "Error provider";            
            }elseif($topup_respon_code == '89'){
                $this->message_status = "Error provider";                
            }elseif($topup_respon_code == '99'){
                $this->message_status = "Error provider";
            }elseif($topup_respon_code == '05'){
                $this->message_status = "Saldo di provider tidak cukup";
            }elseif($topup_respon_code == '06'){
                $this->message_status = "Error provider";                
            }elseif($topup_respon_code == '68'){
                $this->message_status = "Pending";                
            }elseif($topup_respon_code == '63'){
                $this->message_status = "Error provider";                
            }elseif($topup_respon_code == '30'){
                $this->message_status = "Error provider";                    
            }else{
                $this->message_status = "Error provider";                
            } 
            $this->logAction('result', $trace_id, array(), 'Error di pihak provider, ['.$topup_respon_code.'-'.$topup_message.'], <br>Transaction Close'); 
            $this->messages->add('Error di pihak provider, ['.$topup_respon_code.'-'.$topup_message.'], <br>Transaction Close', 'info');             
            redirect(base_url().'admin/tpembelian/com/pulsa_mark/'.$in_catalogid); 
            return;
        }        
        $get_code_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR_'.$in_paytype);
        if($get_code_trans){
            foreach ($get_code_trans as $vres) {
                $res_code_kode      = $vres->kode ?: '';
                $in_desc_trans      = $vres->deskripsi ?: '';
                $res_code_tob       = $vres->tob ?: '';
            }
        }else{
            $in_desc_trans                  = $this->_Kodetrans_by_desc('100');
        }
        $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
        if($get_mycode_trans){
            foreach ($get_mycode_trans as $v_myres) {
                $res_mycode_kode    = $v_myres->kode ?: '';
                $in_desc_trans      = $v_myres->deskripsi ?: '';
                $in_tob             = $v_myres->tob ?: '';
            }
        }else{
            $in_tob                         = $this->_Kodetrans_by_tob('100');
        }
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_keterangan                  = $in_desc_trans." ".strtoupper($r_provider)." ".$in_msisdn." ".  $this->Rp($r_price_selling)." SN:".$topup_no_serial;       
        $in_KUITANSI    = $this->_Kuitansi();                    
        
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(),
            'NOCUSTOMER'        =>$in_msisdn,
            'COMTYPE'           =>$r_type,
//            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>$res_mycode_kode, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$r_price_selling,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>$res_code_kode,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            
        $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("COMTRANS")');
        if(!$res_ins){
            $this->messages->add('Error internal-gagal insert ke database COMTRANS <br>Cek kembali transaksi Anda', 'info');             
            redirect(base_url().'admin/tpembelian/com/pulsa_mark/'.$in_catalogid); 
            return;
            
         }
            if(strtolower($in_paytype) == 'nasabah'){
            //debit nasabah
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$in_rkning.','.$in_msisdn.','.$in_agenid.','.$r_price_selling.','.PULSA_CODE.','.PULSA_MYCODE.','.$gen_id_TABTRANS_ID);
            $res_debit_nasabah = $this->Tab_model->Tab_commerce($in_rkning,$in_msisdn,$in_agenid,$r_price_selling,PULSA_CODE,PULSA_MYCODE,$gen_id_TABTRANS_ID);
            //insert tab indosis
            if($res_debit_nasabah){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$get_nomor_rekening_partner.','.$in_msisdn.','.$in_agenid.','.$r_price_selling.','.PULSA_INDOSIS_CODE.','.PULSA_INDOSIS_MYCODE.','.$gen_id_TABTRANS_ID);
            $res_indosis = $this->Tab_model->Tab_commerce($get_nomor_rekening_partner,$in_msisdn,$in_agenid,$r_price_selling,PULSA_INDOSIS_CODE,PULSA_INDOSIS_MYCODE,$gen_id_TABTRANS_ID);
            if($res_indosis){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }            
        }     
           
            $dataupd = array('master_id' => $gen_id_MASTER,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);     
            $this->logAction('hit model', $trace_id, $dataupd, 'Pay_model->Upd_logpulsa()');
            $this->getid_session = $this->Trxid();
            $this->mViewData['msisdn']   = $in_msisdn;
            $this->mViewData['produk']  = $in_produk.'-'.$r_product_alias;
            $this->mViewData['nama']    = '';
            $this->mViewData['harga']   = $this->Rp($r_price_selling ?: 0);
            $this->mViewData['token']   = $topup_no_serial;
            $this->mViewData['note_code']   = $this->getid_session;
            
            $setanu = array(
            'msisdn'        => $in_msisdn,
            'produk'        => $in_produk.'-'.$r_product_alias,
            'note_due_date' => $this->_Tgl_hari_ini(),
            'nama'          => '',
            'harga'         => $this->Rp($r_price_selling ?: 0),
            'token'         => $topup_no_serial,
            'note_code'     => $this->getid_session);
            
        $this->session->unset_userdata($this->getid_session);
        $this->session->set_userdata($this->getid_session, json_encode($setanu));        
            $this->mTitle = '[1024]Pembelian PULSA';
            $this->messages->add('Transaksi Berhasil', 'success');
            $this->render('pembelian/pulsa_notice');                  
    }
    public function Pulsa_print($in_code = '') {
        
    }
    protected function _tabung($in_rkning = '',$in_nominal = '',$in_agentid = '',$in_kode_perk_hutang_pokok_in_integrasi = '',$in_kode_perk_kas = '') {
        
        if(empty($in_rkning) || empty($in_agentid) || empty($in_nominal) || empty($in_kode_perk_hutang_pokok_in_integrasi) || empty($in_kode_perk_kas)){
            return false;
        }
        $gen_id_TABTRANS_ID         = $this->App_model->Gen_id();
        $gen_id_COMMON_ID           = $this->App_model->Gen_id();
        $gen_id_MASTER              = $this->App_model->Gen_id();
        $in_KUITANSI    = $this->_Kuitansi(); 
        $res_kode_jurnal = 'TAB';
        
        $in_keterangan = "Setoran transaksi sementara";
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>SIMPAN_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$in_nominal,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agentid, 
            'KODE_TRANS'        =>SIMPAN_MYCODE,
            'TOB'               =>'T', 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            //$this->response($arr_data);
            
            $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
            $arr_master = array(
                    'TRANS_ID'          =>  $gen_id_MASTER, 
                    'KODE_JURNAL'       =>  $res_kode_jurnal, 
                    'NO_BUKTI'          =>  $in_KUITANSI, 
                    'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                    'URAIAN'            =>  $in_keterangan, 
                    'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                    'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                    'USERID'            =>  $in_agentid, 
                    'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
                );
            $ar_trans_detail = array(
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                    'DEBET'         =>  0, 
                    'KREDIT'        =>  $in_nominal                                                             
                ),array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_kas, 
                    'DEBET'         =>  $in_nominal, 
                    'KREDIT'        =>  0
                )
            );       
            $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
            $this->Tab_model->Saldo_upd_by_rekening($in_rkning);
    }
    public function Finance() {
        //var_dump($this->session->userdata());
        $crud = $this->generate_crud('com_pulsa'); 
        $crud->set_theme('datatables');
        //$crud->set_model('Deposito_nasabah_model');
        $crud->columns('provider','product_id','status','lastdtm');
        $crud->edit_fields('price_original','price_selling','status','lastdtm');
        $crud->display_as('dtm','Date');        
        $crud->display_as('lastdtm','Update');        
        $crud->display_as('product_id','Product');        
        $crud->display_as('harga','Price');        
        $crud->field_type('type', 'dropdown', array('PULSA','PLN')); 
        $crud->where('type', 'FINANCE');
        $crud->where('status', 'OPEN');
//        $crud->callback_column('price_original',array($this,'rp'));
        $crud->callback_column('price_selling',array($this,'rp'));
//        $crud->callback_column('product_alias',array($this,'rp'));
        //$crud->add_action('Cash', 'Pilih', 'admin/tdeposito/tsimpanan_berjangka/titipan_form', '');
        $crud->add_action('Pilih', 'Pilih', 'admin/tpembelian/com/finance_inq', '');
        $crud->unset_delete();
        $crud->unset_edit();
        $crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_view();
        $crud->set_subject('PLN');
        $this->mTitle = '[1023]Pembayaran MultiFinance';
        $this->render_crud();
    }
    public function Finance_inq($nometer = '') {
        if(empty($nometer)){
            redirect(base_url().'admin/tpembelian/com/finance');
        }        
        $res_call = $this->Pay_model->Pulsa_by_product($nometer,'FINANCE');
        if($res_call){
            foreach ($res_call as $subcall) {
                $g_product_id       = $subcall->product_id;
                $g_product_alias    = $subcall->product_alias;
                $g_price_selling    = $subcall->price_selling;
            }
        }else{
            $this->messages->add('cannot access', 'error'); 
            redirect(base_url().'admin/tpembelian/com/finance');            
        }
        //$this->mViewData['productid'] = $g_product_id;
        //$this->mViewData['aliasname'] = $this->Rp($g_product_alias);
        //$this->mViewData['pricesell'] = $this->Rp($g_price_selling);
        
        $this->load->library('form_validation');
         $this->mTitle = '[1024]Pembayaran MultiFinance';
        $this->form_validation->set_rules('jenis','Jenis Pembayaran','required');        
        //$this->form_validation->set_rules('productid','Product ID','required');        
        $this->form_validation->set_rules('nokontrak','No Kontrak','trim|required|min_length[8]|max_length[20]|integer');
        
        if($this->form_validation->run()===FALSE){
            //$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">','</div>'); 
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
            $this->render('pembelian/finance');            
        }else{
            
            
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        
        $in_paytype     = $this->input->post('jenis') ? $this->input->post('jenis') : '';
        $in_nik         = $this->input->post('nokontrak') ? $this->input->post('nokontrak') : '';
        
        $productid      = $g_product_id ?: PROVIDER_CODE_FINANCE;
        $in_agenid      = $this->session->userdata('user_id') ? : '';

        $URL = URL_MFINANCE_INQ;
        $URL = $URL."?product_code=".$productid;
        $URL = $URL."&trx_id=".$trans_id;
        $URL = $URL."&cust_id=".$in_nik;
        //product_code=&trx_id=&cust_id=
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $res_call_hit_topup = $this->Hitget($URL);
        if(empty($res_call_hit_topup)){
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->messages->add('failed, hit provider', 'error'); 
            redirect(base_url().'admin/tpembelian/com/finance'); 
        }

        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        if($data_topup){
            $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
            $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
            $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
            $topup_partner_id       = isset($data_topup->partner_id) ? $data_topup->partner_id : NULL;
            $topup_product_code     = isset($data_topup->product_code) ? $data_topup->product_code : NULL;
            $topup_transid          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
            $topup_cust_id          = isset($data_topup->cust_id) ? $data_topup->cust_id : NULL;
            $topup_cust_name        = isset($data_topup->cust_name) ? $data_topup->cust_name : NULL;
            $topup_bill_amount      = isset($data_topup->amount) ? $data_topup->amount : 0;
            $topup_admin_fee        = isset($data_topup->admin_fee) ? $data_topup->admin_fee : 0;
            $topup_noinstalment     = isset($data_topup->no_installment) ? $data_topup->no_installment : 0;
            $topup_duedate          = isset($data_topup->due_date) ? $data_topup->due_date : 0;
            
        }

        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->messages->add('error, error provider<br>'.$topup_message, 'error'); 
            redirect(base_url().'admin/tpembelian/com/finance'); 

        }
        if(strtolower($topup_respon_code) != '00'){
            if($topup_respon_code == '04'){
                $data = array('status' => FALSE,'error_code' => '622','message' => 'invalid product','data' => '');          
            }elseif($topup_respon_code == '10' || $topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '603','message' => 'custid tidak terdaftar','data' => '');       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '601','message' => 'tagihan lunas','data' => '');
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => '');
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => '');
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '602','message' => 'saldo insufficent balance','data' => '');
            }else{
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => '');
            }            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->messages->add('failed,<br>'.$topup_respon_code.'-'.$topup_message, 'info'); 
            redirect(base_url().'admin/tpembelian/com/finance_inq'); 
        }
        
        
        if(!$this->Tab_model->Ins_Tbl('com_mfinance_ses',$data_topup)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => $res_result);
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->messages->add('problem internal, failed insert data,', 'error'); 
            redirect(base_url().'admin/tpembelian/com/finance'); 
            //return;
        }
        
//        $this->sub_tagihan  = $topup_bill_amount + $topup_ppn + $topup_penalty;
//        $this->tot_tagihan  = $this->sub_tagihan - $topup_insentif;
        $this->tot_tagihan  = $topup_bill_amount;
        $this->total        = $this->tot_tagihan + $topup_admin_fee;
        //$this->logAction('data', $trace_id, array(), 'tagihan: '.$this->tot_tagihan.' + admin: '.$biaya_adm.' = '.);
        $arr_upd = array(
            'log_trace'  => $trace_id,
            'total' => $this->total
        );
        $this->LogAction('update', $trace_id, $arr_upd, 'Pay_model->Upd_logmfinance("'.$topup_transid.'")');
        $this->Pay_model->Upd_logmfinance($topup_transid,$arr_upd);
        $res_result = array(
            'custid'       => $topup_cust_id ?: '',
            'cust_name'     => $topup_cust_name ?: '',
            'tagihan'       => $this->Rp($this->tot_tagihan) ?: '',
            'adm'           => $this->Rp($topup_admin_fee) ?: '',            
            'total'         => $this->Rp($this->total) ?: '',            
            'code'          => $topup_transid  ?: ''           
        );
        $this->LogAction('info', $trace_id, $res_result, 'tagihan');               
            $this->mViewData['paytype']     = $in_paytype                   ?: '';
            $this->mViewData['produk']      = $productid                    ?: '';
            $this->mViewData['tagihan']     = $this->Rp($this->tot_tagihan) ?: '';
            $this->mViewData['custid']      = $topup_cust_id                ?: '';
            $this->mViewData['cust_name']   = $topup_cust_name              ?: '';
            $this->mViewData['adm']         = $this->Rp($topup_admin_fee) ?: '';
            $this->mViewData['total']       = $this->Rp($this->total)       ?: '';
            $this->mViewData['code']        = $topup_transid                ?: '';
            $this->mViewData['duedate']     = $topup_duedate                ?: '';
            $this->mViewData['noinstalmen'] = $topup_noinstalment                ?: '';
            
            $this->mTitle = '[1024]Pembayaran Multifinance';
            $this->render('pembelian/finance_inq');
        }
    }
    function Finance_purchase() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_agenid  = $this->session->userdata('user_id') ?: ''; //**
        $in_pln     = $this->input->post('nokontrak')   ? $this->input->post('nokontrak') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : ''; // value of agent or nasabah[90]     
        $in_codemfinance = $this->input->post('code')    ? $this->input->post('code') : '';       //**
        
        if(empty($in_pln) || empty($in_paytype) || empty($in_codemfinance)){
            redirect();
        }
        if (strlen($in_pln) < 8)
	{            
            $data = array('status' => FALSE,'error_code' => '616','message' => 'custid invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, custid lenght ['.strlen($in_pln).']');
            $this->messages->add('invalid no kontrak','error');
            redirect(base_url().'admin/tpembelian/com/finance');
        }
        if (!is_numeric($in_pln))
	{
            $data = array('status' => FALSE,'error_code' => '616','message' => 'custid invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, custid isnot numeric ['.$in_pln.']');
            $this->messages->add('invalid no kontrak','error');
            redirect(base_url().'admin/tpembelian/com/finance');
        }
        $this->logAction('info', $trace_id, array(), 'paytype by ['.$in_paytype.']');
        if(strtolower($in_paytype) == "nasabah"){
            $in_rkning = $this->input->post('rekening');
            $this->logAction('info', $trace_id, array(), 'paytype nasabah ditutup');
            $this->messages->add('Jenis pembayaran dengan rekening nasabah tidak diijinkan', 'warning');  
            redirect(base_url().'admin/tpembelian/com/finance');
        }elseif(strtolower($in_paytype) == "agent"){
            $this->load->model('Admin_user2_model');
            $res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agenid);
            $this->logAction('check', $trace_id, array(), 'Admin_user2_model->User_by_username ['.$in_agenid.']');
            
            if($res_call_user_agent){
                $this->logAction('result', $trace_id,$res_call_user_agent , 'OK');
                foreach ($res_call_user_agent as $sub_call_user_agent) {
                    $agn_norekening = $sub_call_user_agent->no_rekening;
                    $agn_active = $sub_call_user_agent->active;
                    $agn_username = $sub_call_user_agent->username;
                }
                if(empty($agn_norekening)){
                    $this->messages->add('Rekening untuk pembayaran belum di setting di account anda','warning');
                    redirect(base_url().'admin/tpembelian/com/finance'); 
                }
               
                if($agn_active == "0"){
                    $data = array('status' => FALSE,'error_code' => '621','message' => 'account agent inactive','data'=>array('sn' => '','code' => ''));
                    $this->logAction('response', $trace_id, $data, 'account agent inactive');
                    $this->messages->add('account agent inactive','warning');
                    redirect(base_url().'admin/tpembelian/com/finance'); 
                }
                $in_rkning =  $agn_norekening;
            }else{
                $data = array('status' => FALSE,'error_code' => '619','message' => 'norekening invalid','data'=>array('sn' => '','code' => ''));
                $this->logAction('response', $trace_id, $data, 'failed, no response or empty data');
                $this->messages->add('Nomor Rekening invalid','error');
                redirect(base_url().'admin/tpembelian/com/finance'); 
            }
        }else{
            $data = array('status' => FALSE,'error_code' => '618','message' => 'paytype invalid','data'=>array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, paytype invalid ['.$in_paytype.']');
            $this->messages->add('Paytype Invalid','error');
            redirect(base_url().'admin/tpembelian/com/finance');
        }
        
        $this->logAction('info', $trace_id, array(), 'norekening ('.$in_paytype.') =>'.$in_rkning);
        
        $res_product = $this->Pay_model->Mfinance_ses_by_code($in_codemfinance,$in_pln);
        if( ! $res_product){
            $data = array('status' => FALSE,'error_code' => '622','message' => 'code','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Pay_model->Mfinance_ses_by_code('.$in_codepln.','.$in_pln.')');
            $this->logAction('response', $trace_id, array(),'error :'.$res_product);
            $this->messages->add('Problem internal','error');
            redirect(base_url().'admin/tpembelian/com/finance');    
        }
        foreach ($res_product as $sub_product) {
            $topup_product_code     = isset($sub_product->product_code) ? $sub_product->product_code : NULL;
            $topup_transid          = isset($sub_product->trx_id) ? $sub_product->trx_id : NULL;
            $topup_cust_id          = isset($sub_product->cust_id) ? $sub_product->cust_id : NULL;
            $topup_cust_name        = isset($sub_product->cust_name) ? $sub_product->cust_name : NULL;
            $topup_admin_fee        = isset($sub_product->admin_fee) ? $sub_product->admin_fee : 0;
            $topup_duedate          = isset($sub_product->duedate) ? $sub_product->duedate : NULL;
            $topup_bill_amount      = isset($sub_product->bill_amount) ? $sub_product->bill_amount : 0;
            $topup_total            = isset($sub_product->total) ? $sub_product->total : NULL;
        }
        
        //**** cek nasabah
        $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
        if(!$res_cek_rek){            
            $data = array('status' => FALSE,'error_code' => '619','message' => 'rekening invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_rkning.')');
            $this->logAction('response', $trace_id, array(),'error :'.$res_cek_rek);
            $this->messages->add('Nomor Rekening invalid','error');
            redirect(base_url().'admin/tpembelian/com/finance');
        }
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
            $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
            $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
        }
        $this->logAction('info', $trace_id, array(), 'total biaya : '.$topup_total);
        $this->logAction('info', $trace_id, array(), 'saldo rekening : '.$cek_saldo_akhir);
        if(floatval(round($topup_total)) > round($cek_saldo_akhir)){
            $data = array('status' => FALSE,'error_code' => '623','message' => 'saldo tabungan tidak cukup','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, saldo insufficent balace');
            $this->messages->add('Invalid saldo insufficent balace','warning');
            redirect(base_url().'admin/tpembelian/com/finance');
            
        }
        
        //*****************************************
        //lokasi hit ke server pln
        //*****************************************
        $URL = URL_MFINANCE_PAY;
        $URL = $URL."?";
        $URL = $URL."product_code=".$topup_product_code;
        $URL = $URL."&trx_id=".$topup_transid;
        $URL = $URL."&cust_id=".$topup_cust_id;
        $URL = $URL."&cust_name=".rawurlencode($topup_cust_name);
        $URL = $URL."&admin_fee=".round($topup_admin_fee);
        $URL = $URL."&no_installment=".round($topup_admin_fee);
        $URL = $URL."&due_date=".round($topup_admin_fee);
        $URL = $URL."&amount=".round($topup_bill_amount);

        //product_code=&trx_id=&cust_id=&cust_name=&amount=&no_installment=&due_date=&admin_fee=

        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $res_call_hit_topup = $this->Hitget($URL);
        
        //$res_call_hit_topup = '{"status":"OK","rc":"00","partner_id":"INDOSIS","product_code":"5002","trx_id":"16122940795468217538","cust_id":"654321198700","cust_name":"SUPARDI NATSIR","rec_payable":"1","rec_rest":"0reffno_pln=PLN000003","reffno_pln":[],"unit_svc":"UNIT A","svc_contact":"0217900001","tariff":"TARIF C","daya":"500","admin_fee":"7000","periode1":"201607","periode2":[],"periode3":[],"periode4":[],"duedate":[],"mtr_date":"2012-04-26","bill_amount":"25000","insentif":"1000","ppn":"5000","penalty":"3000","lwbp_last":"500","lwbp_crr":"100","wbp_last":"600","wbp_crr":"100","kvarh_last":"1100","kvarh_crr":"100","pay_datetime":"20161230162536","footer_message":"OK","receipt_code":"12345","message":"Berhasil"}';
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            return;
        }
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_no_serial        = isset($data_topup->receipt_code) ? $data_topup->receipt_code : NULL;
        //$topup_transid_r          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
       
        
        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->messages->add('Terjadi kesalahan dengan pihak provider','warning');
            redirect(base_url().'admin/tpembelian/com/finance');
        }
        
        $com_pulsa_log = array(
            'request'       => $this->input->raw_input_stream,
            'ip_request'    => $this->input->ip_address(),
            'msisdn'        => $in_pln,
            'product'       => $topup_product_code,
            'nominal'       => $topup_total,
            'urlhit'        => $URL,
            'response'      => $res_call_hit_topup,
            'res_status'    => $topup_status,
            'res_code'      => $topup_respon_code,
            'res_message'   => $topup_message,
            'res_saldo'     => $topup_saldo,
            'userid'        => $in_agenid,
            'trx_status'    => 'topup',
            'desc_trx'      => 'last status = hit topup 3party',
            'provider'      => $topup_provider,
            'price_selling' => $topup_total,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'      => $trace_id,
            'provider'      => $topup_provider
        );
        
        $res_pulsa_log = $this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log);
        if(!$res_pulsa_log){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->messages->add('Problem internal','warning');
            redirect(base_url().'admin/tpembelian/com/finance');
            //return;
        }
        if(strtolower($topup_respon_code) != '00'){
            if($topup_respon_code == '03'){
                $data = array('status' => FALSE,'error_code' => '613','message' => 'invalid pin','data' => array('sn' => '','code' => ''));                
            }elseif($topup_respon_code == '04'){
                $data = array('status' => FALSE,'error_code' => '632','message' => 'invalid product','data' => array('sn' => '','code' => ''));          
            }elseif($topup_respon_code == '10'){
                $data = array('status' => FALSE,'error_code' => '611','message' => 'invalid plnid','data' => array('sn' => '','code' => ''));       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '623','message' => 'saldo tabungan tidak cukup','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '06'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '68'){
                $data = array('status' => FALSE,'error_code' => '634','message' => 'pending','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '30'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }else{
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            } 
            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->messages->add('Error di pihak provider, ['.$topup_respon_code.'-'.$topup_message.'], <br>Transaction Close', 'info');
            redirect(base_url().'admin/tpembelian/com/finance');
        }        
       
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_desc_trans                  = $this->_Kodetrans_by_desc(MFINANCE_CODE);
        $in_tob                         = $this->_Kodetrans_by_tob(MFINANCE_MYCODE);
        $in_keterangan                  = $in_desc_trans." ".$in_pln." ".  $this->Rp($topup_total)." SN:".$topup_no_serial;
        
        $in_KUITANSI    = $this->_Kuitansi();
              
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>MFINANCE_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$topup_total,
            'ADM'               =>'',
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>MFINANCE_CODE,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            //$this->response($arr_data);
            
        $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("TABTRANS")');
         if(!$res_ins){
            //$data = array('status' => FALSE,'error' => 'error db', 'data' => '');
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
            $this->messages->add('Problem internal, <br>Transaction Close', 'info');
            redirect(base_url().'admin/tpembelian/com/finance');
         }
         $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
        $this->Tab_model->Saldo_upd_by_rekening($in_rkning);
        /*
        $res_sum12  = $this->Tab_model->Sum_1_2_taptrans($in_rkning);
        if(!$res_sum12){
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);              
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('select', $trace_id, array(), 'failed, Tab_model->Sum_1_2_taptrans('.$in_rkning.')');
            $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
            $this->logAction('response', $trace_id, $data, '');
            $this->logAction('response', $trace_id, array(),'error :'.$res_sum12);
            $this->messages->add('Problem internal, <br>Transaction Close', 'info');
            redirect(base_url().'admin/tpembelian/com/finance');
            //return;
        }
        foreach ($res_sum12 as $sub12) {
            $setoran12      = $sub12->SETORAN;
            $penarikan12    = $sub12->PENARIKAN;
            $setoran_bunga12    = $sub12->SETORAN_BUNGA;
            $penarikan_bunga12    = $sub12->PENARIKAN_BUNGA;
        }
        
        $hasil      = $setoran12 - $penarikan12;
        $bunga      = $setoran_bunga12 - $penarikan_bunga12;                        
                            
        $datatabung =   array( //rekening sender
                                'SALDO_AKHIR' => $hasil,
                                'STATUS' => '1',
                                'SALDO_AKHIR_TITIPAN_BUNGA' => $bunga);
       
        $res_upd_tab = $this->Tab_model->upd_tabung($in_rkning,$datatabung);
        if(!$res_upd_tab){    
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID); 
            $this->logAction('update', $trace_id, array(), 'failed, Tab_model->upd_tabung('.$in_rkning.','.$datatabung.')');
            $this->logAction('delete', $trace_id, array(), 'rollback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => '');
            $this->logAction('response', $trace_id, array(),'error :'.$res_upd_tab);
            $this->logAction('response', $trace_id, $data, '');
            
            $this->messages->add('Problem internal, <br>Transaction Close', 'info');
            redirect(base_url().'admin/tpembelian/com/finance');
        }       
        */
        $res_call_tab   = $this->Tab_model->Tab_byrek($in_rkning);
        if($res_call_tab){
            foreach($res_call_tab as $sub_call_tab){
                $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
            }
        }else{
            $this->logAction('response', $trace_id, array(),'error :'.$res_call_tab);
        }

        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agenid);
        $this->logAction('response', $trace_id, array(),'error :Sys_daftar_user_model->Perk_kas('.$in_agenid.')');
        if($res_call_kode_perk_kas){
            foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
            }
        }else{
            $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
            $this->logAction('response', $trace_id, array(),'error :'.serialize($res_call_kode_perk_kas));
        }
        $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
        $this->logAction('response', $trace_id, array(),'error :Perk_model->perk_by_kodeperk('.$in_kode_perk_kas.')');
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            //sender
        $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
        if($res_call_tab_integrasi_by_kd){
            foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS ?: '';
                $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK ?: '';
                $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM ?: '';                                                      
            }
        }else{
                $in_kode_perk_kas_in_integrasi          =  '';
                $in_kode_perk_hutang_pokok_in_integrasi =  '';
                $in_kode_perk_pend_adm_in_integrasi     =  ''; 
        }
        //receiver
        
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        if($res_call_perk_kode_all){
            foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
                $count_as_total = $sub_call_perk_kode_all->total ?: 0;
            }        
        }else{
            $count_as_total = '';
        }
        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
        if($res_call_tab_keyvalue){
            foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
                $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE ?: 'TAB';
            }
        }else{
            $res_kode_jurnal = 'TAB';
        }
        $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                'USERID'            =>  $in_agenid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            );
        $ar_trans_detail = array(
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                'DEBET'         =>  0, 
                'KREDIT'        =>  $topup_total                                                             
            ),array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
                'DEBET'         =>  $topup_total, 
                'KREDIT'        =>  0
            )
        );       

        $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
        $this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
        $this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
        if($res_run){
            
            if(strtolower($in_paytype) == "agent"){
                $this->_tabung($in_rkning, $r_price_selling, $in_agenid, $in_kode_perk_hutang_pokok_in_integrasi, $in_kode_perk_kas);
            }
            
            $in_addpoin = array(
                'tid'   => $gen_id_TABTRANS_ID,
                'agent' => $in_agenid,
                'kode'  => MFINANCE_CODE,
                'jenis' => 'TAB',
                'nilai' => $topup_total ?: 0
            );
            $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
            $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
            if($res_poin){
                $this->logAction('result', $trace_id, array(), 'result');
            }else{
                $this->logAction('result', $trace_id, array(), 'error');
            }            

            $arrdata = array(
                'sn' => $topup_no_serial,
                'code' => $trace_id
            );
            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','data' => $arrdata);
            $dataupd = array('master_id' => $gen_id_MASTER,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            $this->logAction('info ', $trace_id, $arrdata, 'serial number : ');
            $this->logAction('response', $trace_id, $data, '');
            $this->messages->add('Transaction successfully', 'info');
            redirect(base_url().'admin/tpembelian/com/finance');
        }else{            
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
            $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
            $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');          
            $this->logAction('response', $trace_id, $data, '');
            $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
            $this->Tab_model->Saldo_upd_by_rekening($in_rkning);
            $this->messages->add('Proble internal', 'error');
            redirect(base_url().'admin/tpembelian/com/finance');
        }          
    
    }
    public function Pln() {
        //var_dump($this->session->userdata());
        $crud = $this->generate_crud('com_pulsa'); 
        $crud->set_theme('datatables');
        //$crud->set_model('Deposito_nasabah_model');
        $crud->columns('provider','product_id','product_alias','price_selling','lastdtm');
        $crud->edit_fields('price_original','price_selling','status','lastdtm');
        $crud->display_as('dtm','Date');        
        $crud->display_as('lastdtm','Update');        
        $crud->display_as('product_id','Product');        
        $crud->display_as('harga','Price');        
        $crud->field_type('type', 'dropdown', array('PULSA','PLN')); 
        $crud->where('type', 'PLN');
        $crud->where('status', 'OPEN');
//        $crud->callback_column('price_original',array($this,'rp'));
        $crud->callback_column('price_selling',array($this,'rp'));
//        $crud->callback_column('product_alias',array($this,'rp'));
        //$crud->add_action('Cash', 'Pilih', 'admin/tdeposito/tsimpanan_berjangka/titipan_form', '');
        $crud->add_action('Beli', 'Pilih', 'admin/tpembelian/com/pln_pra_mark', '');
        $crud->unset_delete();
        $crud->unset_edit();
        $crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_view();
        $crud->set_subject('PLN');
        $this->mTitle = '[1023]Pembelian PLN Token';
        $this->render_crud();
    }
    public function Pln_pra_mark($nometer = '') {
        if(empty($nometer)){
            redirect(base_url().'admin/tpembelian/com/pln');
        }        
        $res_call = $this->Pay_model->Pulsa_by_product($nometer,'PLN');
        if($res_call){
            foreach ($res_call as $subcall) {
                $g_product_id       = $subcall->product_id;
                $g_product_alias    = $subcall->product_alias;
                $g_price_selling    = $subcall->price_selling;
            }
        }else{
            return FALSE;
        }
        $this->mViewData['productid'] = $g_product_id;
        $this->mViewData['aliasname'] = $this->Rp($g_product_alias);
        $this->mViewData['pricesell'] = $this->Rp($g_price_selling);
        
        $this->load->library('form_validation');
         $this->mTitle = '[1024]Pembelian PLN TOKEN';
        $this->form_validation->set_rules('jenis','Jenis Pembayaran','required');        
        $this->form_validation->set_rules('productid','Product ID','required');        
        $this->form_validation->set_rules('nometer','No meter','trim|required|min_length[8]|max_length[20]|integer');
        
        if($this->form_validation->run()===FALSE){
            //$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">','</div>'); 
            $this->form_validation->set_error_delimiters('<div  class="alert alert-warning">', '</div>');
            $this->render('pembelian/pln_token');            
        }else{
            $in_paytype          = $this->input->post('jenis') ?: '';
            $in_nometer          = $this->input->post('nometer') ?: '';               
            $in_productid        = $this->input->post('productid') ?: '';

            
            $this->mViewData['plnid']   = $in_nometer;
            $this->mViewData['paytype'] = $in_paytype;
            $this->mViewData['produk']  = $in_productid;
            $this->mViewData['alias']  = $this->Rp($g_product_alias);
            $this->mViewData['harga']   = $this->Rp($g_price_selling);
            $this->mViewData['catalogid']   = $nometer;
            $this->mTitle = '[1024]Pembelian PLN TOKEN';
            $this->render('pembelian/pln_token_confirm');
        }
    }
    function Pln_pra_purchase() {
        $trace_id = $this->Logid();
        $this->logAction('info', $trace_id, array(), 'parameter :'.$this->uri->uri_string());
        $in_agenid          = $this->session->userdata('user_id') ?: ''; //**     
        $in_pln             = $this->input->post('meterid') ?: '';
        $in_produk          = $this->input->post('produk') ?: '';
        $in_paytype         = $this->input->post('paytype') ?: '';
        $in_catalogid         = $this->input->post('catalogid') ?: '';
        $in_kdepin          = '9999';       //**  
        
        
        if(strtolower($in_paytype) == "nasabah"){      
            $this->logAction('info', $trace_id, array(), 'paytype nasabah ditutup');
            $this->messages->add('Jenis pembayaran dengan rekening nasabah tidak diijinkan', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid);            
        }elseif(strtolower($in_paytype) == "agent"){
            $this->logAction('info', $trace_id, array(), 'paytype agent');
            $this->load->model('Admin_user2_model');
            $res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agenid);
            $this->logAction('hit model', $trace_id, array(), 'Admin_user2_model->User_by_username('.$in_agenid.')');
                    
            if($res_call_user_agent){
                foreach ($res_call_user_agent as $sub_call_user_agent) {
                    $agn_norekening = $sub_call_user_agent->no_rekening;
                    $agn_active = $sub_call_user_agent->active;
                    $agn_username = $sub_call_user_agent->username;
                }

                if(empty($agn_norekening)){
                    $this->logAction('info', $trace_id, array(), 'norekening : Account Anda belum disetting Rekening untuk debet beban biaya pembelian<br>Transaction Close');
                    $this->messages->add('Account Anda belum disetting Rekening untuk debet beban biaya pembelian<br>Transaction Close', 'warning');             
                    redirect(base_url().'admin/tpembelian/com/pln');   
                }
                if($agn_active == "0"){
                    $this->logAction('info', $trace_id, array(), 'Account Anda Inactive, <br>Transaction Close');                    
                    $this->messages->add('Account Anda Inactive, <br>Transaction Close', 'warning');             
                    redirect(base_url().'admin/tpembelian/com/pln');   
                }
                $in_rkning =  $agn_norekening;
            }else{
                $this->logAction('info', $trace_id, array(), 'Account Anda tidak ditemukan, <br>Transaction Close');
                $this->messages->add('Account Anda tidak ditemukan, <br>Transaction Close', 'warning');             
                redirect(base_url().'admin/tpembelian/com/pln');   
            }
        }else{
            $this->logAction('info', $trace_id, array(), 'Jenis Pembayaran invalid, <br>Transaction Close');                
            $this->messages->add('Jenis Pembayaran invalid, <br>Transaction Close', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pln');   
        }
        $res_product = $this->Pay_model->Pulsa_by_product($in_produk,'PLN');
         $this->logAction('hit model', $trace_id, array(), 'Pay_model->Pulsa_by_product('.$in_produk.',PLN)'); 
        if( ! $res_product){
            $this->logAction('result', $trace_id, array(), 'Product PLN invalid, <br>Transaction Close'); 
            $this->messages->add('Product PLN invalid, <br>Transaction Close', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pln');     
        }
        foreach ($res_product as $sub_product) {
            $r_provider     = $sub_product->provider;
            $r_kode_produk     = $sub_product->product_id;
            $r_type             = $sub_product->type;
            //$r_price_original     = $sub_product->price_original;
            $r_price_selling     = $sub_product->price_selling;
            $r_product_alias     = $sub_product->product_alias;
        }
        
        //**** cek nasabah
        $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
        $this->logAction('hit model', $trace_id, array(), 'Tab_model->Tab_nas('.$in_rkning.')'); 
        if(!$res_cek_rek){   
            $this->logAction('result', $trace_id, array(), 'rekening invalid, <br>Transaction Close'); 
            $this->messages->add('rekening invalid, <br>Transaction Close', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid);   
        }
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
            $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
            $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
        }
//        if(floatval(round($r_price_selling)) > round($cek_saldo_akhir)){
//            $this->logAction('info', $trace_id, array(), 'Saldo tabungan tidak cukup, <br>Transaction Close'); 
//            $this->messages->add('Saldo tabungan tidak cukup, <br>Transaction Close', 'info');             
//            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid);   
//            
//        }        
        //*****************************************
        //lokasi hit ke server pln
        //*****************************************
        $URL = URLPLN;
        $URL = $URL."?id_pln=".$in_pln;
        $URL = $URL."&kode_produk=".rawurlencode($in_produk);        
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'masih pura puraaa');
        //$res_call_hit_topup = $this->Hitget($URL);
        $res_call_hit_topup = '{"status":"OK","respon_code":"00","request_id":"628551000727","message":"SN=0015-4476-7285-7623-1087\/YUNITAWULANSARI\/R1\/1300VA\/13,30;14\/02\/17 10:13 ISI PLN20 KE 14222094006, BERHASIL SISA. SAL=71.790,HRG=19.860,ID=58146378,SN=0015-4476-7285-7623-1087\/YUNITAWULANSARI\/R1\/1300VA\/13,30; INFO : Perubahan Axis data utk RS silahkan cek harga , thx.","kode_token":"0015-4476-7285-7623-1087\/YUNITAWULANSARI\/R1\/1300VA\/13","saldo":"71790","id":"58146378","provider":"Powermedia"}';
        
        if(!$res_call_hit_topup){
            $this->logAction('info', $trace_id, array(), 'Error di pihak provider, cek kembali transaksi anda, <br>Transaction Close'); 
            $this->messages->add('Error di pihak provider, cek kembali transaksi anda, <br>Transaction Close', 'info');             
            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid); 
            return;
        }
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->respon_code) ? $data_topup->respon_code : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_no_serial        = isset($data_topup->kode_token) ? $data_topup->kode_token : NULL;
        $topup_transid          = isset($data_topup->id) ? $data_topup->id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
       
        
        if(strtolower($topup_status) == 'nok'){
            $this->logAction('result', $trace_id, array(), 'response : Error di pihak provider, cek kembali transaksi anda, <br>Transaction Close');
            $this->messages->add('Error di pihak provider, cek kembali transaksi anda, <br>Transaction Close', 'info');             
            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid); 
            return;
            //return;
        }        
        $com_pulsa_log = array(
            'request'       => $this->input->raw_input_stream,
            'ip_request'    => $this->input->ip_address(),
            'msisdn'        => $in_pln,
            'product'       => $in_produk,
            'nominal'       => $r_product_alias,
            'urlhit'        => $URL,
            'response'      => $res_call_hit_topup,
            'res_status'    => $topup_status,
            'res_code'      => $topup_respon_code,
            'res_message'   => $topup_message,
            'res_saldo'     => $topup_saldo,
            'userid'        => $in_agenid,
            'trx_status'    => 'topup',
            'desc_trx'      => 'last status = hit topup 3party',
            'provider'      => $r_provider,
            'price_selling' => $r_price_selling,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'      => $trace_id,
            'provider'      => $topup_provider
        );
        
        $this->logAction('hit model', $trace_id, array($com_pulsa_log), 'Tab_model->Ins_Tbl(com_pulsa_log)'); 
        if(!$this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log)){   
            $this->logAction('result', $trace_id, array(), 'error insert database'); 
            $this->messages->add('Error Internal, gagal insert ke database, cek kembali transaksi anda', 'warning');             
            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid); 
            return;
        }
        if(strtolower($topup_respon_code) != '00'){
            if($topup_respon_code == '03'){
                $data = array('status' => FALSE,'error_code' => '622','message' => 'invalid pin','sn' =>'','code'=>'');                
            }elseif($topup_respon_code == '04'){
                $data = array('status' => FALSE,'error_code' => '624','message' => 'invalid product','sn' =>'','code'=>'');          
            }elseif($topup_respon_code == '10'){
                $data = array('status' => FALSE,'error_code' => '623','message' => 'invalid plnid','sn' =>'','code'=>'');       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '626','message' => 'saldo tabungan tidak cukup','sn' => '','code'=>'');
            }elseif($topup_respon_code == '06'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            }elseif($topup_respon_code == '68'){
                $data = array('status' => FALSE,'error_code' => '634','message' => 'pending','sn' => '','code'=>'');
            }elseif($topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            }elseif($topup_respon_code == '30'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            }else{
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            } 
            $this->logAction('result', $trace_id, array(), 'Error di pihak provider, ['.$topup_respon_code.'-'.$topup_message.'], <br>Transaction Close'); 
            $this->messages->add('Error di pihak provider, ['.$topup_respon_code.'-'.$topup_message.'], <br>Transaction Close', 'info');             
            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid); 
            return;
        }        
       
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_desc_trans                  = $this->_Kodetrans_by_desc(PLN_TOKEN_CODE);
        $in_tob                         = $this->_Kodetrans_by_tob(PLN_TOKEN_MYCODE);
        $in_keterangan                  = $in_desc_trans." ".strtoupper($r_provider)." ".$in_pln." ".  $this->Rp($r_price_selling)." SN:".$topup_no_serial;       
        $in_KUITANSI    = $this->_Kuitansi();                    
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>PLN_TOKEN_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$r_price_selling,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>PLN_TOKEN_CODE,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            //$this->response($arr_data);
            
        $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("TABTRANS")');
         if(!$res_ins){
            $this->messages->add('Error internal-gagal insert ke database TABTRANS <br>Cek kembali transaksi Anda', 'info');             
            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid); 
            return;
            
         }
        $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
        $this->Tab_model->Saldo_upd_by_rekening($in_rkning);
        /*
        $res_sum12  = $this->Tab_model->Sum_1_2_taptrans($in_rkning);
        $this->logAction('info', $trace_id, $res_sum12, 'Tab_model->Sum_1_2_taptrans('.$in_rkning.')');
        if(!$res_sum12){
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);              
            $this->logAction('hit model', $trace_id, array(), 'Error internal- Gagal Query sisa saldo<br>Cek kembali transaksi Anda');
            $this->messages->add('Error internal- Gagal Query sisa saldo<br>Cek kembali transaksi Anda', 'info');             
            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid); 
            return;
            //array('sn' => '')return;
        }
       
        foreach ($res_sum12 as $sub12) {
            $setoran12      = $sub12->SETORAN;
            $penarikan12    = $sub12->PENARIKAN;
            $setoran_bunga12    = $sub12->SETORAN_BUNGA;
            $penarikan_bunga12    = $sub12->PENARIKAN_BUNGA;
        }
        
        $hasil      = $setoran12 - $penarikan12;
        $bunga      = $setoran_bunga12 - $penarikan_bunga12;                        
                            
        $datatabung =   array( //rekening sender
                                'SALDO_AKHIR' => $hasil,
                                'STATUS' => '1',
                                'SALDO_AKHIR_TITIPAN_BUNGA' => $bunga);       
        $res_upd_tab = $this->Tab_model->upd_tabung($in_rkning,$datatabung);
        $this->logAction('hit model', $trace_id, $datatabung, 'Tab_model->upd_tabung('.$in_rkning.')');
        if(!$res_upd_tab){    
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);       
            $this->logAction('result', $trace_id, $datatabung, 'Error internal- Gagal Update sisa saldo<br>Cek kembali transaksi Anda');
            $this->messages->add('Error internal- Gagal Update sisa saldo<br>Cek kembali transaksi Anda', 'info');             
            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid); 
            return;
        }   
         * */
           
        
        $res_call_tab   = $this->Tab_model->Tab_byrek($in_rkning);
        if($res_call_tab){
            foreach($res_call_tab as $sub_call_tab){
                $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
            }
        }

        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agenid);
        if($res_call_kode_perk_kas){
            foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
            }
        }else{
            $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
        }
        $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            //sender
        $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
        foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

            $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
            $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
            $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;                                                      
        }
        //receiver
        
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
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                'USERID'            =>  $in_agenid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            );
        $ar_trans_detail = array(
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                'DEBET'         =>  0, 
                'KREDIT'        =>  $r_price_selling                                                             
            ),array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
                'DEBET'         =>  $r_price_selling, 
                'KREDIT'        =>  0
            )
        );       
        $this->logAction('hit model', $trace_id, $arr_master, 'Run_transaction >TRANSAKSI_MASTER');
        $this->logAction('hit model', $trace_id, $ar_trans_detail, 'Run_transaction >TRANSAKSI_DETAIL');
            
        $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
        if($res_run){
          if(strtolower($in_paytype) == "agent"){
                $this->_tabung($in_rkning, $r_price_selling, $in_agenid, $in_kode_perk_hutang_pokok_in_integrasi, $in_kode_perk_kas);
            }
//          $data = array('status' => TRUE,'error_code' => '600','message' => 'success','sn' => $topup_no_serial,'code' => $trace_id);
            $dataupd = array('master_id' => $gen_id_MASTER,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);     
            $this->logAction('hit model', $trace_id, $dataupd, 'Pay_model->Upd_logpulsa()');
            $this->getid_session = $this->Trxid();
            $this->mViewData['plnid']   = $in_pln;
            $this->mViewData['produk']  = $in_produk.'-'.$r_product_alias;
            $this->mViewData['nama']    = '';
            $this->mViewData['harga']   = $this->Rp($r_price_selling ?: 0);
            $this->mViewData['token']   = $topup_no_serial;
            $this->mViewData['note_code']   = $this->getid_session;
            
            $setanu = array(
            'plnid'         => $in_pln,
            'produk'        => $in_produk.'-'.$r_product_alias,
            'note_due_date' => '',
            'nama'          => '',
            'harga'         => $this->Rp($r_price_selling ?: 0),
            'token'         => $topup_no_serial,
            'note_code'     => $this->getid_session
        );
        $this->session->unset_userdata($this->getid_session);
        $this->session->set_userdata($this->getid_session, json_encode($setanu));
        
            $this->mTitle = '[1024]Pembelian PLN TOKEN';
            $this->messages->add('Transaksi Berhasil', 'success');
            $this->render('pembelian/pln_token_notice');             
        }else{            
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID); 
            $this->logAction('hit model', $trace_id, array(), 'Error internal- Gagal Insert data ke database transaksi_master, transaksi_detail<br>Cek kembali transaksi Anda');            
            $this->messages->add('Error internal- Gagal Insert data ke database transaksi_master, transaksi_detail<br>Cek kembali transaksi Anda', 'info');             
            redirect(base_url().'admin/tpembelian/com/pln_pra_mark/'.$in_catalogid); 
            return;
        }          
    }
    function Pln_pra_token_print($in_data =  '') {
        
        //var_dump($this->session->userdata());
        if(empty($in_data)){
            $this->messages->add('cannot access', 'info');             
            redirect('admin/tpembiayaan/angsuran');
            return;
        }
        if($this->session->userdata($in_data)){
            $get_data = $this->session->userdata($in_data);
            $cek = json_decode($get_data,true);
            //var_dump($get_data);
            $data = array();
            foreach ($cek as $val) {
                $data['note_name_nsb']  = $val->plnid ?: '';
                $data['note_rekening']  = $val->produk ?: '';
                $data['token']          = $val->note_due_date ?: '';
                $data['note_code']      = $val->note_angsuran ?: '';
                $data['note_serial_n']  = $val->note_serial_n ?: '';
                $data['note_pokok']     = $this->Rp1($val->note_pokok ?: 0);
                $data['note_basil']     = $this->Rp1($val->note_basil ?: 0);
                $data['note_total']     = $this->Rp1($val->note_total ?: 0);
            }
            $this->load->view('kredit/angsuran_print',$data);
        }else{
            $this->messages->add('expired', 'info');             
            redirect('admin/tpembelian/pln');
            return;
        }
        
    }
    public function Pln_postpaid() {        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nometer','No meter','trim|required|min_length[8]|max_length[20]|integer');
        $this->mMenuID = "1024";
        if($this->form_validation->run()===FALSE){
            //$this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">','</div>'); 
            $this->form_validation->set_error_delimiters('<div  class="alert alert-warning">', '</div>');
            $this->mViewData['data'] = array();
            $this->render('pembelian/pln_postpaid');   
            return;
        }else{
            $no_meter          = $this->input->post('nometer') ?: '';            
            $trace_id = $this->logid();
            $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');      
            $this->logheader($trace_id);
            $res_dta_postpaid = $this->Pay_model->List_product(PROVIDER_CODE_POSPAID);            
            if($res_dta_postpaid){
                foreach ($res_dta_postpaid as $val_postpaid) {
                        $productid                  = $val_postpaid->product_id     ? $val_postpaid->product_id : PRODUCT_CODE_POSPAID;
                        $this->biaya_adm            = $val_postpaid->adm_payment_total  ? $val_postpaid->adm_payment_total : 0;
                }
            }else{
                $this->logAction('response', $trace_id, array(), 'failed, Pay_model->List_product('.PROVIDER_CODE_POSPAID.')');
                //$this->response($data);
                $this->messages->add('error config in internal system','warning');
                $this->mViewData['data'] = array();
                $this->mViewData['nometer'] = $no_meter;
                $this->render('pembelian/pln_postpaid'); 
                return;
            }        
            $URL = URLPLN_POSTPAID_INQ;
            $URL = $URL."?product_code=".$productid;
            $URL = $URL."&trx_id=".$trans_id;
            $URL = $URL."&cust_id=".$no_meter;

            $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
            $res_call_hit_topup = $this->Hitget($URL);
            //$res_call_hit_topup = '{"status":"OK","rc":"00","partner_id":"INDOSIS","product_code":"5002","trx_id":"17121387106994553432","cust_id":"525060838874","cust_name":"TATI SURIYAH","rec_payable":"1","rec_rest":[],"reffno_pln":"17CD94917002","unit_svc":[],"svc_contact":[],"tariff":[],"daya":[],"admin_fee":"1600","periode1":"201712","periode2":["      "],"periode3":["      "],"periode4":["      "],"duedate":[],"mtr_date":[],"bill_amount":"24852","insentif":[],"ppn":[],"penalty":[],"lwbp_last":"00007851","lwbp_crr":"00007915","wbp_last":[],"wbp_crr":[],"kvarh_last":[],"kvarh_crr":[],"message":"Berhasil","provider":"Mitracomm"}';

            if(!$res_call_hit_topup){
                $this->logAction('result', $trace_id, array(), 'failed, response error third party');
                $this->messages->add('failed, response error third party','warning');
                $this->mViewData['data'] = array();
                $this->mViewData['nometer'] = $no_meter;
                $this->render('pembelian/pln_postpaid'); 
                return;
            }
            $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
            $data_topup = json_decode($res_call_hit_topup);
            if($data_topup){
                $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
                $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
                $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
                $topup_partner_id       = isset($data_topup->partner_id) ? $data_topup->partner_id : NULL;
                $topup_product_code     = isset($data_topup->product_code) ? $data_topup->product_code : NULL;
                $topup_transid          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
                $topup_cust_id          = isset($data_topup->cust_id) ? $data_topup->cust_id : NULL;
                $topup_cust_name        = isset($data_topup->cust_name) ? $data_topup->cust_name : NULL;
                $topup_rec_payable      = isset($data_topup->rec_payable) ? $data_topup->rec_payable : NULL;
                $topup_rec_rest         = isset($data_topup->rec_rest) ? $data_topup->rec_rest : NULL;
                $topup_reffno_pln       = isset($data_topup->reffno_pln) ? $data_topup->reffno_pln : NULL;
                $topup_unit_svc         = isset($data_topup->unit_svc) ? $data_topup->unit_svc : NULL;
                $topup_svc_contact      = isset($data_topup->svc_contact) ? $data_topup->svc_contact : NULL;
                $topup_tariff           = isset($data_topup->tariff) ? $data_topup->tariff : NULL;
                $topup_daya             = isset($data_topup->daya) ? $data_topup->daya : NULL;
                $topup_admin_fee        = isset($data_topup->admin_fee) ? $data_topup->admin_fee : 0;
                $this->topup_periode1   = isset($data_topup->periode1) ? $data_topup->periode1 : NULL;
                $topup_periode2         = isset($data_topup->periode2) ? $data_topup->periode2 : NULL;
                $topup_periode3         = isset($data_topup->periode3) ? $data_topup->periode3 : NULL;
                $topup_periode4         = isset($data_topup->provider) ? $data_topup->provider : NULL;
                $topup_duedate          = isset($data_topup->duedate) ? $data_topup->duedate : NULL;
                $topup_mtr_date         = isset($data_topup->mtr_date) ? $data_topup->mtr_date : NULL;
                $topup_bill_amount      = isset($data_topup->bill_amount) ? $data_topup->bill_amount : 0;
                $topup_insentif         = isset($data_topup->insentif) ? $data_topup->insentif : 0;
                $topup_ppn              = isset($data_topup->ppn) ? $data_topup->ppn : 0;
                $topup_penalty          = isset($data_topup->penalty) ? $data_topup->penalty : 0;
                $topup_lwbp_last        = isset($data_topup->lwbp_last) ? $data_topup->lwbp_last : NULL;
                $topup_lwbp_crr         = isset($data_topup->lwbp_crr) ? $data_topup->lwbp_crr : NULL;
                $topup_wbp_last         = isset($data_topup->wbp_last) ? $data_topup->wbp_last : NULL;
                $topup_wbp_crr          = isset($data_topup->wbp_crr) ? $data_topup->wbp_crr : NULL;
                $topup_kvarh_last       = isset($data_topup->kvarh_last) ? $data_topup->kvarh_last : NULL;
                $topup_kvarh_crr        = isset($data_topup->kvarh_crr) ? $data_topup->kvarh_crr : NULL;
            }
            if(strtolower($topup_status) == 'nok'){
                $this->logAction('response', $trace_id, array(), 'failed, status topup NOK');
                $this->messages->add('error provider, coba lagi nanti','warning');  
                $this->mViewData['data'] = array();
                $this->mViewData['nometer'] = $topup_cust_id;
                $this->render('pembelian/pln_postpaid'); 
                return;
                //$this->render('pembelian/pln_postpaid');
            }
            if(strtolower($topup_respon_code) == '88'){
                $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode - TAGIHAN LUNAS');
                $this->messages->add('<h2><i>TAGIHAN LUNAS</i></h2>','info');
                $this->mViewData['data'] = array();
                $this->mViewData['nometer'] = $topup_cust_id;
                $this->render('pembelian/pln_postpaid'); 
                return;
                //$this->render('pembelian/pln_postpaid');
            }
            if(strtolower($topup_respon_code) != '00'){
                if($topup_respon_code == '04'){
                    $data_response = '622 - invalid product';          
                }elseif($topup_respon_code == '10' || $topup_respon_code == '63'){
                    $data_response = '603 - PLNID / NOMETER tidak terdaftar';       
                }elseif($topup_respon_code == '88'){
                    $data_response = '601 - Tagihan lunas';
                }elseif($topup_respon_code == '89'){
                    $data_response = '621 - error provider';
                }elseif($topup_respon_code == '99'){
                    $data_response = '621 - error provider';
                }elseif($topup_respon_code == '05'){
                    $data_response = '602 - saldo insufficent balance';
                }else{
                    $data_response = '621 - error provider';
                }            
                $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode - '.$data_response);
                //$this->logAction('response', $trace_id, $data_response, '');
                $this->messages->add($data_response,'warning');
                $this->mViewData['data'] = array();
                $this->mViewData['nometer'] = $topup_cust_id;
                $this->render('pembelian/pln_postpaid');
                return;
            } 
            if(!$this->Tab_model->Ins_Tbl('com_pln_ses',$data_topup)){
                $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => $this->res_result);
                $this->logAction('insert', $trace_id, array(), 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
                $this->messages->add('Error internal, databases error, please contact IT team','info');
                $this->mViewData['data'] = array();
                $this->mViewData['nometer'] = $topup_cust_id;
                $this->render('pembelian/pln_postpaid'); 
                return;
                //$this->response($data);
            }
            $this->tot_tagihan  = $topup_bill_amount;
            $this->total        = $this->tot_tagihan + $this->biaya_adm;
            //$this->logAction('data', $trace_id, array(), 'tagihan: '.$this->tot_tagihan.' + admin: '.$biaya_adm.' = '.);
            $arr_upd = array(
                'log_trace'     => $trace_id,
                'tagihan'       => $this->tot_tagihan,
                'biaya_adm'     => $this->biaya_adm,
                'total'         => $this->total
            );
            $this->LogAction('update', $trace_id, $arr_upd, 'Pay_model->Upd_logpln');
            $this->Pay_model->Upd_logpln($topup_transid,$arr_upd);
            if(is_array($topup_daya)){ $topup_daya = "";         }
            if(is_array($topup_cust_id)){ $topup_cust_id = "";         }
            if(is_array($topup_cust_name)){ $topup_cust_name = "";         }
         
            $res_result[] = array(
                'meterid'       => $topup_cust_id,
                'cust_name'     => $topup_cust_name,
                'daya'          => $topup_daya,
                'periode'       => $this->topup_periode1,
                'tagihan'       => $this->Rp($this->tot_tagihan),
                'adm'           => $this->Rp($this->biaya_adm),            
                'total'         => $this->Rp($this->total),            
                'code'          => $trace_id            
            ); 
            
            $this->session->unset_userdata($trace_id);
            $this->session->set_userdata($trace_id, 'prepare_charging_pay');
        
            $this->logAction('info', $trace_id, $res_result, 'response tagihan');
            $this->mViewData['data'] = $res_result;
            $this->mViewData['nometer'] = $topup_cust_id;
            $this->render('pembelian/pln_postpaid');
        }        
    }
    function Pln_postpaid_pay($ticketid = '',$in_codepln = '',$in_pln = '') {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('paytype','No meter','trim|required');
        $this->mViewData['ticket']      = $ticketid ?: '';
        $this->mViewData['code']        = $in_codepln ?: '';
        $this->mViewData['meterid']     = $in_pln ?: '';
        $this->mMenuID = "1024";
        if($this->form_validation->run()===FALSE){
            if(empty($in_codepln)){
                    $this->messages->add('Invalid Access','error');
                    $this->mViewData['data'] = array();
                    $this->mViewData['nometer'] = '';
                    $this->render('pembelian/pln_postpaid');
                    return;
            }
            //cek session 
            if($this->session->userdata($in_codepln)){
                if($this->session->userdata($in_codepln) == "prepare_charging_pay"):
                    else:
                        $this->messages->add('Invalid Session','error');
                        $this->mViewData['data'] = array();
                        $this->mViewData['nometer'] = '';
                        $this->render('pembelian/pln_postpaid');
                        return;
                endif;
            }else{
                    $this->messages->add('Expired','error');
                    $this->mViewData['data'] = array();
                    $this->mViewData['nometer'] = '';
                    $this->render('pembelian/pln_postpaid');
                    return;
            }
            $this->render('pembelian/pln_postpaid_pay');
        }else{
            $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : '';
            $in_sticket = $this->input->post('sticket') ? $this->input->post('sticket') : '';
            $in_codepln = $this->input->post('scode') ? $this->input->post('scode') : '';
            $in_pln     = $this->input->post('smeterid') ? $this->input->post('smeterid') : '';

            $trace_id   = $in_codepln ?: $this->Trxid();
            $this->Logheader($trace_id);
            $this->logAction('info', $trace_id, array(), 'paytype ['.$in_paytype.']');
            if(strtolower($in_paytype) == "nasabah"){
                $in_rkning = $this->input->post('rekening') ?: '';
                if(empty($in_rkning)):
                    $this->messages->add('Nomor Rekening Nasabah Kosong','error');
                    $this->logAction('info', $trace_id, array(), 'Nomor Rekening Nasabah Kosong REK : ['.$in_rkning.']');
                    $this->render('pembelian/pln_postpaid_pay');
                    return;
                endif;
            }elseif(strtolower($in_paytype) == "agent"){
                $res_call_user_agent = $this->Admin_user2_model->User_by_username($this->session->userdata('user_id'));
                $this->logAction('check', $trace_id, array(), 'Admin_user2_model->User_by_username ['.$this->session->userdata('user_id').']');

                if($res_call_user_agent){
                    $this->logAction('result', $trace_id,$res_call_user_agent , 'OK');
                    foreach ($res_call_user_agent as $sub_call_user_agent) {
                        $agn_norekening = $sub_call_user_agent->no_rekening ?: '';
                        $agn_active = $sub_call_user_agent->active ?: '';
                        $agn_username = $sub_call_user_agent->username ?: '';
                    }                
                    //$this->_Check_prm_data($agn_norekening, '619', $trace_id, 'norekening invalid');
                    if(empty($agn_norekening)):
                        $this->messages->add('619 - Account Anda belum di setting Rekening, Hubungin atasan Anda','error');
                        $this->logAction('info', $trace_id, array(), '619 - Account Anda belum di setting Rekening, Hubungin atasan Anda');
                        $this->render('pembelian/pln_postpaid_pay');
                        return;
                    endif;
                    if($agn_active == "0"){
                        $this->messages->add('621 - Status Account Anda Inactive, Hubungin atasan Anda','error');
                        $this->logAction('info', $trace_id, array(), '621 - Status Account Anda Inactive, Hubungin atasan Anda');
                        $this->render('pembelian/pln_postpaid_pay');
                        return;
                    }
                    $in_rkning =  $agn_norekening;
                }else{
                    $this->messages->add('619 - Account Anda belum di setting Rekening, Hubungin atasan Anda','error');
                    $this->logAction('info', $trace_id, array(), '619 - Account Anda belum di setting Rekening, Hubungin atasan Anda');
                    $this->render('pembelian/pln_postpaid_pay');
                    return;
                }
            }else{
                $this->messages->add('618 - paytype invalid','error');
                $this->logAction('info', $trace_id, array(), '618 - paytype invalid');
                $this->render('pembelian/pln_postpaid_pay');
                return;
            }
            $this->logAction('info', $trace_id, array(), 'norekening ('.$in_paytype.') =>'.$in_rkning);
            
            $res_product = $this->Pay_model->Pln_ses_by_code($in_codepln,$in_pln);
            if($res_product){                   
                foreach ($res_product as $sub_product) {
                    $topup_status           = isset($sub_product->status) ? $sub_product->status : '' ;
                    $topup_respon_code      = isset($sub_product->rc) ? $sub_product->rc : '';
                    $topup_message          = isset($sub_product->message) ? $sub_product->message : '';
                    $topup_partner_id       = isset($sub_product->partner_id) ? $sub_product->partner_id : NULL;
                    $topup_product_code     = isset($sub_product->product_code) ? $sub_product->product_code : NULL;
                    $topup_transid          = isset($sub_product->trx_id) ? $sub_product->trx_id : NULL;
                    $topup_cust_id          = isset($sub_product->cust_id) ? $sub_product->cust_id : NULL;
                    $topup_cust_name        = isset($sub_product->cust_name) ? $sub_product->cust_name : NULL;
                    $topup_rec_payable      = isset($sub_product->rec_payable) ? $sub_product->rec_payable : NULL;
                    $topup_rec_rest         = isset($sub_product->rec_rest) ? $sub_product->rec_rest : NULL;
                    $topup_reffno_pln       = isset($sub_product->reffno_pln) ? $sub_product->reffno_pln : NULL;
                    $topup_unit_svc         = isset($sub_product->unit_svc) ? $sub_product->unit_svc : NULL;
                    $topup_svc_contact      = isset($sub_product->svc_contact) ? $sub_product->svc_contact : NULL;
                    $topup_tariff           = isset($sub_product->tariff) ? $sub_product->tariff : NULL;
                    $topup_daya             = isset($sub_product->daya) ? $sub_product->daya : NULL;
                    $topup_admin_fee        = isset($sub_product->admin_fee) ? $sub_product->admin_fee : 0;
                    $topup_periode1         = isset($sub_product->periode1) ? $sub_product->periode1 : NULL;
                    $topup_periode2         = isset($sub_product->periode2) ? $sub_product->periode2 : NULL;
                    $topup_periode3         = isset($sub_product->periode3) ? $sub_product->periode3 : NULL;
                    $topup_periode4         = isset($sub_product->provider) ? $sub_product->provider : NULL;
                    $topup_duedate          = isset($sub_product->duedate) ? $sub_product->duedate : NULL;
                    $topup_mtr_date         = isset($sub_product->mtr_date) ? $sub_product->mtr_date : NULL;
                    $topup_bill_amount      = isset($sub_product->bill_amount) ? $sub_product->bill_amount : 0;
                    $topup_insentif         = isset($sub_product->insentif) ? $sub_product->insentif : 0;
                    $topup_ppn              = isset($sub_product->ppn) ? $sub_product->ppn : 0;
                    $topup_penalty          = isset($sub_product->penalty) ? $sub_product->penalty : 0;
                    $topup_lwbp_last        = isset($sub_product->lwbp_last) ? $sub_product->lwbp_last : NULL;
                    $topup_lwbp_crr         = isset($sub_product->lwbp_crr) ? $sub_product->lwbp_crr : NULL;
                    $topup_wbp_last         = isset($sub_product->wbp_last) ? $sub_product->wbp_last : NULL;
                    $topup_wbp_crr          = isset($sub_product->wbp_crr) ? $sub_product->wbp_crr : NULL;
                    $topup_kvarh_last       = isset($sub_product->kvarh_last) ? $sub_product->kvarh_last : NULL;
                    $topup_kvarh_crr        = isset($sub_product->kvarh_crr) ? $sub_product->kvarh_crr : NULL;
                    $topup_tagihan          = isset($sub_product->tagihan) ? $sub_product->tagihan : NULL;
                    $topup_biaya_adm        = isset($sub_product->biaya_adm) ? $sub_product->biaya_adm : NULL;
                    $topup_total            = isset($sub_product->total) ? $sub_product->total : NULL;
                    $this->topup_statushit        = isset($sub_product->status_hit) ? $sub_product->status_hit : 0;
                }
            }else{
                $this->messages->add('618 - Problem internal','error');
                $this->logAction('info', $trace_id, array(), '618 - Problem internal / failed, Pay_model->Pln_ses_by_code('.$in_codepln.','.$in_pln.')');
                $this->render('pembelian/pln_postpaid_pay');
                return;
            }
            if($this->topup_statushit >= 1){
                $this->logAction('info', $trace_id, array(), 'topup_statushit ('.$this->topup_statushit.')');                
                $this->messages->add('622 - Session Expired, Ulangi transaksi dari awal','error');
                $this->logAction('info', $trace_id, array(), '622 - failed, Code sudah pernah digunakan, Session Expired --('.$in_codepln.','.$in_pln.')');
                $this->render('pembelian/pln_postpaid_pay');
                return;
            }               
            $this->Pay_model->Upd_pln_ses_by_code($trace_id,$in_pln,array('status_hit' => $this->topup_statushit + 1));
            $res_dta_postpaid = $this->Pay_model->List_product(PROVIDER_CODE_POSPAID);
            if($res_dta_postpaid){
                foreach ($res_dta_postpaid as $val_postpaid) {
                    $productid          = $val_postpaid->product_id     ? $val_postpaid->product_id : PRODUCT_CODE_POSPAID;
                    $this->biaya_adm_indo     = $val_postpaid->price_original  ? $val_postpaid->price_original : 0;
                    $this->biaya_adm_bmt      = $val_postpaid->price_stok  ? $val_postpaid->price_stok : 0;
                    $this->biaya_adm_agen     = $val_postpaid->price_selling  ? $val_postpaid->price_selling : 0;
                    $this->biaya_adm_prov     = $val_postpaid->adm_payment_provider  ? $val_postpaid->adm_payment_provider : 0;

                    
                }                
            }else{
               $productid   = PRODUCT_CODE_POSPAID;
            }
            $this->biaya_adm = $topup_biaya_adm;
               //**** cek nasabah
            if(strtolower($in_paytype) == "nasabah"){
                $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
                if(!$res_cek_rek){            
                    $this->messages->add('619 - Invalid, Nomor rekening nasabah tidak valid, periksa lagi','error');
                    $this->logAction('info', $trace_id, array(), '619 - Invalid, Nomor rekening nasabah tidak valid, periksa lagi');
                    $this->logAction('response', $trace_id, array(), '619 - failed, Tab_model->Tab_nas('.$in_rkning.')');
                    $this->render('pembelian/pln_postpaid_pay');
                    return;
                }
                foreach ($res_cek_rek as $sub_cek_rek) {
                    $nama_nasabah   = $sub_cek_rek->nama_nasabah;
                    $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
                    $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
                }
                $this->logAction('info', $trace_id, array(), 'total biaya : '.$topup_total);
                $this->logAction('info', $trace_id, array(), 'saldo rekening : '.$cek_saldo_akhir);
                if(floatval(round($topup_total)) > round($cek_saldo_akhir)){
                    $data = array('status' => FALSE,'error_code' => '623','message' => 'saldo tabungan tidak cukup','data' => array('sn' => '','code' => ''));
                    $this->logAction('response', $trace_id, $data, 'failed, saldo insufficent balace');
                    $this->messages->add('623 - Saldo Nasabah tidak cukup (Insufficent Balance)','warning');
                    $this->render('pembelian/pln_postpaid_pay');
                    return;

                }
                //get rekening indosis
                $get_nomor_rekening_partner = $this->Tab_model->Acc_by_partnerid('INDOSIS_PRODUCTION');
                $this->logAction('transaction', $trace_id, array(), 'get rekening partner :Tab_model->Acc_by_partnerid(INDOSIS_PRODUCTION)');
                if(empty($get_nomor_rekening_partner)){
                    $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
                    $this->logAction('response', $trace_id, $data, 'failed');
                     $this->messages->add('632 - error internal','error');
                    $this->render('pembelian/pln_postpaid_pay');
                    return;

                }
                $this->logAction('transaction', $trace_id, array(), 'rek partner : '.$get_nomor_rekening_partner);
                
            }
            //
            $URL = URLPLN_POSTPAID_PAY;
            $URL = $URL."?partner_id=".$topup_partner_id;
            $URL = $URL."&product_code=".$topup_product_code;
            $URL = $URL."&trx_id=".$topup_transid;
            $URL = $URL."&cust_id=".$topup_cust_id;
            $URL = $URL."&cust_name=".rawurlencode($topup_cust_name);
            $URL = $URL."&rec_payable=".$topup_rec_payable;
            $URL = $URL."&rec_rest=".$topup_rec_rest;
            $URL = $URL."reffno_pln=".$topup_reffno_pln;
            $URL = $URL."&unit_svc=".rawurlencode($topup_unit_svc);
            $URL = $URL."&svc_contact=".$topup_svc_contact;
            $URL = $URL."&tariff=".rawurlencode($topup_tariff);
            $URL = $URL."&daya=".$topup_daya;
            $URL = $URL."&admin_fee=".round($topup_admin_fee);
            $URL = $URL."&periode1=".$topup_periode1;
            $URL = $URL."&periode2=".$topup_periode2;
            $URL = $URL."&periode3=".$topup_periode3;
            $URL = $URL."&periode4=".$topup_periode4;
            $URL = $URL."&mtr_date=".$topup_mtr_date;
            $URL = $URL."&bill_amount=".round($topup_bill_amount);
            $URL = $URL."&insentif=".round($topup_insentif);
            $URL = $URL."&ppn=".round($topup_ppn);
            $URL = $URL."&penalty=".round($topup_penalty);
            $URL = $URL."&lwbp_last=".$topup_lwbp_last;
            $URL = $URL."&lwbp_crr=".$topup_lwbp_crr;
            $URL = $URL."&wbp_last=".$topup_wbp_last;
            $URL = $URL."&wbp_crr=".$topup_wbp_crr;
            $URL = $URL."&kvarh_last=".$topup_kvarh_last;
            $URL = $URL."&kvarh_crr=".$topup_kvarh_crr;
            
            $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
            $res_call_hit_topup = $this->Hitget($URL);     

            //$res_call_hit_topup = '{"status":"OK","rc":"00","partner_id":"INDOSIS","product_code":"5002","trx_id":"17121387106994553432","cust_id":"525060838874","cust_name":"TATI SURIYAH","rec_payable":"1","rec_rest":"reffno_pln=17CD94917002","reffno_pln":[],"unit_svc":[],"svc_contact":[],"tariff":[],"daya":[],"admin_fee":"1600","periode1":"201712","periode2":[],"periode3":[],"periode4":"Mitracomm","duedate":[],"mtr_date":[],"bill_amount":"24852","insentif":"0","ppn":"0","penalty":"0","lwbp_last":"7851","lwbp_crr":"7915","wbp_last":[],"wbp_crr":[],"kvarh_last":[],"kvarh_crr":[],"pay_datetime":"20171213094923","footer_message":"\"Informasi Hubungi Call Center 123 Atau Hub PLN Terdekat :\"","receipt_code":"0BAG210ZA98BB8C0210C64DF54B3CB81","message":"Berhasil","provider":"Mitracomm"}';
            if(!$res_call_hit_topup){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
                $this->logAction('result', $trace_id, $data, '633 - failed, response error third party');
                $this->messages->add('633 - error provider','error');
                $this->render('pembelian/pln_postpaid_pay');
                return;
            }
            
            //
            $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
            $data_topup = json_decode($res_call_hit_topup);

            $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
            $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
            $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
            $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
            $topup_no_serial        = isset($data_topup->receipt_code) ? $data_topup->receipt_code : NULL;
            $topup_transid          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
            $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
            
            if(strtolower($topup_status) == 'nok'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
                $this->logAction('response', $trace_id, $data, '633 - failed, status topup NOK');
                $this->messages->add('633 - error provider','error');
                $this->render('pembelian/pln_postpaid_pay');
                return;
            }
            $com_pulsa_log = array(
                'request'       => $this->input->raw_input_stream,
                'ip_request'    => $this->input->ip_address(),
                'destid'        => $in_pln,
                'product'       => $topup_product_code,
                'nominal'       => 'PLN',
                'urlhit'        => $URL,
                'response'      => $res_call_hit_topup,
                'res_status'    => $topup_status,
                'res_code'      => $topup_respon_code,
                'res_message'   => $topup_message,
                'res_saldo'     => $topup_saldo,
                'userid'        => $this->session->userdata('user_id'),
                'trx_status'    => 'payment',
                'desc_trx'      => 'last status = hit topup 3party',
                'provider'      => $topup_provider,
                'adm_provider'  => $this->biaya_adm_prov,
                'adm_indosis'   => $this->biaya_adm_indo,
                'adm_bmt'       => $this->biaya_adm_bmt,
                'adm_agen'      => $this->biaya_adm_agen,
                'adm_fr_mitra'  => $topup_admin_fee,
                'tagihan'       => $topup_tagihan,
                'res_sn'        => $topup_no_serial,
                'res_transid'   => $topup_transid,
                'log_trace'     => $trace_id
            );
            
            if(!$this->Tab_model->Ins_Tbl('com_payment_log',$com_pulsa_log)){
                $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
                $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_payment_log")');
                $this->messages->add('632 - error internal, hubungi cs kemungkinan transaksi telah berhasil','error');
                $this->render('pembelian/pln_postpaid_pay');
                return;
            }             
            if(strtolower($topup_respon_code) != '00'){
                if($topup_respon_code == '03'){
                    $message = "invalid pin";
                    $data = array('status' => FALSE,'error_code' => '613','message' => 'invalid pin','data' => array('sn' => '','code' => ''));                
                }elseif($topup_respon_code == '04'){
                    $message = "invalid product";
                    $data = array('status' => FALSE,'error_code' => '632','message' => 'invalid product','data' => array('sn' => '','code' => ''));          
                }elseif($topup_respon_code == '10'){
                    $message = "invalid nomor pln";
                    $data = array('status' => FALSE,'error_code' => '611','message' => 'invalid plnid','data' => array('sn' => '','code' => ''));       
                }elseif($topup_respon_code == '88'){
                    $message = "error provider";
                    $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
                }elseif($topup_respon_code == '89'){
                    $message = "error provider";
                    $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
                }elseif($topup_respon_code == '99'){
                    $message = "error provider";
                    $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
                }elseif($topup_respon_code == '05'){
                    $message = "Saldo di provider tidak mencukupi (insufficent balance)";
                    $data = array('status' => FALSE,'error_code' => '623','message' => 'saldo tabungan tidak cukup','data' => array('sn' => '','code' => ''));
                }elseif($topup_respon_code == '06'){
                    $message = "error provider";
                    $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
                }elseif($topup_respon_code == '68'){
                    $message = "<h3>PENDING</h3>";
                    $data = array('status' => FALSE,'error_code' => '634','message' => 'pending','data' => array('sn' => '','code' => ''));
                }elseif($topup_respon_code == '63'){
                    $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
                    $message = "error provider";                    
                }elseif($topup_respon_code == '30'){
                    $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
                    $message = "error provider";                    
                }else{
                    $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
                    $message = "error provider";
                } 

                $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
                $this->logAction('info', $trace_id, $data, '');
                $this->messages->add($message,'message');
                $this->render('pembelian/pln_postpaid_pay');
                return;
            }
            $get_code_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR_'.$in_paytype);
            if($get_code_trans){
                foreach ($get_code_trans as $vres) {
                    $res_code_kode      = $vres->kode ?: '';
                    $in_desc_trans      = $vres->deskripsi ?: '';
                    $res_code_tob       = $vres->tob ?: '';
                }
            }else{
                $in_desc_trans                  = $this->_Kodetrans_by_desc('100');
            }
            $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
            if($get_mycode_trans){
                foreach ($get_mycode_trans as $v_myres) {
                    $res_mycode_kode    = $v_myres->kode ?: '';
                    $in_desc_trans      = $v_myres->deskripsi ?: '';
                    $in_tob             = $v_myres->tob ?: '';
                }
            }else{
                $in_tob                         = $this->_Kodetrans_by_tob('100');
            }
            $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
            $gen_id_COMMON_ID               = $this->App_model->Gen_id();
            $gen_id_MASTER                  = $this->App_model->Gen_id();
            $in_keterangan                  = $in_desc_trans." ".$in_pln." ".  $this->Rp($topup_total)." SN:".$topup_no_serial;

            $in_KUITANSI    = $this->_Kuitansi();
            
            $arr_data = array(
                'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
                'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
                'NO_REKENING'       =>$in_rkning, 
                'COMTYPE'           =>'PLN',
                'NOCUSTOMER'        =>$in_pln,
                'MY_KODE_TRANS'     =>$res_mycode_kode, 
                'KUITANSI'          =>$in_KUITANSI, 
                'POKOK'             =>$topup_tagihan,
                'ADM'               =>$topup_biaya_adm,
                'KETERANGAN'        =>$in_keterangan, 
                'VERIFIKASI'        =>'1', 
                'USERID'            =>  $this->session->userdata('user_id'), 
                'KODE_TRANS'        =>$res_code_kode,
                'TOB'               =>$in_tob, 
                'SANDI_TRANS'       =>'',
                'KODE_PERK_OB'      =>'', 
                'NO_REKENING_VS'    =>'', 
                'KODE_KOLEKTOR'     =>'000',
                'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
                'ADM_PENUTUPAN'     =>'0.00',
                'MODEL'             =>  $this->model_pay,
                'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);
            $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("COMTRANS")');
            if(!$res_ins){
                $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
                $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');               
                $this->messages->add('632 - error internal','error');
                $this->render('pembelian/pln_postpaid_pay');
                return;
            }
            if(strtolower($in_paytype) == 'nasabah'){
                //debit nasabah
                $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$in_rkning.''
                        . ','.$in_pln.','.$in_agenid.','.$topup_total.','.PLN_POSTPAID_CODE.','.PLN_POSTPAID_MYCODE.','.$gen_id_TABTRANS_ID);
                $res_debit_nasabah = $this->Tab_model->Tab_commerce($in_rkning,$in_pln,$in_agenid,
                        $topup_total,PLN_POSTPAID_CODE,PLN_POSTPAID_MYCODE,$gen_id_TABTRANS_ID);
                //insert tab indosis
                if($res_debit_nasabah){
                    $this->logAction('insert', $trace_id, array(), 'result : OK');
                }else{                
                    $this->logAction('insert', $trace_id, array(), 'result : failed');
                }
                $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$get_nomor_rekening_partner.''
                        . ','.$in_pln.','.$in_agenid.','.$topup_total.','.PLN_POSTPAID_INDOSIS_CODE.','
                        . ''.PLN_POSTPAID_INDOSIS_MYCODE.','.$gen_id_TABTRANS_ID);
                $res_indosis = $this->Tab_model->Tab_commerce($get_nomor_rekening_partner,$in_pln,
                        $in_agenid,$topup_total,PLN_POSTPAID_INDOSIS_CODE,PLN_POSTPAID_INDOSIS_MYCODE,$gen_id_TABTRANS_ID);
                if($res_indosis){
                    $this->logAction('insert', $trace_id, array(), 'result : OK');
                }else{                
                    $this->logAction('insert', $trace_id, array(), 'result : failed');
                }            
            }
            //poin
//            $in_addpoin = array(
//                'tid'   => $gen_id_TABTRANS_ID,
//                'agent' => $this->session->userdata('user_id'),
//                'kode'  => PLN_POSTPAID_CODE,
//                'jenis' => 'COM',
//                'nilai' => $topup_total
//            );
//            $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
//            $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
            
            //
            $this->logAction('result', $trace_id, array(), 'response');
            
            $arrdata = array(
                'sn' => $topup_no_serial,
                'code' => $trace_id
            );
            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','data' => $arrdata);
            $dataupd = array('master_id' => $gen_id_TABTRANS_ID,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            //$this->logAction('info', $trace_id, $dataupd, 'upd');
            $this->Pay_model->Upd_logpayment($trace_id,$dataupd);          

            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            $this->logAction('info ', $trace_id, $arrdata, 'serial number : ');
            $this->logAction('response', $trace_id, $data, '');
            //$this->response($data); 
            $this->messages->add('Transaksi Selesai','success');
            redirect('admin/tpembelian/com/pln_postpaid_print/?_TID='.$trace_id.'&_NomorMeter='.$in_pln.'&_RefID='.$topup_no_serial);      
        }        
    }
    public function Pln_postpaid_print() {
        //?_TID=aNBeD4nogr6tzj/_NomorMeter=525060838874/_RefID=0BAG210ZA98BB8C0210C64DF54B3CB81
        $inscode  = $this->input->get('_TID') ?: '';
        $intrxid  = $this->input->get('_NomorMeter') ?: '';
        $inreff   = $this->input->get('_RefID') ?: '';
        if(empty($inscode)):
            redirect();
        endif;
        if(empty($intrxid)):
            redirect();
        endif;
        if(empty($inreff)):
            redirect();
        endif;
        $this->load->model('Pay_model');
        $ref = $this->Pay_model->Ref($inscode,$intrxid,$inreff);
        if($ref){
            $data['ref']    = $ref;
            $data['tid']    = $inscode;
            $this->load->view('pembelian/pln_postpaid_print_tes',$data);
        }else{
            $this->messages->add('something wrong','error');
            redirect();
        }
        
               
    }
    function Rp($value)    {
        if(is_numeric($value)){
            return number_format($value,0,",",".");
        }
        return $value;
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
        }else{
            $out_tob   = NULL;
        }
        return $out_tob;
    }
    
    
    
    function _Kuitansi() {
        $result_kwitn = $this->Com_model->Kwitansi();
            if($result_kwitn){
                foreach($result_kwitn as $res_kwi){
                    $out_nokwi          = $res_kwi->KUITANSI;
                }
            }else{
                $out_nokwi = "";
            }
            if(empty($out_nokwi)){
                $out_nokwi = date('Ym')."001";
            }else{
                $out_nokwi = $out_nokwi + 1;
            }
            return $out_nokwi;
    }
    protected function _Tgl_hari_ini(){
        return Date('Y-m-d');
    }  
    function get_init_total_adm($post_array,$primary_key)    {
      $sqlStr = "UPDATE com_pulsa SET adm_payment_total = 
        IF((`com_pulsa`.`price_original` IS NOT NULL),`com_pulsa`.`price_original`,0) +  
        IF((`com_pulsa`.`price_stok` IS NOT NULL),`com_pulsa`.`price_stok`,0) +  
        IF((`com_pulsa`.`price_selling` IS NOT NULL),`com_pulsa`.`price_selling`,0) +  
        IF((`com_pulsa`.`adm_payment_provider` IS NOT NULL),`com_pulsa`.`adm_payment_provider`,0), 
	userid = '".$this->session->userdata('user_id')."' ,	lastdtm = now() , 
	`desc` = 'edit biaya admin oleh ".strtolower($this->session->userdata('username'))."' 
	WHERE id_pulsa=" . $primary_key;
      $this->db->query($sqlStr);
    }
    function get_init_total_adm_users($post_array,$primary_key)    {
      $sqlStr = "UPDATE com_pulsa SET adm_user_payment_total = 
        IF((`com_pulsa`.`adm_user_payment_indosis` IS NOT NULL),`com_pulsa`.`adm_user_payment_indosis`,0) +   
        IF((`com_pulsa`.`adm_user_payment_bmt` IS NOT NULL),`com_pulsa`.`adm_user_payment_bmt`,0) +  
        IF((`com_pulsa`.`adm_payment_provider` IS NOT NULL),`com_pulsa`.`adm_payment_provider`,0), 
	userid = '".$this->session->userdata('user_id')."' ,	adm_user_paymment_lastupd = now() , 
	`desc` = 'edit biaya admin oleh ".strtolower($this->session->userdata('username'))."' 
	WHERE id_pulsa=" . $primary_key;
      $this->db->query($sqlStr);
    }
    function my_sum_function($post_array) {
        $post_array['adm_payment_total'] = $post_array['price_original'] + $post_array['price_stok'] + $post_array['price_selling'] + $post_array['adm_payment_provider'];
        return $post_array;
    } 
    function my_sum_functionusers($post_array) {
        $post_array['adm_user_payment_total'] = $post_array['adm_user_payment_indosis'] + $post_array['adm_user_payment_bmt'] + $post_array['adm_payment_provider'];
        return $post_array;
    } 
}

/*
//define('URLPULSA', 'http://10.1.1.62/API_BMT_NEW/topup_pulsa.php');
define('URLPULSA', 'http://202.43.173.13/API_BMT_NEW/topup_pulsa.php');
define('URLPLN', 'http://202.43.173.13/API_BMT_NEW/tokenpln.php');
//define('URLPLN', 'http://10.1.1.62/API_BMT_NEW/tokenpln.php');
define('FWDPLNTOKEN', base_url().'api/commerce/topup_pln/');
//define('FWDPLNTOKEN', 'http://202.43.173.11:801/ussiweb/api/commerce/topup_pln/');

//define('URL_MFINANCE_INQ', 'http://10.1.1.62/API_BMT_NEW/8_request_inquiry_multifinance.php');
define('URL_MFINANCE_INQ', 'http://202.43.173.13/API_BMT_NEW/8_request_inquiry_multifinance.php');
define('URL_MFINANCE_PAY', 'http://202.43.173.13/API_BMT_NEW/8_request_payment_multifinance.php');
//define('URL_MFINANCE_PAY', 'http://10.1.1.62/API_BMT_NEW/8_request_payment_multifinance.php');
*/

/*
define('PULSA_CODE', '227');
define('PULSA_MYCODE', '200');
define('PLN_TOKEN_CODE', '229');
define('PLN_POSTPAID_CODE', '230');
define('MFINANCE_CODE', '235');
define('MFINANCE_MYCODE', '200');
define('PLN_TOKEN_MYCODE', '200');
define('PLN_POSTPAID_MYCODE', '200');
define('KODE_KANTOR_DEFAULT', '35');
define('KODE_PIN_DEFAULT', '1234');
define('ADM_DEFAULT', '0.00');
define('KODEPERKKAS_DEFAULT', '406');
define('PRODUCT_CODE_POSPAID', '5002');
define('PROVIDER_CODE_POSPAID', '115');
define('PROVIDER_CODE_FINANCE', '113');
define('SIMPAN_MYCODE', '100');
 * 
 * */
 