<?php echo validation_errors(); ?>
<div class="row">
        <!-- left column -->
        <div class="col-md-10">
            
          <!-- general form elements -->
          
          <form action="tsimpanan/simpanan/Kolektif_upd" method="post" accept-charset="utf-8">
          <div class="box box-primary">
              <div class="box-header with-border">
          <h3 class="box-title">Update</h3>
        </div>
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
                                    <?php foreach ($SETSANDT as $sub_san_res) {
                                        if($sub_san_res->sandi_kode == $SETSANDI){
                                             $select = " selected";
                                         }else{
                                             $select = "";
                                         }
                                        echo "<option value=".$sub_san_res->sandi_kode." ".$select.">".$sub_san_res->sandi_kode." - ".$sub_san_res->sandi_deskripsi."</option>";
                                     }?>
                            </select>
                 </div>

                 <div class="col-xs-3" >
                  <label>Kolektor</label>
                  <select class="form-control" name="fkolektor">
                      <option value="" selected></option>
                                    <?php foreach ($SETKOLEK as $sub_kolek_res) {
                                         if($sub_kolek_res->kode == $SETKOLET){
                                             $select = " selected";
                                         }else{
                                             $select = "";
                                         }
                                        echo "<option value=".$sub_kolek_res->kode." ".$select.">".$sub_kolek_res->kode." - ".$sub_kolek_res->deskripsi."</option>";
                                     }?>
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
                  <input type="text" name="fjmlsetoran"  class="form-control input-lg" placeholder="..." value="<?php echo $SETSETOR; ?>">
                 </div>
				 
                 <div class="col-xs-3" >
                  <label>Jml.Penarikan</label>
                  <input type="text" name="fjmlpenarikan"  class="form-control input-lg" placeholder="..." value="<?php echo $SETTARIK; ?>">
                 </div>
                     <input type="hidden" name="ftid" value="<?php echo $SETTRAID; ?>">
				 
                 <div class="col-xs-3">
                    
                 </div>
                <!-- /.input group -->
              </div>
          </div>
            <div class="box-footer">
						<button type="submit" class="btn btn-primary">Update</button>
					  </div>
          </div>
              
        </form>

          <!-- /.box -->

          <!-- Main content -->  
 
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