<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_citizen/Citizen.php,v 1.12 2009/10/01 14:16:59 wjames5 Exp $
 *
 * Copyright ( c ) 2006 bitweaver.org
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package citizen
 */

/**
 * required setup
 */
require_once( LIBERTY_PKG_PATH.'LibertyContent.php' );		// Citizen base class
require_once(NLPG_PKG_PATH.'lib/phpcoord-2.3.php' );

define( 'CITIZEN_CONTENT_TYPE_GUID', 'citizen' );

/**
 * @package citizen
 */
class Citizen extends LibertyContent {
	var $mCitizenId;
	var $mParentId;

	/**
	 * Constructor 
	 * 
	 * Build a Citizen object based on LibertyContent
	 * @param integer Citizen Id identifer
	 * @param integer Base content_id identifier 
	 */
	function Citizen( $pCitizenId = NULL, $pContentId = NULL ) {
		LibertyContent::LibertyContent();
		$this->registerContentType( CITIZEN_CONTENT_TYPE_GUID, array(
				'content_type_guid' => CITIZEN_CONTENT_TYPE_GUID,
				'content_description' => 'Citizen Entry',
				'handler_class' => 'Citizen',
				'handler_package' => 'citizen',
				'handler_file' => 'Citizen.php',
				'maintainer_url' => 'http://lsces.co.uk'
			) );
		$this->mCitizenId = (int)$pCitizenId;
		if ( $pContentId > 9000000000 ) $pContentId = $pContentId - 9000000000;
		$this->mContentId = (int)$pContentId;
		$this->mContentTypeGuid = CITIZEN_CONTENT_TYPE_GUID;
				// Permission setup
		$this->mViewContentPerm  = 'p_citizen_view';
		$this->mCreateContentPerm  = 'p_citizen_create';
		$this->mUpdateContentPerm  = 'p_citizen_update';
		$this->mAdminContentPerm = 'p_citizen_admin';
		
	}

	/**
	 * Load a Citizen content Item
	 *
	 * (Describe Citizen object here )
	 */
	function load($pContentId = NULL) {
		if ( $pContentId ) $this->mContentId = (int)$pContentId;
		if( $this->verifyId( $this->mContentId ) ) {
 			$query = "select ci.*, a.*, n.*, p.*, lc.*,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name
				FROM `".BIT_DB_PREFIX."citizen` ci
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON ( lc.`content_id` = ci.`content_id` )
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = lc.`modifier_user_id`)
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = lc.`user_id`)
				LEFT JOIN `".BIT_DB_PREFIX."citizen_address` a ON a.usn = ci.usn
				LEFT JOIN `".BIT_DB_PREFIX."nlpg_blpu` n ON n.`uprn` = ci.`nlpg`
				LEFT JOIN `".BIT_DB_PREFIX."nlpg_lpi` p ON p.`uprn` = ci.`nlpg` AND p.`language` = 'ENG' AND p.`logical_status` = 1
				WHERE ci.`content_id`=?";
			$result = $this->mDb->query( $query, array( $this->mContentId ) );

			if ( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = (int)$result->fields['content_id'];
				$this->mCitizenId = (int)$result->fields['usn'];
				$this->mParentId = (int)$result->fields['usn'];
				$this->mCitizenName = $result->fields['title'];
				$this->mInfo['creator'] = (isset( $result->fields['creator_real_name'] ) ? $result->fields['creator_real_name'] : $result->fields['creator_user'] );
				$this->mInfo['editor'] = (isset( $result->fields['modifier_real_name'] ) ? $result->fields['modifier_real_name'] : $result->fields['modifier_user'] );
				$this->mInfo['display_url'] = $this->getDisplayUrl();
				$os1 = new OSRef($this->mInfo['x_coordinate'], $this->mInfo['y_coordinate']);
				$ll1 = $os1->toLatLng();
				$this->mInfo['prop_lat'] = $ll1->lat;
				$this->mInfo['prop_lng'] = $ll1->lng;
			}
		}
		LibertyContent::load();
		return;
	}

	/**
	* verify, clean up and prepare data to be stored
	* @param $pParamHash all information that is being stored. will update $pParamHash by reference with fixed array of itmes
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	* @access private
	**/
	function verify( &$pParamHash ) {
		// make sure we're all loaded up if everything is valid
		if( $this->isValid() && empty( $this->mInfo ) ) {
			$this->load( TRUE );
		}

		// It is possible a derived class set this to something different
		if( empty( $pParamHash['content_type_guid'] ) ) {
			$pParamHash['content_type_guid'] = $this->mContentTypeGuid;
		}

		if( !empty( $this->mContentId ) ) {
			$pParamHash['content_id'] = $this->mContentId;
		} else {
			unset( $pParamHash['content_id'] );
		}

		if ( empty( $pParamHash['parent_id'] ) )
			$pParamHash['parent_id'] = $this->mContentId;
			
		// content store
		// check for name issues, first truncate length if too long
		if( empty( $pParamHash['surname'] ) || empty( $pParamHash['forename'] ) )  {
			$this->mErrors['names'] = 'You must enter a forename and surname for this citizen.';
		} else {
			$pParamHash['title'] = substr( $pParamHash['prefix'].' '.$pParamHash['forename'].' '.$pParamHash['surname'].' '.$pParamHash['suffix'], 0, 160 );
			$pParamHash['content_store']['title'] = $pParamHash['title'];
		}	

		// Secondary store entries
		$pParamHash['citizen_store']['prefix'] = $pParamHash['prefix'];
		$pParamHash['citizen_store']['forename'] = $pParamHash['forename'];
		$pParamHash['citizen_store']['surname'] = $pParamHash['surname'];
		$pParamHash['citizen_store']['suffix'] = $pParamHash['suffix'];
		$pParamHash['citizen_store']['organisation'] = $pParamHash['organisation'];

		if ( !empty( $pParamHash['nino'] ) ) $pParamHash['citizen_store']['nino'] = $pParamHash['nino'];
		if ( !empty( $pParamHash['dob'] ) ) $pParamHash['citizen_store']['dob'] = $pParamHash['dob'];
		if ( !empty( $pParamHash['eighteenth'] ) ) $pParamHash['citizen_store']['eighteenth'] = $pParamHash['eighteenth'];
		if ( !empty( $pParamHash['dod'] ) ) $pParamHash['citizen_store']['dod'] = $pParamHash['dod'];

		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Store citizen data
	* @param $pParamHash contains all data to store the citizen
	* @param $pParamHash[title] title of the new citizen
	* @param $pParamHash[edit] description of the citizen
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	**/
	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash ) ) {
			// Start a transaction wrapping the whole insert into liberty 

			$this->mDb->StartTrans();
			if ( LibertyContent::store( $pParamHash ) ) {
				$table = BIT_DB_PREFIX."citizen";

				// mContentId will not be set until the secondary data has commited 
				if( $this->verifyId( $this->mCitizenId ) ) {
					if( !empty( $pParamHash['citizen_store'] ) ) {
						$result = $this->mDb->associateUpdate( $table, $pParamHash['citizen_store'], array( "content_id" => $this->mContentId ) );
					}
				} else {
					$pParamHash['citizen_store']['content_id'] = $pParamHash['content_id'];
					$pParamHash['citizen_store']['usn'] = $pParamHash['content_id'];
					if( isset( $pParamHash['citizen_id'] ) && is_numeric( $pParamHash['citizen_id'] ) ) {
						$pParamHash['citizen_store']['usn'] = $pParamHash['citizen_id'];
					} else {
						$pParamHash['citizen_store']['usn'] = $this->mDb->GenID( 'citizen_id_seq');
					}	

					$pParamHash['citizen_store']['parent_id'] = $pParamHash['citizen_store']['content_id'];
					$this->mCitizenId = $pParamHash['citizen_store']['content_id'];
					$this->mParentId = $pParamHash['citizen_store']['parent_id'];
					$this->mContentId = $pParamHash['content_id'];
					$result = $this->mDb->associateInsert( $table, $pParamHash['citizen_store'] );
				}
				// load before completing transaction as firebird isolates results
				$this->load();
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
				$this->mErrors['store'] = 'Failed to store this citizen.';
			}
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * Delete content object and all related records
	 */
	function expunge()
	{
		$ret = FALSE;
		if ($this->isValid() ) {
			$this->mDb->StartTrans();
			$query = "DELETE FROM `".BIT_DB_PREFIX."citizen` WHERE `content_id` = ?";
			$result = $this->mDb->query($query, array($this->mContentId ) );
			$query = "DELETE FROM `".BIT_DB_PREFIX."citizen_type_map` WHERE `content_id` = ?";
			$result = $this->mDb->query($query, array($this->mContentId ) );
			if (LibertyContent::expunge() ) {
			$ret = TRUE;
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
			}
		}
		return $ret;
	}
    
	/**
	 * Returns Request_URI to a Citizen content object
	 *
	 * @param string name of
	 * @param array different possibilities depending on derived class
	 * @return string the link to display the page.
	 */
	function getDisplayUrl( $pContentId=NULL ) {
		global $gBitSystem;
		if( empty( $pContentId ) ) {
			$pContentId = $this->mContentId;
		}

		return CITIZEN_PKG_URL.'index.php?content_id='.$pContentId;
	}

	/**
	 * Returns HTML link to display a Citizen object
	 * 
	 * @param string Not used ( generated locally )
	 * @param array mInfo style array of content information
	 * @return the link to display the page.
	 */
	function getDisplayLink( $pText, $aux ) {
		if ( $this->mContentId != $aux['content_id'] ) $this->load($aux['content_id']);

		if (empty($this->mInfo['content_id']) ) {
			$ret = '<a href="'.$this->getDisplayUrl($aux['content_id']).'">'.$aux['title'].'</a>';
		} else {
			$ret = '<a href="'.$this->getDisplayUrl($aux['content_id']).'">'."Citizen - ".$this->mInfo['title'].'</a>';
		}
		return $ret;
	}

	/**
	 * Returns title of an Citizen object
	 *
	 * @param array mInfo style array of content information
	 * @return string Text for the title description
	 */
	function getTitle( $pHash = NULL ) {
		$ret = NULL;
		if( empty( $pHash ) ) {
			$pHash = &$this->mInfo;
		} else {
			if ( $this->mContentId != $pHash['content_id'] ) {
				$this->load($pHash['content_id']);
				$pHash = &$this->mInfo;
			}
		}

		if( !empty( $pHash['title'] ) ) {
			$ret = "Citizen - ".$this->mInfo['title'];
		} elseif( !empty( $pHash['content_description'] ) ) {
			$ret = $pHash['content_description'];
		}
		return $ret;
	}

	/**
	 * Returns list of contract entries
	 *
	 * @param integer 
	 * @param integer 
	 * @param integer 
	 * @return string Text for the title description
	 */
	function getList( &$pListHash ) {
		LibertyContent::prepGetList( $pListHash );
		
		$whereSql = $joinSql = $selectSql = '';
		$bindVars = array();
		array_push( $bindVars, $this->mContentTypeGuid );
		$this->getServicesSql( 'content_list_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

		if ( isset($pListHash['find']) ) {
			$findesc = '%' . strtoupper( $pListHash['find'] ) . '%';
			$whereSql .= " AND (UPPER(con.`SURNAME`) like ? or UPPER(con.`FORENAME`) like ?) ";
			array_push( $bindVars, $findesc );
		}

		if ( isset($pListHash['add_sql']) ) {
			$whereSql .= " AND $add_sql ";
		}

		$query = "SELECT con.*, lc.*, 
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name $selectSql
				FROM `".BIT_DB_PREFIX."citizen` ci 
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON ( lc.`content_id` = ci.`content_id` )
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = lc.`modifier_user_id`)
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = lc.`user_id`)
				$joinSql
				WHERE lc.`content_type_guid`=? $whereSql  
				order by ".$this->mDb->convertSortmode( $pListHash['sort_mode'] );
		$query_cant = "SELECT COUNT(lc.`content_id`) FROM `".BIT_DB_PREFIX."citizen` ci
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON ( lc.`content_id` = ci.`content_id` )
				$joinSql
				WHERE lc.`content_type_guid`=? $whereSql";

		$ret = array();
		$this->mDb->StartTrans();
		$result = $this->mDb->query( $query, $bindVars, $pListHash['max_records'], $pListHash['offset'] );
		$cant = $this->mDb->getOne( $query_cant, $bindVars );
		$this->mDb->CompleteTrans();

		while ($res = $result->fetchRow()) {
			$res['citizen_url'] = $this->getDisplayUrl( $res['content_id'] );
			$ret[] = $res;
		}

		$pListHash['cant'] = $cant;
		LibertyContent::postGetList( $pListHash );
		return $ret;
	}

	/**
	* Returns titles of the citizen type table
	*
	* @return array List of citizen type names from the citizen mamanger in alphabetical order
	*/
	function getCitizenTypeList() {
		$query = "SELECT `type_name` FROM `citizen_type`
				  ORDER BY `type_name`";
		$result = $this->mDb->query($query);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = trim($res["type_name"]);
		}
		return $ret;
	}

	/**
	 * GoldenRecordLoad( $data ); 
	 * Rother golden file import 
	 */
	function GoldenRecordLoad( &$data ) {
		$table = BIT_DB_PREFIX."citizen";
		$atable = BIT_DB_PREFIX."citizen_address";

		$usn = 9000000000 + $data[0];
		$pDataHash['citizen_store']['content_id'] = $data[0];
		$pDataHash['citizen_store']['usn'] = $usn;
		$pDataHash['address_store']['content_id'] = $data[0];
		$pDataHash['address_store']['usn'] = $usn;
		$pDataHash['citizen_store']['uprn'] = $data[1];
		$pDataHash['address_store']['uprn'] = $data[1];
		$pDataHash['citizen_store']['prefix'] = $data[2];
		$pDataHash['citizen_store']['forename'] = $data[3];
		$pDataHash['citizen_store']['surname'] = $data[4];
		$pDataHash['citizen_store']['organisation'] = $data[5];
		$pDataHash['citizen_store']['opfl'] = $data[6];
		$pDataHash['citizen_store']['nino'] = $data[7];
		if ( $data[8] <> '' ) $date = substr($data[8], 6, 4 ).'-'.substr($data[8], 3, 2 ).'-'.substr($data[8], 0, 2 ); 
		else $date = NULL;
		$pDataHash['citizen_store']['eighteenth'] = $date;
		if ( $data[9] <> '' ) $date = substr($data[9], 6, 4 ).'-'.substr($data[9], 3, 2 ).'-'.substr($data[9], 0, 2 );
		else $date = NULL;
		$pDataHash['citizen_store']['dob'] = $date;
		$pDataHash['citizen_store']['ctax'] = $data[10];
		$pDataHash['address_store']['organisation'] = $data[11];
		$pDataHash['address_store']['sao'] = $data[12];
		$pDataHash['address_store']['pao'] = $data[13];
		$pDataHash['address_store']['number'] = $data[14];
		$pDataHash['address_store']['street'] = $data[15];
		$pDataHash['address_store']['locality'] = $data[16];
		$pDataHash['address_store']['town'] = $data[17];
		$pDataHash['address_store']['county'] = $data[18];
		$pDataHash['address_store']['postcode'] = substr($data[19], 0, 4).' '.substr($data[19], 4, 3);
		$pDataHash['citizen_store']['nlpg'] = $data[20];
		if ( $data[21] <> '' ) $date = substr($data[21], 6, 4 ).'-'.substr($data[21], 3, 2 ).'-'.substr($data[21], 0, 2 );
		else $date[21] = null;
		$pDataHash['citizen_store']['last_update_date'] = $date;

		$query_cant = "SELECT COUNT(ci.`content_id`) FROM `".BIT_DB_PREFIX."citizen` ci
				WHERE ci.`usn`=?";
		$cant = $this->mDb->getOne( $query_cant, Array( 9000000000 + $data[0] ) );
		
		if ( $cant > 0 ) {
		  $query_cant = "SELECT * FROM `".BIT_DB_PREFIX."citizen` ci
				WHERE con.`usn`=?";
		  $current = $this->mDb->query( $query_cant, Array( 9000000000 + $data[0] ) );
		  $cfields = $current->fetchRow();
		  $save = false;
		  if ( $pDataHash['citizen_store']['uprn'] <> $cfields['uprn'] ) $save = true;
		  if ( $pDataHash['citizen_store']['nlpg'] <> $cfields['nlpg'] ) $save = true;
		  if ( $pDataHash['citizen_store']['prefix'] <> $cfields['prefix'] ) $save = true;
		  if ( $pDataHash['citizen_store']['forename'] <> $cfields['forename'] ) $save = true;
		  if ( $pDataHash['citizen_store']['surname'] <> $cfields['surname'] ) $save = true;
		  if ( $pDataHash['citizen_store']['organisation'] <> $cfields['organisation'] ) $save = true;
		  if ( $pDataHash['citizen_store']['opfl'] <> $cfields['opfl'] ) $save = true;
		  if ( $pDataHash['citizen_store']['nino'] <> $cfields['nino'] ) $save = true;
		  if ( $pDataHash['citizen_store']['ctax'] <> $cfields['ctax'] ) $save = true;
		  if ( $pDataHash['citizen_store']['dob'] && $pDataHash['citizen_store']['dob'] <> $cfields['dob'] ) 
		  { $save = true;
		  }
		  if ( $pDataHash['citizen_store']['eighteenth'] && $pDataHash['citizen_store']['eighteenth'] <> $cfields['eighteenth'] ) $save = true;
		  if ( $pDataHash['citizen_store']['last_update_date'] && $pDataHash['citizen_store']['last_update_date'] <> $cfields['last_update_date'] ) $save = true;
		  if ( $save ) {
//		  	$pDataHash['citizen_store']['last_update_date'] = $this->mDb->now();
			$result = $this->mDb->associateUpdate( $table, $pDataHash['citizen_store'], array( "usn" => $usn ) );
		  }
		  $save = false;
		  if ( $pDataHash['address_store']['uprn'] <> $cfields['uprn'] ) $save = true;
		  if ( $pDataHash['address_store']['organisation'] <> $cfields['organisation'] ) $save = true;
		  if ( $pDataHash['address_store']['sao'] <> $cfields['sao'] ) $save = true;
		  if ( $pDataHash['address_store']['pao'] <> $cfields['pao'] ) $save = true;
		  if ( $pDataHash['address_store']['number'] <> $cfields['number'] ) $save = true;
		  if ( $pDataHash['address_store']['street'] <> $cfields['street'] ) $save = true;
		  if ( $pDataHash['address_store']['locality'] <> $cfields['locality'] ) $save = true;
		  if ( $pDataHash['address_store']['town'] <> $cfields['town'] ) $save = true;
		  if ( $pDataHash['address_store']['county'] <> $cfields['county'] ) $save = true;
		  if ( $pDataHash['address_store']['postcode'] <> $cfields['postcode'] ) $save = true;
			if ( $save ) {
				$result = $this->mDb->associateUpdate( $atable, $pDataHash['address_store'], array( "usn" => $usn ) );
		  }
		} else {
			$this->mDb->StartTrans();
			$this->mContentId = 0;
			$pDataHash['content_id'] = 0;
			if ( LibertyContent::store( $pDataHash ) ) {
				$pDataHash['citizen_store']['content_id'] = $pDataHash['content_id'];
				$pDataHash['address_store']['content_id'] = $pDataHash['content_id'];
				
				$result = $this->mDb->associateInsert( $table, $pDataHash['citizen_store'] );
				$result = $this->mDb->associateInsert( $atable, $pDataHash['address_store'] );

				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
				$this->mErrors['store'] = 'Failed to store this citizen.';
			}				
		}
		return( count( $this->mErrors ) == 0 ); 
	}
	
	/**
	 * GoldenXrefRecordLoad( $data );
	 * Rother golden xref file import  
	 */
	function GoldenXrefRecordLoad( &$data ) {
		$table = BIT_DB_PREFIX."citizen_xref";
		
		$pDataHash['data_store']['content_id'] = $data[0];
		$pDataHash['data_store']['xref_key'] = $data[0];
		$pDataHash['data_store']['source'] = $data[1];
		$pDataHash['data_store']['cross_reference'] = $data[2];
		$pDataHash['data_store']['data'] = $data[3];
		$pDataHash['data_store']['start_date'] = $this->mDb->NOW();
		$pDataHash['data_store']['last_update_date'] = $this->mDb->NOW();
		$pDataHash['data_store']['entry_date'] = $this->mDb->NOW();
		$result = $this->mDb->associateInsert( $table, $pDataHash['data_store'] );
	}

	/**
	 * Delete golden object and all related records
	 */
	function GoldenExpunge()
	{
		$ret = FALSE;
		$query = "DELETE FROM `".BIT_DB_PREFIX."citizen`";
		$result = $this->mDb->query( $query );
		$query = "DELETE FROM `".BIT_DB_PREFIX."citizen_address`";
		$result = $this->mDb->query( $query );
		$query = "DELETE FROM `".BIT_DB_PREFIX."citizen_xref`";
		$result = $this->mDb->query( $query );
		return $ret;
	}

	/**
	 * getCitizenList( &$pParamHash );
	 * Get list of citizen records 
	 */
	function getCitizenList( &$pParamHash ) {
		global $gBitSystem, $gBitUser;
		
		if ( empty( $pParamHash['sort_mode'] ) ) {
			if ( empty( $_REQUEST["sort_mode"] ) ) {
				$pParamHash['sort_mode'] = 'surname_asc';
			} else {
			$pParamHash['sort_mode'] = $_REQUEST['sort_mode'];
			}
		}
		
		LibertyContent::prepGetList( $pParamHash );

		$findSql = '';
		$selectSql = '';
		$joinSql = '';
		$whereSql = '';
		$bindVars = array();
		$type = 'surname';
		
		// this will set $find, $sort_mode, $max_records and $offset
		extract( $pParamHash );

		if( isset( $find_org ) and is_string( $find_org ) and $find_org <> '' ) {
			$whereSql .= " AND UPPER( ci.`organisation` ) like ? ";
			$bindVars[] = '%' . strtoupper( $find_org ). '%';
			$type = 'organisation';
			$pParamHash["listInfo"]["ihash"]["find_org"] = $find_org;
		}
		if( isset( $find_name ) and is_string( $find_name ) and $find_name <> '' ) {
		    $split = preg_split('|[,. ]|', $find_name, 2);
			$whereSql .= " AND UPPER( ci.`surname` ) STARTING ? ";
			$bindVars[] = strtoupper( $split[0] );
		    if ( array_key_exists( 1, $split ) ) {
				$split[1] = trim( $split[1] );
				$whereSql .= " AND UPPER( ci.`forename` ) STARTING ? ";
				$bindVars[] = strtoupper( $split[1] );
			}
			$pParamHash["listInfo"]["ihash"]["find_name"] = $find_name;
		}
		if( isset( $find_street ) and is_string( $find_street ) and $find_street <> '' ) {
			$whereSql .= " AND UPPER( a.`street` ) like ? ";
			$bindVars[] = '%' . strtoupper( $find_street ). '%';
			$pParamHash["listInfo"]["ihash"]["find_street"] = $find_street;
		}
		if( isset( $find_org ) and is_string( $find_postcode ) and $find_postcode <> '' ) {
			$whereSql .= " AND UPPER( `a.postcode` ) LIKE ? ";
			$bindVars[] = '%' . strtoupper( $find_postcode ). '%';
			$pParamHash["listInfo"]["ihash"]["find_postcode"] = $find_postcode;
		}
		$query = "SELECT ci.*, a.UPRN, a.POSTCODE, a.SAO, a.PAO, a.NUMBER, a.STREET, a.LOCALITY, a.TOWN, a.COUNTY, ci.parent_id as uprn,
			(SELECT COUNT(*) FROM `".BIT_DB_PREFIX."citizen_xref` x WHERE x.content_id = ci.content_id ) AS links, 
			(SELECT COUNT(*) FROM `".BIT_DB_PREFIX."task_ticket` e WHERE e.usn = ci.usn ) AS enquiries $selectSql 
			FROM `".BIT_DB_PREFIX."citizen` ci 
			LEFT JOIN `".BIT_DB_PREFIX."citizen_address` a ON a.content_id = ci.content_id $findSql
			$joinSql 
			WHERE ci.`".$type."` <> '' $whereSql ORDER BY ".$this->mDb->convertSortmode( $sort_mode );
		$query_cant = "SELECT COUNT( * )
			FROM `".BIT_DB_PREFIX."citizen` ci
			LEFT JOIN `".BIT_DB_PREFIX."citizen_address` a ON a.content_id = ci.content_id $findSql
			$joinSql WHERE ci.`".$type."` <> '' $whereSql ";
//			INNER JOIN `".BIT_DB_PREFIX."citizen_address` a ON a.content_id = ci.content_id 
		$result = $this->mDb->query( $query, $bindVars, $max_records, $offset );
		$ret = array();
		while( $res = $result->fetchRow() ) {
			if (!empty($parse_split)) {
				$res = array_merge($this->parseSplit($res), $res);
			}
			$ret[] = $res;
		}
		$pParamHash["cant"] = $this->mDb->getOne( $query_cant, $bindVars );

		LibertyContent::postGetList( $pParamHash );
		return $ret;
	}


	/**
	 * loadCitizen( &$pParamHash );
	 * Get citizen record 
	 */
	function loadCitizen( &$pParamHash = NULL ) {
		if( $this->isValid() ) {
		$sql = "SELECT ci.*, a.*, n.*, p.*
			FROM `".BIT_DB_PREFIX."citizen` ci 
			LEFT JOIN `".BIT_DB_PREFIX."citizen_address` a ON a.usn = ci.usn
			LEFT JOIN `".BIT_DB_PREFIX."nlpg_blpu` n ON n.`uprn` = ci.`nlpg`
			LEFT JOIN `".BIT_DB_PREFIX."nlpg_lpi` p ON p.`uprn` = ci.`nlpg` AND p.`language` = 'ENG' AND p.`logical_status` = 1
			WHERE ci.`content_id` = ?";
			if( $rs = $this->mDb->query( $sql, array( $this->mContentId ) ) ) {
				if(	$this->mInfo = $rs->fields ) {
/*					if(	$this->mInfo['local_custodian_code'] == 0 ) {
						global $gBitSystem;
						$gBitSystem->fatalError( tra( 'You do not have permission to access this client record' ), 'error.tpl', tra( 'Permission denied.' ) );
					}
*/

					$sql = "SELECT x.`last_update_date`, x.`source`, x.`cross_reference` 
							FROM `".BIT_DB_PREFIX."citizen_xref` x
							WHERE x.content_id = ?";
/* Link to legacy system
							CASE
							WHEN x.`source` = 'POSTFIELD' THEN (SELECT `USN` FROM `".BIT_DB_PREFIX."caller` c WHERE ci.`caller_id` = x.`cross_reference`)
							ELSE '' END AS USN 
							
 */

					$result = $this->mDb->query( $sql, array( $this->mContentId ) );

					while( $res = $result->fetchRow() ) {
						$this->mInfo['xref'][] = $res;
						if ( $res['source'] == 'POSTFIELD' ) $ticket[] = $res['cross_reference'];
					}
					if ( isset( $ticket ) )
					{ $sql = "SELECT t.* FROM `".BIT_DB_PREFIX."task_ticket` t 
							WHERE t.caller_id IN(". implode(',', array_fill(0, count($ticket), '?')) ." )";
						$result = $this->mDb->query( $sql, $ticket );
						while( $res = $result->fetchRow() ) {
							$this->mInfo['tickets'][] = $res;
						}
					}
					$os1 = new OSRef($this->mInfo['x_coordinate'], $this->mInfo['y_coordinate']);
					$ll1 = $os1->toLatLng();
					$this->mInfo['prop_lat'] = $ll1->lat;
					$this->mInfo['prop_lng'] = $ll1->lng;
//					$this->mInfo['display_usrn'] = $this->getUsrnEntryUrl( $this->mInfo['usrn'] );
//					$this->mInfo['display_uprn'] = $this->getUprnEntryUrl( $this->mInfo['uprn'] );
//vd($this->mInfo);
				} else {
					global $gBitSystem;
					$gBitSystem->fatalError( tra( 'Client record does not exist' ), 'error.tpl', tra( 'Not found.' ) );
				}
			}
		}
		return( count( $this->mInfo ) );
	}


	/**
	 * getXrefList( &$pParamHash );
	 * Get list of xref records for this citizen record
	 */
	function loadXrefList() {
		if( $this->isValid() && empty( $this->mInfo['xref'] ) ) {
		
			$sql = "SELECT x.`last_update_date`, x.`source`, x.`cross_reference` 
				FROM `".BIT_DB_PREFIX."citizen_xref` x
				WHERE x.content_id = ?";

			$result = $this->mDb->query( $sql, array( $this->mContentId ) );

			while( $res = $result->fetchRow() ) {
				$this->mInfo['xref'][] = $res;
				if ( $res['source'] == 'POSTFIELD' ) $caller[] = $res['cross_reference'];
			}

			$sql = "SELECT t.* FROM `".BIT_DB_PREFIX."task_ticket` t 
				WHERE t.usn = ?";
			$result = $this->mDb->query( $sql, array( '9000000001' ) ); //$this->mCitizenId ) );
			while( $res = $result->fetchRow() ) {
				$this->mInfo['tickets'][] = $res;
			}

		}
	}

}
?>
