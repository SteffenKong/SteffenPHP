<?php
/**
 * Created by PhpStorm.
 * User: konghy
 * Date: 19-8-26
 * Time: 19-8-26
 */

/**
 * Class framework
 * 框架核心类
 */
class framework {

    /**
     * 项目启动类
     */
    public static function bootstrap() {
        self::initPath();
        self::register();
        self::route();
    }


    /**
     * 初始化路径常量
     */
    public static function initPath() {
        define('DS',DIRECTORY_SEPARATOR);
        define('ROOT',getcwd().DS);
        define('APPLICATION',ROOT.'application'.DS);
        define('FRAMEWORK',ROOT.'framework'.DS);
        define('PUBLIC',ROOT.'public'.DS);
        define('CORE',FRAMEWORK.'core'.DS);
        define('DATABASE',FRAMEWORK.'database'.DS);
        define('HELPERS',FRAMEWORK.'helpers'.DS);
        define('LIBRARIES',FRAMEWORK.'libraries'.DS);
        define('CONFIG',APPLICATION.'config'.DS);
        define('CONTROLLERS',APPLICATION.'controllers'.DS);
        define('MODELS',APPLICATION.'models'.DS);
        define('VIEWS',APPLICATION.'views'.DS);

        define('PLATFORM',isset($_GET['p'])?addslashes($_GET['p']):'admin');
        define('CLS',isset($_GET['c'])?addslashes($_GET['c']):'Index');
        define('ACTION',isset($_GET['a'])?addslashes($_GET['a']):'index');

        define('CUR_VIEWS',VIEWS.PLATFORM.DS);
        define('CUR_CONTROLLER',CONTROLLERS.PLATFORM.DS);

        //加载后台配置文件
        $GLOBALS['config']['admin'] = require_once CONFIG.'admin.php';

        //加载前台配置文件
        $GLOBALS['config']['index'] = require_once CONFIG.'index.php';

        //加载辅助函数库
        require_once HELPERS.'functions.php';
    }


    /**
     * 负责每个控制器的调度
     */
    public static function route() {
        require_once './framework/libraries/Loader.class.php';
        $controllerName = CLS.'Controller';
        $actionName = ACTION;
        $controllerObj = Loader::sigltion($controllerName);
        $response = call_user_func_array([$controllerObj,$actionName],[]);
        echo $response;
    }


    /**
     * @param $className
     * 自动加载文件
     */
    public static function autoloadFile($className) {
        $frameworkList = [
            'DB'=>DATABASE.$className.'.interface.php',
            'MysqlDB'=>DATABASE.$className.'.class.php',
            'PdoDB'=>DATABASE.$className.'.class.php',
            'Model'=>CORE.$className.'.class.php',
            'Controller'=>CORE.$className.'.class.php',
            'Captcha'=>CORE.$className.'.class.php',
            'Upload'=>CORE.$className.'.class.php',
            'Image'=>CORE.$className.'.class.php'
        ];

        if(isset($frameworkList[$className])) {
            require_once $frameworkList[$className];
        }elseif(substr($className,-10) == 'Controller') {
            require_once CUR_CONTROLLER.$className.'.class.php';
        }elseif(substr($className,-5) == 'Model') {
            require_once CUR_CONTROLLER.$className.'.class.php';
        }else {
            //不错处理
        }
    }


    /**
     * 注册自动加载
     */
    public static function register() {
        spl_autoload_register([__CLASS__,'autoloadFile']);
    }
}