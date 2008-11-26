<?php
global $gBitSystem, $gBitSmarty;
$registerHash = array(
	'package_name' => 'citizen',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'citizen' ) ) {
	$menuHash = array(
		'package_name'  => CITIZEN_PKG_NAME,
		'index_url'     => CITIZEN_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:citizen/menu_citizen.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );
}

?>
