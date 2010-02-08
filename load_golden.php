<?php
/*
 * Created on 5 Jan 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

// Initialization
require_once( '../kernel/setup_inc.php' );
require_once( CITIZEN_PKG_PATH.'Citizen.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'citizen' );

// Now check permissions to access this page
$gBitSystem->verifyPermission('p_citizen_admin' );

$citizens = new Citizen();
set_time_limit(0);

if( empty( $_REQUEST["update"] ) ) {
	$citizens->GoldenExpunge();
	$update = 0;
} else {
	$update = $_REQUEST["update"];
}

$row = 0;

$handle = fopen("data/golden.csv", "r");
if ( $handle == FALSE) {
	$row = -999;
} else {
	while (($data = fgetcsv($handle, 800, ",")) !== FALSE) {
    	if ( $row ) {
    		$citizens->GoldenRecordLoad( $data );
    	}
    	$row++;
	}
	fclose($handle);
}

$gBitSmarty->assign( 'golden', $row );

$row = 0;
$handle = fopen("data/xref.csv", "r");
if ( $handle == FALSE) {
	$row = -999;
} else {
	while (($data = fgetcsv($handle, 4000, ",")) !== FALSE) {
		if ( $row ) {
			$citizens->GoldenXrefRecordLoad( $data );
		}
    	$row++;
	} 
	fclose($handle);
}
$gBitSmarty->assign( 'xref', $row );

$gBitSystem->display( 'bitpackage:citizen/load_golden.tpl', tra( 'Load results: ' ) );
?>
