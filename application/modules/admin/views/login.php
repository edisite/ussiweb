<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="login-box">
    <div class="login-logo"><img src="../assets/images/logo.png" width="360px" height="200px"></div>
	<div class="login-box-body">
		<p class="login-box-msg">Sign in</p>
		<?php echo $form->open(); ?>
			<?php echo $form->messages(); ?>
			<?php echo $form->bs3_text('Username', 'username', ENVIRONMENT==='development' ? '' : ''); ?>
			<?php echo $form->bs3_password('Password', 'password', ENVIRONMENT==='development' ? '' : ''); ?>			
                        <?php /*echo $form->field_recaptcha();*/ ?>
                        <div class="row">
				<div class="col-xs-8">
					<div class="checkbox">
						<label><input type="checkbox" name="remember"> Remember Me</label>
					</div>
				</div>
				<div class="col-xs-4">
					<?php echo $form->bs3_submit('Sign In', 'btn btn-primary btn-block btn-flat'); ?>
				</div>
			</div>
		<?php echo $form->close(); ?>
	</div>
</div>