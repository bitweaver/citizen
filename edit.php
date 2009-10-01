<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_citizen/edit.php,v 1.4 2009/10/01 13:45:33 wjames5 Exp $
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

$gBitSystem->verifyPackage( 'citizen' );

$gBitSystem->verifyPermission( 'p_citizen_update' );

include_once( CITIZEN_PKG_PATH.'Citizen.php' );

$gContent = new Citizen();

if( !empty( $_REQUEST['content_id'] ) ) {
	$gContent->load($_REQUEST['content_id']);
}

// Get plugins with descriptions
global $gLibertySystem;

if( !empty( $gContent->mInfo ) ) {
	$formInfo = $gContent->mInfo;
	$formInfo['edit'] = !empty( $gContent->mInfo['data'] ) ? $gContent->mInfo['data'] : '';
}

$cat_type = BITPAGE_CONTENT_TYPE_GUID;
if(isset($_REQUEST["preview"])) {

	// get files from all packages that process this data further
	foreach( $gBitSystem->getPackageIntegrationFiles( 'form_processor_inc.php', TRUE ) as $package => $file ) {
		if( $gBitSystem->isPackageActive( $package ) ) {
			include_once( $file );
		}
	}

	$gBitSmarty->assign('preview',1);
	$gBitSmarty->assign('title',$_REQUEST["title"]);

	$parsed = $gContent->parseData($formInfo['edit'], (!empty( $_REQUEST['format_guid'] ) ? $_REQUEST['format_guid'] :
		( isset($gContent->mInfo['format_guid']) ? $gContent->mInfo['format_guid'] : 'tikiwiki' ) ) );
	$gBitSmarty->assign_by_ref('parsed', $parsed);
	$gContent->invokeServices( 'content_preview_function' );
} else {
	$gContent->invokeServices( 'content_edit_function' );
}

// Pro
if (isset($_REQUEST["fCancel"])) {
	if( !empty( $gContent->mContentId ) ) {
		header("Location: ".$gContent->getDisplayUrl() );
	} else {
		header("Location: ".CITIZEN_PKG_URL );
	}
	die;
} elseif (isset($_REQUEST["fSaveCitizen"])) {
	if( $gContent->store( $_REQUEST ) ) {
		header("Location: ".$gContent->getDisplayUrl() );
	} else {
		$formInfo = $_REQUEST;
		$formInfo['data'] = &$_REQUEST['edit'];
	}
} 
// Configure quicktags list
if ($gBitSystem->isPackageActive( 'quicktags' ) ) {
	include_once( QUICKTAGS_PKG_PATH.'quicktags_inc.php' );
}

// WYSIWYG and Quicktag variable
$gBitSmarty->assign( 'textarea_id', 'editwiki' );

// formInfo might be set due to a error on submit
if( empty( $formInfo ) ) {
	$formInfo = &$gContent->mInfo;
}
// $formInfo['citizen_type_list'] = $gContent->getCitizensTypeList();
$gBitSmarty->assign_by_ref( 'citizenInfo', $formInfo );

$gBitSmarty->assign_by_ref( 'errors', $gContent->mErrors );
$gBitSmarty->assign( (!empty( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'body').'TabSelect', 'tdefault' );
$gBitSmarty->assign('show_page_bar', 'y');

$gBitSystem->display( 'bitpackage:citizen/edit.tpl', 'Edit: ' , array( 'display_mode' => 'edit' ));
?>
