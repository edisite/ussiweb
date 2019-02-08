<?php echo validation_errors(); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Filter Laporan Jurnal Transaksi</h3>
            </div>
            <div class="box-body">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="report/accounting/lap_bb_buku_harian" method="post">
               
               
                      
                <div class="form-group">
                     
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Kode Kantor</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="kdkantor">
                            <option value="all">* . Konsolidasi</option>
                            <?php foreach ($kdkantr as $subkdkantr) {
                              echo "<option value=".$subkdkantr->KODE_KANTOR.">".$subkdkantr->KODE_KANTOR." - ".$subkdkantr->NAMA_KANTOR."</option>";
                          }?>                          
                      </select>
                    </div> 
                </div>
                    <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">User Name</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="usrid">
                            <option value="alluser">*.Semua User</option>
                            <?php foreach ($userjoin as $useradm) {
                              echo "<option value=".$useradm->user_id.">".$useradm->id." - ".$useradm->user_name." - ".$useradm->kode_perk_kas."</option>";
                          }?>
                      </select>
                    </div>                          
                </div>
                    <div class="form-group">
<!--                        <label for="sel1">Select list (select one):</label>
                        <select class="form-control" id="sel1">
                          <option>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                        </select>
                        <br>-->
                            <label for="sel2" class="control-label col-md-2 col-sm-8 col-xs-12">Perkiraan</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">                        
                                <select multiple class="form-control selectpicker>
                            
                            <?php foreach ($mutasi as $useradm) {
                                    echo "<option value=".$useradm->KODE_PERK.">".$useradm->KODE_PERK." - ".$useradm->NAMA_PERK."</option>";
                              
                                }?>    
<!--                            <option selected>Option 1</option>
                            <optgroup label="Option group 1">
                            <option>Sub option 1</option>
                            <option>Sub option 2</option>
                            <option>Sub option 3</option>
                            </optgroup>
                            <option>Option 2</option>
                            <option>Option 3</option>
                            <option>Option 4</option>
                            <option>Option 5</option>-->
                        </select>
                      </div>
                    </div>
                    
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Dari Tanggal</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="tgl_fr" name="tgl_fr" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('01/m/Y', strtotime(date('Y-m-d'))); ?>">
                    </div> 
                   <label class="control-label col-md-2 col-sm-3 col-xs-12">Sampai Tanggal</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('t/m/Y', strtotime(date('Y-m-d'))); ?>">
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
