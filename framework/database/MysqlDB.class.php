<?php
/**
 * Created by PhpStorm.
 * User: konghy
 * Date: 19-8-26
 * Time: 19-8-26
 */

/**
 * Class MysqlDB
 * mysql工具类
 */
class MysqlDB implements DB {

    private $host;
    private $port;
    private $dbUser;
    private $dbPass;
    private $dbName;
    private $dbChar;
    private $dbPrefix;
    private $conn;
    private static $instance;


    private function __clone() {}


    public static function getSigle($config) {
        if(self::$instance instanceof self) {
            return self::$instance;
        }
        return self::$instance = new self($config);
    }

    private function __construct($config = [])
    {
        $this->host = isset($config['host'])?$config['host']:'';
        $this->port = isset($config['port'])?$config['port']:'';
        $this->dbUser = isset($config['dbUser'])?$config['dbUser']:'';
        $this->dbPass = isset($config['dbPass'])?$config['dbPass']:'';
        $this->dbName = isset($config['dbName'])?$config['dbName']:'';
        $this->dbChar = isset($config['dbChar'])?$config['dbChar']:'';
        $this->dbPrefix = isset($config['dbPrefix'])?$config['dbPrefix']:'';
        try{
            $this->conn = $this->dbConnect();
            $this->setChar($this->dbChar);
        }catch (Exception $e) {
            echo $e->getMessage().'<br/>';
            echo $e->getLine().'<br/>';
            echo $e->getFile().'<br/>';
        }
    }


    /**
     * @return false|mysqli
     * @throws Exception
     */
    public function dbConnect() {
        $link = mysqli_connect($this->host,$this->dbUser,$this->dbPass,$this->dbName,$this->port);
        if(!$link) {
            throw new Exception('mysql连接失败');
        }

        return $link;
    }


    /**
     * @param $dbChar
     * 设置数据库编码
     */
    public function setChar($dbChar) {
        mysqli_query($this->conn,'set names '.$dbChar);
    }


    /**
     * @param $sql
     * @return mixed
     * 执行sql脚本
     */
    public function query($sql)
    {
        $res = mysqli_query($this->conn,$sql);
        if(!$res) {
           echo '<table>';
                echo '<tr>';
                    echo '<td>错误编码</td>';
                    echo '<td>'.mysqli_errno($this->conn).'</td>';
                echo '</tr>';

                echo '<tr>';
                    echo '<td>错误信息</td>';
                    echo '<td>'.mysqli_error($this->conn).'</td>';
                echo '</tr>';

                echo '<tr>';
                    echo '<td>错误语句</td>';
                    echo '<td>'.$sql.'</td>';
                echo '</tr>';
           echo '</table>';
            return false;
        }
        return $res;
    }


    /**
     * @param $sql
     * @return mixed
     * 执行插入语句
     */
    public function insert($sql)
    {
        return $this->query($sql);
    }

    /**
     * @param $sql
     * @return mixed
     * 执行删除语句
     */
    public function delete($sql)
    {
       return $this->query($sql);
    }

    /**
     * @param $sql
     * @return mixed
     * 执行更新语句
     */
    public function update($sql)
    {
        return $this->query($sql);
    }

    /**
     * @param $sql
     * @return mixed
     * 获取一条数据
     */
    public function fetchRow($sql)
    {
        $res = $this->query($sql);
        if(!$res) {
            return false;
        }
        return mysqli_fetch_assoc($res);
    }

    /**
     * @param $sql
     * @return mixed
     * 获取全部数据
     */
    public function fetchAll($sql)
    {
        $res = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_array($res)) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * @param $sql
     * @return mixed
     * 获取一列数据
     */
    public function fetchColumn($sql)
    {
        $res = $this->query($sql);
        if(!$res) {
            return false;
        }
        $row = mysqli_fetch_row($res);
        if(empty($row)) {
            return [];
        }
        return (string) $row[0];
    }

    /**
     * @param $data
     * @return mixed
     * 防止sql注入攻击
     */
    public function Filter($data)
    {
        return "'".mysqli_real_escape_string($this->conn,$data)."'";
    }
}