<?php

/*
 * Dxyz V0.1 Beta
 */

switch ($dzVersion[0]) {
    case 'X1':
    case 'X1.5':
    case 'X1.5.1':
    case 'X2':
        require_once DXYZ_ROOT . '/db/discuz_base.php';
        require_once DXYZ_ROOT . '/db/discuz_database.php';
        require_once DXYZ_ROOT . '/db/discuz_table.php';
        require_once DXYZ_ROOT . '/db/discuz_table_archive.php';
        
        discuz_database::init();
        break;
    case 'X2.5':
    default:
        break;
}

class Dxyz_DB extends discuz_database {}
?>
