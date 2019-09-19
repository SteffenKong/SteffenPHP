<?php
class Captcha {

    private $fontSize;
    private $length;
    private $fontFile;
    private $width;
    private $height;
    private $pixel;
    private $line;
    private $snow;
    private $type;
    private $imgObj = null;


    /**
     * Captcha constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct($config = [])
    {
        $bool = $this->checkConfig($config);
        if(!$bool) {
            throw new Exception('验证码配置格式错误!!!');
        }
        $this->fontSize = isset($config['fontSize'])?$config['fontSize']:20;
        $this->length = isset($config['length'])?$config['length']:4;
        $this->fontFile = isset($config['fontFile'])?$config['fontFile']:'./fonts/angsai.ttf';
        $this->width = isset($config['width'])?$config['width']:130;
        $this->height = isset($config['height'])?$config['height']:40;
        $this->type = isset($config['type'])?$config['type']:'png';

        if(isset($config['pixel']) && $config['pixel']>0) {
            $this->pixel = $config['pixel'];
        }

        if(isset($config['snow']) && $config['snow']>0) {
            $this->snow = $config['snow'];
        }

        if(isset($config['line']) && $config['line']>0) {
            $this->line = $config['line'];
        }
    }


    /**
     * @param $config
     * @return bool
     * 检测配置
     */
    private function checkConfig($config) {
        if(!is_array($config)) {
            return false;
        }
        return true;
    }


    /**
     * @return false|int
     * 随机获取字体颜色
     */
    private function getRandColor() {
        return imagecolorallocate($this->imgObj,mt_rand(1,253),mt_rand(1,253),mt_rand(1,253));
    }

    /**
     * @return false|int
     * 随机获取黑白背景颜色
     */
    private function getRandBgColor() {
        return mt_rand(1,2) == 1 ? imagecolorallocate($this->imgObj,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255)):imagecolorallocate($this->imgObj,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
    }


    /**
     * 生成验证码图片
     */
    public function getCaptcha() {
        $this->imgObj = imagecreatetruecolor($this->width,$this->height);
        imagefilledrectangle($this->imgObj,0,0,$this->width,$this->height,$this->getRandBgColor());
        $angle = mt_rand(1,5);
        $code = $this->getRandCode();
        $x = ($this->width/$this->length)+2;
        $y = $this->height/1.5;
        $path = 'D:/all/web/PHPTutorial/WWW/SteffenPHP/framework/libraries/angsau.ttf';
        imagettftext($this->imgObj,$this->fontSize,$angle,$x,$y,$this->getRandColor(),$path,$code);


        if(!empty($this->snow)) {
            $this->setSnow();
        }

        if(!empty($this->line)) {
            $this->setLine();
        }

        if(!empty($this->pixel)) {
            $this->setPixel();
        }

        $this->output();
    }


    /**
     * @return string
     * 获取随机字符
     */
    private function getRandCode() {
        session_start();
        $randArr = array_merge(range(0,9),range('A','Z'),range('a','z'));
        $randIndex = array_rand($randArr,$this->length);
        shuffle($randIndex);
        $tempStr = '';
        foreach ($randIndex  as $index) {
            $tempStr .= $randArr[$index];
        }
        $_SESSION['code'] = $tempStr;
        return $tempStr;
    }

    private function setPixel() {
        for ($i=1;$i<=$this->pixel;$i++) {
            imagesetpixel($this->imgObj,mt_rand(0,$this->width),mt_rand(0,$this->height),$this->getRandColor());
        }
    }

    private function setLine() {
        for ($i=1;$i<=$this->line;$i++) {
            imageline($this->imgObj,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$this->getRandColor());
        }
    }

    private function setSnow() {
        for ($i=1;$i<=$this->snow;$i++) {
            imagestring($this->imgObj,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$this->getRandColor());
        }
    }

    /**
     * 输出验证码
     */
    public function output() {
        header('Content-type:image/'.$this->type);
        $type = 'image'.$this->type;
        $type($this->imgObj);
        imagedestroy($this->imgObj);
    }


    /**
     * @param $input
     * @return bool
     * 检测验证码
     */
    public function checkCaptcha($input) {
        if(strtolower($input) == strtolower($_SESSION['code'])) {
            $code = $_SESSION['code'];
            unset($_SESSION['code']);
            return true;
        }else {
            return false;
        }
    }
}
//$captcha = new Captcha([
//    'height'=>60,
//    'width'=>200,
//    'pixel'=>100,
//    'line'=>5,
//    'snow'=>5,
//    'fontSize'=>50,
//    'length'=>5
//]);
//$captcha->getCaptcha();