<?php
/**
 * @author 		Sijad aka Mr.Wosi
 * @link		<a href='http://skinod.com'>Skinod.com</a>
 * @copyright	2015 <a href='http://skinod.com'>Skinod.com</a>
 */

$ips_connect_key = 'b7705cb2cf70ee62efa97afab7a41f3b';
$remote_login = 'http://localhost/ips4/remote.php';

$email			= $_GET['email'];
$password		= $_GET['password'];

$key 			= md5($ips_connect_key . $email);

// fetch salt first
$res = json_decode(file_get_contents($remote_login . "?do=get_salt&id={$email}&key={$key}"), true);

$hash = crypt( $password, '$2a$13$' . $res['pass_salt'] );

$res = json_decode(file_get_contents($remote_login . "?do=login&id={$email}&key={$key}&password={$hash}"), true);

print_r($res);