<?php echo validation_errors(); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Pilih Periode Tagihan</h3>
                    <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                <i class="fa fa-times"></i></button>
            </div>
            </div>
          
          <div class="box-body">
              <div class="col-sm-8 invoice-col">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="post">
               <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Dari Tanggal</label>
                    <div class="col-md-4 col-sm-3 col-xs-12">
                        <input id="tgl_fr" name="tgl_fr" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('m/01/Y', strtotime(date('Y-m-d'))); ?>">
                    </div> 
               </div>
               <div class="form-group">
                   <label class="control-label col-md-4 col-sm-4 col-xs-12">Sampai Tanggal</label>
                    <div class="col-md-4 col-sm-3 col-xs-12">
                        <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('m/d/Y', strtotime(date('Y-m-d'))); ?>">
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12"></label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                  </div>
                    
                </div>                
                </form>
             </div>
              <div class="col-sm-4">
                <?php
                
                if($datauser):
                    foreach ($datauser as $vu) {
                        ?>
                        <b>Agent ID #<?php echo $vu->id; ?></b><br>
                        <br>
                        <b>Username:</b> <?php echo $vu->username; ?><br>
                        <b>Name     :</b> <?php echo $vu->first_name.' '.$vu->last_name; ?><br>
                            <?php
                    }
                    endif;
                ?>  
                
              <hr>
              <a href="report/com/agent" class="btn btn-primary btn-block margin-bottom">Cari agent lain</a>
              </div>
           
          </div>       
          <div class="box-body">  
                  <div class="widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="bg-aqua-active">                      
                      <!-- /.widget-user-image -->
                      <?php
                    if($tgl): echo $tgl;
                    endif;
                ?> 
                      
                    </div>
                    
                  </div>             

          </div>
          <div class="pull-right">
                            <a href="report/com/history_transaksi/<?php echo $data; ?>/<?php echo $vu->id; ?>" target="_blank" class="btn btn-primary">History</a>
                            </div>
          <hr>
          
          <div class="box-body">
            <div class="col-md-6">
                <div class="box box-info">
                     <div class="box-header with-border">
                         <h3 class="box-title">Data Transaksi</h3>
                          
                         <div class="box-tools pull-right">
                             
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                              <i class="fa fa-times"></i></button>
                        </div>
                     </div>
                                  <div class="table">
                      <table class="table table-responsive">                        
                        <?php
                            if($data_profit): echo $data_profit;
                            endif;
                        ?>
                      </table>
                  </div>
            </div>
            </div> 
              <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Data Tagihan Sebelum</h3>
                    </div>
                    <div class="box-body">
                    <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                      </div>
                    </div>
                </div>  
            </div>
              
          </div>
          
          <hr>
          <div class="box-body">
            <div class="col-md-12">
                <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Setor Tagihan</h3>
                </div>
                <form class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                          <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                          <div class="col-sm-10">
                            <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                              <label>
                                <input type="checkbox"> Remember me
                              </label>
                            </div>
                          </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                      <button type="submit" class="btn btn-default">Cancel</button>
                      <button type="submit" class="btn btn-info pull-right">Sign in</button>
                    </div>
                    <!-- /.box-footer -->
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
