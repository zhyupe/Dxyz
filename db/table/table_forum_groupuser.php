<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_groupuser.php 29459 2012-04-13 01:45:21Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_groupuser extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_groupuser';
		$this->_pk    = '';

		parent::__construct();
	}
	public function fetch_all_fid_by_uids($uids) {
		if(empty($uids)) {
			return array();
		}
		$data = array();
		$query = Dxyz_DB::query("SELECT fid FROM %t WHERE %i AND level>0 ORDER BY lastupdate DESC", array($this->_table, Dxyz_DB::field('uid', $uids)));
		while($row = Dxyz_DB::fetch($query)) {
			$data[] = $row['fid'];
		}
		return $data;
	}
	public function fetch_userinfo($uid, $fid) {
		if(empty($uid) || empty($fid)) {
			return array();
		}
		return Dxyz_DB::fetch_first("SELECT * FROM %t WHERE fid=%d AND uid=%d", array($this->_table, $fid, $uid));
	}
	public function fetch_all_userinfo($uids, $fid) {
		if(empty($uids) || empty($fid)) {
			return array();
		}
		return Dxyz_DB::fetch_all("SELECT * FROM %t WHERE fid=%d AND ".Dxyz_DB::field('uid', $uids), array($this->_table, $fid));
	}
	public function fetch_all_by_fid($fid, $level = 0) {
		if(empty($fid)) {
			return array();
		}
		$levelsql = ' AND level>0';
		if($level == 1) {
			$levelsql = ' AND level=0';
		} elseif($level == -1) {
			$levelsql = '';
		}
		return Dxyz_DB::fetch_all("SELECT * FROM %t WHERE fid=%d".$levelsql, array($this->_table, $fid));
	}
	public function fetch_count_by_fid($fid, $level = 0) {
		$levelsql = ' AND level>0';
		if($level == 1) {
			$levelsql = ' AND level=0';
		} elseif($level == -1) {
			$levelsql = '';
		}
		return Dxyz_DB::result_first("SELECT COUNT(*) FROM %t WHERE fid=%d".$levelsql, array($this->_table, $fid));
	}
	public function insert($fid, $uid, $username, $level, $joindateline, $lastupdate = 0) {
		Dxyz_DB::query("INSERT INTO %t (fid, uid, username, level, joindateline, lastupdate) VALUES (%d,%d,%s,%d,%d,%d)", array($this->_table, $fid, $uid, addslashes($username), $level, $joindateline, $lastupdate));
	}
	public function update_counter_for_user($uid, $fid, $threads = 0, $replies = 0) {
		if(empty($uid) || empty($fid)) {
			return array();
		}
		$sql = $threads ? 'threads=threads+1' : '';
		if($replies) {
			$sql = ($sql ? ', ' : '').'replies=replies+1';
		}
		if(empty($sql)) {
			return false;
		}
		Dxyz_DB::query("UPDATE ".Dxyz_DB::table('forum_groupuser')." SET $sql, lastupdate='".TIMESTAMP."' WHERE fid=%d AND uid=%d", array($fid, $uid));
	}
	public function delete_by_fid($fids, $uid = 0) {
		if(empty($fids)) {
			return false;
		}
		if($uid) {
			$sqladd = ' AND '.Dxyz_DB::field('uid', $uid);
		}
		Dxyz_DB::query("DELETE FROM ".Dxyz_DB::table('forum_groupuser')." WHERE %i ".$sqladd, array(Dxyz_DB::field('fid', $fids)));
	}
	public function update_for_user($uid, $fid, $threads = null, $replies = null, $level = null) {
		if(empty($uid) || empty($fid)) {
			return array();
		}
		$sqladd = $threads !== null ? 'threads='.intval($threads) : '';
		if($replies !== null) {
			$sqladd .= ($sqladd ? ', ' : '').'replies='.intval($replies);
		}
		if($level !== null) {
			$sqladd .= ($sqladd ? ', ' : '').'level='.intval($level);
		}
		Dxyz_DB::query("UPDATE %t SET $sqladd WHERE fid=%d AND ".Dxyz_DB::field('uid', $uid), array($this->_table, $fid));
	}

	public function groupuserlist($fid, $orderby = '', $num = 0, $start = 0, $addwhere = '', $fieldarray = array(), $onlinemember = array()) {
		$fid = intval($fid);
		if($fieldarray && is_array($fieldarray)) {
			$fieldadd = 'uid';
			foreach($fieldarray as $field) {
				$fieldadd .= ' ,'.$field;
			}
		} else {
			$fieldadd = '*';
		}

		$sqladd = $levelwhere = '';
		if($addwhere) {
			if(is_array($addwhere)) {
				foreach($addwhere as $field => $value) {
					if(is_array($value)) {
						$levelwhere = "AND level>'0' ";
						$sqladd .= "AND $field IN (".dimplode($value).") ";
					} else {
						$sqladd .= is_numeric($field) ? "AND $value " : "AND $field='$value' ";
					}
				}
				if(!empty($addwhere['level'])) $levelwhere = '';
			} else {
				$sqladd = $addwhere;
			}
		}

		$orderbyarray = array('level_join' => 'level ASC, joindateline ASC', 'joindateline' => 'joindateline DESC', 'lastupdate' => 'lastupdate DESC', 'threads' => 'threads DESC', 'replies' => 'replies DESC');
		$orderby = !empty($orderbyarray[$orderby]) ? "ORDER BY $orderbyarray[$orderby]" : '';
		$limitsql = $num ? Dxyz_DB::limit($start, $num) : '';

		$groupuserlist = array();
		$query = Dxyz_DB::query("SELECT $fieldadd FROM ".Dxyz_DB::table('forum_groupuser')." WHERE fid=%d $levelwhere $sqladd $orderby $limitsql", array($fid));
		while($groupuser = Dxyz_DB::fetch($query)) {
			$groupuserlist[$groupuser['uid']] = $groupuser;
			$groupuserlist[$groupuser['uid']]['online'] = !empty($onlinemember) && is_array($onlinemember) && !empty($onlinemember[$groupuser['uid']]) ? 1 : 0;
		}

		return $groupuserlist;
	}
	public function fetch_all_group_for_user($uid, $count = 0, $ismanager = 0, $start = 0, $num = 0) {
		$uid = intval($uid);
		if(empty($uid)) {
			return array();
		}
		if(empty($ismanager)) {
			$levelsql = '';
		} elseif($ismanager == 1) {
			$levelsql = ' AND level IN(1,2)';
		} elseif($ismanager == 2) {
			$levelsql = ' AND level IN(3,4)';
		}
		if($count == 1) {
			return Dxyz_DB::result_first("SELECT count(*) FROM ".Dxyz_DB::table('forum_groupuser')." WHERE uid='$uid' $levelsql");
		}
		empty($start) && $start = 0;
		empty($num) && $num = 100;
		return Dxyz_DB::fetch_all("SELECT fid, level FROM ".Dxyz_DB::table('forum_groupuser')." WHERE uid='$uid' $levelsql ORDER BY lastupdate DESC ".Dxyz_DB::limit($start, $num));
	}
}

?>