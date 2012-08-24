<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_forum_postcomment.php 29123 2012-03-27 06:00:56Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_postcomment extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_postcomment';
		$this->_pk    = 'id';

		parent::__construct();
	}

	public function count_by_authorid($authorid) {
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE authorid=%d', array($this->_table, $authorid));
	}

	public function count_by_pid($pid, $authorid = null, $score = null) {
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE pid=%d '.($authorid ? ' AND '.Dxyz_DB::field('authorid', $authorid) : null).($score ? ' AND '.Dxyz_DB::field('score', $score) : null), array($this->_table, $pid, $authorid, $score));
	}

	public function count_by_search($tid = null, $pid = null, $authorid = null, $starttime = null, $endtime = null, $ip = null, $message = null) {
		$sql = '';
		$tid && $sql .= ' AND '.Dxyz_DB::field('tid', $tid);
		$pid && $sql .= ' AND '.Dxyz_DB::field('pid', $pid);
		$authorid && $sql .= ' AND '.Dxyz_DB::field('authorid', $authorid);
		$starttime && $sql .= ' AND '.Dxyz_DB::field('dateline', $starttime, '>=');
		$endtime && $sql .= ' AND '.Dxyz_DB::field('dateline', $endtime, '<');
		$ip && $sql .= ' AND '.Dxyz_DB::field('useip', str_replace('*', '%', $ip), 'like');
		if($message) {
			$sqlmessage = '';
			$or = '';
			$message = explode(',', str_replace(' ', '', $message));

			for($i = 0; $i < count($message); $i++) {
				if(preg_match("/\{(\d+)\}/", $message[$i])) {
					$message[$i] = preg_replace("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote($message[$i], '/'));
					$sqlmessage .= " $or comment REGEXP '".$message[$i]."'";
				} else {
					$sqlmessage .= " $or ".Dxyz_DB::field('comment', '%'.$message[$i].'%', 'like');
				}
				$or = 'OR';
			}
			$sql .= " AND ($sqlmessage)";
		}
		return Dxyz_DB::result_first('SELECT COUNT(*) FROM %t WHERE authorid>-1 %i', array($this->_table, $sql));
	}

	public function fetch_all_by_search($tid = null, $pid = null, $authorid = null, $starttime = null, $endtime = null, $ip = null, $message = null, $start = null, $limit = null) {
		$sql = '';
		$tid && $sql .= ' AND '.Dxyz_DB::field('tid', $tid);
		$pid && $sql .= ' AND '.Dxyz_DB::field('pid', $pid);
		$authorid && $sql .= ' AND '.Dxyz_DB::field('authorid', $authorid);
		$starttime && $sql .= ' AND '.Dxyz_DB::field('dateline', $starttime, '>=');
		$endtime && $sql .= ' AND '.Dxyz_DB::field('dateline', $endtime, '<');
		$ip && $sql .= ' AND '.Dxyz_DB::field('useip', str_replace('*', '%', $ip), 'like');
		if($message) {
			$sqlmessage = '';
			$or = '';
			$message = explode(',', str_replace(' ', '', $message));

			for($i = 0; $i < count($message); $i++) {
				if(preg_match("/\{(\d+)\}/", $message[$i])) {
					$message[$i] = preg_replace("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote($message[$i], '/'));
					$sqlmessage .= " $or comment REGEXP '".$message[$i]."'";
				} else {
					$sqlmessage .= " $or ".Dxyz_DB::field('comment', '%'.$message[$i].'%', 'like');
				}
				$or = 'OR';
			}
			$sql .= " AND ($sqlmessage)";
		}
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE authorid>-1 %i ORDER BY dateline DESC '.Dxyz_DB::limit($start, $limit), array($this->_table, $sql));
	}

	public function fetch_all_by_authorid($authorid, $start, $limit) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE authorid=%d ORDER BY dateline DESC '.Dxyz_DB::limit($start, $limit), array($this->_table, $authorid));
	}

	public function fetch_all_by_pid($pids) {
		if(empty($pids)) {
			return array();
		}
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE '.Dxyz_DB::field('pid', $pids).' ORDER BY dateline DESC', array($this->_table));
	}

	public function fetch_all_by_pid_score($pid, $score) {
		return Dxyz_DB::fetch_all('SELECT * FROM %t WHERE pid=%d AND score=%d', array($this->_table, $pid, $score));
	}

	public function fetch_standpoint_by_pid($pid) {
		return Dxyz_DB::fetch_first('SELECT * FROM %t WHERE pid=%d AND authorid=-1', array($this->_table, $pid));
	}

	public function update_by_pid($pids, $data, $unbuffered = false, $low_priority = false, $authorid = null) {
		if(empty($data)) {
			return false;
		}
		$where = array();
		$where[] = Dxyz_DB::field('pid', $pids);
		$authorid !== null && $where[] = Dxyz_DB::field('authorid', $authorid);
		return Dxyz_DB::update($this->_table, $data, implode(' AND ', $where), $unbuffered, $low_priority);
	}

	public function delete_by_authorid($authorids, $unbuffered = false, $rpid = false) {
		if(empty($authorids)) {
			return false;
		}
		$where = array();
		$where[] = Dxyz_DB::field('authorid', $authorids);
		$rpid && $where[] = Dxyz_DB::field('rpid', 0, '>');
		return Dxyz_DB::delete($this->_table, implode(' AND ', $where), null, $unbuffered);
	}

	public function delete_by_tid($tids, $unbuffered = false, $authorids = null) {
		$where = array();
		$where[] = Dxyz_DB::field('tid', $tids);
		$authorids !== null && !(is_array($authorids) && empty($authorids)) && $where[] = Dxyz_DB::field('authorid', $authorids);
		return Dxyz_DB::delete($this->_table, implode(' AND ', $where), null, $unbuffered);
	}

	public function delete_by_pid($pids, $unbuffered = false, $authorid = null) {
		$where = array();
		$where[] = Dxyz_DB::field('pid', $pids);
		$authorid !== null && !(is_array($authorid) && empty($authorid)) && $where[] = Dxyz_DB::field('authorid', $authorid);
		return Dxyz_DB::delete($this->_table, implode(' AND ', $where), null, $unbuffered);
	}

	public function delete_by_rpid($rpids, $unbuffered = false) {
		if(empty($rpids)) {
			return false;
		}
		return Dxyz_DB::delete($this->_table, Dxyz_DB::field('rpid', $rpids), null, $unbuffered);
	}

	public function fetch_postcomment_by_pid($pids, &$postcache, &$commentcount, &$totalcomment, $commentnumber) {
		$query = Dxyz_DB::query("SELECT * FROM ".Dxyz_DB::table('forum_postcomment')." WHERE pid IN (".dimplode($pids).') ORDER BY dateline DESC');
		$commentcount = $comments = array();
		while($comment = Dxyz_DB::fetch($query)) {
			if($comment['authorid'] > '-1') {
				$commentcount[$comment['pid']]++;
			}
			if(count($comments[$comment['pid']]) < $commentnumber && $comment['authorid'] > '-1') {
				$comment['avatar'] = avatar($comment['authorid'], 'small');
				$comment['comment'] = str_replace(array('[b]', '[/b]', '[/color]'), array('<b>', '</b>', '</font>'), preg_replace("/\[color=([#\w]+?)\]/i", "<font color=\"\\1\">", $comment['comment']));
				$comments[$comment['pid']][] = $comment;
			}
			if($comment['authorid'] == '-1') {
				$cic = 0;
				$totalcomment[$comment['pid']] = preg_replace('/<i>([\.\d]+)<\/i>/e', "'<i class=\"cmstarv\" style=\"background-position:20px -'.(intval(\\1) * 16).'px\">'.sprintf('%1.1f', \\1).'</i>'.(\$cic++ % 2 ? '<br />' : '');", $comment['comment']);
			}
			$postcache[$comment['pid']]['comment']['count'] = $commentcount[$comment['pid']];
			$postcache[$comment['pid']]['comment']['data'] = $comments[$comment['pid']];
			$postcache[$comment['pid']]['comment']['totalcomment'] = $totalcomment[$comment['pid']];
		}
		return $comments;
	}

}

?>