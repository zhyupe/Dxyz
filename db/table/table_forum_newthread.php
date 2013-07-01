<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_groupuser.php 31121 2012-07-18 06:01:56Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_newthread extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_newthread';
		$this->_pk    = '';

		parent::__construct();
	}
	public function fetch_all_by_fids($fids, $start = 0, $limit = 20) {
		if(empty($fids)) {
			return array();
		}
		return Dxyz_DB::fetch_all("SELECT * FROM %t WHERE %i ORDER BY dateline DESC %i", array($this->_table, Dxyz_DB::field('fid', $fids), Dxyz_DB::limit($start, $limit)), 'tid');
	}
	public function delete_by_fids($fids) {
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('fid', $fids));
	}

	public function delete_by_tids($tids) {
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('tid', $tids));
	}

	public function delete_by_dateline($timestamp) {
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('dateline', $timestamp, '<'));
	}
}

?>