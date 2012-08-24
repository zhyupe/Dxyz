<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_optionvalue.php 27738 2012-02-13 10:02:53Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_optionvalue extends discuz_table
{
	public function __construct() {

		$this->_table = '';
		$this->_pk    = '';

		parent::__construct();
	}

	public function create($sortid, $fields, $dbcharset) {
		if(!$sortid || !$fields || !$dbcharset) {
			return;
		}
		$sortid = intval($sortid);
		$this->_table = 'forum_optionvalue'.$sortid;
		$query = Dxyz_DB::query("SHOW TABLES LIKE '%t'", array($this->_table));
		if(Dxyz_DB::num_rows($query) != 1) {
			$create_table_sql = "CREATE TABLE ".Dxyz_DB::table($this->_table)." ($fields) TYPE=MyISAM;";
			$db = Dxyz_DB::object();
			$create_table_sql = $this->syntablestruct($create_table_sql, $db->version() > '4.1', $dbcharset);
			Dxyz_DB::query($create_table_sql);
		}
	}

	public function truncate($sortid) {
		if(!$sortid) {
			return;
		}
		$sortid = intval($sortid);
		$this->_table = 'forum_optionvalue'.$sortid;
		Dxyz_DB::query("TRUNCATE %t", array($this->_table));
	}

	public function showcolumns($sortid) {
		if(!$sortid) {
			return;
		}
		$sortid = intval($sortid);
		$this->_table = 'forum_optionvalue'.$sortid;
		$db = Dxyz_DB::object();
		if($db->version() > '4.1') {
			$query = Dxyz_DB::query("SHOW FULL COLUMNS FROM %t", array($this->_table), true);
		} else {
			$query = Dxyz_DB::query("SHOW COLUMNS FROM %t", array($this->_table), true);
		}
		$tables = array();
		while($field = @Dxyz_DB::fetch($query)) {
			$tables[$field['Field']] = 1;
		}
		return $tables;
	}

	public function alter($sortid, $sql) {
		if(!$sortid) {
			return;
		}
		$sortid = intval($sortid);
		$this->_table = 'forum_optionvalue'.$sortid;
		Dxyz_DB::query("ALTER TABLE %t %i", array($this->_table, $sql));
	}

	public function drop($sortid) {
		if(!$sortid) {
			return;
		}
		$sortid = intval($sortid);
		$this->_table = 'forum_optionvalue'.$sortid;
		Dxyz_DB::query("DROP TABLE IF EXISTS %t", array($this->_table));
	}

	public function syntablestruct($sql, $version, $dbcharset) {

		if(strpos(trim(substr($sql, 0, 18)), 'CREATE TABLE') === FALSE) {
			return $sql;
		}

		$sqlversion = strpos($sql, 'ENGINE=') === FALSE ? FALSE : TRUE;

		if($sqlversion === $version) {

			return $sqlversion && $dbcharset ? preg_replace(array('/ character set \w+/i', '/ collate \w+/i', "/DEFAULT CHARSET=\w+/is"), array('', '', "DEFAULT CHARSET=$dbcharset"), $sql) : $sql;
		}

		if($version) {
			return preg_replace(array('/TYPE=HEAP/i', '/TYPE=(\w+)/is'), array("ENGINE=MEMORY DEFAULT CHARSET=$dbcharset", "ENGINE=\\1 DEFAULT CHARSET=$dbcharset"), $sql);

		} else {
			return preg_replace(array('/character set \w+/i', '/collate \w+/i', '/ENGINE=MEMORY/i', '/\s*DEFAULT CHARSET=\w+/is', '/\s*COLLATE=\w+/is', '/ENGINE=(\w+)(.*)/is'), array('', '', 'ENGINE=HEAP', '', '', 'TYPE=\\1\\2'), $sql);
		}
	}

	public function fetch_all_tid($sortid, $where) {
		if(!$sortid) {
			return;
		}
		$sortid = intval($sortid);
		$this->_table = 'forum_optionvalue'.$sortid;
		$query = Dxyz_DB::query("SELECT tid FROM %t %i", array($this->_table, $where));
		$return = array();
		while($thread = Dxyz_DB::fetch($query)) {
			$return[] = $thread['tid'];
		}
		return $return;
	}

	public function update($sortid, $tid, $fid, $fields) {
		if(!$sortid || !$fields) {
			return;
		}
		$sortid = intval($sortid);
		$this->_table = 'forum_optionvalue'.$sortid;
		Dxyz_DB::query("UPDATE %t SET %i WHERE tid=%d AND fid=%d", array($this->_table, $fields, $tid, $fid));
	}

	public function insert($sortid, $fields, $replace = false) {
		if(!$sortid || !$fields) {
			return;
		}
		$sortid = intval($sortid);
		$this->_table = 'forum_optionvalue'.$sortid;
		Dxyz_DB::query("%i INTO %t %i", array(!$replace ? 'INSERT' : 'REPLACE', $this->_table, $fields));
	}

}

?>