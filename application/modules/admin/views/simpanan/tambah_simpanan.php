
<div class="row">
        <!-- left column -->
    <div class="col-md-8">
        
          <!-- general form elements -->
          <form role="form" method="post" action="bo_simpanan/Data_master_simpanan/add_form/<?php echo $SETNASABAHID; ?>" accept-charset="utf-8">
            
            <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Tambah Nasabah Simpanan</h3>
            </div>
                <?php
                if(validation_errors()){                    
                ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo validation_errors();?>
                </div>
                <?php 
                }
                ?>
            <!-- form start -->
            <div class="box-body">
            <div class="form-group col-md-12">
                <div class="row">
                    <div class="form-group">
                      <div class="col-xs-5" >
                        <label>Nasabah ID</label>
                        <input type="text" class="form-control input-sm" value="<?php echo $SETNASABAHID;?>" name="fnasabahid">
                      </div>
                      <div class="col-xs-7">
                        <label>Nama</label>
                        <input type="text" class="form-control input-sm" value="<?php echo $SETNAMANASAB;?>" name="fnamanasabah">
                      </div>
                      <div class="col-xs-12" >
                        <label>Alamat</label>
                        <input type="text" class="form-control input-sm" value="<?php echo $SETALAMATNSB;?>" name="falamat">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-xs-4" >
                         <label>Kode Integrasi</label>
                            <select class="form-control" name="fkdintegrasi">  
                                <option value="" selected></option>
                                <?php if($SETINTEGRASI){foreach ($SETINTEGRASI as $sub_san_res) {
                                    echo "<option value=".$sub_san_res->kode.">".$sub_san_res->kode." - ".$sub_san_res->deskripsi."</option>";
                                }}?>
                            </select>
                      </div>
                      <div class="col-xs-4" >
                       <label>Kode Produk</label>
                        <select class="form-control" name="fkdproduk">  
                                <option value="" selected></option>
                                <?php if($SETKODPRODUK){foreach ($SETKODPRODUK as $sub_san_res) {
                                    echo "<option value=".$sub_san_res->kode.">".$sub_san_res->kode." - ".$sub_san_res->deskripsi."</option>";
                                }}?>
                            </select>
                      </div>
                      <div class="col-xs-4" >
                        <label>Kode Kantor</label>
                        <select class="form-control" name="fkdkantor">  
                                <option value="" selected></option>
                                <?php if($SETKODKANTOR){foreach ($SETKODKANTOR as $sub_san_res) {
                                    echo "<option value=".$sub_san_res->KODE_KANTOR.">".$sub_san_res->KODE_KANTOR." - ".$sub_san_res->NAMA_KANTOR."</option>";
                                }}?>
                            </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-xs-4" >
                         <label>No Bilyet</label>
                         <input type="text" class="form-control input-sm" name="fnobilyet">
                      </div>
                      <div class="col-xs-2" >
                       <div class="checkbox">
                          <label>
                              <input type="checkbox" name="fbankaktiva">Antar Bank Aktiva
                          </label>
                            </div>
                            
                      </div>
                      <div class="col-xs-6" >
                        <label>Saldo Saat Ini</label>
                        <input type="text" class="form-control input-sm" name="fsaldo" value="0.00">
                      </div>
                    </div>
                </div>
            </div>
        </div>              	  
        <div class="box-body">
            <div class="form-group col-md-12">
                <div class="row">
                <div class="col-xs-4" >
                  <label>Kode Pemilik</label>
                        <select class="form-control" name="fkdpemilik">  
                            <option value="" selected></option>
                            <?php if($SETKDPEMILIK){foreach ($SETKDPEMILIK as $sub_san_res) {
                                echo "<option value=".$sub_san_res->KODE_PEMILIK.">".$sub_san_res->KODE_PEMILIK." - ".$sub_san_res->DESKRIPSI_PEMILIK."</option>";
                            }}?>
                        </select>
                </div>
                    <div class="col-xs-4">
                  <label>Hubungan dgn Bank</label>
                            <select class="form-control" name="fhubbank">  
                            <option value="" selected></option>
                            <?php if($SETKDHUBBANK){foreach ($SETKDHUBBANK as $sub_san_res) {
                                echo "<option value=".$sub_san_res->kode_hubungan.">".$sub_san_res->kode_hubungan." - ".$sub_san_res->deskripsi_hubungan."</option>";
                            }}?>
                        </select>
                    </div>
                  <div class="col-xs-4">
                  <label>Metode Basil Dana</label>
                        <select class="form-control" name="fmetode">  
                            <option value="" selected></option>
                            <?php if($SETKDMTDBASL){foreach ($SETKDMTDBASL as $sub_san_res) {
                                echo "<option value=".$sub_san_res->kode_metode.">".$sub_san_res->kode_metode." - ".$sub_san_res->deskripsi_metode."</option>";
                            }}?>
                        </select>
                  </div>
                    <div class="col-xs-4">
                  <label>AO</label>
                        <select class="form-control" name="fao">  
                            <option value="" selected></option>
                            <?php if($SETKODGROUP1){foreach ($SETKODGROUP1 as $sub_san_res) {
                                echo "<option value=".$sub_san_res->kode.">".$sub_san_res->kode." - ".$sub_san_res->deskripsi."</option>";
                            }}?>
                        </select>
                    </div>
                    <div class="col-xs-4">
                  <label>Wilayah</label>
                        <select class="form-control" name="fwilayah">  
                            <option value="" selected></option>
                            <?php if($SETKODGROUP2){foreach ($SETKODGROUP2 as $sub_san_res) {
                                echo "<option value=".$sub_san_res->kode.">".$sub_san_res->kode." - ".$sub_san_res->deskripsi."</option>";
                            }}?>
                        </select>
                    </div>
                    <div class="col-xs-4">
                    <label>Profesi</label>
                          <select class="form-control" name="fprofesi">  
                            <option value="" selected></option>
                            <?php if($SETKODGROUP3){foreach ($SETKODGROUP3 as $sub_san_res) {
                                echo "<option value=".$sub_san_res->kode.">".$sub_san_res->kode." - ".$sub_san_res->deskripsi."</option>";
                            }}?>
                        </select>
                    </div>
                    <div class="col-xs-5" >
                        <label>Tanggal Register</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                            </div>
                            <input name="ftglreg" type="text" class="form-control input-sm" id="datepicker" value="<?php echo date('Y-m-d');?>">
                        </div>
                    </div>				
                </div>
            </div> 
       </div>
          <!-- /.box-body -->
        <div class="form-group">
          <div class="box-footer">
                  <button type="submit" class="btn btn-info">Lanjut</button>
                  <button type="" class="btn btn-primary">Batal</button>
          </div>
        </div>		
	</div>
          <!-- /.form-group -->
    </form>
    </div>
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
