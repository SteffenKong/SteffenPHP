<?php
/**
 * Class Image
 * 图片处理类
 */
class Image {

    private $imgObj;
    private $imagePath;
    private $imageInfo;
    private $errorMessage;

    public function __construct($config)
    {
        $this->imagePath = isset($config['imgPath']) ? $config['imgPath'] : '';
        $this->openImage();
    }

    /**
     * @return bool
     * 打开图片,并获取图片信息
     */
    public function openImage() {
        $imageDesc = @getimagesize($this->imagePath);
        if(!$imageDesc) {
            $this->setErrorMessage('图片疑伪造');
            return false;
        }

        $this->imageInfo = [
            'height'=>$imageDesc[1],
            'width'=>$imageDesc[0],
            'mime'=>$imageDesc['mime'],
            'ext'=>image_type_to_extension($imageDesc[2])
        ];

        $createFun = 'imagecreatefrom'.$this->imageInfo['ext'];
        $this->imgObj = $createFun($this->imagePath);
        return true;
    }

    /**
     * @param $width
     * @param $height
     * @return $this
     * 缩略图片
     */
    public function thumb($width,$height) {
        $imageThumb = imagecreatetruecolor($this->imageInfo['width'],$this->imageInfo['height']);
        imagecopyresampled($imageThumb,$this->imgObj,0,0,0,0,$width,$height,$this->imageInfo['width'],$this->imageInfo['height']);
        //销毁原图
        imagedestroy($this->imgObj);
        //将原图资源替换到缩略图资源
        $this->imgObj = $imageThumb;
        return $this;
    }


    /**
     * @param $waterImage
     * @param $x
     * @param $y
     * @param $waterX
     * @param $waterY
     * @param $alphaNum
     * @return $this|bool
     * 添加水印图片
     */
    public function addPicWater($waterImage,$x,$y,$waterX,$waterY,$alphaNum) {
        $imageDesc = @getimagesize($waterImage);
        if($imageDesc) {
            $this->setErrorMessage('水印图片疑伪造');
            return false;
        }
        $descWaterPicInfo = [
            'height'=>$imageDesc[1],
            'width'=>$imageDesc[0],
            'mime'=>$imageDesc['mime'],
            'ext'=>image_type_to_extension($imageDesc[2])
        ];
        $createWaterFun = 'imagecreatetrue'.$descWaterPicInfo['ext'];
        $waterImageObj = $createWaterFun($waterImage);

        imagecopymerge($this->imgObj,$waterImageObj,$x,$y,$waterX,$waterY,$descWaterPicInfo['width'],$descWaterPicInfo['height'],$alphaNum);
        return $this;
    }


    /**
     * @param $ttfPath
     * @param $content
     * @param $size
     * @param $angle
     * @param $offsetX
     * @param $offsetY
     * @param $alphaNum
     * @return $this
     * 添加文字水印
     */
    public function addTextWater($ttfPath,$content,$size,$angle,$offsetX,$offsetY,$alphaNum) {
        //水印文字的颜色
        $color = imagecolorallocatealpha($this->imgObj,255,255,255,$alphaNum);
        imagettftext($this->imgObj,$size,$angle,$offsetX,$offsetY,$color,$ttfPath,$content);
        return $this;
    }

    public function __destruct()
    {
        imagedestroy($this->imgObj);
    }

    /**
     * @param $message
     * @return bool
     * 设置错误信息
     */
    private function setErrorMessage($message) {
        $this->errorMessage = $message;
        return true;
    }

    /**
     * @return mixed
     * 获取错误信息
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }
}