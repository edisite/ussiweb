<?php
date_default_timezone_set('Asia/Jakarta');
?>

<style>
	body, table{font-family:Arial, Helvetica;font-size:13px !important;}
	.title{font-size:18px;}
    .eng{font-family:Arial, Helvetica;font-size:9px;font-style:italic;}
</style>
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
		<div class="col-sm-12 invoice-col" align="center">
            <h2>BUKTI PEMBELIAN PULSA</h2><br>
        </div>
        
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <div class="row">
      <div class="col-md-12">
        <table class="table">
          <thead>
          <tr>
            <th>No. Handphone</th>           
            <th>Produk</th>            
            <th>SN</th>
            <th>Harga</th>
          </tr>
          </thead>
          <tbody>
        <tr>
         <td><?php echo $msisdn; ?></td>
         <td><?php echo $produk; ?></td>
         <td><?php echo $token; ?></td>
         <td>Rp <?php echo $harga; ?></td>
        </tr>
       </tbody>
        </table>
	  </div>
	</div>
      <div class="col-xs-1"></div>
	<div class="col-xs-2">
	  <p class="lead" align="center"></p><br>
          <div>
		 <tr>
                     <th></th>
		  </tr>
	  </div>
          <br><br>
	  <div>
		 <tr>
			<th></th>
		  </tr>
	  </div>
	</div>
	<div class="col-xs-4">
	</div>
		<div class="col-xs-2">
	  <p class="lead" align="center">Pegawai,</p><br><br><br>
          <div>
		  <tr>
			<th>_____________________</th>
		  </tr>
	  </div>    
          <div>
		 <tr>
                     <th align="center"><b><?php echo $this->session->userdata('username')?: 'PUTUT'; ?></b></th>
		  </tr>
	  </div>
	  
	</div>
      <div class="col-xs-2"></div>
  
<div class="row no-print">
        <div class="col-xs-12">
            <a href="<?php echo base_url(); ?>admin/tpembelian/com/pulsa_print/<?php echo $note_code ?: ''; ?>" target="_blank" class="btn btn-success pull-right"><i class="fa fa-print"></i>Print</a>         
        </div>
      </div>
    </section>