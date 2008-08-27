<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_citizen/index.php,v 1.3 2008/08/27 16:20:01 lsces Exp $
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
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

$gContent = new Citizen();

if( !empty( $_REQUEST['content_id'] ) ) {
	$gContent->load($_REQUEST['content_id']);
}

// Comments engine!
if( $gBitSystem->isFeatureActive( 'feature_citizen_comments' ) ) {
	$comments_vars = Array('page');
	$comments_prefix_var='citizen note:';
	$comments_object_var='page';
	$commentsParentId = $gContent->mContentId;
	$comments_return_url = CITIZEN_PKG_URL.'index.php?content_id='.$gContent->mContentId;
	include_once( LIBERTY_PKG_PATH.'comments_inc.php' );
}

$gBitSmarty->assign_by_ref( 'citizenInfo', $gContent->mInfo );
if ( $gContent->isValid() ) {
	$gBitSystem->setBrowserTitle("Citizen List Item");
	$gBitSystem->display( 'bitpackage:citizen/show_citizen.tpl', NULL, array( 'display_mode' => 'display' ));
} else {
	header ("location: ".CITIZEN_PKG_URL."list.php");
	die;
}
?>