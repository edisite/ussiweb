<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
        
            <div class="box-header with-border">
                    <h3 class="box-title">Pilih Tanggal</h3>
            </div>
            <div class="box-body">
                <div>
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="post">
                    <div class="form-group">
                            <label class="control-label col-md-1 col-sm-3 col-xs-12">Tanggal From</label>
                            <div class="col-md-2 col-sm-12 col-xs-12">
                            <input id="tgl_fr" name="tgl_fr" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('m/01/Y', strtotime(date('Y-m-d'))); ?>">
                    
                            </div> 
                            <label class="control-label col-md-1 col-sm-3 col-xs-12">Tanggal To</label>
                            <div class="col-md-2 col-sm-12 col-xs-12">
                            <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('m/d/Y', strtotime(date('Y-m-d'))); ?>">
                    
                            </div>
                            <div class="col-md-2 col-sm-12 col-xs-12">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div> 
                    </div>
                </form>
                </div>
                
                <div class="col-md-12 col-sm-12 col-xs-12"><hr></div>
                <div class="lead center-margin">
                    <div class="pull-right btn btn-default"><a href="#" ><i class="fa fa-download"> Export</i></a></div>                   
      
                    <small class="pull-left">Periode : <?php if($tanggal_fr){ echo $tanggal_fr;}else{echo "-";}?> Ke <?php if($tanggal_to){ echo $tanggal_to;}else{echo "-";};?></small>
                    <label></label>
                </div>
                

            </div>
       </div>

    </div>
    
      
</div>
<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Laporan Tabungan</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body" style="">
              <div class="col-md-12">
                    <table id="report121" class="table table-bordered table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">
                                <th width="5%">No</th>
                                <th width="10%">Kode</th>   					                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                 					                                                                                           	                                              						                                                                                           	                                              				                                                                                           	                                              						                                                                                           	                                              
                                <th width="40%">Keterangan</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="10%">Jumlah Transaksi</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Nominal</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                             					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                  						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                            </tr>
                            <?php
                            $no       = 1;
                            if($rekonsiliasi_tab){
                                foreach ($rekonsiliasi_tab as $v) {
                                    
                                    ?>
                                        <tr>                                
                                            <td align ="center"><?php echo $no; ?></td>
                                            <td align ="center"><?php echo $v->kode_trans; ?></td>
                                            <td><?php echo $v->desc_kode_trans; ?></td>
                                            <td align ="right"><?php echo $v->total; ?></td>
                                            <td align ="right"><?php echo number_format ($v->nominal, 2, ',', '.'); ?></td>
                                        </tr>
                                    <?php
                                    $no = $no + 1;
                                }                             
                            }                            
                            ?>

                    </tbody>
                    </table>
                </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer" style="">
          
        </div>
        <!-- /.box-footer-->
      </div>


<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Laporan Kredit</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body" style="">
               <div class="col-md-12">
                    <table id="report121" class="table table-bordered table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">
                                <th width="5%">No</th>
                                <th width="10%">Kode</th>   					                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                 					                                                                                           	                                              						                                                                                           	                                              				                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Keterangan</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="10%">Jumlah Transaksi</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Pokok</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                             					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                  						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Bunga</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                             					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                  						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                             					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                  						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                            </tr>
                            <?php
                            $no       = 1;
                            if($rekonsiliasi_kre){
                                foreach ($rekonsiliasi_kre as $v) {
                                    
                                    ?>
                                        <tr>                                
                                            <td align ="center"><?php echo $no; ?></td>
                                            <td align ="center"><?php echo $v->kode_trans; ?></td>
                                            <td><?php echo $v->desc_kode_trans; ?></td>
                                            <td align ="right"><?php echo $v->total; ?></td>
                                            <td align ="right"><?php echo number_format ($v->nominal_pokok, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($v->nominal_bunga, 2, ',', '.'); ?></td>
                                         </tr>
                                    <?php
                                    $no = $no + 1;
                                }                             
                            }                            
                            ?>

                    </tbody>
                    </table>
                </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer" style="">
          
        </div>
        <!-- /.box-footer-->
</div>

<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Laporan Deposito</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body" style="">
              <div class="col-md-12">
                    <table id="report121" class="table table-bordered table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">
                                <th width="5%">No</th>
                                <th width="10%">Kode</th>   					                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                 					                                                                                           	                                              						                                                                                           	                                              				                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Keterangan</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="10%">Jumlah Transaksi</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Pokok</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                             					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                  						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Bunga</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                             					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                  						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                             					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                  						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                            </tr>
                            <?php
                            $no       = 1;
                            if($rekonsiliasi_dep){
                                foreach ($rekonsiliasi_dep as $v) {
                                    
                                    ?>
                                        <tr>                                
                                            <td align ="center"><?php echo $no; ?></td>
                                            <td align ="center"><?php echo $v->kode_trans; ?></td>
                                            <td><?php echo $v->desc_kode_trans; ?></td>
                                            <td align ="right"><?php echo $v->total; ?></td>
                                            <td align ="right"><?php echo number_format ($v->nominal_pokok, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($v->nominal_bunga, 2, ',', '.'); ?></td>
                                         </tr>
                                    <?php
                                    $no = $no + 1;
                                }                             
                            }                            
                            ?>

                    </tbody>
                    </table>
                </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer" style="">
          
        </div>
        <!-- /.box-footer-->
</div>

<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Laporan Commerce</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body" style="">
               <div class="col-md-12">
                    <table id="report121" class="table table-bordered table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">
                                <th width="5%">No</th>
                                <th width="10%">Kode</th>   					                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                 					                                                                                           	                                              						                                                                                           	                                              				                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Keterangan</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="10%">Jumlah Transaksi</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Pokok</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                             					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                  						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Adm</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                             					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                  						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                             					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                  						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                            </tr>
                            <?php
                            $no       = 1;
                            if($rekonsiliasi_com){
                                foreach ($rekonsiliasi_com as $v) {
                                    
                                    ?>
                                        <tr>                                
                                            <td align ="center"><?php echo $no; ?></td>
                                            <td align ="center"><?php echo $v->kode_trans; ?></td>
                                            <td><?php echo $v->desc_kode_trans; ?></td>
                                            <td align ="right"><?php echo $v->total; ?></td>
                                            <td align ="right"><?php echo number_format ($v->nominal_pokok, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($v->nominal_adm, 2, ',', '.'); ?></td>
                                         </tr>
                                    <?php
                                    $no = $no + 1;
                                }                             
                            }                            
                            ?>

                    </tbody>
                    </table>
                </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer" style="">
          
        </div>
        <!-- /.box-footer-->
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
