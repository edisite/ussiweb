<?php echo validation_errors(); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Filter Laporan Simpanan</h3>
            </div>
            <div class="box-body">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="report/agent/tab_per_agent" method="post">
               <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Dari Tanggal</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="tgl_fr" name="tgl_fr" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('m/01/Y', strtotime(date('Y-m-d'))); ?>">
                    </div> 
                   <label class="control-label col-md-2 col-sm-3 col-xs-12">Sampai Tanggal</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('m/d/Y', strtotime(date('Y-m-d'))); ?>">
                    </div>
                </div>
                    <div class="form-group">
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">User ID</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="userid">
                            <option value="all">* . Semua</option>
                            <?php foreach ($usragent as $subkdtrans) {
                              echo "<option value=".$subkdtrans->uid.">".strtoupper($subkdtrans->description)."-- [".$subkdtrans->uid."] ".$subkdtrans->nama."</option>";
                          }?>
                      </select>
                    </div>                          
                </div>

            <div class="ln_solid"></div>
            <div class="box-footer">
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
