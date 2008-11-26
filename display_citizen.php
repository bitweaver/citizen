<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_citizen/display_citizen.php,v 1.3 2008/11/26 12:07:25 lsces Exp $
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
 *
 * @package nlpg
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

include_once( CITIZEN_PKG_PATH.'Citizen.php' );

$gBitSystem->verifyPackage( 'citizen' );

$gBitSystem->verifyPermission( 'p_citizen_view' );

if( !empty( $_REQUEST['content_id'] ) ) {
	$gCitizen = new Citizen($_REQUEST['content_id'],$_REQUEST['content_id']);
	$gCitizen->loadCitizen();
} else {
	$gCitizen = new Citizen();
}

$gBitSmarty->assign_by_ref( 'citizenInfo', $gCitizen->mInfo );
if ( $gCitizen->isValid() ) {
	$gBitSystem->setBrowserTitle("Client Activity Item");
	$gBitSystem->display( 'bitpackage:citizen/show_citizen.tpl');
} else {
//	header ("location: ".CITIZEN_PKG_URL."index.php");
//	die;
}
?>
