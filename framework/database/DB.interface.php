<?php
/**
 * Created by PhpStorm.
 * User: konghy
 * Date: 19-8-26
 * Time: 19-8-26
 */

/**
 * Interface DB
 * 数据库接口
 */
interface DB {

    /**
     * @param $sql
     * @return mixed
     * 执行sql脚本
     */
    public function query($sql);

    /**
     * @param $sql
     * @return mixed
     * 执行插入语句
     */
    public function insert($sql);

    /**
     * @param $sql
     * @return mixed
     * 执行删除语句
     */
    public function delete($sql);

    /**
     * @param $sql
     * @return mixed
     * 执行更新语句
     */
    public function update($sql);

    /**
     * @param $sql
     * @return mixed
     * 获取一条数据
     */
    public function fetchRow($sql);

    /**
     * @param $sql
     * @return mixed
     * 获取全部数据
     */
    public function fetchAll($sql);

    /**
     * @param $sql
     * @return mixed
     * 获取一列数据
     */
    public function fetchColumn($sql);


    /**
     * @param $data
     * @return mixed
     * 防止sql注入攻击
     */
    public function Filter($data);
}