<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_block_pic.php 27802 2012-02-15 02:34:36Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_block_pic extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_block_pic';
		$this->_pk    = 'picid';

		parent::__construct();
	}

	public function fetch_all_by_bid_itemid($bid, $itemid = array()) {
		return $bid ? Dxyz_DB::fetch_all('SELECT * FROM '.Dxyz_DB::table($this->_table).' WHERE '.Dxyz_DB::field('bid', $bid).($itemid ? ' AND '.Dxyz_DB::field('itemid', $itemid) : '')) : array();
	}

	public function insert_by_bid($bid, $data) {
		if($bid && $data && is_array($data)) {
			$data = daddslashes($data);
			$str = array();
			foreach($data as $value) {
				$str[] = "('$value[bid]', '$value[pic]', '$value[picflag]', '$value[type]')";
			}
			if($str) {
				Dxyz_DB::query('INSERT INTO '.Dxyz_DB::table($this->_table).' (bid, pic, picflag, `type`) VALUES '.implode(',', $str));
			}
		}
	}

	public function count_by_bid_pic($bid, $pic) {
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE bid=%d AND pic=%s', array($this->_table, $bid, $pic));
	}
}

?>