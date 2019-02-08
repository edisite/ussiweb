<?php echo validation_errors(); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header">
                    <h3 class="box-title">Laporan Teller</h3>
            </div>
            <div class="box-body">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="report/trans_teller_new/lap3" method="post">
               <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">User Name</label>
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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tunai / Non Tunai</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="paytype">
                            <option value="T">T - Tunai</option>
                            <option value="O">O - Non Tunai</option>                            
                      </select>
                    </div>                            
                </div> 
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Dari Tanggal</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <input id="tgl_fr" name="tgl_fr" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text">
                    </div> 
                    <label class="control-label col-md-1 col-sm-1 col-xs-12">Sampai</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                      <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text">
                    </div>
                </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-3">
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

    <!--SELECT date(waktu) as dtm,msisdn,keyword,sms FROM `sms_out_2016_10` WHERE `dmethod` ='pull' and 'cinta_sehat_tarot_station' like concat(concat('%',`keyword`),'%') and '2016-10-21_2016-10-22' like concat(concat('%',date(`waktu`)),'%')--> 