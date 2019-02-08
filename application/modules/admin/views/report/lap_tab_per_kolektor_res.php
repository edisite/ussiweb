<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">LAPORAN TABUNGAN PER KOLEKTOR</h3>
            </div>
            <div class="box-body">
                <div class="center-margin">
                    <div class="pull-right btn btn-default"><a href="<?php echo base_url(); ?>admin/report/tab/lapperkolektor_export_excel" ><i class="fa fa-download"> Export</i></a></div>                   
      
                <small class="pull-left">Tanggal : <?php echo $tgl_from." s/d ".$tgl_to; ?></small>
                </div>
                <div class="row">
                <div class="col-md-12">
                    <table id="report121" class="table table-bordered table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">
                                <th width="5%">ID</th>
                                <th width="40%">Kolektor</th>   					                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                 					                                                                                           	                                              						                                                                                           	                                              				                                                                                           	                                              						                                                                                           	                                              
                                <th width="15%">Penarikan</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="15%">Tabungan</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="15%">(Stock Opname)</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                            </tr>
                            <?php
                            //print_r($result_tab);
                            $tarikan    = 0;
                            $tabung     = 0;
                            $stok       = 0;
                            if($result_tab){
                                foreach ($result_tab as $v) {
                                    
                                    ?>
                                        <tr>                                
                                            <td><?php echo $v->KODE_KOLEKTOR; ?></td>
                                            <td><?php echo $v->nama_kolektor; ?></td>
                                            <td align ="right"><?php echo number_format ($v->penarikan_pokok, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($v->tabungan_pokok, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($v->stok, 2, ',', '.'); ?></td> 
                                        </tr>
                                    <?php
                                    $tarikan    = $tarikan + $v->penarikan_pokok;
                                    $tabung     = $tabung + $v->tabungan_pokok;
                                    $stok       = $stok + $v->stok;
                                }                             
                            }                            
                            ?>
                            <tr>                                
                                <td colspan="2" align="center"><strong>JUMLAH</strong></td>
                                <td align ="right"><strong><?php echo number_format ($tarikan, 2, ',', '.'); ?></strong></td>
                                <td align ="right"><strong><?php echo number_format ($tabung, 2, ',', '.'); ?></strong></td>
                                <td align ="right"><strong><?php echo number_format ($stok, 2, ',', '.'); ?></strong></td> 
                            </tr>


                    </tbody>
                    </table>
                </div>
              
                </div>
            </div>
       </div>

    </div>
</div>

