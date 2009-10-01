<?php
// $Header: /cvsroot/bitweaver/_bit_citizen/list_citizens.php,v 1.2 2009/10/01 13:45:33 wjames5 Exp $
// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
// Initialization
require_once( '../bit_setup_inc.php' );
require_once( CITIZEN_PKG_PATH.'Citizen.php' );

$gBitSystem->isPackageActive('citizen', TRUE);

// Now check permissions to access this page
$gBitSystem->verifyPermission('p_read_citizen');

$citizens = new Citizens( 0 );

if ( empty( $_REQUEST["sort_mode"] ) ) {
	$sort_mode = 'title_asc';
}

// Get a list of Citizens 
$citizens->getList( $_REQUEST );

$smarty->assign_by_ref('listInfo', $_REQUEST['listInfo']);
$smarty->assign_by_ref('list', $citizens);


// Display the template
$gBitSystem->display( 'bitpackage:citizen/list_citizens.tpl', NULL, array( 'display_mode' => 'list' ));
?>
