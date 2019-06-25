<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Recycle
 *
 * @author edisite
 */
class Recycle extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('form_builder');
    }
    
    public function Index($datefil = '') {

        //$datefil = $this->input->get('datefilter') ?: '';
        if(empty($datefil)){
            $dtfrom     = date('Y-m-d');
            $dtto       = date('Y-m-d');
            $datefil    = $dtfrom.'_'.$dtto;
            $datefil_par    = $dtfrom.' sd '.$dtto;
        }else{
            $par        = explode('_', $datefil);
            $dtfrom     = $par[0] ?: date('Y-m-d');
            $dtto       = $par[1] ?: date('Y-m-d');
            $datefil    = trim($dtfrom).'_'.trim($dtto);
            $datefil_par    = trim($dtfrom).' sd '.trim($dtto);
        }
        
        //echo $datefil;
        $crud = $this->generate_crud('recycle_bin_transaksi_master');
        $crud->set_model('RecycleTrans_model');
        $crud->set_theme('datatables');
        $crud->columns('atid','waktu','TGL_TRANS', 
	'KODE_JURNAL', 
	'TRANS_ID', 
	'MASTER_ID', 
        'KODE_PERK',
        'DEBET',
        'KREDIT',
	'URAIAN');
        
//        $this->db->where('NO_REKENING',$norekening);
        $this->db->where('(a.TGL_TRANS >= "'.date(trim($dtfrom)).'"');
        $this->db->where('a.TGL_TRANS <= "'.date($dtto).'")');
////        $this->db->or_where('MY_KODE_TRANS','100');
//        $this->db->where('KODE_TRANS','201');
        
        $crud->display_as('atid', 'ID BACKUP');
        $crud->display_as('waktu', 'TGL DELETE TRANS');
        $crud->display_as('KODE_JURNAL', 'JURNAL');
//        $crud->display_as('DESTACCNO', 'NO.REK');
//        $crud->display_as('DESTNAME', 'NAME');
//        $crud->display_as('nama_koperasi', 'Name Koperasi');
//        $crud->display_as('alamat', 'Address Office');
//        $crud->display_as('DTMTRANS', 'DATE');
//        $crud->display_as('TABTRANS_ID', 'TRANSID');
        
       // $this->unset_crud_fields('TRANSACTION_ID', 'apikeyid');
        
        $crud->callback_column('POKOK',array($this,'rp'));
        $crud->callback_column('ADM',array($this,'rp'));
        // only webmaster can reset Admin User password

        $crud->add_action('Restore', 'Restore', 'Restore','ui-icon-plus',array($this,'mutasi_fwlogdetail'));
         
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_export();
        $crud->unset_read();
        $crud->unset_print();
        $crud->unset_edit();
//        $this->mPageTitle .= 'Mutasi Transaksi Koperasi';
        $crud_obj_name = strtolower(get_class($this->mCrud));
        if ($crud_obj_name==='grocery_crud')
        {
                $this->mCrud->unset_fields($this->mCrudUnsetFields);	
        }

        // render CRUD
        $crud_data = $this->mCrud->render();

        // append scripts
        $this->add_stylesheet($crud_data->css_files, FALSE);
        $this->add_script($crud_data->js_files, TRUE, 'head');

        // display view
        $this->mViewData['crud_output'] = $crud_data->output;
        $this->mViewData['tgl'] = $datefil_par;
        $this->mViewData['pathe'] = "admin/report/recycle/index";
        $this->render('report/crud_tgl');
    }
    public function mutasi_fwlogdetail($primary_key , $row)
    {
        //var_dump($row);
//      if($row->MY_KODE_TRANS == "100" || $row->MY_KODE_TRANS == "200"){
//          return "#";
//      }
      return "report/recycle/TransLogDetail/".$row->MASTER_ID."/".$row->MODUL_ID_SOURCE;
    }
    public function Tgl() {
        $dirpathye = $this->input->get('pathye') ?: '';
        $datefil = $this->input->get('datefilter') ?: '';
        if(empty($datefil)){
            $dtfrom = date('Y-m-d');
            $dtto = date('Y-m-d');
            $datefil = trim($dtfrom).'_'.trim($dtto);
        }else{
            $par        = explode('sd', $datefil);
            $dtfrom     = $par[0] ?: date('Y-m-d');
            $dtto       = $par[1] ?: date('Y-m-d');
            $datefil    = trim($dtfrom).'_'.trim($dtto);
        }
        $url = base_url().$dirpathye.'/'.$datefil;
        //echo $url;
        redirect($url);
    }
    function Rp($value)    {
        if(is_numeric($value)){
            return number_format($value,0,",",".");
        }
        return $value;
    }
    public function TransLogDetail($getrans_id = '',$getmodul = '') {
        $this->transid  = $getrans_id;
        $this->modul    = $getmodul;
        
        $getdata_master = $this->Tab_model->Recycle_master_detail_by_transid($this->transid);
        if($getdata_master){
            foreach ($getdata_master as $v) {
                $getjurnal = $v->KODE_JURNAL ?: '';
                $getid_source = $v->TRANS_ID_SOURCE ?: '';
            }
            if($this->modul == $getjurnal){            
            }else{
                echo "data kode_jurnal tidak sama";
                return;
            }
            
            if($this->modul == "TAB"){
//                1. insert tabtrans
//                2. insert master 
//                3. insert detail
//                4. update Saldo
//                
                $this->Tab_model->Recycle_tabtrans_insert($getid_source);
                $this->Tab_model->Recycle_master_insert($this->transid);
                $this->Tab_model->Recycle_detail_insert($this->transid);
                $this->Tab_model->Recycle_tabtrans_delete($getid_source);
                $this->Tab_model->Recycle_master_delete($this->transid);
                $this->Tab_model->Recycle_detail_delete($this->transid);                
            }
        }
        
        redirect(base_url().'admin/report/recycle/index');         
    }
}
