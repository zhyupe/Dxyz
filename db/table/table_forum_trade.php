<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_trade.php 27769 2012-02-14 06:29:36Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_trade extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_trade';
		$this->_pk    = '';

		parent::__construct();
	}
	public function fetch_all_thread_goods($tid, $pid = 0) {
		$pidsql = $pid ? ' AND '.Dxyz_DB::field('pid', $pid) : '';
		return Dxyz_DB::fetch_all("SELECT * FROM %t WHERE tid=%d $pidsql ORDER BY displayorder", array($this->_table, $tid));
	}
	public function fetch_counter_thread_goods($tid) {
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE tid=%d', array($this->_table, $tid));
	}
	public function fetch_all_for_seller($sellerid, $limit = 10, $tid = 0) {
		$tidsql = $tid ? ' AND '.Dxyz_DB::field('tid', $tid) : '';
		return Dxyz_DB::fetch_all("SELECT * FROM %t WHERE sellerid=%d $tidsql ORDER BY displayorder DESC LIMIT %d", array($this->_table, $sellerid, $limit));
	}
	public function fetch_first_goods($tid) {
		return Dxyz_DB::fetch_first('SELECT * FROM %t WHERE tid=%d ORDER BY displayorder DESC LIMIT 1', array($this->_table, $tid));
	}
	public function fetch_goods($tid, $pid, $orderby = '', $ascdesc = 'asc', $start = 0, $limit = 0) {
		if(empty($pid)) {
			return array();
		}
		if($tid) {
			$tidsql = Dxyz_DB::field('tid', $tid).' AND ';
		}
		if($orderby) {
			$ordersql = " ORDER BY ".Dxyz_DB::order($orderby, $ascdesc);
		}
		return Dxyz_DB::fetch_first("SELECT * FROM %t WHERE $tidsql ".Dxyz_DB::field('pid', $pid).$ordersql.Dxyz_DB::limit($start, $limit), array($this->_table));
	}
	public function fetch_all_statvars($fieldname, $limit = 10) {
		if(empty($fieldname)) {
			return array();
		}
		return Dxyz_DB::fetch_all("SELECT subject, tid, pid, seller, sellerid, SUM(%s) as %s
		FROM ".Dxyz_DB::table('forum_trade')."
		WHERE %s>0
		GROUP BY sellerid
		ORDER BY %s DESC ".Dxyz_DB::limit($limit), array($fieldname, $fieldname, $fieldname));
	}
	public function update_closed($expiration) {
		Dxyz_DB::query("UPDATE %t SET closed='1' WHERE expiration>0 AND expiration<%d", array($this->_table, $expiration));
	}
	public function check_goods($pid) {
		return Dxyz_DB::result_first('SELECT count(*) FROM %t WHERE pid=%d', array($this->_table, $pid));
	}
	public function update($tid, $pid, $data) {
		if(empty($data) || !is_array($data)) {
			return false;
		}
		Dxyz_DB::update('forum_trade', $data, array('tid' => $tid, 'pid' => $pid));
	}
	public function update_counter($tid, $pid, $items, $price, $credit, $amount = 0) {
		Dxyz_DB::query('UPDATE %t SET totalitems=totalitems+\'%d\', tradesum=tradesum+\'%d\', credittradesum=credittradesum+\'%d\', amount=amount+\'%d\' WHERE tid=%d AND pid=%d', array($this->_table, $items, $price, $credit, $amount, $tid, $pid));
	}
	public function delete_by_id_idtype($ids, $idtype) {
		if(empty($ids) || empty($idtype)) {
			return false;
		}
		Dxyz_DB::delete($this->_table, Dxyz_DB::field($idtype, $ids));
	}
	public function fetch_all_for_search($digestltd, $fids, $topltd, $sqlsrch, $start = 0, $limit = 0) {
		return Dxyz_DB::fetch_all("SELECT tr.tid, tr.pid, t.closed FROM ".Dxyz_DB::table('forum_trade')." tr INNER JOIN ".Dxyz_DB::table('forum_thread')." t ON tr.tid=t.tid AND $digestltd t.".Dxyz_DB::field('fid', $fids)." $topltd WHERE$sqlsrch ORDER BY tr.pid DESC".Dxyz_DB::limit($start, $limit));
	}
	public function fetch_all_for_space($wheresql, $ordersql, $count = 0, $start = 0, $limit = 0) {
		if(empty($wheresql)) {
			return array();
		}
		if($count) {
			return Dxyz_DB::result_first("SELECT COUNT(*) FROM ".Dxyz_DB::table('forum_trade')." t WHERE $wheresql");
		}
		if($ordersql && is_string($ordersql)) {
			$ordersql = ' ORDER BY '.$ordersql;
		}
		return Dxyz_DB::fetch_all("SELECT t.* FROM ".Dxyz_DB::table('forum_trade')." t
				INNER JOIN ".Dxyz_DB::table('forum_thread')." th ON t.tid=th.tid AND th.displayorder>='0'
				WHERE $wheresql $ordersql ".Dxyz_DB::limit($start, $limit));
	}
}

?>