<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Nas_model
 *
 * @author edisite
 */
class Nas_model extends MY_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Nas_by_id($in_nasabahid = null) {
        $this->db->select('nasabah_id,nama_nasabah,alamat');
        $this->db->where('nasabah_id',$in_nasabahid);
        $this->db->from('nasabah');
        $this->db->limit('1');
        $query = $this->db->get()->result();
        return $query;
    }
    public function Nas_by_id_more($in_nasabahid = null) {
        $this->db->select('alamat,kecamatan,jenis_kelamin,tempatlahir,tgllahir,hp');
        $this->db->where('nasabah_id',$in_nasabahid);
        $this->db->from('nasabah');
        $this->db->limit('1');
        $query = $this->db->get()->result();
        return $query;
    }
    public function Createnasabahid() {
        $sql = "SELECT MAX(NASABAH_ID) + 1 as NASABAH_ID FROM NASABAH WHERE NASABAH_ID<='35299999' AND NASABAH_ID LIKE '352%%'";
        $query = $this->db->query($sql);
        return $query->result(); 
    }
    public function Tab_src_nas($caridata = "") {
        $sql = "SELECT no_rekening as rekening,nasabah.nama_nasabah as nama,nasabah.alamat "
                . "FROM tabung, nasabah WHERE tabung.nasabah_id=nasabah.nasabah_id  "
                . "AND (UPPER(no_rekening) LIKE '%".$caridata."%' OR UPPER(nama_nasabah) "
                . "LIKE '%".$caridata."%' OR UPPER(no_alternatif) LIKE '%".$caridata."%') limit 12";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Dep_src_nas($caridata = "") {
        $sql = "SELECT no_rekening as rekening,nasabah.nama_nasabah as nama,nasabah.alamat "
                . "FROM deposito, nasabah WHERE deposito.nasabah_id=nasabah.nasabah_id  "
                . "AND (UPPER(no_rekening) LIKE '%".$caridata."%' OR UPPER(nama_nasabah) "
                . "LIKE '%".$caridata."%' OR UPPER(no_alternatif) LIKE '%".$caridata."%') limit 6";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Kre_src_nas($caridata = "") {
        $sql = "SELECT no_rekening as rekening,nasabah.nama_nasabah as nama,nasabah.alamat,kredit.jml_pinjaman "
                . "FROM kredit, nasabah WHERE kredit.nasabah_id=nasabah.nasabah_id  "
                . "AND (UPPER(no_rekening) LIKE '%".$caridata."%' OR UPPER(nama_nasabah) "
                . "LIKE '%".$caridata."%' OR UPPER(no_alternatif) LIKE '%".$caridata."%') ORDER BY rekening DESC limit 12";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function Tfp_tab_src_nas($caridata = "") {
        $sql = "SELECT no_rekening as rekening,nasabah.nama_nasabah as nama,nasabah.alamat "
                . "FROM tabung, nasabah WHERE tabung.nasabah_id=nasabah.nasabah_id  "
                . "AND REPLACE(no_rekening,'.','') = '".$caridata."' limit 1";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
}
