<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Com
 *
 * @author edisite
 */
class Com extends Admin_Controller {
    //put your code here
    private $tbl_res        = '<tr><td colspan="9" align="center">Tidak ada transaksi</td></tr>';
    private $tbl_resp        = '<tr><td colspan="9" align="center">Tidak ada transaksi</td></tr>';
    private $tbl_profit     = '<tr><td colspan="4" align="center">Tidak ada transaksi</td></tr>';
    private $tbl_profitp     = '<tr><td colspan="4" align="center">Tidak ada transaksi</td></tr>';
    //$dtm                    = date("Y-m-d);
    private $tbl_resp_all        = '<tr><td colspan="13" align="center">Tidak ada transaksi</td></tr>';
    public  $tgl_fr_t, $produklist;
    private $tbl_bayarnow   = '<tr><td colspan="4" align="center">Tidak ada transaksi</td></tr>';
    private $tbl_history_byr_last_month   = '<tr><td colspan="5" align="center">Tidak ada transaksi</td></tr>';
    private $tgl_to_t;
    private $info_tgl;
    
    private $mn_cash_awal, $mn_cash_awal_bfr,$jml_setoran_bfr_bydtm,$total_setoran_bfr,$jml_basil_bfr_bydtm   = 0;
    private $mn_cash_akhir, $mn_cash_akhir_bfr  = 0;
    private $mn_cash_adm, $mn_cash_adm_bfr    = 0;
    
    private $mn_debt_awal, $mn_debt_awal_bfr    = 0;
    private $mn_debt_akhir, $mn_debt_akhir_bfr  = 0;
    private $mn_debt_adm, $mn_debt_adm_bfr    = 0;
    private $mn_cash_tagihan, $mn_cash_tagihan_adm, $mn_cash_tagihan_basil, $mn_debt_tagihan, $mn_debt_tagihan_adm, $mn_debt_tagihan_basil = 0; 
    private $mn_cash_tagihan_bfr, $mn_cash_tagihan_adm_bfr, $mn_cash_tagihan_basil_bfr, $mn_debt_tagihan_bfr, $mn_debt_tagihan_adm_bfr, $mn_debt_tagihan_basil_bfr = 0; 
    private $no = 1;
    private $total_setoranp_bfr,$grand_total_transaksi,$grand_total_basil,$grand_total_setoran, $total_transaksip,$total_transaksi,$profit_cashp,$profit_cash,$profit_debtp,$profit_debt,$total_setoranp,$total_setoran = 0;
    
    private $tgl_fr_gnt = "";
    
    protected $mtagihan, $mprov, $mind, $mbmt, $magen, $mtagen;
    
    public function __construct() {
        parent::__construct();
    }
    public function Preview() {
        $this->load->model('Admin_user2_model');
        $trg_kodetrans  = $this->Com_model->kodetrans(); 
        $trg_integrasi  = $this->Com_model->integrasi();         
        $trg_kdkantr    = $this->App_model->kode_kantor();
        $trg_kdkolek    = $this->Admin_user2_model->Usergroup();
        
        $this->mViewData['kdtrans']     = $trg_kodetrans; 
        $this->mViewData['kdintgr']     = $trg_integrasi; 
        $this->mViewData['kdkantr']     = $trg_kdkantr; 
        $this->mViewData['kdkolek']     = $trg_kdkolek; 
        
        $this->mTitle                   = "[8050]Lap. Transaksi Simpanan";
        $this->mMenuID                  = "8601";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal Awal','required');        
        $this->form_validation->set_rules('tgl_to','Tanggal Akhir','required');        
        $this->form_validation->set_rules('kode_trans','Kode Transaksi','required');        
        $this->form_validation->set_rules('kdintegrasi','Kode Integrasi','required');        
        $this->form_validation->set_rules('kdkantor','Kode Kantor','required');        
        $this->form_validation->set_rules('kdagent','User ID','required');        
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_trans_commerce');            
        }else{
            $in_tgl_from        = $this->input->post('tgl_fr') ?: '';
            $in_tgl_to          = $this->input->post('tgl_to') ?: '';
            $in_kode_trans      = $this->input->post('kode_trans') ?: 'all';
            $in_kode_integrasi  = $this->input->post('kdintegrasi') ?: 'all';
            $in_kode_kantor     = $this->input->post('kdkantor') ?: 'all';
            $in_kode_agent      = $this->input->post('kdagent') ?: 'all';
            
            $tgl_to     = date('Y-m-d', strtotime($in_tgl_to));
            $tgl_fr     = date('Y-m-d', strtotime($in_tgl_from));
            
            $res_mutasi     = $this->Com_model->Lap_mutasi($tgl_fr,$tgl_to,$in_kode_trans,$in_kode_integrasi,$in_kode_kantor,$in_kode_agent);           
            //var_dump($res_mutasi);
            $this->mViewData['mutasi']          = $res_mutasi;
            $this->render('report/lap_mutasi_commerce_res');            
            
        }
    }
    public function LogTrx() {
        
        $transid = random_string('alnum', 16);
        $this->session->unset_userdata('logtrx_status');
        $this->session->set_userdata('logtrx_status',$transid);
        $crud = $this->generate_crud('com_pulsa_log');       
        //$crud->set_theme('datatables');
        $crud->set_model('com_logtransaction');

        //$crud->fields('dtm','userid','nominal','msisdn','product','price_original','price_stok','price_selling','res_message','res_code');
        $crud->display_as('dtm','Tanggal');
        $crud->display_as('res_code','Msg');
        $crud->display_as('price_stok','Harga BMT');
        $crud->display_as('price_selling','Harga Jual Akhir');
        $crud->display_as('price_fr_mitra','Harga Mitra');
        $crud->display_as('price_original','Harga Indosis');
        $crud->display_as('msisdn','NomorID');
        $crud->display_as('res_message','S/N');
        $crud->display_as('nominal','Tipe');
        $crud->callback_column('price_stok',array($this,'_column_bonus_right_align'));
        $crud->add_action('Remove Transaction', '', base_url().'admin/report/com/fail_status/'.$transid.'/','ui-button-icon-primary ui-icon ui-icon-cancel');   
        $crud->add_action('Edit', '', base_url().'admin/report/com/chg_status/'.$transid.'/','ui-button-icon-primary ui-icon ui-icon-pencil');   
        
        $crud->callback_column('price_selling',array($this,'_column_bonus_right_align'));
        $crud->callback_column('res_code',array($this,'_column_status'));
        $crud->callback_column('res_code2',array($this,'_callback_webpage_url'));
        //
       
        $crud->unset_export();
        $crud->unset_print();
        if(strtolower($this->session->userdata('username')) == "devapp" 
                || strtolower($this->session->userdata('username')) == "miftah01"
                || strtolower($this->session->userdata('username')) == "dian_indos"):            
                $crud->columns('dtm','username','userid','nominal','msisdn','product','price_fr_mitra','price_original','price_stok','price_selling','res_message','status','res_code');
        elseif(strtolower($this->session->userdata('username')) == "indosis" || strtolower($this->session->userdata('username')) == "cs_indosis"):
                $crud->unset_add();      
                $crud->unset_edit();
                $this->unset_crud_fields('request','urlhit','response','res_status','res_saldo','trx_status','cp','log_trace','username','res_transid'); 
                $crud->columns('dtm','username','userid','nominal','msisdn','product','price_fr_mitra','price_original','price_stok','price_selling','res_message','status','res_code');
       
        else:
                $crud->unset_add();      
                $crud->unset_edit();
                $this->unset_crud_fields('request','urlhit','response','res_status','res_saldo','trx_status','price_original','price_fr_mitra','cp','log_trace','username','res_transid'); 
                $crud->columns('dtm','username','userid','nominal','msisdn','product','price_original','price_stok','price_selling','res_message','status','res_code');
            endif;
        
        $crud->unset_delete();
        $crud->set_subject('');
	$this->mMenuID = "8602";
        $this->render_crud();

    }
    public function LogTrxPayment() {
        
        $transid = random_string('alnum', 16);
        $this->session->unset_userdata('logtrx_status_payment');
        $this->session->set_userdata('logtrx_status_payment',$transid);
        $crud = $this->generate_crud('com_payment_log');       
        //$crud->set_theme('datatables');
        $crud->set_model('com_logtransaction');
        $crud->columns('dtm','username','userid','nominal','destid','product','tagihan','adm_indosis','adm_bmt','adm_agen','res_sn','status','res_code');
        //$crud->fields('dtm','userid','nominal','msisdn','product','price_original','price_stok','price_selling','res_message','res_code');
        $crud->display_as('dtm','Tanggal');
        $crud->display_as('res_code','status');
        $crud->display_as('tagihan','Tagihan');
//        $crud->display_as('price_original','Adm Indosis');
//        $crud->display_as('price_stok','Adm BMT');
//        $crud->display_as('price_selling','Adm Agent');
        $crud->display_as('destid','NomorID');
        $crud->display_as('res_sn','S/N');
        $crud->display_as('nominal','Tipe');
        $crud->callback_column('adm_indosis',array($this,'_column_bonus_right_align'));
        $crud->add_action('Remove Transaction', '', base_url().'admin/report/com/fail_status_payment/'.$transid.'/','ui-button-icon-primary ui-icon ui-icon-cancel');   
        $crud->add_action('Edit Status', '', base_url().'admin/report/com/chg_status_payment/'.$transid.'/','ui-button-icon-primary ui-icon ui-icon-pencil');   
        
        $crud->callback_column('adm_bmt',array($this,'_column_bonus_right_align'));
        $crud->callback_column('adm_agen',array($this,'_column_bonus_right_align'));
        $crud->callback_column('tagihan',array($this,'_column_bonus_right_align'));
        $crud->callback_column('res_code',array($this,'_column_status'));
        //
        $this->unset_crud_fields('request','urlhit','response','res_status','res_saldo','trx_status','price_original','price_fr_mitra','cp','log_trace','username','res_transid');     
        $crud->unset_export();
        $crud->unset_print();
            $crud->add_action('Print Bukti Pembayaran', '', base_url().'admin/report/com/PaymentPrint/'.$transid.'/','ui-button-icon-primary ui-icon ui-icon-print');   
        if(strtolower($this->session->userdata('username')) == "devapp" 
            || strtolower($this->session->userdata('username')) == "miftah01"):
        
            else:
            $crud->unset_add();      
            $crud->unset_edit();
            
            endif;
        //$crud->unset_read();
        $crud->unset_delete();
        $crud->set_subject('');
	$this->mMenuID = "8604";
        $this->render_crud();

    }
    public function log_user_before_delete($primary_key)    {
        $this->db->where('id',$primary_key);
        $user = $this->db->get('cms_user')->row();

        if(empty($user))
        return false;

//        $this->db->insert('user_logs',array(
//        'user_id' => $primary_key,
//        'action'=>'delete', 
//        'email' => $user->email
//        'updated' => date('Y-m-d H:i:s')));
//        return true;
    }
    function _column_bonus_right_align($value,$row)    {        
        return "<span style=\"width:100%;text-align:right;display:block;\">".$this->rp($value,'Rp')."</span>";
    }
    function _column_status($value,$row)    {        
        if($value == "00"){
            $v = "SUCESS";
            return "<span style=\"width:100%;text-align:center;display:block;\"><btn class='label label-success'>".$v."</btn></span>";
        }elseif($value == "68"){
            $v = "PENDING";
            return "<span style=\"width:100%;text-align:center;display:block;\"><btn class='label label-warning'>".$v."</btn></span>";
        }elseif($value == "69"){
            $v = "CANCEL/GAGAL";
            return "<span style=\"width:100%;text-align:center;display:block;\"><btn class='label label-info'>".$v."</btn></span>";
        }elseif($value == "67"){
            $v = "NOPAYMENT";
            return "<span style=\"width:100%;text-align:center;display:block;\"><btn class='label label-default'>".$v."</btn></span>";
        }elseif($value == "94"){
            $v = "DOUBLE";
            return "<span style=\"width:100%;text-align:center;display:block;\"><btn class='label label-primary'>".$v."</btn></span>";
        }
        else{
            $v = "ERROR PROVIDER";
            return "<span style=\"width:100%;text-align:center;display:block;\"><btn class='label label-danger'>".$v."</btn></span>";
        }
    }
    public function Rp($value, $row)    {
        return number_format($value,0,",",".");
    }
    public function Fail_status($chg_session = '',$transid = '') {
        $this->mMenuID = "8602";
        $this->form_validation->set_rules('iCheck','Pembatalan Transaksi','required');       
        $this->form_validation->set_rules('desc_trx','Keterangan','required');       
        //var_dump($this);
        if($this->form_validation->run()){
            
            //master_id=5192152&idtrx=3&desc_trx=
            $in_checked         = ($this->input->post('iCheck')=='on');
            $in_descrip         = $this->input->post('desc_trx') ?: '';
            $in_masterid        = $this->input->post('master_id') ?: '';
            $in_transid         = $this->input->post('idtrx') ?: '';
            $in_useridtrx         = $this->input->post('useridtrx') ?: '';

            if(empty($in_checked)){
                $this->messages->add('pembatalan belum Aprove','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            if(empty($in_descrip)){
                $this->messages->add('Keterangan belum disi','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            if(empty($in_masterid)){
                $this->messages->add('masterid belum disi','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            if(empty($in_transid)){
                $this->messages->add('id transaksi belum diisi','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }            
            if(empty($in_useridtrx)){
                $this->messages->add('userid belum diisi','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            if($in_checked != "on"){
                $this->messages->add('pembatalan belum Aprove','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            
            $this->descrip = date('Ymd His')."/CANCEL/".$this->session->userdata('username').'/'.$in_masterid.' - '.$in_descrip;
            //echo $this->descrip;
//                $this->mTitle.= '[2000]Data Master Simpanan';
//                $this->render('simpanan/tambah_simpanan_next');    
            $arr_upd = array(
                'res_code'  => '69',
                'res_sn'    => '',
                'master_id'    => '',
                'desc_trx'  => $this->descrip
            );
            
            $this->Com_model->Upd_com_log_byid($in_transid,$arr_upd);
            $this->Com_model->Del_Comtrans_byid($in_masterid,$in_useridtrx);            
            
            $this->messages->add('Penghapusan/ Pembatalan transaksi berhasil','info');
            redirect(base_url().'admin/report/com/logtrx');
            
         }
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            if(empty($chg_session)){
                $this->messages->add('You dont have permissions for this operation','warning');
                redirect(base_url().'admin/report/com/logtrx');               
            }
            if($this->session->userdata('logtrx_status') == trim($chg_session)){                             
            }else{
                 $this->messages->add('You dont have permissions for this operation','warning');
                 redirect(base_url().'admin/report/com/logtrx'); 
            }
            if(empty($transid)){
                $this->messages->add('Akses Ilegal','warning');
                redirect(base_url().'admin/report/com/logtrx');                
            }
            if (! $this->ion_auth->in_group(array('webmaster', 'admin','manager')) )
            {         
                $this->messages->add('User Anda tidak memiliki kemampuan akses di halaman ini','warmning');
                redirect(base_url().'admin/report/com/logtrx');  

            }
            $res    = $this->Com_model->Logtrx_byid($transid);
            if($res){
                foreach ($res as $v) {
                    $datastatus = $v->res_code;
                }
            }
            if($datastatus == "00"){
                $this->messages->add('Transaksi yang dipilih STATUS BERHASIL,<br> tidak boleh dilakukan perubahan.','info');
                redirect(base_url().'admin/report/com/logtrx');  
            }
            if($datastatus == "69"){
                $this->messages->add('Transaksi yang dipilih STATUS PEMBATALAN,<br> tidak boleh dilakukan perubahan.','info');
                redirect(base_url().'admin/report/com/logtrx');  
            }
            if($datastatus == "68"){
                $msg_status_rescode = "PENDING";
            }else{
                $msg_status_rescode = "ERROR PROVIDER";
            }
            $this->mViewData['data'] = $res;
            $this->mViewData['tid'] = $transid;
            $this->mViewData['message_rescode'] = $msg_status_rescode;
            $this->mMenuID = "8602";
            $this->render('com/f_logtransaction');     
    }    
    public function Fail_status_payment($chg_session = '',$transid = '') {
        $this->mMenuID = "8602";
        $this->form_validation->set_rules('iCheck','Pembatalan Transaksi','required');       
        $this->form_validation->set_rules('desc_trx','Keterangan','required');       
        //var_dump($this);
        if($this->form_validation->run()){
            
            //master_id=5192152&idtrx=3&desc_trx=
            $in_checked         = ($this->input->post('iCheck')=='on');
            $in_descrip         = $this->input->post('desc_trx') ?: '';
            $in_masterid        = $this->input->post('master_id') ?: '';
            $in_transid         = $this->input->post('idtrx') ?: '';
            $in_useridtrx         = $this->input->post('useridtrx') ?: '';

            if(empty($in_checked)){
                $this->messages->add('pembatalan belum Aprove','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            if(empty($in_descrip)){
                $this->messages->add('Keterangan belum disi','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            if(empty($in_masterid)){
                $this->messages->add('masterid belum disi','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            if(empty($in_transid)){
                $this->messages->add('id transaksi belum diisi','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }            
            if(empty($in_useridtrx)){
                $this->messages->add('userid belum diisi','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            if($in_checked != "on"){
                $this->messages->add('pembatalan belum Aprove','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            
            $this->descrip = date('Ymd His')."/CANCEL/".$this->session->userdata('username').'/'.$in_masterid.' - '.$in_descrip;
            //echo $this->descrip;
//                $this->mTitle.= '[2000]Data Master Simpanan';
//                $this->render('simpanan/tambah_simpanan_next');    
            $arr_upd = array(
                'res_code'  => '69',
                'res_sn'    => '',
                'master_id'    => '',
                'desc_trx'  => $this->descrip
            );
            
            $this->Com_model->Upd_pay_log_byid($in_transid,$arr_upd);
            
            $this->Com_model->Del_Comtrans_byid($in_masterid,$in_useridtrx);            
            
            $this->messages->add('Penghapusan/ Pembatalan transaksi berhasil','info');
            redirect(base_url().'admin/report/com/logtrxpayment');
            
         }
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            if(empty($chg_session)){
                $this->messages->add('You dont have permissions for this operation','warning');
                redirect(base_url().'admin/report/com/logtrxpayment');               
            }
            if($this->session->userdata('logtrx_status_payment') == trim($chg_session)){                             
            }else{
                 $this->messages->add('You dont have permissions for this operation','warning');
                 redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            if(empty($transid)){
                $this->messages->add('Akses Ilegal','warning');
                redirect(base_url().'admin/report/com/logtrxpayment');                
            }
            if (! $this->ion_auth->in_group(array('webmaster', 'admin','manager')) )
            {         
                $this->messages->add('User Anda tidak memiliki kemampuan akses di halaman ini','warmning');
                redirect(base_url().'admin/report/com/logtrxpayment');  

            }
            $res    = $this->Com_model->Logtrxpayment_byid($transid);
            if($res){
                foreach ($res as $v) {
                    $datastatus = $v->res_code;
                }
            }
            if($datastatus == "00"){
                $this->messages->add('Transaksi yang dipilih STATUS BERHASIL,<br> tidak boleh dilakukan perubahan.','info');
                redirect(base_url().'admin/report/com/logtrxpayment');  
            }
            if($datastatus == "69"){
                $this->messages->add('Transaksi yang dipilih STATUS PEMBATALAN,<br> tidak boleh dilakukan perubahan.','info');
                redirect(base_url().'admin/report/com/logtrxpayment');  
            }
            if($datastatus == "68"){
                $msg_status_rescode = "PENDING";
            }else{
                $msg_status_rescode = "ERROR PROVIDER";
            }
            $this->mViewData['data'] = $res;
            $this->mViewData['tid'] = $transid;
            $this->mViewData['message_rescode'] = $msg_status_rescode;
            $this->mMenuID = "8602";
            $this->render('com/f_logpayment');     
    }    
    
    public function Chg_status($chg_session = '',$transid = '') {
        $this->mMenuID = "8602";
        $this->form_validation->set_rules('iCheck','Pembatalan Transaksi','required');       
        $this->form_validation->set_rules('desc_trx','Keterangan','required');       
        $this->form_validation->set_rules('sn_text','Serian number','required');       
        $this->form_validation->set_rules('sn_harga','Harga provider','required|numeric');       
        //var_dump($this);
        if($this->form_validation->run()){
            
            //master_id=5192152&idtrx=3&desc_trx=
            $in_checked         = ($this->input->post('iCheck')=='on');
            $in_descrip         = $this->input->post('desc_trx') ?: '';
            $in_masterid        = $this->input->post('master_id') ?: '';
            $in_transid         = $this->input->post('idtrx') ?: '';
            $in_useridtrx       = $this->input->post('useridtrx') ?: '';
            $in_sn              = $this->input->post('sn_text') ?: '';
            $in_hargapos        = $this->input->post('sn_harga') ?: '';
            $in_harga           = str_replace(".", "", $in_hargapos);
            
            if(empty($in_checked)){
                $this->messages->add('pembatalan belum Aprove','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            if(empty($in_descrip)){
                $this->messages->add('Keterangan belum disi','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            if(empty($in_masterid)){
                $this->messages->add('masterid belum disi','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            if(empty($in_transid)){
                $this->messages->add('id transaksi belum diisi','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }            
            if(empty($in_useridtrx)){
                $this->messages->add('userid belum diisi','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            if($in_checked != "on"){
                $this->messages->add('please checklist jika sudah yakin akan melakukan update status','warning');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            
            $this->descrip = date('Ymd His')."/sucess/".strtolower($this->session->userdata('username')).'/'.$in_masterid.'/pending-sucess#'.$in_descrip;
            //echo $this->descrip;
//                $this->mTitle.= '[2000]Data Master Simpanan';
//                $this->render('simpanan/tambah_simpanan_next');    
            $arr_upd = array(
                'res_code'          => '00',
                'res_sn'            => $in_sn,
                'price_fr_mitra'    => $in_harga,
//                'master_id'    => '',
                'desc_trx'          => $this->descrip
            );
            
            $this->Com_model->Upd_com_log_byid($in_transid,$arr_upd);
            //$this->Com_model->Del_Comtrans_byid($in_masterid,$in_useridtrx);            
            
            $this->messages->add('Perubahan sudah status transaksi berhasil','info');
            redirect(base_url().'admin/report/com/logtrx');
            
         }
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            
            if(empty($chg_session)){
                $this->messages->add('You dont have permissions for this operation','warning');
                redirect(base_url().'admin/report/com/logtrx');               
            }
            if($this->session->userdata('logtrx_status') == trim($chg_session)){                             
            }else{
                 $this->messages->add('You dont have permissions for this operation','warning');
                 redirect(base_url().'admin/report/com/logtrx'); 
            }
            if(empty($transid)){
                $this->messages->add('Akses Ilegal','warning');
                redirect(base_url().'admin/report/com/logtrx');                
            }
            if (! $this->ion_auth->in_group(array('webmaster', 'admin','manager')) )
            {         
                $this->messages->add('User Anda tidak memiliki kemampuan akses di halaman ini','warmning');
                redirect(base_url().'admin/report/com/logtrx');  

            }
            $res    = $this->Com_model->Logtrx_byid($transid);
            if($res){
                foreach ($res as $v) {
                    $datastatus = $v->res_code;
                }
            }
            if($datastatus == "00"){
                $this->messages->add('Transaksi yang dipilih STATUS BERHASIL,<br> tidak boleh dilakukan perubahan.','info');
                redirect(base_url().'admin/report/com/logtrx');  
            }
            if($datastatus == "69"){
                $this->messages->add('Transaksi yang dipilih STATUS PEMBATALAN,<br> tidak boleh dilakukan perubahan.','info');
                redirect(base_url().'admin/report/com/logtrx');  
            }
            if($datastatus == "68"){            
                $msg_status_rescode = "PENDING";
            }else{
                $msg_status_rescode = "ERROR PROVIDER";
                $this->messages->add('STATUS ERROR PROVIDER /FAILED / GAGAL TRANSAKSI,<br> tidak bisa dilakukan perubahan.','info');
                redirect(base_url().'admin/report/com/logtrx'); 
            }
            $this->mViewData['data'] = $res;
            $this->mViewData['tid'] = $transid;
            $this->mViewData['message_rescode'] = $msg_status_rescode;
            $this->mMenuID = "8602";
            $this->render('com/f_chgstatus');     
    }
    public function Chg_status_payment($chg_session = '',$transid = '') {
        $this->mMenuID = "8602";
        $this->form_validation->set_rules('iCheck','Pembatalan Transaksi','required');       
        $this->form_validation->set_rules('desc_trx','Keterangan','required');       
        $this->form_validation->set_rules('sn_text','Serian number','required');            
        //var_dump($this);
        if($this->form_validation->run()){
            
            //master_id=5192152&idtrx=3&desc_trx=
            
            //master_id=5436415&idtrx=1&useridtrx=224&sn_text=23428424y9242942947&desc_trx=tesketerangan
            
            $in_checked         = ($this->input->post('iCheck')=='on');
            $in_descrip         = $this->input->post('desc_trx') ?: '';
            $in_masterid        = $this->input->post('master_id') ?: '';
            $in_transid         = $this->input->post('idtrx') ?: '';
            $in_useridtrx       = $this->input->post('useridtrx') ?: '';
            $in_sn              = $this->input->post('sn_text') ?: '';
            $in_hargapos        = $this->input->post('sn_harga') ?: '';
            $in_harga           = str_replace(".", "", $in_hargapos);
            
            if(empty($in_checked)){
                $this->messages->add('pembatalan belum Aprove','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            if(empty($in_descrip)){
                $this->messages->add('Keterangan belum disi','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            if(empty($in_masterid)){
                $this->messages->add('masterid belum disi','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            if(empty($in_transid)){
                $this->messages->add('id transaksi belum diisi','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }            
            if(empty($in_useridtrx)){
                $this->messages->add('userid belum diisi','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            if($in_checked != "on"){
                $this->messages->add('please checklist jika sudah yakin akan melakukan update status','warning');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            
            $this->descrip = date('Ymd His')."/sucess/".strtolower($this->session->userdata('username')).'/'.$in_masterid.'/pending-sucess#'.$in_descrip;
            //echo $this->descrip;
//                $this->mTitle.= '[2000]Data Master Simpanan';
//                $this->render('simpanan/tambah_simpanan_next');    
            $arr_upd = array(
                'res_code'          => '00',
                'res_sn'            => $in_sn,
//                'master_id'    => '',
                'desc_trx'          => $this->descrip
            );
            
            $this->Com_model->Upd_pay_log_byid($in_transid,$arr_upd);
            //$this->Com_model->Del_Comtrans_byid($in_masterid,$in_useridtrx);            
            
            $this->messages->add('Perubahan sudah status transaksi berhasil','info');
            redirect(base_url().'admin/report/com/logtrxpayment');
            
         }
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            
            if(empty($chg_session)){
                $this->messages->add('You dont have permissions for this operation','warning');
                redirect(base_url().'admin/report/com/logtrxpayment');               
            }
            if($this->session->userdata('logtrx_status_payment') == trim($chg_session)){                             
            }else{
                 $this->messages->add('You dont have permissions for this operation','warning');
                 redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            if(empty($transid)){
                $this->messages->add('Akses Ilegal','warning');
                redirect(base_url().'admin/report/com/logtrxpayment');                
            }
            if (! $this->ion_auth->in_group(array('webmaster', 'admin','manager')) )
            {         
                $this->messages->add('User Anda tidak memiliki kemampuan akses di halaman ini','warmning');
                redirect(base_url().'admin/report/com/logtrxpayment');  

            }
            $res    = $this->Com_model->Logtrxpayment_byid($transid);
            if($res){
                foreach ($res as $v) {
                    $datastatus = $v->res_code;
                }
            }
//           echo json_encode($res);
//           return;
            if($datastatus == "00"){
                $this->messages->add('Transaksi yang dipilih STATUS BERHASIL,<br> tidak boleh dilakukan perubahan.','info');
                redirect(base_url().'admin/report/com/logtrxpayment');  
            }
            if($datastatus == "69"){
                $this->messages->add('Transaksi yang dipilih STATUS PEMBATALAN,<br> tidak boleh dilakukan perubahan.','info');
                redirect(base_url().'admin/report/com/logtrxpayment');  
            }
            if($datastatus == "68"){            
                $msg_status_rescode = "PENDING";
            }else{
                $msg_status_rescode = "ERROR PROVIDER";
                $this->messages->add('STATUS ERROR PROVIDER /FAILED / GAGAL TRANSAKSI,<br> tidak bisa dilakukan perubahan.','info');
                redirect(base_url().'admin/report/com/logtrxpayment'); 
            }
            $this->mViewData['data'] = $res;
            $this->mViewData['tid'] = $transid;
            $this->mViewData['message_rescode'] = $msg_status_rescode;
            $this->mMenuID = "8602";
            $this->render('com/f_chgstatus_payment');     
    }
    
    public function PaymentPrint($chg_session = '',$transid = '') {
               
        $getdata = $this->Com_model->Payment_cetak($transid);
        if($getdata):
            foreach ($getdata as $v) {
                $tgl        = $v->dtm_transaksi ?: '';
                $jenis      = $v->nominal ?: '';
                $idbpjs     = $v->NOCUSTOMER ?: '';
                $tagihan    = $v->tagihan ?: '';
                $admin      = $v->total_adm ?: '';
                $userid     = $v->userid ?: '';
                $log_trace  = $v->log_trace ?: '';
                $res_sn  = $v->res_sn ?: '';
            }
            
            if(strtoupper($jenis) == "BPJS"){
                $getname = $this->Com_model->BPJSSESByLogTrace($log_trace);
                if($getname){
                    foreach ($getname as $vn) {
                        $namacus = $vn->cust_name ?: '';
                        $periode = $vn->periode ?: '';
                        $total_person = $vn->total_person ?: '';
                        $total_tagihan = $vn->total_tagihan ?: '';
                    }
                }else{
                    $namacus = 'NULL';
                    $periode = 'NULL';
                    $total_person = 'NULL';
                    $total_tagihan = 'NULL';
                }   
                $data['tanggal_transaksi'] = $tgl;
                $data['idbpjs'] = $idbpjs;
                $data['tagihan'] = $this->Rp($tagihan);
                $data['admin'] = $this->Rp($admin);
                $data['idtrans'] = $log_trace;
                $data['namacus'] = $namacus;
                $data['periode'] = $periode;
                $data['sn'] = $res_sn;
                $data['total_tagihan'] = $this->Rp($total_tagihan);
                $data['agentid'] = $userid;
                $data['total_person'] = $total_person;
                
                $this->load->view('com/Payment_bukti_bayar',$data);   
            }else{
                echo "this page not working for PLN";
            }
        else:
            
        endif;
        

        
    }
    
    public function Agent()	{
        $crud = $this->generate_crud('admin_users');
        $crud->set_theme('datatables');
        $crud->columns('username', 'first_name', 'last_name');
        $crud->fields('username', 'first_name', 'last_name', 'active');
        $this->unset_crud_fields('ip_address', 'last_login','penerimaan','pengeluaran','model_agent','msisdn_payment','pin_payment','no_rekening','nasabah_id','active','email');

        // cannot change Admin User groups once created
        if ($crud->getState()=='list')
        {
                $crud->set_relation_n_n('groups', 'admin_users_groups', 'admin_groups', 'user_id', 'group_id', 'name');
        }

        // only webmaster can reset Admin User password
        if ( $this->ion_auth->in_group(array('webmaster','admin','manager','staff')) )
        {
                 $transid = random_string('alnum', 16);
                 $this->session->unset_userdata('logtrx_status_agent');
                 $this->session->set_userdata('logtrx_status_agent',$transid);
                 $crud->add_action('Tagihan', '', 'admin/report/com/tagihan/'.$transid, 'fa fa-user');
                 $crud->add_action('Riwayat Setoran', '', 'admin/report/com/setor_history_byagent/'.$transid, 'fa fa-user');
        }
        
        // disable direct create / delete Admin User
        $crud->unset_export();
        $crud->unset_print();   
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();
        $crud->unset_read();

        $this->mTitle.= 'Users';
        $this->mMenuID = "8603";
        $this->render_crud();
    }
    function Tagihan($chg_session,$useragen = '') {
        if($this->session->userdata('logtrx_status_agent') == trim($chg_session)){                             
        }else{
             $this->messages->add('You dont have permissions for this operation','warning');
             redirect(base_url().'admin/report/com/agent'); 
        }
        $this->load->model('Admin_user2_model');
        $userid_res = $this->Admin_user2_model->User_by_username($useragen);
        if(!$userid_res){
            $this->messages->add('You dont have permissions for this operation','warning');
             redirect(base_url().'admin/report/com/agent'); 
        }    
        
        $this->tgl_to_t     = date('m/d/Y', strtotime(date('Y-m-d')));
        $this->tgl_fr_t     = date('m/01/Y', strtotime(date('Y-m-d')));
            
        $this->form_validation->set_rules('tgl_fr','tanggal awal','required');       
        $this->form_validation->set_rules('tgl_to','tanggal akhir','required');       

        if($this->form_validation->run()){
            $this->tgl_fr_t     = $this->input->post('tgl_fr') ?: '';
            $this->tgl_to_t     = $this->input->post('tgl_to') ?: '';
            
            if(empty($this->tgl_fr_t) || empty($this->tgl_to_t)) {
                $this->messages->add('parameter invalid','warning');
                redirect(base_url().'admin/report/com/agent');
            }            
           
            $selisih = strtotime($this->tgl_to_t) -  strtotime($this->tgl_fr_t);
            $hari = $selisih/(60*60*24) + 1;
            
            //$this->info_tgl = '<h4>'.date("j-m-Y", strtotime($this->tgl_fr_t)).' s/d '.date("j-m-Y", strtotime($this->tgl_to_t)).' </h4><h3>'.$hari.' Hari</h3>';
            $this->info_tgl = '<div class="inner"><h3>'.date("j F Y", strtotime($this->tgl_fr_t)).' <div class="text-info">'.date("j F Y", strtotime($this->tgl_to_t)).'</div></h3>'
                    . '</div><div class="icon"><i class="ion">'.$hari.' HARI</i></div>
                    </div>';
            
            
            $tgl_fr_gnt =  date("Y-m-d", strtotime($this->tgl_fr_t));
            $tgl_to_gnt =  date("Y-m-d", strtotime($this->tgl_to_t));
            
            $this->tgl_fr_gnt = $tgl_fr_gnt;
            
            $getdata = $this->Com_model->Tgh_mutasi($useragen,$tgl_fr_gnt,$tgl_to_gnt);               
            if($getdata):
                $this->tbl_res  = '';
                foreach ($getdata as $v) {                        
                    $this->tbl_res .= "<tr>"
                                . "<td>".  $this->no."</td>"
                                . "<td>".$v->dtm_transaksi."</td>"
                                . "<td>".$v->KODE_TRANS."</td>"
                                . "<td>".$v->nominal."</td>"
                                . "<td>".$v->product."</td>"
                                . "<td>".  $this->Phone_space($v->nocustomer)."</td>"
                                . "<td align='right'>".  $this->Rp($v->price_bmt,'')."</td>"
                                . "<td align='right'>".  $this->Rp($v->total,'')."</td>"
                                . "<td align='right'>".  $this->Rp($v->profit_agent,'')."</td>"
                                . "</tr>"; 
            
                    if($v->KODE_TRANS == "101"){ //via cash
                        $this->mn_cash_awal   = $this->mn_cash_awal + $v->price_bmt;
                        $this->mn_cash_akhir  = $this->mn_cash_akhir + $v->total;
                        $this->mn_cash_adm    = $this->mn_cash_adm + $v->adm;
                    }elseif($v->KODE_TRANS == "102"){ //via debet tabungan
                        $this->mn_debt_awal   = $this->mn_debt_awal + $v->price_bmt;
                        $this->mn_debt_akhir  = $this->mn_debt_akhir + $v->total;
                        $this->mn_debt_adm    = $this->mn_debt_adm + $v->adm;

                    } 
                    $this->no ++;
                }               
                
                $this->total_transaksi    = $this->mn_cash_akhir + $this->mn_cash_adm + $this->mn_debt_akhir + $this->mn_debt_adm;
                $this->profit_cash        = $this->mn_cash_akhir - $this->mn_cash_awal;
                $this->profit_debt        = $this->mn_debt_akhir - $this->mn_debt_awal;
                
                $this->total_setoran      = $this->mn_cash_awal - $this->profit_debt;
                
                $this->tbl_res .= "<tr class='total'>"
                                . "<td colspan='6' align='left'><strong>TOTAL</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->mn_cash_awal + $this->mn_debt_awal,'')."</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->mn_cash_akhir + $this->mn_debt_akhir,'')."</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->profit_cash + $this->profit_debt,'')."</strong></td>"
                                . "</tr>";
                $this->tbl_res .= "<tr class='total'>"
                                . "<td colspan='8' align='left'><strong>DITAGIHKAN</strong></td>"
                                    . "<td align='right'><strong>".  $this->Rp($this->total_setoran,'')."</strong></td>"
                                . "</tr>";
               $this->tbl_profit = "        
                        <tr>
                          <th style=width:20%>Total Transaksi</th>
                          <td></td>
                          <td></td>
                          <td align='right'><b>".  $this->Rp($this->total_transaksi,'')."</b></td>
                        </tr>
                        <tr>
                        <th rowspan='2'>Profit Agent</th>
                          <td>Cash</td>
                          <td align='right'>".  $this->Rp($this->profit_cash,'')."</td>
                          <td rowspan='2' align='right'><b>".$this->Rp($this->profit_cash + $this->profit_debt,'')."</b></td>
                        </tr>
                        <tr>
                          <td>Debit Tabungan</td>
                          <td align='right'>".  $this->Rp($this->profit_debt,'')."</td>
                        </tr>
                        <tr>
                          <th>Sub Total Setoran</th>
                          <td></td>
                          <td></td>
                          <td align='right'><b>".  $this->Rp($this->total_setoran,'')."</b></td>
                        </tr>
                ";
                
            endif;
//            
//            ---- payament
//            
//            
            $getdatap = $this->Com_model->Tgh_mutasi_pay($useragen,$tgl_fr_gnt,$tgl_to_gnt);  
            if($getdatap):
                $this->no = 1;
                $this->tbl_resp = '';
                foreach ($getdatap as $v) {                        
                    $this->tbl_resp .= "<tr>"
                                . "<td>".  $this->no."</td>"
                                . "<td>".$v->dtm_transaksi."</td>"
                                . "<td>".$v->KODE_TRANS."</td>"
                                . "<td>".$v->nominal."</td>"
                                . "<td>".$v->product."</td>"
                                . "<td>".  $this->Phone_space($v->NOCUSTOMER)."</td>"
                                . "<td align='right'>".  $this->Rp($v->tagihan,'')."</td>"
                                . "<td align='right'>".  $this->Rp($v->total_adm,'')."</td>"
                                . "<td align='right'>".  $this->Rp($v->price_agent,'')."</td>"
                                . "</tr>"; 
            
                    if($v->KODE_TRANS == "101"){ //via cash
                        $this->mn_cash_tagihan   = $this->mn_cash_tagihan + $v->tagihan;
                        $this->mn_cash_tagihan_adm  = $this->mn_cash_tagihan_adm + $v->total_adm;
                        $this->mn_cash_tagihan_basil    = $this->mn_cash_tagihan_basil + $v->price_agent;
                    }elseif($v->KODE_TRANS == "102"){ //via debet tabungan
                        $this->mn_debt_tagihan   = $this->mn_debt_tagihan + $v->tagihan;
                        $this->mn_debt_tagihan_adm  = $this->mn_debt_tagihan_adm + $v->total_adm;
                        $this->mn_debt_tagihan_basil    = $this->mn_debt_tagihan_basil + $v->price_agent;
                    } 
                    $this->no ++;
                }               
                
                $this->total_transaksip    = $this->mn_cash_tagihan + $this->mn_cash_tagihan_adm + $this->mn_debt_tagihan + $this->mn_debt_tagihan_adm;
                $this->profit_cashp        = $this->mn_cash_tagihan_basil;
                $this->profit_debtp        = $this->mn_debt_tagihan_basil;
                
                $this->setoranp_cash      = $this->mn_cash_tagihan + $this->mn_cash_tagihan_adm;
                $this->setoranp_debt      = $this->mn_debt_tagihan + $this->mn_debt_tagihan_adm;
                
                $this->total_setoranp     = $this->setoranp_cash - $this->setoranp_debt - $this->profit_cashp - $this->profit_debtp;
                $this->tbl_resp .= "<tr class='total'>"
                                . "<td colspan='6' align='left'><strong>TOTAL</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->mn_cash_tagihan + $this->mn_debt_tagihan,'')."</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->mn_cash_tagihan_adm + $this->mn_debt_tagihan_adm,'')."</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->mn_cash_tagihan_basil + $this->mn_debt_tagihan_basil,'')."</strong></td>"
                                . "</tr>";
                $this->tbl_resp .= "<tr class='total'>"
                                . "<td colspan='8' align='left'><strong>DITAGIHKAN</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->total_setoranp,'')."</strong></td>"
                                . "</tr>";
                
               $this->tbl_profitp = "        
                        <tr>
                          <th style=width:20%>Total Transaksi</th>
                          <td></td>
                          <td></td>
                          <td align='right'><b>".  $this->Rp($this->total_transaksip,'')."</b></td>
                        </tr>
                        <tr>
                        <th rowspan='2'>Profit Admin Agent</th>
                          <td>Cash</td>
                          <td align='right'>".  $this->Rp($this->profit_cashp,'')."</td>
                          <td rowspan='2' align='right'><b>".$this->Rp($this->profit_cashp + $this->profit_debtp,'')."</b></td>
                        </tr>
                        <tr>
                          <td>Debit Tabungan</td>
                          <td align='right'>".  $this->Rp($this->profit_debtp,'')."</td>
                        </tr>
                        <tr>
                          <th>Sub Total Setoran</th>
                          <td></td>
                          <td></td>
                          <td align='right'><b>".  $this->Rp($this->total_setoranp,'')."</b></td>
                        </tr>
                ";
                
            endif;
            //get data sebelum tanggal tagihan
            
            $getdatap_bfor = $this->Com_model->Tgh_mutasi_pay_bfor($useragen,$tgl_fr_gnt); 
            if($getdatap_bfor):
                foreach ($getdatap_bfor as $v) {                                                     
                    if($v->KODE_TRANS == "101"){ //via cash
                        $this->mn_cash_tagihan_bfr   = $this->mn_cash_tagihan_bfr + $v->tagihan;
                        $this->mn_cash_tagihan_adm_bfr  = $this->mn_cash_tagihan_adm_bfr + $v->total_adm;
                        $this->mn_cash_tagihan_basil_bfr    = $this->mn_cash_tagihan_basil_bfr + $v->price_agent;
                    }elseif($v->KODE_TRANS == "102"){ //via debet tabungan
                        $this->mn_debt_tagihan_bfr   = $this->mn_debt_tagihan_bfr + $v->tagihan;
                        $this->mn_debt_tagihan_adm_bfr  = $this->mn_debt_tagihan_adm_bfr + $v->total_adm;
                        $this->mn_debt_tagihan_basil_bfr    = $this->mn_debt_tagihan_basil_bfr + $v->price_agent;
                    } 
                }               
                
//                $total_transaksip_bfr    = $this->mn_cash_akhir_bfr + $this->mn_cash_adm_bfr + $this->mn_debt_akhir_bfr + $this->mn_debt_adm_bfr;
//                $profitp_cash_bfr        = $this->mn_cash_akhir_bfr - $this->mn_cash_awal_bfr;
//                $profitp_debt_bfr        = $this->mn_debt_akhir_bfr - $this->mn_debt_awal_bfr;
//                
//                $this->total_setoranp_bfr      = $this->mn_cash_awal_bfr - $profitp_debt_bfr; 
              
                
                //
                $total_transaksip_bfr    = $this->mn_cash_tagihan_bfr + $this->mn_cash_tagihan_adm_bfr + $this->mn_debt_tagihan_bfr + $this->mn_debt_tagihan_adm_bfr;
                $profitp_cash_bfr        = $this->mn_cash_tagihan_basil_bfr;
                $profitp_debt_bfr        = $this->mn_debt_tagihan_basil_bfr;
                
                $setoranp_cash_bfr      = $this->mn_cash_tagihan_bfr + $this->mn_cash_tagihan_adm_bfr;
                $setoranp_debt_bfr      = $this->mn_debt_tagihan_bfr + $this->mn_debt_tagihan_adm_bfr;
                
                $this->total_setoranp_bfr     = $setoranp_cash_bfr - $setoranp_debt_bfr;
                
            endif;
             
            
            
            //get data sebelum tanggal tagihan
            $getdata_bfor = $this->Com_model->Tgh_mutasi_bfor($useragen,$tgl_fr_gnt); 
            if($getdata_bfor):
                foreach ($getdata_bfor as $v) {                                    
                    if($v->KODE_TRANS == "101"){ //via cash
                        $this->mn_cash_awal_bfr   = $this->mn_cash_awal_bfr + $v->price_bmt;
                        $this->mn_cash_akhir_bfr  = $this->mn_cash_akhir_bfr + $v->total;
                        $this->mn_cash_adm_bfr    = $this->mn_cash_adm_bfr + $v->adm;
                    }elseif($v->KODE_TRANS == "102"){ //via debet tabungan
                        $this->mn_debt_awal_bfr   = $this->mn_debt_awal_bfr + $v->price_bmt;
                        $this->mn_debt_akhir_bfr  = $this->mn_debt_akhir_bfr + $v->total;
                        $this->mn_debt_adm_bfr    = $this->mn_debt_adm_bfr + $v->adm;

                    } 
                }               
                
                $total_transaksi_bfr    = $this->mn_cash_akhir_bfr + $this->mn_cash_adm_bfr + $this->mn_debt_akhir_bfr + $this->mn_debt_adm_bfr;
                $profit_cash_bfr        = $this->mn_cash_akhir_bfr - $this->mn_cash_awal_bfr;
                $profit_debt_bfr        = $this->mn_debt_akhir_bfr - $this->mn_debt_awal_bfr;
                
                $this->total_setoran_bfr      = $this->mn_cash_awal_bfr - $profit_debt_bfr; 
              
            endif;
            
            $getdata_bdtm = $this->Com_model->Tgh_before_by_now($useragen,$tgl_fr_gnt,$tgl_to_gnt);
            if($getdata_bdtm):
                $no_now = 1;
//                $this->tbl_bayarnow = "";
                $total_setor = 0;
                $total_basil = 0;
                $this->tbl_bayarnow = "";
                foreach ($getdata_bdtm as $v) {
                    $this->jml_setoran_bfr_bnow    = $v->SETORAN;
                    $this->jml_basil_bfr_bnow      = $v->BASIL;
                    $jam_now                       = $v->JAM;
                    
                    $total_setor += $this->jml_setoran_bfr_bnow; 
                    $total_basil += $this->jml_basil_bfr_bnow; 
                    $this->tbl_bayarnow .= "        
                        <tr>
                          <td>".$no_now."</td>
                          <td>".$jam_now."</td>
                          <td align='right'><b>".  $this->Rp($this->jml_setoran_bfr_bnow,'')."</b></td>
                          <td align='right'><b>".  $this->Rp($this->jml_basil_bfr_bnow,'')."</b></td>
                        </tr>
                ";
                     $no_now ++;
                }
                $this->tbl_bayarnow .= "        
                        <tr class='total'>
                          <td colspan='2'>Total</td>
                          <td align='right'><b>".  $this->Rp($total_setor,'')."</b></td>
                          <td align='right'><b>".  $this->Rp($total_basil,'')."</b></td>
                        </tr>
                ";
                
            endif;
            unset($getdata_lastmonth);
            $getdata_lastmonth = $this->Com_model->Tgh_history_last_month($useragen);
            if($getdata_lastmonth):
                $no_now = 1;
//                $this->tbl_bayarnow = "";
                $total_setor = 0;
                $total_basil = 0;
                $this->tbl_history_byr_last_month = "";
                foreach ($getdata_lastmonth as $v) {
                    $this->jml_setoran_bfr_bnow    = $v->SETORAN;
                    $this->jml_basil_bfr_bnow      = $v->BASIL;
                    $jam_now                       = $v->JAM;
                    $ket_lastmont                  = $v->KETERANGAN;
                    
                    $total_setor += $this->jml_setoran_bfr_bnow; 
                    $total_basil += $this->jml_basil_bfr_bnow; 
                    $this->tbl_history_byr_last_month .= "        
                        <tr>
                          <td>".$no_now."</td>
                          <td>".$jam_now."</td>
                          <td align='right'><b>".  $this->Rp($this->jml_setoran_bfr_bnow,'')."</b></td>
                          <td align='right'><b>".  $this->Rp($this->jml_basil_bfr_bnow,'')."</b></td>
                          <td>". htmlentities($ket_lastmont)."</td>
                        </tr>
                ";
                     $no_now ++;
                }
                $this->tbl_history_byr_last_month .= "        
                        <tr class='total'>
                          <td colspan='2'>Total</td>
                          <td align='right'><b>".  $this->Rp($total_setor,'')."</b></td>
                          <td align='right'><b>".  $this->Rp($total_basil,'')."</b></td>
                          <td></td>
                        </tr>
                ";
                
            endif;
            
            unset($getdata_bfor);
            $getdata_bfor = $this->Com_model->Tgh_before_by_dtm($useragen,$tgl_fr_gnt);
            if($getdata_bfor):
                foreach ($getdata_bfor as $v) {
                    $this->jml_setoran_bfr_bydtm    = $v->SETORAN;
                    $this->jml_adm_bfr_bydtm        = $v->ADM;
                }                
            endif;
            
        }
        //
        $this->total_setoran_bfr        = round($this->total_setoran_bfr, 0, PHP_ROUND_HALF_DOWN);
        $this->total_setoranp_bfr        = round($this->total_setoranp_bfr, 0, PHP_ROUND_HALF_DOWN);
        
        $this->jml_setoran_bfr_bydtm    = round($this->jml_setoran_bfr_bydtm, 0, PHP_ROUND_HALF_DOWN);
        $this->jml_basil_bfr_bydtm      = round($this->jml_basil_bfr_bydtm, 0, PHP_ROUND_HALF_DOWN);
        $this->jml_setoran_bfr_bydtm    = $this->jml_setoran_bfr_bydtm + $this->jml_basil_bfr_bydtm;
        
        $this->total_setoran_bfr = $this->total_setoran_bfr + $this->total_setoranp_bfr;
                
        if($this->total_setoran_bfr == $this->jml_setoran_bfr_bydtm){
            $sisa_setoran_tgl_bfr       = 0;
            $status_setoran_tgl_bfr     = "LUNAS";
            $desc_setoran_tgl_bfr       = '<font color="black"><span style="font-size:60%;">STATUS : </span></font><font color="blue"><span style="font-size:60%;">'.$status_setoran_tgl_bfr.' </span></font><font color="black" class="pull-right">Rp '.  $this->Rp($sisa_setoran_tgl_bfr,'Rp').'</font>'; 
        }elseif ($this->total_setoran_bfr > $this->jml_setoran_bfr_bydtm) {
            $sisa_setoran_tgl_bfr       = $this->total_setoran_bfr - $this->jml_setoran_bfr_bydtm;
            $status_setoran_tgl_bfr     = "KURANG BAYAR";
            $desc_setoran_tgl_bfr       = '<font color="black"><span style="font-size:60%;">STATUS : </span></font><font color="red"><span style="font-size:60%;"> '.$status_setoran_tgl_bfr.' </span></font><font color="black" class="pull-right">Rp '.  $this->Rp($sisa_setoran_tgl_bfr,'Rp').'</font>'; 
        }else{
            $sisa_setoran_tgl_bfr       = $this->jml_setoran_bfr_bydtm - $this->total_setoran_bfr;
            $status_setoran_tgl_bfr     = "LEBIH BAYAR";
            $desc_setoran_tgl_bfr       = '<font color="black"><span style="font-size:60%;">STATUS : </span></font><font color="green"><span style="font-size:60%;"> '.$status_setoran_tgl_bfr.' </span></font><font color="black" class="pull-right">Rp '.  $this->Rp($sisa_setoran_tgl_bfr,'Rp').'</font>'; 
        }
        //count total setoran
        
        //total transaksi
        
        
        $this->grand_total_transaksi    = $this->total_transaksip + $this->total_transaksi;
        //total bagi hasil denganagen
        $this->grand_total_basil        = $this->profit_cashp + $this->profit_cash + $this->profit_debtp + $this->profit_debt;
        //total jumlah setoran
        $this->grand_total_setoran      = $this->total_setoranp + $this->total_setoran;
        
        $this->session->unset_userdata($chg_session.'_data');
        $this->session->unset_userdata($chg_session.'_datap');
        $this->session->set_userdata($chg_session.'_data',$this->tbl_res);        
        $this->session->set_userdata($chg_session.'_datap',$this->tbl_resp);        
     
        $this->session->set_userdata($chg_session.'_datatgl_from',date("d-m-Y", strtotime($this->tgl_fr_t)));        
        $this->session->set_userdata($chg_session.'_datatgl_to',date("d-m-Y", strtotime($this->tgl_to_t)));        
        $this->session->set_userdata($chg_session.'_datapuser',$userid_res);         
              
        $this->mViewData['data']                = $chg_session;
        $this->mViewData['grand_gtt']           = $this->Rp($this->grand_total_transaksi,'');
        $this->mViewData['grand_gtb']           = $this->Rp($this->grand_total_basil,'Rp');
        $this->mViewData['grand_gts']           = $this->Rp($this->grand_total_setoran,'Rp');
        $this->mViewData['data_profit']         = $this->tbl_profit;
        $this->mViewData['data_profitp']        = $this->tbl_profitp;
        $this->mViewData['data_now']            = $this->tbl_bayarnow;
        $this->mViewData['data_last_month']     = $this->tbl_history_byr_last_month;
        $this->mViewData['tgl']                 = $this->info_tgl;
        $this->mViewData['tgl_fr_t']            = $this->tgl_fr_t;
        $this->mViewData['tgl_to_t']            = $this->tgl_to_t;
        $this->mViewData['datauser']            = $userid_res;
        $this->mViewData['tgl_before']          = "<b>".date("j  F  Y", strtotime($this->tgl_fr_gnt))."</b>";
        $this->mViewData['piutang']             = $this->Rp($this->total_setoran_bfr,'');
        $this->mViewData['terbayar']            = $this->Rp($this->jml_setoran_bfr_bydtm,'');
        $this->mViewData['statussisa_tagihan']  = $desc_setoran_tgl_bfr;
        $this->mMenuID                          = "8603";
        $this->render('com/f_tagihan');         
    }
    
    public function Setor_history_byagent($chg_session,$useragen = '') {
        
        if($this->session->userdata('logtrx_status_agent') == trim($chg_session)){                             
        }else{
             $this->messages->add('You dont have permissions for this operation','warning');
             redirect(base_url().'admin/report/com/agent'); 
        }     
        
        $this->load->model('Admin_user2_model');
        $userid_res = $this->Admin_user2_model->User_by_username($useragen);
        if(!$userid_res){
            $this->messages->add('You dont have permissions for this operation','warning');
             redirect(base_url().'admin/report/com/agent'); 
        }   

        $crud = $this->generate_crud('com_setoran'); 
        
        //$crud->set_theme('datatables');        
        $crud->columns('TGL_TRANS','POKOK','ADM','USERID','KODE_KOLEKTOR','KODE_KANTOR','KETERANGAN');
//        $crud->display_as('dtm','Tanggal');
//        $crud->display_as('res_code','STATUS');
//        $crud->display_as('price_stok','Harga BMT');
//        $crud->display_as('price_selling','Harga Jual Akhir');
//        $crud->display_as('msisdn','NomorID');
//        $crud->display_as('res_message','S/N');
        $crud->display_as('ADM','BAGI HASIL');
            $crud->callback_column('POKOK',array($this,'_column_bonus_right_align'));
            $crud->callback_column('ADM',array($this,'_column_bonus_right_align'));
//        $crud->add_action('Remove Transaction', '', base_url().'admin/report/com/fail_status/'.$transid.'/','ui-button-icon-primary ui-icon ui-icon-cancel');   
//        $crud->add_action('Edit', '', base_url().'admin/report/com/chg_status/'.$transid.'/','ui-button-icon-primary ui-icon ui-icon-pencil');   
//        
        $crud->where('USERID', $useragen);
        $crud->unset_columns('JAM');
        $crud->unset_fields('JAM');
        $crud->callback_column('price_selling',array($this,'_column_bonus_right_align'));
        $crud->callback_column('res_code',array($this,'_column_status'));
        //
        $this->unset_crud_fields('NOCUSTOMER','COMTYPE','NO_REKENING','SANDI_TRANS','JAM');
        
//        $crud->unset_export();
//        $crud->unset_print();
        //$crud->unset_add();
        if(strtolower($this->session->userdata('username')) == "devapp"):
        else:
        $crud->unset_add();      
        $crud->unset_edit();
        endif;
        //$crud->unset_edit();
        $crud->unset_delete();
        $crud->set_subject('History Setoran');
	$this->mMenuID = "8603";                
        $this->render_crud();
    }
    public function History_transaksi($chg_session,$useragen = '') {
        if($this->session->userdata($chg_session.'_data')){                             
        }else{
             $this->messages->add('You dont have permissions for this operation','warning');
             redirect(base_url().'admin/report/com/agent'); 
        }
        if($this->session->userdata($chg_session.'_datap')){                             
        }else{
             $this->messages->add('You dont have permissions for this operation','warning');
             redirect(base_url().'admin/report/com/agent'); 
        }
        $this->load->model('Admin_user2_model');
        $userid_res = $this->Admin_user2_model->User_by_username($useragen);
        if(!$userid_res){
            $this->messages->add('You dont have permissions for this operation','warning');
             redirect(base_url().'admin/report/com/agent'); 
        }
        $data['table']                = $this->session->userdata($chg_session.'_data');
        $data['tablep']                = $this->session->userdata($chg_session.'_datap');
        $data['tgl_from']               = $this->session->userdata($chg_session.'_datatgl_from');        
        $data['tgl_to']               = $this->session->userdata($chg_session.'_datatgl_to');        
        $data['datauser']               = $this->session->userdata($chg_session.'_datapuser');        
        

        $this->load->view('com/history_transaksi',$data);
    }
    
    function Setoran() {
        //var_dump($this->session->userdata());
        
        if($this->input->method() != 'post'){
            redirect('admin/report/com/agent');
        }
        
        $post_tanggal       = $this->input->post('tanggal') ?: '';
        $post_nominal       = $this->input->post('nominal_setoran') ?: '0' ;
        $post_basilsetoran       = $this->input->post('basilsetoran') ?: '0' ;
        $post_userid        = $this->input->post('userid') ?: '';
        $post_username      = $this->input->post('username') ?: '';
        $post_keterangan    = $this->input->post('keterangan') ?: '';
        
        if(empty($post_tanggal)){    
            $this->messages->add('Tanggal masih kosong', 'warning');     
            redirect('admin/report/com/agent');
        }
        if(empty($post_nominal)){    
            $this->messages->add('Jumlah setoran belum diisi', 'warning');     
            redirect('admin/report/com/agent');
        }
        if(empty($post_userid)){    
            $this->messages->add('Userid masih salah', 'warning');     
            redirect('admin/report/com/agent');
        }
        if(empty($post_username)){    
            $this->messages->add('Userid masih salah', 'warning');     
            redirect('admin/report/com/agent');
        }
        $post_tanggal       = date('Y-m-d', strtotime($post_tanggal));
        $post_nominal       = $this->convert_to_number($post_nominal);
        $post_basilsetoran  = $this->convert_to_number($post_basilsetoran);
        
        if(trim($post_nominal) == 0){
            $this->messages->add('Jumlah setoran tidak boleh 0', 'warning');     
            redirect('admin/report/com/agent');
        }
        $create_ket = '[Oleh '.$this->session->userdata('user_id').' '.$this->session->userdata('username').'] [setoran '.$post_userid.'-'.$post_username.' Rp'.$post_nominal.'] #'.$post_keterangan;
        $data = array(
            'TGL_TRANS' => $post_tanggal,
            'POKOK' => $post_nominal,
            'ADM' => $post_basilsetoran,
            'USERID' => $post_userid,
            'VERIFIKASI' => '1',
            'KUITANSI' => '',
            'KODE_KOLEKTOR' => $this->session->userdata('user_id'),
            'KODE_KANTOR' => '35',
            'JAM' => Date('Y-m-d H:i:s'),
            'IP_ADD' => $this->input->ip_address(),
            'KETERANGAN' => $create_ket
        ); 
        $res_setor = $this->Tab_model->Ins_Tbl('com_setoran',$data);
        if($res_setor){
            $this->messages->add('Sukses', 'info');     
            redirect('admin/report/com/agent');
            return;
        }
        $this->messages->add('Setoran Gagal System', 'error');     
        redirect('admin/report/com/agent');
        
    }
    function convert_to_number($rupiah)
    {
            //return intval(preg_replace(/[^0-9]/, '', $rupiah));
            return intval(preg_replace('/[^0-9]/', '', $rupiah));

    }
    
    function Phone_space($phone = '') {
        $phone = str_replace(' ', '', $phone);
        $jumlah_sensor=4;
        $setelah_angka_ke=4;

        //ambil 4 angka di tengah yan akan disensor
        $censored = mb_substr($phone, $setelah_angka_ke, $jumlah_sensor);
        //pecah kelompok angka pertama dan terakhir
        $phone2=explode($censored,$phone);

        //gabung angka perama dan terakhir dengan angka tengah yang telah di sensor
        $phone_new=$phone2[0]." ".$censored." ".$phone2[1];

        //tampilkan
        return $phone_new;
    }
    public function _callback_webpage_url($value, $row)
    {
        return "<a href='".site_url('admin/sub_webpages/'.$row->id)."'>$value</a>";
    }
    function ReportTrx() {
        
        $this->tgl_to_t     = date('m/d/Y', strtotime(date('Y-m-d')));
        $this->tgl_fr_t     = date('m/01/Y', strtotime(date('Y-m-d')));
            
        $this->form_validation->set_rules('tgl_fr','tanggal awal','required');       
        $this->form_validation->set_rules('tgl_to','tanggal akhir','required');       

        if($this->form_validation->run()){            
            $this->tgl_fr_t     = $this->input->post('tgl_fr') ?: '';
            $this->tgl_to_t     = $this->input->post('tgl_to') ?: '';
            $this->produklist     = $this->input->post('produklist') ?: '';
            
            if(empty($this->tgl_fr_t) || empty($this->tgl_to_t)) {
                $this->messages->add('parameter invalid','warning');
                redirect(base_url().'admin/report/com/reporttrx');
            }            
           
            
            
            $tgl_fr_gnt =  date("Y-m-d", strtotime($this->tgl_fr_t));
            $tgl_to_gnt =  date("Y-m-d", strtotime($this->tgl_to_t));
            
            $this->tgl_fr_gnt = $tgl_fr_gnt;
//            
//            ---- payament
//            
//            
            $getdatap = $this->Com_model->Tgh_mutasi_pay_all($tgl_fr_gnt,$tgl_to_gnt, $this->produklist);  
            if($getdatap):
                $this->no = 1;
                unset($this->tbl_resp_all);
                foreach ($getdatap as $v) {                        
                    $this->tbl_resp_all .= "<tr>"
                                . "<td>".  $this->no."</td>"
                                . "<td>".$v->dtm_transaksi."</td>"
                                . "<td>".$v->KODE_TRANS."</td>"
                                . "<td>".$v->nominal."</td>"
                                . "<td>".$v->product."</td>"
                                . "<td>".$v->userid."</td>"
                                . "<td>".  $this->Phone_space($v->NOCUSTOMER)."</td>"
                                . "<td align='right'>".  $this->Rp($v->tagihan,'')."</td>"
                                . "<td align='right'>".  $this->Rp($v->price_provider,'')."</td>"
                                . "<td align='right'>".  $this->Rp($v->price_indosis,'')."</td>"
                                . "<td align='right'>".  $this->Rp($v->price_bmt,'')."</td>"
                                . "<td align='right'>".  $this->Rp($v->price_agent,'')."</td>"
                                . "<td align='right'>".  $this->Rp($v->total_adm,'')."</td>"
                                . "</tr>"; 
                    /*
                    if($v->KODE_TRANS == "101"){ //via cash
                        $this->mn_cash_tagihan   = $this->mn_cash_tagihan + $v->tagihan;
                        $this->mn_cash_tagihan_adm  = $this->mn_cash_tagihan_adm + $v->total_adm;
                        $this->mn_cash_tagihan_basil    = $this->mn_cash_tagihan_basil + $v->price_agent;
                    }elseif($v->KODE_TRANS == "102"){ //via debet tabungan
                        $this->mn_debt_tagihan   = $this->mn_debt_tagihan + $v->tagihan;
                        $this->mn_debt_tagihan_adm  = $this->mn_debt_tagihan_adm + $v->total_adm;
                        $this->mn_debt_tagihan_basil    = $this->mn_debt_tagihan_basil + $v->price_agent;
                    } 
                     
                     */
                    $this->mtagihan = $this->mtagihan + $v->tagihan;
                    $this->mprov    = $this->mprov + $v->price_provider;
                    $this->mind     = $this->mind + $v->price_indosis;
                    $this->mbmt     = $this->mbmt + $v->price_bmt;
                    $this->magen    = $this->magen + $v->price_agent;
                    $this->mtagen   = $this->mtagen + $v->total_adm;
                    $this->no ++;
                }               
                
                $this->total_transaksip    = $this->mn_cash_tagihan + $this->mn_cash_tagihan_adm + $this->mn_debt_tagihan + $this->mn_debt_tagihan_adm;
                $this->profit_cashp        = $this->mn_cash_tagihan_basil;
                $this->profit_debtp        = $this->mn_debt_tagihan_basil;
                
                $this->setoranp_cash      = $this->mn_cash_tagihan + $this->mn_cash_tagihan_adm;
                $this->setoranp_debt      = $this->mn_debt_tagihan + $this->mn_debt_tagihan_adm;
                
                $this->total_setoranp     = $this->setoranp_cash - $this->setoranp_debt - $this->profit_cashp - $this->profit_debtp;
                $this->tbl_resp_all .= "<tr class='total'>"
                                . "<td colspan='7' align='left'><strong>TOTAL</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->mtagihan)."</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->mprov)."</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->mind)."</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->mbmt)."</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->magen)."</strong></td>"
                                . "<td align='right'><strong>".  $this->Rp($this->mtagen)."</strong></td>"

                                . "</tr>";                
                
            endif;
            //get data sebelum tanggal tagihan
            $chg_session = random_string('alnum', 16);
            
            $this->session->set_userdata($chg_session.'_datap',$this->tbl_resp_all);       
     
            $this->session->set_userdata($chg_session.'_datatgl_from',date("d-m-Y", strtotime($this->tgl_fr_t)));        
            $this->session->set_userdata($chg_session.'_datatgl_to',date("d-m-Y", strtotime($this->tgl_to_t)));    
            
           // echo "<a href='".base_url()."/com/History_transaksi_pay_all/".$chg_session."' target=\"_blank\">";
            
            echo "<script>window.open('".base_url()."admin/report/com/History_transaksi_pay_all/".$chg_session."','_blank')</script>";
        }
        //
       
        //count total setoran
        $this->mViewData['tgl']                 = $this->info_tgl;
        $this->mViewData['tgl_fr_t']            = $this->tgl_fr_t;
        $this->mViewData['tgl_to_t']            = $this->tgl_to_t;
      
        $this->mMenuID                          = "8605";
        $this->render('com/f_reportTrx');         
    }    
    public function History_transaksi_pay_all($chg_session) {
        if($this->session->userdata($chg_session.'_datap')){                             
        }else{
             $this->messages->add('You dont have permissions for this operation','warning');
             redirect(base_url().'admin/report/com/agent'); 
        }
        $data['tablep']                = $this->session->userdata($chg_session.'_datap');
        $data['tgl_from']               = $this->session->userdata($chg_session.'_datatgl_from');        
        $data['tgl_to']               = $this->session->userdata($chg_session.'_datatgl_to');            
        

        $this->load->view('com/history_transaksi_all_pay',$data);
    }    
}
