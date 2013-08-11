<?php

/*
 * Dxyz V0.2 Beta
 */

if(!defined('IN_DXYZ')) {
	exit('Access Denied');
}

switch ($dzVersion[0]) {
    case 'X1':
    case 'X1.5':
    case 'X1.5.1':
    case 'X2':
        function dintval($int, $allowarray = false) {
            $ret = intval($int);
            if ($int == $ret || !$allowarray && is_array($int))
                return $ret;
            if ($allowarray && is_array($int)) {
                foreach ($int as &$v) {
                    $v = dintval($v, true);
                }
                return $int;
            } elseif ($int <= 0xffffffff) {
                $l = strlen($int);
                $m = substr($int, 0, 1) == '-' ? 1 : 0;
                if (($l - $m) === strspn($int, '0987654321', $m)) {
                    return $int;
                }
            }
            return $ret;
        }

        function currentlang() {
            $charset = strtoupper(CHARSET);
            if ($charset == 'GBK') {
                return 'SC_GBK';
            } elseif ($charset == 'BIG5') {
                return 'TC_BIG5';
            } elseif ($charset == 'UTF-8') {
                global $_G;
                if ($_G['config']['output']['language'] == 'zh_cn') {
                    return 'SC_UTF8';
                } elseif ($_G['config']['output']['language'] == 'zh_tw') {
                    return 'TC_UTF8';
                }
            } else {
                return '';
            }
        }
        
        function dxyz_input() {
            $_GET = dstripslashes($_GET);
            $_POST = dstripslashes($_POST);
            $_COOKIE = dstripslashes($_COOKIE);
        }
        break;
    case 'X2.5':
    case 'X3':
    default:
        function dxyz_input() { }
        break;
}

?>
