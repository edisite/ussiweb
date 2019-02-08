   <div class="row">
        <!-- left column -->
        <div class="col-md-8">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Jenis Transaksi</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-body">
              <div class="form-group col-md-6">
                  <label>Kode Transaksi</label>
                  <select class="form-control">
                    <option>400- Pengambilan Titipan Basil Deposito Tunai</option>
                  </select>
                </div>
              </div>
              <!-- /.box-body -->
          
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
                      <input type="file" id="exampleInputFile">
                      <p class="help-block">Photo dan Tanda Tangan</p>
                    </div>
                 </div>
				
					<!-- /.input group -->
					<div class="col-xs-4">
                                            <label>Nominal</label>
                                            <input id="idnominal" type="text" class="form-control input-sm" placeholder="0.00" value="<?php echo $SETNOMIN; ?>">
                                          </div>
					<div class="col-xs-4">
					  <label>Titipan</label>
					  <input type="text" class="form-control input-sm" placeholder="0.00">
					</div>
              <!-- /.box-body -->
          </div>

            
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Data Transaksi</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
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
                    <div class="col-xs-7">
                      <label>Jumlah Penarikan</label>
                      <input id="idtarik" type="text" class="form-control input-sm" placeholder="0.00">
                    </div>
                    
                </div>
                <div class="form-group">
                    <div class="col-xs-7" >
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
          <!-- /.box -->

          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">OB ke Rekening Simpanan</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body">
					<div class="col-xs-4" >
					  <label>No Rekening</label>
					  <input type="text" class="form-control input-sm" placeholder="Isi No Rekening">
					</div>
					<div class="col-xs-4" >
					  <label>&nbsp;</label>
					  <input type="text" class="form-control input-sm" placeholder="...">
					</div>
					<div class="col-xs-4" >
					  <label style="color: red;">Saldo Simpanan</label>
					  <input style="font-size: 20px;" type="text" class="form-control input-sm" placeholder="0.00">
					</div>
              </div>
              <!-- /.box-body -->
              
              <!-- general form elements -->
			<div class="box box-primary">
				  <div class="box-header with-border">
					<h3 class="box-title">Kode Perkiraan (COA)</h3>
				  </div>
				<!-- /.box-header -->
				<!-- form start -->
				  <div class="box-body">
						<div class="col-xs-6" >
						  <label>No Rekening</label>
						  <input type="text" class="form-control input-sm" placeholder="Isi No Rekening">
						</div>
						<div class="col-xs-6" >
						  <label>&nbsp;</label>
						  <input type="text" class="form-control input-sm" placeholder="...">
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