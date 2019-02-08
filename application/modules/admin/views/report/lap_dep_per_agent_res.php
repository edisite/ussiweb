<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Report Deposito</h3>
            </div>
            <div class="box-body">
                <div class="col-md-12">
			<table id="report121" class="display" cellspacing="0" width="100%">
				<thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Transaksi ID</th>
                                        <th>Jenis Transaksi</th>
                                        <th>Kode Transaksi</th>
                                        <th>Poin</th>
                                        <th>Deptrans ID</th>
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
                        paging: true,  
                        bFilter: true,
                        ordering: false,
                        searching: true,
                        //"scrollY":"1000px",
                        "scrollCollapse": false,
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