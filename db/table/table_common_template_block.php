<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_common_template_block.php 29445 2012-04-12 07:14:40Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_template_block extends discuz_table
{
	public function __construct() {

		$this->_table = 'common_template_block';
		$this->_pk    = '';

		parent::__construct();
	}

	public function delete_by_targettplname($tpl, $tpldirectory = NULL) {
		$add = $tpldirectory !== NULL ? ' AND '.Dxyz_DB::field('tpldirectory', $tpldirectory) : '';
		return $tpl ? Dxyz_DB::delete($this->_table, Dxyz_DB::field('targettplname', $tpl).$add) : false;
	}

	public function fetch_targettplname_by_bid($bid) {
		return ($bid = dintval($bid)) ? Dxyz_DB::result_first('SELECT targettplname FROM %t WHERE bid=%d', array($this->_table, $bid)) : '';
	}

	public function fetch_all_bid_by_targettplname_notinherited($tpl, $notinherited) {
		$bids = array();
		if($tpl) {
			$query = Dxyz_DB::query('SELECT tb.bid FROM %t tb LEFT JOIN %t b ON b.bid=tb.bid WHERE '.Dxyz_DB::field('targettplname', $tpl).' AND b.notinherited=%d', array($this->_table, 'common_block', $notinherited));
			while($value = Dxyz_DB::fetch($query)) {
				$bids[$value['bid']] = $value['bid'];
			}
		}
		return $bids;
	}

	public function fetch_by_bid($bid) {
		return ($bid = dintval($bid)) ? Dxyz_DB::fetch_first('SELECT * FROM '.Dxyz_DB::table($this->_table).' WHERE '.Dxyz_DB::field('bid', $bid)) : array();
	}

	public function fetch_all_by_bid($bids) {
		return ($bids = dintval($bids, true)) ? Dxyz_DB::fetch_all('SELECT * FROM '.Dxyz_DB::table($this->_table).' WHERE '.Dxyz_DB::field('bid', $bids), null, 'bid') : array();
	}

	public function fetch_all_by_targettplname($targettplname, $tpldirectory = NULL) {
		$add = ($tpldirectory !== NULL) ? ' AND '.Dxyz_DB::field('tpldirectory', $tpldirectory) : '';
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE targettplname=%s'.$add, array($this->_table, $targettplname), 'bid');
	}

	public function insert_batch($targettplname, $tpldirectory, $bids) {
		if($targettplname && ($bids = dintval($bids, true))) {
			$values = array();
			foreach ($bids as $bid) {
				if($bid) {
					$values[] = "('$targettplname','$tpldirectory', '$bid')";
				}
			}
			Dxyz_DB::query("INSERT INTO ".Dxyz_DB::table($this->_table)." (targettplname, tpldirectory, bid) VALUES ".implode(',', $values));
		}
	}
}

?>