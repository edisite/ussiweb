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

$autoload['libraries'] = array('user_agent','MY_Log');

$autoload['drivers'] = array();

$autoload['helper'] = array('string');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array(
	'api_key_model'	=> 'api_keys',
	'user_model'	=> 'users',
        'Trans','Tab_model','Kre_model','App_model',
        'Sys_daftar_user_model','Perk_model',
        'Sysmysysid_model','User2_model','Trf_model','Ion_auth_model',
        'Admin_user2_model','Kunci_model','Poin_model','Nas_model','Pay_model'
);
