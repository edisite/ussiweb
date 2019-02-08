<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Simul
 *
 * @author edisite
 */
class Simul extends API_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Openhttp($url= NULL,$postText=null) {
        $ch=curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postText);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json\r\n',            
            'Content-Length: ' . strlen($postText) ,
            'XAPIKEY' => 'a1s2d3f4g5'
            )             
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);          
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $sfd = "Failed : ".curl_error($ch);
            echo $sfd;
        } else {
            echo $data;
        }
        curl_close($ch);  
        
    }
    public function Hit_get() {
        $post = array(
            "no_rekening" => "35.01.006779",
            "nominal"=> "10000000",
            "adm"=> "0",    
            "agentid"=> "2368264",
	);
	$url = "http://localhost/ci/ussiweb/api/tabung/setor";
	$postText = json_encode($post);
        return $this->Openhttp($url,$postText);     
    }
    public function Hitkredit_get() {    
        $post = 
             array('NO_REKENING' => '35.05.001990',
            'ANGSURAN_KE' => '8','POKOK' => '83333.33','BUNGA' => '0.00','DENDA' => NULL,'ADM_LAINNYA' => NULL,'TABUNGAN' => NULL,
            'KETERANGAN' => 'Tagihan Ke 8','VERIFIKASI' => '1','USERID' => '-13171',
            'KODE_TRANS' => '201','KODE_KANTOR' => NULL,'DISCOUNT' => '0.00','AGENT_ID');
            
            $url = "http://localhost/ci/ussiweb/api/kredit/sent_ang";
            $postText = json_encode($post);
            return $this->Openhttp($url,$postText);     
    }
    public function Hittrf_get() {
            $post = array(
            "agentid" => "28762",
            "rek_sender" => "35.01.006779222",
            "rek_receiver" => "35.01.00673022",
            "kode_bank_sender"=> "105",
            "nama_nasabah_sender"=> "Juleha",
            "codetransfer"=> "100",
            "nominal"=> "283"

            );
            $url = "http://localhost/ci/ussiweb/api/transfer/check_rekening";
            $postText = json_encode($post);
            $res = $this->Openhttp($url,$postText);
           // $js = json_decode($res,true);
            return $res;
    }   
    public function Hittrfsent_get() {
            $post = array(
            "agentid" => "28762",
            "code" => "287621610012020LQzeuSkYMX3xcUtfJ569",
            );
            $url = "http://localhost/ci/ussiweb/api/transfer/sent_trf";
            $postText = json_encode($post);
            $res = $this->Openhttp($url,$postText);
            echo $res;
    }  
    public function Pengajuan_pinjaman_get() {
            $post = array(
            "agentid" => "264823",
            "no_rekening" => "35.01.006779",
            "nominal" => "1000000",
            "lama_angsuran" => "12",
            "jumlah_angsuran" => "100000"
            );
            $url = "http://localhost/ci/ussiweb/api/loan_app/cekdata";
            $postText = json_encode($post);
            $res = $this->Openhttp($url,$postText);
            echo $res;
    }
    public function login_get() {
            $post = array(
            "identity" => "edisite",
            "xpasword" => "123456",            
            );
            $url = "http://localhost/ci/ussiweb/api/auth/cek";
            $postText = json_encode($post);
            $res = $this->Openhttp($url,$postText);
            echo $res;
    }
    public function Ceksaldo_get() {
        $post = array(
            "no_rekening" => "35.01.001856",
            "agentid"=> "2368264",
	);
	$url = "http://localhost/ci/ussiweb/api/tabung/saldo";
	$postText = json_encode($post);
        return $this->Openhttp($url,$postText);     
    }
}
