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
						<th>Kelompok</th>
						<th>Giro Rek</th>
                                                <th>Nominal</th>
                                                <th>Tab Rek</th>
                                                <th>Nominal</th>
                                                <th>Deb Rek</th>
                                                <th>Nominal</th>
                                                <th>Sert Dep Rek.</th>
                                                <th>Nominal</th>
                                                <th>Total Rek</th>
                                                <th>Nominal</th>
					</tr>
				</thead>                                
				<tbody>
                                        <?php if($mutasi){                                            
                                                foreach ($mutasi as $val){   
                                                    $jml_bunga = $val->bunga;
                                                    $jml_pajak = $val->pajak;
                                                    $jml_admin = $val->admin;
                                                    $saldo = 0;
                                                      
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $val->ID; ?></td>
                                                            <td><?php echo $val->DESKRIPSI; ?></td>
                                                            <td><?php echo $val->JML_REK_GIRO; ?></td>
                                                            <td><?php echo $val->NOMINAL_GIRO; ?></td>
                                                            <td><?php echo $val->JML_REK_TABUNGAN; ?></td>
                                                            <td><?php echo $val->NOMINAL_TABUNGAN; ?></td>
                                                            <td><?php echo $val->JML_REK_DEPOSITO; ?></td>
                                                            <td><?php echo $val->NOMINAL_DEPOSITO; ?></td>
                                                            <td><?php echo $val->JML_REK_SERT_DEPOSITO; ?></td>
                                                            <td><?php echo $val->NOMINAL_SERT_DEPOSITO; ?></td>
                                                            <td><?php echo $val->JML_REK_LAINNYA; ?></td>
                                                            <td><?php echo $val->NOMINAL_LAINNYA; ?></td>                                                            
                                                        </tr>
                                                    <?php
                                                   
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