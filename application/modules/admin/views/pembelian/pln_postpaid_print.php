<?php
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/skins/_all-skins.min.css">
  <style>
body {
font-family:Tahoma, Geneva, sans-serif;
font-size:12px;
background-color:#eee;
margin:0;
padding:0;
}

 

h1, h2, h3, h4 {
margin:0;
padding:0;
}

#container {
width:500px;
margin:20px auto;
padding:0px;
background-color:#fff;
box-shadow:0px 0px 3px #000;
}
#header {
text-align:center;
}

#menu {
text-align:center;
margin:15px 0px;
border-top:1px solid #CCC;
border-bottom:1px solid #CCC;
}

 

#menu a {
display:inline-block;
padding:5px 10px;
text-decoration:none;
color:#000;
font-weight:bold;
}

 

#menu a:hover {
background-color:#CCC;
}

 

#menu a.active {
background-color:#CCC;
}

 

.table, th, td {
border-collapse:collapse;
border:0px S #ccc;
}

 

.table th {
background-color:#CCC;
}

 

.error {
border:1px solid #FF8080;
background-color:#FFCECE;
padding:3px;
margin:5px 0px;
text-align:center;
}

.ok {
border:1px solid #fff;
background-color:#eee;
padding:3px;
margin:5px 0px;
text-align:center;
}
</style>
  
  
</head>
<body onload="window.print();">
<div class="container">
    <!-- Main content -->
   <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header">
			<img src="<?php echo base_url(); ?>assets/images/logo_bmt.jpg" width="120px" height="60px"/> BMT EL-SEJAHTERA
<small class="pull-right"><b>printed : </b><?php echo date('d F Y H:i:s');?></small>
            
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      
      <!--//array(1) { [0]=> object(stdClass)#57 (5) { ["tagihan"]=> string(8) "24852.00" 
      ["biaya_adm"]=> string(4) "2500" ["cust_name"]=> string(12) "TATI SURIYAH" 
      ["destid"]=> string(12) "525060838874" ["res_sn"]=> string(32) "0BAG210ZA98BB8C0210C64DF54B3CB81" } }--> 
      <?php
            if($ref):
                foreach ($ref as $v) {
                    $nometer    = $v->destid ?: '-';
                    $tagihan    = $v->tagihan ?: '-';
                    $nometer    = $v->destid ?: '-';
                    $biayadm    = $v->biaya_adm ?: '0';
                    $namacus    = $v->cust_name ?: '-';
                    $noreren    = $v->res_sn ?: '-';
                    $blnthun    = $v->periode1 ?: '-';
                    $dayapln    = $v->daya ?: '-';
                    $totalby    = $v->total ?: '0';
                }
            endif;
      ?>
   <div class="row">
       <div class="col-md-12" align="center">
             <h4>BUKTI PEMBAYARAN PLN POSTPAID</h4><br>
        </div>
      <div class="col-md-12">
        <table class="table">
        <tr>
            <td width='20%'>IDPEL</td>
            <td width='20%'>: <?php echo $nometer; ?></td>
            <td width='10%'></td>
            <td width='20%'>BLN / THN</td>
            <td width='20%'>: <?php echo $blnthun; ?></td>
        </tr>
        <tr>
            <td>NAMA</td>
            <td>: <?php echo $namacus; ?></td>
            <td></td>
            <td>STAND METER</td>
            <td>:</td>
        </tr>
        <tr>
            <td>TARIF /DAYA</td>
            <td>: <?php echo $dayapln; ?></td> 
            <td></td>
            <td>NO REF</td>
            <td rowspan="1">: <?php echo $noreren; ?></td>
        </tr>
        <tr>
            <td>TAGIHAN PLN</td>
            <td>: <?php echo number_format($tagihan,2,",",".");; ?></td>            
        </tr>
        <tr>
            <td>BIAYA ADMIN</td>
            <td>: <?php echo number_format($biayadm,2,",","."); ?></td>
            <td rowspan="3" colspan="3" valign='middle' align='left'>
                PLN menyatakan struk ini sebagai bukti pembayaran sah.<br>
                <i>Informasi  Hubungi CALL CENTER 123 atau PLN terdekat</i>
            </td>
        </tr>
       </tbody>
    </table>
      </div>
    </div>  
    <div class="col-xs-12 ok">
        <h5>TOTAL BAYAR <strong> : Rp <?php echo number_format($totalby,2,",","."); ?></strong></h5>
  </div>
  <address style="font-size: 80%"><center>BMT EL-SEJAHTERA, Jl. Jendral Ahmad Yani No.35, Cipari, Cilacap, Indonesia, Phone: (0280) 622-6299, www.bmtelsejahtera.co.id</center></address>
    <!-- /.content -->
<!-- jQuery 2.2.3 -->
