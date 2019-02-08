<?php echo validation_errors(); ?>

<div class="row">
        <!-- left column -->
        <div class="col-md-8">
            <form role="form" method="post">
          <!-- general form elements -->

          <div class="box box-primary">
              <div class="box-header with-border">
				<h3 class="box-title">PLN TOKEN</h3>
			</div>
            <!-- form start -->
             <div class="box-body">
                 <div class="col-xs-16" >
                     <p>Anda akan membeli Token PLN dengan rincian sebagai berikut: </p>
		 
                    <label>PULSA PLN
                    <input type="text" class="form-control input-lg" value="<?php echo $aliasname; ?>" readonly></label>
                    <label>PRODUCT ID
                        <input type="text" class="form-control input-lg" name="productid" value="<?php echo $productid; ?>" readonly></label>
                    <label>HARGA
                    <input type="text" class="form-control input-lg" value="<?php echo $pricesell; ?>" readonly></label>

                    <p>Wajib di isi:  </p>
                    <label>Jenis Pembayaran</label>
                    <select class="form-control input-sm" multiple="" name="jenis">
                        <option value="agent" selected>Cash</option>
                        <option value="nasabah" disabled>Debit Tabungan Nasabah</option>                    
                    </select>
                    <label>No. Meter/Customer ID</label>
                    <input type="text" name="nometer" class="form-control input-lg dat">
                    </div>
                 </div> 

			<div class="box-footer">
                <button type="submit" class="btn btn-primary">Proses</button>
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
