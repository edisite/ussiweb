<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Filter Laporan Simpanan</h3>
            </div>
            <div class="box-body">
                <div class="col-md-12">
			<table id="report121" class="display nowrap" cellspacing="0" width="100%">
				<thead>
                                        <tr>
						<th>No</th>
						<th>No Rekening</th>
						<th>Nama Nasabah</th>
                                                <th>Basil</th>
                                                <th>Pajak</th>
                                                <th>Adm</th>
                                                <th>Saldo</th>
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
                                        <?php if($mutasi){
                                            $no = 1;
                                                foreach ($mutasi as $val){   
                                                    $jml_bunga = $val->bunga;
                                                    $jml_pajak = $val->pajak;
                                                    $jml_admin = $val->admin;
                                                    $saldo = 0;
                                                      
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td><?php echo $val->no_rekening; ?></td>
                                                            <td><?php echo $val->nama_nasabah; ?></td>
                                                            <td align="right"><?php echo number_format($jml_bunga,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($jml_pajak,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($jml_admin,2,',','.'); ?></td>
                                                            
                                                            <td align="right"><?php echo number_format($saldo,2,',','.'); ?></td>
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

    </div>
</div>

	
	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js">
	</script>
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
<!--	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/examples/resources/demo.js">
	</script>-->
	<script type="text/javascript" language="javascript" class="init">
        $(document).ready(function() {
                $('#report121').DataTable( {
                        dom: 'Bfrtip',
                        buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                } );
        } );
        </script>