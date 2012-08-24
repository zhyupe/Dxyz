<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_member_medal.php 27772 2012-02-14 06:48:34Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_member_medal extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_member_medal';
		$this->_pk    = '';

		parent::__construct();
	}

	public function fetch_all_by_uid($uid) {
		return !empty($uid) ? Dxyz_DB::fetch_all('SELECT * FROM '.Dxyz_DB::table($this->_table).' WHERE '.Dxyz_DB::field('uid', $uid), 'medalid') : array();
	}

	public function delete_by_uid_medalid($uid, $medalid) {
		return !empty($uid) && !empty($medalid) ? Dxyz_DB::delete($this->_table, Dxyz_DB::field('uid', $uid).' AND '.Dxyz_DB::field('medalid', $medalid)) : false;
	}

	public function count_by_uid_medalid($uid, $medalid) {
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE uid=%d AND medalid=%d', array($this->_table, $uid, $medalid));
	}
}

?>