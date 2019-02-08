<?php echo validation_errors(); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header">
                    <h3 class="box-title">Filter Laporan Simpanan</h3>
            </div>
            <div class="box-body">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="report/trans_simpanan/rekap/" method="post">
               <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Dari Tanggal</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="tgl_fr" name="tgl_fr" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('m/d/Y');?>">
                    </div> 
                   <label class="control-label col-md-2 col-sm-3 col-xs-12">Sampai Tanggal</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('m/d/Y');?>">
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
                        <select class="form-control" name="paytype">
                           <option value="all">* . Semua</option>
                            <?php foreach ($kdintgr as $subkdintgr) {
                              echo "<option value=".$subkdintgr->kode.">".$subkdintgr->kode." - ".$subkdintgr->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div> 
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Kode Produk</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="paytype">
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
                        <select class="form-control" name="paytype">
                           <option value="all">* . Semua</option>
                            <?php foreach ($ao as $subao) {
                              echo "<option value=".$subao->kode.">".$subao->kode." - ".$subao->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div> 
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Wilayah</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="paytype">
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
                        <select class="form-control" name="paytype">
                           <option value="all">* . Semua</option>
                            <?php foreach ($profesi as $subprofesi) {
                              echo "<option value=".$subprofesi->kode.">".$subprofesi->kode." - ".$subprofesi->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div> 
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Kode Kantor</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="paytype">
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
                        <select class="form-control" name="paytype">
                            <option value="all">* . Semua</option>
                            <?php foreach ($kdkolek as $subkdkolek) {
                              echo "<option value=".$subkdkolek->kode.">".$subkdkolek->kode." - ".$subkdkolek->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div>   
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Rekap Berdasarkan</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="paytype">
                            <option value="all">* . Semua</option>
                            <option value="kode_trans">kode_trans</option>
                            <option value="kode_produk">kode_produk</option>
                            <option value="kode_group1">kode_group1</option>
                            <option value="kode_group2">kode_group2</option>
                            <option value="kode_group3">kode_group3</option>                                
                      </select>
                    </div>   
                </div>
                 <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Nama Pembuat</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="Nmpembuat" name="" class="form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo $user->first_name; ?>">
                    </div>   
                       
                </div>
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-3 col-xs-12">Nama Pemeriksa</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="Nmpembuat" name="" class="form-control col-md-7 col-xs-12" required="required" type="text" value="" placeholder="Masukan Nama Pemeriksa">
                    </div>
                    </div>
                    <?php foreach ($namabos as $subbos) {
                            $bose = $subbos->KEYVALUE;
                        }?> 
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Nama Pejabat</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="Nmpembuat" name="" class="form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo $bose; ?>">
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
