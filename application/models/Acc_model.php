<?php
date_default_timezone_set('Asia/Jakarta');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Acc_model
 *
 * @author edisite
 */
class Acc_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Ins_report_perkiran() {        
        $sql = "insert into ".TBL_NERACA_TEMP." (kode_perk,kode_induk,type_perk,id_perk,id_induk,level_perk,g_or_d,dtm,d_or_k)select kode_perk,kode_induk,type_perk,id_perk,id_induk,level_perk,g_or_d, '".Date('Y-m-d')."',d_or_k from perkiraan order by kode_perk";
        $query = $this->db->query($sql);
        return $query;  
    }
    protected function _Tgl_hari_ini(){
        echo Date('Y-m-d');
    }
    public function Count_report_perkiraan($in_tanggal = '0000-00-00') {
        if(empty($in_tanggal)){ return; }
        $sql= "SELECT COUNT(*) AS total FROM ".TBL_NERACA_TEMP." WHERE dtm = '".$in_tanggal."'";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Perkiraan_by_gord($in_tanggal = '') {
        if(empty($in_tanggal)){ return; }
        $sql = "SELECT KODE_PERK, ID_PERK, TYPE_PERK, LEVEL_PERK,D_OR_K from ".TBL_NERACA_TEMP." WHERE dtm = '".$in_tanggal."' AND G_OR_D='D' AND flag ='0'";
        $query = $this->db->query($sql)->result();
        return $query;
    }
    public function Perkiraan_idinduk_by_kdperk($kode_perk = '',$in_tanggal = '') {
        if(empty($in_tanggal)){ return; }
        if(empty($kode_perk)){ return; }
        $this->db->select('ID_INDUK');
        $this->db->where('ID_PERK',$kode_perk);
        $this->db->where('dtm',$in_tanggal);
        $query = $this->db->get(TBL_NERACA_TEMP)->result();
        return $query;
    }
    public function Trans_detail_saldo_awal($tgl = '', $kode_perk = '') {
        if(empty($tgl)){            return FALSE;        }
        if(empty($kode_perk)){            return FALSE;         }
        $sql = "select sum(debet) as debet, sum(kredit) as kredit "
                . "from transaksi_detail "
                . "where kode_perk='".$kode_perk."' and master_id in "
                . "(select trans_id from transaksi_master "
                . "where date(tgl_trans) < '".$tgl."') ";
        $query = $this->db->query($sql)->result();        
        return $query;
    }
    public function Trans_detail_dork($tgl = '', $kode_perk = '') {
        if(empty($tgl)){            return FALSE;        }
        if(empty($kode_perk)){            return FALSE;         }
        $sql = "select sum(debet) as debet, sum(kredit) as kredit "
                . "from transaksi_detail "
                . "where kode_perk='".$kode_perk."' and master_id in "
                . "(select trans_id from transaksi_master "
                . "where date(tgl_trans) = '".$tgl."') ";
        $query = $this->db->query($sql)->result();        
        return $query;
    }
    public function Trans_main_induk($in_id_induk = '') {
        if(empty($in_id_induk)){            return FALSE;        }
        $sql = "SELECT sum(saldo_akhir1) as AWAL, "
                . "sum(saldo_akhir2) as DEBET, "
                . "sum(saldo_akhir3) as KREDIT,  "
                . "sum(saldo_akhir4) as AKHIR "
                . "FROM ".TBL_NERACA_TEMP." "
                . "WHERE ID_INDUK=".$in_id_induk;
        $query = $this->db->query($sql)->result();        
        return $query;
    }
    public function Upd_temp_neraca_harian($in_kode_perk = '',$in_tanggal = '',$arr_upd = array()) {
        if(empty($in_kode_perk)){            return;        }
        if(empty($in_tanggal)){            return;        }
        if((empty($arr_upd)) || (!is_array($arr_upd))){            return;        }
        $this->db->where('kode_perk',$in_kode_perk);
        $this->db->where('flag','0');
        $this->db->where('dtm',$in_tanggal);
        $query = $this->db->update(TBL_NERACA_TEMP,$arr_upd);
        return $query;
        //UPDATE MyTTELLER040117160433 set saldo_akhir1=0, saldo_akhir2=0, saldo_akhir3=0, saldo_akhir4=0 
        //where kode_perk='407'
    }
    public function Upd_temp_neraca_harian_by_id_perk($in_id_perk = '',$in_tanggal = '',$arr_upd = array()) {
        if(empty($in_id_perk)){            return;        }
        if(empty($in_tanggal)){            return;        }
        if((empty($arr_upd)) || (!is_array($arr_upd))){            return;        }
        $this->db->where('ID_PERK',$in_id_perk);
        $this->db->where('dtm',$in_tanggal);
        $query = $this->db->update(TBL_NERACA_TEMP,$arr_upd);
        return $query;
        
    }
    public function Saldo_dk($kodekredit = '',$tanggal = '') {
        if(empty($kodekredit)){
            return false;
        }
            $sql = "call Neraca_harian_saldo_dk(?,?)";
            $prm = array($kodekredit,$tanggal);
       
            $query = $this->db->query($sql,$prm);
            //$this->db->free_db_resource();
        return $query;      
    }
    
}
//define("TBL_NERACA_TEMP", "rep_neraca_harian_temp");
