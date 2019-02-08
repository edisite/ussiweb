      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <form role="form" data-parsley-validate>
          <!-- general form elements -->

          <div class="box box-primary">
              <div class="box-header">
				<h3 class="box-title">Info</h3>
			</div>
            <!-- form start -->
             <div class="box-body">
                 <div class="col-xs-5" >
                  <label>ID Nasabah</label>
					<select class="form-control input-sm" class="form-control">
                      <option><option>
					  <option>100. ...........</option>
					  <option>101. ...........</option>
					  <option>102. ...........</option>					  
                    </select>
                  <label>Nama</label>
					<input type="text" class="form-control input-sm">
                  <label>Alamat</label>
					<textarea type="text" class="form-control input-sm"></textarea>
				  <label>Pekerjan</label>
					<select class="form-control input-sm" class="form-control">
                      <option>Pegawai</option>
					  <option>Mahasiswa</option>
					  <option>Ibu Rumah Tangga</option>					  
                    </select>
				  <label>Penerimaan</label>
					<input type="text" class="form-control input-sm">
				  <label>Pengeluaran</label>
					<input type="text" class="form-control input-sm">

                 </div> 
                 
				</div>
				</div>
		        <!-- /.input group -->
              </div>
			</div>
			<div class="box-footer">
                <button type="submit" class="btn btn-primary">Update</button>
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
