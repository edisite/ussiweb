<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
        
            <div class="box-header with-border">
                    <h3 class="box-title">MUTASI NERACA</h3>
            </div>
            <div class="box-body">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="post">
                    <div class="form-group">
                        <label class="control-label col-md-1 col-sm-3 col-xs-12">Tanggal</label>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                            <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('d/m/Y', strtotime(date('Y-m-d'))); ?>">
                            
                            </div> 
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div> 
                    </div>
                </form>
                
                
                
                <div class="lead center-margin">
                    <div class="pull-right btn btn-default"><a href="<?php echo base_url(); ?>admin/report/acc/neraca_harian_export_excel" ><i class="fa fa-download"> Export</i></a></div>                   
      
                    <small class="pull-left">Periode : <?php if($tanggal){ echo $tanggal;}else{echo "-";};?></small>
                    <label></label>
                </div>
                <div class="row">
                    <div class="col-md-12">
                <div class="col-md-6">
                    <table id="report121" class="table table-hover table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">
                                <th colspan="2" >Nama Perkiraan</th>
                                <th></th>   
                                <th>Jumlah(Rp)</th>						                                                                                           	                                              						                                                                                           	                                              
                            </tr>

                            
                            <?php if($neraca){

                            foreach ($neraca as $val){
                                    if($val->group_neraca == "AKTIVA"){
                                    ?>
                                        <tr>

                                            <?php
                                            if(strlen($val->kode_perk) <= 3){
                                                ?>
                                            <td colspan="2"><b><?php echo $val->nama_perk; ?></b></td>
                                            
                                            <td></td>
                                            <td align="right"><?php echo number_format($val->saldo_akhir,2,',','.'); ?></td>
                                                <?php
                                            }else{
                                                ?>  <td width="1px"></td>
                                                    <td><?php echo str_replace('Simpanan', '',$val->nama_perk); ?></td>
                                                    <td align="right"><?php echo number_format($val->saldo_akhir,2,',','.'); ?></td>
                                                    <td></td>
                                                 <?php

                                            }
                                            ?>
                                        </tr>                                               
                                    <?php
                                    }
                            }
                        }
                        //var_dump($data);
                        ?>

                    </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table id="report121" class="table table-hover table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">
                                <th colspan="2">Nama Perkiraan</th>
                                <th></th>   
                                <th>Jumlah(Rp)</th>							                                                                                           	                                              						                                                                                           	                                              
                            </tr>
                                 <?php if($neraca){

                            foreach ($neraca as $val){  
                                 if($val->group_neraca == "PASIVA"){
                                    ?>
                                        <tr>

                                            <?php
                                            if(strlen($val->kode_perk) <= 3){
                                                if( $val->g_or_d == "G"){
                                                        ?>
                                                        <td colspan="2"><b><?php echo $val->nama_perk; ?></b></td>

                                                        <td></td>
                                                        <td align="right"><?php echo number_format($val->saldo_akhir,2,',','.'); ?></td>
                                                        <?php
                                                }   else{
                                                
                                            
                                                }
                                            }else{
                                                ?>  <td width="1px"></td>
                                                    <td><?php echo str_replace('Simpanan', '',$val->nama_perk); ?></td>
                                                    <td align="right"><?php echo number_format($val->saldo_akhir,2,',','.'); ?></td>
                                                    <td></td>
                                                 <?php

                                            }
                                            ?>
                                        </tr>                                               
                                    <?php
                                 }
                            }
                        }
                        //var_dump($data);
                        ?>

                    </tbody>
                    </table>
                </div>
                    </div>
                </div>
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
