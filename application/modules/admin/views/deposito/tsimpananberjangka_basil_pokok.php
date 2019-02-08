 <div class="row">
        <!-- left column -->
        <div class="col-md-7">
            <form role="form" data-parsley-validate>
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Jenis Transaksi</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
              <div class="form-group col-md-6">
                  <label>Kode Transaksi</label>
                  <select class="form-control">
                    <option>304 - Pengambilan Basil OB ke Nominal (Pokok) </option>
                  </select>
                </div>
              </div>
          
              <!-- /.box-body -->
            <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Data Rekening</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    <div class="col-xs-4" >
                      <label>No Rekening</label>
                      <input type="text" class="form-control input-sm" value="<?php echo $SETNOREK; ?>">
                    </div>
                    <div class="col-xs-6" >
                      <label>Jenis Tabungan</label>
                      <input type="text" class="form-control input-sm" value="<?php echo $SETPRODK; ?>">
                    </div>
                  </div>
                <div class="form-group">
                  <div class="col-xs-4" >
                    <label>Nasabah ID</label>
                    <input type="text" class="form-control input-sm" value="<?php echo $SETNSBID; ?>">
                  </div>
                  <div class="col-xs-6" >
                    <label>Nama Nasabah</label>
                    <input type="text" class="form-control input-sm" value="<?php echo $SETNAMAP; ?>">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-xs-12" >
                    <label>Alamat</label>
                    <input type="text" class="form-control input-sm" value="<?php echo $SETADDRS; ?>">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-xs-4">
                    <label>Tgl Register</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control input-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value="<?php echo $SETTGLMU; ?>">
                    </div>
                  </div>
                  <div class="col-xs-4">
                    <label>Jatuh Tempo</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control input-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value="<?php echo $SETTGLMU; ?>">
                    </div>
                  </div>
                  <div class="col-xs-4">
                      <label for="exampleInputFile"></label>
                      <input type="file" id="exampleInputFile" class="form-control btn btn-upload">

                      <p class="help-block">Photo dan Tanda Tangan</p>
                    </div>
                 </div>
                <div class="form-group">
                    <div class="col-xs-4">
                      <label>Nominal</label>
                      <input id="idnominal" type="text" class="form-control input-sm" placeholder="0.00" value="<?php echo $SETNOMIN; ?>">
                    </div>
                    <div class="col-xs-4">
                      <label>Bunga</label>
                      <input id="idbunga" type="text" class="form-control input-sm" placeholder="0.00" value="<?php echo $SETBUNGA; ?>">
                    </div>
                    <div class="col-xs-4">
                      <label>Pajak</label>
                      <input id="idpajak" type="text" class="form-control input-sm" placeholder="0.00" value="<?php echo $SETPAJAK; ?>">
                    </div>         
                </div>
            </div>
            
            <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Data Transaksi</h3>
            </div>            
            <div class="box-body">
              <div class="form-group">
                <div class="col-xs-4" >
                  <label>Tanggal Transaksi:</label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control input-sm" id="datepicker">
                  </div>
                </div>
                  <div class="col-xs-4" >
                    <label>No Kwitansi</label>
                     <input type="text" class="form-control input-sm">
                  </div>
                  <div class="col-xs-4" >
                        <label>Select</label>
                        <select class="form-control input-sm">
                        </select>
                    </div>
              </div>  
                
                <div class="form-group">
                    <div class="col-xs-4">
                      <label>Jumlah Penarikan</label>
                      <input id="idtarik" type="text" class="form-control input-sm" placeholder="0.00">
                    </div>
                    <div class="col-xs-4">
                      <label>Administrasi</label>
                      <input id="idadm" type="text" class="form-control input-sm" placeholder="0.00">
                    </div>
                    <div class="col-xs-4">
                      <label>Bagi Hasil</label>
                      <button type="submit" class="form-control btn-danger">Hitung</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-6" >
                      <label>TOTAL</label>
                      <input type="text" class="form-control input-sm"  placeholder="0.00">
                       <input type="hidden" name="stotal" id="stotal">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-6">
                        <label for="comment">Keterangan:</label>
                        <textarea class="form-control input-sm" rows="2" id="comment"><?php echo $SETDESCR; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-6">
                        <label></label>
                    </div>
                    <div class="col-xs-6">
                      <label>Saldo Setelah Transaksi</label>
                      <input type="text" class="form-control " placeholder="0.00 ">
                    </div>
                </div>
                <!-- /.input group -->
            </div>
            <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Kode Perkiraan TDP</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body">
                <div  class="col-xs-5" >
                  <label>No Rekening</label>
                  <input type="text" class="form-control input-sm" placeholder="Isi No Rekening">
                </div>
                <div class="col-xs-5" >
                  <label></label>
                  <input type="text" class="form-control input-sm" placeholder="Bagi Hasil Deposito">
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                  <div  class="col-xs-5" >
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="submit" class="btn btn-primary">Keluar</button>
                  </div>
              </div>
            </form>
          </div>
        </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
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
      function caltotal()
    {
        vat totalValue = 0;
          var tarik = document.getElementById('idtarik').value;
          var adm = document.getElementById('idadm' + i + '').value;
          totalValue = (price/per_pack)*quantity;
          
        document.getElementById('Total').value = totalValue.toFixed(2);
    }
    </script>