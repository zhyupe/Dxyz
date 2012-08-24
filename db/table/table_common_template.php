<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_template.php 27745 2012-02-14 01:43:38Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_template extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_template';
		$this->_pk    = 'templateid';

		parent::__construct();
	}

	public function fetch_all_data() {
		return Dxyz_DB::fetch_all('SELECT * FROM %t', array($this->_table));
	}

	public function delete($val) {
		if(!$val) {
			return;
		}
		Dxyz_DB::query("DELETE FROM %t WHERE %i AND templateid<>1", array($this->_table, Dxyz_DB::field('templateid', $val)));
	}

	public function get_templateid($name) {
		return Dxyz_DB::result_first("SELECT templateid FROM %t WHERE name=%s", array($this->_table, $name));
	}

}

?>