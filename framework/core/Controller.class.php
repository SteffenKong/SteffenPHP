<?php
/**
 * Created by PhpStorm.
 * User: konghy
 * Date: 19-8-27
 * Time: 19-8-27
 */

/**
 * Class Controller
 * 基类控制器
 */
class Controller {

    public function __construct()
    {
        $this->setHeader();
    }

    /**
     * 设置头部
     */
    protected function setHeader() {
        header('Content-type:text/html;charset=utf-8');
    }

    /**
     * @param $url
     * @param $message
     * @param $time
     * 返回操作成功
     */
    public function success($url,$message,$time) {
        echo "<script>alert(".$message.")</script>";
        header('refresh:'.$time.';url='.$url);
        exit;
    }


    /**
     * @param $url
     * @param $message
     * @param $time
     * 返回操作失败
     */
    public function fail($url,$message,$time) {
        echo "<script>alert(".$message.")</script>";
        header('refresh:'.$time.';url='.$url);
        exit;
    }


    /**
     * @param $url
     * 立即跳转
     */
    public function redirect($url) {
        header('Location:'.$url);
        exit;
    }


    /**
     * @param $code
     * @param $message
     * @param array $data
     * @param array $extra
     * @return false|string
     * 返回json格式数据
     */
    public function json($code,$message,$data = [],$extra = []) {
        $return = [
            'code'=>$code,
            'message'=>$message,
            'data'=>$data,
            'extra'=>$extra
        ];
        return json_encode($return);
    }
}