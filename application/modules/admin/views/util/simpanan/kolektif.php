<?php echo validation_errors(); ?>
<div class="row">
        <!-- left column -->
        <div class="col-md-10">
            
          <!-- general form elements -->
          <div class="box box-primary">
              <div class="box-header with-border">
          <h3 class="box-title">Simpanan Kolektif</h3>
        </div>
            <!-- form start -->
            <form role="form" data-parsley-validate action="" method="post">
             <div class="box-body">
                 <div class="col-xs-2" >
                    <label>Nomor Rekening:</label>
      
                 </div>
                 <div class="col-xs-4" >
                    
                     <input type="text" name="in_norek" <?php echo set_value('in_norek'); ?> class="form-control input-sm" placeholder="Isi No Rekening">
                 </div>
                 <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary">Cari</button>
                  </div>
             </div>
            </form>
          <form action="tsimpanan/simpanan/kolektif_ins_table" method="post" accept-charset="utf-8">
          <div class="box box-primary">
            <!-- form start -->
             <div class="box-body">
                 <div class="form-group">
                 <div class="col-xs-3" >
                    <label>Tanggal Transaksi:</label>
                    <div class="input-group date">
                     <div class="input-group-addon">
                     <i class="fa fa-calendar"></i>
                     </div>
                        <input type="text" class="form-control input-sm" id="datepicker" name="ftgl" value="<?php echo date('Y-m-d');?>">
                    </div>
                 </div>
                
                 <div class="col-xs-3" >
                  <label>No Kwitansi</label>
                  <input type="text" name="fkwitansi" class="form-control input-sm" placeholder="" value="<?php echo $SETNOKWI; ?>">
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
                 </div>
                <!-- /.input group -->
                 <div class="form-group">
                 <div class="col-xs-3" >
                  <label>No.Rekening</label>
                  <input type="text" name="fnorek" class="form-control input-sm" placeholder="..." value="<?php echo $SETNOREK; ?>">
                 </div>

                 <div class="col-xs-3" >
                  <label>Nama Nasabah</label>
                  <input type="text" name="fnama" class="form-control input-sm" placeholder="..." value="<?php echo $SETNAMAP; ?>">
                 </div>
				 
				 <div class="col-xs-6" >
                  <label>Alamat</label>
                  <input type="text" name="falamat" class="form-control input-sm" placeholder="..." value="<?php echo $SETADDRS; ?>">
                 </div>
                <!-- /.input group -->
              </div>
                 <div class="form-group">
                 <div class="col-xs-3" >
                  <label>Saldo</label>
                  <input type="text" name="fsaldo" class="form-control input-lg" placeholder="..." value="<?php echo $SETNOMIN; ?>">
                 </div>

                 <div class="col-xs-3" >
                  <label>Jml.Setoran</label>
                  <input type="text" name="fjmlsetoran"  class="form-control input-lg" placeholder="...">
                 </div>
				 
                 <div class="col-xs-3" >
                  <label>Jml.Penarikan</label>
                  <input type="text" name="fjmlpenarikan"  class="form-control input-lg" placeholder="...">
                 </div>
				 
                 <div class="col-xs-3">
                    <label></label>
                    <button class="help-block input-lg" <?php echo $SETBUTON; ?>>Masuk ke table</button>
                 </div>
                <!-- /.input group -->
              </div>
          </div>
          </div>
        </form>

          <!-- /.box -->

          <!-- Main content -->  

          <div class="box box-primary">		  
			  <div class="row">
				<div class="col-xs-12">
                                    <div class="form-group">
					<div class="box-body">
					  <table id="example2" class="table table-bordered table-striped table-condensed ">
						<tbody>
						 <tr>
                                                   <th width="4%">No.</th>
						   <th width="13%">No.Rekening</th>
						   <th width="20%">Nama</th>
						   <th width="15%" align="center">Saldo</th>
						   <th width="15%" align="center">Setoran</th>
						   <th width="15%" align="center">Penarikan</th> 
                                                   <th width="10%" align="center"></th> 
						</tr> 
						<?php echo $SETTABLE; ?>
						</tbody>
					  </table>
					</div>
					<!-- /.box-body -->
					  <div class="box-footer">
						<!--<button type="submit" class="btn btn-primary">Simpan</button>-->
                                                <a href='tsimpanan/simpanan/simpkolektif_save' class='btn btn-primary' role='button' onclick="return confirm('Are you sure you want to save this item?');">Simpan</a>
                                                <a href='tsimpanan/simpanan/kolektif_cancel' class='btn btn-danger' role='button' onclick="return confirm('Are you sure you want to cancel this item?');">Batal</a>
					  </div>
				  <!-- /.box -->
				</div>
                                </div>
				<!-- /.col -->
			  </div>
			  <!-- /.row -->
		  </div>
  <!-- /.content-wrapper -->
   
          <!-- general form elements -->
          
          </div><!-- class col-md6- -->
          <!-- /.form-group -->
      </div>
      <!-- /.tab-pane -->
      
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