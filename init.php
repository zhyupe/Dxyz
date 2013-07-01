<?php

/*
 * Dxyz V0.2 Beta
 */

define('DXYZ_ROOT', dirname(__FILE__));
define('IN_DXYZ', true);

require_once DXYZ_ROOT . '/../source/discuz_version.php';
$dzVersion = explode(' ', DISCUZ_VERSION);

require_once DXYZ_ROOT . '/init_core.php';
require_once DXYZ_ROOT . '/init_db.php';
require_once DXYZ_ROOT . '/init_function.php';

if (defined('IN_ADMINCP') && $_G['gp_action'] == 'plugins' && isset($installlang)) {
    require_once DXYZ_ROOT . '/init_installlang.php';
}
?>
