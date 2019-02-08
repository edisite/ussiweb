<?php
define('DEFAULTVAL', '');
if(!$SETHEADLINE){
    $SETHEADLINE = DEFAULTVAL;
}
if(!$SETWARNTEXT){
    $SETWARNTEXT = DEFAULTVAL;
}
if(!$SETINFOTEXT){
    $SETINFOTEXT = DEFAULTVAL;
}
if(!$SETCOLULANG){
    $SETCOLULANG = DEFAULTVAL;
}
if(!$SETCOLHOME){
    $SETCOLHOME = DEFAULTVAL;
}



?>
<div class="error-page">
	<h2 class="headline text-yellow"> <?php echo $SETHEADLINE; ?></h2>
	<div class="error-content">
		<h3><i class="fa fa-warning text-yellow"></i> <?php echo $SETWARNTEXT; ?></h3>
		<p>
			<?php echo $SETINFOTEXT; ?>
			Meanwhile, you may <a href="<?php echo $SETCOLHOME; ?>">return to dashboard</a> or <a href="<?php echo $SETCOLULANG; ?>">retry again</a>
		</p>
	</div>
</div>