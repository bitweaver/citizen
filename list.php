<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_citizen/list.php,v 1.3 2009/10/01 13:45:33 wjames5 Exp $
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package citizen
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

include_once( CITIZEN_PKG_PATH.'Citizen.php' );

$gBitSystem->verifyPackage( 'citizen' );

$gBitSystem->verifyPermission( 'p_citizen_view' );

$gContent = new Citizen( );

if( !empty( $_REQUEST["find_org"] ) ) {
	$_REQUEST["find_name"] = '';
	$_REQUEST["sort_mode"] = 'organisation_asc';
} else if( empty( $_REQUEST["sort_mode"] ) ) {
	$_REQUEST["sort_mode"] = 'surname_asc';
	$_REQUEST["find_name"] = 'a,a';
}

//$citizen_type = $gContent->getCitizensTypeList();
//$gBitSmarty->assign_by_ref('citizen_type', $citizen_type);
$listHash = $_REQUEST;
// Get a list of matching citizen entries
$listcitizens = $gContent->getCitizenList( $listHash );

$gBitSmarty->assign_by_ref( 'listcitizens', $listcitizens );
$gBitSmarty->assign_by_ref( 'listInfo', $listHash['listInfo'] );

$gBitSystem->setBrowserTitle("View Citizens List");
// Display the template
$gBitSystem->display( 'bitpackage:citizen/list.tpl', NULL, array( 'display_mode' => 'list' ));

?>
