<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_home_feed.php 28335 2012-02-28 04:37:47Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_home_feed extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_feed';
		$this->_pk    = 'feedid';

		parent::__construct();
	}

	public function optimize_table() {
		return Dxyz_DB::query("OPTIMIZE TABLE %t", array($this->_table), true);
	}

	public function fetch($id, $idtype = '', $uid = '', $feedid = '') {
		$wherearr = array();
		if($feedid) {
			$wherearr[] = Dxyz_DB::field('feedid', $feedid);
		}
		if($id) {
			$wherearr[] = Dxyz_DB::field('id', $id);
			$wherearr[] = Dxyz_DB::field('idtype', $idtype);
		}
		if($uid) {
			$wherearr[] = Dxyz_DB::field('uid', $uid);
		}
		$wheresql = !empty($wherearr) && is_array($wherearr) ? ' WHERE '.implode(' AND ', $wherearr) : '';

		if(empty($wheresql)) {
			return null;
		}

		return Dxyz_DB::fetch_first('SELECT * FROM '.Dxyz_DB::table($this->_table).' '.$wheresql);
	}

	public function fetch_all_by_uid_dateline($uids, $findex = true, $start = 0, $limit = 5) {
		if(!($uids = dintval($uids, true))) {
			return null;
		}
		return Dxyz_DB::fetch_all('SELECT * FROM %t '.(($findex) ? 'USE INDEX(dateline)' : '').' WHERE uid IN (%n) ORDER BY dateline desc %i', array($this->_table, $uids, Dxyz_DB::limit($start, $limit)));
	}

	public function fetch_all_by_hot($hotstarttime) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t USE INDEX(hot) WHERE dateline>=%d ORDER BY hot DESC LIMIT 0,10', array($this->_table, $hotstarttime));
	}

	public function update($id, $data, $idtype = '', $uid = '', $feedid = '') {
		$condition = array();
		if($feedid) {
			$condition[] = Dxyz_DB::field('feedid', $feedid);
		}
		if($id) {
			$condition[] = Dxyz_DB::field('id', $id);
			$condition[] = Dxyz_DB::field('idtype', $idtype);
		}
		if($uid) {
			$condition[] = Dxyz_DB::field('uid', $uid);
		}

		if(empty($data) || !is_array($data) || !count($condition)) {
			return null;
		}
		Dxyz_DB::update($this->_table, $data, implode(' AND ', $condition));
	}

	public function update_hot_by_id($id, $idtype, $uid, $inchot) {
		Dxyz_DB::query('UPDATE %t SET hot = hot+\'%d\' WHERE id = %d AND idtype = %s AND uid = %d', array($this->_table, $inchot, $id, $idtype, $uid));
	}

	public function update_hot_by_feedid($feedid, $inchot) {
		Dxyz_DB::query('UPDATE %t SET hot = hot+\'%d\' WHERE feedid = %d', array($this->_table, $inchot, $feedid));
	}

	public function delete_by_dateline($dateline, $hot = 0) {
		if(!is_numeric($dateline) || !is_numeric($hot)) {
			return false;
		}
		$condition = array();

		$condition[] = Dxyz_DB::field('dateline', $dateline, '<');
		$condition[] = Dxyz_DB::field('hot', $hot);

		Dxyz_DB::delete($this->_table, implode(' AND ', $condition));
	}

	public function delete_by_id_idtype($ids, $idtype) {
		if(!$ids || !$idtype) {
			return null;
		}
		$condition = array();

		$condition[] = Dxyz_DB::field('id', $ids);
		$condition[] = Dxyz_DB::field('idtype', $idtype);

		Dxyz_DB::delete($this->_table, implode(' AND ', $condition));
	}

	public function delete_by_uid_idtype($uid, $idtype) {
		if(!$uid || !$idtype) {
			return null;
		}
		$condition = array();
		$condition[] = Dxyz_DB::field('uid', $uid);
		$condition[] = Dxyz_DB::field('idtype', $idtype);

		Dxyz_DB::delete($this->_table, implode(' AND ', $condition));
	}

	public function delete_by_icon($icon) {
		if(!$icon) {
			return null;
		}
		Dxyz_DB::delete($this->_table, Dxyz_DB::field('icon', $icon));
	}

	public function delete($feedid, $uid = '') {
		$condition = array();

		if($feedid) {
			$condition[] = Dxyz_DB::field('feedid', $feedid);
		}

		if($uid) {
			$condition[] = Dxyz_DB::field('uid', $uid);
		}

		if(!count($condition)) {
			return null;
		}

		Dxyz_DB::delete($this->_table, implode(' AND ', $condition));
	}

	public function delete_by_uid($uids) {
		if(!$uids) {
			return null;
		}
		Dxyz_DB::delete($this->_table, Dxyz_DB::field('uid', $uids).' OR ('.Dxyz_DB::field('id', $uids).' AND idtype=\'uid\')');
	}

	public function fetch_uid_by_username($users) {
		if(!$users) {
			return null;
		}
		return Dxyz_DB::fetch_all('SELECT uid FROM %t WHERE username IN (%n)', array($this->_table, $users), 'uid');
	}

	public function fetch_icon_by_icon($icon) {
		return Dxyz_DB::fetch_first('SELECT icon FROM %t WHERE icon=%s', array($this->_table, $icon));
	}

	public function fetch_feedid_by_hashdata($uid, $hash_data) {
		return Dxyz_DB::fetch_first('SELECT feedid FROM %t WHERE uid=%d AND hash_data=%s LIMIT 0,1', array($this->_table, $uid, $hash_data));
	}

	public function fetch_feedid_by_feedid($feedid) {
		if(!$feedid) {
			return null;
		}
		return Dxyz_DB::fetch_all('SELECT feedid FROM %t WHERE feedid IN (%n)', array($this->_table, $feedid), 'feedid');
	}

	public function fetch_uid_by_uid($uid) {
		if(!$uid) {
			return null;
		}
		return Dxyz_DB::fetch_all('SELECT uid FROM %t WHERE uid IN (%n)', array($this->_table, $uid), 'uid');
	}

	public function fetch_all_by_search($fetchtype, $uids, $icon, $starttime, $endtime, $feedids, $hot1, $hot2, $start = 0, $limit = 0, $findex = '', $appid = '') {
		$parameter = array($this->_table);
		$wherearr = array();
		if(is_array($uids) && count($uids)) {
			$parameter[] = $uids;
			$wherearr[] = 'uid IN(%n)';
		}

		if($appid) {
			$parameter[] = $appid;
			$wherearr[] = 'appid=%d';
		}

		if($icon) {
			$parameter[] = $icon;
			$wherearr[] = 'icon=%s';
		}

		if($starttime) {
			$parameter[] = is_numeric($starttime) ? $starttime : strtotime($starttime);
			$wherearr[] = 'dateline>%d';
		}

		if($endtime) {
			$parameter[] = is_numeric($endtime) ? $endtime : strtotime($endtime);
			$wherearr[] = 'dateline<%d';
		}

		if(is_array($feedids) && count($feedids)) {
			$parameter[] = $feedids;
			$wherearr[] = 'feedid IN(%n)';
		}

		if($hot1) {
			$parameter[] = $hot1;
			$wherearr[] = 'hot>=%d';
		}

		if($hot2) {
			$parameter[] = $hot2;
			$wherearr[] = 'hot<=%d';
		}

		if($fetchtype == 3) {
			$selectfield = "count(*)";
		} elseif ($fetchtype == 2) {
			$selectfield = "feedid";
		} else {
			$selectfield = "*";
			$parameter[] = Dxyz_DB::limit($start, $limit);
			$ordersql = ' ORDER BY dateline DESC %i';
		}

		if($findex) {
			$findex = 'USE INDEX(dateline)';
		}

		$wheresql = !empty($wherearr) && is_array($wherearr) ? ' WHERE '.implode(' AND ', $wherearr) : '';

		if($fetchtype == 3) {
			return Dxyz_DB::result_first("SELECT $selectfield FROM %t $wheresql", $parameter);
		} else {
			return Dxyz_DB::fetch_all("SELECT $selectfield FROM %t {$findex} $wheresql $ordersql", $parameter);
		}
	}
}

?>