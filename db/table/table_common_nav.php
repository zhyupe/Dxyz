<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_nav.php 27972 2012-02-20 01:49:49Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_nav extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_nav';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function fetch_by_id_navtype($id, $navtype) {
		return Dxyz_DB::fetch_first('SELECT * FROM %t WHERE id=%d AND navtype=%d', array($this->_table, $id, $navtype));
	}

	public function fetch_by_type_identifier($type, $identifier) {
		return Dxyz_DB::fetch_first('SELECT * FROM %t WHERE type=%d AND identifier=%s', array($this->_table, $type, $identifier));
	}

	public function fetch_all_by_navtype($navtype = null) {
		$parameter = array($this->_table);
		$wheresql = '';
		if($navtype !== null) {
			$parameter[] = $navtype;
			$wheresql = ' WHERE navtype=%d';
		}
		return Dxyz_DB::fetch_all('SELECT * FROM %t '.$wheresql.' ORDER BY displayorder', $parameter, $this->_pk);
	}

	public function fetch_all_by_navtype_parentid($navtype, $parentid) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE navtype=%d AND parentid=%d ORDER BY displayorder', array($this->_table, $navtype, $parentid), $this->_pk);
	}
	public function fetch_all_mainnav() {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE navtype=0 AND (available=1 OR type=0) AND parentid=0 ORDER BY displayorder', array($this->_table), $this->_pk);
	}
	public function fetch_all_subnav($parentid) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE navtype=0 AND parentid=%d AND available=1 ORDER BY displayorder', array($this->_table, $parentid), $this->_pk);
	}
	public function fetch_all_by_navtype_type_identifier($navtype, $type, $identifier) {
		$navtype = dintval($navtype, true);
		$type = dintval($type, true);
		if($navtype && $type) {
			$wherearr[] = Dxyz_DB::field('navtype', $navtype);
			$wherearr[] = Dxyz_DB::field('type', $type);
			$wherearr[] = Dxyz_DB::field('identifier', $identifier);
			return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE %i', array($this->_table, implode(' AND ', $wherearr)), 'identifier');
		}
		return array();
	}

	public function update_by_identifier($identifier, $data) {
		if(is_array($identifier) && empty($identifier)) {
			return 0;
		}
		if(!empty($data) && is_array($data)) {
			return Dxyz_DB::update($this->_table, $data, Dxyz_DB::field('identifier', $identifier));
		}
		return 0;
	}

	public function update_by_navtype_type_identifier($navtype, $type, $identifier, $data) {
		if(!empty($data) && is_array($data)) {
			$navtype = dintval($navtype, true);
			$type = dintval($type, true);
			if(is_array($navtype) && empty($navtype) || is_array($type) && empty($type) || is_array($identifier) && empty($identifier)) {
				return 0;
			}
			$wherearr[] = Dxyz_DB::field('navtype', $navtype);
			$wherearr[] = Dxyz_DB::field('type', $type);
			$wherearr[] = Dxyz_DB::field('identifier', $identifier);
			return Dxyz_DB::update($this->_table, $data, implode(' AND ', $wherearr));
		}
		return 0;
	}
	public function update_by_type_identifier($type, $identifier, $data) {
		$type = dintval($type, is_array($type) ? true : false);
		if(is_array($identifier) && empty($identifier)) {
			return 0;
		}
		if(!empty($data) && is_array($data)) {
			return Dxyz_DB::update($this->_table, $data, Dxyz_DB::field('type', $type).' AND '.Dxyz_DB::field('identifier', $identifier));
		}
		return 0;
	}

	public function delete_by_navtype_id($navtype, $ids) {
		$ids = dintval($ids, is_array($ids) ? true : false);
		$navtype = dintval($navtype, is_array($navtype) ? true : false);
		if($ids) {
			return Dxyz_DB::delete($this->_table, Dxyz_DB::field('id', $ids).' AND '.Dxyz_DB::field('navtype', $navtype));
		}
		return 0;
	}
	public function delete_by_navtype_parentid($navtype, $parentid) {
		$navtype = dintval($navtype, is_array($navtype) ? true : false);
		$parentid = dintval($parentid, is_array($parentid) ? true : false);
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('navtype', $navtype).' AND '.Dxyz_DB::field('parentid', $parentid));
	}

	public function delete_by_type_identifier($type, $identifier) {
		if(is_array($identifier) && empty($identifier)) {
			return 0;
		}
		$type = dintval($type, is_array($type) ? true : false);
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('type', $type).' AND '.Dxyz_DB::field('identifier', $identifier));
	}
	public function delete_by_parentid($id) {
		$id = dintval($id, is_array($id) ? true : false);
		if($id) {
			return Dxyz_DB::delete($this->_table, Dxyz_DB::field('parentid', $id));
		}
		return 0;
	}
	public function count_by_navtype_type_identifier($navtype, $type, $identifier) {
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE navtype=%d AND type=%d AND identifier=%s', array($this->_table, $navtype, $type, $identifier));
	}

}

?>