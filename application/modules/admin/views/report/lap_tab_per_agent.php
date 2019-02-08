<?php echo validation_errors(); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Filter Laporan Simpanan</h3>
            </div>
            <div class="box-body">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="post">
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

<div class="box">
            <div class="box-header with-border">
                    <h3 class="box-title">Report Tabungan</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                          <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                          <i class="fa fa-times"></i></button>
                      </div>
            </div>
            <div class="box-body">
                <div class="col-md-12">
			<table id="report121" class="table table-bordered table-condensed table-striped" cellspacing="0" width="100%">
				<thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Transaksi ID</th>
                                        <th>Jenis Transaksi</th>
                                        <th>Kode Transaksi</th>
                                        <th>Poin</th>
                                        <th>Tabtrans ID</th>
                                        <th>No Rekening</th>
                                        <th>User ID</th>
                                        <th>Tarik</th>
                                        <th>Setor</th>
                                        <th>Tanggal</th>
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
                                        <?php if($history){
                                            $no = 1;
                                                foreach ($history as $val){                                                         
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td><?php echo $val->transaksi_id; ?></td>
                                                            <td><?php echo $val->jenis_transaksi; ?></td>
                                                            <td><?php echo $val->MY_KODE_TRANS; ?></td>
                                                            <td><?php echo $val->DEPTRANS_ID; ?></td>
                                                            <td><?php echo $val->NO_REKENING; ?></td>
                                                            <td><?php echo $val->POKOK_TRANS; ?></td>
                                                            <td><?php echo $val->tanggal; ?></td>                                                            
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
