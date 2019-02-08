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
                    <div class="col-md-8 col-sm-3 col-xs-12">
                        <input id="tgl_fr" name="tgl_fr" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo $tgl_fr_t; ?>">
                    </div> 
               </div>
               <div class="form-group">
                   <label class="control-label col-md-4 col-sm-4 col-xs-12">Sampai Tanggal</label>
                    <div class="col-md-8 col-sm-3 col-xs-12">
                        <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo $tgl_to_t; ?>">
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12"></label>
                  <div class="col-md-8 col-sm-4 col-xs-12">
                    <button type="submit" class="btn btn-primary pull-left">Submit</button>
                  </div>
                    
                </div>                
                </form>
             </div>
              <div class="col-sm-4">
                  <div class="box invoice-col">
<!--                      <div class="box-body">-->
                          <!--<div class="table table-responsive">-->
                              <table class="table table-responsive">  
                    <?php

                    if($datauser):
                        foreach ($datauser as $vu) {
                            ?>
                                  <tr>
                                      <td><b>Agent ID <b></td><td>#<?php echo $vu->id; ?></td>
                                  </tr>
                                  <tr>
                                      <td><b>Username <b></td><td><?php echo $vu->username; ?> (<?php echo $vu->first_name.' '.$vu->last_name; ?>)</td>
                                  </tr>
                                  <tr>
                                      <td colspan="2"><div class="pull-left"><a href="report/com/agent" class="btn btn-primary">Cari agent lain</a></div></td>
                                  </tr>
                                <?php
                        }
                        endif;
                    ?>  
                              </table>
                          <!--</div>-->
                      <!--</div>-->
                </div>
          </div>
          </div>        
          <?php
                    if($tgl):
                ?> 
                <div class="box-body">  
                    <div class="small-box bg-aqua-active">
                    <!-- begin -->
                      
                     <?php
                          if($tgl): echo $tgl;
                          endif;
                      ?> 
                    <!--//end-->
                </div>
          <?php          
                endif;
          ?>
          
          <div class="pull-right">
                <a href="report/com/history_transaksi/<?php echo $data; ?>/<?php echo $vu->id; ?>" target="_blank" class="btn btn-primary">History Transaksi</a>
                </div>
          <hr>
          
          <div class="box-body">
            <div class="col-lg-6 col-md-6">
		<div class="box box-danger box-solid">
                     <div class="box-header with-border">
                         <h3 class="box-title">Report Transaksi <strong>Payment ( Pembayaran )</strong></h3>                      
                     </div>
                    <div class="table">
                      <table class="table table-responsive">                        
                        <?php
                            if($data_profitp): echo $data_profitp;
                            endif;
                        ?>
                      </table>
                    </div>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="box box-danger box-solid">
                     <div class="box-header with-border">
                         <h3 class="box-title">Report Transaksi <strong>Penjualan</strong></h3>
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
                         
          </div>
          <div class="box-body">
              <div class="box box-solid">
              <div class="col-sm-4 col-xs-6">                  
                  <div class="description-block border-right">
                      <h5 class="description-header">
                          Rp
                          <?php 
                          if(empty($grand_gtt)){
                              echo 0;
                          }else{
                               echo $grand_gtt;
                          }
                          ?>
                      </h5>
                    <br>
                    <span class="description-text">TOTAL TRANSAKSI</span>
                  </div>
                  <!-- /.description-block -->
                </div>
              <div class="col-sm-4 col-xs-6">
                  <div class="description-block border-right">
                      <h5 class="description-header">
                          Rp
                          <?php 
                          if(empty($grand_gtb)){
                              echo 0;
                          }else{
                            echo $grand_gtb;
                          }
                          ?>
                      </h5>
                    <br>
                    <span class="description-text">TOTAL BASIL AGENT</span>
                  </div>
                  <!-- /.description-block -->
                </div>
              <div class="col-sm-4 col-xs-6">
                  <div class="description-block border-right">

                      <h5 class="description-header">
                          Rp
                          <?php 
                          if(empty($grand_gts)){
                              echo 0;
                          }else{
                              echo $grand_gts;
                          }
                          ?>
                      </h5>
                    <br>
                    <span class="description-text">TOTAL SETORAN</span>
                  </div>
                  <!-- /.description-block -->
                </div>
              </div>
          </div>
          <hr>
          <div class="box-body">
            <div class="col-lg-4 col-md-4">
		<div class="box box-primary box-solid" style="height: 100px;">
                    <div class="box-header with-border">
			<h3 class="box-title">Tagihan Sebelum Tgl - <?php echo $tgl_before; ?></h3>
                    </div>
                    <div class="table swidget-box-2" id="piemnt">
                            <div class="widget-detail-2 center-margin">
                                    <h3 class="m-b-0" data-plugin="counterup"><font color="red"><span style="font-size:50%;">Piutang</span> <?php echo $piutang." </font> | <font color='green'>".$terbayar;?> <span style="font-size:50%;">Terbayar</span></font></h3>

                            </div>

                            <div class="widget-detail-2">
                                    <h3 class="m-b-0" data-plugin="counterup"><?php echo $statussisa_tagihan; ?></h3>
                            </div>
                    </div>
		</div>
                
            </div> 
            <div class="col-md-8">
                <div class="box box-warning box-solid">
                     <div class="box-header with-border">
                         <h3 class="box-title">Data Pembayaran Periode ini</h3>
                         <div class="box-tools pull-right">
                            <a href="#myModal" class="btn btn-warning" role="button"  data-toggle="modal"><i class="glyphicon glyphicon-search"></i> Riwayat Setoran </a>                        
                        </div>
                     </div>
                    <div class="table">
                      <table class="table table-responsive table-bordered">
                          <tr>
                              <th>No.</th>
                              <th>Tanggal Bayar</th>
                              <th>Setoran (Rp)</th>
                              <th>Basil (Rp)</th>
                          </tr>
                        <?php
                            if($data_now): echo $data_now;
                            endif;
                        ?>
                      </table>
                    </div>
                </div>
            </div>           
                         
          </div> 
          <hr>
          
          <?php
            $getuserid = '';
            $getname  = '';
            if($datauser):
            foreach ($datauser as $vu) {
                $getuserid  =  $vu->id; 
                $getname    = $vu->username; 
            }
            endif;

            ?>
          <div class="box-body">
               
            <div class="col-md-12">
                <div class="box box-success box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Setor Tagihan</h3>
                </div>
                    <form class="form-horizontal" action="report/com/setoran" method="post">
                        <input type="hidden" name="userid" value="<?php echo $getuserid; ?>">
                        <input type="hidden" name="username" value="<?php echo $getname; ?>">
                        <div class="box-body">
                            <div class="form-group">
                              <label for="tgl" class="col-sm-2 control-label">Tanggal Tagihan</label>
                              <div class="col-sm-10">
                                  <input type="input" class="date-picker form-control col-md-7 col-xs-12" id="tgl_tagihan" name="tanggal" value="<?php echo date('m/d/Y', strtotime(date('Y-m-d'))); ?>" placeholder="Input Tanggal">
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="Nominal" class="col-sm-2 control-label">Jumlah Setoran</label>
                              <div class="col-sm-10">
                                  <input type="tanpa-rupiah" class="form-control col-md-7 col-xs-12" id="tanpa-rupiah" name="nominal_setoran" placeholder="Rp">

                              </div>
                            </div>
                            <div class="form-group">
                              <label for="Nominal" class="col-sm-2 control-label">Tarikan Bagi Hasil</label>
                              <div class="col-sm-10">
                                  <input type="basilsetoran" class="form-control col-md-7 col-xs-12" id="basilsetoran" name="basilsetoran" placeholder="Rp">

                              </div>
                            </div> 
                            <div class="form-group">
                              <label for="Keterangan" class="col-sm-2 control-label">Keterangan</label>
                              <div class="col-sm-10">
                                  <textarea class="form-control" rows="3" name="keterangan" placeholder="Enter ..."></textarea>
                              </div>
                            </div>                        
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
    <!--                      <button type="cancel" class="btn btn-default">Cancel</button>-->
    <button type="submit" class="btn btn-info pull-right" name="tbsubmit">Bayar</button>
                        </div>
                    <!-- /.box-footer -->
                    </form>
                </div>
            </div>
    </div>

</div>
          
    <div class="modal fade" role="dialog" tabindex="-1" id="myModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Riwayat Setoran</h4>
                        	
                    </div>
                    <div class="modal-body"> 
                        <h5> Data 1 Bulan Terakhir</h5>
						<table id="example" class="table table-responsive table-hover table-bordered display groceryCrudTable" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Tanggal Bayar</th>
                                                            <th>Setoran Pokok</th>         
                                                            <th>Basil</th>
                                                            <th>Ket</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>     
                                                        <?php
                                                            if($data_last_month):                                                               
                                                                echo $data_last_month;
                                                            endif;                                                        
                                                        ?>
                                                    </tbody>
                                                </table>
                    </div>
                    <div class="modal-footer">
                        <a target="_blank" href="<?php echo base_url();?>admin/report/com/setor_history_byagent/<?php echo $data."/".$vu->id; ?>" class="btn btn-primary btn-sm pull-left" role="button" onclick="return confirm('Klik OK untuk melihat seluruh riwayat setoran!');"></i><i class="glyphicon glyphicon-link"></i>History lebih detail</a>
                                                                            
                        <button class="btn btn-default pull-right" type="button" data-dismiss="modal">Close</button>
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
      $(document).ready(function() {
        $('#tgl_tagihan').daterangepicker({
          
          singleDatePicker: true,
          //showDropdowns: true
          calender_style: "picker_4"
          
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
      
      /* Tanpa Rupiah */
	var tanpa_rupiah = document.getElementById('tanpa-rupiah');
	tanpa_rupiah.addEventListener('keyup', function(e)
	{
		tanpa_rupiah.value = formatRupiah(this.value);
	});
	var basilsetoran = document.getElementById('basilsetoran');
	basilsetoran.addEventListener('keyup', function(e)
	{
		basilsetoran.value = formatRupiah(this.value);
	});
        var tanpa_rupiah = document.getElementById('tanpa-rupiah');
	tanpa_rupiah.addEventListener('keyup', function(e)
	{
		tanpaaja.value = convert_to_number(this.value);
	});

	
	/* Dengan Rupiah */
	var dengan_rupiah = document.getElementById('dengan-rupiah');
	dengan_rupiah.addEventListener('keyup', function(e)
	{
		dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
	});
        
        
	
	/* Fungsi */
	function formatRupiah(angka, prefix)
	{
		var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split	= number_string.split(','),
			sisa 	= split[0].length % 3,
			rupiah 	= split[0].substr(0, sisa),
			ribuan 	= split[0].substr(sisa).match(/\d{3}/gi);
			
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
	}
        function convertToAngka(rupiah)
        {
                return parseInt(rupiah.replace(/,.*|[^0-9]/g, ''), 10);
        }
        function convert_to_number(rupiah)
	{
		return intval(preg_replace(/,.*|[^0-9]/, '', rupiah));
	}
    </script>
