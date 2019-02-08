<html>
<head>

<style>
    div#wrapper{width: 800px;margin: auto;page-break-after: always;}
    table{ font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; text-transform:uppercase;border-collapse: collapse; width: 100%;}
    table tr td,th { font-size: 10px; padding: 2px;  }
    table#info{margin-top: 5px;}
    table#info td{border:none;font-size: 10px;padding: 2px;}
    
    table#tbl-footer{border:none;text-transform: none;}
    table#tbl-footer td{border:none;}
    
    .div_preheader, .div_header{ font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; }
    .div_preheader { font-size: 17px; font-weight: bold }
    .div_preheader2, .div_header{ font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; }
    .div_preheader2 { font-size: 14px; font-weight: italic }

    .div_header { font-size: 25px}
    .div_headertext { font-size: 10px }

    .div_border td {border:none;}
    .div_judul { font-size: 20px; font-weight: bold }
    .div_teks { font-size: 15px;  }
    td, th {
        border: 1px solid #000000;
        padding: 2px;
    }
    tr.total td {border-left: none;border-right : none;border-top  : 1.5px dashed #000000; border-bottom  : 1.5px dashed #000000;}
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
                    <div align="center" class="div_preheader2">laporan commerce</div>
                    <?php
                    if($datauser):
                        foreach ($datauser as $vu) {
                            $userid     = $vu->id;
                            $username   = $vu->username;;
                            $namalengkap     = $vu->first_name.' '.$vu->last_name;
                        }
                        endif;
                        
                        ?>
                    <table id="info" width="100%" >
                        <tr>
                            <td>ID AGENT</td>
                            <td>: <?php echo $userid.' - '.$username; ?></td>
                            <td>Tgl. Transaksi</td>
                            <td>: <?php echo $tgl_from.' s/d '.$tgl_to; ?></td>
                        </tr>
                        <tr>
                            <td>NAMA AGENT</td>
                            <td>: <?php echo $namalengkap; ?></td>
                            <td>Tgl. Cetak</td>
                            <td>: <?php echo date('H:i:s / d-m-Y');?> </td>
                        </tr>
<!--                        <tr>
                            <td>Tanggal</td>
                            <td></td>
                            <td>Tgl. Cetak</td>
                            <td></td>
                        </tr>-->
                    </table>
                </td>
                <td width="80">
                <img width="80" align="right" src="https://sia.mercubuana.ac.id/application/akad/assets/images/mercubuana2.jpg">
                </td>
            </tr> 
            </thead>
        </table>
      
        <div style="clear:both"></div><br>
                <h3 class="box-title div_preheader2">Data Transaksi Penjualan</h3>          
                <table class="table table-hover table-bordered">
                    <tr bgcolor="A9C5EB">
                        <th style="width:1%">No</th>
                        <th style="width:10%">Tanggal</th>
                        <th style="width:5%">Kode</th>
                        <th style="width:8%">Tipe</th>
                        <th style="width:8%">Produk</th>
                        <th style="width:10%">Nomor</th>
                        <th style="width:10%">Harga BMT</th>
                        <th style="width:10%">Harga JUAL</th>
                        <th style="width:10%">Basil Agent</th>

                    </tr>
                    <?php  
                    $no = 0;
                        echo $table;
                    ?>
                </table>
                <div style="clear:both"></div><br>
                <h3 class="box-title div_preheader2">Data Transaksi Payment (Pembayaran)</h3>         
                <table class="table table-hover table-bordered">
                    <tr bgcolor="A9C5EB">
                        <th style="width:1%">No</th>
                        <th style="width:10%">Tanggal</th>
                        <th style="width:5%">Kode</th>
                        <th style="width:8%">Tipe</th>
                        <th style="width:8%">Produk</th>
                        <th style="width:10%">Nomor</th>
                        <th style="width:10%">Tagihan</th>
                        <th style="width:10%">Total Adm</th>
                        <th style="width:10%">Basil Agent</th>

                    </tr>
                    <?php  
                    $no = 0;
                        echo $tablep;
                    ?>
                </table>
 
            <div style="clear:both"></div><br>

                    <table id="tbl-footer" width="200" border="1">
                      <tr>
                        <td width="5%" rowspan="10" style="vertical-align:top"></td>
                        <td width="10%">&nbsp;</td>
                        <td width="23%">&nbsp;</td>
                        <td width="30%">&nbsp;</td>
                        <td width="25%">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>Diketahui,</td>
                        <td>&nbsp;</td>
                        <td>Cipari, <?php echo date('d F Y'); ?> </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>Pimpinan BMT ELSejahtera</td>
                        <td>&nbsp;</td>
                        <td>Petugas</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>(................................................)</td>
                        <td>&nbsp;</td>
                        <td>(................................................)</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td style="font-size:10px">Tulis Nama Jelas</td>
                        <td>&nbsp;</td>
                        <td style="font-size:10px">Tulis Nama Jelas</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                  </table>
         
    </div>
        </div>
    </section>
</body>
</html>
