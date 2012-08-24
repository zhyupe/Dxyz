<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_member_count_archive.php 28589 2012-03-05 09:54:11Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_member_count_archive extends table_common_member_count
{
	public function __construct() {

		parent::__construct();
		$this->_table = 'common_member_count_archive';
		$this->_pk    = 'uid';
	}

	public function fetch($id){
		return ($id = dintval($id)) ? Dxyz_DB::fetch_first('SELECT * FROM '.Dxyz_DB::table($this->_table).' WHERE '.Dxyz_DB::field($this->_pk, $id)) : array();
	}

	public function fetch_all($ids) {
		$data = array();
		if(($ids = dintval($ids, true))) {
			$query = Dxyz_DB::query('SELECT * FROM '.Dxyz_DB::table($this->_table).' WHERE '.Dxyz_DB::field($this->_pk, $ids));
			while($value = Dxyz_DB::fetch($query)) {
				$data[$value[$this->_pk]] = $value;
			}
		}
		return $data;
	}

	public function delete($val, $unbuffered = false) {
		return ($val = dintval($val, true)) && Dxyz_DB::delete($this->_table, Dxyz_DB::field($this->_pk, $val), null, $unbuffered);
	}
}

?>