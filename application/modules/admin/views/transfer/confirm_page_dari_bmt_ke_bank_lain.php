<?php echo validation_errors(); ?>
<div class="row">
    <div class="col-md-6">
        
        <div class="box box-primary">
          <div class="box-header with-border">
                <h3 class="box-title">Data Transfer</h3>
            </div>
            <div class="box-body">
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="get">
               <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Tanggal</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <span>:</span>
                        <label class="text-blue"><?php echo $dtm; ?></label>
                    </div>
                </div>
               <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Agent</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <span>:</span>
                        <label class="text-info"><?php echo $agentid.'-'.$agent_nama; ?></label>
                    </div>
                </div>
                <br>
                 <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Bank Pengirim</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                         <span>:</span>
                        <label class="text-blue"><?php echo $bank_sender; ?></label>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">No Rekening</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                         <span>:</span>
                        <label class="text-blue"><?php echo $rek_sender; ?></label>
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Nama</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                         <span>:</span>
                         <label class="text-blue"><?php echo ucfirst($nama_sender); ?></label>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12 " style="text-align: left;">Bank Penerima</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                         <span>:</span>
                        <label class="text-blue"><?php echo $bank_receiver; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12 " style="text-align: left;">Rekening</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                         <span>:</span>
                        <label class="text-blue"><?php echo $rek_receiver; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Nama</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                         <span>:</span>
                         <label class="text-blue"><?php echo ucfirst($nama_receiver); ?></label>
                    </div>
                </div>
                <br>
				<div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Nominal</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                         <span>:</span>
                        <label class="text-blue"><?php echo $nominal; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Biaya Adm</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                         <span>:</span>
                        <label class="text-blue"><?php echo $adm; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Kode Transfer</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                         <span>:</span>
                        <label class="text-blue"><?php echo $code_transfer; ?></label>
                    </div>
                </div>
				
            
          </form>
        </div>
      </div>
        <a href="trf/antarbank/ver_transfer_antar_bank/" class="btn btn-default btn-block margin-bottom">Kembali kehalaman sebelumnya</a>
    </div>
    <div class="col-md-6">
      <div class="box box-primary">
          <div class="box-header with-border">
				<h3 class="box-title">Form Update Transfer Antar BMT ke Bank Lain</h3>
			</div>
            <div class="box-body">
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="trf/antarbank/procantar_bank_upd" method="post">
               <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Tanggal</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input id="tgl_to" name="tgl" type="text" class="form-control input-lg" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Jam</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" name="jam" class="form-control input-lg">
                    </div>
                </div>
	
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Berita</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" name="berita" class="form-control input-lg">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">No.Referensi</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" name="ref" class="form-control input-lg">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Status</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <select multiple class="form-control" name="status">
                            <option value="succes">Succes</option>
                            <option value="gagal">Gagal</option>
                            <option value="tolak">Di Tolak</option>
                      </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align: left;">Keterangan</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <textarea class="form-control" rows="3" placeholder="Berikan keterangan disini" name="keterangan"></textarea>
                    </div>
                </div>
            <div class="ln_solid"></div>
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12"></label>
              <div class="col-md-1 col-sm-8 col-xs-12">
                  <input type="text" name="code" hidden value="<?php echo $code_transfer; ?>">                 
                <button type="submit" class="btn btn-primary">Finish</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

    <script src="../js/moment/moment.min.js"></script>
    <script src="../js/datepicker/daterangepicker.js"></script>

    <script>
      $(document).ready(function() {
        $('#tgl_fr').daterangepicker({
        
        singleDatePicker: true,
        locale: {
      format: 'YYYY-mm-dd'
    }
        });
      });
      
      $(document).ready(function() {
        $('#tgl_to').daterangepicker({
         
          singleDatePicker: true,
          //showDropdowns: true
          calender_style: "picker_4"
         
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>