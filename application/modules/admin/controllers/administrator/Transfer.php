<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transfer
 *
 * @author edisite
 */
class Transfer extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Daftar_bank() {
        $crud = $this->generate_crud('transfer_daftar_bank');
        $crud->set_theme('datatables');
        $crud->display_as('sett_default', 'Default');
        //$crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_delete();
        $crud->fields('nama_bank','biaya_adm','status','sett_default','keterangan'); 
        $crud->columns('nama_bank','biaya_adm','status','sett_default','keterangan');
        $crud->field_type('status','dropdown',array('1' => 'active', '0' => 'inactive'));
        $crud->callback_add_field('keterangan',array($this,'add_field_callback_1'));
        $crud->callback_edit_field('keterangan',array($this,'add_field_callback_1'));
        $crud->set_subject('Bank');
        $this->mTitle .= '[1013] Daftar Data Bank';
        $this->render_crud();
    }
    public function Modeltrf() {
        $crud = $this->generate_crud('transfer_jenis');
        $crud->set_theme('datatables');
        $crud->columns('kode_trf','nama_trf','set_adm_default','adm_default','adm_bmt', 
	'set_limit','min_trf','max_trf','dtm');
        $crud->display_as('kode_trf', 'Kode');
        $crud->display_as('nama_trf', 'Nama');
        $crud->display_as('set_limit', 'Set Transfer');
        $crud->display_as('min_trf', 'MIN Transfer');
        $crud->display_as('max_trf', 'MAX Transfer');
        $crud->display_as('adm_default', 'ADM');
        $crud->callback_column('adm_default',array($this,'_column_bonus_right_align'));
        $crud->callback_column('min_trf',array($this,'_column_bonus_right_align'));
        $crud->callback_column('max_trf',array($this,'_column_bonus_right_align'));
        $crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_add();
        $crud->unset_delete();
        $crud->set_subject('Bank');
        $this->mTitle .= '[1014] Daftar Jenis Transfer';
        $this->render_crud();
    }
    function add_field_callback_1()
    {
          return '<textarea name="address" class="texteditor"></textarea>';
    }
    function _column_bonus_right_align($value)
    {       
        return "<span style=\"width:100%;text-align:right;display:block;\">".$this->rp($value)."</span>";
    }
    function Rp($value)
    {
        return number_format($value,2,",",".");
    }
}
