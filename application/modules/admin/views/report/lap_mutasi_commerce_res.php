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
						<th>Tanggal Trans</th>
						<th>No Customer</th>
						<th>Type</th>
						<th>Kode Trans</th>
                                                <th>MY Kode Trans</th>
                                                <th>Pokok</th>
                                                <th>Adm</th>
                                                <th>No Rekening</th>
                                                <th>Kuitansi</th>
                                                <th>Agentid</th>
                                                <th>Kode Kantor</th>
					</tr>
				</thead>

				<tbody>
                                    
                                        <?php if($mutasi){
                                            $no = 1;
                                                foreach ($mutasi as $val){   
//                                                    $jml_setoran = $val->setoran;
//                                                    $jml_penarikan = $val->penarikan;
//                                                    if($val->DK == 'D'){
//                                                        $saldo = $saldo + $jml_setoran;  
//                                                    }else{
//                                                        $saldo = $saldo - $jml_penarikan;
//                                                    } 
                                                      
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td><?php echo $val->TGL_TRANS; ?></td>
                                                            <td><?php echo $val->NOCUSTOMER; ?></td>
                                                            <td><?php echo $val->COMTYPE; ?></td>
                                                            <td><?php echo $val->KODE_TRANS; ?></td>
                                                            <td><?php echo $val->MY_KODE_TRANS; ?></td>
                                                            <td><?php echo $val->POKOK; ?></td>
                                                            <td><?php echo $val->ADM; ?></td>
                                                            <td><?php echo $val->NO_REKENING; ?></td>
                                                            <td><?php echo $val->KUITANSI; ?></td>
                                                            <td><?php echo $val->USERID; ?></td>
                                                            <td><?php echo $val->KODE_KANTOR; ?></td>
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