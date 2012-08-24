<?php

/*
 * Dxyz V0.1 Beta
 */

require_once DISCUZ_ROOT . '/source/discuz_version.php';
$dzVersion = explode(' ', DISCUZ_VERSION);

require_once DISCUZ_ROOT . '/dxyz/init_db.php';
require_once DISCUZ_ROOT . '/dxyz/init_function.php';
if (defined('IN_ADMINCP') && $_G['gp_action'] == 'plugins' && isset($installlang)) {
    require_once DISCUZ_ROOT . '/dxyz/init_installlang.php';
}
?>