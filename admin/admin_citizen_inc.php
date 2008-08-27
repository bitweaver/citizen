<?php

// $Header: /cvsroot/bitweaver/_bit_citizen/admin/admin_citizen_inc.php,v 1.1 2008/08/27 16:20:01 lsces Exp $

// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

include_once( CITIZEN_PKG_PATH.'Citizen.php' );

$formCitizenListFeatures = array(
	"citizen_list_id" => array(
		'label' => 'Citizen Number',
	),
	"citizen_list_forename" => array(
		'label' => 'Forname',
	),
	"citizen_list_surname" => array(
		'label' => 'Surname',
	),
	"citizen_list_home_phone" => array(
		'label' => 'Home Phone',
	),
	"citizen_list_mobile_phone" => array(
		'label' => 'Mobile Phone',
	),
	"citizen_list_email" => array(
		'label' => 'eMail Address',
		'help' => 'Primary citizen email address - additional citizen details can be found in the full record',
	),
	"citizen_list_edit_details" => array(
		'label' => 'Creation and editing details',
		'help' => 'Enable the record modification data in the citizen list. Useful to allow checking when deatils were last changed.',
	),
	"citizen_list_last_modified" => array(
		'label' => 'Last Modified',
		'help' => 'Can be selected to enable filter button, without enabling the details section to allow fast checking of the last citizen records that have been modified.',
	),
);
$gBitSmarty->assign( 'formCitizenListFeatures',$formCitizenListFeatures );

if (isset($_REQUEST["citizenlistfeatures"])) {
	
	foreach( $formCitizenListFeatures as $item => $data ) {
		simple_set_toggle( $item, CITIZEN_PKG_NAME );
	}
}

?>
