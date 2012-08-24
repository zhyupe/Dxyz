<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_collectioninvite.php 27779 2012-02-14 07:33:17Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_collectioninvite extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_collectioninvite';
		$this->_pk    = '';

		parent::__construct();
	}


	public function fetch_by_ctid_uid($ctid, $uid) {
		return Dxyz_DB::fetch_first('SELECT * FROM %t WHERE ctid=%d AND uid=%d', array($this->_table, $ctid, $uid));
	}

	public function delete_by_ctid_uid($ctid, $uid) {
		$condition = array();

		if($ctid) {
			$condition[] = Dxyz_DB::field('ctid', $ctid);
		}

		if($uid) {
			$condition[] = Dxyz_DB::field('uid', $uid);
		}

		if(!count($condition)) {
			return false;
		}

		Dxyz_DB::delete($this->_table, implode(' AND ', $condition));
	}

	public function delete_by_ctid($ctid) {
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('ctid', $ctid));
	}

	public function delete_by_dateline($dateline) {
		if(!is_numeric($dateline)) {
			return false;
		}
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('dateline', $dateline, '<='));
	}
}

?>