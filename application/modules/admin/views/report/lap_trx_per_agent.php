      <div class="row">       
        <!-- /.col -->
       
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div class="col-md-9">
             <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Laporan Transaksi Cash Out Per Hari</h3>
            </div>
            <div class="box-body">
                <div class="col-md-12">
                    <div class="pull-right btn btn-default"><a href="<?php echo base_url(); ?>admin/report/tab/lapperkolektor_export_excel" ><i class="fa fa-download"> Export</i></a></div>                   
                    <div class="table table-condensed">
                        <small class="pull-left"><h4>AgentID : [ <?php echo $agentid; ?> ]<h4></small>
                        <small class="pull-left"><h4>Tanggal :[ <?php echo $tgl_fr; ?> ]<h4></small>
                    </div>
                    
                </div>
                <div class="row">
                <div class="col-md-12">
                    <div class="label-success col-md-12 col-sm-12 col-xs-12"><strong>TABUNG / SIMPANAN</strong></div>
                    <table id="report121" class="table table-bordered table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">  					                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                 					                                                                                           	                                              						                                                                                           	                                              				                                                                                           	                                              						                                                                                           	                                             						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="4%">No.</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="6%">Kode</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="20%">Deskripsi</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="15%">Penarikan</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="15%">Tabungan</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="4%">Jml Transaksi</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="15%" align ="center">Saldo</th>                               
                            </tr>
                            <?php
                            //print_r($result_tab);
                            $tarikan    = 0;
                            $tabung     = 0;
                            $stok_tab       = 0;
                            $totalpelangan       = 0;
                            $this->no   = 1;
                            if($this->session->userdata('tab_peragent_ses')){
                                foreach ($this->session->userdata('tab_peragent_ses') as $v) {
                                    
                                    ?>
                                        <tr> 
                                            
                                            <td align ="center"><?php echo $this->no; ?></td>
                                            <td align ="left"><?php echo $v->kode_trans; ?></td>
                                            <td align ="left"><?php echo $v->desc_kode_trans; ?></td>
                                            <td align ="right"><?php echo number_format ($v->penarikan_pokok, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($v->tabungan_pokok, 2, ',', '.'); ?></td>
                                            <td align ="center"><?php echo $v->total; ?></td>
                                            <td align ="right"><?php echo number_format ($v->stok, 2, ',', '.'); ?></td> 
                                            
                                        </tr>
                                    <?php
                                    $tarikan    = $tarikan + $v->penarikan_pokok;
                                    $tabung     = $tabung + $v->tabungan_pokok;
                                    $stok_tab       = $stok_tab + $v->stok;
                                    $totalpelangan       = $totalpelangan + $v->total;
                                    $this->no ++;
                                }                             
                            }                            
                            ?>
                            <tr>                                
                                
                                <td align ="center" colspan="3"><strong>JUMLAH</strong></td>
                                <td align ="right"><strong><?php echo number_format ($tarikan, 2, ',', '.'); ?></strong></td>
                                <td align ="right"><strong><?php echo number_format ($tabung, 2, ',', '.'); ?></strong></td>
                                <td align ="center"><strong><?php echo $totalpelangan; ?></strong></td> 
                                <td align ="right"><strong><h4><?php echo number_format ($stok_tab, 2, ',', '.'); ?><h4></strong></td> 
                                </tr>


                    </tbody>
                    </table>
                </div>              
                </div>
                <div class="row">
                <div class="col-md-12">
                    <div class="label-success col-md-12 col-sm-12 col-xs-12"><strong>KREDIT / PEMBIAYAAN</strong></div>
                    <table id="report121" class="table table-bordered table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">  					                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                 					                                                                                           	                                              						                                                                                           	                                              				                                                                                           	                                              						                                                                                           	                                             						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="5%" rowspan="2">No.</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="40%" colspan="4">Angsuran</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="40%" colspan="4">Realisasi</th>
                                <th width="15%" rowspan="2">Jumlah</th>
                            </tr>
                            <tr class="label-primary">  			                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                 					                                                                                           	                                              						                                                                                           	                                              				                                                                                           	                                              						                                                                                           	                                             						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <td width="10%">Pokok</td>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <td width="10%">Bunga</td>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <td width="10%">Denda</td>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <td width="10%">Adm</td>	
                                <td width="10%">Pokok</td>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <td width="10%">Bunga</td>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <td width="10%">Denda</td>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <td width="10%">Adm</td>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
            
                            </tr>
                            <?php
                            //print_r($result_tab);
                            $tarikan    = 0;
                            $tabung     = 0;
                            $stok_kre       = 0;
                            $this->no   = 1;
                            
                            $angsur_pokok = 0;
                            $angsur_bunga = 0;
                            $angsur_denda = 0;
                            $angsur_adm   = 0;
                            
                            $real_pokok     = 0;
                            $real_bunga     = 0;
                            $real_denda     = 0;
                            $real_adm       = 0;
                            
                            if($this->session->userdata('kre_peragent_ses')){
                                //var_dump($this->session->userdata('kre_peragent_ses'));
                                foreach ($this->session->userdata('kre_peragent_ses') as $v) {
                                    $angsur_pokok   = $v->ang_pokok ?: 0;
                                    $angsur_bunga   = $v->ang_bunga ?: 0;
                                    $angsur_denda   = $v->ang_denda ?: 0;
                                    $angsur_adm     = $v->ang_adm ?: 0;
                                    $real_pokok     = $v->rel_pokok ?: 0;
                                    $real_bunga     = $v->rel_bunga ?: 0;
                                    $real_denda     = $v->rel_denda ?: 0;
                                    $real_adm       = $v->rel_adm ?: 0;
                                    // { ["ang_pokok"]=> NULL ["ang_bunga"]=> NULL ["ang_denda"]=> NULL ["ang_adm"]=> NULL ["rel_pokok"]=> NULL ["rel_bunga"]=> NULL ["rel_denda"]=> NULL ["rel_adm"]=> NULL } } 
                                    
                                    $total_angsuran = $angsur_pokok + $angsur_bunga + $angsur_denda + $angsur_adm;
                                    $total_realisasi = $real_pokok + $real_bunga + $real_denda + $real_denda;
                                    $stok_kre       = $total_angsuran - $total_realisasi;
                                    ?>
                                        <tr> 
                                            
                                            <td align ="center"><?php echo $this->no; ?></td>
                                            <td align ="right"><?php echo number_format ($angsur_pokok, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($angsur_bunga, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($angsur_denda, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($angsur_adm, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($real_pokok, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($real_bunga, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($real_denda, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($real_adm, 2, ',', '.'); ?></td>
                                            <td align ="right"><?php echo number_format ($stok_kre, 2, ',', '.'); ?></td> 
             
                                        </tr>
                                    <?php
                                    
                                }                             
                            }       
                            $this->no ++;
                            ?>
                    </tbody>
                    </table>
                </div>              
                </div>
                <div class="row">
                <div class="col-md-12">
                    <div class="label-success col-md-12 col-sm-12 col-xs-12"><strong>COMMERCE</strong></div>
                    <table id="report121" class="table table-bordered table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">  					                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                 					                                                                                           	                                              						                                                                                           	                                              				                                                                                           	                                              						                                                                                           	                                             						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="4%">No.</th>
                                <th width="15%" align ="center">PRODUK</th>
                                <th width="6%">Kode</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="25%">Deskripsi</th>					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                                           						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="4%">Jml Transaksi</th>
                                <th width="15%">Nominal</th>
                                  
                            </tr>
                            <?php
                            //print_r($result_tab);
                            $subjumlah    = 0;                            
                            $totalpelangan       = 0;
                            $this->no   = 1;
                            if($this->session->userdata('com_peragent_ses')){
                                foreach ($this->session->userdata('com_peragent_ses') as $v) {
                                    
                                    ?>
                                        <tr> 
                                            <td align ="center"><?php echo $this->no; ?></td>
                                            <td align ="left"><?php echo $v->comtype; ?></td>
                                            <td align ="left"><?php echo $v->KODE_TRANS; ?></td>
                                            <td align ="left"><?php echo $v->DESKRIPSI_TRANS; ?></td>
                                            <td align ="right"><?php echo $v->total; ?></td>
                                            <td align ="right"><?php echo number_format ($v->subjumlah, 2, ',', '.'); ?></td>

                                        </tr>
                                    <?php
                                    $subjumlah    = $subjumlah + $v->subjumlah;
                                    //$tabung     = $tabung + $v->tabungan_pokok;
                                    //$stok       = $stok + $v->stok;
                                    $this->no ++;
                                }                             
                            }                            
                            ?>
                            <tr>                                
                                <td align ="center" colspan="5"><strong>JUMLAH</strong></td>
                                <td align ="right"><strong><?php echo number_format ($subjumlah, 2, ',', '.'); ?></strong></td>
                                </tr>


                    </tbody>
                    </table>
                </div>
              
                </div>               
                <div class="row">
                <div class="col-md-12">
                    <div class="label-danger col-md-12 col-sm-12 col-xs-12"><strong>CASH OUT</strong></div>
                    <table id="report121" class="table table-bordered table-condensed table-striped">                                
                        <tbody>                                            
                            <tr class="label-primary">  					                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                 					                                                                                           	                                              						                                                                                           	                                              				                                                                                           	                                              						                                                                                           	                                             						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="15%">TAB</th>
                                <th width="15%">KRE</th>						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              
                                <th width="15%">COM</th>		                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                                           						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              					                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                              						                                                                                           	                                                                             
                            </tr>                   
                            <tr> 
                                <td align ="left"><?php echo number_format ($stok_tab, 2, ',', '.'); ?></td>
                                <td align ="left"><?php echo number_format ($stok_kre, 2, ',', '.'); ?></td>
                                <td align ="left"><?php echo number_format ($subjumlah, 2, ',', '.'); ?></td>

                            </tr>
                            <tr>                                
                                <td align ="center" colspan="2"><strong>JUMLAH</strong></td>
                                <td align ="right"><strong><?php echo number_format ($stok_tab + $stok_kre +  $subjumlah, 2, ',', '.'); ?></strong></td>
                                </tr>


                    </tbody>
                    </table>
                </div>
              
                </div>               
            </div>
       </div>     
      </div>
      
       
        <div class="col-md-3 col-sm-12 col-xs-12">
            <!--<a href="#" class="btn btn-primary btn-block margin-bottom">Back</a>-->  
          <!-- /.box -->
            <div class="box box-solid">
                
          <div class="box-header with-border">
				<h3 class="box-title">Cari berdasarkan tanggal</h3>
			</div>
                <?php echo validation_errors(); ?>
            <div class="box-body">
               <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="post">
               <div class="form-group">
                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Date</label>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input id="tgl_fr" name="tgl_fr" type="text" class="form-control input-sm" value="<?php echo date('m/d/Y', strtotime(date('Y-m-d'))); ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Agent ID</label>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <select multiple="multiple" class="form-control" name="listagent">                           
                                <?php 
                                if($USERNMGROUP){
                                    foreach ($USERNMGROUP as $v_usrgroup) {
                                        echo "<option value=".$v_usrgroup->id.">".$v_usrgroup->id." - ".$v_usrgroup->username."</option>";
                                    }
                                }                        
                               ?>                          
                        </select>
                    </div>
                </div>	
            <div class="ln_solid"></div>
            <div class="form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12"></label>
              <div class="col-md-12 col-sm-12 col-xs-12">                             
                <button type="submit" class="btn btn-primary pull-right">Search</button>
              </div>
            </div>
            </form>
        </div>
      </div>
        </div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="<?php echo base_url(); ?>dist/js/demo.js"></script>-->
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

    </script>

