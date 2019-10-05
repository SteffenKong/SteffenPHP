<?php

/**
 * Class Upload
 * 文件上传类
 */
class Upload {

    private $filePath;
    private $allowMime;
    private $allowExt;
    private $imageFlag;
    private $errorMessage;
    private $size;
    private $ext;

    public function __construct($config)
    {
        $this->filePath = isset($config['filePath']) ? $config['filePath']:'';
        $this->allowMime = isset($config['allowMime']) ? $config['allowMime']:[];
        $this->allowExt = isset($config['allowExt']) ? $config['allowExt']:[];
        $this->imageFlag = isset($config['imageFlag']) ? $config['imageFlag']:false;
        $this->size = isset($config['size']) ? $config['size']:1024*1024;
    }

    /**
     * @param $file
     * @return bool
     * 检测是否有错误信息
     */
    private function checkFileError($file) {
        $errorFlag = true;
        switch ($file['error']) {
            case 1:
                $this->setErrorMessage('上传文件的超过了php配置文件中的upload_max_filesize选项的值');
                $errorFlag = false;
                break;
            case 2:
                $this->setErrorMessage('超过了表单的max_file_size限制的大小');
                $errorFlag = false;
                break;
            case 3:
                $this->setErrorMessage('文件部分被上传');
                $errorFlag = false;
                break;
            case 4:
                $this->setErrorMessage('没有选择上传文件');
                $errorFlag = false;
                break;
            case 6:
                $this->setErrorMessage('没有找到临时目录');
                $errorFlag = false;
                break;
            case 7:
                $this->setErrorMessage('文件目录不可写');
                $errorFlag = false;
                break;
        }
        return $errorFlag;
    }

    /**
     * @param $file
     * @return bool
     * 检测文件Mime类型
     */
    private function checkFileMime($file) {
        $fInfo = new finfo();
        $mimeType = $fInfo->file($file['tmp_name'],FILEINFO_MIME_TYPE);
        if(!in_array($mimeType,$this->allowMime)) {
            $this->setErrorMessage('mime类型非法');
            return false;
        }
        return true;
    }


    /**
     * @param $file
     * @return bool
     * 检测文件后缀
     */
    private function checkFileExt($file) {
        $ext = strrchr($file['name'],'.');
        $this->allowExt = array_map(function($currentExt) {
            return str_pad($currentExt,'1','.',STR_PAD_LEFT);
        },$this->allowExt);

        if(!in_array($ext,$this->allowExt)) {
            $this->setErrorMessage('文件后缀类型非法');
            return false;
        }
        $this->ext = $ext;
        return true;
    }


    /**
     * @param $file
     * @return bool
     * 检测文件大小范围
     */
    private function checkFileSize($file) {
        if($file['size']>$this->size) {
            $this->setErrorMessage('文件大小超出');
            return false;
        }
        return true;
    }


    /**
     * @param $file
     * @return bool
     * 检测文件上传是否为HTTP上传方式
     */
    private function checkIsHttpPost($file) {
        if(!is_uploaded_file($file['tmp_name'])) {
            $this->setErrorMessage('文件大小超出');
            return false;
        }
        return true;
    }


    /**
     * @param $file
     * @param $newName
     * @return bool|string
     * 上传单个文件
     */
    public function upFile($file,$newName = '') {
        $dd = $this->checkFileMime($file);

        if(!$this->checkFileError($file) || !$this->checkFileSize($file) || !$this->checkFileExt($file) || !$this->checkFileMime($file) || !$this->checkIsHttpPost($file)) {
            return false;
        }
        //检测上传的路径是否存在
        $this->checkUploadPathOrCreate();

        if($newName == '') {
            $newName = $this->filePath.'/'.md5(md5(uniqid('upload_',true))).$this->ext;
        }else {
            $newName = $this->filePath.'/'.$newName;
        }


        if(!move_uploaded_file($file['tmp_name'],$newName)) {
            return false;
        }
        return $newName;
    }


    public function upFiles($files) {
        $tmpFile = [];
        foreach ($files['name'] ?? [] as $key=>$file) {
            $tmpFile = [
                'type'=>$files[$key]['type'],
                'name'=>$files[$key]['name'],
                'size'=>$files[$key]['size'],
                'error'=>$files[$key]['error'],
                'tmp_name'=>$files[$key]['tmp_name'],
            ];
            $this->upFile($tmpFile,'');
        }
    }


    /**
     * @return bool
     * 检测上传的路径是否存在,没有则创建
     */
    public function checkUploadPathOrCreate() {
        if(!file_exists($this->filePath)) {
            mkdir($this->filePath);
            chmod($this->filePath,0777);
        }
        return true;
    }

    public function getErrorMessage() {
        return $this->errorMessage.'<br/>';
    }


    /**
     * @param string $errorMessage
     * @return bool
     * 设置错误信息
     */
    private function setErrorMessage($errorMessage = '') {
        $this->errorMessage = $errorMessage;
        return true;
    }
}

$upload = new Upload([
    'filePath'=>'./upload',
    'size'=>10240*1024,
    'allowExt'=>['.jpeg','.gif','.jpg','png'],
    'allowMime'=>['image/png','image/gif','image/jpg','image/jpeg'],
    'imageFlag'=>true
]);
$res = $upload->upFile($_FILES['image'],'');

if(!$res) {
    echo $upload->getErrorMessage();
}

