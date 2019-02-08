      <div class="row">
        <!-- left column -->
        <div class="col-md-8">
            <form role="form" data-parsley-validate>
          <!-- general form elements -->

          <div class="box box-primary">
              <div class="box-header">
				<h3 class="box-title">PLN Prabayar</h3>
			</div>
            <!-- form start -->
             <div class="box-body">
                 <div class="col-xs-5" >
                  <label>ID Rekening</label>
					<select class="form-control input-sm" class="form-control">
                      <option>100. .......</option>
                    </select>
				  <label>Transaction (TRX ID)</label>
					<input type="text" class="form-control input-sm">
				  <label>No. Meter/Customer ID</label>
					<input type="text" class="form-control input-sm">
				  <label>Nominal</label>
					<select class="form-control input-sm" class="form-control">
                      <option>25000</option>
					  <option>50000</option>
					  <option>100000</option>
					  <option>150000</option>
					  <option>200000</option>
					  <option>500000</option>
                    </select>
                 </div> 
				</div>
			<div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
				</div>
		        <!-- /.input group -->
              </div>
			</div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
  </div>
</div>
</form>
</div>
</div>
  <div class="control-sidebar-bg"></div>
</div>
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
