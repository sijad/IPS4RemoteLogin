<?php
/**
 * @author 		Sijad aka Mr.Wosi
 * @link		<a href='http://skinod.com'>Skinod.com</a>
 * @copyright	2015 <a href='http://skinod.com'>Skinod.com</a>
 */

$_SERVER['SCRIPT_FILENAME']	= __FILE__;
$path	= '';

require_once $path . 'init.php';
\IPS\Session\Front::i();

$key = md5( md5( \IPS\Settings::i()->sql_user . \IPS\Settings::i()->sql_pass ) . \IPS\Settings::i()->board_start );

$login_type = 'email';

/* uncomment for more security  */
// $ip_address = array('127.0.0.1', 'x.x.x.x'); // EDIT THIS LINE!!
// if(in_array($_SERVER['REMOTE_ADDR'], $ip_address) !== TRUE) {
	// \IPS\Output::i()->json(array('status' => 'FAILD', 'msg' => 'BAD_IP_ADDR'));
// }

/* -~-~-~-~-~-~ Stop Editing -~-~-~-~-~-~ */

if( !\IPS\Request::i()->do || !\IPS\Request::i()->id || !\IPS\Request::i()->key || !\IPS\Login::compareHashes( \IPS\Request::i()->key, md5($key . \IPS\Request::i()->id))) {
	\IPS\Output::i()->json(array('status' => 'FAILD', 'msg' => 'BAD_KEY'));
}

$member = \IPS\Member::load( \IPS\Request::i()->id, $login_type );

if( !$member->member_id ) {
	\IPS\Output::i()->json(array('status' => 'FAILD', 'msg' => 'ACCOUNT_NOT_FOUND'));
}

switch(\IPS\Request::i()->do) {
	case 'get_salt':
		\IPS\Output::i()->json(array('status' => 'SUCCESS', 'pass_salt' => $member->members_pass_salt));
	break;
	case 'login':
		if( \IPS\Login::compareHashes($member->members_pass_hash, \IPS\Request::i()->password) === TRUE ) {
			\IPS\Output::i()->json(
					array(
						'status' => 'SUCCESS',
						'connect_status'			=> ( $member->members_bitoptions['validating'] ) ? 'VALIDATING' : 'SUCCESS',
						'email'						=> $member->email,
						'name'						=> $member->name,
						'connect_id'				=> $member->member_id,
					)
				);
		}
	break;
}