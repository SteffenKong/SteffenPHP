<?php
/**
 * Created by PhpStorm.
 * User: konghy
 * Date: 19-8-26
 * Time: 19-8-26
 */
class PdoDb implements DB {

    /**
     * @param $sql
     * @return mixed
     * 执行sql脚本
     */
    public function query($sql)
    {
        // TODO: Implement query() method.
    }

    /**
     * @param $sql
     * @return mixed
     * 执行插入语句
     */
    public function insert($sql)
    {
        // TODO: Implement insert() method.
    }

    /**
     * @param $sql
     * @return mixed
     * 执行删除语句
     */
    public function delete($sql)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $sql
     * @return mixed
     * 执行更新语句
     */
    public function update($sql)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $sql
     * @return mixed
     * 获取一条数据
     */
    public function fetchRow($sql)
    {
        // TODO: Implement fetchRow() method.
    }

    /**
     * @param $sql
     * @return mixed
     * 获取全部数据
     */
    public function fetchAll($sql)
    {
        // TODO: Implement fetchAll() method.
    }

    /**
     * @param $sql
     * @return mixed
     * 获取一列数据
     */
    public function fetchColumn($sql)
    {
        // TODO: Implement fetchColumn() method.
    }

    /**
     * @param $data
     * @return mixed
     * 防止sql注入攻击
     */
    public function Filter($data)
    {
        // TODO: Implement Filter() method.
    }
}