      <div class="row">        
        <!-- /.col -->
        <div class="col-md-9">
                   <!-- ./box-body -->
<!--            <div class="box-footer">
              <div class="row">
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
                    <h5 class="description-header">$35,210.43</h5>
                    <span class="description-text">TOTAL REVENUE</span>
                  </div>
                   /.description-block 
                </div>
                 /.col 
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                    <h5 class="description-header">$10,390.90</h5>
                    <span class="description-text">TOTAL COST</span>
                  </div>
                   /.description-block 
                </div>
                 /.col 
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
                    <h5 class="description-header">$24,813.53</h5>
                    <span class="description-text">TOTAL PROFIT</span>
                  </div>
                   /.description-block 
                </div>
                 /.col 
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block">
                    <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
                    <h5 class="description-header">1200</h5>
                    <span class="description-text">GOAL COMPLETIONS</span>
                  </div>
                   /.description-block 
                </div>
              </div>
               /.row 
            </div>-->
<!-- /.box-footer -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activitytab" data-toggle="tab">TAB</a></li>
              <li><a href="#activitykre" data-toggle="tab">KRE</a></li>
              <li><a href="#activitydep" data-toggle="tab">DEP</a></li>
              <li><a href="#activityaku" data-toggle="tab">Akumulasi</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activitytab">
                <!-- Post -->
                <div class="row">        <!-- left column -->            
                <div class="col-md-12">
			<table id="report121" class="display" cellspacing="0" width="100%">
				<thead>
                                        <tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Kode Trans</th>
						<th>No Rekening</th>
						<!--<th>Nama Nasabah</th>-->
                                                <th>Debet</th>
                                                <th>Kredit</th>
                                                <th>Keterangan</th>                                                
					</tr>
				</thead>
<!--				<tfoot>
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Uraian</th>
						<th>No Bukti</th>
						<th>Penerimaan</th>
						<th>Pengeluaran</th>
					</tr>
				</tfoot>-->
                                
				<tbody>
                                        <?php if($this->session->userdata('tab_ses')){
                                            $no = 1;
                                                foreach ($this->session->userdata('tab_ses') as $val){                                                                                               
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td><?php echo $val->tgl_trans; ?></td>
                                                            <td><?php echo $val->kode_trans; ?></td>
                                                            <td><?php echo $val->no_rekening; ?></td>
                                                            <!--<td><?php //echo $val->nama_nasabah; ?></td>-->
                                                            <td align="right"><?php echo number_format($val->setor,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($val->tarik,2,',','.'); ?></td>
                                                            <td><?php echo $val->keterangan; ?></td>                                                            
                                                        </tr>
                                                    <?php
                                                    $no++;
                                            }
                                        }
                                        //var_dump($data);
                                        ?>
					
				</tbody>
			</table>
                </div>
            
                <!-- /.form-group -->
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="activitykre">
                <!-- The timeline -->
                <div class="row">        
                <div class="col-md-12">
			<table id="report_kre" class="display" cellspacing="0" width="100%">
				<thead>
                                        <tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Kode trans</th>
						<th>No Rekening</th>
						<th>Ang KE</th>
                                                <th>Pokok</th>
                                                <th>Basil</th>
                                                <th>Denda</th>
                                                <th>Adm</th>
                                                <th>Keterangan</th>                                                
					</tr>
				</thead>
<!--				<tfoot>
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Uraian</th>
						<th>No Bukti</th>
						<th>Penerimaan</th>
						<th>Pengeluaran</th>
					</tr>
				</tfoot>-->
                                
				<tbody>
                                        <?php if($this->session->userdata('kre_ses')){
                                            $no = 1;
                                                foreach ($this->session->userdata('kre_ses') as $val){                                                                                               
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td><?php echo $val->TGL_TRANS; ?></td>
                                                            <td><?php echo $val->KODETRANS; ?></td>
                                                            <td><?php echo $val->NO_REKENING; ?></td>
                                                            <td><?php echo $val->ANGSURAN_KE; ?></td>
                                                            <td align="right"><?php echo number_format($val->POKOK,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($val->BUNGA,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($val->DENDA,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($val->ADM,2,',','.'); ?></td>
                                                            <td><?php echo $val->KET; ?></td>                                                            
                                                        </tr>
                                                    <?php
                                                    $no++;
                                            }
                                        }
                                        //var_dump($data);
                                        ?>
					
				</tbody>
			</table>
                </div>
                </div>
              </div>
              <!-- /.tab-pane -->
              
              <div class="tab-pane" id="activitydep">
                <div class="row">        <!-- left column -->            
                <div class="col-md-12">
			<table id="report_dep" class="display" cellspacing="0" width="100%">
				<thead>                                    	
                                        <tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Kode Trans</th>
						<th>No Rekening</th>
						<th>Pokok</th>
                                                <th>Bunga</th>
                                                <th>Pajak</th>
                                                <th>Adm</th>
                                                <th>Keterangan</th>                                                
					</tr>
				</thead>
<!--				<tfoot>
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Uraian</th>
						<th>No Bukti</th>
						<th>Penerimaan</th>
						<th>Pengeluaran</th>
					</tr>
				</tfoot>-->
                                
				<tbody>
                                        <?php if($this->session->userdata('dep_ses')){
                                            $no = 1;
                                                foreach ($this->session->userdata('dep_ses') as $val){                                                                                               
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td><?php echo $val->TGL_TRANS; ?></td>
                                                            <td><?php echo $val->KODE_TRANS; ?></td>
                                                            <td><?php echo $val->NO_REKENING; ?></td>                                                   
                                                            <td align="right"><?php echo number_format($val->POKOK,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($val->BUNGA,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($val->PAJAK,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($val->ADM,2,',','.'); ?></td>
                                                            <td><?php echo $val->KET; ?></td>                                                            
                                                        </tr>
                                                    <?php
                                                    $no++;
                                            }
                                        }
                                        //var_dump($data);
                                        ?>
					
				</tbody>
			</table>
                </div>
            
                <!-- /.form-group -->
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="activityaku">
                <div class="row">        <!-- left column -->            
                <div class="col-md-12">
                    <p>TABUNGAN</p>
                        <table id="report121" class="display" cellspacing="0" width="100%">
                        <thead>
                                        <tr>
						<th>No</th>
						<th>Setoran</th>
						<th>Tarikan</th>
						<th>Saldo</th>                                                
					</tr>
				</thead>
                                <tbody>
                        <?php 
                          if($this->session->userdata('tab_ses_sum')){
                                $no = 1;                              
                                    foreach ($this->session->userdata('tab_ses_sum') as $val){                                                                                               
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo number_format($val->setoran,2,",","."); ?></td>
                                                <td><?php echo number_format($val->tarikan,2,",","."); ?></td>
                                                <td><?php echo number_format($val->saldo,2,",","."); ?></td>
                                                <!--<td><?php //echo $val->nama_nasabah; ?></td>-->                                                                                           
                                            </tr>
                                        <?php
                                        $no++;
                                }
                            }
                            //var_dump($data);
                        ?>
                                </tbody>
                        </table>
                    <p>KREDIT</p>
                        <table id="report121" class="display" cellspacing="0" width="100%">
                        <thead>
                                        <tr>
						<th>No</th>
						<th>Pokok</th>
						<th>Bunga</th>
						<th>Denda</th>
						<th>Adm</th>                                                
						<th>Total</th>                                                
					</tr>
				</thead>
                                <tbody>
                        <?php 
                          if($this->session->userdata('kre_ses_sum')){
                                $no = 1;                              
                                    foreach ($this->session->userdata('kre_ses_sum') as $val){                                                                                               
                                        ?>  
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo number_format($val->apokok,2,",","."); ?></td>
                                                <td><?php echo number_format($val->abunga,2,",","."); ?></td>
                                                <td><?php echo number_format($val->adenda,2,",","."); ?></td>
                                                <td><?php echo number_format($val->aadm,2,",","."); ?></td>                                                                                          
                                                <td><?php echo number_format($val->atotal,2,",","."); ?></td>                                                                                          
                                            </tr>
                                        <?php
                                        $no++;
                                }
                                $no = 1;                              
                                    foreach ($this->session->userdata('kre_ses_sum') as $val1){                                                                                               
                                        ?>  
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo number_format($val1->rpokok,2,",","."); ?></td>
                                                <td><?php echo number_format($val1->rbunga,2,",","."); ?></td>
                                                <td><?php echo number_format($val1->rdenda,2,",","."); ?></td>
                                                <td><?php echo number_format($val1->radm,2,",","."); ?></td>
                                                <td><?php echo number_format($val1->rtotal,2,",","."); ?></td>                                                                                         
                                            </tr>
                                        <?php
                                        $no++;
                                }
                            }
                            //var_dump($data);
                        ?>
                                </tbody>
                        </table>
                </div>
            
                <!-- /.form-group -->
                </div>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <div class="col-md-3">
            <!--<a href="#" class="btn btn-primary btn-block margin-bottom">Back</a>-->  
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url();?>dist/img/user4-128x128.jpg" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo $NAMA_NASABH;?></h3>

              <p class="text-muted text-center"><?php echo strtolower($USERNAME_NS); ?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Nomor Rekening</b> <a class="pull-right"><?php echo $NO_REKENING; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Saldo</b> <a class="pull-right"><?php echo $SALDO_NSBAH; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Status</b> <a class="pull-right"><?php echo $STATUS_NBAH; ?></a>
                </li>
              </ul>

<!--              <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
            <div class="box box-primary">
                
          <div class="box-header with-border">
				<h3 class="box-title">Cari berdasarkan tanggal</h3>
			</div>
                <?php echo validation_errors(); ?>
            <div class="box-body">
               <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="agent/transaksi/logagent" method="post">
               <div class="form-group">
                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Tanggal</label>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input id="tgl_fr" name="tgl_fr" type="text" class="form-control input-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                    </div>
                </div>
<!--                <div class="form-group">
                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Sampai dengan</label>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input id="tgl_to" type="text" name="jam_to" class="form-control input-sm">
                    </div>
                </div>	-->
                <div class="form-group">
                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Agent</label>
                    <select multiple class="form-control" name="listagent">                           
                            <?php 
                            if($USERNMGROUP){
                                foreach ($USERNMGROUP as $v_usrgroup) {
                                    echo "<option value=".$v_usrgroup->id.">".$v_usrgroup->id." - ".$v_usrgroup->username."</option>";
                                }
                            }                        
                           ?>                          
                    </select>
                </div>	
            <div class="ln_solid"></div>
            <div class="form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12"></label>
              <div class="col-md-12 col-sm-12 col-xs-12">                             
                <button type="submit" class="btn btn-primary">Go</button>
              </div>
            </div>
            </form>
        </div>
      </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

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
<!--    	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js">
	</script>-->
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/media/js/jquery.dataTables.js">
	</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/extensions\Buttons/js/dataTables.buttons.js">
	</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/extensions\Buttons/js/buttons.flash.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js">
	</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/extensions/Buttons/js/buttons.html5.js">
	</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/extensions/Buttons/js/buttons.print.js">
	</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/examples/resources/syntax/shCore.js">
	</script>

	<script type="text/javascript" language="javascript" class="init">
        $(document).ready(function() {
                $('#report121').DataTable( {
                        dom: 'Bfrtip',
                        paging: true,  
                        bFilter: true,
                        ordering: true,
                        searching: true,
                        //"scrollY":"1000px",
                        //"scrollCollapse": true,
                        header: true,
                            title: 'My Table Title',
                            orientation: 'landscape',
                            customize: function(doc) {
                               doc.defaultStyle.fontSize = 8; //<-- set fontsize to 16 instead of 10 
                            },
                        buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                } );
                $('#report_kre').DataTable( {
                        dom: 'Bfrtip',
                        paging: true,  
                        bFilter: true,
                        ordering: true,
                        searching: true,
                        //"scrollY":"1000px",
                        //"scrollCollapse": true,
                        header: true,
                            title: 'My Table Title',
                            orientation: 'landscape',
                            customize: function(doc) {
                               doc.defaultStyle.fontSize = 8; //<-- set fontsize to 16 instead of 10 
                            },
                        buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                } );
                $('#report_dep').DataTable( {
                        dom: 'Bfrtip',
                        paging: true,  
                        bFilter: true,
                        ordering: true,
                        searching: true,
                        //"scrollY":"1000px",
                        //"scrollCollapse": true,
                        header: true,
                            title: 'My Table Title',
                            orientation: 'landscape',
                            customize: function(doc) {
                               doc.defaultStyle.fontSize = 8; //<-- set fontsize to 16 instead of 10 
                            },
                        buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                } );
        } );    
        </script>
