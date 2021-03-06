<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_member_crime.php 33347 2013-05-30 08:24:40Z jeffjzhang $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_member_crime extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_member_crime';
		$this->_pk    = 'cid';

		parent::__construct();
	}

	public function fetch_all_by_uid($uid) {
		return $uid ? Dxyz_DB::fetch_all('SELECT * FROM %t WHERE uid=%d ORDER BY dateline DESC', array($this->_table, $uid), $this->_pk) : array();
	}

	public function count_by_uid_action($uid, $action) {
		return $uid ? Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE uid=%d AND action=%d', array($this->_table, $uid, $action)) : 0;
	}

	public function count_by_where($where) {
		return $where ? Dxyz_DB::result_first('SELECT COUNT(*) FROM %t %i ', array($this->_table, $where)) : $this->count();
	}

	public function fetch_all_by_where($where, $start = 0, $limit = 0) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t %i ORDER BY dateline DESC '.Dxyz_DB::limit($start, $limit), array($this->_table, $where));
	}

	public function fetch_all_by_uid_action($uid, $action) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE '.Dxyz_DB::field('uid', $uid).' AND '.Dxyz_DB::field('action', $action).' ORDER BY dateline', array($this->_table));
	}

	public function fetch_all_by_cid($cid, $action, $limit) {
		if(!$cid) {
			return Dxyz_DB::fetch_all('SELECT * FROM %t '.($action ? 'WHERE '.Dxyz_DB::field('action', $action) : '').' ORDER BY cid DESC '.Dxyz_DB::limit($limit), array($this->_table), $this->_pk);
		} else {
			return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE '.Dxyz_DB::field('cid', $cid, '<').($action ? ' AND '.Dxyz_DB::field('action', $action) : '').' ORDER BY cid DESC '.Dxyz_DB::limit($limit), array($this->_table), $this->_pk);
		}
	}
}

?>