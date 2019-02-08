<?php echo validation_errors(); ?>

<div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <form role="form" method="post" action="<?php echo base_url(); ?>admin/tpembelian/com/finance_purchase/" method="post">
          <!-- general form elements -->

          <div class="box box-primary">
              <div class="box-header with-border">
				<h3 class="box-title">FINANCE</h3>
			</div>
            <!-- form start -->
             <div class="box-body">
                 <div class="col-xs-6">
                     <p>Data Rincian Anda</p>
                     <div class="table">                         
                        <table class="table table-hover table-striped">
                          <tr>
                            <th width="30%">ID Kontrak</th>
                            <td width="5%">:</td>
                            <td><?php echo $custid; ?></td>
                          </tr>
                          <tr>
                            <th>Nama Nasabah</th>
                            <td>:</td>
                            <td><?php echo $cust_name; ?></td>
                          </tr>
                          <tr>
                            <th>No_installment</th>
                            <td>:</td>
                            <td><?php echo $noinstalmen; ?></td>
                          </tr>
                          <tr>
                            <th>Jatuh Tempo</th>
                            <td>:</td>
                            <td><?php echo $duedate; ?></td>
                          </tr>
                          <tr>
                            <th>Tagihan</th>
                            <td>:</td>
                            <td><?php echo $tagihan; ?></td>
                          </tr>
                          <tr>
                            <th>Biaya admin</th>
                            <td>:</td>
                            <td><?php echo $adm; ?></td>
                          </tr>
                        </table>
                      </div>
                 </div> 
                 <div class="col-xs-6">
                     <div class="table-striped"> 
                         <p>Pembayaran akan didebitkan dari rekening</p>
                        <table class="table table-striped">
                          <tr>
                            <td width="30%">Jenis Pembayaran:</td>
                            <td><?php echo $paytype; ?></td>
                          </tr>                          
                        </table>
                         <p>Dengan jumlah pembayara </p>
                         <table class="table table-striped">
                          <tr>
                              <td width="30%"><h3>Total</h3></td>
                            <td><h3><?php echo $total; ?></h3></td>
                          </tr>                          
                        </table>                         
                      </div>
                     <input type="hidden" name="nokontrak" id="nokontrak" value="<?php echo $custid; ?>">
                     <input type="hidden" name="paytype" id="paytype" value="<?php echo $paytype; ?>">
                     <input type="hidden" name="code" id="code" value="<?php echo $code; ?>">
                      <div class="box-footer">
                           <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah anda sudah yakin akan melakukan pembayaran?');"><i class="fa fa-save"></i> Bayar</button> 
<!--                          <a href="<?php echo base_url(); ?>admin/tpembelian/com/finance_purchase/<?php echo $code ?: ''; ?>" class="btn btn-success pull-right" onclick="return confirm('Apakah anda sudah yakin akan melakukan pembayaran?');"><i class="fa fa-save"></i> Bayar</a>
                    </div>-->
                 </div>
               
				</div>
		        <!-- /.input group -->
              </div>
			</div>
          <!-- /.box -->

        <!-- /.col -->
      <!-- /.row -->

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../../plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- bootstrap datepicker -->
<script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();
    //Date picker
    $('#datepicker').datepicker({
	  format:'dd/mm/yyyy',
      autoclose: true
    });
  });
</script>
</body>
</html>
