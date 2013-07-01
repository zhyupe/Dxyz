<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_home_friend.php 31044 2012-07-12 01:50:32Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_home_friend extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_friend';
		$this->_pk    = 'uid';

		parent::__construct();
	}

	public function fetch_all_by_uid_username($uid, $username, $start = 0, $limit = 0) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE uid=%d AND fusername IN (%n) %i', array($this->_table, $uid, $username, Dxyz_DB::limit($start, $limit)));
	}

	public function fetch_all_by_uid_fuid($uid, $fuid) {
		if(!$uid || !$fuid) {
			return null;
		}
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE uid=%d AND fuid IN (%n)', array($this->_table, $uid, $fuid));
	}

	public function fetch_all_by_uid_gid($uid, $gid, $start = 0, $limit = 100, $order = true) {
		$parameter = array($this->_table, $uid, $gid);

		if($order) {
			$limitsql = ' ORDER BY num DESC, dateline DESC';
		}
		if($limit) {
			$parameter[] = Dxyz_DB::limit($start, $limit);
			$limitsql .= ' %i';
		}
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE uid=%d AND gid=%d'.$limitsql, $parameter);
	}

	public function fetch_all_by_uid_common($uid, $fuid) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE uid=%d OR uid=%d', array($this->_table, $uid, $fuid));
	}

	public function fetch_all_by_uid($uid, $start, $limit, $order = false) {
		return $this->fetch_all_search($uid, '', '', false, $start, $limit, $order);
	}

	public function fetch_all_search($uid, $gid, $searchkey, $count = false, $start = 0, $limit = 0, $order = false) {
		$parameter = array($this->_table);
		$wherearr = array();
		$parameter[] = $uid;
		$wherearr[] = is_array($uid) ? 'uid IN(%n)' : 'uid=%d';

		if(is_numeric($gid) && $gid > -1) {
			$parameter[] = $gid;
			$wherearr[] = 'gid=%d';
		}

		if($searchkey) {
			$field = "fusername LIKE '{text}%'";
			$keyword = $searchkey;
			if(preg_match("(AND|\+|&|\s)", $keyword) && !preg_match("(OR|\|)", $keyword)) {
				$andor = ' AND ';
				$keywordsrch = '1';
				$keyword = preg_replace("/( AND |&| )/is", "+", $keyword);
			} else {
				$andor = ' OR ';
				$keywordsrch = '0';
				$keyword = preg_replace("/( OR |\|)/is", "+", $keyword);
			}
			$keyword = str_replace('*', '%', addcslashes(daddslashes($keyword), '%_'));
			foreach(explode('+', $keyword) as $text) {
				$text = trim($text);
				if($text) {
					$keywordsrch .= $andor;
					$keywordsrch .= str_replace('{text}', $text, $field);
				}
			}
			$parameter[] = " ($keywordsrch)";
			$wherearr[] = '%i';
		}

		if(!$count) {
			if($order) {
				$limitsql = ' ORDER BY num DESC, dateline DESC';
			}
			if($limit) {
				$parameter[] = Dxyz_DB::limit($start, $limit);
				$limitsql .= ' %i';
			}
		}

		$wheresql = !empty($wherearr) && is_array($wherearr) ? ' WHERE '.implode(' AND ', $wherearr) : '';

		if($count) {
			return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t '.$wheresql, $parameter);
		} else {
			return Dxyz_DB::fetch_all('SELECT * FROM %t '.$wheresql.$limitsql, $parameter);
		}
	}

	public function count_by_uid($uid) {
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE uid=%d', array($this->_table, $uid));
	}

	public function delete_by_uid_fuid($uids) {
		if(!$uids) {
			return null;
		}
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('uid', $uids).' OR '.Dxyz_DB::field('fuid', $uids));
	}

	public function delete_by_uid_fuid_dual($uids, $touid) {
		if(!$uids || !$touid) {
			return null;
		}
		return Dxyz_DB::delete($this->_table, '('.Dxyz_DB::field('uid', $uids).' AND '.Dxyz_DB::field('fuid', $touid).') OR ('.Dxyz_DB::field('fuid', $uids).' AND '.Dxyz_DB::field('uid', $touid).')');
	}

	public function update_num_by_uid_fuid($incnum, $uid, $fuid) {
		return Dxyz_DB::query('UPDATE %t SET num=num+\'%d\' WHERE uid=%d AND fuid=%d', array($this->_table, $incnum, $uid, $fuid));
	}

	public function update_by_uid_fuid($uid, $fuid, $data) {
		if(!$uid || !$fuid || empty($data) || !is_array($data)) {
			return null;
		}
		return Dxyz_DB::update($this->_table, $data, Dxyz_DB::field('uid', $uid).' AND '.Dxyz_DB::field('fuid', $fuid));
	}
}

?>