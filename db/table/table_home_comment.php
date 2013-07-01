<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_home_comment.php 31996 2012-10-30 06:15:14Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_home_comment extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_comment';
		$this->_pk    = 'cid';

		parent::__construct();
	}

	public function fetch_all_by_uid($uids, $start = 0, $limit = 5) {
		if(!$uids) {
			return null;
		}
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE uid IN (%n) OR authorid IN (%n) OR (id IN (%n) AND idtype=%s)  %i', array($this->_table, $uids, $uids, $uids, 'uid', Dxyz_DB::limit($start, $limit)));
	}

	public function delete_by_uid_idtype($uid) {
		if(!$uid){
			return null;
		}
		Dxyz_DB::delete($this->_table, Dxyz_DB::field('uid', $uid).' OR '.Dxyz_DB::field('authorid', $uid).' OR ('.Dxyz_DB::field('id', $uid).' AND idtype=\'uid\')');
	}

	public function delete_by_uid($uids) {
		if(!$uids){
			return null;
		}
		Dxyz_DB::delete($this->_table, Dxyz_DB::field('uid', $uids).' OR ('.Dxyz_DB::field('id', $uids).' AND idtype=\'uid\')');
	}

	public function delete($cid = '', $id = '', $idtype = '') {
		$condition = array();

		if($cid) {
			$condition[] = Dxyz_DB::field('cid', $cid);
		}

		if($id) {
			$condition[] = Dxyz_DB::field('id', $id);
			$condition[] = Dxyz_DB::field('idtype', $idtype);
		}

		if(!count($condition)) {
			return null;
		}

		Dxyz_DB::delete($this->_table, implode(' AND ', $condition));
	}

	public function update($cids, $data, $authorid = '') {
		$condition = array();
		if($cids) {
			$condition[] = Dxyz_DB::field('cid', $cids);
		}
		if($authorid) {
			$condition[] = Dxyz_DB::field('authorid', $authorid);
		}

		if(empty($data) || !is_array($data) || !count($condition)) {
			return null;
		}

		return Dxyz_DB::update($this->_table, $data, implode(' AND ', $condition));
	}

	public function count_by_id_idtype($id, $idtype, $cid = '') {
		if($cid) {
			$cidsql = Dxyz_DB::field('cid', $cid). ' AND ';
		}
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE '.$cidsql.' id=%d AND idtype=%s', array($this->_table, $id, $idtype));
	}

	public function fetch_all_by_id_idtype($id, $idtype, $start, $limit, $cid = '', $order = '') {
		if($cid) {
			$cidsql = Dxyz_DB::field('cid', $cid). ' AND ';
		}
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE '.$cidsql.' id=%d AND idtype=%s ORDER BY '.Dxyz_DB::order('dateline', $order).' %i', array($this->_table, $id, $idtype, Dxyz_DB::limit($start, $limit)));
	}

	public function fetch_latest_by_authorid($uid, $cid) {
		if($cid) {
			$cidsql = Dxyz_DB::field('cid', $cid). ' AND ';
		}
		return Dxyz_DB::fetch_first('SELECT * FROM %t WHERE '.$cidsql.' authorid=%d ORDER BY dateline DESC LIMIT 0,1', array($this->_table, $uid));
	}

	public function fetch_by_id_idtype($id, $idtype, $cid = '') {
		if($cid) {
			$cidsql = Dxyz_DB::field('cid', $cid). ' AND ';
		}
		return Dxyz_DB::fetch_first('SELECT * FROM %t WHERE '.$cidsql.' id=%d AND idtype=%s', array($this->_table, $id, $idtype));
	}

	public function fetch($cid, $authorid = '') {
		if(!$cid) {
			return null;
		}
		$wherearr = array();
		$wherearr[] = Dxyz_DB::field('cid', $cid);
		if($authorid) {
			$wherearr[] = Dxyz_DB::field('authorid', $authorid);
		}

		$wheresql = !empty($wherearr) && is_array($wherearr) ? ' WHERE '.implode(' AND ', $wherearr) : '';

		return Dxyz_DB::fetch_first('SELECT * FROM '.Dxyz_DB::table($this->_table).' '.$wheresql);
	}

	public function fetch_all_search($fetchtype, $ids, $authorid, $uids, $useip, $keywords, $idtype, $starttime, $endtime, $start, $limit, $basickeywords = 0) {
		$parameter = array($this->_table);
		$wherearr = array();
		if($ids) {
			$parameter[] = $ids;
			$wherearr[] = 'id IN(%n)';
		}
		if(is_array($authorid) && count($authorid)) {
			$parameter[] = $authorid;
			$wherearr[] = 'authorid IN(%n)';
		}
		if($idtype) {
			$parameter[] = $idtype;
			$wherearr[] = 'idtype=%s';
		}
		if($starttime) {
			$parameter[] = is_numeric($starttime) ? $starttime : strtotime($starttime);
			$wherearr[] = 'dateline>%d';
		}
		if($endtime) {
			$parameter[] = is_numeric($endtime) ? $endtime : strtotime($endtime);
			$wherearr[] = 'dateline<%d';
		}
		if($uids) {
			$parameter[] = $uids;
			$wherearr[] = 'uid IN(%n)';
		}
		if($keywords) {
			if(!$basickeywords) {
				$sqlkeywords = '';
				$or = '';
				$keywords = explode(',', str_replace(' ', '', $keywords));

				for($i = 0; $i < count($keywords); $i++) {
					if(preg_match("/\{(\d+)\}/", $keywords[$i])) {
						$keywords[$i] = preg_replace("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote($keywords[$i], '/'));
						$sqlkeywords .= " $or message REGEXP '".addslashes(stripsearchkey($keywords[$i]))."'";
					} else {
						$sqlkeywords .= " $or message LIKE '%".addslashes(stripsearchkey($keywords[$i]))."%'";
					}
					$or = 'OR';
				}
				$parameter[] = $sqlkeywords;
				$wherearr[] = '%i';
			} else {
				$parameter[] = '%'.$basickeywords.'%';
				$wherearr[] = 'message LIKE %s';
			}
		}
		if($useip) {
			$parameter[] = str_replace('*', '%', $useip);
			$wherearr[] = 'ip LIKE  %s';
		}
		if($fetchtype == 3) {
			$selectfield = "count(*)";
		} elseif ($fetchtype == 2) {
			$selectfield = "cid";
		} else {
			$selectfield = "*";
			$parameter[] = Dxyz_DB::limit($start, $limit);
			$ordersql = ' ORDER BY dateline DESC %i';
		}
		$wheresql = !empty($wherearr) && is_array($wherearr) ? ' WHERE '.implode(' AND ', $wherearr) : '';
		if(empty($wheresql)) {
			return null;
		}
		if($fetchtype == 3) {
			return Dxyz_DB::result_first("SELECT $selectfield FROM %t $wheresql", $parameter);
		} else {
			return Dxyz_DB::fetch_all("SELECT $selectfield FROM %t $wheresql $ordersql", $parameter);
		}
	}

}

?>