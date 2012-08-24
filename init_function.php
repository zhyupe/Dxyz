<?php

/*
 * Dxyz V0.1 Beta
 */

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

        break;
    case 'X2.5':
    default:
        break;
}

?>
