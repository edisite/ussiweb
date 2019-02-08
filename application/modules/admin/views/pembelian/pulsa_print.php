<?php
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
</head>
<body onload="window.print();">
<div class="wrapper">
    <!-- Main content -->
   <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
			<img src="<?php echo base_url(); ?>assets/images/logo_bmt.jpg" width="100px" height="50px"/> BMT EL-SEJAHTERA
<small class="pull-right"><b>Tanggal : </b><?php echo date('d F Y H:i:s');?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-xs-12" align="center">
             <h2>BUKTI PEMBELIAN TOKEN LISTRIK</h2><br>
        </div>
      </div>
      <!-- /.row -->
   <div class="row">
      <div class="col-md-12">
        <table class="table">
          <thead>
          <tr>
            <th>No.Meter</th>
            <th>Nama Pelangan</th>            
            <th>Produk</th>            
            <th>Nominal</th>
            <th>Token</th>
          </tr>
          </thead>
          <tbody>
        <tr>
         <td><?php echo $plnid; ?></td>
         <td><?php echo $nama; ?></td>
         <td><?php echo $produk; ?></td>
         <td><?php echo $harga; ?></td>
         <td><?php echo $token; ?></td>
        </tr>
       </tbody>
        </table>
	  </div>
	</div>

  <div class="col-xs-12">
    <div class="table-responsive">
    <table class="table">
    </table>
    </div>
  </div>
  <div class="col-xs-3">
    <p class="lead" align="center">Petugas,</p><br><br><br>
    <div class="table-responsive">
    <table class="table">
      <tr>
      <th>___________________</th>
      </tr>
    </table>
    </div>
  </div>
  <div class="col-xs-6">
  </div>
    <div class="col-xs-3">
    <p class="lead" align="center">Penerima,</p><br><br><br>
    <div class="table-responsive">
    <table class="table">
      <tr>
      <th>___________________</th>
      </tr>
    </table>
    </div>
  </div>
    <div class="col-xs-12">
    <div class="table-responsive">
    <table class="table">
    </table>
    </div>
  </div>
  <address style="font-size: 70%"><center>BMT EL-SEJAHTERA, Jl. Jendral Ahmad Yani No.35, Cipari, Cilacap, Indonesia, Phone: (0280) 622-6299, www.bmtelsejahtera.co.id</center></address>
    <!-- /.content -->
    <div class="clearfix"></div>
  <div class="control-sidebar-bg"></div>
<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
</body>
</html>