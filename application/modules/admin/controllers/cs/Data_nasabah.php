<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Data_nasabah
 *
 * @author edisite
 */
class Data_nasabah extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
                
                $res_group1 = $this->Css_model->Kodegroup1();
                $arr_group1 = array();
                foreach ($res_group1 as $sub_group1) {
                    $arr_group1[$sub_group1->kode] = $sub_group1->deskripsi;
                }
                $res_group2 = $this->Css_model->Kodegroup2();
                $arr_group2 = array();
                foreach ($res_group2 as $sub_group2) {
                    $arr_group2[$sub_group2->kode] = $sub_group2->deskripsi;
                }
                $res_group3 = $this->Css_model->Kodegroup3();
                $arr_group3 = array();
                foreach ($res_group3 as $sub_group3) {
                    $arr_group3[$sub_group3->kode] = $sub_group3->deskripsi;
                }
                $res_agama = $this->Css_model->Kodeagama();
                $arr_agama = array();
                foreach ($res_agama as $sub_agama) {
                    $arr_agama[$sub_agama->DESKRIPSI] = $sub_agama->DESKRIPSI;
                }
                $res_provinsi = $this->Css_model->Kodepropvinsi();
                $arr_provinsi = array();
                foreach ($res_provinsi as $sub_provinsi) {
                    $arr_provinsi[$sub_provinsi->KODE_PROVINSI] = $sub_provinsi->NAMA_PROVINSI;
                }
                $res_dati = $this->Css_model->Kodedati();
                $arr_dati = array();
                foreach ($res_dati as $sub_dati) {
                    $arr_dati[$sub_dati->KODE_DATI] = $sub_dati->DESKRIPSI_KODE_DATI;
                }
                $res_jenisid = $this->Css_model->Jenisid();
                $arr_jenisid = array();
                foreach ($res_jenisid as $sub_jenisid) {
                    $arr_jenisid[$sub_jenisid->JENIS_ID] = $sub_jenisid->NAMA_IDENTITAS;
                }                              
                
                //echo $arr_nasabahid;
                $crud = $this->generate_crud('nasabah');
                //$crud->set_theme('datatables');
                $crud->field_type('JENIS_KELAMIN', 'dropdown', array('L'=>'LAKI-LAKI','P'=>'PEREMPUAN'));
                $crud->field_type('KODE_GROUP1', 'dropdown', $arr_group1);
                $crud->field_type('KODE_GROUP2', 'dropdown', $arr_group2);
                $crud->field_type('KODE_GROUP3', 'dropdown', $arr_group3);
                $crud->field_type('KODE_AGAMA', 'dropdown', $arr_agama);
                $crud->field_type('JENIS_ID', 'dropdown', $arr_jenisid);
                $crud->field_type('KOTA_KAB', 'dropdown', $arr_dati);
                $crud->field_type('PROPINSI', 'dropdown', $arr_provinsi);
                //$crud->field_type('NASABAH_ID', 'string', $arr_nasabahid);
                $crud->fields('NASABAH_ID','TGL_REGISTER','NAMA_NASABAH','NAMA_ALIAS','JENIS_KELAMIN','TEMPATLAHIR','TGLLAHIR','JENIS_ID',
                        'NO_ID','MASA_BERLAKU_KTP','KODE_AGAMA','NAMA_IBU_KANDUNG','ALAMAT','DESA','KECAMATAN',
                        'KOTA_KAB','PROPINSI','TELPON',
                        'HP','KODE_GROUP1','KODE_GROUP2','KODE_GROUP3','USERID');
		$crud->columns('NASABAH_ID', 'NAMA_NASABAH', 'ALAMAT', 'VERIFIKASI','DIN');
                $crud->callback_add_field('USER_PASSWORD',array($this,'add_field_callback_1'));
                //$crud->callback_edit_field('phone',array($this,'edit_field_callback_1'));
                $crud->callback_before_delete(array($this,'log_user_before_delete'));
                $crud->callback_add_field('ALAMAT',array($this,'add_field_callback_tarea'));
                $crud->callback_edit_field('ALAMAT',array($this,'add_field_callback_tarea'));
                    $crud->callback_add_field('NASABAH_ID',array($this,'field_callback_1'));
                   $crud->callback_add_field('USER_ID',array($this,'field_callback_agentid'));
                    
                    $crud->display_as('NASABAH_ID', 'ID Nasabah');
                    $crud->display_as('TGL_REGISTER', 'Tanggal Register');
                    $crud->display_as('NAMA_NASABAH', 'Nama Nasabah');
                    $crud->display_as('NAMA_ALIAS', 'Nama Panggilan');
                    $crud->display_as('JENIS_KELAMIN', 'Jenis Kelamin');
                    $crud->display_as('TEMPATLAHIR', 'Tempat Lahir');
                    $crud->display_as('TGLLAHIR', 'Tanggal Lahir');
                    $crud->display_as('JENIS_ID', 'Jenis ID');
                    $crud->display_as('NO_ID', 'No Identitas');
                    $crud->display_as('MASA_BERLAKU_KTP', 'KTP Berlaku s/d');
                    $crud->display_as('KODE_AGAMA', 'Agama');
                    $crud->display_as('NAMA_IBU_KANDUNG', 'Nama Ibu Kandung');
                    $crud->display_as('ALAMAT', 'Alamat');
                    $crud->display_as('DESA', 'Kelurahan/Desa');
                    $crud->display_as('KECAMATAN', 'Kecamatan');
                    $crud->display_as('KOTA_KAB', 'Kotamadya');
                    $crud->display_as('PROPINSI', 'Lokasi Nasabah');
                    $crud->display_as('TELPON', 'No Telp');
                    $crud->display_as('KODE_GROUP1', 'Pekerjaan');
                    $crud->display_as('KODE_GROUP2', 'Pendidikan');
                    $crud->display_as('KODE_GROUP3', 'Keanggotaan');
                    $crud->display_as('USER_ID', 'Agent ID');
                    $crud->set_rules('HP', 'HP','numeric');
                    $crud->set_rules('TELPON', 'TELPON','numeric');
                    $crud->required_fields('NASABAH_ID','TGL_REGISTER','NAMA_NASABAH','JENIS_KELAMIN','TEMPATLAHIR','TGLLAHIR','JENIS_ID',
                        'NO_ID','MASA_BERLAKU_KTP','KODE_AGAMA','NAMA_IBU_KANDUNG','ALAMAT','DESA','KECAMATAN',
                        'KOTA_KAB','PROPINSI',
                        'HP','KODE_GROUP1','KODE_GROUP2','KODE_GROUP3');
                    
                $crud->set_subject('Nasabah');

		$this->mTitle.= '[1000]Data Nasabah';
		$this->render_crud();
    }
    function field_callback_1($arr_nasabahid = null)
        {
            $res_nasabahid = $this->Nas_model->Createnasabahid();
                if($res_nasabahid){
                    foreach ($res_nasabahid as $sub_nasabahid) {
                        $arr_nasabahid = $sub_nasabahid->NASABAH_ID;
                    }
                }else{
                    redirect();
                }  
            return '<input type="text" maxlength="50" value="'.$arr_nasabahid.'" name="NASABAH_ID" style="width:462px" readonly>';
        }
    function field_callback_agentid()
        { 
            return '<input type="text" maxlength="10" value="'.$this->session->userdata('user_id').'" name="USERID" style="width:462px" readonly>';
        }
    function add_field_callback_1()
        {
                unset($post_arr['USER_PASSWORD']);   
                return $post_arr;
        }
    function add_field_callback_tarea()
        {

              return '<textarea name="ALAMAT" rows="1" cols="7"></textarea>';

        }    
    public function log_user_before_delete($primary_key)
        {
            $this->db->where('NASABAH_ID',$primary_key);
            $this->db->where('VERIFIKASI','1');
            $user = $this->db->get('nasabah')->row();

            if(empty($user)){
                return false;
            }
            else{
                return true;
            }
        }
    

}
