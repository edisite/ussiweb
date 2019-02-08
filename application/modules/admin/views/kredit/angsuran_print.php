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
  <link rel="stylesheet" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/skins/_all-skins.min.css">
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
            <small class="pull-right"><b>Tanggal : </b><?php echo date('d F Y H:i:s');?><br><br><p><b>AgentID :</b> 007612</p></small>
           </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
            <div class="row invoice-info">
		<div class="col-sm-12 invoice-col" align="center">
            <h2>BUKTI PEMBAYARAN ANGSURAN</h2><br>
        </div>
      </div>
      <!-- /.row --><div class="col-xs-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th>Nama</th>
              <td><?php echo $note_name_nsb ?: ''; ?></td>
            </tr>
            <tr>
              <th>No.Rekening</th>
              <td><?php echo $note_rekening ?: ''; ?></td>
            </tr>
            <tr>
              <th>Jatuh Tempo</th>
              <td><?php echo $note_due_date ?: ''; ?></td>
            </tr>
          </table>
        </div>
  </div>

<div class="col-xs-6">
  <div class="table-responsive">
    <table class="table">
      <tr>
        <th>Pokok</th>
        <td><?php echo $note_pokok ?: ''; ?></td>
      </tr>
      <tr>
        <th>Basil</th>
        <td><?php echo $note_basil ?: ''; ?></td>
      </tr>
      <tr>
        <th>Total</th>
        <td><?php echo $note_total ?: ''; ?></td>
      </tr>
    </table>
  </div>
</div>

<div class="col-xs-12">
  <div class="table-hover">
    <table class="table">
      <tr>
        <th>Angsuran Ke</th>
        <td><?php echo $note_angsuran; ?></td>
      </tr>
      <tr>
        <th>Nomor Transaksi</th>
        <td><?php echo $note_serial_n; ?></td>
      </tr>
    </table>
  </div>
</div>

	<div class="col-xs-2">
	  <p class="lead" align="center">Petugas,</p><br>
          <div>
		 <tr>
			<th><?php echo $this->session->userdata('USERNAME')?: ''; ?></th>
		  </tr>
	  </div>
          <br><br>
	  <div>
		 <tr>
			<th>_____________________</th>
		  </tr>
	  </div>
	</div>
	<div class="col-xs-8">
	</div>
		<div class="col-xs-2">
	  <p class="lead" align="center">Penerima,</p><br><br><br>
	  <div>
		  <tr>
			<th>_____________________</th>
		  </tr>
	  </div>
	</div>
  <address style="font-size: 70%"><center>BMT EL-SEJAHTERA, Jl. Jendral Ahmad Yani No.35, Cipari, Cilacap, Indonesia, Phone: (0280) 622-6299, www.bmtelsejahtera.co.id</center></address>
    <!-- /.content -->
    <div class="clearfix"></div>
  <div class="control-sidebar-bg"></div>
</div>
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>dist/js/demo.js"></script>
</body>
</html>