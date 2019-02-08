<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Menu_model
 *
 * @author edisite
 */
class Sys_daftar_user_model extends MY_Model{
    //put your code here
    public function Get_id()
	{
                //$this->dtpengguna = "TELLER";  
		$this->db->select('*');
		//$this->db->where('user_name', $this->dtpengguna);
		$query = $this->db->get('sys_daftar_user');
		return $query->result();              
	}
        public function Unitkerja($idpegawai) {
            $this->db->select('UNIT_KERJA');
            $this->db->where('USER_ID',  $idpegawai);
            $this->db->limit(1);
            $query = $this->db->get('sys_daftar_user');
            return $query->result();
        }
        public function Byuser_id($uid)
	{
                //$this->dtpengguna = "TELLER";  
		$this->db->select('USER_NAME,UNIT_KERJA,PENERIMAAN,PENGELUARAN,JABATAN,PENERIMAAN_OB,PENGELUARAN_OB,user_code');
		$this->db->where('user_id', $uid);
                $this->db->limit(1);
		$query = $this->db->get('sys_daftar_user');
		return $query->result();              
	}
        
        public function Upd_Userid($unit,$terima = 0,$keluar = 0,$terimaob = 0,$keluarob = 0,$jabatan,$usercode,$username) {
                $SQL = "UPDATE SYS_DAFTAR_USER SET "
                        . "UNIT_KERJA='".$unit."', "
                        . "PENERIMAAN='".$terima."', "
                        . "PENGELUARAN='".$keluar."', "
                        . "PENERIMAAN_OB='".$terimaob."', "
                        . "PENGELUARAN_OB='".$keluarob."', "
                        . "JABATAN='".$jabatan."', "
                        . "USER_CODE='".$usercode."' "
                        . "WHERE USER_NAME='".$username."'";
                return $this->db->query($SQL);
        }
        public function Perk_kas($uid)
	{
                //$this->dtpengguna = "TELLER";  
		$this->db->select('KODE_PERK_KAS');
		$this->db->where('user_id', $uid);
                $this->db->limit(1);
		$query = $this->db->get('sys_daftar_user');
		return $query->result();              
	}
}
