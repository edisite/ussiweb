<html>
<head>

<style>
    div#wrapper{width: 800px;margin: auto;page-break-after: always;}
    
    table{ font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; text-transform:uppercase;border-collapse: collapse; width: 100%;}
    table tr td,th { font-size: 10px; padding: 2px;  }
    table#info{margin-top: 5px;}
    table#info td{border:none;font-size: 12px;padding: 2px;}
    
    table#tbl-footer{border:none;text-transform: none;}
    table#tbl-footer td{border:none;}
    
    .div_preheader, .div_header{ font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; }
    .div_preheader { font-size: 17px; font-weight: bold }
    .div_preheader2, .div_header{ font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; }
    .div_preheader2 { font-size: 14px; font-weight: italic }

    .div_header { font-size: 25px}
    .div_headertext { font-size: 10px }
    .div_notis { font-size: 9px; font-weight: italic; font-family: "arial",Helvetica,Arial,sans-serif; }

    .div_border td {border:none;}
    .div_judul { font-size: 20px; font-weight: bold }
    .div_teks { font-size: 15px;  }
    td, th,p {
        border: 1px solid #808080;
        padding: 2px;
    }
    tr.total td {border-left: none;border-right : 1.5px dashed #000000; border-top  : 1.5px dashed #000000; border-bottom  : 1.5px dashed #000000;}
</style>
<!-- PRINT OUT SET MARGIN "TOP:26mm","BOTTOM,LEFT,RIGHT:0mm"-->
<!--<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/adminlte.min.css">    
--><link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/admin.min.css">
<!--<script src='<?php echo base_url(); ?>assets/dist/adminlte.min.js'></script>-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BMT EL-SEJAHTERA</title>
</head>
<body>
    <!--<div class="wrapper">-->
    <!-- Main content -->
    <section class="invoice">
        <div id="wrapper" class="row" col-md>
      <div class="col-xs-12">
<!--        <h2 class="page-header">
          <i class="fa fa-globe"></i> BMT ELSEJAHTERA
          <small class="pull-right">Print: <?php echo date('H:i:s / d-m-Y');?></small>
        </h2>-->
          <table id="head" align="center" class="headerlaporan">
        <thead>
            <tr>
                <td width="80">
                    <img width="80" src="<?php echo base_url();?>assets/images/logo.png">
                </td>
                <td valign="top">
                    <div align="center" class="div_preheader">BMT ELSEJAHTERA</div>
                    <br>
                    <div align="center" class="div_preheader2">Bukti Pembayaran BPJS KESEHATAN</div>
                </td>
                <td width="80">
                <img width="80" align="right" src="https://sia.mercubuana.ac.id/application/akad/assets/images/mercubuana2.jpg">
                </td>
            </tr> 
            </thead>
        </table>
      
        <div style="clear:both"></div><br>                      
                <table border="0" cellspacing="0" cellpadding="2" align="center" width="780">                    
                    <tr> 
                      <td height="2" width="72"><b>No. BPJS</b></td>
                      <td height="2" width="291"><?php echo $idbpjs;?></td>
                        <td height="2" width="72"><b>Tgl Cetak</b></td>
                      <td height="2" width="291"><?php echo date("j F, Y H:i", strtotime(date('Y-m-d H:i:s'))); ?></td>
                    </tr>
                    <tr> 
                      <td height="2" width="72"><b>Nama</b></td>
                      <td height="2" width="291"><?php echo $namacus; ?></td>
                        <td height="2" width="72"><b>AgentID</b></td>
                      <td height="2" width="291"><?php echo $agentid; ?></td>
                    </tr>
                    <tr> 
                      <td height="2" width="72"><b>Periode</b></td>
                      <td height="2" width="291"><?php echo $periode; ?></td>
                    <td height="2" width="100"><b>Tagihan</b></td>
                      <td height="2" width="200" align="left"><?php echo $tagihan; ?>,00</td>
                    </tr>
                    <tr> 
                      <td height="2" width="72"><b>JUMLAH PESERTA</b></td>
                      <td height="2" width="291"><?php echo $total_person; ?> Orang</td>
                        <td height="2" width="100"><b>Biaya Admin</b></td>
                      <td height="2" width="200" align="left"><?php echo $admin; ?>,00</td>
                    </tr>
                    <tr> 
                        <td height="2" width="72"><b>No. REFF</b></td>
                        <td height="2" width="291"><?php echo $sn; ?></td>
                        <td height="2" width="100" rowspan="3"><b>Total Bayar</b></td>
                        <td height="2" width="200" rowspan="3" align="center"><h2>Rp <?php echo $total_tagihan; ?>,00</h2></td>
                    </tr>
                    <tr> 
                      <td height="2" width="72"><b>Tgl Transaksi</b></td>
                      <td height="2" width="291"><?php echo date("j F, Y H:i", strtotime($tanggal_transaksi)); ?></td>
                    </tr>
                    <tr> 
                      <td height="2" width="72"><b>TID</b></td>
                      <td height="2" width="291"><?php echo $idtrans; ?></td>
                    </tr>
                  </table>
        
 
            <div style="clear:both"></div><br>
                <p class="div_notis">Rincian Struk ini dapat di akses di www.bpjs-kesehatan.co.id<br>
                Simpalah struk ini sebagai bukti pembayaran. Struk ini merupakan dokumen elektronik dan alat buktu hukum yang sah(PASAL 5 Ayat (1) UU ITE) </p>
                    
    </div>
        </div>
    </section>
</body>
</html>
