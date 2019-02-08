<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| AUTO-LOADER (module-specific)
| -------------------------------------------------------------------
| For detailed usage, please check the comments from original file:
| application/config/autoload.php
|
*/

$autoload['packages'] = array();

$autoload['libraries'] = array('user_agent','session');

$autoload['drivers'] = array();

$autoload['helper'] = array('string');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array(
        'Admin_user2_model'
);
