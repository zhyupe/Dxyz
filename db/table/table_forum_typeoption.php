<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_typeoption.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_typeoption extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_typeoption';
		$this->_pk    = 'optionid';

		parent::__construct();
	}

	public function fetch_all_by_classid($classid, $start = 0, $limit = 0) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE classid=%d ORDER BY displayorder '.Dxyz_DB::limit($start, $limit), array($this->_table, $classid));
	}

	public function fetch_all_by_identifier($identifier, $start = 0, $limit = 0, $not_optionid = null) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE identifier=%s '.($not_optionid ? ' AND '.Dxyz_DB::field('optionid', $not_optionid, '<>').' ' : '').Dxyz_DB::limit($start, $limit), array($this->_table, $identifier));
	}

}

?>