<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_citizen/index.php,v 1.5 2009/10/01 13:45:33 wjames5 Exp $
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
 *
 * @package citizen
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

include_once( CITIZEN_PKG_PATH.'Citizen.php' );

$gBitSystem->isPackageActive('citizen', TRUE);

if( !empty( $_REQUEST['content_id'] ) ) {
	$gCitizen = new Citizen( null, $_REQUEST['content_id'] );
	$gCitizen->load();
	$gCitizen->loadXrefList();
} else {
	$gCitizen = new Citizen();
}

// Comments engine!
if( $gBitSystem->isFeatureActive( 'feature_citizen_comments' ) ) {
	$comments_vars = Array('page');
	$comments_prefix_var='citizen note:';
	$comments_object_var='page';
	$commentsParentId = $gContent->mContentId;
	$comments_return_url = CITIZEN_PKG_URL.'index.php?content_id='.$gCitizen->mContentId;
	include_once( LIBERTY_PKG_PATH.'comments_inc.php' );
}

$gBitSmarty->assign_by_ref( 'citizenInfo', $gCitizen->mInfo );
if ( $gCitizen->isValid() ) {
	$gBitSystem->setBrowserTitle("Citizen List Item");
	$gBitSystem->display( 'bitpackage:citizen/show_citizen.tpl', NULL, array( 'display_mode' => 'display' ));
} else {
	header ("location: ".CITIZEN_PKG_URL."list.php");
	die;
}
?>