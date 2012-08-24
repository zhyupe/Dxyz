<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_diy_data.php 27827 2012-02-15 07:03:43Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_diy_data extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_diy_data';
		$this->_pk    = '';

		parent::__construct();
	}

	public function fetch($targettplname, $tpldirectory) {
		return !empty($targettplname) ? Dxyz_DB::fetch_first('SELECT * FROM '.Dxyz_DB::table($this->_table).' WHERE '.Dxyz_DB::field('targettplname', $targettplname).' AND '.Dxyz_DB::field('tpldirectory', $tpldirectory)) : array();
	}

	public function delete($targettplname, $tpldirectory = null) {
		foreach($this->fetch_all($targettplname, $tpldirectory) as $value) {
			$file = ($value['tpldirectory'] ? $value['tpldirectory'].'/' : '').$value['targettplname'];
			@unlink(DISCUZ_ROOT.'./data/diy/'.$file.'.htm');
			@unlink(DISCUZ_ROOT.'./data/diy/'.$file.'.htm.bak');
			@unlink(DISCUZ_ROOT.'./data/diy/'.$file.'_diy_preview.htm');
		}
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('targettplname', $targettplname).($tpldirectory !== null ? ' AND '.Dxyz_DB::field('tpldirectory', $tpldirectory) : ''));
	}

	public function update($targettplname, $tpldirectory, $data) {
		if(!empty($targettplname) && !empty($data) && is_array($data)) {
			return Dxyz_DB::update($this->_table, $data, Dxyz_DB::field('targettplname', $targettplname).' AND '.Dxyz_DB::field('tpldirectory', $tpldirectory));
		}
		return false;
	}

	public function fetch_all($targettplname, $tpldirectory = null) {
		return !empty($targettplname) ? Dxyz_DB::fetch_all('SELECT * FROM '.Dxyz_DB::table($this->_table).' WHERE '.Dxyz_DB::field('targettplname', $targettplname).($tpldirectory !== null ? ' AND '.Dxyz_DB::field('tpldirectory', $tpldirectory) : '')) : array();
	}

	public function count_by_where($wheresql) {
		$wheresql = $wheresql ? ' WHERE '.$wheresql : '';
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM '.Dxyz_DB::table($this->_table).$wheresql);
	}

	public function fetch_all_by_where($wheresql, $ordersql, $start, $limit) {
		$wheresql = $wheresql ? ' WHERE '.$wheresql : '';
		return Dxyz_DB::fetch_all('SELECT * FROM '.Dxyz_DB::table($this->_table).$wheresql.' '.$ordersql.Dxyz_DB::limit($start, $limit), null, $this->_pk ? $this->_pk : '');
	}
}

?>