<?php
/**
 * Created by PhpStorm.
 * User: konghy
 * Date: 19-8-26
 * Time: 19-8-26
 */


//辅助函数库

/**
 * 调试函数
 */
if(!function_exists('dd')) {
    function dd() {
        $args = func_get_args();
        foreach ($args as $k=>$v) {
            echo '<pre/>';
            var_dump($v);
            echo '<hr/>';
        }
        return true;
    }
}