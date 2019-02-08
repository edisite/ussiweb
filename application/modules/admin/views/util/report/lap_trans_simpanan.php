<?php echo validation_errors(); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Filter Laporan Simpanan</h3>
            </div>
            <div class="box-body">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="report/trans_teller_new/lap3" method="post">
               <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Dari Tanggal</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="tgl_fr" name="tgl_fr" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="">
                    </div> 
                   <label class="control-label col-md-2 col-sm-3 col-xs-12">Sampai Tanggal</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="">
                    </div>
                </div>
                    <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Kode Transaksi</label>
                    <div class="col-md-5 col-sm-3 col-xs-12">
                        <select class="form-control" name="usrid">
                            <option value="all">* . Semua</option>
                            <?php foreach ($kdtrans as $subkdtrans) {
                              echo "<option value=".$subkdtrans->kode.">".$subkdtrans->kode." - ".$subkdtrans->deskripsi."</option>";
                          }?>
                      </select>
                    </div>                          
                </div>
               <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Kode Integrasi</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="kdintegrasi">
                           <option value="all">* . Semua</option>
                            <?php foreach ($kdintgr as $subkdintgr) {
                              echo "<option value=".$subkdintgr->kode.">".$subkdintgr->kode." - ".$subkdintgr->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div> 
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Kode Produk</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="kdproduk">
                            <option value="all">* . Semua</option>
                            <?php foreach ($kdprodk as $subkdprodk) {
                              echo "<option value=".$subkdprodk->kode.">".$subkdprodk->kode." - ".$subkdprodk->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div> 
                </div> 
                      <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">AO</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="ao">
                           <option value="all">* . Semua</option>
                            <?php foreach ($ao as $subao) {
                              echo "<option value=".$subao->kode.">".$subao->kode." - ".$subao->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div> 
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Wilayah</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="wiayah">
                            <option value="all">* . Semua</option>
                            <?php foreach ($wilayah as $subwilayah) {
                              echo "<option value=".$subwilayah->kode.">".$subwilayah->kode." - ".$subwilayah->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div> 
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Profesi</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="profesi">
                           <option value="all">* . Semua</option>
                            <?php foreach ($profesi as $subprofesi) {
                              echo "<option value=".$subprofesi->kode.">".$subprofesi->kode." - ".$subprofesi->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div> 
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Kode Kantor</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="kdkantor">
                            <option value="all">* . Semua</option>
                            <?php foreach ($kdkantr as $subkdkantr) {
                              echo "<option value=".$subkdkantr->KODE_KANTOR.">".$subkdkantr->KODE_KANTOR." - ".$subkdkantr->NAMA_KANTOR."</option>";
                          }?>                          
                      </select>
                    </div> 
                </div>
               <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Kolektor</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="kdkolek">
                            <option value="all">* . Semua</option>
                            <?php foreach ($kdkolek as $subkdkolek) {
                              echo "<option value=".$subkdkolek->kode.">".$subkdkolek->kode." - ".$subkdkolek->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div>   
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Jenis Transaksi</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="jns_trans">
                            <option value="all">* . Semua</option>
                            <?php foreach ($kdprodk as $subkdprodk) {
                              echo "<option value=".$subkdprodk->kode.">".$subkdprodk->kode." - ".$subkdprodk->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div>   
                </div>
                
            <div class="ln_solid"></div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-1 col-sm-3 col-xs-12">
                <button type="submit" class="btn btn-primary">Submit</button>
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
      format: 'YYYY-MM-DD'
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
