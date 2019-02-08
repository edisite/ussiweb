<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Daftar Perkiraan</h3>
            </div>
            <div class="box-body">
                <div class="col-md-12">
			<table id="report121" class="display wrap" cellspacing="0" width="100%">
                                
         
                                <thead>
                                        <tr>
						<th>Kode Perkiraan</th>
						<th>Nama Perkiraan</th>
						<th>Induk</th>
                                                <th>Level</th>
                                                <th>Type</th>
					</tr>
				</thead>
                                
				<tbody>
                                        <?php if($mutasi){
                                                foreach ($mutasi as $val){   
                                                      
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $val->KODE_PERK; ?></td>
                                                            <td><?php 
                                                                if($val->LEVEL_PERK == 0){
                                                                    echo "<b>".$val->NAMA_PERK."</b>";
                                                                }elseif($val->LEVEL_PERK == 1){
                                                                    echo "- ".$val->NAMA_PERK;
                                                                }elseif($val->LEVEL_PERK == 2){
                                                                    echo "- - ".$val->NAMA_PERK;
                                                                }elseif($val->LEVEL_PERK == 3){
                                                                    echo "- - - ".$val->NAMA_PERK;
                                                                }else{
                                                                    echo "- - - - ".$val->NAMA_PERK;
                                                                }
                                                            ?></td>
                                                            <td><?php echo $val->KODE_INDUK; ?></td>
                                                            <td align="right"><?php echo $val->LEVEL_PERK; ?></td>
                                                            <td align="right"><?php echo $val->TYPE_PERK; ?></td>
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
                        bFilter: false,
                        ordering: false,
                        searching: true,
                        "scrollY":"500px",
                        "scrollCollapse": true,
                        buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                } );
        } );
        </script>