<?php echo $form->messages(); ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Create User</h3>
			</div>
			<div class="box-body">
				<?php echo $form->open(); ?>

					<?php echo $form->bs3_text('Username', 'username'); ?>
                                        
					<?php echo $form->bs3_text('First Name', 'first_name'); ?>
					<?php echo $form->bs3_text('Last Name', 'last_name'); ?>

                                        <div class="form-group">
                                            <label for="cabang">Unit Kerja / Kode Kantor</label>
                                            <select class="form-control" name="kodekantor">
                                                <?php 
                                                        if( !empty($kodekantor)) :
                                                            //$kantor =  array();
                                                            foreach ($kodekantor as $val) {
                                                                $kantor = $val->KODE_KANTOR." - ".$val->NAMA_KANTOR;                                               
                                                                ?>
                                                                    <option value="<?php echo $val->KODE_KANTOR; ?>"><?php echo $kantor; ?></option>
                                                
                                                                <?php
                                                            }
                                                            //echo $form->field_dropdown('kantor',$kantor,'','',''); 

                                                        endif;

                                               ?>

                                            </select>                                                                      
                                        </div>
                            
                                        
                                        <?php if ( !empty($groups) ): ?>
					<div class="form-group">
						<label for="groups">Groups</label>
						<div>
						<?php foreach ($groups as $group): ?>
							<label class="checkbox-inline">
								<input type="checkbox" name="groups[]" value="<?php echo $group->id; ?>"> <?php echo $group->name; ?>
							</label>
						<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>
                                        <?php echo $form->bs3_password('Password', 'password'); ?>
					<?php echo $form->bs3_password('Konfirmasi Password', 'retype_password'); ?>

					<?php echo $form->bs3_submit(); ?>
					
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>
    
	
</div>