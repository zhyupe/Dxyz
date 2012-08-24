<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_typevar.php 28071 2012-02-22 04:06:45Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_typevar extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_typevar';
		$this->_pk    = '';

		parent::__construct();
	}

	public function count_by_search($search) {
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE search>%d', array($this->_table, $search));
	}

	public function fetch_all_by_search_optiontype($search, $optiontypes) {
		if(empty($optiontypes)) {
			return array();
		}
		return Dxyz_DB::fetch_all('SELECT p.*, v.* FROM %t v LEFT JOIN %t p ON p.optionid=v.optionid WHERE search=%d OR p.'.Dxyz_DB::field('type', $optiontypes),
				array($this->_table, 'forum_typeoption', $search));
	}

	public function fetch_all_by_sortid($sortid, $order = '') {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE sortid=%d '.($order ? 'ORDER BY '.Dxyz_DB::order('displayorder', $order) : ''), array($this->_table, $sortid), 'optionid');
	}

	public function update($sortid, $optionid, $data, $unbuffered = false, $low_priority = false) {
		if(empty($data)) {
			return false;
		}
		return Dxyz_DB::update($this->_table, $data, array('sortid' => $sortid, 'optionid' => $optionid), $unbuffered, $low_priority);
	}

	public function update_by_search($search, $data, $unbuffered = false, $low_priority = false) {
		if(empty($data)) {
			return false;
		}
		return Dxyz_DB::update($this->_table, $data, array('search' => $search), $unbuffered, $low_priority);
	}

	public function delete($sortids = null, $optionids = null) {
		$where = array();
		$sortids && $where[] = Dxyz_DB::field('sortid', $sortids);
		$optionids && $where[] = Dxyz_DB::field('optionid', $optionids);
		if($where) {
			return Dxyz_DB::query('DELETE FROM %t WHERE '.implode(' AND ', $where), array($this->_table));
		} else {
			return false;
		}
	}
}

?>