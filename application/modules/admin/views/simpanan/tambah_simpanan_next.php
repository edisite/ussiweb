<div class="row">
        <!-- left column -->
    <div class="col-md-8">
        
          <!-- general form elements -->
          <form role="form" method="post" action="bo_simpanan/Data_master_simpanan/add_form2" accept-charset="utf-8">
            
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
                      <div class="col-xs-3" >
                        <label>Nasabah ID</label>
                        <input type="text" class="form-control input-sm" value="<?php echo $SET_NASABAHID;?>" name="fnasabahid" readonly>
                        <input type="hidden" class="form-control input-sm" value="<?php echo $SET_NASABAHID;?>" name="fnasabahid_h">
                      
                      </div>
                        <div class="col-xs-4" >
                        <label>Nomor Rekening</label>
                        <input type="text" class="form-control input-sm" value="<?php echo $SET_NOREKENING;?>" name="fnorek" readonly>
                        <input type="hidden" class="form-control input-sm" value="<?php echo $SET_NOREKENING;?>" name="fnorek_h">
                      
                        </div>
                      <div class="col-xs-7">
                        <label>Nama</label>
                        <input type="text" class="form-control input-sm" value="<?php echo $SET_NAMANSBAH;?>" name="fnamanasabah" readonly>
                        <input type="hidden" class="form-control input-sm" value="<?php echo $SET_NAMANSBAH;?>" name="fnamanasabah_h">
                      </div>
                      
                      
                    </div>
                </div>
                </div>
                
                <div class="form-group col-md-12">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-xs-3">
                                    <label>Nisbah &nbsp; &nbsp;%</label>
                                    <input type="text" name="nisbah" class="form-control input-sm" value="<?php echo $SET_BUNGA; ?>">
                            </div>
                            <div class="col-xs-4">
                                <label>Setoran Minimal</label>
                                <input type="text" name="setro_min" class="form-control input-sm" value="<?php echo $SET_SETORAN_MIN; ?>">
                            </div>
                            
                            <div class="col-xs-4">
                                <label>Adm Perbulan</label>
                                <input type="text" name="admpbln" class="form-control input-sm" value="<?php echo $SET_ADMPERBLN; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            
                            <div class="col-xs-3">
                                    <label>PPh &nbsp; &nbsp;%</label>
                                    <input type="text" name="pph" class="form-control input-sm" value="<?php echo $SET_PPH; ?>">
                            </div>
                            <div class="col-xs-4">
                                    <label>Saldo Minimal</label>
                                    <input type="text" name="saldo_min" class="form-control input-sm" value="<?php echo $SET_SALDOMIN; ?>">
                            </div>
                            <div class="col-xs-4">
                                <label>Zakat &nbsp; &nbsp;%</label>
                                <input type="text" name="zakat" class="form-control input-sm" value="<?php echo ""; ?>">
                            </div>
                        </div>
                </div>  
                </div>
            </div>
            <div class="box-primary">
            <div class="box-header with-border">
              <h4 class="box-title">Sebagai Jaminan Untuk (Diisi apabila rekening ini dijadikan jaminan pembiayaan)</h4>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-11">
                        <div class="col-xs-4">
                          <label>No.Rekening Pemb</label>
                          <input type="text" class="form-control input-sm" name="norek_penj">                          
                        </div>
                        <div class="col-xs-4">
                          <label>Nama</label>
                          <input type="text" class="form-control input-sm" name="nama_penj">
                        </div>
                        <div class="col-xs-4">
                          <label>Saldo Yang Dijaminkan</label>
                          <input type="text" class="form-control input-sm" name="saldo_penj">
                        </div>
                        
                    </div>
                </div>
            </div>
          <!-- /.box -->

          <!-- general form elements -->
        <div class="box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Simpanan Program (Diisi apabila rekening ini adalah simpanan program)</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <div class="row">
                <div class="form-group col-md-11">
                      <div class="col-xs-4" >
                        <label>Target Nominal</label>
                              <input type="text" class="form-control input-sm" name="p_target_nominal">
                        <label>Setoran Awal</label>
                              <input type="text" class="form-control input-sm" name="p_setoran">
                        <label>Rekening Tab.Umum</label>
                              <input type="text" class="form-control input-sm" name="p_rek_tab_umum">
                      </div>

                      <div class="col-xs-2" >
                        <label>Jangka Waktu</label>
                              <input type="text" class="form-control input-sm" value="0" name="p_jangka_waktu">
                        
                      </div>
                        <div class="col-xs-4" >                       
                        <label>Setoran Perbulan</label>
                              <input type="text" class="form-control input-sm"  name="p_setoran_perbln">
                      </div>
                      <div class="col-xs-4" >
                        <label>Tgl jatuh tempo</label>
                              <div class="input-group date">
                                <div class="input-group-addon">
                                      <i class="fa fa-calendar"></i>
                                </div>
                                 <input type="text" class="form-control input-sm" id="datepicker" value="<?php echo date('Y-m-d');?>"  name="p_tgl_jt">
                              </div>
                      </div>

                      <div class="col-xs-6" >
                        <label>&nbsp;</label>
                              <input type="text" class="form-control input-sm"  name="">
                      </div>
                    
                 </div>
                  <div class="form-group col-md-11">
                        <div  class="col-xs-12" >
                          <label>Keterangan</label>
                          <textarea type="text" class="form-control input-sm" name="keterangan"></textarea>
                        </div>
                 </div>
                  <input type="hidden" name="h_integrasi" class="form-control input-sm" value="<?php echo $SETINTEGRASI; ?>">
                  <input type="hidden" name="h_pemilik" class="form-control input-sm" value="<?php echo $SETKDPEMILIK; ?>">
                  <input type="hidden" name="h_group1" class="form-control input-sm" value="<?php echo $SETKODGROUP1; ?>">
                  <input type="hidden" name="h_group2" class="form-control input-sm" value="<?php echo $SETKODGROUP1; ?>">
                  <input type="hidden" name="h_group3" class="form-control input-sm" value="<?php echo $SETKODGROUP3; ?>">
                  <input type="hidden" name="h_produk" class="form-control input-sm" value="<?php echo $SETKODPRODUK; ?>">
                  <input type="hidden" name="h_kantor" class="form-control input-sm" value="<?php echo $SETKODKANTOR; ?>">
                  <input type="hidden" name="h_basil" class="form-control input-sm" value="<?php echo $SETKDMTDBASL; ?>">
                  <input type="hidden" name="h_hubbank" class="form-control input-sm" value="<?php echo $SETKDHUBBANK; ?>">
                  <input type="hidden" name="h_alamat" class="form-control input-sm" value="<?php echo $SETALAMATNSB; ?>">
                  <input type="hidden" name="h_tglreg" class="form-control input-sm" value="<?php echo $SETTGLREGIST; ?>">
                  <input type="hidden" name="h_saldoskrg" class="form-control input-sm" value="<?php echo $SETSALDOSKRG; ?>">
                  <input type="hidden" name="h_nobilyet" class="form-control input-sm" value="<?php echo $SETNO_BILYET; ?>">
         </div>
        </div>
              <!-- /.box-body -->

              <!-- general form elements -->
        <div class="box-primary">
              <!-- /.box-body -->
            <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href='<?php echo BASE_URL(); ?>' class='btn btn-danger' role='button' onclick="return confirm('Are you sure you want to cancel this item?');">Batal</a>
            </div>
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
