<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Report
 *
 * @author edisite
 */
class Report extends MY_Controller{
    //put your code here
    
    private $tbl_res     = '';
    private $tbl_resp     = '';
    private $tbl_profit     = '';
    private $tgl_fr_t    = '';
    private $tgl_to_t    = '';
    private $info_tgl;
    
    private $mn_cash_awal   = 0;
    private $mn_cash_akhir  = 0;
    private $mn_cash_adm    = 0;
    
    private $mn_debt_awal   = 0;
    private $mn_debt_akhir  = 0;
    private $mn_debt_adm    = 0;
    
    private $no             = 1;
    private $mn_cash_awal_bfr,$jml_setoran_bfr_bydtm,$total_setoran_bfr,$jml_basil_bfr_bydtm   = 0;
    private $mn_cash_akhir_bfr  = 0;
    private $mn_cash_adm_bfr    = 0;
    

    private $mn_cash_tagihan, $mn_cash_tagihan_adm, $mn_cash_tagihan_basil, $mn_debt_tagihan, $mn_debt_tagihan_adm, $mn_debt_tagihan_basil = 0; 
    private $mn_cash_tagihan_bfr, $mn_cash_tagihan_adm_bfr, $mn_cash_tagihan_basil_bfr, $mn_debt_tagihan_bfr, $mn_debt_tagihan_adm_bfr, $mn_debt_tagihan_basil_bfr = 0; 
    private $total_setoranp_bfr,$grand_total_transaksi,$grand_total_basil,$grand_total_setoran, $total_transaksip,$total_transaksi,$profit_cashp,$profit_cash,$profit_debtp,$profit_debt,$total_setoranp,$total_setoran = 0;
    
    private $tgl_fr_gnt = "";
    
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Com_model');
        if(!$this->session->userdata('ident')){
            redirect('agen/login');
        }
        
    }
    public function Trx_perday() {
        $res_hist = $this->Com_model->Hist_per_day($this->session->userdata('ident'));
        $arr = array();
        $arr['reportperday'] = $res_hist;
        //var_dump($res_hist);
        $this->load->view('report_per_day',$arr);        
    }
    public function Transaksi() {
        $arr['tgl'] = '';
        $arr['data'] = '';
        $arr['data_profit'] = '';
        
        $this->load->view('report_recon',$arr);        
    }
    function Transaksi_proses() {
        $this->session->set_userdata('errormsg');
        $tgl_fr     = $this->input->post('tgl_fr') ?: '';
        $tgl_to     = $this->input->post('tgl_to') ?: '';        
        if(empty($tgl_fr)){
            redirect('agen/report/transaksi');        }
        if(empty($tgl_to)){
            redirect('agen/report/transaksi');        }
            
        $selisih = strtotime($tgl_to) - strtotime($tgl_fr);
        $hari = $selisih/(60*60*24);
        $hari = $hari + 1;
        if($hari == 0){
            $notifhari = "Hari sama";
        }else{
            $notifhari = $hari. " Hari";
        }
        $this->info_tgl = '<div class="price-availability-block clearfix"><div class="price">
                      <strong>'.$notifhari.'</strong>
                      <em><b>'.date("j - m - Y", strtotime($tgl_fr)).'  s/d  '.date("j - m - Y", strtotime($tgl_to)).'</b></em>
                    </div></div>';
        if($hari > 31){
            $this->session->set_userdata('errormsg','Mutasi data maksimal 30 hari');
            redirect('agen/report/transaksi');
            return;
        }
        $tgl_fr =  date("Y-m-d", strtotime($tgl_fr));
        $tgl_to =  date("Y-m-d", strtotime($tgl_to));
        
        $getdata = $this->Com_model->Tgh_mutasi($this->session->userdata('ident'),$tgl_fr,$tgl_to);               
       if($getdata):
                foreach ($getdata as $v) {                        
                    $this->tbl_res .= "<tr>"
                                . "<td>".$this->no."</td>"
                                . "<td>".$v->dtm_transaksi."</td>"
                                . "<td>".$v->KODE_TRANS."</td>"
                                . "<td>".$v->nominal."</td>"
                                . "<td>".$v->product."</td>"
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
                
                $this->total_setoran      = $this->mn_cash_awal;
                
                $this->tbl_res .= "<tr >"
                                . "<td colspan='5' align='center'><strong>TOTAL</strong></td>"
                                . "<td align='right' class='pricing'><strong>".  $this->Rp($this->mn_cash_awal + $this->mn_debt_awal,'')."</strong></td>"
                                . "<td align='right' class='pricing'><strong>".  $this->Rp($this->mn_cash_akhir + $this->mn_debt_akhir,'')."</strong></td>"
                                . "<td align='right' class='pricing'><strong>".  $this->Rp($this->profit_cash + $this->profit_debt,'')."</strong></td>"
                                . "</tr>";
            endif;
            
            // payment
        $getdatap = $this->Com_model->Tgh_mutasi_pay($this->session->userdata('ident'),$tgl_fr,$tgl_to);     
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
                                . "<td>".$v->NOCUSTOMER."</td>"
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
                
                $this->setoranp_cash      = $this->mn_cash_tagihan + $this->mn_cash_tagihan_adm - $this->mn_cash_tagihan_basil;
                $this->setoranp_debt      = $this->mn_debt_tagihan + $this->mn_debt_tagihan_adm - $this->mn_debt_tagihan_adm;
                
                $this->total_setoranp     = $this->setoranp_cash - $this->setoranp_debt ;
                $this->tbl_resp .= "<tr>"
                                . "<td colspan='6' align='center'><strong>TOTAL</strong></td>"
                                . "<td align='right' class='pricing'><strong>".  $this->Rp($this->mn_cash_tagihan + $this->mn_debt_tagihan,'')."</strong></td>"
                                . "<td align='right' class='pricing'><strong>".  $this->Rp($this->mn_cash_tagihan_adm + $this->mn_debt_tagihan_adm,'')."</strong></td>"
                                . "<td align='right' class='pricing'><strong>".  $this->Rp($this->mn_cash_tagihan_basil + $this->mn_debt_tagihan_basil,'')."</strong></td>"
                                . "</tr>";
            endif;
            //get data sebelum tanggal tagihan    
            $subtotalprofit = $this->profit_cash + $this->profit_debt + $this->profit_cashp + $this->profit_debtp;
            $subtotalsetoran = $this->total_setoranp + $this->total_setoran;
            $this->tbl_profit = "         
                        <tr>
                          <th style=width:20%>Total Transaksi</th>
                          <td></td>
                          <td></td>
                          <td align='right' class='pricing-active'><b>".  $this->Rp($this->total_transaksi + $this->total_transaksip,'')."</b></td>
                        </tr>
                        <tr>
                        <th rowspan='2'>Profit Agent</th>
                          <td>Cash</td>
                          <td align='right'>".  $this->Rp($this->profit_cash + $this->profit_cashp,'')."</td>
                              
                          <td rowspan='2' align='right' class='pricing-active'><b>".$this->Rp($subtotalprofit,'')."</b></td>
                        </tr>
                        <tr>
                          <td>Debit Tabungan</td>
                          <td align='right'>".  $this->Rp($this->profit_debt + $this->profit_debtp,'')."</td>
                        </tr>
                        <tr>
                          <th>Total Setoran</th>
                          <td></td>
                          <td></td>
                          <td align='right' class='pricing-active'><b>".  $this->Rp($subtotalsetoran,'')."</b></td>
                        </tr>
                ";
            
        $arr['tgl']         = $this->info_tgl;
        $arr['data']        = $this->tbl_res;
        $arr['datap']       = $this->tbl_resp;
        $arr['data_profit'] = $this->tbl_profit;
        $this->load->view('report_recon',$arr);
        
    }
    protected function Rp($value, $row)    {
        return number_format($value,2,",",".");
    }
}
