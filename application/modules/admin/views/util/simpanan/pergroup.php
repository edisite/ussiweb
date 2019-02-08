<div class="row">
        <!-- left column -->
        <div class="col-md-8">
            <form role="form" data-parsley-validate>
          <!-- general form elements -->

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Data Transaksi</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <div class="box-body">
                 <div class="col-xs-3" >
                    <label>Tanggal Transaksi:</label>
                    <div class="input-group date">
                     <div class="input-group-addon">
                     <i class="fa fa-calendar"></i>
                     </div>
                    <input type="text" class="form-control input-sm" id="datepicker" value="<?php echo date('Y-m-d');?>">
                    </div>
                 </div>
                
                 <div class="col-xs-3" >
                  <label>No Kwitansi</label>
                  <input type="text" class="form-control input-sm" placeholder="Isi No Kuitansi" value="<?php echo $SETNOKWI; ?>">
                 </div>

                 <div class="col-xs-3" >
                  <label>Sandi</label>
                  <select class="form-control" name="fsandi">  
                      <option value="" selected></option>
                                    <?php if($SETSANDT){foreach ($SETSANDT as $sub_san_res) {
                                        echo "<option value=".$sub_san_res->sandi_kode.">".$sub_san_res->sandi_kode." - ".$sub_san_res->sandi_deskripsi."</option>";
                                    }}?>
                            </select>
                 </div>

                 <div class="col-xs-3" >
                  <label>Kolektor</label>
                  <select class="form-control" name="fkolektor">
                      <option value="" selected></option>
                                    <?php if($SETKOLEK){foreach ($SETKOLEK as $sub_kolek_res) {
                                        echo "<option value=".$sub_kolek_res->kode.">".$sub_kolek_res->kode." - ".$sub_kolek_res->deskripsi."</option>";
                                    }}?>
                            </select>
                 </div>

                <!-- /.input group -->
              </div>
			  
          <!-- /.box -->

          <div class="box box-primary">
            <!-- form start -->
             <div class="box-body">
                  <div class="checkbox">
                    <label style="font-weight: bold;">
                      <input type="checkbox">
                      Setoran VIA COA
                    </label>
                  </div>
				 <div class="box-header with-border">
				  <h3 class="box-title">Kode Perkiraan [COA]</h3>
				</div>
	
                 <div class="col-xs-3" >
                  <label>Kode Perkiraan</label>
                  <input type="text" class="form-control input-sm" placeholder="...">
                 </div>

                 <div class="col-xs-3" >
                  <label>&nbsp;</label>
                  <input type="text" class="form-control input-sm" placeholder="...">
                 </div>

                 <div class="col-xs-3" >
                  <label>&nbsp;
                  </label>
                  <select class="form-control input-sm" class="form-control">
                    <option>SETORAN</option>
                    <option>PENARIKAN</option>
                  </select>
                 </div>
                <!-- /.input group -->
              </div>
          <!-- /.box -->

          <!-- Main content -->  

    <div class="box box-primary">		  
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body" style="width:100%; height:350px; overflow:auto;">
              <table id="example2" class="table table-bordered table-hover">

                <thead>
                <tr>
                    
                  <th colspan="3" style="text-align: center;" >No Rekening dan Nama Nasabah</th>
                  <th colspan="2" style="text-align: center;" >Tabungan</th>
                </tr>
                </thead>
                
                <tbody>
                 <tr>
                   <th>No</th>
                   <th>No Rekening</th>
                   <th>Nama</th>
                   <th>Saldo Akhir</th>
                   <th>Jumlah Transaksi</th> 
                </tr> 
                <?php echo $SETTABLE; ?>
                

              </table>
            </div>
            <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Transaksi</button>
                <a href='tsimpanan/simpanan/simpkolektif_save' class='btn btn-primary' role='button' onclick="return confirm('Are you sure you want to save this item?');">Simpan</a>
                <a href='tsimpanan/simpanan/kolektif_cancel' class='btn btn-danger' role='button' onclick="return confirm('Are you sure you want to cancel this item?');">Selesai</a>
              </div>
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
  </div>
  <!-- /.content-wrapper -->
   
          <!-- general form elements -->
          
          </div><!-- class col-md6- -->
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<script src="../plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="../js/moment/moment.min.js"></script>


<script>
  $(function () {    //Initialize Select2 Elements
    //Date picker
    $('#datepicker').datepicker({
           format:'yyyy-mm-dd',
      autoclose: true
    });
  });

</script>
<script> 
    function convertToRupiah (objek) { 
        separator = "."; 
        a = objek.value; 
        b = a.replace(/[^\d]/g,""); 
        c = ""; 
        panjang = b.length; 
        j = 0; for (i = panjang; i > 0; i--) { 
        j = j + 1; if (((j % 3) == 1) && (j != 1)) { 
        c = b.substr(i-1,1) + separator + c; } else { 
        c = b.substr(i-1,1) + c; } } objek.value = c; 
    } 

    function convertToRupiahhh(angka){
       var rupiah = '';
       var angkarev = angka.toString().split('').reverse().join('');
       for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
       return rupiah.split('',rupiah.length-1).reverse().join('');
    }	
    function rupiah(){
        var nominal= document.getElementById("hutanganda").value;
        var rupiah = convertToRupiahhh(nominal);
        document.getElementById("hutanganda").value = rupiah;
    }
 </script>