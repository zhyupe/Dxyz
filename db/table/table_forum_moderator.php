<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_moderator.php 28576 2012-03-05 06:46:08Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_moderator extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_moderator';
		$this->_pk    = '';

		parent::__construct();
	}

	public function fetch_all_by_fid($fid, $order = true) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE fid=%d'.($order ? ' ORDER BY inherited, displayorder' : ''), array($this->_table, $fid), 'uid');
	}

	public function fetch_all_by_fid_inherited($fid, $inherited = 0) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE fid=%d AND inherited=%d', array($this->_table, $fid, $inherited), 'uid');
	}

	public function fetch_all_by_uid($uid) {
		if(!$uid) {
			return null;
		}
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE %i', array($this->_table, Dxyz_DB::field('uid', $uid)), 'fid');
	}

	public function fetch_all_by_uid_forum($uid) {
		return Dxyz_DB::fetch_all('SELECT m.fid, f.name, f.recyclebin
			FROM %t m, %t f
			WHERE m.uid=%d AND f.fid=m.fid AND f.status=\'1\' AND f.type<>\'group\'', array($this->_table, 'forum_forum', $uid));
	}

	public function fetch_uid_by_fid_uid($fid, $uid) {
		return Dxyz_DB::result_first('SELECT uid FROM %t WHERE fid=%d AND uid=%d', array($this->_table, $fid, $uid));
	}

	public function fetch_uid_by_tid($tid, $uid, $archiveid) {
		$archiveid = dintval($archiveid);
		$threadtable = $archiveid ? "forum_thread_{$archiveid}" : 'forum_thread';
		return Dxyz_DB::result_first('SELECT uid FROM %t m INNER JOIN %t t ON t.tid=%d AND t.fid=m.fid WHERE m.uid=%d', array($this->_table, $threadtable, $tid, $uid));
	}

	public function count_by_uid($uid) {
		if(!$uid) {
			return null;
		}
		return Dxyz_DB::result_first('SELECT count(*) FROM %t WHERE %i', array($this->_table, Dxyz_DB::field('uid', $uid)));
	}

	public function fetch_all_no_inherited_by_fid($fid) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE fid=%d AND inherited=0 ORDER BY displayorder', array($this->_table, $fid), 'uid');
	}

	public function fetch_all_no_inherited() {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE inherited=0 ORDER BY displayorder', array($this->_table));
	}

	public function update_by_fid_uid($fid, $uid, $data) {
		if(!$fid || !$uid || !$data || !is_array($data)) {
			return null;
		}
		return Dxyz_DB::update($this->_table, $data, array('fid' => $fid, 'uid' => $uid));
	}
	public function delete_by_uid($uid) {
		return $uid ? Dxyz_DB::delete($this->_table, Dxyz_DB::field('uid', $uid)) : false;
	}

	public function delete_by_fid($fid) {
		return $fid ? Dxyz_DB::delete($this->_table, Dxyz_DB::field('fid', $fid)) : false;
	}

	public function delete_by_fid_inherited($fid, $inherited) {
		return $fid ? Dxyz_DB::delete($this->_table, Dxyz_DB::field('fid', $fid).' AND '.Dxyz_DB::field('inherited', $inherited)) : false;
	}

	public function delete_by_uid_fid_inherited($uid, $fid, $fidarray) {
		if(!$fid || !$uid) {
			return null;
		}
		$fid = dintval($fid);
		$uid = dintval($uid);
		$fidarray = array_map('addslashes', $fidarray);
		return Dxyz_DB::delete($this->_table, "uid='$uid' AND ((fid='$fid' AND inherited='0') OR (fid IN (".dimplode($fidarray).") AND inherited='1'))");
	}

	public function delete_by_uid_fid($uid, $fid) {
		if(!$fid || !$uid) {
			return null;
		}
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('uid', $uid).' AND '.Dxyz_DB::field('fid', $fid).' AND '.Dxyz_DB::field('inherited', 1));
	}
}

?>