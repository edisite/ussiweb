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

$autoload['libraries'] = array('form_validation');

$autoload['drivers'] = array();

$autoload['helper'] = array();

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array('App_model','Sys_daftar_user_model','Dep_model','Tab_model','Kre_model','Perk_model','Nas_model','Trans','Trf_model','Css_model','Sys_daftar_user_menu_model','Poin_model','Com_model');
