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

        class C {

            private static $_tables;
            private static $_imports;

            public static function t($name) {
                $pluginid = null;
                if ($name[0] === '#') {
                    list(, $pluginid, $name) = explode('#', $name);
                }
                $classname = 'table_' . $name;
                if (!isset(self::$_tables[$classname])) {
                    if (!class_exists($classname, false)) {
                        self::import(($pluginid ? '/../source/plugin/' . $pluginid : '/db') . '/table/' . $name);
                    }
                    self::$_tables[$classname] = new $classname;
                }
                return self::$_tables[$classname];
            }

            public static function import($name, $folder = '', $force = true) {
                $key = $folder . $name;
                if (!isset(self::$_imports[$key])) {
                    $path = DXYZ_ROOT . $folder;
                    if (strpos($name, '/') !== false) {
                        $pre = basename(dirname($name));
                        $filename = dirname($name) . '/' . $pre . '_' . basename($name) . '.php';
                    } else {
                        $filename = $name . '.php';
                    }

                    if (is_file($path . '/' . $filename)) {
                        self::$_imports[$key] = true;
                        return include $path . '/' . $filename;
                    } elseif (!$force) {
                        return false;
                    } else {
                        throw new Exception('Oops! System file lost: ' . $filename);
                    }
                }
                return true;
            }

            public function app() {
                $discuz = & discuz_core::instance();
                return $discuz;
            }

        }

        break;
    case 'X2.5':
    case 'X3':
    default:
        break;
}
?>
