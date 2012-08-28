<?php

/*
 * Dxyz V0.1 Beta
 */

if(!defined('IN_DXYZ')) {
	exit('Access Denied');
}

switch ($dzVersion[0]) {
    case 'X1':
    case 'X1.5':
    case 'X1.5.1':
        $installlang = $installlang[$_G['gp_dir']];
        break;
    case 'X2':
    case 'X2.5':
    default:
        break;
}

?>
