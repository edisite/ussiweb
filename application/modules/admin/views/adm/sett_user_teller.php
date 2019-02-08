<?php echo $form->messages(); ?>
<?php
foreach($dtuser as $subdata){
    if($subdata->JABATAN){
        $par_jabatan = $subdata->JABATAN;
    }
    if($subdata->PENERIMAAN){
        $par_terima = $subdata->PENERIMAAN;
    }
    if($subdata->PENGELUARAN){
        $par_keluar = $subdata->PENGELUARAN;
    }
    if($subdata->PENERIMAAN_OB){
        $par_terimaob = $subdata->PENERIMAAN_OB;
    }
    if($subdata->PENGELUARAN_OB){
        $par_keluarob = $subdata->PENGELUARAN_OB;
    }
    if($subdata->UNIT_KERJA){
        $par_unit_kerja= $subdata->UNIT_KERJA;
    }
    
}
?>
<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Update Hak Akses Teller</h3>
			</div>
			<div class="box-body">
				<?php echo $form->open(); ?>
					<table class="table table-bordered">
						<tr>
							<th style="width:120px">Username </th>
							<td><?php echo $target->username; ?></td>
						</tr>
						<tr>
							<th>First Name </th>
							<td><?php echo $target->first_name. ' '.$target->last_name; ?></td>
						</tr> 
						<tr>
							<th>Jabatan </th>
							<td><?php 
                                                        
                                                        echo $form->bs3_text('', 'tes',$par_jabatan); ?></td>
						</tr>
                                                <tr>
							<th>Kode Kantor</th>
							<td>
                                                            <div>
                                                                  <select class="form-control" name="paytype">                                                                     
                                                                        <?php foreach ($kdkantor as $subkdkantr) {
                                                                              if($par_unit_kerja == $subkdkantr->KODE_KANTOR){
                                                                                  $inj_selected = "selected";
                                                                              }else{
                                                                                  $inj_selected = "";
                                                                              }  
                                                                          echo "<option value=".$subkdkantr->KODE_KANTOR." ".$inj_selected.">".$subkdkantr->KODE_KANTOR." - ".$subkdkantr->NAMA_KANTOR."</option>";
                                                                      }?>                          
                                                                  </select>
                                                            </div>
                                                        </td>
						</tr>
					</table>
                                        <table class="table table-responsive">
                                            <tr>
                                                <td>
                                                    <?php echo $form->bs3_text('Max Penerimaan(T)', 'tes',$par_terima); ?>
                                                    
                                                </td>
                                                <td>
                                                    <?php echo $form->bs3_text('Max Pengeluaran(T)', 'tes',$par_keluar); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php echo $form->bs3_text('Max Penerimaan(OB)', 'tes',$par_terimaob); ?>
                                                    
                                                </td>
                                                <td>
                                                    <?php echo $form->bs3_text('Max Pengeluaran(OB)', 'tes',$par_keluarob); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <label>Kode Perkiraan Kas</label>
                                                        <div>
                                                            <select class="form-control" name="paytype">
                                                                <option value="T">T - Tunai</option>
                                                                <option value="O">O - Non Tunai</option>                            
                                                          </select>
                                                        </div>                            
                                                    </div> 
                                                </td>
                                                <td>
                                                     <div class="form-group">
                                                        <label>Kode Perk Ledger</label>
                                                        <div>
                                                            <select class="form-control" name="paytype">
                                                                <option value="T">T - Tunai</option>
                                                                <option value="O">O - Non Tunai</option>                            
                                                          </select>
                                                        </div>                            
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                
                                                <td colspan="2">
                                                    <div class="form-group">
                                                        <label for="groups">Setting User</label>
                                                        <div>
                                                            <div class="form-group">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" name="tes" value="tes"> None
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" name="tes" value="tes"> Teller
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" name="tes" value="tes"> Otorisator
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" name="tes" value="tes"> Counter Sign
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
					<?php echo $form->bs3_submit(); ?>
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>
	
</div>
