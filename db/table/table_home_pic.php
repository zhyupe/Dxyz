<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_home_pic.php 30713 2012-06-13 09:44:05Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_home_pic extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_pic';
		$this->_pk    = 'picid';

		parent::__construct();
	}

	public function update_click($picid, $clickid, $incclick) {
		$clickid = intval($clickid);
		if($clickid < 1 || $clickid > 8 || empty($picid) || empty($incclick)) {
			return false;
		}
		return Dxyz_DB::query('UPDATE %t SET click'.$clickid.' = click'.$clickid.'+\'%d\' WHERE picid = %d', array($this->_table, $incclick, $picid));
	}
	public function update_hot($picid, $num = 1) {
		return Dxyz_DB::query('UPDATE %t SET hot=hot+\'%d\' WHERE picid=%d', array($this->_table, $num, $picid));
	}
	public function update_sharetimes($picid, $num = 1) {
		return Dxyz_DB::query('UPDATE %t SET sharetimes=sharetimes+\'%d\' WHERE picid=%d', array($this->_table, $num, $picid));
	}
	public function fetch_all_by_uid($uids, $start = 0, $limit = 0, $picids = 0) {
		if(empty($uids)) {
			return array();
		}
		$picidsql = $picids ? Dxyz_DB::field('picid', $picids).' AND ' : '';
		return Dxyz_DB::fetch_all("SELECT * FROM %t WHERE $picidsql ".Dxyz_DB::field('uid', $uids).Dxyz_DB::limit($start, $limit), array($this->_table));
	}
	public function update_for_uid($uids, $picids, $data) {
		if(!empty($data) && is_array($data)) {
			return Dxyz_DB::update($this->_table, $data, Dxyz_DB::field('picid', $picids).' AND '.Dxyz_DB::field('uid', $uids));
		}
		return 0;
	}
	public function fetch_all_by_albumid($albumids, $start = 0, $limit = 0, $picids = 0, $orderbypicid = 0, $orderbydateline = 0, $uid = 0, $count = false) {
		$albumids = $albumids < 0 ? 0 : $albumids;
		$picidsql = $picids ? Dxyz_DB::field('picid', $picids).' AND ' : '';
		if($orderbypicid) {
			$ordersql = 'ORDER BY picid DESC ';
		} elseif($orderbydateline) {
			$ordersql = 'ORDER BY dateline DESC ';
		}
		$uidsql = $uid ? ' AND '.Dxyz_DB::field('uid', $uid) : '';
		if ($count) {
			return Dxyz_DB::result_first("SELECT COUNT(*) FROM %t WHERE $picidsql ".Dxyz_DB::field('albumid', $albumids)." $uidsql", array($this->_table));
		} else {
			return Dxyz_DB::fetch_all("SELECT * FROM %t WHERE $picidsql ".Dxyz_DB::field('albumid', $albumids)." $uidsql $ordersql".Dxyz_DB::limit($start, $limit), array($this->_table));
		}
	}
	public function update_for_albumid($albumid, $data) {
		if(!empty($data) && is_array($data)) {
			return Dxyz_DB::update($this->_table, $data, Dxyz_DB::field('albumid', $albumid));
		}
		return 0;
	}
	public function delete_by_uid($uids) {
		return Dxyz_DB::query("DELETE FROM %t WHERE ".Dxyz_DB::field('uid', $uids), array($this->_table));
	}
	public function delete_by_albumid($albumids) {
		return Dxyz_DB::query("DELETE FROM %t WHERE ".Dxyz_DB::field('albumid', $albumids), array($this->_table));
	}
	public function fetch_all_by_sql($where = '1', $orderby = '', $start = 0, $limit = 0, $count = 0, $joinalbum = 1) {
		if(!$where) {
			$where = '1';
		}
		if($count) {
			return Dxyz_DB::result_first("SELECT count(*) FROM ".Dxyz_DB::table($this->_table)." p WHERE %i", array($where));
		}
		return Dxyz_DB::fetch_all("SELECT ".($joinalbum ? 'a.*, ' : '')."p.* FROM ".Dxyz_DB::table($this->_table)." p ".($joinalbum ? "LEFT JOIN ".Dxyz_DB::table('home_album')." a USING(albumid)" : '')." WHERE %i ".($orderby ? "ORDER BY $orderby " : '').Dxyz_DB::limit($start, $limit), array($where));
	}
	public function fetch_albumpic($albumid, $uid) {
		return Dxyz_DB::fetch_first("SELECT filepath, thumb FROM %t WHERE albumid=%d AND uid=%d ORDER BY thumb DESC, dateline DESC LIMIT 0,1", array($this->_table, $albumid, $uid));
	}
	public function check_albumpic($albumid, $status = NULL, $uid = 0) {
		$sql = $albumid ? Dxyz_DB::field('albumid', $albumid) : '';
		$sql .= $uid ? ($sql ? ' AND ' : '').Dxyz_DB::field('uid', $uid) : '';
		$sql .= $status === NULL ? '' : ($sql ? ' AND ' : '').Dxyz_DB::field('status', $status);
		return Dxyz_DB::result_first("SELECT COUNT(*) FROM %t WHERE $sql", array($this->_table));
	}
	public function count_size_by_uid($uid) {
		return Dxyz_DB::result_first("SELECT SUM(size) FROM %t WHERE uid=%d", array($this->_table, $uid));
	}
	public function fetch_by_id_idtype($id) {
		if(!$id) {
			return false;
		}
		return Dxyz_DB::fetch_first('SELECT * FROM %t WHERE %i', array($this->_table, Dxyz_DB::field('picid', $id)));
	}
	public function update_dateline_by_id_idtype_uid($id, $idtype, $dateline, $uid) {
		if(empty($id) || empty($idtype) || empty($dateline) || empty($uid)) {
			return false;
		}
		return Dxyz_DB::update($this->_table, array('dateline' => intval($dateline)), array($idtype => intval($id), 'uid' => intval($uid)));
	}
}

?>