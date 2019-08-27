<?php
/**
 * Created by PhpStorm.
 * User: konghy
 * Date: 19-8-26
 * Time: 19-8-26
 */

/**
 * Class Loader
 * 工厂工具类
 */
class Loader {

    protected static $instanceClass = [];

    /**
     * @param $className
     * @return mixed
     * 单例模式
     */
    public static function sigltion($className) {
        if(!isset(self::$instanceClass[$className])) {
            self::$instanceClass[$className] = new $className;
        }

        return self::$instanceClass[$className];
    }
}