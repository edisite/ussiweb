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
						<th>No Rekening</th>
						<th>Nama Nasabah</th>
                                                <th>Debet</th>
                                                <th>Kredit</th>                                        
                                                <th>Saldo Akhir</th>
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
                                    <?php
                                        foreach ($saldo_awal as $val) {
                                            $saldo = $val->saldo_awal;
                                        }
                                    ?>
                                        <tr>
                                            <td>Saldo Awal</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>                                            
                                            <td></td>
                                            <td><?php if($saldo){echo number_format($saldo,2,',','.');} ?></td>
                                        </tr>
                                        <?php if($mutasi){
                                            $no = 1;
                                                foreach ($mutasi as $val){   
                                                    $jml_setoran = $val->setoran;
                                                    $jml_penarikan = $val->penarikan;
//                                                    if($val->DK == 'D'){
//                                                        $saldo = $saldo + $jml_setoran;  
//                                                    }else{
//                                                        $saldo = $saldo - $jml_penarikan;
//                                                    } 
                                                      
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td><?php echo $val->tgl_trans; ?></td>
                                                            <td><?php echo $val->no_rekening; ?></td>
                                                            <td><?php echo $val->nama_nasabah; ?></td>
                                                            <td align="right"><?php echo number_format($jml_setoran,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($jml_penarikan,2,',','.'); ?></td>
                                                            
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
                        paging: false,  
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