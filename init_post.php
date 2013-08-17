<?php

/*
 * Dxyz V0.2 Beta
 */

if (!defined('IN_DXYZ')) {
    exit('Access Denied');
}

switch ($dzVersion[0]) {
    case 'X1':
    case 'X1.5':
    case 'X1.5.1':
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
            $_GET = array_merge($_GET, $_POST);
        }
        break;
    case 'X2':
    case 'X2.5':
    case 'X3':
    default:
        break;
}