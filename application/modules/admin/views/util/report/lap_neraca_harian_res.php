<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">MUTASI NERACA</h3>
            </div>
            <div class="box-body">
                <div class="col-md-12">
			<table id="report121" class="display nowrap" cellspacing="0" border ="0" width="100%">
				<thead>
                                        <tr>
						<th></th>
						<th>Periode 06 Jan 2017</th>
						<th></th>
                                                <th></th>
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
                                            
                                             <tr>
						<th>Kode Perk</th>
						<th>Nama Perkiraan</th>						
						<th></th>
                                                <th align="right">Jumlah</th>
                                                                                               
						                                              
					</tr>
                                        <?php if($neraca){
                                            
                                            foreach ($neraca as $val){                                                  
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $val->kode_perk; ?></td>
                                                            
                                                            <?php
                                                            if(strlen($val->kode_perk) <= 3){
                                                                ?>
                                                            <td><b><?php echo $val->nama_perk; ?></b></td>
                                                            <td></td>
                                                            <td align="right"><?php echo number_format($val->saldo_akhir,2,',','.'); ?></td>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                    <td>- -<?php echo str_replace('Simpanan', '',$val->nama_perk); ?></td>
                                                                    <td align="right"><?php echo number_format($val->saldo_akhir,2,',','.'); ?></td>
                                                                    <td></td>
                                                                 <?php
                                                            
                                                            }
                                                            ?>
                                                            
<!--                                                            <td align="right"><?php echo number_format($val->saldo_awal,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($val->mut_debet,2,',','.'); ?></td>
                                                            <td align="right"><?php echo number_format($val->mut_kredit,2,',','.'); ?></td>-->
                                                            
                                                                   
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