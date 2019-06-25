<?php echo $form->messages(); ?>


<div class="row">

	<div class="col-md-8">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">User Info</h3>
			</div>
			<div class="box-body">
				<?php echo $form->open(); ?>
                                        <div class="col-md-6">
					
                                            <div class="form-group"><label for="nasabahid">Nasabah ID</label><input type="text" name="nasabahid" value="<?php echo $nasabahid; ?>" id="nasabahid"  class="form-control" readonly="" />
                                            </div>					
                                            <div class="form-group"><label for="first_name">Nama</label><input type="text" name="first_name" value="<?php echo $nama; ?>" id="first_name"  class="form-control" readonly=""/>
                                            </div>					
                                            <div class="form-group"><label for="addresss">Alamat</label><textarea name="addresss" cols="40" rows="10" id="last_name"  class="form-control" readonly=""><?php echo $alamat; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <input type="hidden" name="nasabahid" value="<?php echo $nasabahid; ?>" id="nasabahid"  class="form-control" />
					<?php echo $form->bs3_text('Username (*10 Huruf)', 'username',$usernamesugest); ?>
					<?php echo $form->bs3_text('Email', 'email'); ?>                                        
					<?php echo $form->bs3_text('PIN (*4 digit)', 'pin',random_string('numeric',4)); ?>                                        
					<?php echo $form->bs3_password('Password', 'password'); ?>
					<?php echo $form->bs3_password('Retype Password', 'retype_password'); ?>

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

					<?php echo $form->bs3_submit(); ?>
                                        </div>
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>
	
</div>