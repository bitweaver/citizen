<?php
$tables = array(

'citizen' => "
  content_id I8 PRIMARY,
  usn I8 NOTNULL,
  parent_id I8,
  uprn I8,
  nlpg I8,
  ctax I8,
  opfl I8,
  cltype I4,
  prefix C(35),
  forename C(35),
  surname C(35),
  suffix C(35),
  organisation C(100),
  dob D,
  eighteenth D,
  dod D,
  nino C(9),
  specialneeds C(4),
  last_update_date T  DEFAULT CURRENT_TIMESTAMP,
  note C(40),
  memo X
",

'citizen_xref' => "
  content_id I8 NOTNULL,
  xref_key C(14),
  start_date T,
  last_update_date T,
  entry_date T,
  end_date T,
  source C(20) PRIMARY,
  cross_reference C(22) PRIMARY,
  data X
  ",

'citizen_type' => "
  citizen_type_id I4 PRIMARY,
  type_name	C(64)
",

'citizen_xref_source' => "
  source C(6) PRIMARY,
  cross_ref_title C(64),
  cross_ref_href C(256),
  data X
  ",

'citizen_type_map' => "
  content_id I4 PRIMARY,
  citizen_type_id I4 PRIMARY,
  type_value	I4
",

'task_ticket' => "
  ticket_id I8 PRIMARY,
  ticket_ref T NOT NULL,
  office I4 NOTNULL,
  ticket_no I4 NOT NULL,
  tags I4 DEFAULT 0,
  clearance I2 DEFAULT 0,
  room I2 DEFAULT 0,
  last T,
  staff_id I4 NOTNULL,
  init_id I4 NOTNULL,
  caller_id I8,
  appoint_id I8 DEFAULT 0,
  applet V(1) DEFAULT '' NOTNULL,
  note C(40),
  memo X
",

'task_transaction' => "
  ticket_id I8 PRIMARY,
  transact_no I2 PRIMARY,
  transact T NOTNULL,
  ticket_ref T NOTNULL,
  office I4 NOTNULL,
  ticket_no I4 NOTNULL,
  staff_id I4 NOTNULL,
  previous I4 NOTNULL,
  room I2,
  proom I2,
  applet V(1) DEFAULT '' NOTNULL
",

'task_reason' => "
  reason I2 PRIMARY,
  title C(20),
  reason_type I2,
  reason_source I2,
  tag V(3),
  qty I4
",

'task_appointment' => "
  appoint_id I2 PRIMARY,
  appoint_date T NOTNULL,
  appoint_time T NOTNULL,
  staff_id I4 NOTNULL,
  citizen_id I4 NOTNULL,
  reason I2,
  office I4,
  room I2,
  note C(40),
  letter C(40),
  ticket_id I4
",

'task_booking_plan' => "
  office I4 PRIMARY,
  row I2 PRIMARY,
  title C(5),
  atime T,
  rooms I2
",

'task_stats' => "
  office I4 PRIMARY,
  time_st T PRIMARY,
  queue I2 PRIMARY,
  line1 I2,
  line2 I2,
  line3 I2
",

'task_stats_print' => "
  office I4 PRIMARY,
  col I2 PRIMARY,
  title C(5),
  line1 I2,
  line2 I2,
  line3 I2,
  coltime T
",

'task_roomstat' => "
  office I4 PRIMARY,
  terminal I2 PRIMARY,
  title C(40),
  head C(8),
  announce C(32),
  ter_type I2,
  led I2,
  ledhead C(4),
  beacon I2,
  camera I2,
  serving I2,
  act1 I2,
  fro_ I2,
  alarm I2,
  curmode I2,
  x1 I2,
  x2 I2,
  x3 I2,
  x4 I2,
  x5 I2,
  x6 I2,
  x7 I2,
  x8 I2,
  x9 I2,
  x10 I2,
  status I2,
  logon I4,
  ter_location C(32),
  ticketprint C(32),
  reportprint C(32),
  booking I4,
  book I4
",

'address_book' => "
  content_id I8 PRIMARY,
  usn I8,
  uprn I8,
  postcode C(10),
  organisation C(100),
  sao C(80),
  pao C(80),
  number C(80),
  street C(250),
  locality C(250),
  town C(80),
  county C(80),
  zone_id I4,
  country_id I4,
  last_update_date T DEFAULT CURRENT_TIMESTAMP
",

'postcode' => "
  postcode C(10),
  add1 C(32),
  add2 C(32),
  add3 C(32),
  add4 C(32),
  town C(20),
  county C(20),
  grideast I4,
  gridnorth I4,
  w_id C(6),
  p_id C(7),
  NHS C(3),
  PCG C(5)
",

);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( CITIZEN_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( CITIZEN_PKG_NAME, array(
	'description' => "Base Citizen management package with citizen xref and address books",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Indexes
$indices = array (
	'citizen_citizen_id_idx' => array( 'table' => 'citizen', 'cols' => 'usn', 'opts' => NULL ),
);
$gBitInstaller->registerSchemaIndexes( CITIZEN_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'citizen_id_seq' => array( 'start' => 1 ),
);
$gBitInstaller->registerSchemaSequences( CITIZEN_PKG_NAME, $sequences );

// ### Defaults

// ### Default User Permissions
$gBitInstaller->registerUserPermissions( CITIZEN_PKG_NAME, array(
	array('p_citizen_view', 'Can browse the Citizen List', 'basic', CITIZEN_PKG_NAME),
	array('p_citizen_update', 'Can update the Citizen List content', 'registered', CITIZEN_PKG_NAME),
	array('p_citizen_create', 'Can create a new Citizen List entry', 'registered', CITIZEN_PKG_NAME),
	array('p_citizen_admin', 'Can admin Citizen List', 'admin', CITIZEN_PKG_NAME),
	array('p_citizen_expunge', 'Can remove a Citizen entry', 'editors', CITIZEN_PKG_NAME)
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( CITIZEN_PKG_NAME, array(
	array( CITIZEN_PKG_NAME, 'citizen_default_ordering','title_desc'),
	array( CITIZEN_PKG_NAME, 'citizen_list_created','y'),
	array( CITIZEN_PKG_NAME, 'citizen_list_lastmodif','y'),
	array( CITIZEN_PKG_NAME, 'citizen_list_notes','y'),
	array( CITIZEN_PKG_NAME, 'citizen_list_title','y'),
	array( CITIZEN_PKG_NAME, 'citizen_list_user','y'),
) );

$gBitInstaller->registerSchemaDefault( CITIZEN_PKG_NAME, array(
"INSERT INTO `".BIT_DB_PREFIX."citizen_type` VALUES (0, 'Personal')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_type` VALUES (1, 'Business')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_type` VALUES (2, 'Manufacturer')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_type` VALUES (3, 'Distributor')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_type` VALUES (4, 'Supplier')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_type` VALUES (5, 'Record Company')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_type` VALUES (6, 'Record Artist')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_type` VALUES (7, 'Cartographer')",

"INSERT INTO `".BIT_DB_PREFIX."citizen_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('0' , 'Free format information', '../citizen/?xref=')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#R', 'Residential Address', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#T', 'Tenant Address', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#C', 'Correspondence Address', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#O', 'Owner Address', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#K', 'Keyholder', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('HBEN', 'Housing Benefit', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('CTAX', 'Council Tax', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('NNDR', 'National Non-domestic Rates', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."citizen_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('ER', 'Electoral Roll', '../nlpg/?uprn=')",
) );


?>
