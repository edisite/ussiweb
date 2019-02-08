<!--        $this->mViewData['SETNOREK']         = $out_norek;
        $this->mViewData['SETNSBID']         = $out_nasabah_id;
        $this->mViewData['SETNAMAP']         = $out_nama_nasabah;
        $this->mViewData['SETADDRS']         = $out_alamat;
        $this->mViewData['SETPRODK']         = $out_des_produk;
        $this->mViewData['SETTGLMU']         = $out_tgl_mulai;
        $this->mViewData['SETTGLJT']         = $out_tgl_jt;
        $this->mViewData['SETNOMIN']         = $out_nominal;
        $this->mViewData['SETDESCR']         = "Setoran Tabungan tunai an: ".$out_norek."[".$out_nama_nasabah."]";-->
<div class="row">
   <!-- left column -->
   <div class="col-md-8">
<form role="form" data-parsley-validate>
     <!-- general form elements -->
     <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Jenis Transaksi</h3>
        </div>
        <div class="box-body">
          <div class="form-group col-md-6">
            <label>Kode Transaksi</label>
            <select class="form-control">
              <option>100 - Setoran Pokok Deposito Tunai </option>
            </select>
          </div>
        </div>
     <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Data Rekening</h3>
        </div>
        <div class="box-body"> 
                <div class="form-group">
                         <div class="col-xs-4" >
                           <label>No Rekening</label>
                           <input type="text" class="form-control input-sm" value="<?php echo $SETNOREK; ?>" placeholder="">
                         </div>
                         <div class="col-xs-4">
                           <label>&nbsp;</label>
                           <input type="text" class="form-control input-sm" value="<?php echo $SETPRODK; ?>" placeholder="">
                         </div>
                         <div class="col-xs-4">
                           <label>No Alternatif</label>
                           <input type="text" class="form-control input-sm" value="<?php echo $SETALTER; ?>" placeholder="">
                         </div>
                 </div>
                    <div class="form-group">
                            <div class="col-xs-4">
                              <label>Nasabah ID</label>
                              <input type="text" class="form-control input-sm" value="<?php echo $SETNSBID; ?>" placeholder="">
                            </div>
                            <div class="col-xs-4">
                              <label>&nbsp;</label>
                              <input type="text" class="form-control input-sm" value="<?php echo $SETNAMAP; ?>" placeholder="">
                            </div>
                            <div class="col-xs-4">
                              <label for="exampleInputFile"></label>
                              <input type="file" id="exampleInputFile">
                              <p class="help-block">Photo dan Tanda Tangan</p>
                            </div>
                    </div>
                <div class="form-group">
                            <div class="col-xs-12" >
                                <label>Alamat</label>
                              <input type="text" class="form-control input-sm" value="<?php echo $SETADDRS; ?>" placeholder="">
                            </div>
                            
                </div>
                    <div class="form-group">
                            <div class="col-xs-4" >
                                <label>Tanggal Register:</label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                  </div>
                                  <input type="text" class="form-control input-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value="<?php echo $SETTGLMU; ?>">
                                </div>
                            </div>
                            <div class="col-xs-4" >
                                <label>Jatuh Tempo:</label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                  </div>
                                  <input type="text" class="form-control input-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value="<?php echo $SETTGLJT; ?>">
                                </div>
                            </div>
                            <div class="col-xs-4">
                              <label>Saldo Tabungan</label>
                              <input type="text" class="form-control input-lg" value="<?php echo $SETNOMIN; ?>" placeholder="">
                            </div>
                    </div>
            </div>

   <div class="box box-primary">
       <div class="box-header with-border">
         <h3 class="box-title">Data Transaksi</h3>
       </div>
       <!-- /.box-header -->

       <!-- form start -->
       <div class="box-body">
                    <div class="form-group">
                        <div class="col-xs-6" >
                                <label>Tanggal Transaksi:</label>
                                <div class="input-group date">
                                        <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                        </div>
                                  <input type="text" class="form-control input-sm" id="datepicker">
                                </div>
                        </div>
                        <div class="col-xs-6" >
                                <label>Sandi</label>
                            <select class="form-control" name="usrid">
                                    <?php foreach ($SETSANDT as $sub_san_res) {
                                        echo "<option value=".$sub_san_res->sandi_kode.">".$sub_san_res->sandi_kode." - ".$sub_san_res->sandi_deskripsi."</option>";
                                     }?>
                            </select>
                        </div>
                    </div>
           <div class="form-group">
                        <div class="col-xs-6">
                                  <label>No Kwitansi</label>
                                  <input type="text" class="form-control input-sm" placeholder="Isi No Kwitansi">
                        </div>
                        <div class="col-xs-6">
                                <label>Kolektor</label>
                            <select class="form-control" name="usrid">
                                    <?php foreach ($SETKOLEK as $sub_kolek_res) {
                                        echo "<option value=".$sub_kolek_res->kode.">".$sub_kolek_res->kode." - ".$sub_kolek_res->deskripsi."</option>";
                                     }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                            <div class="col-xs-6">
                                    <label>Jumlah Setoran</label>
                                    <input type="text" class="form-control input-sm" placeholder="0.00">                                   
                            </div>
                        
                        <div class="col-xs-6">                                   
                                    <label>Jumlah Penarikan</label>
                                    <input type="text" class="form-control input-sm" placeholder="0.00">         
                            </div>
                        <div class="col-xs-6">
                                    <label>Adm. Penutupan</label>
                              <input type="text" class="form-control input-sm" placeholder="0.00">
                            </div>
                    </div>  
                    <div class="form-group">
                            <div class="col-xs-6" >
                              <label>Total Diterima</label>
                              <input style="font-weight: bold; font-size: 15px;" type="text" class="form-control input-sm"  placeholder="0.00">
                            </div>
                            <div class="col-xs-6" >
                              <label>Pajak</label>
                              <input style="font-weight: bold; font-size: 15px;" type="text" class="form-control input-sm"  placeholder="0.00">
                            </div>
                            <div class="col-xs-6">
                              <label>Keterangan</label>
                                    <textarea class="form-control" rows="2" id="comment"><?php echo $SETDESCR; ?></textarea>
                            </div>
                            <div class="col-xs-6"><br><br>
                              <label>Status :Aktif</label>
                            </div>
                            <div class="col-xs-6">
                              <label>&nbsp;</label>
                            </div>
                            <div class="col-xs-4">
                              <label>Saldo Tabungan Setelah Transaksi</label>
                              <input style="font-size: 15px;" type="text" class="form-control " placeholder="0.00 ">
                            </div>
                    </div>
       </div>
                <!-- /.input group -->
    </div>
    </div>
     <!-- /.box -->

     <!-- general form elements -->
     <div class="box box-primary">
       <div class="box-header with-border">
         <h3 class="box-title">OB ke Rekening Simpanan</h3>
       </div>
       <!-- /.box-header -->
       <!-- form start -->
         <div class="box-body" >
                             <div class="row">
                                   <div class="form-group col-md-12">
                                           <div class="col-xs-4" >
                                             <label>No Rekening</label>
                                             <input type="text" class="form-control input-sm" placeholder="Isi No Rekening">
                                           </div>
                                           <div class="col-xs-4" >
                                             <label>&nbsp;</label>
                                             <input type="text" class="form-control input-sm" placeholder="...">
                                           </div>
                                           <div class="col-xs-4" >
                                             <label style="color: red;">Saldo Tabungan</label>
                                             <input style="font-size: 20px;" type="text" class="form-control input-sm" placeholder="0.00">
                                           </div>
                                   </div>	
                                   </div>
         </div>
         <!-- /.box-body -->

         <!-- general form elements -->
     <div class="box box-primary">
       <div class="box-header with-border">
         <h3 class="box-title">Kode Perkiraan (COA)</h3>
       </div>
       <!-- /.box-header -->
         <div class="box-body">
                             <div class="row">
                             <div class="form-group col-md-12">
                                           <div class="col-xs-4">
                                             <label>No Rekening</label>
                                             <input type="text" class="form-control input-sm" placeholder="Isi No Rekening">
                                           </div>
                                           <div class="col-xs-8">
                                             <label>&nbsp;</label>
                                             <input type="text" class="form-control input-sm" placeholder="...">
                                           </div>
                                   </div>
                                   </div>
         </div>
         <!-- /.box-body -->

         <div class="box-footer">
           <button type="submit" class="btn btn-primary">Submit</button>
         </div>
                   </div>
                   </div>
 </div>
 <!-- /.tab-pane -->
     </form>
</div>
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