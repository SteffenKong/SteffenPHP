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
    protected $table = '';
    protected $field = [];

    public function __construct()
    {
        $this->dao = Loader::sigltion(MysqlDB::class);
        $field = $this->getFields();
    }


    private function getFields() {
        $sql = "DESCRIPTION $this->table";
        $fields = $this->dao->fetchAll($sql);
    }
}