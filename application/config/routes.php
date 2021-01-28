<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'Home';
$route['page-not-found'] = 'ErrorPage';
$route['404_override'] = 'ErrorPage';
$route['500_override'] = 'ErrorPage/error_500';
$route['translate_uri_dashes'] = FALSE;

$route['login/verify'] = "Auth/userVerification";
