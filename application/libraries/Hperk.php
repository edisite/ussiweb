<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Hperk
 *
 * @author edisite
 */
class Hperk {
    //put your code here
    
    protected $res_call_kode_perk_kas;    
    public $in_kode_perk_kas,$in_nama_perk,$in_gord,$in_kode_perk_com_in_integrasi,$in_kode_perk_kas_in_integrasi;
    public $kodeperkas_default  = '406';
    public $arrres = array();


    public function Com_get_perk($in_agenid = '') {
        if(empty($in_agenid) || empty($in_kode_integrasi)){
            return false;
        }
        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agenid);
        if($res_call_kode_perk_kas){
            foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
            }
        }else{
            $in_kode_perk_kas = $kodeperkas_default;
        }
        $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
        
            if($res_call_perk_kode_gord){  
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            
            //sender
        $res_call_tab_integrasi_by_kd = $this->Com_model->Integrasi_by_kd_int($in_kode_integrasi);
        foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

            $in_kode_perk_com_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_COM;                                                   
            $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;                                                   
        }
        $this->load->model('Sysmysysid_model');
        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_COMMERCE');
        foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
            $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE;
        }
        
        $arr_res = array(
          'kodeperk_kas'    => $in_kode_perk_kas,  
          'namaperk'        => $in_nama_perk,  
          'gord'            => $in_gord,
          'kodeperk_com'    => $in_kode_perk_com_in_integrasi,  
          'kode_jurnal'     => $res_kode_jurnal   
        );
        $this->Render_json($arr_res);
    }
    public function Render_json($data, $code = 200)
    {
        $this->output
                ->set_status_header($code)
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        global $OUT;
        $OUT->_display();
        exit;
    }
}
