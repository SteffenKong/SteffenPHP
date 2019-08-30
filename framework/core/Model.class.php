<?php
/**
 * Created by PhpStorm.
 * User: konghy
 * Date: 19-8-26
 * Time: 19-8-26
 */

/**
 * Class Model
 * 模型类
 */
class Model {

    protected $dao;
    protected $table = 'users';
    protected $field = [];

    public function __construct()
    {
        $this->dao =  MysqlDB::getSigle($GLOBALS['config']['admin']['db']);
        $this->getFields();
    }


    /**
     * 设置列
     */
    private function getFields() {
        $sql = "DESC $this->table";
        $fields = $this->dao->fetchAll($sql);
        foreach ($fields ?? [] $key=>$value) {
            $this->field[] = $value;
            if($value['key'] == 'PRI') {
                $pk = $value['key'];
            }

            if (isset($pk)) {
                $this->field['pk'] = $pk;
            }
        }
    }


    /**
     * @return mixed
     * 获取所有数据
     */
    public function getAllData() {
        $sql = "select * from $this->table";
        return $this->dao->fetchAll($sql);
    }


    /**
     * @param array $data
     * @return mixed
     * 添加
     */
    public function add($data = []) {
        foreach ($data ?? [] as $key=>&$value) {
            $data[$key] = str_pad($value,0,"'");
            $data[$key] = str_pad($value,strlen($value),"'");
        }
        $fieldListStr = implode(',',array_keys($data));
        $valueListStr = implode(',',array_values($data));
        $sql = "INSERT INTO `{$this->table}`($fieldListStr) VALUES($valueListStr)";
        return $this->dao->insert($sql);
    }


    /**
     * @param array $data
     * @return mixed
     * 更新成功
     */
    public function update($data = []) {
        $upList = '';
        $where = '';
        foreach ($data ?? [] as $key=>$value) {
            if (in_array($value,$this->field)) {
                if($value == $this->field['pk']) {
                    $where = "$key = `'".$value."'`";
                }else {
                    $upList.= "$key = `'".$value."'`,";
                }
            }
        }
        $upList = rtrim($upList,',');
        $sql = "UPDATE `{$this->table}` SET $upList WHERE $where";
        return $this->dao->update($sql);
    }


    /**
     * @param $pk
     * @return mixed
     */
    public function delete($pk) {
        $where = '';
        $sql = '';
        if(is_array($pk)) {
            $sql = "DELETE FROM `{$this->table}` WHERE `{$this->field['pk']}` IN ('".implode(',',$pk)."')";
        }else {
            $sql = "DELETE FROM `{$this->table}` WHERE `{$this->field['pk']}` = $pk";
        }
        return $this->dao->delete($sql);
    }


    /**
     * @param $pk
     * @return mixed
     */
    public function selectByPk($pk) {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->field['pk']}` = ".$pk;
        return $this->dao->fetchRow($sql);
    }
}