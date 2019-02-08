<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| JWT Config
| -------------------------------------------------------------------------
| Values to be used in Jwt Client library
|
*/

$config['jwt_issuer'] = 'BMT';

// must be non-empty
$config['jwt_secret_key'] = 'a1s2d3f4g5';

// expiry time since a JWT is issued (in seconds); set NULL to never expired
$config['jwt_expiry'] = NULL;